<?php
class ExportHelper2Component extends Object {

  var $name = 'ExportHelper2';
		
  function getGroupMemberHelper($grpEventId) {
  	$this->GroupEvent = ClassRegistry::init('GroupEvent');
  	$this->User = ClassRegistry::init('User');
  	
  	$groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
    $i = 0;
  	foreach($groupMembers as $g){
  	  $fields = array('id', 'first_name', 'last_name', 'student_no', 'email');
  	  $user = $this->User->findUserByidWithFields($g['GroupsMembers']['user_id'], $fields);
      $groupMembers[$i] = $user;
  	  $i++;
  	}
  	return $groupMembers;
  }
  
  function buildExporterGrid($xLim, $yLim) {
	$yAxis = array_fill(0, $yLim, '');
  	$grid = array_fill(0, $xLim, $yAxis);
  	return $grid;  	
  }
  
  function repeatDrawByCoordinateVertical(&$grid ,$initX, $initY, $vertIndexing, $reptition, $value) {
  	while($reptition > 0) {
  	  $grid[$initX][$initY] = $value;
  	  $initY += $vertIndexing;
  	  $reptition--;
  	}
  }
  
  function fillGridHorizonally (&$grid = array(), $xFrom, $yIndex, $values = array()) {
  	$index = count($values);
    for ($i=0; $i<$index; $i++) {
      $grid[$xFrom+$i][$yIndex] = $values[$i];
    }
  }
  
  function fillGridVertically (&$grid = array(), $yFrom, $xIndex, $values = array()) {
	$index = count($values);
  	for ($i=0; $i<$index; $i++) {
  	  $grid[$xIndex][$yFrom + $i] = $values[$i];
  	}
  }
  
  function arrayDraw($grid) {
  	$formatCSV = '';
  	$xLim = count($grid);
  	$yLim = count($grid[0]);
  	for ($y=0; $y<$yLim; $y++) {
	  for ($x=0; $x<$xLim-1; $x++) {
	  	$formatCSV .= $grid[$x][$y].",";
	  }
	$formatCSV .= "\n";  
  	}
  	return $formatCSV;
  }
  
  function arrayDrawMod($modGrid) {
  	$csv = '';
  	for($row=0 ;$row<count($modGrid); $row++) {
  	  for($cell=0; $cell<count($modGrid[$row]); $cell++) {
  	  	$csv .= $modGrid[$row][$cell];
  	  }
  	}
  	return $csv;
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
  
  function formatEvaluateeHeaderArray($params, $evaluatee) {
  	$evaluateeHeader = array("Evaluatee :");
  	if(!empty($params['include_student_name'])) {
  	  /*array_push($evaluateeHeader, $evaluatee['last_name']);
  	  array_push($evaluateeHeader, $evaluatee['first_name']);*/
  	  array_push($evaluateeHeader, $evaluatee['first_name'].' '.$evaluatee['last_name']);
  	}
  	if(!empty($params['include_student_id'])) {
  	  array_push($evaluateeHeader, $evaluatee['student_no']);
  	}
  	if(!empty($params['include_student_email'])) {
  	  array_push($evaluateeHeader, $evaluatee['email']);
  	}
  	return $evaluateeHeader;
  }
  
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
	$evaluationType == 2 ? ($questions = $this->RubricsCriteria->getCriteria($evaluationId)) &&
						   ($evalType = 'RubricsCriteria') : 
						   ($questions = $this->MixevalsQuestion->getQuestion($evaluationId, 'S')) &&
						   ($evalType = 'MixevalsQuestion');
	//Store the $evaluationType
	$questions[0][$evalType]['evaluation_type'] = $evaluationType;
	//$questions['evaluation_type'] = $evaluationType;
	return $questions;
  }
  
  function formatEvaluatorsHeaderArray($evaluators) {
  	$first_name = array(); $last_name = array(); $student_no = array();
  	$headerArray['name'] = array();
  	$headerArray['last_name'] = array();
  	$headerArray['first_name'] = array();
  	$headerArray['student_no'] = array();
  	$headerArray['email'] = array();
  	
  	foreach($evaluators as $e) {
  	//	array_push($headerArray['first_name'], $e['first_name'].' '.$e['last_name']);
  		array_push($headerArray['name'], $e['first_name'].' '.$e['last_name']);
  	//	array_push($headerArray['last_name'], $e['last_name']);
  		array_push($headerArray['student_no'], $e['student_no']);
  		array_push($headerArray['email'], $e['email']);
  	}
	return $headerArray;
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
  
  function unsetMultipleGridRow(&$grid, $initRowNum, $repetition, $indexing) {
  	$spacing = 0;
  	while($repetition > 0){
  	  unset($grid[$initRowNum + $spacing]);
  	  $spacing += $indexing;
  	  $repetition--;
  	}
  }
  
  function createGroupMemberArrayBlock($groupMembers, $parms) {
  	$memberCount = count($groupMembers);
  	$grpMemberBlock = array_fill(0, $memberCount, array());
  	if(!empty($params['include_student_name'])) {
  	  for($i=0; $i<$memberCount; $i++) {
  	  	array_push($grpMemberBlock[$i], $groupMembers[$i]['last_name']);
  	  	array_push($grpMemberBlock[$i], $groupMembers[$i]['first_name']);
  	  }
  	}
  	if(!empty($params['include_student_id'])) {
  	  for($i=0; $i<$memberCount; $i++) {
  	  	array_push($grpMemberBlock[$i], $groupMembers[$i]['student_no']);
  	  }
  	}
  	return $grpMemberBlock;
  }
}
	