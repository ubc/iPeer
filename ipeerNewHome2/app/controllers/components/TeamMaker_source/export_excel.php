<?php
Class ExportExcelComponent extends ExportBaseNewComponent {

  function createExcel($params, $eventId) {
  	$this->Event = ClassRegistry::init('Event');
  	$this->Group = ClassRegistry::init('Group');
  	// Prepare header.
  	$CSV = '';
	$header = $this->generateHeader($params, $eventId);
	$CSV .= $header."\n";
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
  	      	$CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n";
  	      }
  	      if(!empty($params['simple_eval_grade_table'])){
  	        $simpleResults = $this->buildSimpleEvalResults($groupEvents[$i]['GroupEvent']['id'], $params);
  	        $CSV .= $simpleResults."\n\n";
  	      }
  	      if(!empty($params['simple_evaluator_comment'])) {
  	      	$CSV .= ", Simple Evaluation Comments :\n\n";
  	      	foreach($groupMembers as $evaluatee) {
			  $simpleEvalComments = $this->buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'S');
			  $CSV .= $simpleEvalComments."\n";   		
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
		    $CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n\n";
		  }	
		  if(!empty($params['rubric_criteria_marks'])) {
		  	$CSV .= "Rubrics Evaluation Grade Tables:\n\n";
			$gradeTable = $this->buildRubricsResultTable($params, $grpEventId);
			$CSV .= $gradeTable."\n";
		  }
		  if(!empty($params['rubric_general_comments'])) {
		  	$CSV .= "Rubrics General Comments\n\n";
		    foreach($groupMembers as $evaluatee) {
			  $rubricGeneralComments = $this->buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'R');
			  $CSV .= $rubricGeneralComments."\n";    	      		
  	      	}  	      		 
		  }
  	  	}
  	  	return $CSV;
  	  break;
  	  
  	  // Mixed Evaluation Event
  	  case 4 :
  	  	for ($i=0; $i<count($groupEvents); $i++) {
		  $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
		  $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
		  $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);		  
		  if(!empty($params['include_group_names'])) {
		    $CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n\n\n";
		  }
		  if(!empty($params['include_mixeval_grades'])) {
		  	$CSV .= "Part 1: Licket Scale Questions Grade Table :\n\n";
 		  	$gradeTable = $this->buildMixevalResult($params, $groupEvents[$i]['GroupEvent']['id'], $groupMembers[$i]['GroupsMembers']['id']);
 		  	$CSV .= $gradeTable."\n\n";
 		  }		  
 		  if (!empty($params['include_mixeval_question_comment'])) {
 		  	$CSV .= "Part 2: Comment Question Results :\n\n";
 		  	$questionComments = $this->buildMixEvalQuestionCommentTable($params, $grpEventId);
 		  	$CSV .= $questionComments."\n\n";
 		  }
  	  	}
  	  break;  
  	  default: throw new Exception("Event id input seems to be invalid!");
  	}
	return $CSV;
  }
	
}
?>