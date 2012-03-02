<?php
/**
 * EvaluationSimple
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationSimple extends AppModel
{
    public $name = 'EvaluationSimple';
    public $actsAs = array('Traceable');

  /*public $belongsTo = array(
    'SimpleEvaluation' => array(
    'className' => 'SimpleEvaluation',
    'foreignKey' => 'simple_evaluation_id'
    )
  );*/

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
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
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
        //return $this->find('all', 'grp_event_id='.$grpEventId.' AND evaluator='.$evaluator);
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator)
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
            $conditions['EvaluationSimple.evaluatee IN'] = $evaluateeIds;
        }
        return $this->updateAll($fields, $conditions);
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
    function setAllEventCommentRelease($eventId=null, $releaseStatus=null)
    {
        //    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE event_id = '.$eventId;
        //    return $this->query($sql);
        $fields = array('EvaluationSimple.release_status' => $releaseStatus);
        $conditions = array('EvaluationSimple.event_id' => $eventId);
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
        //		$sql = 'UPDATE evaluation_simples SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
        //	  return $this->query($sql);
        $fields = array('EvaluationSimple.grade_release' => $releaseStatus);
        $conditions = array('EvaluationSimple.event_id' => $eventId);
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
        //return $this->find('grp_event_id='.$grpEventId, 'SUM(score) AS received_total_score');
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
        //return $this->find('grp_event_id='.$grpEventId, 'COUNT(*) AS received_total_count');
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
    function getAllComments($grpEventId=null, $evaluateeId=null, $includeEvaluator=false)
    {
        //return $this->find('all', 'grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'eval_comment');
        $temp = $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluateeId),
            'fields' => array('evaluatee AS evaluateeId', 'eval_comment', 'event_id', 'User.first_name AS evaluator_first_name', 'User.last_name AS evaluator_last_name', 'User.student_no AS evaluator_student_no'),
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
        //return $this->find(count, 'grp_event_id='.$groupEventId.' AND grade_release != '.$releaseStatus);
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
        $ret = array();
        //$status = $this->findAll('grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, release_status, grade_release', 'evaluatee');
        $status = $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId),
            'fields' => array('evaluatee', 'release_status', 'grade_release'),
            'order' => 'evaluatee',
            'group' => 'evaluatee'
        ));

        foreach ($status as $s) {
            $ret[$s['EvaluationSimple']['evaluatee']] = $s['EvaluationSimple'];
        }
        return $ret;
    }
}
