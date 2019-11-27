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
class EvaluationComponent extends CakeObject
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
     * markSimpleEvalReviewed
     *
     * @param mixed $eventId
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function markSimpleEvalReviewed($eventId, $grpEventId)
    {
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');
        
        // set group event to reviewed if all evaluatees' release status has been modified
        $eval = $this->EvaluationSimple->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'order' => array('EvaluationSimple.modified ASC')
        ));
        $event = $this->Event->findById($eventId);
        $status = $this->EvaluationSimple->findAllByGrpEventId($grpEventId);
        $status = Set::extract('/EvaluationSimple/release_status', $status);
        $all = array_product($status);
        $some = array_sum($status);
        $this->GroupEvent->id = $grpEventId;
        if ($all) {
            $this->GroupEvent->saveField('comment_release_status', 'All');
        } else if ($some) {
            $this->GroupEvent->saveField('comment_release_status', 'Some');
        } else {
            $this->GroupEvent->saveField('comment_release_status', 'None');
        }
        
        // if the oldest modified date is after the event's close date
        if (strtotime($event['Event']['release_date_end']) <= strtotime($eval['EvaluationSimple']['modified'])) {
            // set group event to reviewed
            $this->GroupEvent->id = $grpEventId;
            $this->GroupEvent->saveField('marked', 'reviewed');
        }
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
     * setGroupEventsReleaseStatus
     *
     * @param mixed $groupEventIds  group event ids
     * @param mixed $status         status
     *
     * @access public
     * @return void
     */
    function setGroupEventsReleaseStatus($groupEventIds, $status)
    {
        $this->setGroupEventsGradeReleaseStatus($groupEventIds, $status);
        $this->setGroupEventsCommentReleaseStatus($groupEventIds, $status);
    }
    
    /**
     * setGroupEventsGradeReleaseStatus
     *
     * @param mixed $groupEventIds  group event ids
     * @param mixed $status         status
     *
     * @access public
     * @return void
     */
    function setGroupEventsGradeReleaseStatus($groupEventIds, $status)
    {
        $this->GroupEvent = ClassRegistry::init('GroupEvent');

        $fields = array('GroupEvent.grade_release_status' => "'".$status."'");
        $conditions = array('GroupEvent.id' => $groupEventIds);
        $this->GroupEvent->updateAll($fields, $conditions);
    }
    
    /**
     * setGroupEventsCommentReleaseStatus
     *
     * @param mixed $groupEventIds  group event ids
     * @param mixed $status         status
     *
     * @access public
     * @return void
     */
    function setGroupEventsCommentReleaseStatus($groupEventIds, $status)
    {
        $this->GroupEvent = ClassRegistry::init('GroupEvent');

        $fields = array('GroupEvent.comment_release_status' => "'".$status."'");
        $conditions = array('GroupEvent.id' => $groupEventIds);
        $this->GroupEvent->updateAll($fields, $conditions);
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
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');
        $this->Event = ClassRegistry::init('Event');

        // assuming all are in the same order and same size
        $evaluatees = $params['form']['memberIDs'];
        $points = $params['form']['points'];
        $comments = $params['form']['comments'];
        $evaluator = $params['data']['Evaluation']['evaluator_id'];
        isset($params['form']['group_id']) ? $evaluators = $this->GroupsMembers->findAllByGroupId($params['form']['group_id']) : $evaluators = null;
        $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);

        // If value is not within range, then don't save.
        $pos = 0;
        $totalPoints = 0;
        foreach ($evaluatees as $value) {
            $totalPoints += $points[$pos];
            $pos ++;
        }
        $event = $this->Event->getEventById($params['form']['event_id']);
        $simpleEval = $this->SimpleEvaluation->getEvaluation($event['Event']['template_id']);
        $required = $simpleEval['SimpleEvaluation']['point_per_member'] * count($evaluatees);
        if ($totalPoints != $required) {
            return false;
        }

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
                $evalMarkRecord['EvaluationSimple']['release_status'] = $event['Event']['auto_release'];
                $evalMarkRecord['EvaluationSimple']['grade_release'] = $event['Event']['auto_release'];
            }
            $evalMarkRecord['EvaluationSimple']['score'] = $points[$pos];
            $totalPoints += $points[$pos];
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
        $memberCompletedNo = $this->EvaluationSubmission->find('count', array(
            'conditions' => array('grp_event_id' => $groupEvent['GroupEvent']['id'], 'submitter_id' => $evaluators)
        ));
        //Check to see if all members are completed this evaluation
        $evaluators = count($evaluators);
        if ($memberCompletedNo == $evaluators) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->GroupEvent->save($groupEvent)) {
                return false;
            }
        }
        return true;
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
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');

        $this->GroupEvent->id = $groupEventId;
        
        $now = "'".date("Y-m-d H:i:s")."'";
        $user = $this->Auth->user('id');

        if ($params['form']['submit']=='Save Changes') {
            //Reset all release status to false first
            $this->EvaluationSimple->setAllGroupCommentRelease($groupEventId, 0);
            $evaluatorIds = array_unique($evaluatorIds);
            foreach ($evaluatorIds as $evaluator) {
                if (isset($params['form']['release'.$evaluator])) {
                    $evaluateeIds = $params['form']['release'.$evaluator];
                    $fields = array('EvaluationSimple.release_status' => 1, 'EvaluationSimple.modified' => $now, 'EvaluationSimple.updater_id' => $user);
                    $conditions = array('EvaluationSimple.evaluator' => $evaluator, 'EvaluationSimple.evaluatee' => $evaluateeIds, 'grp_event_id' => $groupEventId);
                    $this->EvaluationSimple->updateAll($fields, $conditions);
                }
            }
        } else if ($params['form']['submit']=='Release All') {
            $fields = array('EvaluationSimple.release_status' => 1, 'EvaluationSimple.modified' => $now, 'EvaluationSimple.updater_id' => $user);
            $conditions = array('grp_event_id' => $groupEventId);
            $this->EvaluationSimple->updateAll($fields, $conditions);
        } else if ($params['form']['submit']=='Unrelease All') {
            $fields = array('EvaluationSimple.release_status' => 0, 'EvaluationSimple.modified' => $now, 'EvaluationSimple.updater_id' => $user);
            $conditions = array('grp_event_id' => $groupEventId);
            $this->EvaluationSimple->updateAll($fields, $conditions);
        }
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
        $ret['evaluators'] = $groupIds + $droppedUsers;
        // users currently in the group but who haven't made a submission
        $ret['Incomplete'] = array_diff($ret['evaluators'], $droppedUsers,
            $submitterIds);
        // users who are currently in the group
        $ret['GroupMembers'] = $groupIds;
        // users no longer in the group but still relevant
        $ret['Dropped'] = $droppedUsers;

        // grab user info from user ids
        foreach ($ret['evaluators'] as $userid) {
            $userinfo = $this->User->find('first',
                array(
                    'conditions' => array('User.id' => $userid),
                    'contain' => array('Role')
                )
            );
            $ret['evaluators'][$userid] = $userinfo;
        }
        $ret['evaluatees'] = Set::extract('/Role[id='.$this->User->USER_TYPE_TA.']/RolesUser/user_id', $ret['evaluators']);
        $ret['evaluatees'] = array_diff_key($ret['evaluators'], array_flip($ret['evaluatees']));

        // calculate penalty
        $ret['Penalties'] = $this->SimpleEvaluation->formatPenaltyArray(
            $eventId, $groupId, $ret['evaluatees']);
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
     * @param mixed $studentId
     *
     * @access public
     * @return void
     */
    function loadRubricEvaluationDetail($event, $studentId = null)
    {
        $this->EvaluationRubric = new EvaluationRubric;
        $this->GroupsMembers = new GroupsMembers;
        $this->EvaluationRubricDetail = new EvaluationRubricDetail;
        $this->Rubric = new Rubric;
        $this->User = ClassRegistry::init('User');

        $Session = new SessionComponent();
        $user = $Session->read('Auth.User');//User or Admin or
        $evaluator = empty($studentId) ? $user['id'] : $studentId;
        $result = array();
        //Get Members for this evaluation
        $groupMembers = $this->User->getEventGroupMembersNoTutors(
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
        $numMembers = count($groupMembers);
        //$this->set('evaluateeCount', $numMembers);
        $result['evaluateeCount'] = $numMembers;
        return $result;
    }


    /**
     * saveRubricEvaluation
     *
     * @param mixed $targetEvaluatee
     * @param mixed $viewMode
     * @param mixed $params
     * @param mixed $targetCriteria
     *
     * @access public
     * @return void
     */
    function saveRubricEvaluation($targetEvaluatee, $viewMode, $params=null, $targetCriteria=null)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Rubric = ClassRegistry::init('Rubric');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');

        // assuming all are in the same order and same size
        $evaluator = $params['data']['Evaluation']['evaluator_id'];
        $groupEventId = $params['form']['group_event_id'];
        $groupId = $params['form']['group_id'];
        $rubricId = $params['form']['rubric_id'];

        //Get the target event
        $eventId = $params['form']['event_id'];

        $this->Event->id = $eventId;
        $event = $this->Event->read();
        $auto_release = $event['Event']['auto_release'];

        //Get simple evaluation tool
        $this->Rubric->id = $event['Event']['template_id'];
        $rubric = $this->Rubric->read();

        // validate
        $valid_lom_nums = SET::extract($rubric['RubricsLom'], '/lom_num');
        if ($viewMode == 0) {
            for ($i=1; $i <= $rubric['Rubric']['criteria']; $i++) {
                $selectedLom = $params['form']['selected_lom_'.$targetEvaluatee.'_'.$i];
                if (!in_array($selectedLom, $valid_lom_nums)) {
                    return false;
                }
            }
        } elseif ($viewMode == 1) {
            $selectedLom = $params['form']['selected_lom_'.$targetEvaluatee.'_'.$targetCriteria];
            if (!in_array($selectedLom, $valid_lom_nums)) {
                return false;
            }
        } else {
            return false;
        }

        // Save evaluation data
        // total grade for evaluatee from evaluator
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
            $evalRubric['EvaluationRubric']['id'], $rubric, $targetEvaluatee, $params['form'], $viewMode, $auto_release, $targetCriteria);
        $evalRubric['EvaluationRubric']['score'] = $score;
        $evalRubric['EvaluationRubric']['comment_release'] = $auto_release;
        $evalRubric['EvaluationRubric']['grade_release'] = $auto_release;

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
     * @param mixed $viewMode        view mode
     * @param mixed $auto_release    auto release
     * @param mixed $targetCriteria  target criteria
     *
     * @access public
     * @return void
     */
    function saveNGetEvalutionRubricDetail ($evalRubricId, $rubric, $targetEvaluatee, $form, $viewMode, $auto_release, $targetCriteria=null)
    {
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
        $totalGrade = 0;
        $totalLom = count($rubric['RubricsLom']);

        if ($viewMode == 0) {
            $pos = 0;
            for ($i=1; $i <= $rubric['Rubric']['criteria']; $i++) {
                $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
                //TODO: LOM = 1
                if ($rubric['Rubric']['lom_max'] == 1) {
                    $form[$targetEvaluatee."selected$i"] = ($form[$targetEvaluatee."selected$i"] ? $form[$targetEvaluatee."selected$i"] : 0);
                }

                // get total possible grade for the criteria number ($i)
                foreach ($rubric['RubricsCriteria'] as $criteria) {
                    if ($criteria['criteria_num'] == $i) {
                        $multiplier = $criteria['multiplier'];
                        break;
                    }
                }
                $grade = isset($form['selected_lom_'.$targetEvaluatee.'_'.$i]) ?
                    ($form['selected_lom_'.$targetEvaluatee.'_'.$i] - $rubric['Rubric']['zero_mark']) *
                    ($multiplier/($totalLom - $rubric['Rubric']['zero_mark'])) : "";

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
                $evalRubricDetail['EvaluationRubricDetail']['comment_release'] = $auto_release;

                if($selectedLom != null){
                    $this->EvaluationRubricDetail->save($evalRubricDetail);
                }
                $this->EvaluationRubricDetail->id=null;

                $totalGrade += $grade;
            }
        }
        elseif ($viewMode == 1) {
            $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
            //TODO: LOM = 1
            if ($rubric['Rubric']['lom_max'] == 1) {
                $form[$targetEvaluatee."selected$targetCriteria"] = ($form[$targetEvaluatee."selected$targetCriteria"] ? $form[$targetEvaluatee."selected$targetCriteria"] : 0);
            }

            foreach ($rubric['RubricsCriteria'] as $criteria) {
                if ($criteria['criteria_num'] == $targetCriteria) {
                    $multiplier = $criteria['multiplier'];
                    break;
                }
            }
            $grade = isset($form['selected_lom_'.$targetEvaluatee.'_'.$targetCriteria]) ?
                ($form['selected_lom_'.$targetEvaluatee.'_'.$targetCriteria] - $rubric['Rubric']['zero_mark']) *
                ($multiplier/($totalLom - $rubric['Rubric']['zero_mark'])) : "";

            $selectedLom = $form['selected_lom_'.$targetEvaluatee.'_'.$targetCriteria];

            // Set up and save EvaluationRubricDetail
            $evalRubricDetail = $this->EvaluationRubricDetail->getByEvalRubricIdCritera($evalRubricId, $targetCriteria);
            if (isset($evalRubricDetail)) {
                $this->EvaluationRubricDetail->id=$evalRubricDetail['EvaluationRubricDetail']['id'] ;
            }
            $evalRubricDetail['EvaluationRubricDetail']['evaluation_rubric_id'] = $evalRubricId;
            $evalRubricDetail['EvaluationRubricDetail']['criteria_number'] = $targetCriteria;
            $evalRubricDetail['EvaluationRubricDetail']['criteria_comment'] = $form[$targetEvaluatee."comments"][$targetCriteria-1];
            $evalRubricDetail['EvaluationRubricDetail']['selected_lom'] = $selectedLom;
            $evalRubricDetail['EvaluationRubricDetail']['grade'] = $grade;
            $this->EvaluationRubricDetail->save($evalRubricDetail);
            $this->EvaluationRubricDetail->id=null;

            // Loop through all criteria to get total grade
            foreach ($rubric['RubricsCriteria'] as $rubricCriteria) {
                $criteriaNum = $rubricCriteria['criteria_num'];
                $grade = isset($form['selected_lom_'.$targetEvaluatee.'_'.$criteriaNum])? $form['selected_lom_'.$targetEvaluatee.'_'.$criteriaNum]/$totalLom : 0;
                $totalGrade += $grade;
            }
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
        if (empty($rubricResult)) {
            return false;
        }
        return $evalResult;
    }


    /**
     * changeIndivRubricEvalCommentRelease
     *
     * @param mixed $params params
     *
     * @access public
     * @return void
     */
    function changeIndivRubricEvalCommentRelease($params)
    {
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
        
        $grpEventId = $params['group_event_id'];
        $evaluatee = $params['evaluatee'];
        
        $now = '"'.date("Y-m-d H:i:s").'"';
        $user = $this->Auth->user('id');
        
        $evalRubrIds = $this->EvaluationRubric->find('list', array(
            'conditions' => array('evaluatee' => $evaluatee, 'grp_event_id' => $grpEventId)
        ));
        // change comment release to 0 for the evaluatee in this group event
        $this->EvaluationRubric->updateAll(
            array('EvaluationRubric.comment_release' => 0, 'EvaluationRubric.modified' => $now, 'EvaluationRubric.updater_id' => $user),
            array('EvaluationRubric.id' => $evalRubrIds)
        );
        $this->EvaluationRubricDetail->updateAll(
            array('EvaluationRubricDetail.comment_release' => 0, 'EvaluationRubricDetail.modified' => $now, 'EvaluationRubricDetail.updater_id' => $user),
            array('EvaluationRubricDetail.evaluation_rubric_id' => $evalRubrIds)
        );
        
        //release general comments
        $genComment = isset($params['releaseGeneralCom']) ? $params['releaseGeneralCom'] : array();
        $this->EvaluationRubric->updateAll(
            array('EvaluationRubric.comment_release' => 1, 'EvaluationRubric.modified' => $now, 'EvaluationRubric.updater_id' => $user),
            array('EvaluationRubric.evaluatee' => $evaluatee, 'EvaluationRubric.evaluator' => $genComment)
        );
        // release detailed comments
        $detailComment = isset($params['releaseComments']) ? $params['releaseComments'] : array();
        $this->EvaluationRubricDetail->updateAll(
            array('EvaluationRubricDetail.comment_release' => 1, 'EvaluationRubricDetail.modified' => $now, 'EvaluationRubricDetail.updater_id' => $user),
            array('EvaluationRubricDetail.id' => $detailComment)
        );
    }
    
    /**
     * changeRubricEvalCommentRelease
     *
     * @param mixed $status     status
     * @param mixed $grpEvents  group events
     * @param mixed $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function changeRubricEvalCommentRelease($status, $grpEvents, $evaluatee = null)
    {
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');

        $now = '"'.date("Y-m-d H:i:s").'"';
        $user = $this->Auth->user('id');
        
        $conditions = array('grp_event_id' => $grpEvents);
        if ($evaluatee) {
            $conditions['evaluatee'] = $evaluatee;
        }
        
        $evalIds = $this->EvaluationRubric->find('list', array('conditions' => $conditions));
        // update all comment release status that meets the conditions
        $this->EvaluationRubric->updateAll(
            array('EvaluationRubric.comment_release' => $status, 'EvaluationRubric.modified' => $now, 'EvaluationRubric.updater_id' => $user),
            array('EvaluationRubric.id' => $evalIds)
        );
        $this->EvaluationRubricDetail->updateAll(
            array('EvaluationRubricDetail.comment_release' => $status, 'EvaluationRubricDetail.modified' => $now, 'EvaluationRubricDetail.updater_id' => $user),
            array('EvaluationRubricDetail.evaluation_rubric_id' => $evalIds)
        );
    }
    
    /**
     * markRubricEvalReviewed
     *
     * @param mixed $eventId
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function markRubricEvalReviewed($eventId, $grpEventId)
    {
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');
        
        // set group event to reviewed if all evaluatees' release status has been modified
        $eval = $this->EvaluationRubric->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'order' => array('EvaluationRubric.modified ASC')
        ));
        $gen = $this->EvaluationRubric->find('list', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'fields' => 'EvaluationRubric.comment_release'
        ));
        $detail = $this->EvaluationRubricDetail->find('list', array(
            'conditions' => array('EvaluationRubricDetail.evaluation_rubric_id' => array_keys($gen)),
            'fields' => 'EvaluationRubricDetail.comment_release'
        ));
        $event = $this->Event->findById($eventId);

        $this->GroupEvent->id = $grpEventId;
        $all = array_product($gen) * array_product($detail);
        $some = array_sum($gen) + array_sum($detail);
        if ($all) {
            $this->GroupEvent->saveField('comment_release_status', 'All');
        } else if ($some) {
            $this->GroupEvent->saveField('comment_release_status', 'Some');
        } else {
            $this->GroupEvent->saveField('comment_release_status', 'None');
        } 
        // if the oldest modified date is after the event's close date
        if (strtotime($event['Event']['release_date_end']) <= strtotime($eval['EvaluationRubric']['modified'])) {
            // set group event to reviewed
            $this->GroupEvent->saveField('marked', 'reviewed');
        }
    }
    
    /**
     * loadMixEvaluationDetail
     * Mixeval Evaluation functions
     *
     * @param mixed $event
     * @param mixed $studentId
     *
     * @access public
     * @return void
     */
    function loadMixEvaluationDetail ($event, $studentId = null)
    {
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->User = ClassRegistry::init('User');

        $evaluator = empty($studentId) ? $this->Auth->user('id') : $studentId;

        //Get Members for this evaluation
        $groupMembers = $this->User->getEventGroupMembersNoTutors(
            $event['Group']['id'], $event['Event']['self_eval'], $evaluator);
        foreach($groupMembers as $key => $member) {
            $targetEvaluatee = $member['User']['id'];
            $evaluation = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                $event['GroupEvent']['id'], $evaluator, $targetEvaluatee);
            if (!empty($evaluation)) {
                $groupMembers[$key]['User']['Evaluation'] = $evaluation;
            }
        }
        return $groupMembers;
    }


    /**
     * saveMixevalEvaluation
     *
     * @param array $params mixeval array
     *
     * @access public
     * @return void
     */
    function saveMixevalEvaluation($params)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Mixeval = ClassRegistry::init('Mixeval');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');

        // assuming all are in the same order and same size
        $evaluator = $params['Evaluation']['evaluator_id'];
        $evaluatee = $params['Evaluation']['evaluatee_id'];
        $groupEventId = $params['Evaluation']['group_event_id'];

        //Get the target event
        $eventId = $params['Evaluation']['event_id'];
        $this->Event->id = $eventId;
        $event = $this->Event->read();
        $auto_release = $event['Event']['auto_release'];

        //Get simple evaluation tool
        $this->Mixeval->id = $event['Event']['template_id'];
        $mixeval = $this->Mixeval->read();

        // validate
        $data = $params['EvaluationMixeval'];
        $questions = $this->MixevalQuestion->findAllByMixevalId($event['Event']['template_id']);
        foreach($questions as $ques) {
            $num = $ques['MixevalQuestion']['question_num'];
            $multiplier = $ques['MixevalQuestion']['multiplier'];
            $zero_mark = $mixeval['Mixeval']['zero_mark'];

            if ($ques['MixevalQuestion']['mixeval_question_type_id'] == '1') {
                $scale = count($ques['MixevalQuestionDesc']);
                if (empty($data[$num]['selected_lom'])) {
                    if (!empty($params['EvaluationMixeval'][$num]['grade'])) {
                        // something is wrong if there is grade selected but no lom
                        return false;
                    }
                    continue;
                }
                $valid_loms = array();
                $valid_grades = array();
                foreach ($ques['MixevalQuestionDesc'] as $key => $desc) {
                    $lom = $desc['scale_level'];
                    if ($lom == 0) {
                        // upgraded from pre 3.1, scale_levels are set to 0. So use $key as level, scale_level starts from 1
                        $lom = $key + 1;
                    }
                    $valid_loms[]=$lom;
                    $valid_grades[]=$multiplier * (($lom - $zero_mark) / ($scale - $zero_mark));
                }
                if (!in_array($data[$num]['selected_lom'], $valid_loms)) {
                    return false;
                }
                // recalculate and override the grades from user
                $params['EvaluationMixeval'][$num]['grade'] = $valid_grades[$data[$num]['selected_lom'] - 1];
            }
        }

        $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
            $groupEventId, $evaluator, $evaluatee);
        if (empty($evalMixeval)) {
            //Save the master Evalution Mixeval record if empty
            $this->EvaluationMixeval->id = null;
            $evalMixeval['EvaluationMixeval']['evaluator'] = $evaluator;
            $evalMixeval['EvaluationMixeval']['evaluatee'] = $evaluatee;
            $evalMixeval['EvaluationMixeval']['grp_event_id'] = $groupEventId;
            $evalMixeval['EvaluationMixeval']['event_id'] = $eventId;
            $this->EvaluationMixeval->save($evalMixeval);
            $evalMixeval = $this->EvaluationMixeval->read();
        }

        $score = $this->saveNGetEvaluationMixevalDetail(
            $evalMixeval['EvaluationMixeval']['id'], $mixeval, $params, $auto_release);
        $evalMixeval['EvaluationMixeval']['score'] = $score;
        // save default grade release status 
        $evalMixeval['EvaluationMixeval']['grade_release'] = $auto_release;
        if (!$this->EvaluationMixeval->save($evalMixeval)) {
            return false;
        }

        return true;
    }


    /**
     * saveNGetEvaluationMixevalDetail
     *
     * @param mixed $evalMixevalId mixeval id
     * @param mixed $mixeval       mixeval
     * @param mixed $form          form
     * @param mixed $auto_release  auto_release
     *
     * @access public
     * @return void
     */
    function saveNGetEvaluationMixevalDetail($evalMixevalId, $mixeval, $form, $auto_release)
    {
        if ($evalMixevalId === null || $mixeval == null || $form == null || $auto_release === null) {
            return false;
        }
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $totalGrade = 0;
        $data = $form['EvaluationMixeval'];

        foreach($mixeval['MixevalQuestion'] as $ques) {
            $num = $ques['question_num'];
            $evalMixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria($evalMixevalId, $num);
            if(!empty($evalMixevalDetail)) {
                $this->EvaluationMixevalDetail->id = $evalMixevalDetail['EvaluationMixevalDetail']['id'];
            }
            $evalMixevalDetail['EvaluationMixevalDetail']['evaluation_mixeval_id'] = $evalMixevalId;
            $evalMixevalDetail['EvaluationMixevalDetail']['question_number'] = $num;
            $evalMixevalDetail['EvaluationMixevalDetail']['comment_release'] = $auto_release;

            if (in_array($ques['mixeval_question_type_id'], array('1','4'))) {
                if (empty($data[$num]['selected_lom']) && $ques['mixeval_question_type_id'] != '4' ) {
                    continue;
                }
                if ($ques['mixeval_question_type_id'] == '1') {
                    $evalMixevalDetail['EvaluationMixevalDetail']['selected_lom'] = $data[$num]['selected_lom'];
                    // Do not grab grade directly as users can edit the HTML to submit illegitimate answers
                    $grade = $data[$num]['selected_lom'] * ($ques['multiplier']/($ques['scale_level'] - $mixeval['Mixeval']['zero_mark']));
                } else {
                    // if the question type is 4 - simply grab the grade
                    $evalMixevalDetail['EvaluationMixevalDetail']['grade'] = $data[$num]['grade'];
                }
                $evalMixevalDetail['EvaluationMixevalDetail']['grade'] = $data[$num]['grade'];
                if ($ques['required'] && !$ques['self_eval']) {
                    $totalGrade += $evalMixevalDetail['EvaluationMixevalDetail']['grade'];
                }
                // empty the comment for type 1 and 4 questions
                $evalMixevalDetail['EvaluationMixevalDetail']['question_comment'] = NULL;
            } else {
                if (empty($data[$num]['question_comment']) && !empty($evalMixevalDetail)) {
                    $this->EvaluationMixevalDetail->delete($this->EvaluationMixevalDetail->id);
                    continue;
                } else if (empty($data[$num]['question_comment'])) {
                    continue;
                }
                $evalMixevalDetail['EvaluationMixevalDetail']['question_comment'] = $data[$num]['question_comment'];
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
     * @param mixed $include      members that have submitted
     * @param mixed $required_questions required questions for the mixed eval
     *
     * @access public
     * @return void
     */
    function getMixevalResultDetail($groupEventId, $groupMembers, $include, $required_questions)
    {
        $this->EvaluationMixeval  = ClassRegistry::init('EvaluationMixeval');
        $mixevalResultDetail = array();
        $evalResult = array();
        if ($groupEventId && $groupMembers) {
            foreach ($groupMembers as $user) {
                $userId = isset($user['User'])? $user['User']['id'] : $user['id'];
                // get the results for students
                $evalResult[$userId] = $this->EvaluationMixeval->getResultsByEvaluatee($groupEventId, $userId, $include);
            }
        }

        $mixevalResultDetail['scoreRecords'] =  $this->formatMixevalEvaluationResultsMatrix($evalResult, $required_questions);
        $mixevalResultDetail['evalResult'] = $evalResult;

        return $mixevalResultDetail;
    }

    /**
     * formatMixevalEvaluationResultsMatrix
     * results matrix format:
     * Matrix[evaluatee_id][question_index] = score
     *
     * @param mixed $evalResults evaluation result
     * @param mixed $required_questions required questions for the mixed eval
     *
     * @access public
     * @return array
     */
    function formatMixevalEvaluationResultsMatrix($evalResults, $required_questions)
    {
        $matrix = array();
        foreach ($evalResults as $userId => $evals) {
            $counter = array();
            $matrix[$userId] = array();
            foreach ($evals as $eval) {
                $questions_evaluated = Set::extract('/EvaluationMixevalDetail/question_number', $eval);
                // skip incomplete submissions
                $missing_required_question = false;
                foreach ($required_questions as $question_num) {                    
                    if (!in_array($question_num, $questions_evaluated)) {
                        $missing_required_question = true;
                    }
                }
                // skip incomplete evaluations for a user
                if ($missing_required_question) {
                    continue;
                }
                
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
                    $mixevalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria($evalMark['id']);
                    $evalResult[$userId][$userPOS++]['EvaluationMixeval']['details'] = $mixevalDetail;
                }
            }
        }
        return $evalResult;
    }

    
    /**
     * changeIndivMixedEvalCommentRelease
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    function changeIndivMixedEvalCommentRelease($params) 
    {
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        
        $grpEventId = $params['group_event_id'];
        $evaluatee = $params['evaluatee'];
        
        $now = '"'.date("Y-m-d H:i:s").'"';
        $user = $this->Auth->user('id');
        
        $evalIds = $this->EvaluationMixeval->find('list', array(
            'conditions' => array('evaluatee' => $evaluatee, 'grp_event_id' => $grpEventId)
        ));
        // unrelease all comments initially
        $this->EvaluationMixevalDetail->updateAll(
            array('EvaluationMixevalDetail.comment_release' => 0, 'EvaluationMixevalDetail.modified' => $now, 'EvaluationMixevalDetail.updater_id' => $user),
            array('EvaluationMixevalDetail.evaluation_mixeval_id' => $evalIds)
        );
        
        //release the selected comments
        $evalDetailIds = isset($params['releaseComments']) ? $params['releaseComments'] : array();
        $this->EvaluationMixevalDetail->updateAll(
            array('EvaluationMixevalDetail.comment_release' => 1, 'EvaluationMixevalDetail.modified' => $now, 'EvaluationMixevalDetail.updater_id' => $user),
            array('EvaluationMixevalDetail.id' => $evalDetailIds)
        );
    }
    
    /**
     * changeMixedEvalCommentRelease
     *
     * @param mixed $status     status
     * @param mixed $grpEvents  group events
     * @param mixed $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function changeMixedEvalCommentRelease($status, $grpEvents, $evaluatee = null)
    {
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');

        $now = '"'.date("Y-m-d H:i:s").'"';
        $user = $this->Auth->user('id');
        
        $conditions = array('grp_event_id' => $grpEvents);
        if ($evaluatee) {
            $conditions['evaluatee'] = $evaluatee;
        }
        
        $evalIds = $this->EvaluationMixeval->find('list', array('conditions' => $conditions));
        // update all comment release status that meets the conditions
        $this->EvaluationMixevalDetail->updateAll(
            array('EvaluationMixevalDetail.comment_release' => $status, 'EvaluationMixevalDetail.modified' => $now, 'EvaluationMixevalDetail.updater_id' => $user),
            array('EvaluationMixevalDetail.evaluation_mixeval_id' => $evalIds)
        );
    }

    /**
     * markMixedEvalReviewed
     *
     * @param mixed $eventId
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function markMixedEvalReviewed($eventId, $grpEventId)
    {
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        
        // set group event to reviewed if all evaluatees' release status has been modified
        $eval = $this->EvaluationMixevalDetail->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'order' => array('EvaluationMixevalDetail.modified ASC')
        ));
        $event = $this->Event->findById($eventId);
        $questions = $this->MixevalQuestion->find('list', array(
            'conditions' => array('mixeval_id' => $event['Event']['template_id'], 'mixeval_question_type_id' => array(2, 3)),
            'fields' => array('question_num')
        ));
        $evalIds = $this->EvaluationMixeval->find('list', array(
            'conditions' => array('grp_event_id' => $grpEventId)
        ));
        $details = $this->EvaluationMixevalDetail->find('all', array(
            'conditions' => array('evaluation_mixeval_id' => $evalIds, 'question_number' => $questions),
            'fields' => array('comment_release')
        ));
        $details = Set::extract('/EvaluationMixevalDetail/comment_release', $details);

        $this->GroupEvent->id = $grpEventId;
        $all = array_product($details);
        $some = array_sum($details);
        if ($all) {
            $this->GroupEvent->saveField('comment_release_status', 'All');
        } else if ($some) {
            $this->GroupEvent->saveField('comment_release_status', 'Some');
        } else {
            $this->GroupEvent->saveField('comment_release_status', 'None');
        } 
        
        // if the oldest modified date is after the event's close date
        if (strtotime($event['Event']['release_date_end']) <= strtotime($eval['EvaluationMixevalDetail']['modified'])) {
            // set group event to reviewed
            $this->GroupEvent->id = $grpEventId;
            $this->GroupEvent->saveField('marked', 'reviewed');
        }
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

        if(empty($survey)) {
            return false;
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
