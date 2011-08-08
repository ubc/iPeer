<?php

class ExportBaseComponent extends Object {

  var $globUsersArr = array();
  var $globEventId;
  var $mixedEvalNumeric;
  var $components = array('ExportHelper');
  
    
  /**
   * 
   * Generates generic header for exvaluation export files.
   * 
   * $params : Form data passed from evaluation/export.
   */
  /*function generateHeader($params) {
    if (!empty($params['course_name']) || !empty($params['export_date']) || !empty($params['instructors'])) {
      $header = '************************************************************************'."\n";
      $header .= !empty($params['course_name']) ? "Course :".$params['course_name']."\n":'';
      $header .= "Instructors :";
      foreach ($params['instructors'] as $instructor) {
        $instructor = $instructor['User'];
        $header .= $instructor['first_name'].' '.$instructor['last_name'].", ";
      }
    $header .= "\n";
    $header .= !empty($params['export_date']) ?  "Export Date :".$params['export_date']."\n\n\n" : '';
        
    $evalTypes = array(1=>'Simple Evaluation',2=>'Rubric',4=>'Mixed Evaluation');
    if (!empty($params['event_name']) || !empty($params['event_type_id'])) {
      $header .= "\n";
      $header .= !empty($params['event_name']) ? 'Event Name: '.$params['event_name']."\n":'';
      $header .= !empty($params['event_type_id']) ? 'Event Type: '.$evalTypes[$params['event_type_id']]."\n":'';
      $header .= "\n";
    }
    
    $header .= '************************************************************************'."\n";
    return  $header;
    } else {
      return '';
    }
  }*/
  
  function generateHeader($params){
  	$header = '';
  	// Header must print event name or evaluation type
  	if(!empty($params['course_name']) || !empty($params['event_name'])){
	  $header .= "********************************************\n";
	  !empty($params['course_name']) ? $header .= "Course : ,,".$params['course_name']."\n" : 
	  								  $header .= '';
	  !empty($params['event_name']) ? $header .= "Event : ,,".$params['event_name']."\n" :
	  								 $header .= ''; 
  	}
  	
  	!empty($params['evaluation_type']) ? $header .= "Evaluation Type : ,,".$params['evaluation_type']."\n" :
  										 $header .= '';
  	$header .= "\n";
  	!empty($params['export_date']) ? $header .= "Export Date : ,,".$params['export_date']."\n" :
  									 $header .= '';
	
	if(!empty($params['instructors'])){
	  $header .= "Instructor(s) : ,,";
	  foreach($params['instructors'] as $i){
	    $header .= $i.",";
	  }
	  $header .= ",\n";
	}
  	$header .= "********************************************";
  	return $header;
  }
  
  
  function bildMixevalCommentOutput($grpEventId) {
	
  }
  
  /**
   * Formats corresponding comments for evaluation export files.
   * 
   * @param $eventId_INT : event's Id.
   * @param $evaluatee_ARRAY : evaluate user info, array obtained from User model.
   * @param $evaluators_ARRAY : evaluators user info, array obtained from User model.
   * @param $evaluationType_CHAR : The type of evaluation; Simple(S), Mixed(M), and Rubric(R).
   * @param $input_ARRAY: Comments for the evaluatee; use this format {EvaluatorName, Comment}
   */
  function buildCommentOutput($input) {
  	
  	$this->Event = ClassRegistry::init('Event');
  	$this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
  	$eventId = $input[0]['event_id'];
  	$event = $this->Event->getEventById($eventId);
  	
  	$eventId = $event['Event']['template_id'];
  	$evaluationType = $event['Event']['event_template_type_id'];
  	
  	$questions = null;
  	$evaluatee = $input[0]['evaluatee'];
  	$commentType = array('1' => 'Comments', '2' => 'General Comments', '4' => 'Question Comments');
  	$formatComment = 'Evaluatee:,,Student #:,'.$commentType[$evaluationType]."\n";
  	$formatComment .= $evaluatee['last_name'].','.$evaluatee['first_name'].','.$evaluatee['student_no'];
  	$formatComment .= "\n\n";
  	
  	// For mixed evals, we have to attach the questions.
	if($evaluationType == 4){
		$questions = $this->MixevalsQuestion->getQuestion($eventId, 'T');
		$counter = 1;
		foreach($questions as $q){
			$questionNum = $q['MixevalsQuestion']['question_num'];
			$matchingComments = $this->getMatchingCommentHelper($input, $questionNum);
			$formatComment .= "Question ".$counter.": ".$q['MixevalsQuestion']['title']."\n";
			foreach($matchingComments as $m){
				$formatComment .= $m['evaluator_last_name'].','.$m['evaluator_first_name'].','
									.$m['evaluator_student_no'].','.$m['question_comment']."\n";
			}
			$counter++;
		}
		$formatComment .= "\n\n\n";
	}
	// else $evaluationType == 1(EvaluationSimple) or 2(EvaluationRubrics).
	else{
		$formatComment .= "Evaluators:\n";
	  	foreach($input as $i){
	  		$evaluatorFirstName = $i['evaluator_first_name'];
	  		$evaluatorLastName = $i['evaluator_last_name'];
	  		$evaluatorStudentNo = $i['evaluator_student_no'];
	  		$comment = $i['comment'];
	  		$formatComment .= $evaluatorLastName.','.$evaluatorFirstName.','.$evaluatorStudentNo.','.$comment;
	  		$formatComment .= "\n";
	  	}
	  	return $formatComment;
	}
	return $formatComment;
  }
  
