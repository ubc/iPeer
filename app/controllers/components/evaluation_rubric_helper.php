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
class EvaluationRubricHelperComponent extends Object
{
	var $components = array('EvaluationHelper', 'rdAuth');

  function loadRubricEvaluationDetail ($event)
  {
    $this->EvaluationRubric = new EvaluationRubric;
    $this->GroupsMembers = new GroupsMembers;
    $this->EvaluationRubricDetail = new EvaluationRubricDetail;
    $this->Rubric = new Rubric;

    $result = array();
 	  $evaluator = $this->rdAuth->id;

    //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                               $this->rdAuth->id);
    for ($i = 0; $i<count($groupMembers); $i++) {
       $targetEvaluatee = $groupMembers[$i]['User']['id'];
       $evaluation = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee($event['group_event_id'],
                                                                                          $evaluator, $targetEvaluatee);

       if (!empty($evaluation)) {
         $groupMembers[$i]['User']['Evaluation'] = $evaluation;

         $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] = $this->EvaluationRubricDetail->getAllByEvalRubricId
                                                                              ($evaluation['EvaluationRubric']['id']);
        }
    }
		//$this->set('groupMembers', $groupMembers);
		$result['groupMembers'] = $groupMembers;

    //Get the target rubric
	  $this->Rubric->setId($event['Event']['template_id']);
    //$this->set('rubric', $this->Rubric->read());
    $result['rubric'] = $this->Rubric->read();

 		// enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
 		$numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->findCount('group_id='.$event['group_id']) :
 		                                           $this->GroupsMembers->findCount('group_id='.$event['group_id']) - 1;
		//$this->set('evaluateeCount', $numMembers);
		$result['evaluateeCount'] = $numMembers;

