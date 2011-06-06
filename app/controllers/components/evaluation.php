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
class EvaluationComponent extends Object
{
  //General functions
// Moved to Event Model
//  function formatEventObj ($eventId, $groupId=null)
//  {
//    //Get the target event
//    $this->Event = new Event;
//    $this->Event->id = $eventId;
//    $event = $this->Event->read();
//
//    //Get the group name
//    if ($groupId != null) {
//      $this->Group = new Group;
//      $this->Group->id = $groupId;
//
//      $group = $this->Group->read();
//      $event['group_name'] = 'Group '.$group['Group']['group_num'].' - '.$group['Group']['group_name'];
//      $event['group_id'] = $group['Group']['id'];
//
//      $this->GroupEvent = new GroupEvent;
//      $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
//      $event['group_event_id'] = $groupEvent['GroupEvent']['id'];
//      $event['group_event_marked'] = $groupEvent['GroupEvent']['marked'];
//    }
//    return $event;
//  }

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

// 	Moved to EventTemplateType Model
// 	function getEventType ($eventTemplateTypeId, $field='type_name')
// 	{
// 	  $this->EventTemplateType = new EventTemplateType;
// 	  $this->EventTemplateType->id = $eventTemplateTypeId;
// 	  $eventTemplate = $this->EventTemplateType->read();
//
// 	  return $eventTemplate['EventTemplateType'][$field];
// 	}

  function formatSimpleEvaluationResultsMatrix($event, $groupMembers, $evalResult) {
    //
    // results matrix format:
    // Matrix[evaluatee_id][evaluator_id] = score
    //
    $matrix = array();

    //$this->User = new User;
    //print_r($this->User->findUserByStudentNo('36241032'));

    foreach($evalResult AS $index => $value){
      $evalMarkArray = $value;
      $evalTotal = 0;
      if ($evalMarkArray!=null) {
        $grade_release = 1;
        //Get total score of each memeber
        //$receivedTotalScoreAry = isset($evalMarkArray[-1]['received_total_score'])? $evalMarkArray[-1]['received_total_score']: 0;
        //foreach($receivedTotalScoreAry AS $totalScore ){
        //$receivedTotalScore = $totalScore['received_total_score'];
        //}
      foreach($evalMarkArray AS $row ){
        $evalMark = isset($row['EvaluationSimple'])? $row['EvaluationSimple']: null;

        if ($evalMark!=null) {
          $grade_release = $evalMark['grade_release'];
          //$ave_score= $receivedTotalScore / count($evalMarkArray);
          $matrix[$index][$evalMark['evaluatee']] = $evalMark['score'];
          //$matrix[$index]['received_ave_score'] =$ave_score;
          /*if ($index == $evalMark['evaluatee'] ) {
                  $matrix[$index]['grade_released'] =$grade_release;
                  $matrix[$index]['evaluatee'] =$evalMark['evaluatee'];

          }*/
        } else{
          $matrix[$index][$evalMark['evaluatee']] = 'n/a';
        }
      }
    }else {
      foreach ($groupMembers as $user) {
        $matrix[$index][$user['User']['id']] = 'n/a';
      }
    }
    //if(!$event['Event']['self_eval']) $matrix[$member->getId()][$member->getId()] = '--';
    }
    return $matrix;
  }


  //Simple Evaluation functions

  function saveSimpleEvaluation($params=null, $groupEvent=null, $evaluationSubmission=null){
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
    if (!$this->EvaluationSubmission->save($evaluationSubmission)){
      return false;
    }

    //checks if all members in the group have submitted
    //the number of submission equals the number of members
    //means that this group is ready to review
    $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted(
      $groupEvent['GroupEvent']['group_id'], $groupEvent['GroupEvent']['id']);
    $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
    //Check to see if all members are completed this evaluation
    if($numOfCompletedCount == $evaluateeCount ){
      $groupEvent['GroupEvent']['marked'] = 'to review';
      if (!$this->GroupEvent->save($groupEvent)){
        return false;
      }
    }
    return true;
  }

