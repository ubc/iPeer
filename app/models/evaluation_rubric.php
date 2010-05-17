<?php
class EvaluationRubric extends AppModel
{
  var $name = 'EvaluationRubric';

  var $hasMany = array(
  									  'EvaluationRubricDetail' =>
                        array(
                          'className' => 'EvaluationRubricDetail',
                          'conditions' => '',
                          'order' => '',
                          'dependent' => true,
                          'foreignKey' => 'evaluation_rubric_id'
                      )
  								);

	var $belongsTo = array(
									 			'Rubric' => array(
									 			  'className' => 'Rubric',
									 			  'foreignKey' => 'id'
									 			)
									 );

	function getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null){
		return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsByEvaluatee($grpEventId=null, $evaluatee=null) {
    return $this->findAll('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, null, 'evaluator ASC');
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
    return $this->findAll('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator, null, 'evaluatee ASC');
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null) {
	  $condition = 'EvaluationRubric.grp_event_id='.$grpEventId.' AND EvaluationRubric.evaluatee='.$evaluatee;
    $fields = 'EvaluationRubricDetail.*';
    $joinTable = array(' LEFT JOIN evaluation_rubric_details AS EvaluationRubricDetail ON EvaluationRubric.id=EvaluationRubricDetail.evaluation_rubric_id');

    return $this->findAll($condition, $fields, 'EvaluationRubric.id ASC, EvaluationRubricDetail.criteria_number ASC', null, null, null, $joinTable );

	}

	function getCriteriaResults($grpEventId=null,$evaluatee=null) {
	  return $this->findBySql('SELECT SUM(EvaluationRubricDetail.grade) AS score FROM evaluation_rubrics JOIN evaluation_rubric_details AS EvaluationRubricDetail ON EvaluationRubricDetail.evaluation_rubric_id = evaluation_rubrics.id WHERE evaluation_rubrics.grp_event_id='.$grpEventId.' AND evaluation_rubrics.evaluatee='.$evaluatee.' GROUP BY EvaluationRubricDetail.criteria_number');
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsDetailByEvaluator($grpEventId=null, $evaluator=null) {
	  $condition = 'EvaluationRubric.grp_event_id='.$grpEventId.' AND EvaluationRubric.evaluator='.$evaluator;
    $fields = 'EvaluationRubricDetail.*';
    $joinTable = array(' LEFT JOIN evaluation_rubric_details AS EvaluationRubricDetail ON EvaluationRubric.id=EvaluationRubricDetail.evaluation_rubric_id');

    return $this->findAll($condition, $fields, 'EvaluationRubric.id ASC, EvaluationRubricDetail.criteria_number ASC', null, null, null, $joinTable );

	}

	// get total mark each member recieved
	function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
    return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'SUM(score) AS received_total_score');
	}

	function getAllComments($grpEventId=null, $evaluatee=null) {
	  return $this->findAll('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee,'general_comment');
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
	  return $this->findAll('grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, comment_release, grade_release', 'evaluatee');
	}

	function setAllEventCommentRelease($eventId=null, $releaseStatus=null) {
		$sql = 'UPDATE evaluation_rubrics SET comment_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
	  return $this->query($sql);
	}

	function setAllEventGradeRelease($eventId=null, $releaseStatus=null) {
		$sql = 'UPDATE evaluation_rubrics SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
	  return $this->query($sql);
	}
}

?>