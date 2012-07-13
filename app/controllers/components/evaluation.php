<?php
App::import('Model', 'Mixeval');
App::import('Model', 'Event');
App::import('Model', 'SurveyQuestion');

/**
 * EvaluationComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationComponent extends Object
{
    public $uses =  array('Mixeval');
    public $components = array('Session', 'Auth');


    /**
     * formatGradeReleaseStatus
     *
     * @param mixed $groupEvent               group events
     * @param mixed $releaseStatus            group status
     * @param mixed $oppositGradeReleaseCount opposite grade release count
     *
     * @access public
     * @return array group events
     */
    function formatGradeReleaseStatus($groupEvent, $releaseStatus, $oppositGradeReleaseCount)
    {
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
        } else {
            //User clicked unrelease individual grade

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

    /**
     * getGroupReleaseStatus
     *
     * @param mixed $groupEvent group event
     *
     * @access public
     * @return array release status
     */
    function getGroupReleaseStatus($groupEvent)
    {
        if (isset($groupEvent)) {
            $release = array(
                'grade_release_status'   => $groupEvent['GroupEvent']['grade_release_status'],
                'comment_release_status' => $groupEvent['GroupEvent']['comment_release_status']
            );
        } else {
            $release = array('grade_release_status' => 'None', 'comment_release_status' => 'None');
        }

        return $release;
    }


    /**
     * formatCommentReleaseStatus
     *
     * @param mixed $groupEvent                 group event
     * @param mixed $releaseStatus              release status
     * @param mixed $oppositCommentReleaseCount opposite comment release count
     *
     * @access public
     * @return array group event
     */
    function formatCommentReleaseStatus($groupEvent, $releaseStatus, $oppositCommentReleaseCount)
    {
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

    /**
     * formatSimpleEvaluationResultsMatrix
     *
     * @param mixed $event        event
     * @param mixed $groupMembers group members
     * @param mixed $evalResult   evaluation result
     *
     * @access public
     * @return array matrix
     */
    function formatSimpleEvaluationResultsMatrix($event, $groupMembers, $evalResult)
    {
        //
        // results matrix format:
        // Matrix[evaluatee_id][evaluator_id] = score
        //
        $matrix = array();

        //$this->User = new User;
        foreach ($evalResult as $index => $value) {
            $evalMarkArray = $value;
            $evalTotal = 0;
            if ($evalMarkArray!=null) {
                $grade_release = 1;
                //Get total score of each memeber
                //$receivedTotalScoreAry = isset($evalMarkArray[-1]['received_total_score'])? $evalMarkArray[-1]['received_total_score']: 0;
                //foreach ($receivedTotalScoreAry as $totalScore) {
                //$receivedTotalScore = $totalScore['received_total_score'];
                //}
                foreach ($evalMarkArray as $row) {
                    $evalMark = isset($row['EvaluationSimple'])? $row['EvaluationSimple']: null;
                    if ($evalMark!=null) {
                        $grade_release = $evalMark['grade_release'];
                        //$ave_score= $receivedTotalScore / count($evalMarkArray);
                        $matrix[$index][$evalMark['evaluatee']] = $evalMark['score'];
                        //$matrix[$index]['received_ave_score'] =$ave_score;
          /*if ($index == $evalMark['evaluatee']) {
                  $matrix[$index]['grade_released'] =$grade_release;
                  $matrix[$index]['evaluatee'] =$evalMark['evaluatee'];

          }*/
                    } else {
                        $matrix[$index][$evalMark['evaluatee']] = 'n/a';
                    }
                }
            } else {
                foreach ($groupMembers as $user) {
                    $matrix[$index][$user['User']['id']] = 'n/a';
                }
            }
            //if (!$event['Event']['self_eval']) $matrix[$member->getId()][$member->getId()] = '--';
        }

        return $matrix;
    }

    /**
     * filterString Filters a input string by removing unwanted chars of {"_", "0", "1", "2", "3",...}
     *
     * @param bool $string string to be filered
     *
     * @access public
     * @return string filtered string
     */
    function filterString($string = null)
    {
        $unwantedChar = array("_", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        return str_replace($unwantedChar, "", $string);
    }

    /**
     * isLate
     *
     * @param mixed $event the event to be tested
     *
     * @access public
     * @return int how many days late
     */
    function isLate($event)
    {
        $submitted = date('Y-m-d H:i:s');
        $lateDays = $this->daysLate($event, $submitted);
        return $lateDays;
    }


    /**
     * daysLate
     *
     * @param mixed $event          event
     * @param mixed $submissionDate submission date
     *
     * @access public
     * @return int how many days late
     */
    function daysLate($event, $submissionDate)
    {
        $this->Event = ClassRegistry::init('Event');
        $days = 0;
        $dueDate = $this->Event->find('first', array('conditions' => array('Event.id' => $event), 'fields' => array('Event.due_date')));
        $dueDate = $dueDate['Event']['due_date'];
        $seconds = strtotime($dueDate) - strtotime($submissionDate);
        $diff = $seconds /60/60/24;
        if ($diff<0) {
            $days = abs(floor($diff));
        }

        return $days;
    }

    /**
     * saveSimpleEvaluation
     *
     * @param bool $params               parameters
     * @param bool $groupEvent           group event
     * @param bool $evaluationSubmission evaluation submission
     *
     * @access public
     * @return bool if save is successfully
     */
    function saveSimpleEvaluation($params=null, $groupEvent=null, $evaluationSubmission=null)
    {
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Penalty = ClassRegistry::init('Penalty');

        // assuming all are in the same order and same size
        $evaluatees = $params['form']['memberIDs'];
        $points = $params['form']['points'];
        $comments = $params['form']['comments'];
        $evaluator = $params['data']['Evaluation']['evaluator_id'];
        $evaluateeCount = $params['form']['evaluateeCount'];

        // create Evaluations for each evaluator-evaluatee pair
        $marks = array();
        $pos = 0;
        foreach ($evaluatees as $key => $value) {
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

            if (!$this->EvaluationSimple->save($evalMarkRecord)) {
                return false;
            }
            $this->EvaluationSimple->id=null;
            $pos++;
        }

        // if no submission exists, create one
        $grpEvent = $groupEvent['GroupEvent']['id'];
        $event = $groupEvent['GroupEvent']['event_id'];
        $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $grpEvent;
        $evaluationSubmission['EvaluationSubmission']['event_id'] = $event;
        $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
        // save evaluation submission
        $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
        $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
        if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
            return false;
        }

        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted(
            $groupEvent['GroupEvent']['group_id'], $groupEvent['GroupEvent']['id']);
        $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
        //Check to see if all members are completed this evaluation
        if ($numOfCompletedCount == $evaluateeCount) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->GroupEvent->save($groupEvent)) {
                return false;
            }
        }
        return true;
    }



    /**
     * changeSimpleEvaluationGradeRelease
     *
     * @param mixed $eventId       event id
     * @param mixed $groupId       group id
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeSimpleEvaluationGradeRelease($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus)
    {
        $this->EvaluationSimple = new EvaluationSimple;
        $this->GroupEvent = new GroupEvent;

        //changing grade release for each EvaluationSimple
        $evaluationMarkSimples = $this->EvaluationSimple->getResultsByEvaluatee($groupEventId, $evaluateeId);
        foreach ($evaluationMarkSimples as $row) {
            $evalMarkSimple = $row['EvaluationSimple'];
            if (isset($evalMarkSimple)) {
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


    /**
     * changeSimpleEvaluationCommentRelease
     *
     * @param mixed $eventId      event id
     * @param mixed $groupId      group id
     * @param mixed $groupEventId group event id
     * @param mixed $evaluatorIds evaluator ids
     * @param mixed $params       params
     *
     * @access public
     * @return void
     */
    function changeSimpleEvaluationCommentRelease($eventId, $groupId, $groupEventId, $evaluatorIds, $params)
    {

        $this->GroupEvent = new GroupEvent;
        $this->EvaluationSimple = new EvaluationSimple;

        $this->GroupEvent->id = $groupEventId;
        $groupEvent = $this->GroupEvent->read();

        //handle comment release by "Save Change"
        $evaluator = null;
        if ($params['form']['submit']=='Save Changes') {
            //Reset all release status to false first
            $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);
            foreach ($evaluatorIds as $key => $value) {
                if ($evaluator != $value) {
                    //Check for released guys
                    if (isset($params['form']['release'.$value])) {
                        $evaluateeIds = $params['form']['release'.$value];
                        $idString = array();
                        $pos = 1;
                        foreach ($evaluateeIds as $index => $id) {
                            $idString[$pos] = $id;
                            $pos ++;
                        }
                        $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 1, $value, $idString);
                    }
                    $evaluator = $value;
                    $idString = array();
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
        } else if ($params['form']['submit']=='Release All') {
            //Reset all release status to false first
            $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 1);

            //changing grade release status for the GroupEvent
            $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 1);
            $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 1, $oppositCommentReleaseCount);
        } else if ($params['form']['submit']=='Unrelease All') {
            //Reset all release status to false first
            $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);

            //changing grade release status for the GroupEvent
            $oppositCommentReleaseCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus($groupEventId, 0);
            $groupEvent = $this->formatCommentReleaseStatus($groupEvent, 0, $oppositCommentReleaseCount);

        }
        $this->GroupEvent->save($groupEvent);
    }


    /**
     * formatSimpleEvaluationResult
     *
     * @param bool $event
     *
     * @access public
     * @return void
     */
    function formatSimpleEvaluationResult($event=null)
    {
        $this->GroupsMembers = new GroupsMembers;
        $this->EvaluationSimple = new EvaluationSimple;
        $this->EvaluationSubmission = new EvaluationSubmission;
        $result = array();

        //Get Members for this evaluation
        $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'],
            $event['Event']['self_eval'], $this->Auth->user('id'));
        $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors($event['group_id'],
            $event['Event']['self_eval'], $this->Auth->user('id'));

        // get comment records - do changes to records above this.
        $commentRecords = array();
        $memberScoreSummary = array();
        $inCompletedMembers = array();
        $allMembersCompleted = true;
        if ($event['group_event_id'] && $groupMembersNoTutors) {
            $pos = 0;
            foreach ($groupMembers as $user) {
                //Check if this memeber submitted evaluation
                $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(
                    $event['group_event_id'], $user['User']['id']);

                if (empty($evalSubmission['EvaluationSubmission'])) {
                    $allMembersCompleted = false;
                    $inCompletedMembers[$pos++]=$user;
                }
                $evalResult[$user['User']['id']] = $this->EvaluationSimple->getResultsByEvaluator(
                    $event['group_event_id'], $user['User']['id']);

                //Get total mark each member received
                $receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore(
                    $event['group_event_id'], $user['User']['id']);
                $memberScoreSummary[$user['User']['id']]['received_total_score'] = $receivedTotalScore[0][0]['received_total_score'];

            }
        }
        $scoreRecords = $this->formatSimpleEvaluationResultsMatrix($event, $groupMembersNoTutors, $evalResult);
        $gradeReleaseStatus = $this->EvaluationSimple->getTeamReleaseStatus($event['group_event_id']);
        $result['scoreRecords'] = $scoreRecords;
        $result['memberScoreSummary'] = $memberScoreSummary;
        $result['evalResult'] = $evalResult;
        $result['groupMembers'] = $groupMembers;
        $result['groupMembersNoTutors'] = $groupMembersNoTutors;
        $result['inCompletedMembers'] = $inCompletedMembers;
        $result['allMembersCompleted'] = $allMembersCompleted;
        $result['gradeReleaseStatus'] = $gradeReleaseStatus;
        return $result;
    }



    /**
     * loadRubricEvaluationDetail
     * Rubric Evaluation functions
     *
     * @param mixed $event
     *
     * @access public
     * @return void
     */
    function loadRubricEvaluationDetail($event)
    {
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
        $groupMembers = $this->GroupsMembers->getEventGroupMembersNoTutors(
            $event['group_id'], $event['Event']['self_eval'], $evaluator);
        for ($i = 0; $i<count($groupMembers); $i++) {
            $targetEvaluatee = $groupMembers[$i]['User']['id'];
            $evaluation = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(
                $event['group_event_id'], $evaluator, $targetEvaluatee);
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
        $numMembers=count($this->GroupsMembers->getEventGroupMembersNoTutors($event['group_id'], $event['Event']['self_eval'], $evaluator));
        //$this->set('evaluateeCount', $numMembers);
        $result['evaluateeCount'] = $numMembers;
        return $result;
    }


    /**
     * saveRubricEvaluation
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function saveRubricEvaluation($params=null)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Rubric = ClassRegistry::init('Rubric');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');

        // assuming all are in the same order and same size
        $evaluatees = $params['form']['memberIDs'];
        $evaluator = $params['data']['Evaluation']['evaluator_id'];
        $groupEventId = $params['form']['group_event_id'];
        $rubricId = $params['form']['rubric_id'];
        $grpEvent = $params['form']['group_event_id'];

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
        for ($i=0; $i<count($evaluatees); $i++) {
            if (isset($params['form'][$evaluatees[$i]]) && $params['form'][$evaluatees[$i]] = 'Save This Section') {
                $targetEvaluatee = $evaluatees[$i];
            }
        }
        $evalRubric = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee($groupEventId, $evaluator, $targetEvaluatee);
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
            $evalRubric['EvaluationRubric']['id'], $rubric, $targetEvaluatee, $params['form']);
        $evalRubric['EvaluationRubric']['score'] = $score;

        if (!$this->EvaluationRubric->save($evalRubric)) {
            return false;
        }

        return true;
    }


    /**
     * saveNGetEvalutionRubricDetail
     *
     * @param mixed $evalRubricId    eval rubric id
     * @param mixed $rubric          rubric
     * @param mixed $targetEvaluatee target evaluatee
     * @param mixed $form            form
     *
     * @access public
     * @return void
     */
    function saveNGetEvalutionRubricDetail ($evalRubricId, $rubric, $targetEvaluatee, $form)
    {
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
        $isCheckBoxes = false;
        $totalGrade = 0;
        $pos = 0;

        for ($i=1; $i <= $rubric['Rubric']['criteria']; $i++) {
            //TODO: LOM = 1
            if ($rubric['Rubric']['lom_max'] == 1) {
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


    /**
     * getRubricResultDetail
     *
     * @param mixed $event        event
     * @param mixed $groupMembers group members
     *
     * @access public
     * @return void
     */
    function getRubricResultDetail ($event, $groupMembers)
    {
        $pos = 0;
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationRubric  = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail   = ClassRegistry::init('EvaluationRubricDetail');
        $rubricResultDetail = array();
        $memberScoreSummary = array();
        $allMembersCompleted = true;
        $inCompletedMembers = array();
        $evalResult = array();

        if (empty($event) || empty($groupMembers)) {
            return false;
        }
        if ($event['group_event_id'] && $groupMembers) {
            foreach ($groupMembers as $user) {
                $userPOS = 0;
                if (isset($user['id'])) {
                    $userId = $user['id'];
                    $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['group_event_id'], $userId);
                    // if (isset($evalSubmission['EvaluationSubmission'])) {
                    $rubricResult = $this->EvaluationRubric->getResultsByEvaluatee($event['group_event_id'], $userId);
                    $evalResult[$userId] = $rubricResult;
                    //Get total mark each member received
                    $receivedTotalScore = $this->EvaluationRubric->getReceivedTotalScore($event['group_event_id'], $userId);
                    $ttlEvaluatorCount = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($event['group_event_id'], $userId);
                    if ($ttlEvaluatorCount >0) {
                        $memberScoreSummary[$userId]['received_total_score'] =
                            $receivedTotalScore[0][0]['received_total_score'];
                        $memberScoreSummary[$userId]['received_ave_score'] =
                            $receivedTotalScore[0][0]['received_total_score'] / $ttlEvaluatorCount;
                    }

                    foreach ($rubricResult as $row) {
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
                    $receivedTotalScore = $this->EvaluationRubric->getReceivedTotalScore($event['group_event_id'], $userId);
                    $ttlEvaluatorCount = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($event['group_event_id'], $userId);
                    $memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0][0]['received_total_score'];
                    if ($ttlEvaluatorCount == 0) {
                        $memberScoreSummary[$userId]['received_ave_score'] = 0;
                    } else {
                        $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0][0]['received_total_score'] /
                            $ttlEvaluatorCount;
                    }
                    foreach ($rubricResult as $row) {
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


    /**
     * getStudentViewRubricResultDetailReview
     *
     * @param mixed $event  event
     * @param mixed $userId user id
     *
     * @access public
     * @return void
     */
    function getStudentViewRubricResultDetailReview ($event, $userId)
    {
        $userPOS = 0;
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationRubric  = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail   = ClassRegistry::init('EvaluationRubricDetail');
        $this->User = ClassRegistry::init('User');

        $rubricResultDetail = array();
        $memberScoreSummary = array();
        $allMembersCompleted = true;
        $inCompletedMembers = array();

        if (empty($event) || empty($userId)) {
            return false;
        }

        $this->User->id = $userId;

        $user = $this->User->read();
        if ($event['group_event_id'] && $userId) {
            $rubricResult = $this->EvaluationRubric->getResultsByEvaluator($event['group_event_id'], $userId);

            $evalResult[$userId] = $rubricResult;

            foreach ($rubricResult as $row) {
                $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
                if ($evalMark!=null) {
                    $rubricDetail = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalMark['id']);
                    $evalResult[$userId][$userPOS++]['EvaluationRubric']['details'] = $rubricDetail;
                }
            }
        }
        return $evalResult;
    }


    /**
     * formatRubricEvaluationResultsMatrix
     *
     * @param mixed $event        event
     * @param mixed $groupMembers group members
     * @param mixed $evalResult   evel result
     *
     * @access public
     * @return void
     */
    function formatRubricEvaluationResultsMatrix($event, $groupMembers, $evalResult)
    {
        //
        // results matrix format:
        // Matrix[evaluatee_id][evaluator_id] = score
        //
        $matrix = array();
        $groupCriteriaAve = array();

        if (empty($evalResult)) {
            return false;
        }

        foreach ($evalResult as $index => $value) {
            $evalMarkArray = $value;
            $evalTotal = 0;
            $rubricCriteria = array();

            if ($evalMarkArray!=null) {
                $grade_release = 1;
                $detailPOS = 0;
                foreach ($evalMarkArray as $row) {
                    $evalMark = isset($row['EvaluationRubric'])? $row['EvaluationRubric']: null;
                    if ($evalMark!=null) {
                        $grade_release = $evalMark['grade_release'];
                        $comment_release = $evalMark['comment_release'];
                        if ($index == $evalMark['evaluatee']) {
                            $matrix[$index]['grade_released'] = $grade_release;
                            $matrix[$index]['comment_released'] = $comment_release;
                        }

                        //Format the rubric criteria\
                        foreach ($evalMark['details'] as $detail) {
                            $rubricResult = $detail['EvaluationRubricDetail'];
                            if (!isset($rubricCriteria[$rubricResult['criteria_number']])) {
                                $rubricCriteria[$rubricResult['criteria_number']] = 0;
                            }
                            $rubricCriteria[$rubricResult['criteria_number']] += $rubricResult['grade'];
                        }
                        $detailPOS ++ ;
                    } else {
                        //$matrix[$index][$evalMark['evaluatee']] = 'n/a';
                    }
                }
            } else {
                foreach ($groupMembers as $user) {
                    if (isset($user['User'])) {
                        $user = $user['User'];
                    }
                    $matrix[$index][$user['id']] = 'n/a';
                }
            }
            //Get Ave Criteria Grade
            foreach ($rubricCriteria as $criIndex => $criGrade) {
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
        foreach ($groupCriteriaAve as $groupIndex => $groupGrade) {
            $ave = $groupGrade / count($evalResult);
            $groupCriteriaAve[$groupIndex] = $ave;
        }
        $matrix['group_criteria_ave'] = $groupCriteriaAve;

        return $matrix;
    }


    /**
     * changeRubricEvaluationGradeRelease
     *
     * @param mixed $eventId       event id
     * @param mixed $groupId       group id
     * @param mixed $groupEventId  group even tid
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeRubricEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus)
    {
        $this->EvaluationRubric  = ClassRegistry::init('EvaluationRubric');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');

        //changing grade release for each EvaluationRubric
        $evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
        foreach ($evaluationRubric as $row) {
            $evalRubric = $row['EvaluationRubric'];
            if (isset($evalRubric)) {
                $this->EvaluationRubric->id = $evalRubric['id'];
                $evalRubric['grade_release'] = $releaseStatus;
                $this->EvaluationRubric->save($evalRubric);
            }
        }

        //changing grade release status for the GroupEvent
        $this->GroupEvent->id = $groupEventId;
        $oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
        $groupEvent = $this->formatGradeReleaseStatus(
            $this->GroupEvent->read(), $releaseStatus, $oppositGradeReleaseCount);
        $this->GroupEvent->save($groupEvent);
    }


    /**
     * changeRubricEvaluationCommentRelease
     *
     * @param mixed $eventId       event id
     * @param mixed $groupId       group id
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeRubricEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus)
    {
        $this->EvaluationRubric  =  ClassRegistry::init('EvaluationRubric');
        $this->GroupEvent =  ClassRegistry::init('GroupEvent');
        $this->GroupEvent->id = $groupEventId;
        $groupEvent = $this->GroupEvent->read();

        //changing comment release for each EvaluationRubric
        $evaluationRubric = $this->EvaluationRubric->getResultsByEvaluatee($groupEventId, $evaluateeId);
        foreach ($evaluationRubric as $row) {
            $evalRubric = $row['EvaluationRubric'];
            if (isset($evalRubric)) {
                $this->EvaluationRubric->id = $evalRubric['id'];
                $evalRubric['comment_release'] = $releaseStatus;
                $this->EvaluationRubric->save($evalRubric);
            }
        }

        //changing comment release status for the GroupEvent
        $this->GroupEvent->id = $groupEventId;
        $oppositGradeReleaseCount = $this->EvaluationRubric->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
        $groupEvent = $this->formatCommentReleaseStatus(
            $this->GroupEvent->read(), $releaseStatus, $oppositGradeReleaseCount);

        $this->GroupEvent->save($groupEvent);
    }


    /**
     * formatRubricEvaluationResult
     *
     * @param bool   $event         event
     * @param string $displayFormat display format
     * @param int    $studentView   student view
     * @param bool   $currentUser   current user
     *
     * @access public
     * @return void
     */
    function formatRubricEvaluationResult($event=null, $displayFormat='', $studentView=0, $currentUser=null)
    {
        $this->Rubric =  ClassRegistry::init('Rubric');
        $this->User =  ClassRegistry::init('User');
        $this->GroupsMembers =  ClassRegistry::init('GroupsMembers');
        $this->RubricsCriteria =  ClassRegistry::init('RubricsCriteria');
        $this->EvaluationRubric =  ClassRegistry::init('EvaluationRubric');
        $this->Event =  ClassRegistry::init('Event');
        $this->Penalty =  ClassRegistry::init('Penalty');

        $evalResult = array();
        $groupMembers = array();
        $groupMembersNoTutors = array();
        $result = array();

        $this->Rubric->id = $event['Event']['template_id'];

        $rubric = $this->Rubric->read();
        $result['rubric'] = $rubric;
        $eventId = $event['Event']['id'];

        //Get Members for this evaluation
        if ($studentView) {
            $this->User->id = $this->Auth->user('id');
            $this->User->recursive = -1;
            $user = $this->User->read();
            $rubricResultDetail = $this->getRubricResultDetail($event, $user);
            $groupMembers = $this->GroupsMembers->getEventGroupMembers(
                $event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors(
                $event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $rubricResultDetail = $this->getRubricResultDetail($event, $user);
            $membersAry = array();
            $membersAryNoTutors = array();
            foreach ($groupMembers as $member) {
                $membersAry[$member['User']['id']] = $member;
            }
            foreach ($groupMembersNoTutors as $member) {
                $membersAryNoTutors[$member['User']['id']] = $member;
            }
            $result['groupMembers'] = $membersAry;
            $result['groupMembersNoTutors'] = $membersAryNoTutors;

            $reviewEvaluations = $this->getStudentViewRubricResultDetailReview($event, $currentUser['id']);
            $result['reviewEvaluations'] = $reviewEvaluations;
            
            $event_info = $this->Event->find(
                'first',
                array(
                    'conditions' => array('Event.id' => $eventId),
                )
            );
            // storing the timestamp of the due date/end date of the event
            $event_due = strtotime($event_info['Event']['due_date']);
            $event_end = strtotime($event_info['Event']['release_date_end']);
            // assign penalty to user if they submitted late or never submitted by release_date_end
            $scorePenalty = null;
            $event_sub= $this->Event->find(
                'first',
                array(
                    'conditions' => array('Event.id' => $eventId),
                    'contain' => array('EvaluationSubmission' => array(
                        'conditions' => array('EvaluationSubmission.submitter_id' => $this->Auth->user('id'))
                )))
            );
            // no submission - if now is after release date end then - gets final deduction
            if (empty($event_sub['EvaluationSubmission'])) {
                if (time() > $event_end) {
                    $scorePenalty = $this->Penalty->getPenaltyFinal($eventId);
                }
            // there is submission - may be on time or late
            } else {
                $late_diff = strtotime($event_sub['EvaluationSubmission'][0]['date_submitted']) - $event_due;
                // late
                if (0 < $late_diff) {
                    $days_late = $late_diff/(24*60*60);
                    $scorePenalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                }
            }
            $result['penalty'] = $scorePenalty['Penalty']['percent_penalty'];
        } else {
            $groupMembers = $this->GroupsMembers->getEventGroupMembers($event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors($event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $rubricResultDetail = $this->getRubricResultDetail($event, $groupMembers);
            $result['groupMembers'] = $groupMembers;
            $result['groupMembersNoTutors'] = $groupMembersNoTutors;
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



    /**
     * loadMixEvaluationDetail
     * Mixeval Evaluation functions
     *
     * @param mixed $event
     *
     * @access public
     * @return void
     */
    function loadMixEvaluationDetail ($event)
    {
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $this->Mixeval = ClassRegistry::init('Mixeval');

        $result = array();
        $evaluator = $this->Auth->user('id');

        //Get Members for this evaluation
        $groupMembers = $this->GroupsMembers->getEventGroupMembersNoTutors(
            $event['group_id'], $event['Event']['self_eval'], $this->Auth->user('id'));
        for ($i = 0; $i<count($groupMembers); $i++) {
            $targetEvaluatee = $groupMembers[$i]['User']['id'];
            $evaluation = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                $event['group_event_id'], $evaluator, $targetEvaluatee);
            if (!empty($evaluation)) {
                $groupMembers[$i]['User']['Evaluation'] = $evaluation;
                $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] =
                    $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evaluation['EvaluationMixeval']['id']);
            }
        }
        //$this->set('groupMembers', $groupMembers);
        $result['groupMembers'] = $groupMembers;

        //Get the target mixeval
        $this->Mixeval->id = $event['Event']['template_id'];
        //$this->set('mixeval', $this->Mixeval->read());
        $result['mixeval'] = $this->Mixeval->read();

        // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
        $numMembers=count($this->GroupsMembers->getEventGroupMembersNoTutors($event['group_id'], 
            $event['Event']['self_eval'], $evaluator));
        //$this->set('evaluateeCount', $numMembers);
        $result['evaluateeCount'] = $numMembers;

        return $result;
    }


    /**
     * saveMixevalEvaluation
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function saveMixevalEvaluation($params=null)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Mixeval = ClassRegistry::init('Mixeval');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');

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
        for ($i=0; $i<count($evaluatees); $i++) {
            if (isset($params['form'][$evaluatees[$i]]) && $params['form'][$evaluatees[$i]] = 'Save This Section') {
                $targetEvaluatee = $evaluatees[$i];
            }
        }
        $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
            $groupEventId, $evaluator, $targetEvaluatee);

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
            $evalMixeval['EvaluationMixeval']['id'], $mixeval, $targetEvaluatee, $params);

        $evalMixeval['EvaluationMixeval']['score'] = $score;
        if (!$this->EvaluationMixeval->save($evalMixeval)) {
            return false;
        }

        return true;
    }


    /**
     * saveNGetEvalutionMixevalDetail
     *
     * @param mixed $evalMixevalId   mixeval id
     * @param mixed $mixeval         mixeval
     * @param mixed $targetEvaluatee target evaluatee
     * @param mixed $form            form
     *
     * @access public
     * @return void
     */
    function saveNGetEvalutionMixevalDetail($evalMixevalId, $mixeval, $targetEvaluatee, $form)
    {
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $isCheckBoxes = false;
        $totalGrade = 0;
        $pos = 0;

        for ($i=0; $i < $mixeval['Mixeval']['total_question']; $i++) {
            $evalMixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMixevalId, $i);

            if (isset($evalMixevalDetail[$i])) {
                $this->EvaluationMixevalDetail->id=$evalMixevalDetail[$i]['EvaluationMixevalDetail']['id'] ;
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


    /**
     * getMixevalResultDetail
     *
     * @param mixed $event        event
     * @param mixed $groupMembers group members
     *
     * @access public
     * @return void
     */
    function getMixevalResultDetail ($event, $groupMembers)
    {

        $pos = 0;
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationMixevalDetail   = ClassRegistry::init('EvaluationMixevalDetail');
        $mixevalResultDetail = array();
        $memberScoreSummary = array();
        $allMembersCompleted = true;
        $inCompletedMembers = array();
        $evalResult = array();

        if ($event['group_event_id'] && $groupMembers) {
            foreach ($groupMembers as $user) {
                $userPOS = 0;
                if (isset($user['id'])) {
                    $userId = $user['id'];
                    $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(
                        $event['group_event_id'], $userId);
                    // if (isset($evalSubmission['EvaluationSubmission'])) {
                    $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluatee($event['group_event_id'], $userId);
                    $evalResult[$userId] = $mixevalResult;

                    //Get total mark each member received
                    $receivedTotalScore = $this->EvaluationMixeval->getReceivedTotalScore(
                        $event['group_event_id'], $userId);
                    $ttlEvaluatorCount = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount($event['group_event_id'], $userId);
                    if ($ttlEvaluatorCount > 0) {
                        $memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
                        $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] / $ttlEvaluatorCount;
                    }
                    foreach ($mixevalResult as $row) {
                        $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
                        if ($evalMark!=null) {
                            $rubriDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMark['id']);
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
                        $event['group_event_id'], $userId);
                    $receivedAvgScore = $this->EvaluationMixeval->getReceivedAvgScore($event['group_event_id'], $userId);
                    $ttlEvaluatorCount = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(
                        $event['group_event_id'], $userId);
                    if ($ttlEvaluatorCount > 0) {
                        $memberScoreSummary[$userId]['received_count'] = $ttlEvaluatorCount;
                        $memberScoreSummary[$userId]['received_total_score'] = $receivedTotalScore[0]['received_total_score'];
                        $memberScoreSummary[$userId]['received_ave_score'] = $receivedTotalScore[0]['received_total_score'] /
                            $ttlEvaluatorCount;
                    } else {
                        $memberScoreSummary[$userId]['received_count'] = 0;
                        $memberScoreSummary[$userId]['received_total_score'] = 0;
                        $memberScoreSummary[$userId]['received_ave_score'] = 0;
                    }
                    // $memberScoreSummary =   $receivedTotalScore;
                    foreach ($mixevalResult as $row) {
                        $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
                        if ($evalMark!=null) {
                            $rubriDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMark['id']);
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

    /**
     * getStudentViewMixevalResultDetailReview
     *
     * @param mixed $event  event
     * @param mixed $userId user id
     *
     * @access public
     * @return void
     */
    function getStudentViewMixevalResultDetailReview ($event, $userId)
    {
        $userPOS = 0;
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationMixevalDetail   = ClassRegistry::init('EvaluationMixevalDetail');
        $this->User = ClassRegistry::init('User');
        $currentUser=$this->User->getCurrentLoggedInUser();
        $evalResult = array();
        $mixevalResultDetail = array();
        $memberScoreSummary = array();
        $allMembersCompleted = true;
        $inCompletedMembers = array();
        $this->User->id = $userId;
        $user = $this->User->read();
        if ($event['group_event_id'] && $userId) {
            $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluator($event['group_event_id'], $userId);
            $evalResult[$userId] = $mixevalResult;

            foreach ($mixevalResult as $row) {
                $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
                if ($evalMark!=null) {
                    $rubriDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMark['id']);
                    $evalResult[$userId][$userPOS++]['EvaluationMixeval']['details'] = $rubriDetail;
                }
            }
        }
        return $evalResult;
    }


    /**
     * formatMixevalEvaluationResultsMatrix
     *
     * @param mixed $event        event
     * @param mixed $groupMembers group member
     * @param mixed $evalResult   evaluation result
     *
     * @access public
     * @return array
     */
    function formatMixevalEvaluationResultsMatrix($event, $groupMembers, $evalResult)
    {
        //
        // results matrix format:
        // Matrix[evaluatee_id][evaluator_id] = score
        //
        $matrix = array();
        $groupQuestionAve = array();
        if (empty($evalResult)) {
            return false;
        }
        foreach ($evalResult as $index => $value) {
            $evalMarkArray = $value;
            $evalTotal = 0;
            $mixevalQuestion = array();

            if ($evalMarkArray != null) {
                $grade_release = 1;
                $detailPOS = 0;

                foreach ($evalMarkArray as $row) {
                    $evalMark = isset($row['EvaluationMixeval'])? $row['EvaluationMixeval']: null;
                    if ($evalMark!=null) {
                        //print_r($evalMark);
                        $grade_release = $evalMark['grade_release'];
                        $comment_release = $evalMark['comment_release'];
                        //$ave_score= $receivedTotalScore / count($evalMarkArray);
                        //$matrix[$index][$evalMark['evaluator']] = $evalMark['score'];
                        //$matrix[$index]['received_ave_score'] =$ave_score;
                        if ($index == $evalMark['evaluatee']) {
                            $matrix[$index]['grade_released'] =$grade_release;
                            $matrix[$index]['comment_released'] =$comment_release;
                        }
                        //Format the mixeval question
                        foreach ($evalMark['details'] as $detail) {
                            $mixevalResult = $detail['EvaluationMixevalDetail'];
                            if (!isset($mixevalQuestion[$mixevalResult['question_number']])) {
                                $mixevalQuestion[$mixevalResult['question_number']] = 0;
                            }
                            $mixevalQuestion[$mixevalResult['question_number']] += $mixevalResult['grade'];
                        }
                        $detailPOS ++ ;
                    } else {
                        //$matrix[$index][$evalMark['evaluatee']] = 'n/a';
                    }
                }
            } else {
                foreach ($groupMembers as $user) {
                    if (isset($user['User'])) {
                        $user = $user['User'];
                    }
                    if (!empty($user)) {
                        // The array's format varries. Sometime a sub-array [0] is present
                        $id = !empty($user['id']) ? $user['id'] : $user['User']['id'];
                        $matrix[$index][$id] = 'n/a';
                    }
                }
            }
            //Get Ave Question Grade
            foreach ($mixevalQuestion as $criIndex => $criGrade) {
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
        foreach ($groupQuestionAve as $groupIndex => $groupGrade) {
            $ave = $groupGrade / count($evalResult);
            $groupQuestionAve[$groupIndex] = $ave;
        }
        $matrix['group_question_ave'] = $groupQuestionAve;

        return $matrix;
    }


    /**
     * changeMixevalEvaluationGradeRelease
     *
     * @param mixed $eventId       event id
     * @param mixed $groupId       group id
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeMixevalEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus)
    {
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');

        //changing grade release for each EvaluationMixeval
        $evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
        foreach ($evaluationMixeval as $row) {
            $evalMixeval = $row['EvaluationMixeval'];
            if (isset($evalMixeval)) {
                $this->EvaluationMixeval->id = $evalMixeval['id'];
                $evalMixeval['grade_release'] = $releaseStatus;
                $this->EvaluationMixeval->save($evalMixeval);
            }
        }

        //changing grade release status for the GroupEvent
        $this->GroupEvent->id = $groupEventId;
        $oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus($groupEventId, $releaseStatus);
        $groupEvent = $this->formatGradeReleaseStatus(
            $this->GroupEvent->read(), $releaseStatus, $oppositGradeReleaseCount);
        $this->GroupEvent->save($groupEvent);
    }


    /**
     * changeMixevalEvaluationCommentRelease
     *
     * @param mixed $eventId       event id
     * @param mixed $groupId       group id
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeMixevalEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus)
    {
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');

        $this->GroupEvent->id = $groupEventId;
        $groupEvent = $this->GroupEvent->read();

        //changing comment release for each EvaluationMixeval
        $evaluationMixeval = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $evaluateeId);
        foreach ($evaluationMixeval as $row) {
            $evalMixeval = $row['EvaluationMixeval'];
            if (isset($evalMixeval)) {
                $this->EvaluationMixeval->id = $evalMixeval['id'];
                $evalMixeval['comment_release'] = $releaseStatus;
                $this->EvaluationMixeval->save($evalMixeval);;
            }
        }

        //changing comment release status for the GroupEvent
        $this->GroupEvent->id = $groupEventId;
        $oppositGradeReleaseCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus($groupEventId, $releaseStatus);
        $groupEvent = $this->formatCommentReleaseStatus($this->GroupEvent->read(), $releaseStatus,
            $oppositGradeReleaseCount);

        $this->GroupEvent->save($groupEvent);
    }


    /**
     * formatMixevalEvaluationResult
     *
     * @param bool   $event         event
     * @param string $displayFormat display format
     * @param int    $studentView   student view
     *
     * @access public
     * @return void
     */
    function formatMixevalEvaluationResult($event=null, $displayFormat='', $studentView=0)
    {
        $this->Course = ClassRegistry::init('Mixeval');
        $this->Mixeval = ClassRegistry::init('Mixeval');
        $this->User = ClassRegistry::init('User');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->Event = ClassRegistry::init('Event');
        $this->Penalty = ClassRegistry::init('Penalty');

        $evalResult = array();
        $groupMembers = array();
        $groupMembersNoTutors = array();
        $result = array();

        $this->Mixeval->id = $event['Event']['template_id'];

        $mixeval = $this->Mixeval->read();
        $result['mixeval'] = $mixeval;
        $eventId = $event['Event']['id'];

        $currentUser = $this->User->getCurrentLoggedInUser();

        //Get Members for this evaluation
        if ($studentView) {

            $this->User->id = $this->Auth->user('id');

            $this->User->recursive = -1;
            $user = $this->User->read();
            $mixevalResultDetail = $this->getMixevalResultDetail($event, $user);
            $groupMembers = $this->GroupsMembers->getEventGroupMembers(
                $event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors(
                $event['group_id'], $event['Event']['self_eval'], $currentUser['id']);
            $membersAry = array();
            $membersAryNoTutors = array();
            foreach ($groupMembers as $member) {
                $membersAry[$member['User']['id']] = $member;
            }
            foreach ($groupMembersNoTutors as $member) {
                $membersAryNoTutors[$member['User']['id']] = $member;
            }
            $result['groupMembers'] = $membersAry;
            $result['groupMembersNoTutors'] = $membersAryNoTutors;

            $reviewEvaluations = $this->getStudentViewMixevalResultDetailReview(
                $event, $this->Auth->user('id'));
            $result['reviewEvaluations'] = $reviewEvaluations;
            $event_info = $this->Event->find(
                'first',
                array(
                    'conditions' => array('Event.id' => $eventId),
                )
            );
            
            // storing the timestamp of the due date/end date of the event
            $event_due = strtotime($event_info['Event']['due_date']);
            $event_end = strtotime($event_info['Event']['release_date_end']);
            // assign penalty to user if they submitted late or never submitted by release_date_end
            $scorePenalty = null;
            $event_sub = $this->Event->find(
                'first',
                array(
                    'conditions' => array('Event.id' => $eventId),
                    'contain' => array('EvaluationSubmission' => array(
                        'conditions' => array('EvaluationSubmission.submitter_id' => $this->Auth->user('id'))
                )))
            );
            // no submission - if now is after release date end then - gets final deduction
            if (empty($event_sub['EvaluationSubmission'])) {
                if (time() > $event_end) {
                    $scorePenalty = $this->Penalty->getPenaltyFinal($eventId);
                }
            // there is submission - may be on time or late
            } else {
                $late_diff = strtotime($event_sub['EvaluationSubmission'][0]['date_submitted']) - $event_due;
                // late
                if (0 < $late_diff) {
                    $days_late = $late_diff/(24*60*60);
                    $scorePenalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                }
            }
            $result['penalty'] = $scorePenalty['Penalty']['percent_penalty'];
        } else {
            $groupMembers = $this->GroupsMembers->getEventGroupMembers(
                $event['group_id'], $event['Event']['self_eval'], $this->Auth->user('id'));
            $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors(
                $event['group_id'], $event['Event']['self_eval'], $this->Auth->user('id'));
            $mixevalResultDetail = $this->getMixevalResultDetail($event, $groupMembers);
            $result['groupMembers'] = $groupMembers;
            $result['groupMembersNoTutors'] = $groupMembersNoTutors;
        }
        
        //Get Detail information on Mixeval score
        if ($displayFormat == 'Detail') {
            //echo 'ss';
            $mixevalQuestion = $this->MixevalsQuestion->getQuestion($mixeval['Mixeval']['id']);
            foreach ($mixevalQuestion as $row) {
                $row['MixevalsQuestion']['Description'] = $row['Description'];
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



    /**
     * saveSurveyEvaluation
     * Survey Evaluation functions
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function saveSurveyEvaluation($params=null)
    {
        $this->Response = ClassRegistry::init('Response');
        $this->Question = ClassRegistry::init('Question');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');

        $userId = $params['data']['Evaluation']['surveyee_id'];
        $eventId = $params['form']['event_id'];
        $surveyId = $params['form']['survey_id'];

        //get existing record if there is one
        $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
        if (empty($evaluationSubmission)) {
            //if there is no existing record, fill in all fields
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $userId;
            $evaluationSubmission['EvaluationSubmission']['survey_id'] = $surveyId;
            $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
        } else {
            //if existing record, just update the time submitted
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
        }
        $surveyInput=array();
        $surveyInput['SurveyInput']['user_id'] = $userId;
        $surveyInput['SurveyInput']['survey_id'] = $surveyId;
        $successfullySaved = true;
        for ($i=1; $i<=$params['form']['question_count']; $i++) {
            $this->SurveyInput = new SurveyInput;
            //Set survey and user id
            $surveyInput[$i]['SurveyInput']['user_id'] = $userId;
            $surveyInput[$i]['SurveyInput']['survey_id'] = $surveyId;
            //Set question Id
            $questionId = $params['form']['question_id'.$i];
            $surveyInput[$i]['SurveyInput']['question_id'] = $questionId;
            //Set answers
            $answer = $params['form']['answer_'.$questionId];
            $modAnswer =  $this->filterString($answer);
            $surveyInput[$i]['SurveyInput']['response_text']=$modAnswer;
            //Set reponse_id
            $response = $params['form']['answer_'.$questionId];
            $modResponse = $this->filterString($response);
            $responseId = $this->Response->getResponseId($questionId, $modResponse);
            $surveyInput[$i]['SurveyInput']['response_id']=$responseId;
            //Check SurveyInput existed
            $this->SurveyInput->recursive = 0;
            $surveyInputId = $this->SurveyInput->find('first', array(
                'conditions' => array('SurveyInput.survey_id' => $surveyId,
                'SurveyInput.user_id' => $userId,
                'SurveyInput.question_id' => $questionId),
                'fields' => array('SurveyInput.id')
            ));
            if ($surveyInputId) {
                $surveyInput[$i]['SurveyInput']['id'] = $surveyInputId['SurveyInput']['id'];
            }
            //Save data
            if (!$this->SurveyInput->save($surveyInput[$i]['SurveyInput'])) {
                $successfullySaved=false;
            }
        }

        //Indicate that evaluation has been submitted
        if ($successfullySaved) {
            $this->EvaluationSubmission->save($evaluationSubmission);
            return true;
        } else {
            return false;
        }
    /*
    // if no submission exists, create one
    //$surveyInput['SurveyInput']['event_id'] = $params['form']['event_id'];
    // save evaluation submission
    //$surveyInput['SurveyInput']['date_submitted'] = date('Y-m-d H:i:s');

    //parse for question id and their response id/text
    //then save
    for ($i=1; $i < $params['form']['question_count']+1; $i++) {
        $questionId = $params['form']['question_id'.$i];
        if (isset($params['form']['answer_'.$i])) {
            if (is_array($params['form']['answer_'.$i])) {
                if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId)) {
                    die('delete failed');
                }
                //parse answers for 'any choice' type
                for ($j=0; $j < count($params['form']['answer_'.$i]); $j++) {
                    $surveyInput['SurveyInput']['user_id'] = $userId;
                    $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                    $surveyInput['SurveyInput']['question_id'] = $questionId;

                    $answer = explode("_", $params['form']['answer_'.$i][$j]);
                    $surveyInput['SurveyInput']['response_id'] = $answer[1];
                    if (!$this->SurveyInput->save($surveyInput)) {
                        return false;
                    }
                    $this->SurveyInput->id = null;
                }
            } else {
                //get existing 'answer' record
                $surveyInput = $this->SurveyInput->getSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId);

                //if none exists fill in fields
                if (empty($surveyInput)) {
                    $surveyInput['SurveyInput']['user_id'] = $userId;
                    $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                }
                $surveyInput['SurveyInput']['question_id'] = $questionId;

                //check for MC
                $type = $this->Question->getTypeById($questionId);
                if ($type == 'M') {
                    $answer = explode("_", $params['form']['answer_'.$i]);
                    $surveyInput['SurveyInput']['response_id'] = $answer[1];
                } else {
                    $surveyInput['SurveyInput']['response_text'] = $params['form']['answer_'.$i];
                }
                if (!$this->SurveyInput->save($surveyInput)) {
                    return false;
                }
                $this->SurveyInput->id = null;
            }
        } else {
            if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId)) {
            die('delete failed');
            } else {
                if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId)) {
                    die('delete failed');
                }
                //parse answers for 'any choice' type
                $surveyInput['SurveyInput']['user_id'] = $userId;
                $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                $surveyInput['SurveyInput']['question_id'] = $questionId;
                if (!$this->SurveyInput->save($surveyInput)) {
                    return false;
                }
                $this->SurveyInput->id = null;
            }
        }
    }
    if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
        echo "this->EvaluationSubmission->save() returned false";
        return false;
    }
    $this->EvaluationSubmission->id = null;
    return true;
     */
    }


    /**
     * formatStudentViewOfSurveyEvaluationResult
     *
     * @param bool $event
     *
     * @access public
     * @return void
     */
    function formatStudentViewOfSurveyEvaluationResult($event=null)
    {
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
                $numMembers=$event['Event']['self_eval'] ?
                    $this->GroupsMembers->find('count', 'group_id='.$event['group_id']) :
                    $this->GroupsMembers->find('count', 'group_id='.$event['group_id']) - 1;
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


    /**
     * formatSurveyEvaluationResult
     *
     * @param bool $event     event
     * @param bool $studentId student id
     *
     * @access public
     * @return void
     */
    function formatSurveyEvaluationResult($event=null, $studentId=null)
    {
        $this->Survey = ClassRegistry::init('Survey');
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
        $this->Response = ClassRegistry::init('Response');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');

        $result = array();

        $survey_id = $this->Survey->getSurveyIdByCourseIdTitle($this->Session->read('ipeerSession.courseId'), $event['Event']['title']);
        //$this->set('survey_id', $survey_id);
        $result['survey_id'] = $survey_id;

        // Get all required data from each table for every question
        $tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
        $tmp = $this->Question->fillQuestion($tmp);
        $tmp = $this->Response->fillResponse($tmp);
        $questions = null;

        // Sort the resultant array by question number
        $count = 1;
        for ($i = 0; $i <= count($tmp); $i++) {
            for ($j=0; $j<count($tmp); $j++) {
                if ($i == $tmp[$j]['Question']['number']) {
                    $questions[$count]['Question'] = $tmp[$j]['Question'];
                    $count++;
                }
            }
        }
        $answers = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId($survey_id, $studentId);
        //$this->set('answers', $answers);
        $result['answers'] = $answers;
        //$this->set('questions', $result);
        $result['questions'] = $questions;
        //$this->set('event', $event);
        $result['event'] = $event;
        return $result;
    }


    /**
     * formatSurveyGroupEvaluationResult
     *
     * @param bool $surveyId      survey id
     * @param bool $surveyGroupId survey group id
     *
     * @access public
     * @return void
     */
    function formatSurveyGroupEvaluationResult($surveyId=null, $surveyGroupId=null)
    {
        $this->Survey = ClassRegistry::init('Survey');
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
        $this->Response = ClassRegistry::init('Response');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');
        $this->User = ClassRegistry::init('User');

        $result = array();

        // Get all required data from each table for every question
        $tmp = $this->SurveyQuestion->getQuestionsID($surveyId);
        $tmp = $this->Question->fillQuestion($tmp);
        $tmp = $this->Response->fillResponse($tmp);
        $questions = null;

        // Sort the resultant array by question number
        $count = 1;
        for ($i=0; $i<=count($tmp); $i++) {
            for ($j=0; $j<count($tmp); $j++) {
                if ($i == $tmp[$j]['Question']['number']) {
                    $questions[$count]['Question'] = $tmp[$j]['Question'];
                    $count++;
                }
            }
        }

        for ($i=1; $i < count($questions)+1; $i++) {
            $questionType = $questions[$i]['Question']['type'];
            $questionTypeAllowed = array('C', 'M');
            $questionId = $questions[$i]['Question']['id'];

            //count the choice responses
            if (in_array($questionType, $questionTypeAllowed)) {
                $totalResponsePerQuestion = 0;
                for ($j=0; $j < count($questions[$i]['Question']['Responses']); $j++) {
                    $responseId = $questions[$i]['Question']['Responses']['response_'.$j]['id'];
                    $answerCount = $this->SurveyInput->findCountInSurveyGroup($surveyId, $questionId, $responseId, $surveyGroupId);
                    //echo $surveyId.';'.$questionId.' '.$responseId.' '.$surveyGroupId;
                    //print_r($answerCount); die;
                    $questions[$i]['Question']['Responses']['response_'.$j]['count'] = $answerCount;
                    $totalResponsePerQuestion += $answerCount;
                }
                $questions[$i]['Question']['total_response'] = $totalResponsePerQuestion;
            } else {
                $responses = $this->SurveyInput->findResponseInSurveyGroup($surveyId, $questionId, $surveyGroupId);
                $questions[$i]['Question']['Responses'] = array();
                //sort results by last name
                $tmpUserResponse = array();
                for ($j=0; $j < count($responses); $j++) {
                    $responseText = $responses[$j]['SurveyInput']['response_text'];
                    $userId = $responses[$j]['SurveyInput']['user_id'];
                    $userName = $this->User->findById($userId);
                    $userName = $userName['User']['last_name'].", ".$userName['User']['first_name'];
                    $tmpUserResponse[$userName]['response_text'] = $responseText;
                }
                ksort($tmpUserResponse);

                $k=1;
                foreach ($tmpUserResponse as $username => $response) {
                    $questions[$i]['Question']['Responses']['response_'.$k]['response_text'] = $response['response_text'];
                    $questions[$i]['Question']['Responses']['response_'.$k]['user_name'] = $username;
                    $k++;
                }
            }
        }

        return $questions;
    }


    /**
     * formatSurveyEvaluationSummary
     *
     * @param bool $surveyId
     *
     * @access public
     * @return void
     */
    function formatSurveyEvaluationSummary($surveyId=null)
    {
        $this->Survey = ClassRegistry::init('Survey');
        //$this->SurveyQuestion = new SurveyQuestion;
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
        $this->Response = ClassRegistry::init('Response');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');
        $this->User = ClassRegistry::init('User');

        $result = array();
        $survey_id = $surveyId;

        // Get all required data from each table for every question
        $tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
        $tmp = $this->Question->fillQuestion($tmp);
        $tmp = $this->Response->fillResponse($tmp);

        $questions = null;

        // Sort the resultant array by question number
        $count = 1;
        for ($i=0; $i<=count($tmp); $i++) {
            for ($j=0; $j<count($tmp); $j++) {
                if ($i == $tmp[$j]['Question']['number']) {
                    $questions[$count]['Question'] = $tmp[$j]['Question'];
                    $count++;
                }
            }
        }

        for ($i=1; $i < count($questions)+1; $i++) {
            $questionType = $questions[$i]['Question']['type'];
            $questionTypeAllowed = array('C', 'M');
            $questionId = $questions[$i]['Question']['id'];

            //count the choice responses
            if (in_array($questionType, $questionTypeAllowed)) {
                $totalResponsePerQuestion = 0;
                for ($j=0; $j < count($questions[$i]['Question']['Responses']); $j++) {
                    $responseId = $questions[$i]['Question']['Responses']['response_'.$j]['id'];
                    $answerCount = $this->SurveyInput->find('count', array('conditions' => array('survey_id' => $surveyId,
                        'question_id' => $questionId,
                        'response_id' => $responseId)));
                    $questions[$i]['Question']['Responses']['response_'.$j]['count'] = $answerCount;
                    $totalResponsePerQuestion += $answerCount;
                }
                $questions[$i]['Question']['total_response'] = $totalResponsePerQuestion;
            } else {

                $responses = $this->SurveyInput->find('all', array(
                    'conditions' => array('SurveyInput.survey_id' => $surveyId,
                    'SurveyInput.question_id' => $questionId),
                    'fields' => array('response_text', 'user_id')

                ));
                $questions[$i]['Question']['Responses'] = array();
                //sort results by last name
                $tmpUserResponse = array();

                for ($j=0; $j < count($responses); $j++) {
                    $responseText = $responses[$j]['SurveyInput']['response_text'];
                    $userId = $responses[$j]['SurveyInput']['user_id'];
                    $userName = $this->User->findById($userId);
                    $userName = $userName['User']['last_name'].", ".$userName['User']['first_name'];
                    $tmpUserResponse[$userName]['response_text'] = $responseText;
                }
                ksort($tmpUserResponse);
                $k=1;
                foreach ($tmpUserResponse as $username => $response) {
                    $questions[$i]['Question']['Responses']['response_'.$k]['response_text'] = $response['response_text'];
                    $questions[$i]['Question']['Responses']['response_'.$k]['user_name'] = $username;
                    $k++;
                }
            }
        }
        return $questions;
    }

}
