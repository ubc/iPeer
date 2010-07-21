<?php
class EvaluationMixeval extends AppModel
{
  var $name = 'EvaluationMixeval';

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
		return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
	}

	// gets Mixeval evaluation result for a specific assignment and evaluator
	function getResultsByEvaluatee($grpEventId=null, $evaluatee=null) {
    return $this->findAll('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, null, 'evaluator ASC');
	}

	// gets Mixeval evaluation result for a specific assignment and evaluator
	function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
    return $this->findAll('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator, null, 'evaluatee ASC');
	}

	// gets Mixeval evaluation result for a specific assignment and evaluator
	function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null) {
	  $condition = 'EvaluationMixeval.grp_event_id='.$grpEventId.' AND EvaluationMixeval.evaluatee='.$evaluatee;
    $fields = 'EvaluationMixevalDetail.*';
    $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');

    return $this->findAll($condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );
	}

  // gets Mixeval evaluation result for a specific assignment and evaluator
  function getResultsDetailByEvaluator($grpEventId=null, $evaluator=null) {
    $condition = 'EvaluationMixeval.grp_event_id='.$grpEventId.' AND EvaluationMixeval.evaluator='.$evaluator;
    $fields = 'EvaluationMixevalDetail.*';
    $joinTable = array(' LEFT JOIN evaluation_mixeval_details AS EvaluationMixevalDetail ON EvaluationMixeval.id=EvaluationMixevalDetail.evaluation_mixeval_id');

    return $this->findAll($condition, $fields, 'EvaluationMixeval.id ASC', null, null, null, $joinTable );
	}

	// get total mark each member recieved
	function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
    return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'SUM(score) AS received_total_score');
	}

	// get total evaluator each member recieved
	function getReceivedTotalEvaluatorCount($grpEventId=null, $evaluatee=null) {
    return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'COUNT(*) AS ttl_count');
	}

	function getOppositeGradeReleaseStatus($groupEventId=null, $releaseStatus){
	  return $this->findCount('grp_event_id='.$groupEventId.' AND grade_release != '.$releaseStatus);
	}

	function getOppositeCommentReleaseStatus($groupEventId=null, $releaseStatus){
	  return $this->findCount('grp_event_id='.$groupEventId.' AND comment_release != '.$releaseStatus);
	}

	function getTeamReleaseStatus($groupEventId=null){
	  return $this->findAll('grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, grade_release', 'evaluatee');
	}

	function setAllEventCommentRelease($eventId=null, $releaseStatus=null) {
		$sql = 'UPDATE evaluation_mixevals SET comment_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
	  return $this->query($sql);
	}

	function setAllEventGradeRelease($eventId=null, $releaseStatus=null) {
		$sql = 'UPDATE evaluation_mixevals SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
	  return $this->query($sql);
	}
	
	function getMixEvalById($id)
	{
		return $this->find('id = '.$id);
	}
}

?>
