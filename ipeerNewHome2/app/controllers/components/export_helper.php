<?php

class ExportHelperComponent extends Object {
	
	var $name = 'ExportHelper';
	
	/**
	 * Formats $param to a correct formatting style to be used as 
	 * "comment" input for the function ExportBaseComponent::buildCommentOutput()
	 * 
	 * @param $evalType_CHAR: The type of evaluation; SimpleEval(S), Rubric(R), MixEval(M)
	 * @param $data_ARRAY: $data input can be obtained from any of the fallowing function calls:
	 * 						$data <= EvaluationSimple::getAllComments()
	 *					   	$data <= EvaluationRubric::getAllComments()
	 *						$data <= EvaluationMixevalDetail::getResultsDetailByEvaluatee()
	 *
	 * @param return : appropriate $comment input for ExportBaseComponent::buildCommentOutput() 
	 */
 /* function compileCommentInput($data) {
  	$this->User = ClassRegistry::init('User');
  	//$evaluateeInfo = $this->User->findUserByidWithFields($data[0][])
	
  	// Determine the type of evaluation input.
  	$keys = array_keys($data[0]);
  	$evalType = null;
  	$evaluations = array('EvaluationSimple', 'EvaluationRubric', 'EvaluationMixevalDetail');
  	foreach($evaluations as $e){
  		if(in_array($e, $keys))
  			$evalType = $e;
  	}
	
	// Make sure the evalType is either 'Simple', 'Rubrics', or 'Mixed'.
	if(isset($evalType)){
		$formatted = array();
		if($evalType == 'EvaluationMixevalDetail'){
			$i = 0;
			foreach($data as $result){
				// Only get $commentType == 0, which are written question comments.
				$commentType = $result['EvaluationMixevalDetail']['selected_lom'];
				if($commentType == 0){
					// Make sure the evaluator who wrote the comment is set.
					if(isset($result['User'])){
						$evaluationMixevalDetail = $result['EvaluationMixevalDetail'];
						$evaluationMixeval = $result['EvaluationMixeval'];
						$evaluator = $result['User'];
						
						$params = array('first_name', 'last_name', 'student_no');
						$evaluateeInfo = $this->User->findUserByidWithFields($evaluationMixeval['evaluateeId'], $params);
						
						$formatted[$i]['evaluatee'] = $evaluateeInfo;
						$formatted[$i]['evaluator_first_name'] = $evaluator['evaluator_first_name'];
						$formatted[$i]['evaluator_last_name'] = $evaluator['evaluator_last_name'];
						$formatted[$i]['evaluator_student_no'] = $evaluator['evaluator_student_no'];
						$formatted[$i]['question_number'] = $evaluationMixevalDetail['question_number'];
						$formatted[$i]['grade'] = $evaluationMixevalDetail['grade'];
						$formatted[$i]['question_comment'] = $evaluationMixevalDetail['question_comment'];
						$formatted[$i]['event_id'] = $evaluationMixeval['event_id'];
						$i++;
					}
					else
						throw new Exception('ARRAY $data IS MISSING A "USER" ENTRY AT INDEX ['.$i.']!');
				}
			}
			return $formatted;
		}
		// $evalType == 'EvaluationSimple' or 'EvaluationRubic'.
		else{
			$evalType == 'EvaluationSimple' ? $eval = 'EvaluationSimple' : $eval = 'EvaluationRubric';
			$evalType == 'EvaluationSimple' ? $commentType = 'eval_comment' : $commentType = 'general_comment';
			$i = 0;
			foreach($data as $entry){
				// Make sure the evaluator who wrote the comment is set.
				if(isset($entry['User'])){
					$params = array('first_name', 'last_name', 'student_no');
					$evaluateeInfo = $this->User->findUserByidWithFields($entry[$eval]['evaluateeId'], $params);
					
					$formatted[$i]['evaluatee'] = $evaluateeInfo;
					$formatted[$i]['evaluator_first_name'] = $entry['User']['evaluator_first_name'];
					$formatted[$i]['evaluator_last_name'] = $entry['User']['evaluator_last_name'];
					$formatted[$i]['evaluator_student_no'] = $entry['User']['evaluator_student_no'];
					$formatted[$i]['comment'] = $entry[$eval][$commentType];
					$formatted[$i]['event_id'] = $entry[$eval]['event_id'];
					$i++;
				}
				else
					throw new Exception('ARRAY $data IS MISSING A "USER" ENTRY AT INDEX ['.$i.']!');
			}
			return $formatted;
		}
	}
  }*/
  
