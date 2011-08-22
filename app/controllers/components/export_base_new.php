<?php

class ExportBaseNewComponent extends Object {
	
  var $components = array('ExportHelper2');
  
  function generateHeader2($params, $eventId, $type){
  	$this->Course = ClassRegistry::init('Course');
  	$this->Event = ClassRegistry::init('Event');
  	$this->UserCourse = ClassRegistry::init('UserCourse');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$eventType = array('1' => 'Simple Evaluation', '2' => 'Rubrics Evaluation', '4' => 'Mixed Evaluation');
  		
  	$courseId = $this->Event->getCourseByEventId($eventId);
  	$event = $this->Event->getEventById($eventId);
  	$course = $this->Course->getCourseById($courseId);

  	$grid = $this->ExportHelper2->buildExporterGrid(8, 8);
  	$grid[0][0] = "********************************************";
  	$yIndex = 1;
    if(!empty($params['include_course'])) {
      $grid[0][$yIndex] = "Course Name : ,,".$course['Course']['title'];
    }
    if(!empty($params['include_eval_event_names'])) { 
	  $yIndex++;
	  $grid[0][$yIndex] = "Event : ,,".$event['Event']['title'];
    }
    if(!empty($params['include_eval_event_type'])) {
      $yIndex++;
  	  $grid[0][$yIndex] = "Evaluation Type : ,,".$eventType[$event['Event']['event_template_type_id']];
  	}
    if(!empty($params['include_date'])) {
      $yIndex += 2;
  	  $grid[0][$yIndex] = "Date : ,,".date("F j Y g:i a");
  	}
  	$yIndex++;
  	$grid[0][$yIndex] = "********************************************";
  	if($type == 'CSV') {
  	  return $this->ExportHelper2->arrayDraw($grid);
  	}
  	else {
  	  for($y=1; $y<count($grid); $y++) {
		$grid[0][$y] = str_replace(array(","), "", $grid[0][$y]);
  	  }
  	  return $grid;
  	}
  }
  
  function generateHeader($params, $eventId){
  	$this->Course = ClassRegistry::init('Course');
  	$this->Event = ClassRegistry::init('Event');
  	$this->UserCourse = ClassRegistry::init('UserCourse');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$eventType = array('1' => 'Simple Evaluation', '2' => 'Rubrics Evaluation', '4' => 'Mixed Evaluation');
  		
  	$courseId = $this->Event->getCourseByEventId($eventId);
  	$event = $this->Event->getEventById($eventId);
  	$course = $this->Course->getCourseById($courseId);
  	//$instructors = $this->UserCourse->getInstructors($courseId);
  	$instructors[0]['first_name'] = "Gordon";
  	$instructors[0]['last_name'] = "Slade";
  	$header = '';
  	
  	if(!empty($params['include_course']) || !empty($params['include_eval_event_names'])) {
	  $header .= "********************************************\n";
	  if(!empty($params['include_course']))
	    $header .= "Course Name : ,,".$course['Course']['title']."\n";
	  if(!empty($params['include_eval_event_names'])) 
	  	$header .= "Event : ,,".$event['Event']['title']."\n";
  	}
  	if(!empty($params['include_eval_event_type'])) {
  	  $header .= "Evaluation Type : ,,".$eventType[$event['Event']['event_template_type_id']]."\n\n";
  	}
  	if(!empty($params['include_date'])) {
  	  $header .= "Date : ,,".date("F j Y g:i a")."\n";
  	}
  	if(!empty($params['include_instructors'])) {
  	  $header .= "Instructors :,,";
  	  foreach($instructors as $i) {
  	  	$header .= $i['first_name']." ".$i['last_name'].",\n";
  	   }
  	}
  	$header .= "********************************************\n";
  	return $header;
  }
  
