<?php
App::import('Vendor','PHPExcel',array('file' => 'excel/PHPExcel.php'));
App::import('Vendor','PHPExcelWriter',array('file' => 'excel/PHPExcel/Writer/Excel5.php'));

Class ExportExcelComponent extends ExportBaseNewComponent {

  var $sheet;
  var $rowls;
  var $alphaNumeric;
  var $cursor = array();
  var $components = array('ExportHelper2');
  var $EvaluationSubmission, $EvaluationSimple, $EvaluationRubric, $User, $GroupsMembers, $GroupEvent, $EvaluationMixeval,
  	  $MixevalsQuestion, $Event, $Group;
  
  function __construct() {
    $this->xls = new PHPExcel();
    $this->sheet = $this->xls->getActiveSheet();
    $this->alphaNumeric = array();
    foreach(range('A', 'Z') as $letters) {
      array_push($this->alphaNumeric, $letters);
    }
    $this->cursor = array('x' => 0, 'y' => 1);
  }
  
  function _output($fileName) {
	$starting_pos = ord('C');
    header("Content-type: application/vnd.ms-excel"); 
	header('Content-Disposition: attachment;filename='.$fileName.".xls");
    header('Cache-Control: max-age=0');
    $objWriter = new PHPExcel_Writer_Excel5($this->xls);
    $objWriter->setTempDir(TMP);
    $objWriter->save('php://output');
  }
  
  // converts a block array to xls sheet; by default the function fills the sheet starting at the top left corner.
  function _drawToExcelSheetAtCoordinates($grid = null, $rowOffSet = 0, $colOffSet = 0) {
  	$rowSpan = count($grid);
  	$colSpan = count($grid[0]);
    for($row = 0; $row < $rowSpan; $row++) {
  	  for($col = 0; $col < $colSpan; $col++) {
  	  	$colAlphabetIndex = $this->_convertToRowAlphabetIndex($row + $rowOffSet);
  	  	$this->sheet->getColumnDimension($colAlphabetIndex)->setColumnIndex($colAlphabetIndex)->setWidth(10);
  	  	$cell = $colAlphabetIndex.($col + $colOffSet);
  	  	if(!empty($grid[$row][$col])) {
  	  	  $this->sheet->setCellValue($cell, $grid[$row][$col]);
  	  	}
  	  }
  	}
  }
  
  function _convertToRowAlphabetIndex($colNum) {
  	if($colNum < 0 ) {
  	  throw new EXCEPTION("Column number must be greater than zero.");
  	}
  	if($colNum < 26) {
  	  return $this->alphaNumeric[$colNum];
  	}
  	else {
  	  $letterIndex = $colNum/26; 
  	  $former = $this->alphaNumeric[intval($letterIndex) - 1];
  	  $latter = $this->alphaNumeric[$colNum % 26];
  	  return $former.$latter;
  	}
  }
  
  function _buildSimpleEvalResults($grpEventId, $params) {    	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	// Build grid
  	$xRange = 9 + count($groupMembers); 
  	$yRange = 4 + count($groupMembers);
  	$grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
    $evaluatorsArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
    $xPosition = 0;
    // Fill in Evaluatee Rows
    if(!empty($params['include_student_email'])) {
      $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['email']);
    }
    if(!empty($params['include_student_id'])) {
      $xPosition++;
      $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['student_no']);
    }
    if(!empty($params['include_student_name'])) {
      $xPosition++;
      $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['name']);
    }

    $xPosition ++;
    $yPosition = 6;
    // Fill in score table
    $grid[$xPosition + count($groupMembers) + 1][$yPosition - 1] = "Evaluatee Ave Score";
    foreach($groupMembers as $evaluatee) {
      $evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['id'], true);
	  // Format marks input array
	  $resultRowArray = array();
	  $totalScore = 0;
	  foreach($evalResults as $evaluator) {
	  	$totalScore += $evaluator['EvaluationSimple']['score'];
	    array_push($resultRowArray, $evaluator['EvaluationSimple']['score']);
	  }
	  // Insert Evaluatee Average Mark; if neccessary.
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $resultRowArray);
	  if(!empty($params['include_final_marks'])) {
	  	$submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
	    $grid[$xPosition + count($groupMembers) + 1][$yPosition] = $totalScore/$submissionCount;
	  }
	  $yPosition++;
    }
  	// Fill in Evaluator Columns
	$yPosition = 5;
    if(!empty($params['include_student_name'])) {
	  /*$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['last_name']);
	  $yPosition--;
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['first_name']);*/
      $yPosition--;
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['name']);
    }
    if(!empty($params['include_student_id'])) {
      $yPosition--;
      $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['student_no']);
    }
    $yPosition--;
    $grid[$xPosition][$yPosition] = "Evaluators:";
    //return $this->ExportHelper2->arrayDraw($grid);
    return $grid;
  }
  
  
  function _buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluateeId, $params, $eventType=null) {
    $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
    $evaluatee = $this->User->findUserByid($evaluateeId);
    // Build Grid
    $xRange = 5; 
    $yRange = count($groupMembers) + 2;
    $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
    // Insert Evaluatee Header
    $xPosition = 1;
    $evaluateeHeaderArray = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee['User']);
    $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeaderArray);
    // Insert evaluators' comment
    $eventType == 'S' ? ($evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluateeId, true)) &&
    					($evalType = 'EvaluationSimple') &&
    					($commentType = 'eval_comment') 
    					:
						($evalResults = $this->EvaluationRubric->getResultsByEvaluatee($grpEventId, $evaluateeId, true)) &&
						($evalType = 'EvaluationRubric') &&
						($commentType = 'general_comment');
    $yRowPosition = 2;
    for($i=0; $i<count($groupMembers); $i++) {
      $evaluator = $groupMembers[$i];
      // Insert evaluator rows, we can utilize the format evaluatee header function with some modifications
      $evaluatorRow = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluator);
      array_shift($evaluatorRow);
      array_pop($evaluatorRow);
      // Push if only evaluator has submitted an evaluation for the current evaluatee
      if(!empty($evalResults[$i][$evalType][$commentType])) {
      	array_push($evaluatorRow, $evalResults[$i][$evalType][$commentType]);
      }
      $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 1, $yRowPosition, $evaluatorRow);
      $yRowPosition++;
    }
    return $grid;
  }
  
  function _buildMixevalResultByEvaluatee($params, $grpEventId, $evaluateeId) {
  	 $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
  	 $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
  	 $this->GroupEvent = ClassRegistry::init('GroupEvent');
  	 $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	 $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	 $this->User = ClassRegistry::init('User');
  	
  	 $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	 $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);
  	
  	 $xRange = count($groupMembers) + 7;
  	 $yRange = count($questions) + 8;
  	 $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
  	
  	 $evaluatee = $this->User->findUserByidWithFields($evaluateeId);
  	 $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);

  	 $yPosition = 2; $xPosition = 1;
  	 $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeader);
  	 $grid[$xPosition][1] = "######################################################";
  	
  	 $evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
  	 if(!empty($params['include_student_name'])) {
  	   $grid[$xPosition + 1][$yPosition] = "Evaluators :";
 	   $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
  	 }
  	 if(!empty($params['include_student_id'])) {
  	   $yPosition++;
  	   $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id :";
       $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
  	 }
  	 if(!empty($params['include_student_email'])) {
  	   $yPosition++;
  	   $grid[$xPosition + 1][$yPosition] = "Evaluator's Email :";
	   $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
  	 }
  	 	   	 
	 $grid[$xPosition + count($groupMembers) + 4][$yPosition + 1] = "Question Avg Mark";
	 $rowNum = 7; $finalMark = 0;
	 foreach($questions as $q) {
	 	$totalScore = 0;
	  	$row = array();
	  	array_push($row, $q['MixevalsQuestion']['title'].' (/'.$q['MixevalsQuestion']['multiplier'].')'.',');
	 	foreach($groupMembers as $evaluator) {
		  $evalResult = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluatee['id'],
									$evaluator['id'], $q['MixevalsQuestion']['question_num']);
		  array_push($row, $evalResult['EvaluationMixevalDetail']['grade']);
		  $totalScore += $evalResult['EvaluationMixevalDetail']['grade'];	
	  	}
	    array_push($row, ' ');
	    $sumbissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
	    $questionAvg = $totalScore/$sumbissionCount;
	  	array_push($row, $questionAvg);
	  	$this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 1, $yPosition+2, $row);
	  	$finalMark += $questionAvg;
	  	$rowNum++;
	  	$yPosition++;
	 }
	 
	 if(!empty($params['include_final_marks'])) {
	 	$grid[$xPosition + count($groupMembers) + 3][$yPosition + 2] = "Final Mark =";
	 	$grid[$xPosition + count($groupMembers) + 4][$yPosition + 2] = $finalMark;
	 }
	 return $grid;
  }
  
  function _buildRubricsResultByEvalatee($params, $grpEventId, $evaluateeId) {
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
    $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);
  	
  	$xRange = count($groupMembers) + 7;
  	$yRange = count($questions) + 8;
  	$grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
  	
  	$evaluatee = $this->User->findUserByidWithFields($evaluateeId);
  	$evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
  	$yPosition = 2; $xPosition = 1;
  	$this->ExportHelper2->fillGridHorizonally($grid, $xPosition,  0, $evaluateeHeader);
  	$grid[$xPosition][1] = "######################################################";
  	
  	$evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
  	if(!empty($params['include_student_name'])) {
  	  $grid[$xPosition+1][$yPosition] = "Evaluators :";
 	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
  	}
  	if(!empty($params['include_student_id'])) {
  	  $yPosition++;
  	  $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id :";
      $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
  	}
  	if(!empty($params['include_student_email'])) {
  	  $yPosition++;
  	  $grid[$xPosition + 1][$yPosition] = "Evaluator's Email :";
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
    } 	   	 
	$grid[$xPosition + 7][$yPosition + 1] = "Question Avg Mark";
	$questionArray = array();
	// Insert in question column
	foreach($questions as $q) {
	  array_push($questionArray, $q['RubricsCriteria']['criteria']." ( /".$q['RubricsCriteria']['multiplier'].")");
	}
	$this->ExportHelper2->fillGridVertically($grid, $yPosition + 2, $xPosition + 1, $questionArray);
	$xPosition += 2;
	$questionTotalMarkArray = array_pad(array(), count($questions),0);
	foreach($groupMembers as $evaluator) {
	  $evalResult = $this->EvaluationRubric->getRubricsCriteriaResult($grpEventId, $evaluatee['id'], $evaluator['id']);
	  $gradesArray = array();
	  $questionNum = 0;
	  foreach($evalResult as $result) {
	  	array_push($gradesArray, $result['EvaluationRubricDetail']['grade']);
	  	$questionTotalMarkArray[$questionNum] += $result['EvaluationRubricDetail']['grade'];
	  	$questionNum++;
	  }
	  $this->ExportHelper2->fillGridVertically($grid, $yPosition + 2, $xPosition + 1, $gradesArray);
	  $xPosition++;
	}
	// Calculate question average
	$xPosition++; $finalMark = 0;
	$submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
	foreach($questionTotalMarkArray as $questionTotal) {
	  $questionAverage = $questionTotal/count($submissionCount);
	  $grid[$xPosition + 1][$yPosition + 2] = $questionAverage;
	  $finalMark += $questionAverage;
	  $yPosition++;
	}
	// Sum up final mark
	if(!empty($params['include_final_marks'])) {
	  $grid[$xPosition][$yPosition + count($questions)] = "          Total=";
	  $grid[$xPosition + 1][$yPosition + count($questions)] = $finalMark;
	}
	return $grid;
  }
  
  function _buildMixEvalQuestionCommentTable($params ,$grpEventId) {
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->Event = ClassRegistry::init('Event');
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$groupCount = count($groupMembers);
  	$grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
  	$evaluation = $this->Event->getEventById($grpEvent['GroupEvent']['event_id']);
  	$questions = $this->MixevalsQuestion->getQuestion($evaluation['Event']['template_id'], 'T');
  	$validQuestionNum = array();
  	foreach($questions as $q) {
  	  array_push($validQuestionNum, $q['MixevalsQuestion']['question_num']);
  	}
  	$qCount = count($questions);
  	// Create grid
  	$sectionSpacing = 4 + $qCount * ($groupCount+1);
  	$gridDimensionY = $sectionSpacing * $groupCount;
  	$gridDimensionX = 8;
  	$grid = $this->ExportHelper2->buildExporterGrid($gridDimensionX, $gridDimensionY);
  	// Fill in the questions
  	$questionNum = 1; $questionYPos = 2; $xPosition = 2;
  	$submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
  	foreach($questions as $q) {
  	  $this->ExportHelper2->repeatDrawByCoordinateVertical($grid, $xPosition, $questionYPos, $sectionSpacing, $groupCount, 
  	  														"Question ".$questionNum.": ".$q['MixevalsQuestion']['title']);
  	  $questionNum++;
  	  $questionYPos += $submissionCount + 1;
  	}
  	// Fill in the comments
  	$headerYPos = 0; $commentRowYPos = 3;
  	foreach($groupMembers as $evaluatee) {
  	  // First fill in the evaluatee headers
  	  $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
  	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition - 1, $headerYPos, $evaluateeHeader);
  	  
  	  // Now start filling in mixeval question comments
  	  $mixedResults = $this->EvaluationMixeval->getResultsDetailByEvaluatee($grpEventId, $evaluatee['id'], true);
  	  $tmpYPos = $commentRowYPos;
  	  foreach($mixedResults as $evaluator) {
  	  	// Only loop through question comment results
		if(!in_array($evaluator['EvaluationMixevalDetail']['question_number'], $validQuestionNum))
  	  	  continue;
  	  	  
  	  	$evaluatorArray = array();
  	    if(!empty($params['include_student_name'])) {
  	      array_push($evaluatorArray, $evaluator['User']['evaluator_last_name']);
  	      array_push($evaluatorArray, $evaluator['User']['evaluator_first_name']);
  	    }
  	    if(!empty($params['include_student_id'])) {
  	      array_push($evaluatorArray, $evaluator['User']['evaluator_student_no']);
  	    }
  	    array_push($evaluatorArray, $evaluator['EvaluationMixevalDetail']['question_comment']);
  	    empty($grid[$xPosition][$tmpYPos]) ? $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $tmpYPos, $evaluatorArray) : 
  	    							$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $tmpYPos+1, $evaluatorArray);
		$tmpYPos++;
  	  }
  	  $commentRowYPos += $sectionSpacing; 
  	  $headerYPos += $sectionSpacing;
  	}
	return $grid;
	
  }
  
  function _setFontSizeVertically($initY, $endY, $column, $fontSize) {
  	for($y=$initY; $y<=$endY; $y++) {
  	  $this->sheet->getStyle($column.$y)->getFont()->setSize($fontSize);
  	}
  }
  
  function createExcel($params, $eventId) {
  	// Prepare header.
	$header = $this->generateHeader2($params, $eventId, 'fsa');
	$this->_drawToExcelSheetAtCoordinates($header, $this->cursor['x'], $this->cursor['y']);
	$this->_setFontSizeVertically(1, count($header[0])-1, 'A', 16);
	$this->cursor['y'] += (count($header[0])); 
	$groupEvents = $this->GroupEvent->getGroupEventByEventId($eventId);
	$event = $this->Event->getEventById($eventId);
  	switch($event['Event']['event_template_type_id']){
  	  // Simple Evaluation
  	  case 1 :
  	    for ($i=0; $i<count($groupEvents); $i++) {
  	      $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
  	      $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
  	      $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
  	      if(!empty($params['include_group_names'])) {
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name : ".$group[0]['Group']['group_name']);
  	      	$this->cursor['y'] += 2;
  	      	$this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
  	      	$this->cursor['y'] += 2;
  	      }
  	      if(!empty($params['simple_eval_grade_table'])){
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Simple Evaluation Grades Table");
  	        $simpleResults = $this->_buildSimpleEvalResults($groupEvents[$i]['GroupEvent']['id'], $params);
  	        $this->_drawToExcelSheetAtCoordinates($simpleResults, $this->cursor['x'], $this->cursor['y']);
  	        $this->cursor['y'] += (count($simpleResults[0])+2);
  	      }
  	      if(!empty($params['simple_evaluator_comment'])) {
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Evaluation Comments");
  	      	$this->cursor['y']++;
//  	      	$CSV .= "Simple Evaluation Comments :\n\n";
  	      	foreach($groupMembers as $evaluatee) {	
			  $simpleEvalComments = $this->_buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'S');
			  $this->_drawToExcelSheetAtCoordinates($simpleEvalComments, $this->cursor['x'], $this->cursor['y']);
			  $this->cursor['y'] += (count($simpleEvalComments[0]) + 1);
  	      	}
  	      }
  	    }
  	  break;
  	  
  	  //Rubrics Evaluation Event
  	  case 2:
  	  	for($i=0; $i<count($groupEvents); $i++) {
		  $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
		  $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
		  $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
		  if(!empty($params['include_group_names'])) {
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name : ".$group[0]['Group']['group_name']);
  	      	$this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
  	      	$this->cursor['y'] += 2;
		  }
		  if(!empty($params['rubric_criteria_marks'])) {
		  	$this->sheet->setCellValue('A'.$this->cursor['y'], "Rubrics Evaluation Grade Table :");
		  	$this->cursor['y'] += 2;
		    foreach($groupMembers as $evaluatee) {
      		  $gradeTable = $this->_buildRubricsResultByEvalatee($params, $grpEventId, $evaluatee['GroupsMembers']['user_id']);
      		  $this->_drawToExcelSheetAtCoordinates($gradeTable, 0, $this->cursor['y']);
      		  $this->cursor['y'] += (count($gradeTable[0]));
    		}
		  }
		  if(!empty($params['rubric_general_comments'])) {
		  	$this->sheet->setCellValue('A'.$this->cursor['y'], "Rubrics General Comments");
		  	$this->cursor['y'] += 2;
		    foreach($groupMembers as $evaluatee) {
			  $rubricGeneralComments = $this->_buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'R');
			  $this->_drawToExcelSheetAtCoordinates($rubricGeneralComments, 0, $this->cursor['y']);
			  $this->cursor['y'] += (count($rubricGeneralComments[0]) + 1);
  	      	}
		  }
  	  	}
  	  	//return $CSV;
  	  break;
  	  
  	  // Mixed Evaluation Event
  	  case 4 :
  	  	for ($i=0; $i<count($groupEvents); $i++) {
		  $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
		  $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
		  $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);		  
		  if(!empty($params['include_group_names'])) {
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name : ".$group[0]['Group']['group_name']);
  	      	$this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
  	      	$this->cursor['y'] += 2;
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Mixed Evaluation Grade Table");
  	      	$this->cursor['y'] += 2;
		  }
		  if(!empty($params['include_mixeval_grades'])) {
			foreach($groupMembers as $evaluatee) {
      		  $gradeTable = $this->_buildMixevalResultByEvaluatee($params, $grpEventId, $evaluatee['GroupsMembers']['user_id']);
      		  $this->_drawToExcelSheetAtCoordinates($gradeTable, 0, $this->cursor['y']);
      		  $this->cursor['y'] += (count($gradeTable[0]));
    		}
    		$this->cursor['y'] += 2;
		  }  
 		  if (!empty($params['include_mixeval_question_comment'])) {
  	      	$this->sheet->setCellValue('A'.$this->cursor['y'], "Mixed Evaluation Comments:");
  	      	$this->cursor['y'] += 2;
 		  	$questionComments = $this->_buildMixEvalQuestionCommentTable($params, $grpEventId);
			$this->_drawToExcelSheetAtCoordinates($questionComments, 0, $this->cursor['y']);
			$this->cursor['y'] += (count($questionComments[0]) + 1);
 		  }
  	  	}
  	  break;
  	  
  	  default: throw new Exception("Event id input seems to be invalid!");
  	}
	//return $CSV;
	$this->_output($params['file_name']);
  }
  
}
?>
