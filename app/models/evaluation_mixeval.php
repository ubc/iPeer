<?php
class EvaluationMixeval extends AppModel
{
    var $name = 'EvaluationMixeval';
    var $actsAs = array('Traceable');

    var $hasMany = array(
        'EvaluationMixevalDetail' =>
            array(
                'className' => 'EvaluationMixevalDetail',
                'conditions' => '',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'id'
                )
            );
             
    function getEvalMixevalByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null){
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
    	$eval = $this->find('first', array(
            'conditions' => array('grp_event_id' =>$grpEventId, 'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
        ));
        return $eval; 
    }
    
    function getReceivedAvgScore($grpEventId=null, $evaluatee=null) {
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('AVG(score) AS received_avg_score')
        ));
    }
    
    // gets Mixeval evaluation result for a specific assignment and evaluator
    function getResultsByEvaluatee($grpEventId=null, $evaluatee=null) {
        //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, null, 'evaluator ASC');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'order' => 'evaluator ASC'
        ));
    }

    // gets Mixeval evaluation result for a specific assignment and evaluator
    function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
        //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluator='.$evaluator, null, 'evaluatee ASC');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator),
            'order' => 'evaluatee ASC'
        ));
    }
    
    function getResultsDetailByEvaluateeByQuestion($grpEventId=null, $evaluatee=null, $includeEvaluators=false) {
      
    }    
    // gets Mixeval evaluation result for a specific assignment and evaluatee
    // $evaluator input is optional.
    function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null, $includeEvaluators=false) {
//        $condition = 'EvaluationMixeval.grp_event_id=' . $grpEventId .
//            ' AND EvaluationMixeval.evaluatee=' .$evaluatee;*
//        $fields = 'EvaluationMixevalDetail.*';
//        $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ' .
//            'ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');
//
//        return $this->find('all',$condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );
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
        if(!$includeEvaluators){
        	for($i=0 ; $i<count($temp); $i++)
        		unset($temp[$i]['User']);
        }
        return $temp;
    }

    // gets Mixeval evaluation result for a specific assignment and evaluator
    function getResultsDetailByEvaluator($grpEventId=null, $evaluator=null) {
//        $condition = 'EvaluationMixeval.grp_event_id='.$grpEventId.' AND EvaluationMixeval.evaluator='.$evaluator;
//        $fields = 'EvaluationMixevalDetail.*';
//        $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');
//
//        return $this->find('all',$condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );

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

    // get total mark each member recieved
    function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
//        return $this->find('grp_event_id=' . $grpEventId .
//             ' AND evaluatee=' . $evaluatee,
//             'AVG(score) AS received_total_score');
        return $this->find('first', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
            'fields' => array('AVG(score) AS received_total_score')
        ));
    }

    // get total evaluator each member recieved
    function getReceivedTotalEvaluatorCount($grpEventId=null, $evaluatee=null) {
        //return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'COUNT(*) AS ttl_count');
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee)
        ));
    }

    function getOppositeGradeReleaseStatus($groupEventId=null, $releaseStatus){
        //return $this->find(count,'grp_event_id='.$groupEventId.' AND grade_release != '.$releaseStatus);
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'grade_release !=' => $releaseStatus)
        ));
    }

    function getOppositeCommentReleaseStatus($groupEventId=null, $releaseStatus){
        //return $this->find(count,'grp_event_id='.$groupEventId.' AND comment_release != '.$releaseStatus);
        return $this->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'comment_release !=' => $releaseStatus)
        ));
    }

    function getTeamReleaseStatus($groupEventId=null){
        //return $this->find('all','grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, grade_release', 'evaluatee');
        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId),
            'group' => array('evaluatee')
        ));
    }

    function setAllEventCommentRelease($eventId=null, $releaseStatus=null) {
//        $sql = 'UPDATE evaluation_mixevals SET comment_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
//        return $this->query($sql);
      $fields = array('EvaluationMixeval.commnet_release' => $releaseStatus);
      $conditions = array('EvaluationMixeval.event_id' => $eventId);
      return $this->updateAll($fields, $conditions);
    }

    function setAllEventGradeRelease($eventId=null, $releaseStatus=null) {
//        $sql = 'UPDATE evaluation_mixevals SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
//        return $this->query($sql);
      $fields = array('EvaluationMixeval.grade_release' => $releaseStatus);
      $conditions = array('EvaluationMixeval.event_id' => $eventId);
      return $this->updateAll($fields, $conditions);
    }

    function getMixEvalById($id)
    {
        //return $this->find('id = '.$id);
        return $this->find('first', array(
            'conditions' => array('EvaluationMixeval.id' => $id)
        ));
    }
    
    function getResultDetailByQuestion($groupEventId, $evaluateeId, $evluatorId, $questionNum){
		$mixEval = $this->find('first', array('conditions' => array('evaluator' => $evluatorId,'evaluatee' => $evaluateeId,
															'grp_event_id' => $groupEventId)));
		$evalDetail = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera($mixEval['EvaluationMixeval']['id'], $questionNum);
		return $evalDetail;
    }
}
?>