  /**
   * Compile a simple evaluationÊresult data table.
   * 
   * @param INT $grpEventId : group_event_id corresponding to the simple evaluation.
   * @return formatted simple evaluation result data table.
   */
  function buildSimpleEvalResults($grpEventId, $selectParams) {
  	
	$this->User = ClassRegistry::init('User');
	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
	$this->GroupEvent = ClassRegistry::init('GroupEvent');
	$this->Group = ClassRegistry::init('Group');
	
	$formatComment = '';
	$evalType = $this->GroupEvent->getEvalType($grpEventId);
	$groupEvent = $this->GroupEvent->getGrpEvent($grpEventId);
	// Assert that this groupEvent is a simple evaluation evalType == 1
	if(1 != $evalType){
	  throw new Exception("[   $.grpEvent id DOES NOT CORRESPOND TO A SIMPLE EVALUATION EVENT, PLEASE CHANGE $.grpEventId TO A CORRESPONDING SIMPLE EVALUATION EVENT!   ]");
	}
	
	$group = $this->Group->getGroupById($grpEventId['group_id']);
	// Fills in group name parameter
	!empty($selectParams['include_group_names']) ? $formatComment .= $group['group_name']."\n\n" :
												   $formatComment .= '';
	
	$groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
	$i = 0;
	for($i; $i<count($groupMembers); $i++){
		$user = $this->User->findUserByidWithFields($groupMembers[$i]['GroupsMembers']['user_id']);
		$groupMembers[$i]['User'] = $user;
	}
	$formatComment .= "Student # :,Evaluator=>";
	foreach($groupMembers as $participant){
		$formatComment .= ",".$participant['User']['student_no'];	
	}
	$formatComment .= "\n";
	$formatComment .= "Evaluatee(DOWN),";
	foreach($groupMembers as $participant){
		$formatComment .= ",".$participant['User']['first_name'].$participant['User']['last_name'];
	}
	$formatComment .= "\n";
	foreach($groupMembers as $evaluatee){
		$evaluatee = $evaluatee['User'];
		$formatComment .= $evaluatee['student_no'].','.$evaluatee['first_name'].$evaluatee['last_name'];
		$evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['id']);
		foreach($evalResults as $result){
			if(isset($result['EvaluationSimple']))
				$formatComment .= ",".$result['EvaluationSimple']['score'];
			else
				$formatComment .= ",";
		}
		$formatComment .= ",\n";
	}
	return $formatComment;
  }
  
  
  function buildSimpleEvalResultArray2($grpEventId, $selectParams) {
  	$this->User = ClassRegistry::init('User');
	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
	$this->GroupEvent = ClassRegistry::init('GroupEvent');
	$this->Group = ClassRegistry::init('Group');
	
  	$evalType = $this->GroupEvent->getEvalType($grpEventId);
	$groupEvent = $this->GroupEvent->getGrpEvent($grpEventId);
	// Assert that this groupEvent is a simple evaluation evalType == 1
	if(1 != $evalType){
	  throw new Exception("[   $.grpEvent id DOES NOT CORRESPOND TO A SIMPLE EVALUATION EVENT, PLEASE CHANGE $.grpEventId TO A CORRESPONDING SIMPLE EVALUATION EVENT!   ]");
	}
  	
  	$groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
  	$i = 0;
	for($i; $i<count($groupMembers); $i++){
		$user = $this->User->findUserByidWithFields($groupMembers[$i]['GroupsMembers']['user_id']);
		$groupMembers[$i]['User'] = $user;
	}
  	$xLim = count($groupMembers) + 6;
  	$yLim = count($groupMembers) + 6;
  	$grid = $this->ExportHelper->buildExporterGrid($xLim, $yLim);
	
	$group = $this->Group->getGroupById($groupEvent['GroupEvent']['group_id']);
	!empty($selectParams['include_group_names']) ? $grid[0][0] = "Group Name :".$group['Group']['group_name'] :
												   $grid[0][0] = '';
	$grid[1][2] = "Evaluatee\Evaluator";
	// Insert Student Number into grid if selected
	if ($selectParams['include_student_id']) {
	  // Insert student_no horizontal row
	  $xIndex = 3;
	  foreach ($groupMembers as $g) {
        $grid[$xIndex][2] = $g['GroupsMembers']['user_id'];
		$xIndex++;
	  }
	  $yTempIndex = 5;
	  foreach ($groupMembers as $g) {
	  	$grid[0][$yTempIndex] = $g['GroupsMembers']['user_id'];
	  	$yTempIndex++;
	  }
	}
    // Insert student name into grid if selected
    if($selectParams['include_student_name']){
      $xIndex = 3;
      $yTempIndex = 3;
      // First fill in grid horizontally
      foreach ($groupMembers as $g) {
        $firstName = $g['User']['first_name'];
    	$lastName = $g['User']['last_name'];
    	$grid[$xIndex][$yTempIndex] = $firstName;
    	$grid[$xIndex][$yTempIndex+1] = $lastName;
    	$xIndex++;
      }
      // Now fill in grid veritcally
      $xIndex = 1;
      $yTempIndex = 5;
      foreach($groupMembers as $g) {
	    $firstName = $g['User']['first_name'];
    	$lastName = $g['User']['last_name'];
    	$grid[$xIndex][$yTempIndex] = $firstName;
    	$grid[$xIndex+1][$yTempIndex] = $lastName;
    	$yTempIndex++;
      }
    }
    
    // Now fill in the  scores table
	$yTempIndex = 5;
  	foreach ($groupMembers as $evaluatee) {
	  $evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['User']['id']);
	  $xIndex = 3;
	  foreach ($evalResults as $result) {
	  	if (isset($result['EvaluationSimple']['score'])) {
	  	  $grid[$xIndex][$yTempIndex] = $result['EvaluationSimple']['score'];
	  	}
	  	$xIndex++; 
	  }
	  $yTempIndex++;
    }
    
    // Fill in the email, if necessary
    if (!empty($selectParams['include_student_email'])) {
      $xIndex = 8;
      $yTempIndex = 5;
      foreach($groupMembers as $g) {
      	$grid[$xIndex][$yTempIndex] = $g['User']['email'];
      	$yTempIndex++;
      }
    }
    
    $formattedCSV = $this->ExportHelper->arrayDraw($grid);
    return $formattedCSV;
    
  }
  
  function buildMixevalResult($grpEventId, $params) {
  	$this->GroupsMembers = ClassRegistry::init('GroupsMembers');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	
  	$groupMembers = $this->ExportHelper->getGroupMemberHelper($grpEventId);
  	$evaluatorHeaderRow = $this->ExportHelper->createEvaluatorsHeaderRow($groupMembers);
  	$questions = $this->ExportHelper->getEvaluationQuestions($grpEventId);
  	
  	$xRange = count($groupMembers) + 6;
  	$yRange = count($groupMembers)*(count($questions) + 9);
  	$grid = $this->ExportHelper->buildExporterGrid($xRange, $yRange);
  	
  	$evaluatorHeaderArray = $this->ExportHelper->formatEvaluatorsHeaderArray($groupMembers);
  	$yIncrement = 0; $yGradeRow = 7;
  	$xFrom = 1;
  	
  	foreach($groupMembers as $evaluatee) {
  	  $evaluateeHeader = array("Evaluatee" ,$evaluatee['last_name'], $evaluatee['first_name'], $evaluatee['student_no']);
  	  $this->ExportHelper->fillGridHorizonally($grid, 0, $yIncrement, $evaluateeHeader);
	  $grid[0][1+$yIncrement] = "###########################################";
	  $this->ExportHelper->fillGridHorizonally($grid, $xFrom, 2+$yIncrement, $evaluatorHeaderArray['first_name']);
	  $this->ExportHelper->fillGridHorizonally($grid, 2+$xFrom, 3+$yIncrement, $evaluatorHeaderArray['last_name']);
	  $this->ExportHelper->fillGridHorizonally($grid, $xFrom, 4+$yIncrement, $evaluatorHeaderArray['student_no']);
	  $this->ExportHelper->fillGridHorizonally($grid, $xFrom, 5+$yIncrement, $evaluatorHeaderArray['email']);
	  $this->ExportHelper->fillGridHorizonally($grid, 8, 6+$yIncrement, array("Queston Avg Mark:"));
	  //$this->ExportHelper->fillGridHorizonally($grid, 8, 5+$yIncrement, $evaluatorHeaderArray['email']);
	  foreach($questions as $q) {
	  	$row = array();
	  	array_push($row, $q['MixevalsQuestion']['title'],'');
	  	$yTempIndex = $yGradeRow;
	  	foreach($groupMembers as $evaluator) {
		  $evalResult = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluatee['id'],
									$evaluator['id'], $q['MixevalsQuestion']['question_num']);
		  array_push($row, $evalResult['EvaluationMixevalDetail']['grade']);		
	  	}
	  	$this->ExportHelper->fillGridHorizonally($grid, 1, $yTempIndex, $row);
	  	$yTempIndex++;
	  }
	  $yGradeRow += 10;
	  $yIncrement += (9+count($questions));
  	}
  	return $this->ExportHelper->drawMixOrRubricsGrid($grid, $params, count($groupMembers));
  }
  
  function buildRubricOrMixResults($grpEventId) {
  	$this->GroupsMembers = ClassRegistry::init('GroupsMembers');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	
  	$formatComment = '';
  	$groupMembers = $this->ExportHelper->getGroupMemberHelper($grpEventId);
  	$evaluatorHeaderRow = $this->ExportHelper->createEvaluatorsHeaderRow($groupMembers);

  	$questions = $this->ExportHelper->getEvaluationQuestions($grpEventId);
  	$evaluationType = $questions[0]['evaluation_type'];
  	// Define the appropriate array index based on evaluation type; $evaluationType == 2 || 4.
  	$questionType = null;
  	$questionIdentifier = null;
  	$evaluationType == 4 ? ($questionType = 'MixevalsQuestion') && ($questionIdentifier = 'title') : 
  						   ($questionType = 'RubricsCriteria') && ($questionIdentifier = 'criteria');
  	foreach($groupMembers as $evaluatee){
  		$formatComment .= "Evaluatee :,".$evaluatee['last_name'].",".$evaluatee['first_name'].",".$evaluatee['student_no']."\n";
  		$formatComment .= "###########################################\n";
  		$formatComment .= ",".$evaluatorHeaderRow."\n\n";
  		$evaluatorTotalMark = array();
  		$questionIndex = 0;
  		foreach($questions as $q){
  			$totalMark = 0;
			$formatComment .= ","."Q".($questionIndex+1)." : ".$q[$questionType][$questionIdentifier].",";
			foreach($groupMembers as $evaluator){
				if($evaluationType == 4){
					$evalResult = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluatee['id'],
													$evaluator['id'], $q['MixevalsQuestion']['question_num']);
					if(!empty($evalResult)){
						$score = $evalResult['EvaluationMixevalDetail']['grade'];
						$totalMark += intval($score);
					}
					else
						$score = ",";
					$formatComment .= ",".$score;
				}
				else{
					$evalResult = $this->EvaluationRubric->getRubricsCriteriaResult($grpEventId, $evaluatee['id'], $evaluator['id']);
					if(!empty($evalResult)){
						$score = $evalResult[$questionIndex]['EvaluationRubricDetail']['grade'];
						$totalMark += intval($score);
					}
					else
						$score = ",";
					$formatComment .= ",".$score;
				}
			}
			$formatComment .= ",".($totalMark/count($groupMembers));
			$formatComment .= "\n";
			$questionIndex++;
  		}
		
  		// Set the average mark for every evaluator over the number of questions.
  		$formatComment .= ",Evaluator Average Mark:,";
  		
  		$evaluationType == 4 ?  ($eval = $this->EvaluationMixeval->getResultsByEvaluatee($grpEventId, $evaluatee['id']))
  								& ($type = 'EvaluationMixeval') :
  						   		($eval = $this->EvaluationRubric->getResultsByEvaluatee($grpEventId, $evaluatee['id']))
  						   		& ($type = 'EvaluationRubric');
  		foreach($eval as $evalResult){
  			$avgScore = $evalResult[$type]['score']/count($questions);
  			$formatComment .= ",".$avgScore;
  		}
  		$formatComment .= "\n\n";
  	}
  	return $formatComment;
  }
  
  /**
   * Called by ExportBaseComponent::buildCommentOutput($input); helper function 
   * that matches an evaluator's mix eval comment with the corresponding question.
   * 
   * @param $comment : $comment taken directly from the $input in the 
   * 				   ExportBaseComponent::buildCommentOutput($input) function.
   * @param INT $questionNum : question number of a particular mix eval question. 
   */
  function getMatchingCommentHelper($comment, $questionNum) {
  	$subArray = array();
  	foreach($comment as $c){
  		if($questionNum == $c['question_number'])
  			array_push($subArray, $c);
  	}
  	return $subArray;
  }  
  
  function testSimple($grpEventId, $selectParams) {
  	
	$this->User = ClassRegistry::init('User');
	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
	$this->GroupEvent = ClassRegistry::init('GroupEvent');
	$this->Group = ClassRegistry::init('Group');
	
	$formatComment = '';
	$evalType = $this->GroupEvent->getEvalType($grpEventId);
	$groupEvent = $this->GroupEvent->getGrpEvent($grpEventId);
	// Assert that this groupEvent is a simple evaluation evalType == 1
	if(1 != $evalType){
	  throw new Exception("[   $.grpEvent id DOES NOT CORRESPOND TO A SIMPLE EVALUATION EVENT, PLEASE CHANGE $.grpEventId TO A CORRESPONDING SIMPLE EVALUATION EVENT!   ]");
	}
	
	$group = $this->Group->getGroupById($grpEventId['group_id']);
	// Fills in group name parameter
	!empty($selectParams['include_group_names']) ? $formatComment .= $group['group_name']."\n\n" :
												   $formatComment .= '';
												   
	$groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
	$i = 0;
	for($i; $i<count($groupMembers); $i++){
		$user = $this->User->findUserByidWithFields($groupMembers[$i]['GroupsMembers']['user_id']);
		$groupMembers[$i]['User'] = $user;
	}
	// If student_id option is selected
	if(!empty($selectParams['include_student_id'])){
		$formatComment .= ",,,";
		foreach($groupMembers as $participant){
			$formatComment .= ",".$participant['User']['student_no'];	
		}
		$formatComment .= "\n";
	}
	$formatComment .= ",Evaluatee\Evaluator,";
	if(!empty($selectParams['include_student_name'])){
	  
	}
	
	foreach($groupMembers as $participant){
		$formatComment .= ",".$participant['User']['first_name'];
	}
	foreach($groupMembers as $evaluatee){
		$evaluatee = $evaluatee['User'];
		$formatComment .= $evaluatee['student_no'].','.$evaluatee['first_name'].$evaluatee['last_name'];
		$evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['id']);
		foreach($evalResults as $result){
			if(isset($result['EvaluationSimple']))
				$formatComment .= ",".$result['EvaluationSimple']['score'];
			else
				$formatComment .= ",";
		}
		$formatComment .= ",\n";
	}
	return $formatComment;
  }

}
?>