  /**
   * 
   * Helper function for getting the rubrics criteria or mix eval questions.
   * @param INT $grpEventId : group_event_id
   * @return Evluation question corresponding to the grpEventId
   */
  function getEvaluationQuestions($grpEventId) {
  	$this->Event = ClassRegistry::init('Event');
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
  	$this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
  	
	$groupEvent = $this->GroupEvent->getGrpEvent($grpEventId);
	$eventId = $groupEvent['GroupEvent']['event_id'];
	$event = $this->Event->getEventById($eventId);
	$evaluationId = $event['Event']['template_id'];
	$evaluationType = $event['Event']['event_template_type_id'];
	
	$questions = null; 
	// $EventType = 2(Rubric) or 4(Mix Eval)
	$evaluationType == 2 ? $questions = $this->RubricsCriteria->getCriteria($evaluationId) : 
						   $questions = $this->MixevalsQuestion->getQuestion($evaluationId, 'S');
	//Store the $evaluationType
	$questions[0]['evaluation_type'] = $evaluationType;
		
	return $questions;
  }
  
  function getGroupMemberHelper($grpEventId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->User = ClassRegistry::init('User');
  	
  	$groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
    $i = 0;
  	foreach($groupMembers as $g){
  		$fields = array('first_name', 'last_name', 'student_no', 'email');
  		$user = $this->User->findUserByidWithFields($g['GroupsMembers']['user_id'], $fields);
  		$groupMembers[$i] = $user;
  		$i++;
  	}
  	return $groupMembers;
  }
  
  /**
   * Used by ExportBase::buildRubricsResult(); Generates top row corresponding to the evaluator's 
   * info for rubics/mix eval result table.
   * 
   * @param ARRAY $evaluators : array consisting of the evaluator's info (first name, last name, and student #)
   * @return String $headerRow : formatted headerRow corresponding to $evaluator's info
   */
  function createEvaluatorsHeaderRow($evaluators) {
  	$headerRow = "Evaluators =>,";
  	foreach($evaluators as $e){
  		$headerRow .= ",".$e['first_name'].$e['last_name'];
  	}
  	$headerRow .= "\n,";
  	$headerRow .= "Evaluator Student Num =>,";
  	foreach($evaluators as $e){
  		$headerRow .= ",".$e['student_no'];
  	}
  	$headerRow .= ",,Average Mark For Question";
  	return $headerRow;
  }
  
  function formatEvaluatorsHeaderArray($evaluators) {
  	$first_name = array(); $last_name = array(); $student_no = array();
  	$headerArray['last_name'] = array();
  	$headerArray['email'][0] = "Evaluator's Email :";
  	$headerArray['email'][1] = "";
 	$headerArray['first_name'][0] = "Evaluators :";
	$headerArray['first_name'][1] = '';
	$headerArray['student_no'][0] = "Student Number :";
	$headerArray['student_no'][1] = '';
  	foreach($evaluators as $e) {
  		array_push($headerArray['first_name'], $e['first_name']);
  		array_push($headerArray['last_name'], $e['last_name']);
  		array_push($headerArray['student_no'], $e['student_no']);
  		array_push($headerArray['email'], $e['email']);
  	}
	return $headerArray;
  }
  
