<?php
/**
 * EvaluationMixeval
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationMixeval extends AppModel
{
    public $name = 'EvaluationMixeval';
    public $actsAs = array('Traceable');

    public $hasMany = array(
        'EvaluationMixevalDetail' =>
        array(
            'className' => 'EvaluationMixevalDetail',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'id'
        )
    );
    
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
    );

    /**
     * getEvalMixevalByGrpEventIdEvaluatorEvaluatee
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getEvalMixevalByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null)
    {
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
        $eval = $this->find('first', array(
            'conditions' => array('grp_event_id' =>$grpEventId, 'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
        ));
        return $eval;
    }


    /**
     * getReceivedAvgScore
     *
     * @param bool $grpEventId group event
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getReceivedAvgScore($grpEventId=null, $evaluatee=null)
    {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('AVG(score) AS received_avg_score')
        ));
    }


    /**
     * getResultsByEvaluatee
     * gets Mixeval evaluation result for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getResultsByEvaluatee($grpEventId=null, $evaluatee=null)
    {
        //return $this->find('all', 'grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, null, 'evaluator ASC');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'order' => 'evaluator ASC'
        ));
    }


    /**
     * getResultsByEvaluator
     * gets Mixeval evaluation result for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     *
     * @access public
     * @return void
     */
    function getResultsByEvaluator($grpEventId=null, $evaluator=null)
    {
        //return $this->find('all', 'grp_event_id='.$grpEventId.' AND evaluator='.$evaluator, null, 'evaluatee ASC');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator),
            'order' => 'evaluatee ASC'
        ));
    }

    /**
     * getResultsDetailByEvaluatee
     * gets Mixeval evaluation result for a specific assignment and evaluatee
     * $evaluator input is optional.
     *
     * @param bool $grpEventId        group event id
     * @param bool $evaluatee         evaluatee
     * @param bool $includeEvaluators include evaluators
     *
     * @access public
     * @return void
     */
    function getResultsDetailByEvaluatee($grpEventId, $evaluatee, $includeEvaluators=false)
    {
        //        $condition = 'EvaluationMixeval.grp_event_id=' . $grpEventId .
        //            ' AND EvaluationMixeval.evaluatee=' .$evaluatee;*
        //        $fields = 'EvaluationMixevalDetail.*';
        //        $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ' .
        //            'ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');
        //
        //        return $this->find('all', $condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );
        $temp = $this->find('all', array(
            'conditions' => array('EvaluationMixeval.grp_event_id' => $grpEventId, 'EvaluationMixeval.evaluatee' => $evaluatee),
            'fields' => array('evaluatee AS evaluateeId', 'event_id', 'EvaluationMixevalDetail.*', 'User.first_name AS evaluator_first_name', 'User.last_name AS evaluator_last_name', 'User.student_no AS evaluator_student_no'),
            'order' => array('EvaluationMixevalDetail.question_number' => 'ASC'),
            'joins' => array(
                array(
                    'table' => 'evaluation_mixeval_details',
                    'alias' => 'EvaluationMixevalDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EvaluationMixeval.id = EvaluationMixevalDetail.evaluation_mixeval_id')
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('EvaluationMixeval.evaluator = User.id')
                )
            )
        ));
        if (!$includeEvaluators) {
            for ($i=0; $i<count($temp); $i++) {
                unset($temp[$i]['User']);
            }
        }
        return $temp;
    }


    /**
     * getResultsDetailByEvaluator
     * gets Mixeval evaluation result for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     *
     * @access public
     * @return void
     */
    function getResultsDetailByEvaluator($grpEventId, $evaluator)
    {
        //        $condition = 'EvaluationMixeval.grp_event_id='.$grpEventId.' AND EvaluationMixeval.evaluator='.$evaluator;
        //        $fields = 'EvaluationMixevalDetail.*';
        //        $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');
        //
        //        return $this->find('all', $condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );

        return $this->find('all', array(
            'conditions' => array('EvaluationMixeval.grp_event_id' => $grpEventId, 'EvaluationMixeval.evaluator' => $evaluator),
            'fields' => array('EvaluationMixevalDetail.*'),
            'joins' => array(
                array(
                    'table' => 'evaluation_mixeval_details',
                    'alias' => 'EvaluationMixevalDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EvaluationMixeval.id = EvaluationMixevalDetail.evaluation_mixeval_id')
                )
            )
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
        //        return $this->find('grp_event_id=' . $grpEventId .
        //             ' AND evaluatee=' . $evaluatee,
        //             'AVG(score) AS received_total_score');
        return $this->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('AVG(score) AS received_total_score')
        ));
    }


    /**
     * getReceivedTotalEvaluatorCount
     * get total evaluator each member recieved
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getReceivedTotalEvaluatorCount($grpEventId, $evaluatee)
    {
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'COUNT(*) AS ttl_count');
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee)
        ));
    }


    /**
     * getOppositeGradeReleaseStatus
     *
     * @param int   $groupEventId  group event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function getOppositeGradeReleaseStatus($groupEventId, $releaseStatus)
    {
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'grade_release !=' => $releaseStatus)
        ));
    }


    /**
     * getOppositeCommentReleaseStatus
     *
     * @param int   $groupEventId  group event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function getOppositeCommentReleaseStatus($groupEventId, $releaseStatus)
    {
        //return $this->find(count, 'grp_event_id='.$groupEventId.' AND comment_release != '.$releaseStatus);
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'comment_release !=' => $releaseStatus)
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
        //return $this->find('all', 'grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, grade_release', 'evaluatee');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId),
            'group' => array('evaluatee')
        ));
    }


    /**
     * setAllEventCommentRelease
     *
     * @param int   $eventId       event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function setAllEventCommentRelease($eventId=null, $releaseStatus=null)
    {
        //        $sql = 'UPDATE evaluation_mixevals SET comment_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
        //        return $this->query($sql);
        $fields = array('EvaluationMixeval.comment_release' => $releaseStatus);
        $conditions = array('EvaluationMixeval.event_id' => $eventId);
        return $this->updateAll($fields, $conditions);
    }


    /**
     * setAllEventGradeRelease
     *
     * @param int   $eventId       event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function setAllEventGradeRelease($eventId=null, $releaseStatus=null)
    {
        //        $sql = 'UPDATE evaluation_mixevals SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
        //        return $this->query($sql);
        $fields = array('EvaluationMixeval.grade_release' => $releaseStatus);
        $conditions = array('EvaluationMixeval.event_id' => $eventId);
        return $this->updateAll($fields, $conditions);
    }


    /**
     * getMixEvalById
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function getMixEvalById($id)
    {
        //return $this->find('id = '.$id);
        return $this->find('first', array(
            'conditions' => array('EvaluationMixeval.id' => $id)
        ));
    }


    /**
     * getResultDetailByQuestion
     *
     * @param mixed $groupEventId group event id
     * @param mixed $evaluateeId  evaluatee id
     * @param mixed $evluatorId   evaluator id
     * @param mixed $questionNum  question number
     *
     * @access public
     * @return void
     */
    function getResultDetailByQuestion($groupEventId, $evaluateeId, $evluatorId, $questionNum)
    {
        $mixEval = $this->find('first', array('conditions' => array('evaluator' => $evluatorId, 'evaluatee' => $evaluateeId,
            'grp_event_id' => $groupEventId)));
        $evalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($mixEval['EvaluationMixeval']['id'], $questionNum);
        return $evalDetail;
    }
    
    /**
     * mixedEvalScore
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
     function mixedEvalScore($eventId, $fields, $conditions) {
        $evalSub = ClassRegistry::init('EvaluationSubmission');
        $pen = ClassRegistry::init('Penalty');
        
        $list = $this->find('all',
            array('fields' => $fields, 'conditions' => $conditions));   
        
        $data = array();
        foreach($list as $mark) {
            if (!isset($data[$mark['EvaluationMixeval']['evaluatee']])) {
                $data[$mark['EvaluationMixeval']['evaluatee']]['user_id'] = $mark['EvaluationMixeval']['evaluatee'];
                $data[$mark['EvaluationMixeval']['evaluatee']]['score'] = $mark['EvaluationMixeval']['score'];
                $data[$mark['EvaluationMixeval']['evaluatee']]['numEval']= 1;
            } else {
                $data[$mark['EvaluationMixeval']['evaluatee']]['score'] += $mark['EvaluationMixeval']['score'];
                $data[$mark['EvaluationMixeval']['evaluatee']]['numEval']++;
            }
        }
        
        $sub = $evalSub->find('all', array('conditions' => array('event_id' => $eventId)));
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId)));
        
        foreach($sub as $stu) {
            if (isset($data[$stu['EvaluationSubmission']['submitter_id']])) {
                $diff = strtotime($stu['EvaluationSubmission']['date_submitted']) - strtotime($event['Event']['due_date']);
                $days = $diff/(60*60*24);
                $penalty = $pen->getPenaltyByEventAndDaysLate($eventId,$days);
                $data[$stu['EvaluationSubmission']['submitter_id']]['penalty'] = (isset($penalty['Penalty']['percent_penalty'])) ? $penalty['Penalty']['percent_penalty'] :
                        0;
            }
        }
        
        foreach($data as $demo) {
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
            $grades[]['EvaluationMixeval'] = $tmp;
        }
        
        return $grades;
     }
}
