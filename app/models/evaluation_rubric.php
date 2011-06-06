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
                          'dependent' => true
                      )
  								);

/*	var $belongsTo = array(
									 			'Rubric' => array(
									 			  'className' => 'Rubric',
									 			)
									 );*/

	function getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null){

            //return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
            return $this->find('first', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
            ));

	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsByEvaluatee($grpEventId=null, $evaluatee=null) {
            //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, null, 'evaluator ASC');
            return $this->find('all', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
                'order' => 'evaluator ASC'
            ));
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
            //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluator='.$evaluator, null, 'evaluatee ASC');
            return $this->find('all', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator),
                'order' => 'evaluatee ASC'
            ));
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null) {
//        $condition = 'EvaluationRubric.grp_event_id='.$grpEventId.' AND EvaluationRubric.evaluatee='.$evaluatee;
//        $fields = 'EvaluationRubricDetail.*';
//        $joinTable = array(' LEFT JOIN evaluation_rubric_details AS EvaluationRubricDetail ON EvaluationRubric.id=EvaluationRubricDetail.evaluation_rubric_id');
//
//        return $this->find('all',$condition, $fields, 'EvaluationRubric.id ASC, EvaluationRubricDetail.criteria_number ASC', null, null, null, $joinTable );
//

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

	function getCriteriaResults($grpEventId=null,$evaluatee=null) {
        $data = $this->query('SELECT ' .
                'SUM(EvaluationRubricDetail.grade) AS sumScore, ' .
                'COUNT(EvaluationRubricDetail.grade) As count, ' .
                'EvaluationRubricDetail.criteria_number as criteria FROM evaluation_rubrics ' .
                'JOIN evaluation_rubric_details AS EvaluationRubricDetail ' .
                'ON EvaluationRubricDetail.evaluation_rubric_id = evaluation_rubrics.id ' .
                'WHERE evaluation_rubrics.grp_event_id=' . $grpEventId .
                ' AND evaluation_rubrics.evaluatee=' . $evaluatee .
                ' GROUP BY EvaluationRubricDetail.criteria_number');

        $results = array();
        for ($i = 0; $i < sizeof($data); $i++) {
            //Calculate grade average
            $results[$data[$i]['EvaluationRubricDetail']['criteria']] =
                $data[$i][0]['sumScore'] / $data[$i][0]['count'];
        }

        return $results;
	}

	// gets rubric evaluation result for a specific assignment and evaluator
	function getResultsDetailByEvaluator($grpEventId=null, $evaluator=null) {
//        $condition = 'EvaluationRubric.grp_event_id='.$grpEventId.' AND EvaluationRubric.evaluator='.$evaluator;
//        $fields = 'EvaluationRubricDetail.*';
//        $joinTable = array(' LEFT JOIN evaluation_rubric_details AS EvaluationRubricDetail ON EvaluationRubric.id=EvaluationRubricDetail.evaluation_rubric_id');
//
//        return $this->find('all',$condition, $fields, 'EvaluationRubric.id ASC, EvaluationRubricDetail.criteria_number ASC', null, null, null, $joinTable );
//
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

	// get total mark each member recieved
	function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
            //return $this->find('grp_event_id=' . $grpEventId . ' AND evaluatee=' . $evaluatee, 'SUM(score) AS received_total_score');
            return $this->find('all', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
                'fields' => array('SUM(score) AS received_total_score')
            ));
	}

	function getAllComments($grpEventId=null, $evaluatee=null) {
        //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee,'general_comment');
            return $this->find('all', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
                'fields' => array('general_comment')
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
                'conditions' => array('grp_event_id' => $grpEventId, 'grade_release !=' => $releaseStatus)
            ));
	}

	function getOppositeCommentReleaseStatus($groupEventId=null, $releaseStatus){
        //return $this->find(count,'grp_event_id='.$groupEventId.' AND comment_release != '.$releaseStatus);
            return $this->find('count', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'comment_release !=' => $releaseStatus)
            ));
	}

	function getTeamReleaseStatus($groupEventId=null){
        //return $this->find('all','grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, comment_release, grade_release', 'evaluatee');
            return $this->find('all', array(
                'conditions' => array('grp_event_id' => $groupEventId),
                'group' => array('evaluatee'),
                'fields' => array('evaluatee', 'comment_release', 'grade_release'),
                'order' => array('evaluatee')
            ));
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