  function formatStudentViewOfSimpleEvaluationResult($event=null){
    $this->EvaluationSimple = new EvaluationSimple;
    $this->GroupsMembers = new GroupsMembers;
    $gradeReleaseStatus = 0;
    $aveScore = 0; $groupAve = 0;
    $studentResult = array();

    $results = $this->EvaluationSimple->getResultsByEvaluatee($event['group_event_id'], $this->Auth->user('id'));
    if ($results !=null) {
      //Get Grade Release: grade_release will be the same for all evaluatee records
      $gradeReleaseStatus = $results[0]['EvaluationSimple']['grade_release'];
      if ($gradeReleaseStatus) {
        //Grade is released; retrieve all grades
        //Get total mark each member received
	$receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore(
        $event['group_event_id'], $this->Auth->user('id'));
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
         $comments = $this->EvaluationSimple->getAllComments($event['group_event_id'], $this->Auth->user('id'));
         if (shuffle($comments)) {
           $studentResult['comments'] = $comments;
         }
         $studentResult['commentReleaseStatus'] = $commentReleaseStatus;
       }

    }
    $studentResult['gradeReleaseStatus'] = $gradeReleaseStatus;
    return $studentResult;
  }

  function changeSimpleEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationSimple = new EvaluationSimple;
    $this->GroupEvent = new GroupEvent;

    //changing grade release for each EvaluationSimple
    $evaluationMarkSimples = $this->EvaluationSimple->getResultsByEvaluatee($groupEventId, $evaluateeId);
    foreach ($evaluationMarkSimples as $row) {
      $evalMarkSimple = $row['EvaluationSimple'];
      if( isset($evalMarkSimple) ) {
        $this->EvaluationSimple->id = $evalMarkSimple['id'];
        $evalMarkSimple['grade_release'] = $releaseStatus;
        $this->EvaluationSimple->save($evalMarkSimple);
      }
    }

