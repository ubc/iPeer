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
class EvaluationMixevalHelperComponent extends Object
{
	var $components = array('EvaluationHelper', 'rdAuth');

  function loadMixEvaluationDetail ($event)
  {
    $this->GroupsMembers = new GroupsMembers;
    $this->EvaluationMixeval = new EvaluationMixeval;
    $this->EvaluationMixevalDetail = new EvaluationMixevalDetail;
    $this->Mixeval = new Mixeval;

    $result = array();
 	  $evaluator = $this->rdAuth->id;

    //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                               $this->rdAuth->id);
    for ($i = 0; $i<count($groupMembers); $i++) {
       $targetEvaluatee = $groupMembers[$i]['User']['id'];
       $evaluation = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee($event['group_event_id'],
                                                                                          $evaluator, $targetEvaluatee);
       if (!empty($evaluation)) {
         $groupMembers[$i]['User']['Evaluation'] = $evaluation;
         $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] = $this->EvaluationMixevalDetail->getAllByEvalMixevalId
                                                                              ($evaluation['EvaluationMixeval']['id']);
        }
    }
		//$this->set('groupMembers', $groupMembers);
		$result['groupMembers'] = $groupMembers;

    //Get the target mixeval

	  $this->Mixeval->setId($event['Event']['template_id']);
    //$this->set('mixeval', $this->Mixeval->read());
    $result['mixeval'] = $this->Mixeval->read();

 		// enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
 		$numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->findCount('group_id='.$event['group_id']) :
 		                                           $this->GroupsMembers->findCount('group_id='.$event['group_id']) - 1;
		//$this->set('evaluateeCount', $numMembers);
		$result['evaluateeCount'] = $numMembers;