  function buildSimpleEvaluationScoreTableByEvaluatee($params, $grpEventId, $evaluateeId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->Group = ClassRegistry::init('Group');
  	$this->User = ClassRegistry::init('User');
  	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
  	$this->UserGradePenalty = ClassRegistry::init('UserGradePenalty');
  	$this->Penalty = ClassRegistry::init('Penalty');
  	
  	$grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
  	$group = $this->Group->getGroupByGroupId($grpEvent['GroupEvent']['group_id']);
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	// Build grid
	$xDimension = 10;
  	$yDimensions = count($groupMembers);
	$grid = $this->ExportHelper2->buildExporterGrid($xDimension, $yDimensions);
  	$xPosition = 0;
  	$yPosition = 0;
  	// Fill in grid Results
    $evaluatee = $this->User->findUserByid($evaluateeId);
  	$yInc = 0; $index = 0;
  	foreach($groupMembers as $evaluator) {
  	  $row = array();
  	  if(!empty($params['include_group_names'])) {
  	    array_push($row, $group[0]['Group']['group_name']);
  	  }
  	  if(!empty($params['include_student_email'])) {
  	  	array_push($row, $evaluatee['User']['email']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	    array_push($row, $evaluatee['User']['first_name']." ".$evaluatee['User']['last_name']);
  	  }
   	  if(!empty($params['include_student_id'])) {
  	    array_push($row, $evaluatee['User']['student_no']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	  	array_push($row, $evaluator['first_name']." ".$evaluator['last_name']);
  	  }
  	  if(!empty($params['include_student_id'])) {
  	    array_push($row, $evaluator['student_no']);
  	  }
  	  $simpleEvalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['User']['id'], true);
  	  // Save all the submissions; use to find evaluators who haven't submitted yet
  	  $submissionStudentNo = array();
  	  foreach($simpleEvalResults as $results) {
  	  	 array_push($submissionStudentNo, $results['User']['student_no']);
  	  }
  	  // Verify that the evaluator has indeed submitted an evaluator for the current evaluatee; ie the evluator exists in the array
  	  if(!in_array($evaluator['student_no'], $submissionStudentNo)) {
  	  	$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition+$yInc, $row);
  	  	$yInc++;
  	  	continue;
  	  }
  	  array_push($row, $simpleEvalResults[$index]['EvaluationSimple']['score']);
  	  $userPenalty = $this->UserGradePenalty->getByUserIdGrpEventId($grpEventId, $evaluateeId);
  	  $penalty = $this->Penalty->getPenaltyById($userPenalty['UserGradePenalty']['penalty_id']);
  	  if(!empty($penalty)) {
  	  	array_push($row, $penalty['Penalty']['percent_penalty']."%");
  	  } else {
  	  	array_push($row, "0%");
  	  }
  	  $finalGrade = $simpleEvalResults[$index]['EvaluationSimple']['score'] * (1 - ($penalty['Penalty']['percent_penalty']/100));
  	  array_push($row, $finalGrade);
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition + $yInc, $row);
  	  $index++;
  	  $yInc++;
  	}
	return $this->ExportHelper2->arrayDraw($grid);
  }
  
  function buildSimpleEvaluationScoreTableByGroup($params, $grpEventId) {
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$csv = '';
  	foreach($groupMembers as $evaluatee) {
  	  $resultTable = $this->buildSimpleEvaluationScoreTableByEvaluatee($params, $grpEventId, $evaluatee);
  	  $csv .= $resultTable;
  	}
  	return $csv;
  }
  
  function buildSimpleEvaluationScoreTableByEvent($params, $eventId) {
	$this->GroupEvent = ClassRegistry::init('GroupEvent');
	$csv  = '';
	$groupEvents = $this->GroupEvent->getGrpEventByEventId($eventId);
	foreach($groupEvents as $ge) {
  	  $SimpleEvalResultTable =  $this->buildSimpleEvaluationScoreTableByGroup($params, $ge['GroupEvent']['id']);
  	  $csv .= $SimpleEvalResultTable."\n";
  	}
  	return $csv;
  }
   
  function buildMixedEvalScoreTableByEvaluatee($params, $grpEventId, $evaluateeId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->Group = ClassRegistry::init('Group');
  	$this->User = ClassRegistry::init('User');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
  	$this->UserGradePenalty = ClassRegistry::init('UserGradePenalty');
  	$this->Penalty = ClassRegistry::init('Penalty');
  	
  	$grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
  	$group = $this->Group->getGroupByGroupId($grpEvent['GroupEvent']['group_id']);
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);
  	
  	// Creat grid
  	$xDimension = 10 + count($questions);
  	$yDimensions = count($groupMembers);
  	$grid = $this->ExportHelper2->buildExporterGrid($xDimension, $yDimensions);
  	$xPosition = 0;
  	$yPosition = 0;
  	// Fill in grid with results
  	$evaluatee = $this->User->findUserByid($evaluateeId);
  	$yInc = 0;
  	foreach($groupMembers as $evaluator) {
  	  $row = array();
  	  if(!empty($params['include_group_names'])) {
  	    array_push($row, $group[0]['Group']['group_name']);
  	  }
  	  if(!empty($params['include_student_email'])) {
  	  	array_push($row, $evaluatee['User']['email']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	    array_push($row, $evaluatee['User']['first_name']." ".$evaluatee['User']['last_name']);
  	  }
  	  if(!empty($params['include_student_id'])) {
  	  	array_push($row, $evaluatee['User']['student_no']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	  	array_push($row, $evaluator['first_name']." ".$evaluator['last_name']);
  	  }
  	  if(!empty($params['include_student_id'])) {
  	    array_push($row, $evaluator['student_no']);
  	  }
  	  // Get mix eval results, also check if the evaluator actually submitted an evaluation
  	  $mixEval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee($grpEventId, $evaluator['id'], $evaluatee['User']['id']);
  	  if(empty($mixEval)) {
  	  	$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition + $yInc, $row);
  	  	$yInc++;
  	  	continue;
  	  }
  	  // else fill in grades
	  $mixEvalResults = $this->EvaluationMixevalDetail->getLickertScaleQuestionResults($mixEval['EvaluationMixeval']['id']);
	  foreach($mixEvalResults as $result) {
	  	array_push($row, $result['EvaluationMixevalDetail']['grade']);
	  }
	  array_push($row, $mixEval['EvaluationMixeval']['score']);
  	  $userPenalty = $this->UserGradePenalty->getByUserIdGrpEventId($grpEventId, $evaluateeId);
  	  $penalty = $this->Penalty->getPenaltyById($userPenalty['UserGradePenalty']['penalty_id']);
  	  if(!empty($penalty)) {
  	  	array_push($row, $penalty['Penalty']['percent_penalty']."%");
  	  } else {
  	  	array_push($row, "0%");
  	  }
  	  $finalGrade = $mixEval['EvaluationMixeval']['score'] * (1 - ($penalty['Penalty']['percent_penalty']/100));
  	  array_push($row, $finalGrade);
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition + $yInc, $row);
	  $yInc++;
  	}
  	return $this->ExportHelper2->arrayDraw($grid);
  }
  
  function buildMixedEvalScoreTableByGroupEvent($params, $grpEventId) {  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$csv = '';
  	foreach($groupMembers as $evalutee) {
  	   $resultTable =  $this->buildMixedEvalScoreTableByEvaluatee($params, $grpEventId, $evalutee['id']);
  	   $csv .= $resultTable;
  	}
  	$csv .= "\n";
  	return $csv;
  }
  
  function buildMixEvalScoreTableByEvent($params, $eventId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$groupEvents = $this->GroupEvent->getGrpEventByEventId($eventId);
  	$csv  = '';
  	foreach($groupEvents as $ge) {
  	  $mixEvalResultTable =  $this->buildMixedEvalScoreTableByGroupEvent($params, $ge['GroupEvent']['id']);
  	  $csv .= $mixEvalResultTable."\n";
  	}
  	return $csv;
  }

  function buildRubricsScoreTableByEvaluatee($params, $grpEventId, $evaluateeId) {
    $this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->Group = ClassRegistry::init('Group');
  	$this->User = ClassRegistry::init('User');
	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
	$this->UserGradePenalty = ClassRegistry::init('UserGradePenalty');
	$this->Penalty = ClassRegistry::init('Penalty');
  	
  	$grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
  	$group = $this->Group->getGroupByGroupId($grpEvent['GroupEvent']['group_id']);
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);
  	
  	// Creat grid
  	$xDimension = 10 + count($questions);
  	$yDimensions = count($groupMembers);
  	$grid = $this->ExportHelper2->buildExporterGrid($xDimension, $yDimensions);
  	$xPosition = 0;
  	$yPosition = 0;
  	// Fill in grid with results
  	$evaluatee = $this->User->findUserByid($evaluateeId);
  	$yInc = 0;
  	foreach($groupMembers as $evaluator) {
  	  $row = array();
  	  if(!empty($params['include_group_names'])) {
  	    array_push($row, $group[0]['Group']['group_name']);
  	  }
  	  if(!empty($params['include_student_email'])) {
  	  	array_push($row, $evaluatee['User']['email']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	    array_push($row, $evaluatee['User']['first_name']." ".$evaluatee['User']['last_name']);
  	  }
  	  if(!empty($params['include_student_id'])) {
  	  	array_push($row, $evaluatee['User']['student_no']);
  	  }
  	  if(!empty($params['include_student_name'])) {
  	  	array_push($row, $evaluator['first_name']." ".$evaluator['last_name']);
  	  }
  	  if(!empty($params['include_student_id'])) {
  	    array_push($row, $evaluator['student_no']);
  	  }
  	  $rubricsEvaluation = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId, $evaluator['id'], $evaluateeId);
  	  // check to see evaluator has sumbitted an evaluation
  	  if(empty($rubricsEvaluation)) {
		$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition+$yInc, $row);
		$yInc++;
		continue;  	
  	  }
  	  $rubricsEvalResult = $rubricsEvaluation['EvaluationRubricDetail'];
  	  foreach($rubricsEvalResult as $result) {
  	  	array_push($row, $result['grade']);
  	  }
  	  array_push($row, $rubricsEvaluation['EvaluationRubric']['score']);
  	  $userPenalty = $this->UserGradePenalty->getByUserIdGrpEventId($grpEventId, $evaluateeId);
  	  $penalty = $this->Penalty->getPenaltyById($userPenalty['UserGradePenalty']['penalty_id']);
  	  if(!empty($penalty)) {
  	  	array_push($row, $penalty['Penalty']['percent_penalty']."%");
  	  } else {
  	  	array_push($row, "0%");
  	  }
  	  $finalGrade = $rubricsEvaluation['EvaluationRubric']['score'] * (1 - ($penalty['Penalty']['percent_penalty']/100));
  	  array_push($row, $finalGrade);
	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition + $yInc, $row);
  	  $yInc++;
  	}
  	return $this->ExportHelper2->arrayDraw($grid);
  }
  
  function buildRubricsEvalTableByGroupEvent($params, $grpEventId) {
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$csv = '';
  	foreach($groupMembers as $evaluatee) {
	  $resultTable =  $this->buildRubricsScoreTableByEvaluatee($params, $grpEventId, $evaluatee['id']);
	  $csv .= $resultTable;
  	}
  	return $csv;
  }
  
  function buildRubricsEvalTableByEventId($params, $eventId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$groupEvents = $this->GroupEvent->getGroupEventByEventId($eventId);
  	$csv = '';
  	foreach($groupEvents as $ge) {
  	  $resultTable = $this->buildRubricsEvalTableByGroupEvent($params, $ge['GroupEvent']['id']);
  	  $csv .= $resultTable."\n";
  	}
  	return $csv;
  }
