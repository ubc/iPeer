<?php
/* SVN FILE: $Id$ */
/*
 *
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
App::import('Model', 'Group');
App::import('Model', 'GroupEvent');
class EvaluationHelperComponent
{
  function formatEventObj ($eventId, $groupId=null)
  {
    //Get the target event
    $this->Event = new Event;
	  $this->Event->id = $eventId;
		$event = $this->Event->read();

		//Get the group name
		if ($groupId != null) {
  		$this->Group = new Group;
  	    $this->Group->id = $groupId;

  		$group = $this->Group->read();
  		$event['group_name'] = 'Group '.$group['Group']['group_num'].' - '.$group['Group']['group_name'];
  		$event['group_id'] = $group['Group']['id'];

  	  $this->GroupEvent = new GroupEvent;
   		$groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
  		$event['group_event_id'] = $groupEvent['GroupEvent']['id'];
  		$event['group_event_marked'] = $groupEvent['GroupEvent']['marked'];
		}
    return $event;
  }

 	function formatGradeReleaseStatus($groupEvent, $releaseStatus, $oppositGradeReleaseCount) {
 		$gradeReleaseStatus = $groupEvent['GroupEvent']['grade_release_status'];

    //User clicked to release individual grade
 	  if ($releaseStatus) {

      //Check and update the groupEvent release status
  	  if ($gradeReleaseStatus == 'None') {
  	      $groupEvent['GroupEvent']['grade_release_status'] = "Some";
  	  } else if ($gradeReleaseStatus == 'Some') {
  	      //Check whether all members are released
  	      if ($oppositGradeReleaseCount == 0) {
  	       $groupEvent['GroupEvent']['grade_release_status'] = "All";
  	      }
  	  }
  	} //User clicked unrelease individual grade
  	else {

      //Check and update the groupEvent release status
  	  if ($gradeReleaseStatus == 'Some') {
  	      //Check whether all members' released are none
  	      if ($oppositGradeReleaseCount == 0) {
    	      $groupEvent['GroupEvent']['grade_release_status'] = "None";
    	    }
  	  } else if ($gradeReleaseStatus == 'All') {
   	      $groupEvent['GroupEvent']['grade_release_status'] = "Some";
  	  }
  	}
 	  return $groupEvent;
 	}

  function getGroupReleaseStatus($groupEvent) {

    if (isset($groupEvent)) {
	  	$release = array('grade_release_status'=>$groupEvent['GroupEvent']['grade_release_status'], 'comment_release_status'=>$groupEvent['GroupEvent']['comment_release_status']);
  	}else{
	    $release = array('grade_release_status'=>'None', 'comment_release_status'=>'None');
    }

		return $release;
	}

 	function formatCommentReleaseStatus($groupEvent, $releaseStatus, $oppositCommentReleaseCount) {
 		$commentReleaseStatus = $groupEvent['GroupEvent']['comment_release_status'];

    //User clicked to release individual comment
 	  if ($releaseStatus) {

      //Check and update the groupEvent release status
	    if ($oppositCommentReleaseCount == 0) {
	      $groupEvent['GroupEvent']['comment_release_status'] = "All";
	    } else {
	      $groupEvent['GroupEvent']['comment_release_status'] = "Some";
  	  }
  	} //User clicked unrelease individual grade
  	else {

      //Check whether all members' released are none
      if ($oppositCommentReleaseCount == 0) {
        $groupEvent['GroupEvent']['comment_release_status'] = "None";
      } else {
        $groupEvent['GroupEvent']['comment_release_status'] = "Some";
      }
    }
 	  return $groupEvent;
 	}
 	
 	function getEventType ($eventTemplateTypeId, $field='type_name')
 	{
 	  $this->EventTemplateType = new EventTemplateType;
 	  $this->EventTemplateType->id = $eventTemplateTypeId;
 	  $eventTemplate = $this->EventTemplateType->read();
 	  
 	  return $eventTemplate['EventTemplateType'][$field];
 	}


 	
}

?>