		return $result;
  }

  function saveMixevalEvaluation($params=null)
  {
    $this->Event = new Event;
    $this->Mixeval = new Mixeval;
    $this->EvaluationMixeval = new EvaluationMixeval;

    // assuming all are in the same order and same size
		$evaluatees = $params['form']['memberIDs'];
		$evaluator = $params['data']['Evaluation']['evaluator_id'];
    $groupEventId = $params['form']['group_event_id'];

    //Get the target event
    $eventId = $params['form']['event_id'];
	  $this->Event->setId($eventId);
		$event = $this->Event->read();

		//Get simple evaluation tool
		$this->Mixeval->setId($event['Event']['template_id']);
		$mixeval = $this->Mixeval->read();

		// Save evaluation data
		// total grade for evaluatee from evaluator
		$totalGrade = 0;
		$targetEvaluatee = null;
		for($i=0; $i<count($evaluatees); $i++) {
		  if (isset($params['form'][$evaluatees[$i]]) && $params['form'][$evaluatees[$i]] = 'Save This Section') {
		    $targetEvaluatee = $evaluatees[$i];
		  }
	  }

		$evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee($groupEventId, $evaluator,
		                                                                                   $targetEvaluatee);
		if (empty($evalMixeval)) {
		    //Save the master Evalution Mixeval record if empty
		    $evalMixeval['EvaluationMixeval']['evaluator'] = $evaluator;
		    $evalMixeval['EvaluationMixeval']['evaluatee'] = $targetEvaluatee;
		    $evalMixeval['EvaluationMixeval']['grp_event_id'] = $groupEventId;
		    $evalMixeval['EvaluationMixeval']['event_id'] = $eventId;
        $evalMixeval['EvaluationMixeval']['release_status'] = 0;
        $evalMixeval['EvaluationMixeval']['grade_release'] = 0;
        $this->EvaluationMixeval->save($evalMixeval);
        $evalMixeval['EvaluationMixeval']['id']=$this->EvaluationMixeval->id;
        $evalMixeval = $this->EvaluationMixeval->read();

		 }
		 $score = $this->saveNGetEvalutionMixevalDetail($evalMixeval['EvaluationMixeval']['id'], $mixeval,
		                                               $targetEvaluatee, $params['form']);
     $evalMixeval['EvaluationMixeval']['score'] = $score;
     if (!$this->EvaluationMixeval->save($evalMixeval))
     {
       return false;
     }

    return true;
  }

  function saveNGetEvalutionMixevalDetail ($evalMixevalId, $mixeval, $targetEvaluatee, $form)
  {
    $this->EvaluationMixevalDetail = new EvaluationMixevalDetail;
    $isCheckBoxes = false;
    $totalGrade = 0;
    $pos = 0;
    for($i=1; $i < $form['data']['Mixeval']['total_question']; $i++) {

  		$evalMixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMixevalId, $i);
      if (isset($evalMixevalDetail)) {
        $this->EvaluationMixevalDetail->id=$evalMixevalDetail['EvaluationMixevalDetail']['id'] ;
      }
      $evalMixevalDetail['EvaluationMixevalDetail']['evaluation_mixeval_id'] = $evalMixevalId;
      $evalMixevalDetail['EvaluationMixevalDetail']['question_number'] = $i;
      if ($form['data']['Mixeval']['question_type'.$i] == 'S') {
    		// get total possible grade for the question number ($i)
    		$selectedLom = $form['selected_lom_'.$targetEvaluatee.'_'.$i];
    		$grade = $form[$targetEvaluatee.'criteria_points_'.$i];
        $evalMixevalDetail['EvaluationMixevalDetail']['selected_lom'] = $selectedLom;
        $evalMixevalDetail['EvaluationMixevalDetail']['grade'] = $grade;
    		$totalGrade += $grade;
      } else if ($form['data']['Mixeval']['question_type'.$i] == 'T') {
        $evalMixevalDetail['EvaluationMixevalDetail']['question_comment'] = $form["response_text_".$targetEvaluatee."_".$i];
      }
      $this->EvaluationMixevalDetail->save($evalMixevalDetail);
      $this->EvaluationMixevalDetail->id=null;
    }
    return $totalGrade;
  }

  function getMixevalResultDetail ($event, $groupMembers) {
	  $pos = 0;
	  $this->EvaluationSubmission = new EvaluationSubmission;
	  $this->EvaluationMixeval  = new EvaluationMixeval;
    $this->EvaluationMixevalDetail   = new EvaluationMixevalDetail;
    $mixevalResultDetail = array();
		$memberScoreSummary = array();
		$allMembersCompleted = true;
		$inCompletedMembers = array();
    $evalResult = array();

	  if ($event['group_event_id'] && $groupMembers) {
			foreach ($groupMembers as $user)   {
	      $userPOS = 0;
	      if (isset($user['id'])) {
	        $userId = $user['id'];
	      } elseif (isset($user['User'])) {
	        $userId = $user['User']['id'];
  	      //$userId = isset($user['User'])? $user['User']['id'] : $user['id'];
  			  //Check if this memeber submitted evaluation
  			  $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['group_event_id'],
  			                                                                                        $userId);
  			  
  			 // if (isset($evalSubmission['EvaluationSubmission'])) {
  			    $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluatee($event['group_event_id'], $userId);
  				  $evalResult[$userId] = $mixevalResult;

    		    //Get total mark each member received
    				$receivedTotalScore = $this->EvaluationMixeval->getReceivedTotalScore($event['group_event_id'],
    				                                                                     $userId);
            $ttlEvaluatorCount = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount($event['group_event_id'],
                                                                                         $userId);
            if ($ttlEvaluatorCount[0]['ttl_count'] >0 ) {
      				$memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
      			  $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] /
      			                                                                   $ttlEvaluatorCount[0]['ttl_count'];
      			}
      	    foreach($mixevalResult AS $row ){
      	      $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
              if ($evalMark!=null) {
      			    $rubriDetail = $this->EvaluationMixevalDetail->getAllByEvalMixevalId($evalMark['id']);
      			    $evalResult[$userId][$userPOS++]['EvaluationMixeval']['details'] = $rubriDetail;
      			  }
      			}
  			  if (!isset($evalSubmission['EvaluationSubmission'])) {
  			     $allMembersCompleted = false;
  			     $inCompletedMembers[$pos++]=$user;
  			  }
  			}
			}
		}

  	$mixevalResultDetail['scoreRecords'] =  $this->formatMixevalEvaluationResultsMatrix($event, $groupMembers, $evalResult);
  	$mixevalResultDetail['allMembersCompleted'] = $allMembersCompleted;
  	$mixevalResultDetail['inCompletedMembers'] = $inCompletedMembers;
  	$mixevalResultDetail['memberScoreSummary'] = $memberScoreSummary;
  	$mixevalResultDetail['evalResult'] = $evalResult;

  	return $mixevalResultDetail;
   }

  function getStudentViewMixevalResultDetailReview ($event, $userId) {
	  $userPOS = 0;
	  $this->EvaluationSubmission = new EvaluationSubmission;
	  $this->EvaluationMixeval  = new EvaluationMixeval;
    $this->EvaluationMixevalDetail   = new EvaluationMixevalDetail;
    $this->User = new User;

    $mixevalResultDetail = array();
		$memberScoreSummary = array();
		$allMembersCompleted = true;
		$inCompletedMembers = array();
    $this->User->setId($userId);
    $user = $this->User->read();
	  if ($event['group_event_id'] && $userId) {
			    $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluator($event['group_event_id'], $userId);
				  $evalResult[$userId] = $mixevalResult;

    	    foreach($mixevalResult AS $row ){
    	      $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
            if ($evalMark!=null) {
    			    $rubriDetail = $this->EvaluationMixevalDetail->getAllByEvalMixevalId($evalMark['id']);
    			    $evalResult[$userId][$userPOS++]['EvaluationMixeval']['details'] = $rubriDetail;
    			  }
    			}
		}
  	return $evalResult;
   }

 	function formatMixevalEvaluationResultsMatrix($event, $groupMembers, $evalResult) {
		//
		// results matrix format:
		// Matrix[evaluatee_id][evaluator_id] = score
		//
		$matrix = array();
		$groupQuestionAve = array();

	  foreach($evalResult AS $index => $value){
	    $evalMarkArray = $value;
	    $evalTotal = 0;
      $mixevalQuestion = array();

      if ($evalMarkArray!=null) {
        $grade_release = 1;

  	    foreach($evalMarkArray AS $row ){
  	      $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
          if ($evalMark!=null) {
//print_r($evalMark);
    				$grade_release = $evalMark['grade_release'];
    				$comment_release = $evalMark['comment_release'];
    				//$ave_score= $receivedTotalScore / count($evalMarkArray);
        	  //$matrix[$index][$evalMark['evaluator']] = $evalMark['score'];
        	  //$matrix[$index]['received_ave_score'] =$ave_score;
						if ($index == $evalMark['evaluatee'] ) {

							$matrix[$index]['grade_released'] =$grade_release;
							$matrix[$index]['comment_released'] =$comment_release;
						}
         		$detailPOS = 0;

        	  //Format the mixeval question
        	  foreach ($evalMark['details'] AS $detail) {
        	    $mixevalResult = $detail['EvaluationMixevalDetail'];
	            if (!isset($mixevalQuestion[$mixevalResult['question_number']])) $mixevalQuestion[$mixevalResult['question_number']] = 0;
        	      $mixevalQuestion[$mixevalResult['question_number']] += $mixevalResult['grade'];
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
      //Get Ave Question Grade
      foreach ($mixevalQuestion AS $criIndex => $criGrade) {
        if (!isset($groupQuestionAve[$criIndex])) $groupQuestionAve[$criIndex] = 0;
        $ave = $criGrade / $detailPOS;
        $mixevalQuestion[$criIndex] = $ave;
        $groupQuestionAve[$criIndex]+= $ave;
      }
      $matrix[$index]['mixeval_question_ave'] = $mixevalQuestion;
	  }

    //Get Group Ave Question Grade
    foreach ($groupQuestionAve AS $groupIndex => $groupGrade) {
      $ave = $groupGrade / count($evalResult);
      $groupQuestionAve[$groupIndex] = $ave;
    }
    $matrix['group_question_ave'] = $groupQuestionAve;

		return $matrix;
	}



  function changeEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
 	  $this->EvaluationMixeval  = new EvaluationMixeval;
 	  $this->GroupEvent = new GroupEvent;

    //changing grade release for each EvaluationMixeval
 		$evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
 		foreach ($evaluationMixeval as $row) {
 			$evalMixeval = $row['EvaluationMixeval'];
 			if( isset($evalMixeval) ) {
				$this->EvaluationMixeval->setId($evalMixeval['id']);
 				$evalMixeval['grade_release'] = $releaseStatus;
 				$this->EvaluationMixeval->save($evalMixeval);
 			}
 		}

		//changing grade release status for the GroupEvent
		$this->GroupEvent->setId($groupEventId);
		$oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
	  $groupEvent = $this->EvaluationHelper->formatGradeReleaseStatus($this->GroupEvent->read(), $releaseStatus,
	                                                                  $oppositGradeReleaseCount);
	  $this->GroupEvent->save($groupEvent);
	}

  function changeEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
 	  $this->EvaluationMixeval  = new EvaluationMixeval;
 	  $this->GroupEvent = new GroupEvent;

		$this->GroupEvent->setId($groupEventId);
		$groupEvent = $this->GroupEvent->read();

    //changing comment release for each EvaluationMixeval
 		$evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
 		foreach ($evaluationMixeval as $row) {
 			$evalMixeval = $row['EvaluationMixeval'];
 			if( isset($evalMixeval) ) {
				$this->EvaluationMixeval->setId($evalMixeval['id']);
 				$evalMixeval['comment_release'] = $releaseStatus;
 				$this->EvaluationMixeval->save($evalMixeval);
 			}
 		}

		//changing comment release status for the GroupEvent
		$this->GroupEvent->setId($groupEventId);
		$oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
	  $groupEvent = $this->EvaluationHelper->formatCommentReleaseStatus($this->GroupEvent->read(), $releaseStatus,
	                                                                  $oppositGradeReleaseCount);

  	$this->GroupEvent->save($groupEvent);
  }

	function formatMixevalEvaluationResult($event=null, $displayFormat='', $studentView=0)
	{
	  $this->Mixeval = new Mixeval;
	  $this->User = new User;
	  $this->GroupsMembers = new GroupsMembers;
	  $this->MixevalsQuestion = new MixevalsQuestion;
	  $this->EvaluationMixeval = new EvaluationMixeval;

	  $evalResult = array();
	  $groupMembers = array();
	  $result = array();

	  $this->Mixeval->setId($event['Event']['template_id']);
	  $mixeval = $this->Mixeval->read();
	  $result['mixeval'] = $mixeval;


     //Get Members for this evaluation
     if ($studentView) {
       $this->User->setId($this->rdAuth->id);
       $user = $this->User->read();
       $mixevalResultDetail = $this->getMixevalResultDetail($event, $user);
       $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                                  $this->rdAuth->id);
       $membersAry = array();
       foreach ($groupMembers as $member) {
        $membersAry[$member['User']['id']] = $member;
       }
       $result['groupMembers'] = $membersAry;

       $reviewEvaluations = $this->getStudentViewMixevalResultDetailReview($event,
                                                                          $this->rdAuth->id);
		   $result['reviewEvaluations'] = $reviewEvaluations;

     } else {
       $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],
                                                                  $this->rdAuth->id);
       $mixevalResultDetail = $this->getMixevalResultDetail($event, $groupMembers);
       $result['groupMembers'] = $groupMembers;
     }


    //Get Detail information on Mixeval score
		if ($displayFormat == 'Detail') {
//echo 'ss';
		  $mixevalQuestion = $this->MixevalsQuestion->getQuestion($mixeval['Mixeval']['id']);
		  foreach ($mixevalQuestion AS $row) {
        $question = $row['MixevalsQuestion'];
        $result['mixevalQuestion'][$question['question_num']] = $question;
      }
		  //$result['mixevalQuestion'] = $mixevalQuestion;
		}
    $gradeReleaseStatus = $this->EvaluationMixeval->getTeamReleaseStatus($event['group_event_id']);

    $result['allMembersCompleted'] = $mixevalResultDetail['allMembersCompleted'];
    $result['inCompletedMembers'] = $mixevalResultDetail['inCompletedMembers'];
		$result['scoreRecords'] = $mixevalResultDetail['scoreRecords'];
		$result['memberScoreSummary'] = $mixevalResultDetail['memberScoreSummary'];
		$result['evalResult'] = $mixevalResultDetail['evalResult'];
    $result['gradeReleaseStatus'] = $gradeReleaseStatus;
    return $result;
	}
}
?>