    //changing grade release status for the GroupEvent
    $this->GroupEvent->id = $groupEventId;
    $oppositGradeReleaseCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
    $groupEvent = $this->formatGradeReleaseStatus(
      $this->GroupEvent->read(), $releaseStatus, $oppositGradeReleaseCount);
    $this->GroupEvent->save($groupEvent);
  }

  function changeSimpleEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluatorIds, $params) {

    $this->GroupEvent = new GroupEvent;
    $this->EvaluationSimple = new EvaluationSimple;

    $this->GroupEvent->id = $groupEventId;
    $groupEvent = $this->GroupEvent->read();

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
        $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 1, $oppositCommentReleaseCount);
      } else {
        $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 0);
        if ($oppositCommentReleaseCount == 0) {
          $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 0, $oppositCommentReleaseCount);
        } else {
          $groupEvent['GroupEvent']['comment_release_status'] = "Some";
        }
      }
    } else if($params['form']['submit']=='Release All') {
      //Reset all release status to false first
      $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 1);

      //changing grade release status for the GroupEvent
      $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 1);
      $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 1, $oppositCommentReleaseCount);
    } else if($params['form']['submit']=='Unrelease All') {
      //Reset all release status to false first
      $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);

      //changing grade release status for the GroupEvent
      $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 0);
      $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 0, $oppositCommentReleaseCount);

    }
    $this->GroupEvent->save($groupEvent);
  }

  function formatSimpleEvaluationResult($event=null) {
    $this->GroupsMembers = new GroupsMembers;
    $this->EvaluationSimple = new EvaluationSimple;
    $this->EvaluationSubmission = new EvaluationSubmission;
    $result = array();

    //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'],
      $event['Event']['self_eval'], $this->Auth->user('id'));

    // get comment records - do changes to records above this.
    $commentRecords = array();
    $memberScoreSummary = array();
    $inCompletedMembers = array();
    $allMembersCompleted = true;
    if ($event['group_event_id'] && $groupMembers) {
      $pos = 0;
      foreach ($groupMembers as $user)   {
	//Check if this memeber submitted evaluation
	$evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(
          $event['group_event_id'],$user['User']['id']);

	if (empty($evalSubmission['EvaluationSubmission'])) {
          $allMembersCompleted = false;
	  $inCompletedMembers[$pos++]=$user;
	}
	$evalResult[$user['User']['id']] = $this->EvaluationSimple->getResultsByEvaluator(
          $event['group_event_id'],$user['User']['id']);

	//Get total mark each member received
	$receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore(
          $event['group_event_id'],$user['User']['id']);
	$memberScoreSummary[$user['User']['id']]['received_total_score'] = $receivedTotalScore[0][0]['received_total_score'];

      }
    }
    $scoreRecords = $this->formatSimpleEvaluationResultsMatrix($event, $groupMembers,$evalResult);

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

  //Rubric Evaluation functions

  function loadRubricEvaluationDetail($event) {
    $this->EvaluationRubric = new EvaluationRubric;
    $this->GroupsMembers = new GroupsMembers;
    $this->EvaluationRubricDetail = new EvaluationRubricDetail;
    $this->Rubric = new Rubric;
    $this->User=  new User;

    $Session = new SessionComponent();
    $user = $Session->read('Auth.User');//User or Admin or
    $evaluator = $user['id'];
    $result = array();
    //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers(
      $event['group_id'], $event['Event']['self_eval'],$evaluator);
    for ($i = 0; $i<count($groupMembers); $i++) {
      $targetEvaluatee = $groupMembers[$i]['User']['id'];
      $evaluation = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(
        $event['group_event_id'],$evaluator, $targetEvaluatee);
      if (!empty($evaluation)) {
        $groupMembers[$i]['User']['Evaluation'] = $evaluation;
        $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] =
          $this->EvaluationRubricDetail->getAllByEvalRubricId($evaluation['EvaluationRubric']['id']);
      }
    }
    //$this->set('groupMembers', $groupMembers);
    $result['groupMembers'] = $groupMembers;

    //Get the target rubric

    $this->Rubric->id = $event['Event']['template_id'];

    //$this->set('rubric', $this->Rubric->read());
    $result['rubric'] = $this->Rubric->read();

    // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
    $numMembers=$event['Event']['self_eval'] ? 
      $this->GroupsMembers->find('count', array(
        'conditions'=>array('group_id' => $event['group_id']))) :
      ($this->GroupsMembers->find('count', array(
        'conditions'=> array('group_id' => $event['group_id'])))-1);

    //$this->set('evaluateeCount', $numMembers);
    $result['evaluateeCount'] = $numMembers;
    return $result;
  }

  function saveRubricEvaluation($params=null) {
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

    $this->Event->id = $eventId;
    $event = $this->Event->read();

    //Get simple evaluation tool
    $this->Rubric->id = $event['Event']['template_id'];
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
    $evalRubric = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee($groupEventId, $evaluator,$targetEvaluatee);
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
    $score = $this->saveNGetEvalutionRubricDetail(
      $evalRubric['EvaluationRubric']['id'], $rubric,$targetEvaluatee, $params['form']);
    $evalRubric['EvaluationRubric']['score'] = $score;

    if (!$this->EvaluationRubric->save($evalRubric)){
      return false;
    }
    return true;
  }

  function saveNGetEvalutionRubricDetail ($evalRubricId, $rubric, $targetEvaluatee, $form) {
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
      foreach ($groupMembers as $user)   {
        $userPOS = 0;
        if (isset($user['id'])) {
          $userId = $user['id'];
          $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['group_event_id'],$userId);
          // if (isset($evalSubmission['EvaluationSubmission'])) {
          $rubricResult = $this->EvaluationRubric->getResultsByEvaluatee($event['group_event_id'], $userId);
          $evalResult[$userId] = $rubricResult;

          //Get total mark each member received
          $receivedTotalScore = $this->EvaluationRubric->getReceivedTotalScore($event['group_event_id'],$userId);
          $ttlEvaluatorCount = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($event['group_event_id'],$userId);
          if ($ttlEvaluatorCount[0]['ttl_count'] >0 ) {
            $memberScoreSummary[$userId]['received_total_score'] =
            $receivedTotalScore[0]['received_total_score'];
            $memberScoreSummary[$userId]['received_ave_score'] =
            $receivedTotalScore[0]['received_total_score'] / $ttlEvaluatorCount[0]['ttl_count'];
          }

          foreach($rubricResult AS $row ){
            $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
            if ($evalMark!=null) {
              $rubricDetail = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalMark['id']);
              $evalResult[$userId][$userPOS++]['EvaluationRubric']['details'] = $rubricDetail;
            }
          }
          if (!isset($evalSubmission['EvaluationSubmission'])) {
            $allMembersCompleted = false;
            $inCompletedMembers[$pos++]=$user;
          }
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
          $receivedTotalScore = $this->EvaluationRubric->getReceivedTotalScore($event['group_event_id'],$userId);
          $ttlEvaluatorCount = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($event['group_event_id'],$userId);
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
              $rubricDetail = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalMark['id']);
              $evalResult[$userId][$userPOS++]['EvaluationRubric']['details'] = $rubricDetail;
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

    $this->User->id = $userId;

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

    foreach($evalResult AS $index => $value) {
      $evalMarkArray = $value;
      $evalTotal = 0;
      $rubricCriteria = array();

      if ($evalMarkArray!=null) {
        $grade_release = 1;
        $detailPOS = 0;

        foreach($evalMarkArray AS $row ){
          $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
          if ($evalMark!=null) {
            $grade_release = $evalMark['grade_release'];
            $comment_release = $evalMark['comment_release'];
            if ($index == $evalMark['evaluatee'] ) {
              $matrix[$index]['grade_released'] = $grade_release;
              $matrix[$index]['comment_released'] = $comment_release;
            }

            //Format the rubric criteria\
            foreach ($evalMark['details'] AS $detail) {
              $rubricResult = $detail['EvaluationRubricDetail'];
              if (!isset($rubricCriteria[$rubricResult['criteria_number']])) {
                $rubricCriteria[$rubricResult['criteria_number']] = 0;
              }
              $rubricCriteria[$rubricResult['criteria_number']] += $rubricResult['grade'];
            }
            $detailPOS ++ ;
          } else{
              //$matrix[$index][$evalMark['evaluatee']] = 'n/a';
          }
        }
      } else {
        foreach ($groupMembers as $user) {
          $matrix[$index][$user['User']['id']] = 'n/a';
        }
      }
      //Get Ave Criteria Grade
      foreach ($rubricCriteria AS $criIndex => $criGrade) {
        if (!isset($groupCriteriaAve[$criIndex])) {
          $groupCriteriaAve[$criIndex] = 0;
        }
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

  function changeRubricEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationRubric  = new EvaluationRubric;
    $this->GroupEvent = new GroupEvent;

    //changing grade release for each EvaluationRubric
    $evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
    foreach ($evaluationRubric as $row) {
      $evalRubric = $row['EvaluationRubric'];
      if( isset($evalRubric) ) {
        $this->EvaluationRubric->id = $evalRubric['id'];
        $evalRubric['grade_release'] = $releaseStatus;
        $this->EvaluationRubric->save($evalRubric);
      }
    }

    //changing grade release status for the GroupEvent
    $this->GroupEvent->id = $groupEventId;
    $oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
    $groupEvent = $this->formatGradeReleaseStatus(
      $this->GroupEvent->read(), $releaseStatus,$oppositGradeReleaseCount);
    $this->GroupEvent->save($groupEvent);
  }

  function changeRubricEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationRubric  = new EvaluationRubric;
    $this->GroupEvent = new GroupEvent;
    $this->GroupEvent->id = $groupEventId;
    $groupEvent = $this->GroupEvent->read();

    //changing comment release for each EvaluationRubric
    $evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
    foreach ($evaluationRubric as $row) {
      $evalRubric = $row['EvaluationRubric'];
      if( isset($evalRubric) ) {
        $this->EvaluationRubric->id = $evalRubric['id'];
        $evalRubric['comment_release'] = $releaseStatus;
        $this->EvaluationRubric->save($evalRubric);
      }
    }

    //changing comment release status for the GroupEvent
    $this->GroupEvent->id = $groupEventId;
    $oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
    $groupEvent = $this->formatCommentReleaseStatus(
      $this->GroupEvent->read(), $releaseStatus,$oppositGradeReleaseCount);

    $this->GroupEvent->save($groupEvent);
  }

  function formatRubricEvaluationResult($event=null, $displayFormat='', $studentView=0, $currentUser) {
    $this->Rubric = new Rubric;
    $this->User = new User;
    $this->GroupsMembers = new GroupsMembers;
    $this->RubricsCriteria = new RubricsCriteria;
    $this->EvaluationRubric = new EvaluationRubric;

    $evalResult = array();
    $groupMembers = array();
    $result = array();

    $this->Rubric->id = $event['Event']['template_id'];

    $rubric = $this->Rubric->read();
    $result['rubric'] = $rubric;

    //Get Members for this evaluation
    if ($studentView) {
      $this->User->id = $this->Auth->user('id');
      $user = $this->User->read();
      $rubricResultDetail = $this->getRubricResultDetail($event, $user);
      $groupMembers = $this->GroupsMembers->getEventGroupMembers(
        $event['group_id'], $event['Event']['self_eval'],$currentUser['id']);

      $membersAry = array();
      foreach ($groupMembers as $member) {
        $membersAry[$member['User']['id']] = $member;
      }
      $result['groupMembers'] = $membersAry;

      $reviewEvaluations = $this->getStudentViewRubricResultDetailReview($event,$currentUser['id']);
      $result['reviewEvaluations'] = $reviewEvaluations;

    } else {
      $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'],$currentUser['id']);
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

  //Mixeval Evaluation functions

  function loadMixEvaluationDetail ($event) {
    $this->GroupsMembers = new GroupsMembers;
    $this->EvaluationMixeval = new EvaluationMixeval;
    $this->EvaluationMixevalDetail = new EvaluationMixevalDetail;
    $this->Mixeval = new Mixeval;

    $result = array();
    $evaluator = $this->Auth->user('id');

    //Get Members for this evaluation
    $groupMembers = $this->GroupsMembers->getEventGroupMembers(
      $event['group_id'], $event['Event']['self_eval'],$this->Auth->user('id'));
    for ($i = 0; $i<count($groupMembers); $i++) {
      $targetEvaluatee = $groupMembers[$i]['User']['id'];
      $evaluation = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
        $event['group_event_id'],$evaluator, $targetEvaluatee);
      if (!empty($evaluation)) {
        $groupMembers[$i]['User']['Evaluation'] = $evaluation;
        $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] =
          $this->EvaluationMixevalDetail->getAllByEvalMixevalId($evaluation['EvaluationMixeval']['id']);
      }
    }
    //$this->set('groupMembers', $groupMembers);
    $result['groupMembers'] = $groupMembers;

    //Get the target mixeval
    $this->Mixeval->id = $event['Event']['template_id'];
    //$this->set('mixeval', $this->Mixeval->read());
    $result['mixeval'] = $this->Mixeval->read();

    // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
    $numMembers=$event['Event']['self_eval'] ?
      $this->GroupsMembers->find('count', array(
        'conditions'=>(array ('group_id' => $event['group_id'])))) :
      $this->GroupsMembers->find('count',array(
        'conditions'=>(array ('group_id' => $event['group_id'])))) - 1;
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
    $this->Event->id = $eventId;
    $event = $this->Event->read();

    //Get simple evaluation tool
    $this->Mixeval->id = $event['Event']['template_id'];
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
    $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
      $groupEventId, $evaluator,$targetEvaluatee);

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
    $score = $this->saveNGetEvalutionMixevalDetail(
      $evalMixeval['EvaluationMixeval']['id'], $mixeval,$targetEvaluatee, $params);

    $evalMixeval['EvaluationMixeval']['score'] = $score;
    if (!$this->EvaluationMixeval->save($evalMixeval)){
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
    for($i=0; $i < $mixeval['Mixeval']['total_question']; $i++) {
      $evalMixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMixevalId, $i);
      if (isset($evalMixevalDetail)) {
        $this->EvaluationMixevalDetail->id=$evalMixevalDetail['EvaluationMixevalDetail']['id'] ;
      }
      $evalMixevalDetail['EvaluationMixevalDetail']['evaluation_mixeval_id'] = $evalMixevalId;
      $evalMixevalDetail['EvaluationMixevalDetail']['question_number'] = $i;

      if ($form['data']['Mixeval']['question_type'.$i] == 'S') {
        // get total possible grade for the question number ($i)
      	$selectedLom = $form['form']['selected_lom_'.$targetEvaluatee.'_'.$i];
    	$grade = $form['form'][$targetEvaluatee.'criteria_points_'.$i];
        $evalMixevalDetail['EvaluationMixevalDetail']['selected_lom'] = $selectedLom;
        $evalMixevalDetail['EvaluationMixevalDetail']['grade'] = $grade;
    	$totalGrade += $grade;
      } else if ($form['data']['Mixeval']['question_type'.$i] == 'T') {
        $evalMixevalDetail['EvaluationMixevalDetail']['question_comment'] = $form['form']["response_text_".$targetEvaluatee."_".$i];
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
            $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(
              $event['group_event_id'],$userId);
            // if (isset($evalSubmission['EvaluationSubmission'])) {
            $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluatee($event['group_event_id'], $userId);
            $evalResult[$userId] = $mixevalResult;

            //Get total mark each member received
            $receivedTotalScore = $this->EvaluationMixeval->getReceivedTotalScore(
              $event['group_event_id'],$userId);
            $ttlEvaluatorCount = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount($event['group_event_id'],$userId);
            if ($ttlEvaluatorCount[0]['ttl_count'] >0 ) {
              $memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
              $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] / $ttlEvaluatorCount[0]['ttl_count'];
            }
            foreach($mixevalResult AS $row ) {
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
          } elseif (isset($user['User'])) {
            $userId = $user['User']['id'];
            //$userId = isset($user['User'])? $user['User']['id'] : $user['id'];
            //Check if this memeber submitted evaluation
            $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(
              $event['group_event_id'], $userId);

            // if (isset($evalSubmission['EvaluationSubmission'])) {
            $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluatee($event['group_event_id'], $userId);
            $evalResult[$userId] = $mixevalResult;

            //Get total mark each member received
            $receivedTotalScore = $this->EvaluationMixeval->getReceivedTotalScore(
              $event['group_event_id'],$userId);
            $ttlEvaluatorCount = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(
              $event['group_event_id'],$userId);
            if ($ttlEvaluatorCount[0]['ttl_count'] > 0 ) {
              $memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
              $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] /
              $ttlEvaluatorCount[0]['ttl_count'];
            }
            foreach($mixevalResult AS $row ) {
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
    $currentUser=$this->User->getCurrentLoggedInUser();

    $mixevalResultDetail = array();
    $memberScoreSummary = array();
    $allMembersCompleted = true;
    $inCompletedMembers = array();
    $this->User->id = $userId;
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

    foreach($evalResult AS $index => $value) {
      $evalMarkArray = $value;
      $evalTotal = 0;
      $mixevalQuestion = array();

      if ($evalMarkArray != null) {
        $grade_release = 1;
        $detailPOS = 0;

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
            //Format the mixeval question
            foreach ($evalMark['details'] AS $detail) {
              $mixevalResult = $detail['EvaluationMixevalDetail'];
              if (!isset($mixevalQuestion[$mixevalResult['question_number']]))
                $mixevalQuestion[$mixevalResult['question_number']] = 0;
              $mixevalQuestion[$mixevalResult['question_number']] += $mixevalResult['grade'];
            }
            $detailPOS ++ ;
          } else{
              //$matrix[$index][$evalMark['evaluatee']] = 'n/a';
          }
        }
      }	else {
        foreach ($groupMembers as $user) {
          if (!empty($user)) {
            // The array's format varries. Sometime a sub-array [0] is present
            $id = !empty($user['id']) ? $user['id'] : $user['User']['id'];
            $matrix[$index][$id] = 'n/a';
          }
        }
      }
      //Get Ave Question Grade
      foreach ($mixevalQuestion AS $criIndex => $criGrade) {
        if (!isset($groupQuestionAve[$criIndex])) {
          $groupQuestionAve[$criIndex] = 0;
        }
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

  function changeMixevalEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationMixeval  = new EvaluationMixeval;
    $this->GroupEvent = new GroupEvent;

    //changing grade release for each EvaluationMixeval
    $evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
    foreach ($evaluationMixeval as $row) {
      $evalMixeval = $row['EvaluationMixeval'];
      if( isset($evalMixeval) ) {
        $this->EvaluationMixeval->id = $evalMixeval['id'];
        $evalMixeval['grade_release'] = $releaseStatus;
        $this->EvaluationMixeval->save($evalMixeval);
      }
    }

    //changing grade release status for the GroupEvent
    $this->GroupEvent->id = $groupEventId;
    $oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
    $groupEvent = $this->formatGradeReleaseStatus(
      $this->GroupEvent->read(), $releaseStatus,$oppositGradeReleaseCount);
    $this->GroupEvent->save($groupEvent);
  }

  function changeMixevalEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus) {
    $this->EvaluationMixeval  = new EvaluationMixeval;
    $this->GroupEvent = new GroupEvent;

    $this->GroupEvent->id = $groupEventId;
    $groupEvent = $this->GroupEvent->read();

    //changing comment release for each EvaluationMixeval
    $evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
    foreach ($evaluationMixeval as $row) {
      $evalMixeval = $row['EvaluationMixeval'];
      if( isset($evalMixeval) ) {
        $this->EvaluationMixeval->id = $evalMixeval['id'];
        $evalMixeval['comment_release'] = $releaseStatus;
        $this->EvaluationMixeval->save($evalMixeval);
      }
    }

    //changing comment release status for the GroupEvent
    $this->GroupEvent->id = $groupEventId;
    $oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
    $groupEvent = $this->formatCommentReleaseStatus($this->GroupEvent->read(), $releaseStatus,
                                                                    $oppositGradeReleaseCount);

    $this->GroupEvent->save($groupEvent);
  }

  function formatMixevalEvaluationResult($event=null, $displayFormat='', $studentView=0) {
    $this->Course= & ClassRegistry::init('Mixeval');
    $this->Mixeval = new Mixeval;
    $this->User = new User;
    $this->GroupsMembers = new GroupsMembers;
    $this->MixevalsQuestion = new MixevalsQuestion;
    $this->EvaluationMixeval = new EvaluationMixeval;

    $evalResult = array();
    $groupMembers = array();
    $result = array();

    $this->Mixeval->id = $event['Event']['template_id'];

    $mixeval = $this->Mixeval->read();
    $result['mixeval'] = $mixeval;

    $currentUser = $this->User->getCurrentLoggedInUser();

    //Get Members for this evaluation
    if ($studentView) {

     $this->User->id = $this->Auth->user('id');

     $user = $this->User->read();
     $mixevalResultDetail = $this->getMixevalResultDetail($event, $user);
     $groupMembers = $this->GroupsMembers->getEventGroupMembers(
      $event['group_id'], $event['Event']['self_eval'],$currentUser['id']);
     $membersAry = array();
     foreach ($groupMembers as $member) {
      $membersAry[$member['User']['id']] = $member;
     }
     $result['groupMembers'] = $membersAry;

     $reviewEvaluations = $this->getStudentViewMixevalResultDetailReview(
      $event, $this->Auth->user('id'));
     $result['reviewEvaluations'] = $reviewEvaluations;
    } else {
     $groupMembers = $this->GroupsMembers->getEventGroupMembers(
      $event['group_id'], $event['Event']['self_eval'],$this->Auth->user('id'));
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

  //Survey Evaluation functions


}

?>