  function getResultsDetailHelper($grpEventId, $evaluateeId, $evaluatorId, $evalType, $questionNum){
  	$result=null;
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
  	
  	// $evaluateeId == 4(mix eval), 2(Rubric)
  	$evaluateeId == 4 ?	$result = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluateeId, $evaluatorId, $questionNum) :
  						$result = $this->EvaluationRubric->
  										 getRubricsCriteriaResult($grpEventId, $evaluateeId, $evaluatorId);
  	return $result;
  }
  
  function getMixEvalResult($grpEventId, $evaluator, $evaluatee, $questionNum){
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
  	$this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
  	
  	$evalMix = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee($grpEventId, $evaluator, $evaluatee);
  	$evalMixDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMix['EvaluationMixeval']['id'], $questionNum);
  	return $evalMixDetail;
  }
  
  /**
   * Compiles the necessary data $input for the EvaluationBase:: ... ($input) function.
   * 
   * @param ARRAY $simpleEvalResult: 
   * 				$simpleEvalResult obtained from EvaluationSimple::getResultsByEvaluatee()
   */
  function compileSimpleEvalResultsInput($allSimpleEvalResult){
  	// Check that the $simpleEvalResult input contains a evaluatee index
  	$this->User = ClassRegistry::init('User');
  	if(!isset($simpleEvalResult[0]['EvaluationSimple']['User']))
  		throw new Exception("EVALUATEE WAS NOT SET IN THE INPUT ARRAY,  RESET INPUT EvaluationSimple::getResultsByEvaluatee(.., .., TRUE)");
  	
  	$formatted = array();
  	$fields = array('first_name', 'last_name', 'student_no');
  	$currentEvaluateeId = null;
  	foreach($simpleEvalResult as $participants){
  		$evaluatee = $this->User->findUserByidWithFields($participants['EvaluationSimple']['evaluatee'], $fields);
  		// Fill data horizonatally
  		if($currentEvaluateeId != $evaluatee['id']){
  			
  		}
  	}
  }
  
  function fillGridVertically (&$grid = array(), $yFrom, $yTo, $xIndex, $values = array()) {
  	$difference = $yTo - $yFrom + 1;
  	for ($i=0; $i<$difference; $i++) {
  		$grid[$xIndex][$yIndex + i] = $value[$i];
  	}
  }
  
  function fillGridHorizonally (&$grid = array(), $xFrom, $yIndex, $values = array()) {
  	$index = count($values);
    for ($i=0; $i<$index; $i++) {
      $grid[$xFrom+$i][$yIndex] = $values[$i];
    }
  }
  
  function unsetMultipleGridRow(&$grid, $initRowNum, $repetition, $indexing) {
  	$spacing = 0;
  	while($repetition > 0){
  	  unset($grid[$initRowNum + $spacing]);
  	  $spacing += $indexing;
  	  $repetition--;
  	}
  }
  
  function drawMixOrRubricsGrid($grid, $params, $groupMemberCount) {
    $formatCSV = array();
	$grid = $this->modifyGrid($grid);
	$rowRange = count($grid);
	$colRange = count($grid[0]);
	$sectionSize = 1;
	$i = 1;
	while($grid[$i][0] != "Evaluatee") {
	  $sectionSize++;
	  $i++;
	}
	$evaluatorRow = 2; $evaluateeRow = 0; $emailRow = 5;
	if(empty($params['include_student_name'])){
	  $this->repeatDrawByCoordinateVertical($grid, 0, 1, $sectionSize, $groupMemberCount, '');
	  $this->repeatDrawByCoordinateVertical($grid, 0, 2, $sectionSize, $groupMemberCount, '');
	  $this->unsetMultipleGridRow($grid, $evaluatorRow, $groupMemberCount, $sectionSize);
	  $this->unsetMultipleGridRow($grid, 1 + $evaluatorRow, $groupMemberCount, $sectionSize);
	}
	if(empty($params['include_student_id'])) {
	  $this->repeatDrawByCoordinateVertical($grid, 0, 3, $sectionSize, $groupMemberCount, '');
	  $this->unsetMultipleGridRow($grid, 2 + $evaluatorRow, $groupMemberCount, $sectionSize);
	}
	if(empty($params['email'])) {
	  $this->unsetMultipleGridRow($grid, $emailRow, $groupMemberCount, $sectionSize);
	}
	$formatCSV = array();
	for ($row=0; $row<$rowRange; $row++) {
	  if(isset($grid[$row])) {
	    for ($col=0; $col<$colRange; $col++) {
	      $formatCSV .= $grid[$row][$col].",";
	    }
	    $formatCSV .= "\n";
	  }
	}
	
	
	return $formatCSV;
  }
  
  function repeatDrawByCoordinateHorizontal(&$grid ,$initRow, $initCol, $horIndexing, $reptition, $value) {
  	while($reptition > 0) {
  	  $grid[$initRow][$initCol] = $value;
  	  $initCol += $horIndexing;
  	  $reptition--;
  	}
  }
  
  function repeatDrawByCoordinateVertical(&$grid ,$initRow, $initCol, $vertIndexing, $reptition, $value) {
  	while($reptition > 0) {
  	  $grid[$initRow][$initCol] = $value;
  	  $initRow += $vertIndexing;
  	  $reptition--;
  	}
  }
  
  function arrayDraw($grid) {
  	$formatCSV = array();
  	$xLim = count($grid);
  	$yLim = count($grid[0]);
  	for ($y=0; $y<$yLim; $y++) {
	  for ($x=0; $x<$xLim; $x++) {
	  	$formatCSV .= $grid[$x][$y].",";
	  }
	$formatCSV .= "\n";  
  	}
  	return $formatCSV;
  }
  
  // Swaps x and y axis, temp use for convenience
  function modifyGrid($grid) {
    $modGrid = array();
    $xLim = count($grid);
  	$yLim = count($grid[0]);
    for($y=0 ; $y<$yLim; $y++) {
      $tmp = array();
      for($x=0; $x<$xLim; $x++) {
        array_push($tmp, $grid[$x][$y]);
      }
      array_push($modGrid, $tmp);
    } 
    return $modGrid;
  }
  
  function buildExporterGrid($xLim, $yLim) {
	$yAxis = array_fill(0, $yLim-1, '');
  	$grid = array_fill(0, $xLim-1, $yAxis);
  	return $grid;  	
  }
}

?>