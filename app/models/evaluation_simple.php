<?php
App::import('Model', 'EvaluationResponseBase');

/**
 * EvaluationSimple
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationSimple extends EvaluationResponseBase
{
    public $name = 'EvaluationSimple';
    public $useTable = null;

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
    );

    /**
     * getEvalMarkByGrpEventIdEvaluatorEvaluatee
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getEvalMarkByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null)
    {
        return $this->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
        ));
    }

    /**
     * getResultsByEvaluator
     * gets simple_evaluation_mark objects for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     *
     * @access public
     * @return void
     */
    function getResultsByEvaluator($grpEventId=null, $evaluator=null)
    {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator),
            'contain' => false
        ));
    }

    /**
     * getResultsByEvaluatee
     * gets simple_evaluation_mark objects for a specific assignment and evaluator
     *
     * @param bool $grpEventId       group event id
     * @param bool $evaluatee        evaluatee
     * @param bool $includeEvaluator include evaluator
     *
     * @access public
     * @return void
     */
    function getResultsByEvaluatee($grpEventId=null, $evaluatee=null, $includeEvaluator=false)
    {
        $includeEvaluator ? $user = 'User.*' : $user = '';
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = EvaluationSimple.evaluator'))),
            'fields' => array('EvaluationSimple.*', $user),
            'order' => array('EvaluationSimple.evaluator' => 'ASC')
        ));
    }

    /**
     * getReceivedTotalScore
     * get total mark each member recieved
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getReceivedTotalScore($grpEventId=null, $evaluatee=null)
    {
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'SUM(score) AS received_total_score');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('SUM(score) AS received_total_score')
        ));
    }


    /**
     * setAllGroupCommentRelease
     *
     * @param bool $grpEventId    group event id
     * @param bool $releaseStatus release status
     * @param bool $evaluator     evaluator
     * @param bool $evaluateeIds  evaluatee id
     *
     * @access public
     * @return void
     */
    function setAllGroupCommentRelease($grpEventId=null, $releaseStatus=null, $evaluator=null, $evaluateeIds=null)
    {
        //    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE grp_event_id = '.$grpEventId;
        //    if ($evaluateeIds !=null) {
        //      $sql .= ' AND evaluator = '.$evaluator.' AND evaluatee IN ('.$evaluateeIds.')';
        //    }
        //    return $this->query($sql);
        $fields = array('EvaluationSimple.release_status' => $releaseStatus);
        $conditions = array('EvaluationSimple.grp_event_id' => $grpEventId);
        if ($evaluateeIds !=null) {
            $conditions['EvaluationSimple.evaluator'] = $evaluator;
            $conditions['EvaluationSimple.evaluatee'] = $evaluateeIds;
        }
        return $this->updateAll($fields, $conditions);
    }


    /**
     * setAllEventCommentRelease
     *
     * @param int $eventId         event id
     * @param mixed $userId        user id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function setAllEventCommentRelease($eventId, $userId, $releaseStatus)
    {      
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        
        $now = '"'.date("Y-m-d H:i:s").'"';
        // only change release status if the group event is NOT marked as reviewed
        $grpEvents = $this->GroupEvent->find('list', array(
            'conditions' => array('event_id' => $eventId)
        ));
        $this->updateAll(
            array('EvaluationSimple.release_status' => $releaseStatus, 'EvaluationSimple.modified' => $now, 'EvaluationSimple.updater_id' => $userId),
            array('EvaluationSimple.grp_event_id' => $grpEvents)
        );
    }



    /**
     * setAllEventGradeRelease
     *
     * @param bool $eventId       event id
     * @param bool $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function setAllEventGradeRelease($eventId, $releaseStatus)
    {       
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        
        // only change release status if the group event is NOT marked as reviewed
        $grpEvents = $this->GroupEvent->find('list', array(
            'conditions' => array('event_id' => $eventId)
        ));
        
        $fields = array('EvaluationSimple.grade_release' => $releaseStatus);
        $conditions = array('EvaluationSimple.grp_event_id' => $grpEvents);
        return $this->updateAll($fields, $conditions);
    }


    /**
     * getAllGroupResultsByGroupEventId
     *
     * @param bool $grpEventId
     *
     * @access public
     * @return void
     */
    function getAllGroupResultsByGroupEventId($grpEventId=null)
    {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'order' => array('EvaluationSimple.evaluatee ASC')
        ));
    }


    /**
     * getGroupResultsByGroupEventId
     *
     * @param bool $grpEventId
     *
     * @access public
     * @return void
     */
    function getGroupResultsByGroupEventId($grpEventId=null)
    {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'fields' => array('SUM(score) AS received_total_score')
        ));
    }


    /**
     * getGroupResultsCountByGroupEventId
     *
     * @param bool $grpEventId
     *
     * @access public
     * @return void
     */
    function getGroupResultsCountByGroupEventId($grpEventId=null)
    {
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $grpEventId),
            'order' => 'EvaluationSimple.evaluatee ASC'
        ));
    }


    /**
     * getAllComments
     *
     * @param bool $grpEventId       group event id
     * @param bool $evaluateeId      evaluatee id
     * @param bool $includeEvaluator include evaluator
     *
     * @access public
     * @return void
     */
    function getAllComments($grpEventId, $evaluateeId, $includeEvaluator=false)
    {
        $temp = $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluateeId),
            'fields' => array('evaluatee AS evaluateeId', 'comment', 'event_id', 'User.first_name AS evaluator_first_name', 'User.last_name AS evaluator_last_name', 'User.student_no AS evaluator_student_no'),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = EvaluationSimple.evaluator')))
                ));
        if (!$includeEvaluator) {
            for ($i=0; $i<count($temp); $i++) {
                unset($temp[$i]['User']);
            }
        }
        return $temp;
    }

    /**
     * getComment
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function getComment($id)
    {
        $temp = $this->find('first', array(
            'conditions' => array('EvaluationSimple.id' => $id),
            'fields' => array('evaluatee AS evaluateeId', 'comment', 'event_id', 'User.first_name AS evaluator_first_name', 'User.last_name AS evaluator_last_name', 'User.student_no AS evaluator_student_no'),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = EvaluationSimple.evaluator')))
                ));
        return $temp;
    }


    /**
     * getOppositeGradeReleaseStatus
     *
     * @param bool  $grpEventId    group event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function getOppositeGradeReleaseStatus($grpEventId, $releaseStatus)
    {
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'grade_release !='=>$releaseStatus)
        ));
    }

    /**
     * getOppositeCommentReleaseStatus
     *
     * @param mixed $groupEventId  group event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function getOppositeCommentReleaseStatus($groupEventId, $releaseStatus)
    {
        //return $this->find(count, 'grp_event_id='.$groupEventId.' AND release_status != '.$releaseStatus);
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'release_status !='=>$releaseStatus)
        ));
    }

    /**
     * getTeamReleaseStatus
     *
     * @param bool $groupEventId
     *
     * @access public
     * @return void
     */
    function getTeamReleaseStatus($groupEventId=null)
    {
        $status = $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId),
            'fields' => array('evaluatee', 
                'MIN(release_status) as release_status', 'MIN(grade_release) as grade_release'),
            'order' => 'evaluatee',
            'group' => 'evaluatee'
        ));

        return Set::combine($status, '{n}.EvaluationSimple.evaluatee', '{n}.0');
    }

    /**
     * formatStudentViewOfSimpleEvaluationResult
     *
     * @param bool $event
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function formatStudentViewOfSimpleEvaluationResult($event=null, $userId=null)
    {
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->User = ClassRegistry::init('User');
        $this->Penalty = ClassRegistry::init('Penalty');
        $this->Event = ClassRegistry::init('Event');

        $gradeReleased = 0;
        $commentReleased = 0;
        $aveScore = 0;
        $groupAve = 0;
        $studentResult = array();
        $studentResult['numMembers'] = null;
        $studentResult['receivedNum'] = null;
        $studentResult['penalty'] = null;
        $studentResult['avePenalty'] = null;
        $studentResult['aveScore'] = null;
        $studentResult['groupAve'] = null;
        $subtractAvgScore = 0;
        $eventId = $event['Event']['id'];
        $results = $this->getResultsByEvaluatee($event['GroupEvent']['id'], $userId);
        $event_info = $this->Event->findById($eventId);
        if ($results != null) {
            //Get total mark each member received
            $receivedTotalScore = $this->getReceivedTotalScore(
                $event['GroupEvent']['id'], $userId);
            $totalScore = $receivedTotalScore[0][0]['received_total_score'];
            $numMemberSubmissions = $this->find('count', array(
                'conditions' => array(
                    'EvaluationSimple.evaluatee' => $userId,
                    'EvaluationSimple.event_id' => $event['Event']['id'],
                    'EvaluationSimple.grp_event_id' => $event['GroupEvent']['id'])));

            $gradeReleased = array_product(Set::extract($results, '/EvaluationSimple/grade_release')) ||
                $event_info['Event']['auto_release'];
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
                        'conditions' => array('EvaluationSubmission.submitter_id' => $userId)
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
            $subtractAvgScore = ((($scorePenalty['Penalty']['percent_penalty']) / 100) * $totalScore) / $numMemberSubmissions;

            $aveScore = $totalScore / $numMemberSubmissions;
            $studentResult['numMembers'] = $numMemberSubmissions;
            $studentResult['receivedNum'] = count($receivedTotalScore);
            $studentResult['penalty'] = $scorePenalty['Penalty']['percent_penalty'];

            $tmp_total = 0;
            $avg = $this->find('all', array(
                'conditions' => array('EvaluationSimple.grp_event_id' => $event['GroupEvent']['id']),
                'fields' => array('AVG(score) as avg', 'sum(score) as sum', 'evaluatee', 'grp_event_id'),
                'group' => 'evaluatee'
            ));

            if (isset($avg)) {
                $i = 0;
                // Deduct marks if the evaluator submitted a late evaluation.
                foreach ($avg as $a) {
                    $user_id = $a['EvaluationSimple']['evaluatee'];
                    $userPenalty = array();
                    $event_info = $this->Event->findById($eventId);
                    // storing the timestamp of the due date of the event
                    $event_due = strtotime($event_info['Event']['due_date']);
                    $event_end = strtotime($event_info['Event']['release_date_end']);
                    // assign penalty to groupMember if they submitted late or never submitted by release_date_end
                    $event_sub = $this->Event->find(
                        'first',
                        array(
                            'conditions' => array('Event.id' => $eventId),
                            'contain' => array('EvaluationSubmission' => array(
                            'conditions' => array('EvaluationSubmission.submitter_id' => $user_id)
                        )))
                    );
                    // no submission - if now is after release date end then - gets final deduction
                    if (empty($event_sub['EvaluationSubmission'])) {
                        if (time() > $event_end) {
                            $userPenalty = $this->Penalty->getPenaltyFinal($eventId);
                        }
                    // there is submission - may be on time or late
                    } else {
                        $late_diff = strtotime($event_sub['EvaluationSubmission'][0]['date_submitted']) - $event_due;
                        // late
                        if (0 < $late_diff) {
                            $days_late = $late_diff/(24*60*60);
                            $userPenalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                        }
                    }
                    $avgSubtract = 0;
                    if (!empty($userPenalty)) {
                        $avgSubtract = ($userPenalty['Penalty']['percent_penalty'] / 100) * ($avg[$i][0]['avg']);
                    }
                    $tmp_total += $a['0']['avg'] - $avgSubtract;
                    $i++;
                }
            }
            $groupAve = $tmp_total/count($avg);
            $studentResult['avePenalty'] = $subtractAvgScore;
            $studentResult['aveScore'] = $aveScore;
            $studentResult['groupAve'] = $groupAve;

            $releasedComments = array();
            //Get Comment Release: release_status may not be the same for all evaluators
            foreach ($results as $comment) {
                if ($comment['EvaluationSimple']['release_status'] || $event_info['Event']['auto_release']) {
                    $releasedComments[] = $this->getComment($comment['EvaluationSimple']['id']);
                }
            }
            if (!empty($releasedComments) && shuffle($releasedComments)) {
                $studentResult['comments'] = $releasedComments;
            }

        } else {
            $studentResult['penalty'] = null;
            $studentResult['avePenalty'] = null;
        }
        $commentReleased = !empty($releasedComments) || $event_info['Event']['auto_release'];
        $studentResult['gradeReleased'] = $gradeReleased;
        $studentResult['commentReleased'] = $commentReleased;
        return $studentResult;
    }

    /**
     * simpleEvalScore
     *
     * @param mixed $eventId
     * @param mixed $fields
     * @param mixed $conditions
     *
     * @access public
     * @return void
     */
    function simpleEvalScore($eventId, $fields, $conditions) {
        $evalSub = ClassRegistry::init('EvaluationSubmission');
        $pen = ClassRegistry::init('Penalty');
        //$simp = ClassRegistry::init('SimpleEvaluation');

        $data = array();
        
        $list = $this->find('all', array('fields' => $fields, 'conditions' => $conditions, 'contain' => false));
        foreach ($list as $mark) {
            if (!isset($data[$mark['EvaluationSimple']['evaluatee']])) {
                $data[$mark['EvaluationSimple']['evaluatee']]['user_id'] = $mark['EvaluationSimple']['evaluatee'];
                $data[$mark['EvaluationSimple']['evaluatee']]['score'] = $mark['EvaluationSimple']['score'];
                $data[$mark['EvaluationSimple']['evaluatee']]['numEval']= 1;
            } else {
                $data[$mark['EvaluationSimple']['evaluatee']]['score'] += $mark['EvaluationSimple']['score'];
                $data[$mark['EvaluationSimple']['evaluatee']]['numEval']++;
            }
        }
        //cleanup
        unset($list);

        $sub = $evalSub->getEvalSubmissionsByEventId($eventId);
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId)));
        $penalties = $pen->getPenaltyByEventId($eventId);
        //$template = $simp->find('first', array('conditions' => array('SimpleEvaluation.id' => $event['Event']['template_id'])));
        //$max = $template['SimpleEvaluation']['point_per_member'];

        foreach ($sub as $stu) {
            if (isset($data[$stu['EvaluationSubmission']['submitter_id']])) {
                $diff = strtotime($stu['EvaluationSubmission']['date_submitted']) - strtotime($event['Event']['due_date']);
                $days = $diff/(60*60*24);
                $penalty = $pen->getPenaltyByPenaltiesAndDaysLate($penalties, $days);
                $data[$stu['EvaluationSubmission']['submitter_id']]['penalty'] = (isset($penalty['Penalty']['percent_penalty'])) ? $penalty['Penalty']['percent_penalty'] :
                        0;
            }
        }
        $end = strtotime($event['Event']['release_date_end']);
        $now = time();
        if ($now >= $end) {
            $final_penalty = $pen->getPenaltyFinal($eventId);
            $noSubmissions = array_diff(Set::extract($data, '/user_id'), Set::extract($sub, '/EvaluationSubmission/submitter_id'));
            foreach ($noSubmissions as $userId) {
                $data[$userId]['penalty'] = $final_penalty['Penalty']['percent_penalty'];
            }
        }
        //cleanup
        unset($sub);

        foreach ($data as $demo) {
            if (!isset($demo['penalty'])) {
                $data[$demo['user_id']]['penalty'] = 0;
            }
        }

        $grades = array();
        foreach ($data as $student) {
            $tmp = array();
            $tmp['id'] = 0;
            $tmp['evaluatee'] = $student['user_id'];
            $tmp['score'] = $student['score']/$student['numEval']*(1-$student['penalty']/100);
            $grades[]['EvaluationSimple'] = $tmp;
        }

        /*foreach ($grades as $key => $student) {
            if ($student['EvaluationSimple']['score'] > $max) {
                $grades[$key]['EvaluationSimple']['score'] = $max;
            }
        }*/

        return $grades;
    }
}
