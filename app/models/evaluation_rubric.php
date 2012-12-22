<?php
App::import('Model', 'EvaluationResponseBase');

/**
 * EvaluationRubric
 *
 * @uses EvaluationResponseBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationRubric extends EvaluationResponseBase
{
    public $name = 'EvaluationRubric';
    public $useTable = null;

    public $hasMany = array(
        'EvaluationRubricDetail' =>
        array(
            'className' => 'EvaluationRubricDetail',
            'conditions' => '',
            'order' => '',
            'dependent' => true
        )
    );

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
    );


    /**
     * getEvalRubricByGrpEventIdEvaluatorEvaluatee
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null)
    {
        return $this->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId,
            'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
        ));
    }


    /**
     * getResultsByEvaluatee
     * gets rubric evaluation result for a specific assignment and evaluator
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
                    'conditions' => array('User.id = EvaluationRubric.evaluator'))),
            'fields' => array('EvaluationRubric.*', $user),
            'order' => array('EvaluationRubric.evaluator' => 'ASC')
        ));

    }


    /**
     * getResultsByEvaluator
     * gets rubric evaluation result for a specific assignment and evaluator
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
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator)
        ));
    }

    /**
     * getResultsDetailByEvaluatee
     * gets rubric evaluation result for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null)
    {
        return $this->find('all', array(
            'conditions' => array('EvaluationRubric.grp_event_id' => $grpEventId, 'EvaluationRubric.evaluatee' => $evaluatee),
            'fields' => array('EvaluationRubricDetail.*'),
            'joins' => array(
                array(
                    'table' => 'evaluation_rubric_details',
                    'alias' => 'EvaluationRubricDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EvaluationRubric.id = EvaluationRubricDetail.evaluation_rubric_id')
                )
            ),
            'order' => 'EvaluationRubricDetail.criteria_number ASC'
        ));
    }


    /**
     * getCriteriaResults
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     *
     * @access public
     * @return void
     */
    function getCriteriaResults($grpEventId=null,$evaluatee=null)
    {
        $this->recursive = 0;
        $data = $this->find('all', array(
            'conditions' => array('EvaluationRubric.grp_event_id' => $grpEventId, 'EvaluationRubric.evaluatee' => $evaluatee),
            'fields' => array('SUM(EvaluationRubricDetail.grade) AS sumScore',
            'COUNT(EvaluationRubricDetail.grade) As count',
            'EvaluationRubricDetail.criteria_number as criteria'),
            'joins' => array(array(
                'table' => 'evaluation_rubric_details',
                'alias' => 'EvaluationRubricDetail',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions'=> array('EvaluationRubricDetail.evaluation_rubric_id = EvaluationRubric.id')
            )),

            'group' => 'EvaluationRubricDetail.criteria_number'
        ));

        $results = array();
        for ($i = 0; $i < sizeof($data); $i++) {
            //Calculate grade average
            $results[$data[$i]['EvaluationRubricDetail']['criteria']] =
                $data[$i][0]['sumScore'] / $data[$i][0]['count'];
        }
        return $results;
    }


    /**
     * getResultsDetailByEvaluator
     * gets rubric evaluation result for a specific assignment and evaluator
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluator  evaluator
     *
     * @access public
     * @return void
     */
    function getResultsDetailByEvaluator($grpEventId=null, $evaluator=null)
    {
        return $this->find('all', array(
            'conditions' => array('EvaluationRubric.grp_event_id' => $grpEventId, 'EvaluationRubric.evaluator' => $evaluator),
            'fields' => array('EvaluationRubricDetail.*'),
            'joins' => array(
                array(
                    'table' => 'evaluation_rubric_details',
                    'alias' => 'EvaluationRubricDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EvaluationRubric.id = EvaluationRubricDetail.evaluation_rubric_id')
                )
            ),
            'order' => 'EvaluationRubricDetail.criteria_number ASC'
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
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('SUM(score) AS received_total_score')
        ));
    }


    /**
     * getAllComments
     *
     * @param bool $grpEventId group event id
     * @param bool $evaluatee  evaluatee
     * @param bool $evaluators evaluators
     *
     * @access public
     * @return void
     */
    function getAllComments($grpEventId=null, $evaluatee=null, $evaluators=false)
    {
        $temp = $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('general_comment', 'event_id', 'evaluatee AS evaluateeId', 'User.first_name AS evaluator_first_name', 'User.last_name AS evaluator_last_name', 'User.student_no AS evaluator_student_no'),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = EvaluationRubric.evaluator')))
                ));
        if (!$evaluators) {
            for ($i=0; $i<count($temp); $i++) {
                unset($temp[$i]['User']);
            }
        }
        return $temp;
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
    function getReceivedTotalEvaluatorCount($grpEventId=null, $evaluatee=null)
    {
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee)
        ));
    }


    /**
     * getOppositeGradeReleaseStatus
     *
     * @param bool  $groupEventId  group event id
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
     * @param bool  $groupEventId  group event id
     * @param mixed $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function getOppositeCommentReleaseStatus($groupEventId, $releaseStatus)
    {
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'comment_release !=' => $releaseStatus)
        ));
    }


    /**
     * getTeamReleaseStatus
     *
     * @param mixed $groupEventId
     *
     * @access public
     * @return void
     */
    function getTeamReleaseStatus($groupEventId)
    {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId),
            'group' => array('evaluatee'),
            'fields' => array('evaluatee', 'comment_release', 'grade_release'),
            'order' => array('evaluatee')
        ));
    }


    /**
     * setAllEventCommentRelease
     *
     * @param bool $eventId       event id
     * @param bool $releaseStatus release status
     *
     * @access public
     * @return void
     */
    function setAllEventCommentRelease($eventId, $releaseStatus)
    {
        $fields = array('EvaluationRubric.comment_release' => $releaseStatus);
        $conditions = array('EvaluationRubric.event_id' => $eventId);
        return $this->updateAll($fields, $conditions);
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
    function setAllEventGradeRelease($eventId=null, $releaseStatus=null)
    {
        $fields = array('EvaluationRubric.grade_release' => $releaseStatus);
        $conditions = array('EvaluationRubric.event_id' => $eventId);
        return $this->updateAll($fields, $conditions);
    }


    /**
     * getRubricsCriteriaResult
     *
     * @param mixed $grpEventId group event id
     * @param mixed $evaluatee  evaluatee
     * @param mixed $evaluator  evaluator
     *
     * @access public
     * @return void
     */
    function getRubricsCriteriaResult($grpEventId, $evaluatee, $evaluator)
    {
        $evalRubric = $this->find('first', array(
            'conditions' => array('evaluator' => $evaluator, 'evaluatee' => $evaluatee,
            'grp_event_id' => $grpEventId)));
        $result = $this->EvaluationRubricDetail->getAllByEvalRubricId($evalRubric['EvaluationRubric']['id']);
        return $result;
    }
    /**
     * rubricEvalScore
     *
     * @param mixed $eventId
     * @param mixed $fields
     * @param mixed $conditions
     *
     * @access public
     * @return void
     */
    function rubricEvalScore($eventId, $fields, $conditions) {
        $evalSub = ClassRegistry::init('EvaluationSubmission');
        $pen = ClassRegistry::init('Penalty');

        $list = $this->find('all',
            array('fields' => $fields, 'conditions' => $conditions));

        $data = array();
        foreach ($list as $mark) {
            if (!isset($data[$mark['EvaluationRubric']['evaluatee']])) {
                $data[$mark['EvaluationRubric']['evaluatee']]['user_id'] = $mark['EvaluationRubric']['evaluatee'];
                $data[$mark['EvaluationRubric']['evaluatee']]['score'] = $mark['EvaluationRubric']['score'];
                $data[$mark['EvaluationRubric']['evaluatee']]['numEval']= 1;
            } else {
                $data[$mark['EvaluationRubric']['evaluatee']]['score'] += $mark['EvaluationRubric']['score'];
                $data[$mark['EvaluationRubric']['evaluatee']]['numEval']++;
            }
        }

        $sub = $evalSub->getEvalSubmissionsByEventId($eventId);
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId)));

        foreach ($sub as $stu) {
            if (isset($data[$stu['EvaluationSubmission']['submitter_id']])) {
                $diff = strtotime($stu['EvaluationSubmission']['date_submitted']) - strtotime($event['Event']['due_date']);
                $days = $diff/(60*60*24);
                $penalty = $pen->getPenaltyByEventAndDaysLate($eventId, $days);
                $data[$stu['EvaluationSubmission']['submitter_id']]['penalty'] = (isset($penalty['Penalty']['percent_penalty'])) ? $penalty['Penalty']['percent_penalty'] :
                        0;
            }
        }

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
            $grades[]['EvaluationRubric'] = $tmp;
        }

        return $grades;
    }
}
