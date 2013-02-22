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
        $pos = 0;
        foreach ($evaluatees as $value) {
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
            $evalMarkRecord['EvaluationSimple']['comment'] = $comments[$pos];
            $evalMarkRecord['EvaluationSimple']['date_submitted'] = date('Y-m-d H:i:s');

            if (!$this->EvaluationSimple->save($evalMarkRecord)) {
                return false;
            }
            $this->EvaluationSimple->id=null;
            $pos++;
        }

        // if no submission exists, create one
        $sub = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEvent['GroupEvent']['id'], $evaluator);
        if (empty($sub)) {
            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEvent['GroupEvent']['id'];
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $groupEvent['GroupEvent']['event_id'];
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
            // save evaluation submission
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
            if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
                return false;
            }
        }
        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo =
            $this->EvaluationSubmission->numCountInGroupCompleted(
                $groupEvent['GroupEvent']['id']);
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
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeSimpleEvaluationGradeRelease($groupEventId, $evaluateeId, $releaseStatus)
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
     * @param mixed $groupEventId group event id
     * @param mixed $evaluatorIds evaluator ids
     * @param mixed $params       params
     *
     * @access public
     * @return void
     */
    function changeSimpleEvaluationCommentRelease($groupEventId, $evaluatorIds, $params)
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
            foreach ($evaluatorIds as $value) {
                if ($evaluator != $value) {
                    //Check for released guys
                    if (isset($params['form']['release'.$value])) {
                        $evaluateeIds = $params['form']['release'.$value];
                        $idString = array();
                        $pos = 1;
                        foreach ($evaluateeIds as $id) {
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
    function formatSimpleEvaluationResult($event)
    {
        $this->User = new User;
        $this->GroupsMembers = new GroupsMembers;
        $this->EvaluationSimple = new EvaluationSimple;
        $this->EvaluationSubmission = new EvaluationSubmission;
        $this->SimpleEvaluation = new SimpleEvaluation;
        $result = array();

        $eventId = $event['Event']['id'];
        $groupId = $event['Group']['id'];
        $grpEventId = $event['GroupEvent']['id'];
        $subs = $this->EvaluationSubmission->findAllByGrpEventId($grpEventId,
            null, null, null, null, -1);
        $ret = array();
        $submitterIds = array(); // keeps track of the users who have submitted
        $evaluateeIds = array(); // keeps track of users who were evaluated
        // Grab submissions and calculate the total grade and # of evaluators
        $ret['TotalGrades'] = array();
        foreach ($subs as $sub) {
            $submitter = $sub['EvaluationSubmission']['submitter_id'];
            $submitterIds[$submitter] = $submitter;
            $results = $this->EvaluationSimple->
                getResultsByEvaluator($grpEventId, $submitter);
            $tmp = array();
            foreach ($results as $result) {
                $evalRes = $result['EvaluationSimple'];
                $evaluatee = $evalRes['evaluatee'];
                $evaluateeIds[$evaluatee] = $evaluatee;
                $tmp[$evaluatee] = $evalRes;
                // keep track of the total score for this evaluatee
                if (isset($ret['TotalGrades'][$evaluatee])) {
                    $ret['TotalGrades'][$evaluatee] += $evalRes['score'];
                }
                else {
                    $ret['TotalGrades'][$evaluatee] = $evalRes['score'];
                }
                // keep track of the number of evaluators for this evaluatee
                if (isset($ret['NumEvaluators'][$evaluatee])) {
                    $ret['NumEvaluators'][$evaluatee] += 1;
                }
                else {
                    $ret['NumEvaluators'][$evaluatee] = 1;
                }
            }
            $ret['Submissions'][$submitter] = $tmp;
        }
        // Get user info for all users who have submitted a submission or
        // been evaluated in a submission. Such users is not guaranteed to
        // still be in the group or the course when you view the results
        // (e.g.: they might have dropped the course) but still needs to be
        // displayed.
        $groupIds = $this->GroupsMembers->find(
            'list',
            array(
                'conditions' => array('group_id' => $groupId),
                // indexed by user_id instead of id
                'fields' => array('user_id', 'user_id')
            )
        );
        $droppedUsers = array_diff($submitterIds, $groupIds);
        $droppedUsers += array_diff($evaluateeIds, $groupIds);
        // all users currently in the group or have submitted/been evaluated
        $ret['All'] = $groupIds + $droppedUsers;
        // users currently in the group but who haven't made a submission
        $ret['Incomplete'] = array_diff($ret['All'], $droppedUsers,
            $submitterIds);
        // users who are currently in the group
        $ret['GroupMembers'] = $groupIds;
        // users no longer in the group but still relevant
        $ret['Dropped'] = $droppedUsers;

        // grab user info from user ids
        foreach ($ret['All'] as $userid) {
            $userinfo = $this->User->find('first',
                array(
                    'conditions' => array('id' => $userid),
                    'contain' => array('Role')
                )
            );
            $ret['All'][$userid] = $userinfo;
        }

        // calculate penalty
        $ret['Penalties'] = $this->SimpleEvaluation->formatPenaltyArray(
            $ret['All'], $eventId, $groupId);
        foreach ($ret['TotalGrades'] as $userid => $grade) {
            $ret['FinalGrades'][$userid] = $grade -
                ($grade * ($ret['Penalties'][$userid] / 100));
        }

        // get release status
        $ret['ReleaseStatus'] = $this->EvaluationSimple->getTeamReleaseStatus(
            $grpEventId);
        return $ret;
    }



    /**
     * loadRubricEvaluationDetail
     * Rubric Evaluation functions
     *
     * @param mixed $event
     * @param mixed $groupId
     *
     * @access public
     * @return void
     */
    function loadRubricEvaluationDetail($event, $groupId)
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
            $event['Group']['id'], $event['Event']['self_eval'], $evaluator);
        for ($i = 0; $i<count($groupMembers); $i++) {
            $targetEvaluatee = $groupMembers[$i]['User']['id'];
            $evaluation = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(
                $event['GroupEvent']['id'], $evaluator, $targetEvaluatee);
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
        $numMembers = count($this->GroupsMembers->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator));
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

        //Get the target event
        $eventId = $params['form']['event_id'];

        $this->Event->id = $eventId;
        $event = $this->Event->read();

        //Get simple evaluation tool
        $this->Rubric->id = $event['Event']['template_id'];
        $rubric = $this->Rubric->read();

        // Save evaluation data
        // total grade for evaluatee from evaluator
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

        $evalRubric['EvaluationRubric']['comment'] = $params['form'][$targetEvaluatee.'gen_comment'];
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

        if (empty($event) || empty($userId)) {
            return false;
        }

        $this->User->id = $userId;

        if ($event['GroupEvent']['id'] && $userId) {
            $rubricResult = $this->EvaluationRubric->getResultsByEvaluator($event['GroupEvent']['id'], $userId);

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
     * @param mixed $evalResult evel result
     * @param mixed $rubric     rubric with critieria
     *
     * @access public
     * @return void
     */
    function formatRubricEvaluationResultsMatrix($evalResult, $rubric)
    {
        $summary = array();
        $criteria = array();        // for storing criteria numbers
        foreach ($evalResult as $result) {
            $userId = $result['EvaluationRubric']['evaluatee'];
            $evaluator = $result['EvaluationRubric']['evaluator'];
            $summary[$userId]['gradeRelease'] = $result['EvaluationRubric']['grade_release'];
            $summary[$userId]['commentRelease'] = $result['EvaluationRubric']['comment_release'];
            $summary[$userId]['total']['score'] = (isset($summary[$userId]['total']['score'])) ?
                $summary[$userId]['total']['score'] + $result['EvaluationRubric']['score'] : $result['EvaluationRubric']['score'];
            $summary[$userId]['evaluator_count'] = (isset($summary[$userId]['evaluator_count'])) ?
                $summary[$userId]['evaluator_count'] + 1 : 1;
            foreach ($result['EvaluationRubricDetail'] as $detail) {
                $criteria[] = $detail['criteria_number'];
                $summary[$userId]['grades'][$detail['criteria_number']]['grade'] = (isset($summary[$userId]['grades'][$detail['criteria_number']]['grade'])) ?
                    $summary[$userId]['grades'][$detail['criteria_number']]['grade'] + $detail['grade'] : $detail['grade'];
                $summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'] = (isset($summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'])) ?
                    $summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'] + 1 : 1;
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['grade'] =
                    $detail['grade'];
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['comment'] =
                    $detail['criteria_comment'];
            }
            $summary[$userId]['individual'][$evaluator]['general_comment'] = $result['EvaluationRubric']['comment'];
        }

        foreach ($summary as $id => $score) {
            $summary[$id]['total'] = $score['total']['score'] / $score['evaluator_count'];
            foreach ($score['grades'] as $num => $grade) {
                $summary[$id]['grades'][$num] = $grade['grade']/$grade['evaluator_count'];
            }
        }

        foreach (array_unique($criteria) as $num) {
            $grades = Set::extract($summary, '/grades/'.$num);
            $group['grades'][$num] = array_sum($grades) / count($grades);
        }

        return $summary + $group;
    }


    /**
     * changeRubricEvaluationGradeRelease
     *
     * @param mixed $groupEventId  group even tid
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeRubricEvaluationGradeRelease ($groupEventId, $evaluateeId, $releaseStatus)
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
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeRubricEvaluationCommentRelease ($groupEventId, $evaluateeId, $releaseStatus)
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
            $event['Group']['id'], $event['Event']['self_eval'], $this->Auth->user('id'));
        for ($i = 0; $i<count($groupMembers); $i++) {
            $targetEvaluatee = $groupMembers[$i]['User']['id'];
            $evaluation = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                $event['GroupEvent']['id'], $evaluator, $targetEvaluatee);
            if (!empty($evaluation)) {
                $groupMembers[$i]['User']['Evaluation'] = $evaluation;
                $groupMembers[$i]['User']['Evaluation']['EvaluationDetail'] =
                    $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evaluation['EvaluationMixeval']['id']);
            }
        }
        //$this->set('groupMembers', $groupMembers);
        $result['groupMembers'] = $groupMembers;

        $result['mixeval'] = $this->Mixeval->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']),
            'contain' => array('Question' => 'Description'),
        ));

        // index by question number
        if (!empty($result['mixeval']['Question'])) {
            $result['mixeval']['Question'] = Set::combine($result['mixeval']['Question'], '{n}.question_num', '{n}');
        }

        // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
        $numMembers = count($this->GroupsMembers->getEventGroupMembersNoTutors($event['Group']['id'],
            $event['Event']['self_eval'], $evaluator));
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
        $totalGrade = 0;

        for ($i=1; $i <= $mixeval['Mixeval']['total_question']; $i++) {
            $evalMixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($evalMixevalId, $i);

            if (isset($evalMixevalDetail[$i])) {
                $this->EvaluationMixevalDetail->id=$evalMixevalDetail[$i]['EvaluationMixevalDetail']['id'] ;
            }
            $evalMixevalDetail['EvaluationMixevalDetail']['evaluation_mixeval_id'] = $evalMixevalId;
            $evalMixevalDetail['EvaluationMixevalDetail']['question_number'] = $i;

            if ($form['data']['Mixeval']['mixeval_question_type_id'.$i] == '1') {
                // get total possible grade for the question number ($i)
                $selectedLom = $form['form']['selected_lom_'.$targetEvaluatee.'_'.$i];
                $grade = $form['form'][$targetEvaluatee.'criteria_points_'.$i];
                $evalMixevalDetail['EvaluationMixevalDetail']['selected_lom'] = $selectedLom;
                $evalMixevalDetail['EvaluationMixevalDetail']['grade'] = $grade;
                $totalGrade += $grade;
            } else if ($form['data']['Mixeval']['mixeval_question_type_id'.$i] == '2') {
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
     * @param mixed $groupEventId group event id
     * @param mixed $groupMembers group members
     *
     * @access public
     * @return void
     */
    function getMixevalResultDetail($groupEventId, $groupMembers)
    {
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $mixevalResultDetail = array();
        $memberScoreSummary = array();
        $evalResult = array();
        if ($groupEventId && $groupMembers) {
            foreach ($groupMembers as $user) {
                $userId = isset($user['User'])? $user['User']['id'] : $user['id'];

                // filter out the people who are not student, they should not get result
                if ($user['Role'][0]['name'] != 'student') {
                    continue;
                }

                // get the results for students
                $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $userId);
                $evalResult[$userId] = $mixevalResult;

                //Get total mark each member received
                $memberScoreSummary[$userId]['received_count'] = count($mixevalResult);
                $memberScoreSummary[$userId]['received_total_score'] = array_sum(Set::extract($mixevalResult, '/EvaluationMixeval/score'));
                $memberScoreSummary[$userId]['received_ave_score'] = ($memberScoreSummary[$userId]['received_count'] == 0 ?
                    0 : $memberScoreSummary[$userId]['received_total_score'] / $memberScoreSummary[$userId]['received_count']);
            }
        }

        $mixevalResultDetail['scoreRecords'] =  $this->formatMixevalEvaluationResultsMatrix($evalResult);
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
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationMixevalDetail   = ClassRegistry::init('EvaluationMixevalDetail');
        $this->User = ClassRegistry::init('User');
        $evalResult = array();
        $this->User->id = $userId;
        if ($event['GroupEvent']['id'] && $userId) {
            $mixevalResult = $this->EvaluationMixeval->getResultsByEvaluator($event['GroupEvent']['id'], $userId);
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
     * results matrix format:
     * Matrix[evaluatee_id][evaluator_id] = score
     *
     * @param mixed $groupMembers group member
     * @param mixed $evalResult   evaluation result
     *
     * @access public
     * @return array
     */
    /*function formatMixevalEvaluationResultsMatrix($groupMembers, $evalResult)
    {
        $matrix = array();
        $groupQuestionAve = array();
        if (empty($evalResult)) {
            return false;
        }
        foreach ($evalResult as $index => $value) {
            $evalMarkArray = $value;
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

        var_dump($matrix);
        return $matrix;
    }*/

    /**
     * formatMixevalEvaluationResultsMatrix
     * results matrix format:
     * Matrix[evaluatee_id][question_index] = score
     *
     * @param mixed $evalResults evaluation result
     *
     * @access public
     * @return array
     */
    function formatMixevalEvaluationResultsMatrix($evalResults)
    {
        $matrix = array();
        foreach ($evalResults as $userId => $evals) {
            $counter = array();
            $matrix[$userId] = array();
            foreach ($evals as $eval) {
                foreach ($eval['EvaluationMixevalDetail'] as $detail) {
                    // skip the comment question
                    if ($detail['question_comment'] !== null) {
                        continue;
                    }
                    $counter[$detail['question_number']] = isset($counter[$detail['question_number']]) ?
                        $counter[$detail['question_number']] : 0;
                    $matrix[$userId][$detail['question_number']] = isset($matrix[$userId][$detail['question_number']]) ?
                        $matrix[$userId][$detail['question_number']] : 0;
                    $matrix[$userId][$detail['question_number']] += $detail['grade'];
                    // need a counter for each question, in case different number of evalutions
                    // for each question (optional questoin)
                    $counter[$detail['question_number']]++;
                }
            }
            foreach ($counter as $questionNumber => $count) {
                $matrix[$userId][$questionNumber] = $matrix[$userId][$questionNumber]/$count;
            }
        }

        return $matrix;
    }

    /**
     * changeMixevalEvaluationGradeRelease
     *
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeMixevalEvaluationGradeRelease ($groupEventId, $evaluateeId, $releaseStatus)
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
     * @param mixed $groupEventId  group event id
     * @param mixed $evaluateeId   evaluatee id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function changeMixevalEvaluationCommentRelease ($groupEventId, $evaluateeId, $releaseStatus)
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
    function formatMixevalEvaluationResult($event, $displayFormat='', $studentView=0)
    {
        $this->Mixeval = ClassRegistry::init('Mixeval');
        $this->User = ClassRegistry::init('User');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->Event = ClassRegistry::init('Event');
        $this->Penalty = ClassRegistry::init('Penalty');

        $groupMembers = array();
        $groupMembersNoTutors = array();
        $result = array();

        $eventId = $event['Event']['id'];

        $currentUser = $this->User->getCurrentLoggedInUser();

        //Get Members for this evaluation
        if ($studentView) {

            $user = $this->User->find('first', array(
                'conditions' => array('id' => User::get('id')),
                'contain' => array('Role'),
            ));
            $mixevalResultDetail = $this->getMixevalResultDetail($event['GroupEvent']['id'], array($user));
            $groupMembers = $this->GroupsMembers->getEventGroupMembers(
                $event['Group']['id'], $event['Event']['self_eval'], $currentUser['id']);
            $groupMembersNoTutors = $this->GroupsMembers->getEventGroupMembersNoTutors(
                $event['Group']['id'], $event['Event']['self_eval'], $currentUser['id']);
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
            $groupMembers = $this->User->getMembersByGroupId(
                $event['Group']['id'],
                ($event['Event']['self_eval'] ? null : $this->Auth->user('id'))
            );
            $mixevalResultDetail = $this->getMixevalResultDetail($event['GroupEvent']['id'], $groupMembers);
        }

        //Get Detail information on Mixeval score
        $mixevalQuestion = $this->MixevalQuestion->getQuestion($mixeval['Mixeval']['id']);
        $gradeReleaseStatus = $this->EvaluationMixeval->getTeamReleaseStatus($event['GroupEvent']['id']);

        $result['gradeReleaseStatus'] = $gradeReleaseStatus;

        return $result;
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

        $results = $this->EvaluationSimple->getResultsByEvaluatee($event['GroupEvent']['id'], $this->Auth->user('id'));
        if ($results !=null) {
            //Get Grade Release: grade_release will be the same for all evaluatee records
            $gradeReleaseStatus = $results[0]['EvaluationSimple']['grade_release'];
            if ($gradeReleaseStatus) {
                //Grade is released; retrieve all grades
                //Get total mark each member received
                $receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore(
                    $event['GroupEvent']['id'], $this->Auth->user('id'));
                $totalScore = $receivedTotalScore[0]['received_total_score'];
                $numMembers=$event['Event']['self_eval'] ?
                    $this->GroupsMembers->find('count', 'group_id='.$event['Group']['id']) :
                    $this->GroupsMembers->find('count', 'group_id='.$event['Group']['id']) - 1;
                $aveScore = $totalScore / $numMembers;
                $studentResult['numMembers'] = $numMembers;
                $studentResult['receivedNum'] = count($receivedTotalScore);

                $groupTotal = $this->EvaluationSimple->getGroupResultsByGroupEventId($event['GroupEvent']['id']);
                $groupTotalCount = $this->EvaluationSimple->getGroupResultsCountByGroupEventId($event['GroupEvent']['id']);
                $groupAve = $groupTotal[0]['received_total_score'] / $groupTotalCount[0]['received_total_count'];
            }
            $studentResult['aveScore'] = $aveScore;
            $studentResult['groupAve'] = $groupAve;

            //Get Comment Release: release_status will be the same for all evaluatee
            $commentReleaseStatus = $results[0]['EvaluationSimple']['release_status'];
            if ($commentReleaseStatus) {
                //Comment is released; retrieve all comments
                $comments = $this->EvaluationSimple->getAllComments($event['GroupEvent']['id'], $this->Auth->user('id'));
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
    function formatSurveyEvaluationResult($event, $studentId=null)
    {
        $this->Survey = ClassRegistry::init('Survey');
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
        $this->Response = ClassRegistry::init('Response');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');

        $result = array();

        $survey_id = $event['Event']['template_id'];
        $result['survey_id'] = $survey_id;

        // Get all required data from each table for every question
        $survey = $this->Survey->getSurveyWithQuestionsById($survey_id);
        $answers = $this->SurveyInput->getByEventIdUserId(
            $event['Event']['id'], $studentId);

        $result['answers'] = $answers;
        $result['questions'] = $survey['Question'];
        $result['event'] = $event;

        return $result;
    }

    /**
     * formatSurveyEvaluationSummary
     *
     * @param bool $surveyId survey id
     * @param bool $eventId  event  id
     * @param bool $userIds  the user ids to search with
     *
     * @access public
     * @return void
     */
    function formatSurveyEvaluationSummary($surveyId, $eventId, $userIds = array())
    {
        $this->Survey = ClassRegistry::init('Survey');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');
        $this->User = ClassRegistry::init('User');

        // Get all required data from each table for every question
        $survey = $this->Survey->getSurveyWithQuestionsById($surveyId);
        $questions = $survey['Question'];
        $conditions = array('event_id' => $eventId);
        if (!empty($userIds)) {
            // use userIds to exclude drop students
            $conditions['user_id'] = $userIds;
        }

        foreach ($questions as $i => $question) {
            $questionType = $question['type'];
            $questionTypeAllowed = array('C', 'M');
            $questionId = $question['id'];

            //count the choice responses
            if (in_array($questionType, $questionTypeAllowed)) {
                $totalResponsePerQuestion = 0;
                for ($j=0; $j < count($question['Response']); $j++) {
                    $answerCount = $this->SurveyInput->find('count', array(
                        'conditions' => array_merge($conditions, array(
                            'question_id' => $questionId,
                            'response_id' => $question['Response'][$j]['id'],
                        ))
                    ));
                    $questions[$i]['Response'][$j]['count'] = $answerCount;
                    $totalResponsePerQuestion += $answerCount;
                }
                $questions[$i]['total_response'] = $totalResponsePerQuestion;
            } else {

                $responses = $this->SurveyInput->find('all', array(
                    'fields' => array('response_text', 'user_id'),
                    'conditions' => array_merge($conditions, array(
                        'question_id' => $questionId,
                    )),
                ));
                $questions[$i]['Responses'] = array();
                //sort results by last name
                $tmpUserResponse = array();

                for ($j=0; $j < count($responses); $j++) {
                    $responseText = $responses[$j]['SurveyInput']['response_text'];
                    $userId = $responses[$j]['SurveyInput']['user_id'];
                    $userName = $this->User->findById($userId);
                    $userName = $userName['User']['full_name'];
                    $tmpUserResponse[$userName]['response_text'] = $responseText;
                }
                ksort($tmpUserResponse);
                $k=1;
                foreach ($tmpUserResponse as $username => $response) {
                    $questions[$i]['Response'][$k]['response_text'] = $response['response_text'];
                    $questions[$i]['Response'][$k]['user_name'] = $username;
                    $k++;
                }
            }
        }
        return $questions;
    }
}
