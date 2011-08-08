<?php
class EvaluationSimple extends AppModel
{
  var $name = 'EvaluationSimple';
  var $actsAs = array('Traceable');

  /*var $belongsTo = array(
    'SimpleEvaluation' => array(
    'className' => 'SimpleEvaluation',
    'foreignKey' => 'simple_evaluation_id'
    )
    );*/

  function getEvalMarkByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null){
    //return $this->find('grp_event_id='.$grpEventId.' AND evaluator='.$evaluator.' AND evaluatee='.$evaluatee);
      return $this->find('first', array(
          'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator, 'evaluatee' => $evaluatee)
      ));
  }

  // gets simple_evaluation_mark objects for a specific assignment and evaluator
  function getResultsByEvaluator($grpEventId=null, $evaluator=null) {
    //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluator='.$evaluator);
      return $this->find('all', array(
          'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $evaluator)
      ));
  }

  // gets simple_evaluation_mark objects for a specific assignment and evaluator
  function getResultsByEvaluatee($grpEventId=null, $evaluatee=null, $includeEvaluator=false) {
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

  // get total mark each member recieved
  function getReceivedTotalScore($grpEventId=null, $evaluatee=null) {
    //return $this->find('grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'SUM(score) AS received_total_score');
      return $this->find('all', array(
          'conditions' => array('grp_event_id' => $grpEventId, 'evaluatee' => $evaluatee),
          'fields' => array('SUM(score) AS received_total_score')
      ));
  }

  function setAllGroupCommentRelease($grpEventId=null, $releaseStatus=null, $evaluator=null, $evaluateeIds=null) {
//    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE grp_event_id = '.$grpEventId;
//    if ($evaluateeIds !=null) {
//      $sql .= ' AND evaluator = '.$evaluator.' AND evaluatee IN ('.$evaluateeIds.')';
//    }
//    return $this->query($sql);
    $fields = array('EvaluationSimple.release_status' => $releaseStatus);
    $conditions = array('EvaluationSimple.grp_event_id' => $grpEventId);
    if ($evaluateeIds !=null){
      $conditions['EvaluationSimple.evaluator'] = $evaluator;
      $conditions['EvaluationSimple.evaluatee IN'] = $evaluateeIds;
    }
    return $this->updateAll($fields, $conditions);
  }

  function setAllEventCommentRelease($eventId=null, $releaseStatus=null) {
//    $sql = 'UPDATE evaluation_simples SET release_status = '.$releaseStatus.' WHERE event_id = '.$eventId;
//    return $this->query($sql);
    $fields = array('EvaluationSimple.release_status' => $releaseStatus);
    $conditions = array('EvaluationSimple.event_id' => $eventId);
    return $this->updateAll($fields, $conditions);
  }


  function setAllEventGradeRelease($eventId=null, $releaseStatus=null) {
//		$sql = 'UPDATE evaluation_simples SET grade_release = '.$releaseStatus.' WHERE event_id = '.$eventId;
//	  return $this->query($sql);
    $fields = array('EvaluationSimple.grade_release' => $releaseStatus);
    $conditions = array('EvaluationSimple.event_id' => $eventId);
    return $this->updateAll($fields, $conditions);
  }

  function getAllGroupResultsByGroupEventId($grpEventId=null){
	return $this->find('all', array(
                 'conditions' => array('grp_event_id' => $grpEventId),
				 'order' => array('EvaluationSimple.evaluatee ASC')
			));
  }
  
	function getGroupResultsByGroupEventId($grpEventId=null)
	{
	   //return $this->find('grp_event_id='.$grpEventId, 'SUM(score) AS received_total_score');
            return $this->find('all', array(
                  'conditions' => array('grp_event_id' => $grpEventId),
                  'fields' => array('SUM(score) AS received_total_score')
              ));
	}
	
	function getGroupResultsCountByGroupEventId($grpEventId=null)
	{
	   //return $this->find('grp_event_id='.$grpEventId, 'COUNT(*) AS received_total_count');
           return $this->find('count', array(
                  'conditions' => array('grp_event_id' => $grpEventId),
           		  'order' => 'EvaluationSimple.evaluatee ASC' 
              ));
	}

	function getAllComments($grpEventId=null, $evaluateeId=null, $includeEvaluator=false) {
	  //return $this->find('all','grp_event_id='.$grpEventId.' AND evaluatee='.$evaluatee, 'eval_comment');
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
	  if(!$includeEvaluator){
	  	for($i=0; $i<count($temp); $i++){
	  		unset($temp[$i]['User']);
	  	}
	  }
	  return $temp;
	}

	function getOppositeGradeReleaseStatus($grpEventId=null, $releaseStatus){
	  //return $this->find(count,'grp_event_id='.$groupEventId.' AND grade_release != '.$releaseStatus);
          return $this->find('count', array(
                  'conditions' => array('grp_event_id' => $grpEventId, 'grade_release !='=>$releaseStatus)
              ));
	}

	function getOppositeCommentReleaseStatus($groupEventId=null, $releaseStatus){
	  //return $this->find(count,'grp_event_id='.$groupEventId.' AND release_status != '.$releaseStatus);
            return $this->find('count', array(
                  'conditions' => array('grp_event_id' => $groupEventId, 'release_status !='=>$releaseStatus)
              ));
	}

  function getTeamReleaseStatus($groupEventId=null){
    $ret = array();
    //$status = $this->findAll('grp_event_id='.$groupEventId.' GROUP BY evaluatee', 'evaluatee, release_status, grade_release', 'evaluatee');
    $status = $this->find('all', array(
        'conditions' => array('grp_event_id' => $groupEventId),
        'fields' => array('evaluatee', 'release_status', 'grade_release'),
        'order' => 'evaluatee',
        'group' => 'evaluatee'
    ));

    foreach($status as $s) {
      $ret[$s['EvaluationSimple']['evaluatee']] = $s['EvaluationSimple'];
    }
    return $ret;	
  }
}
