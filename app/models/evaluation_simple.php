<?php
class EvaluationSimple extends AppModel
{
  var $name = 'EvaluationSimple';

  /*var $belongsTo = array(
    'SimpleEvaluation' => array(
    'className' => 'SimpleEvaluation',
    'foreignKey' => 'simple_evaluation_id'
    )
    );*/

  function getEvalMarkByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null){
    return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
  }

  // gets simple_evaluation_mark objects for a specific assignment and evaluator
  function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
    return $this->find('all','grp_event_id='.$grpEventId.' AND evaluator='.$evaluator);
  }

  // gets simple_evaluation_mark objects for a specific assignment and evaluator
  function getResultsByEvaluatee($grpEventId=null, $evaluatee=null) {
    return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee);
  }

  // get total mark each member recieved
  function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
    return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'SUM(score) AS received_total_score');
  }

  function setAllGroupCommentRelease($grpEventId=null, $releaseStatus=null, $evaluator=null, $evaluateeIds=null) {
    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE grp_event_id = '.$grpEventId;
    if ($evaluateeIds !=null) {
      $sql .= ' AND evaluator = '.$evaluator.' AND evaluatee IN ('.$evaluateeIds.')';
    }
    return $this->query($sql);
  }

  function setAllEventCommentRelease($eventId=null, $releaseStatus=null) {
    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE event_id = '.$eventId;
    return $this->query($sql);
  }


  function setAllEventGradeRelease($eventId=null, $releaseStatus=null) {
		$sql = 'UPDATE evaluation_simples SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
	  return $this->query($sql);
	}

	function getGroupResultsByGroupEventId($grpEventId=null)
	{
	   return $this->find('grp_event_id='.$grpEventId, 'SUM(score) AS received_total_score');
	}

	function getGroupResultsCountByGroupEventId($grpEventId=null)
	{
	   return $this->find('grp_event_id='.$grpEventId, 'COUNT(*) AS received_total_count');
	}

	function getAllComments ($grpEventId=null, $evaluatee=null) {
	  return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'eval_comment');
	}

	function getOppositeGradeReleaseStatus($groupEventId=null, $releaseStatus){
	  return $this->find(count,'grp_event_id='.$groupEventId.' AND grade_release != '.$releaseStatus);
	}

	function getOppositeCommentReleaseStatus($groupEventId=null, $releaseStatus){
	  return $this->find(count,'grp_event_id='.$groupEventId.' AND release_status != '.$releaseStatus);
	}

  function getTeamReleaseStatus($groupEventId=null){
    $ret = array();
    $status = $this->findAll('grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, release_status, grade_release', 'evaluatee');

    foreach($status as $s) {
      $ret[$s['EvaluationSimple']['evaluatee']] = $s['EvaluationSimple'];
    }
    return $ret;	
  }
}
