<?php
/* SVN FILE: $Id$ */
/*
 * To use your Model’s inside of your components, you can create a new instance like this:
 *  $this->foo = new Foo;
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class EvaluationSimpleHelperComponent extends Object
{
	var $components = array('rdAuth', 'EvaluationHelper', 'EvaluationResult');

  function saveSimpleEvaluation($params=null, $groupEvent=null, $evaluationSubmission=null)
  {
    $this->EvaluationSimple = new EvaluationSimple;
    $this->EvaluationSubmission = new EvaluationSubmission;
    $this->GroupEvent = new GroupEvent;

    // assuming all are in the same order and same size
		$evaluatees = $params['form']['memberIDs'];
		$points = $params['form']['points'];
		$comments = $params['form']['comments'];
		$evaluator = $params['data']['Evaluation']['evaluator_id'];
		$evaluateeCount = $params['form']['evaluateeCount'];

		// create Evaluations for each evaluator-evaluatee pair
		$marks = array();
		$pos = 0;
		foreach($evaluatees as $key=>$value) {
		  $evalMarkRecord = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee($groupEvent['GroupEvent']['id'],
		                                                                                           $evaluator, $value);
		  if (empty($evalMarkRecord)) {
		    $evalMarkRecord['EvaluationSimple']['evaluator'] = $evaluator;
		    $evalMarkRecord['EvaluationSimple']['evaluatee'] = $value;
		    $evalMarkRecord['EvaluationSimple']['grp_event_id'] = $groupEvent['GroupEvent']['id'];
		    $evalMarkRecord['EvaluationSimple']['event_id'] = $groupEvent['GroupEvent']['event_id'];
        $evalMarkRecord['EvaluationSimple']['release_status'] = 0;
        $evalMarkRecord['EvaluationSimple']['grade_release'] = 0;
		  }
      $evalMarkRecord['EvaluationSimple']['score'] = $points[$pos];
      $evalMarkRecord['EvaluationSimple']['eval_comment'] = $comments[$pos];
      $evalMarkRecord['EvaluationSimple']['date_submitted'] = date('Y-m-d H:i:s');

      if (!$this->EvaluationSimple->save($evalMarkRecord))
      {
        return false;
      }
      $this->EvaluationSimple->id=null;
      $pos++;
		}

		// if no submission exists, create one
    $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEvent['GroupEvent']['id'];
    $evaluationSubmission['EvaluationSubmission']['event_id'] = $groupEvent['GroupEvent']['event_id'];
    $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
		// save evaluation submission
    $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
    $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
		if (!$this->EvaluationSubmission->save($evaluationSubmission))
		{
		  return false;
		}

		//checks if all members in the group have submitted
		//the number of submission equals the number of members
		//means that this group is ready to review
		$memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent['GroupEvent']['group_id'],
		                                                                           $groupEvent['GroupEvent']['id']);
		$numOfCompletedCount = $memberCompletedNo[0][0]['count'];
    //Check to see if all members are completed this evaluation
		if($numOfCompletedCount == $evaluateeCount ){
		  $groupEvent['GroupEvent']['marked'] = 'to review';
		  if (!$this->GroupEvent->save($groupEvent))
		  {
		    return false;
		  }
		}
    return true;
  }

	function formatStudentViewOfSimpleEvaluationResult($event=null)
	{
	  $this->EvaluationSimple = new EvaluationSimple;
	  $this->GroupsMembers = new GroupsMembers;
	  $gradeReleaseStatus = 0;
	  $aveScore = 0; $groupAve = 0;
	  $studentResult = array();

	  $results = $this->EvaluationSimple->getResultsByEvaluatee($event['group_event_id'], $this->rdAuth->id);
		if ($results !=null) {
       //Get Grade Release: grade_release will be the same for all evaluatee records
       $gradeReleaseStatus = $results[0]['EvaluationSimple']['grade_release'];
       if ($gradeReleaseStatus) {
          //Grade is released; retrieve all grades

          //Get total mark each member received
  				$receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore($event['group_event_id'],
  				                                                                         $this->rdAuth->id);
  				$totalScore = $receivedTotalScore[0]['received_total_score'];

  				$numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->find(count,'group_id='.$event['group_id']) :
  				                                           $this->GroupsMembers->find(count,'group_id='.$event['group_id']) - 1;
          $aveScore = $totalScore / $numMembers;
          $studentResult['numMembers'] = $numMembers;
          $studentResult['receivedNum'] = count($receivedTotalScore);

          $groupTotal = $this->EvaluationSimple->getGroupResultsByGroupEventId($event['group_event_id']);
          $groupTotalCount = $this->EvaluationSimple->getGroupResultsCountByGroupEventId($event['group_event_id']);
          $groupAve = $groupTotal[0]['received_total_score'] / $groupTotalCount[0]['received_total_count'];
       }

        $studentResult['aveScore'] = $aveScore;
        $studentResult['groupAve'] = $groupAve;

       //Get Comment Release: release_status will be the same for all evaluatee
       $commentReleaseStatus = $results[0]['EvaluationSimple']['release_status'];
       if ($commentReleaseStatus) {
         //Comment is released; retrieve all comments
          $comments = $this->EvaluationSimple->getAllComments($event['group_event_id'], $this->rdAuth->id);
          if (shuffle($comments)) {
            $studentResult['comments'] = $comments;
          }
      		$studentResult['commentReleaseStatus'] = $commentReleaseStatus;
       }

		}
		$studentResult['gradeReleaseStatus'] = $gradeReleaseStatus;

		return $studentResult;
	}

  function changeEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationSimple = new EvaluationSimple;
    $this->GroupEvent = new GroupEvent;
    
		$courseId = $this->rdAuth->courseId;

    //changing grade release for each EvaluationSimple
 		$evaluationMarkSimples = $this->EvaluationSimple->getResultsByEvaluatee($groupEventId, $evaluateeId);
 		foreach ($evaluationMarkSimples as $row) {
 			$evalMarkSimple = $row['EvaluationSimple'];
 			if( isset($evalMarkSimple) ) {
				$this->EvaluationSimple->setId($evalMarkSimple['id']);
 				$evalMarkSimple['grade_release'] = $releaseStatus;
 				$this->EvaluationSimple->save($evalMarkSimple);
 			}
 		}

		//changing grade release status for the GroupEvent
		$this->GroupEvent->setId($groupEventId);
		$oppositGradeReleaseCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
	  $groupEvent = $this->EvaluationHelper->formatGradeReleaseStatus($this->GroupEvent->read(), $releaseStatus,
	                                                                  $oppositGradeReleaseCount);
	  $this->GroupEvent->save($groupEvent);

		
  }
  
  function changeEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluatorIds, $params) {
    
    $this->GroupEvent = new GroupEvent;
    $this->EvaluationSimple = new EvaluationSimple;
    
		$this->GroupEvent->setId($groupEventId);
		$groupEvent = $this->GroupEvent->read();
		$courseId = $this->rdAuth->courseId;

    //handle comment release by "Save Change"
		$evaluator = null;
		if($params['form']['submit']=='Save Changes') {
		  //Reset all release status to false first
			$this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);
			foreach($evaluatorIds as $key=>$value) {
  			if ($evaluator != $value) {
				  //Check for released guys
					if (isset($params['form']['release'.$value])) {
							$evaluateeIds = $params['form']['release'.$value];
							$idString = '';
							$pos = 1;
							foreach($evaluateeIds as $index=>$id) {
							  $idString .= $id;
								if ($pos < count($evaluateeIds)) {
							     $idString .= ',';
								}
								$pos ++;
							}
							$this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 1, $value, $idString);
						}
  				$evaluator = $value;
					$idString = '';
				}
			}
  		//check grade release status for the GroupEvent
  		$oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 1);
  		if ($oppositCommentReleaseCount == 0) {
  		  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($groupEvent, 1, $oppositCommentReleaseCount);
  		} else {
  		  $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 0);
  		  if ($oppositCommentReleaseCount == 0) {
    		  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($groupEvent, 0, $oppositCommentReleaseCount);
    		} else {
    		  $groupEvent['GroupEvent']['comment_release_status'] = "Some";
    		}
  		}

		} else if($params['form']['submit']=='Release All') {
		  //Reset all release status to false first
			$this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 1);

  		//changing grade release status for the GroupEvent
  		$oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 1);
  	  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($groupEvent, 1, $oppositCommentReleaseCount);


    } else if($params['form']['submit']=='Unrelease All') {
		  //Reset all release status to false first
			$this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);

  		//changing grade release status for the GroupEvent
  		$oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 0);
  	  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($groupEvent, 0, $oppositCommentReleaseCount);

    }
  	$this->GroupEvent->save($groupEvent);

  }  
  
	function formatSimpleEvaluationResult($event=null)
	{
	  $this->GroupsMembers = new GroupsMembers;
	  $this->EvaluationSimple = new EvaluationSimple;
	  $this->EvaluationSubmission = new EvaluationSubmission;
	  $result = array();
	  
     //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'],
                                                               $event['Event']['self_eval'], $this->rdAuth->id);
                                                               
		// get comment records - do changes to records above this.
		$commentRecords = array();
		$memberScoreSummary = array();
    $inCompletedMembers = array();
    $allMembersCompleted = true;
    if ($event['group_event_id'] && $groupMembers) {
	    $pos = 0;
			foreach ($groupMembers as $user)   {
			  //Check if this memeber submitted evaluation
			  $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['group_event_id'],
			                                                                                        $user['User']['id']);
			                                                                                        			  
			  if (empty($evalSubmission['EvaluationSubmission'])) {                                                                                      			  
			     $allMembersCompleted = false;
			     $inCompletedMembers[$pos++]=$user;
			  }
				$evalResult[$user['User']['id']] = $this->EvaluationSimple->getResultsByEvaluator($event['group_event_id'],
				                                                                                      $user['User']['id']);

		    //Get total mark each member received
				$receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore($event['group_event_id'],
				                                                                         $user['User']['id']);
				$memberScoreSummary[$user['User']['id']]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];

			}
		}
		$scoreRecords = $this->EvaluationResult->formatSimpleEvaluationResultsMatrix($event, $groupMembers,
		                                                                             $evalResult);

    $gradeReleaseStatus = $this->EvaluationSimple->getTeamReleaseStatus($event['group_event_id']);
		$result['scoreRecords'] = $scoreRecords;
		$result['memberScoreSummary'] = $memberScoreSummary;
		$result['evalResult'] = $evalResult;
		$result['groupMembers'] = $groupMembers;
		$result['inCompletedMembers'] = $inCompletedMembers;
		$result['allMembersCompleted'] = $allMembersCompleted;
		$result['gradeReleaseStatus'] = $gradeReleaseStatus;
		return $result;
	}

  
}?>