/*
 * UNDER CONSTRUCTION !!!  
  function buildMixEvalCommentTableByEvaluatee($params, $grpEventId, $evaluateeId) {
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->Event = ClassRegistry::init('Event');
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$questions = $this->MixevalsQuestion->getQuestion($evaluation['Event']['template_id'], 'T');
  	// Create grid
  	$gridYDim = count($questions)*(count($groupMembers) + 1);
  	$gridXDim = 8;
  	$grid = $this->ExportHelper2->buildExporterGrid($gridDimensionX, $gridDimensionY);
  	$xPosition = 0; $yPosition = 0;
  	// Fill in questions
  	$qCount = 1; 
  	$qSpacing = count($groupMembers) + 1;
  	$qIndexing = 0;
  	foreach($question as $q) {
  	  $grid[$xPosition + 2][$yPosition + $qIndexing + 2] = "Question ".$qCount.":".$q['MixevalsQuestion']['title'];
  	  $qIndexing += $qSpacing; 
  	}
  	// Save question_comment's quesion num; only way to identify question comments via question num
    $validQuestionNum = array();
  	foreach($questions as $q) {
  	  array_push($validQuestionNum, $q['MixevalsQuestion']['question_num']);
  	}
  	// Setup evaluator's info
  	$grpMembersBlock = $this->ExportHelper2->createGroupMemberArrayBlock($groupMembers, $params);
  	$questionInitialYPos = 3; 
  	for($inc=0; $inc<$count($groupMembers); $inc++) {
  	  $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 2, $questionInitialYPos + $i, $grpMembersBlock[$i]);
  	}
  	
  }  
  */
  
  function buildMixEvalQuestionCommentTable($params ,$grpEventId) {
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
	$csv = $this->ExportHelper2->arrayDraw($grid);
	return $csv;
  }
  
  function buildRubricsResultByEvalatee($params, $grpEventId, $evaluateeId) {
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
	  $grid[$xPosition][$yPosition + count($questions)] = "Total=";
	  $grid[$xPosition + 1][$yPosition + count($questions)] = $finalMark;
	}
	return $this->ExportHelper2->arrayDraw($grid);
  }
  
  function buildMixevalResultByEvaluatee($params, $grpEventId, $evaluateeId) {
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
	 return $this->ExportHelper2->arrayDraw($grid);
  } 
  
  function buildMixevalResult($params, $grpEventId) {
  	$this->GroupsMembers = ClassRegistry::init('GroupsMembers');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	$resultCSV = '';
  	foreach($groupMembers as $evaluatee) {
  	  $resultCSV .= $this->buildMixevalResultByEvaluatee($params, $grpEventId ,$evaluatee['id']);
  	  $resultCSV .= "\n";
  	}
    return $resultCSV;  	
  }
  
  function buildRubricsResultTable($params, $grpEventId) {
    $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
    $resultCSV = '';
    foreach($groupMembers as $evaluatee) {
      $resultCSV .= $this->buildRubricsResultByEvalatee($params, $grpEventId, $evaluatee['id']);
      $resultCSV .= "\n";
    }
    return $resultCSV;
  }

  function buildSimpleEvalResults($grpEventId, $params) {
 	$this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
  	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
    	
  	$groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
  	// Build grid
  	$xRange = 9 + count($groupMembers); 
  	$yRange = 6 + count($groupMembers);
  	$grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
  	$grid[0][1] = "Simple Evaluation Grades Table :";
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
    return $this->ExportHelper2->arrayDraw($grid);
  }
  
  function buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluateeId, $params, $eventType=null) {
  	$this->User = ClassRegistry::init('User');
  	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
  	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	
    $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
    $evaluatee = $this->User->findUserByid($evaluateeId);
    // Build Grid
    $xRange = 9; 
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
    return $this->ExportHelper2->arrayDraw($grid);
  }
}