		return $result;
  }

  function saveRubricEvaluation($params=null)
  {
    $this->Event = new Event;
    $this->Rubric = new Rubric;
    $this->EvaluationRubric = new EvaluationRubric;

    // assuming all are in the same order and same size
		$evaluatees = $params['form']['memberIDs'];
		$evaluator = $params['data']['Evaluation']['evaluator_id'];
    $groupEventId = $params['form']['group_event_id'];
    $rubricId = $params['form']['rubric_id'];

    //Get the target event
    $eventId = $params['form']['event_id'];
	  $this->Event->setId($eventId);
		$event = $this->Event->read();

		//Get simple evaluation tool
		$this->Rubric->setId($event['Event']['template_id']);
		$rubric = $this->Rubric->read();

		// Save evaluation data
		// total grade for evaluatee from evaluator
		$totalGrade = 0;
		$targetEvaluatee = null;
		for($i=0; $i<count($evaluatees); $i++) {
		  if (isset($params['form'][$evaluatees[$i]]) && $params['form'][$evaluatees[$i]] = 'Save This Section') {
		    $targetEvaluatee = $evaluatees[$i];
		  }
	  }

		$evalRubric = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee($groupEventId, $evaluator,
		                                                                                   $targetEvaluatee);
		if (empty($evalRubric)) {
		    //Save the master Evalution Rubric record if empty
		    $evalRubric['EvaluationRubric']['evaluator'] = $evaluator;
		    $evalRubric['EvaluationRubric']['evaluatee'] = $targetEvaluatee;
		    $evalRubric['EvaluationRubric']['grp_event_id'] = $groupEventId;
		    $evalRubric['EvaluationRubric']['event_id'] = $eventId;
		    $evalRubric['EvaluationRubric']['rubric_id'] = $rubricId;
        $evalRubric['EvaluationRubric']['release_status'] = 0;
        $evalRubric['EvaluationRubric']['grade_release'] = 0;
        $this->EvaluationRubric->save($evalRubric);
        $evalRubric['EvaluationRubric']['id']=$this->EvaluationRubric->id;
        $evalRubric= $this->EvaluationRubric->read();

		 }

     $evalRubric['EvaluationRubric']['general_comment'] = $params['form'][$targetEvaluatee.'gen_comment'];
		 $score = $this->saveNGetEvalutionRubricDetail($evalRubric['EvaluationRubric']['id'], $rubric,
		                                               $targetEvaluatee, $params['form']);
     $evalRubric['EvaluationRubric']['score'] = $score;
     
     if (!$this->EvaluationRubric->save($evalRubric))
     {
       return false;
     }
    return true;
  }

  function saveNGetEvalutionRubricDetail ($evalRubricId, $rubric, $targetEvaluatee, $form)
  {
    $this->EvaluationRubricDetail = new EvaluationRubricDetail;
    $isCheckBoxes = false;
    $totalGrade = 0;
    $pos = 0;

    for($i=1; $i <= $rubric['Rubric']['criteria']; $i++) {
      //TODO: LOM = 1
  		if ($rubric['Rubric']['lom_max'] == 1 ) {
  			$form[$targetEvaluatee."selected$i"] = ($form[$targetEvaluatee."selected$i"] ? $form[$targetEvaluatee."selected$i"] : 0);
  		}

  		// get total possible grade for the criteria number ($i)
  		$grade = $form[$targetEvaluatee.'criteria_points_'.$i];
  		$selectedLom = $form['selected_lom_'.$targetEvaluatee.'_'.$i];
  		$evalRubricDetail = $this->EvaluationRubricDetail->getByEvalRubricIdCritera($evalRubricId, $i);
      if (isset($evalRubricDetail)) {
        $this->EvaluationRubricDetail->id=$evalRubricDetail['EvaluationRubricDetail']['id'] ;
      }
      $evalRubricDetail['EvaluationRubricDetail']['evaluation_rubric_id'] = $evalRubricId;
      $evalRubricDetail['EvaluationRubricDetail']['criteria_number'] = $i;
      $evalRubricDetail['EvaluationRubricDetail']['criteria_comment'] = $form[$targetEvaluatee."comments"][$pos++];
      $evalRubricDetail['EvaluationRubricDetail']['selected_lom'] = $selectedLom;
      $evalRubricDetail['EvaluationRubricDetail']['grade'] = $grade;
      $this->EvaluationRubricDetail->save($evalRubricDetail);
      $this->EvaluationRubricDetail->id=null;
  		$totalGrade += $grade;
    }
    return $totalGrade;
  }

  function getRubricResultDetail ($event, $groupMembers) {
	  $pos = 0;
	  $this->EvaluationSubmission = new EvaluationSubmission;
	  $this->EvaluationRubric  = new EvaluationRubric;
    $this->EvaluationRubricDetail   = new EvaluationRubricDetail;
    $rubricResultDetail = array();
		$memberScoreSummary = array();
		$allMembersCompleted = true;
		$inCompletedMembers = array();
    $evalResult = array();

	  if ($event['group_event_id'] && $groupMembers) {
//print_r($groupMembers);
			foreach ($groupMembers as $user)   {
	      $userPOS = 0;
	      if (isset($user['id'])) {
	        $userId = $user['id'];
	      } elseif (isset($user['User'])) {
	        $userId = $user['User']['id'];
	      //}
	      //$userId = isset($user['User'])? $user['User']['id'] : $user['id'];
			  //Check if this memeber submitted evaluation
			  $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['group_event_id'], $userId);
			  if (isset($evalSubmission['EvaluationSubmission'])) {

			  } else {
			     $allMembersCompleted = false;
			     $inCompletedMembers[$pos++]=$user;
			  }
			    $rubricResult = $this->EvaluationRubric->getResultsByEvaluatee($event['group_event_id'], $userId);
				  $evalResult[$userId] = $rubricResult;

  		    //Get total mark each member received
  				$receivedTotalScore = $this->EvaluationRubric->getReceivedTotalScore($event['group_event_id'],
  				                                                                     $userId);

          $ttlEvaluatorCount = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($event['group_event_id'],
                                                                                       $userId);
  				$memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
  				if ($ttlEvaluatorCount[0]['ttl_count'] == 0) {
  				  $memberScoreSummary[$userId]['received_ave_score'] = 0;
  				} else {
  			    $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] /
  			                                                                   $ttlEvaluatorCount[0]['ttl_count'];
  			                                                                  }
    	    foreach($rubricResult AS $row ){
    	      $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
            if ($evalMark!=null) {
    			    $rubriDetail = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalMark['id']);
    			    $evalResult[$userId][$userPOS++]['EvaluationRubric']['details'] = $rubriDetail;
    			  }
    			}

    		}
			}
		}
  	$rubricResultDetail['scoreRecords'] =  $this->formatRubricEvaluationResultsMatrix($event, $groupMembers, $evalResult);
  	$rubricResultDetail['allMembersCompleted'] = $allMembersCompleted;
  	$rubricResultDetail['inCompletedMembers'] = $inCompletedMembers;
  	$rubricResultDetail['memberScoreSummary'] = $memberScoreSummary;
  	$rubricResultDetail['evalResult'] = $evalResult;

  	return $rubricResultDetail;
   }

  function getStudentViewRubricResultDetailReview ($event, $userId) {
	  $userPOS = 0;
	  $this->EvaluationSubmission = new EvaluationSubmission;
	  $this->EvaluationRubric  = new EvaluationRubric;
    $this->EvaluationRubricDetail   = new EvaluationRubricDetail;
    $this->User = new User;

    $rubricResultDetail = array();
		$memberScoreSummary = array();
		$allMembersCompleted = true;
		$inCompletedMembers = array();
    $this->User->setId($userId);
    $user = $this->User->read();
	  if ($event['group_event_id'] && $userId) {
			    $rubricResult = $this->EvaluationRubric->getResultsByEvaluator($event['group_event_id'], $userId);
				  $evalResult[$userId] = $rubricResult;

    	    foreach($rubricResult AS $row ){
    	      $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
            if ($evalMark!=null) {
    			    $rubriDetail = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalMark['id']);
    			    $evalResult[$userId][$userPOS++]['EvaluationRubric']['details'] = $rubriDetail;
    			  }
    			}
		}
  	return $evalResult;
   }

 	function formatRubricEvaluationResultsMatrix($event, $groupMembers, $evalResult) {
		//
		// results matrix format:
		// Matrix[evaluatee_id][evaluator_id] = score
		//
		$matrix = array();
		$groupCriteriaAve = array();

	  foreach($evalResult AS $index => $value){
	    $evalMarkArray = $value;
	    $evalTotal = 0;
      $rubricCriteria = array();

      if ($evalMarkArray!=null) {
        $grade_release = 1;

  	    foreach($evalMarkArray AS $row ){
  	      $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
          if ($evalMark!=null) {
    				$grade_release = $evalMark['grade_release'];
    				$comment_release = $evalMark['comment_release'];
						if ($index == $evalMark['evaluatee'] ) {

							$matrix[$index]['grade_released'] =$grade_release;
							$matrix[$index]['comment_released'] =$comment_release;
						}
         		$detailPOS = 0;

        	  //Format the rubric criteria
        	  foreach ($evalMark['details'] AS $detail) {
        	    $rubricResult = $detail['EvaluationRubricDetail'];
	            if (!isset($rubricCriteria[$rubricResult['criteria_number']])) $rubricCriteria[$rubricResult['criteria_number']] = 0;
        	      $rubricCriteria[$rubricResult['criteria_number']] += $rubricResult['grade'];
        	      $detailPOS ++ ;
        	  }
      	  } else{
      	    //$matrix[$index][$evalMark['evaluatee']] = 'n/a';
      	  }


    	  }
    	}	else {
				foreach ($groupMembers as $user) {
					$matrix[$index][$user['User']['id']] = 'n/a';
				}
			}
      //Get Ave Criteria Grade
      foreach ($rubricCriteria AS $criIndex => $criGrade) {
        if (!isset($groupCriteriaAve[$criIndex])) $groupCriteriaAve[$criIndex] = 0;
        $ave = $criGrade / $detailPOS;
        $rubricCriteria[$criIndex] = $ave;
        $groupCriteriaAve[$criIndex]+= $ave;
      }
      $matrix[$index]['rubric_criteria_ave'] = $rubricCriteria;
	  }

    //Get Group Ave Criteria Grade
    foreach ($groupCriteriaAve AS $groupIndex => $groupGrade) {
      $ave = $groupGrade / count($evalResult);
      $groupCriteriaAve[$groupIndex] = $ave;
    }
    $matrix['group_criteria_ave'] = $groupCriteriaAve;

		return $matrix;
	}



  function changeEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
 	  $this->EvaluationRubric  = new EvaluationRubric;
 	  $this->GroupEvent = new GroupEvent;

    //changing grade release for each EvaluationRubric
 		$evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
 		foreach ($evaluationRubric as $row) {
 			$evalRubric = $row['EvaluationRubric'];
 			if( isset($evalRubric) ) {
				$this->EvaluationRubric->setId($evalRubric['id']);
 				$evalRubric['grade_release'] = $releaseStatus;
 				$this->EvaluationRubric->save($evalRubric);
 			}
 		}

		//changing grade release status for the GroupEvent
		$this->GroupEvent->setId($groupEventId);
		$oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
	  $groupEvent = $this->EvaluationHelper->formatGradeReleaseStatus($this->GroupEvent->read(), $releaseStatus,
	                                                                  $oppositGradeReleaseCount);
	  $this->GroupEvent->save($groupEvent);
	}

  function changeEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
 	  $this->EvaluationRubric  = new EvaluationRubric;
 	  $this->GroupEvent = new GroupEvent;

		$this->GroupEvent->setId($groupEventId);
		$groupEvent = $this->GroupEvent->read();

    //changing comment release for each EvaluationRubric
 		$evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
 		foreach ($evaluationRubric as $row) {
 			$evalRubric = $row['EvaluationRubric'];
 			if( isset($evalRubric) ) {
				$this->EvaluationRubric->setId($evalRubric['id']);
 				$evalRubric['comment_release'] = $releaseStatus;
 				$this->EvaluationRubric->save($evalRubric);
 			}
 		}

		//changing comment release status for the GroupEvent
		$this->GroupEvent->setId($groupEventId);
		$oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
	  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($this->GroupEvent->read(), $releaseStatus,
	                                                                  $oppositGradeReleaseCount);

  	$this->GroupEvent->save($groupEvent);
  }

	function formatRubricEvaluationResult($event=null, $displayFormat='', $studentView=0)
	{
	  $this->Rubric = new Rubric;
	  $this->User = new User;
	  $this->GroupsMembers = new GroupsMembers;
	  $this->RubricsCriteria = new RubricsCriteria;
	  $this->EvaluationRubric = new EvaluationRubric;

	  $evalResult = array();
	  $groupMembers = array();
	  $result = array();

	  $this->Rubric->setId($event['Event']['template_id']);
	  $rubric = $this->Rubric->read();
	  $result['rubric'] = $rubric;


     //Get Members for this evaluation
     if ($studentView) {
       $this->User->setId($this->rdAuth->id);
       $user = $this->User->read();
       $rubricResultDetail = $this->getRubricResultDetail($event, $user);
       $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                                  $this->rdAuth->id);
       $membersAry = array();
       foreach ($groupMembers as $member) {
        $membersAry[$member['User']['id']] = $member;
       }
       $result['groupMembers'] = $membersAry;

       $reviewEvaluations = $this->getStudentViewRubricResultDetailReview($event,
                                                                          $this->rdAuth->id);
		   $result['reviewEvaluations'] = $reviewEvaluations;

     } else {
       $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                                  $this->rdAuth->id);
       $rubricResultDetail = $this->getRubricResultDetail($event, $groupMembers);
       $result['groupMembers'] = $groupMembers;
     }


    //Get Detail information on Rubric score
		if ($displayFormat == 'Detail') {
		  $rubricCriteria = $this->RubricsCriteria->getCriteria($rubric['Rubric']['id']);
		  $result['rubricCriteria'] = $rubricCriteria;
		}
    $gradeReleaseStatus = $this->EvaluationRubric->getTeamReleaseStatus($event['group_event_id']);

    $result['allMembersCompleted'] = $rubricResultDetail['allMembersCompleted'];
    $result['inCompletedMembers'] = $rubricResultDetail['inCompletedMembers'];
		$result['scoreRecords'] = $rubricResultDetail['scoreRecords'];
		$result['memberScoreSummary'] = $rubricResultDetail['memberScoreSummary'];
		$result['evalResult'] = $rubricResultDetail['evalResult'];
    $result['gradeReleaseStatus'] = $gradeReleaseStatus;

    return $result;
	}
}
?>