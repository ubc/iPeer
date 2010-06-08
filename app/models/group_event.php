<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/11/16 21:51:10 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Group
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class GroupEvent extends AppModel
{
  var $name = 'GroupEvent';

  var $hasMany = array(
                        'EvaluationSubmission' => array(
                          'className' => 'EvaluationSubmission',
                          'conditions' => 'EvaluationSubmission.record_status = "A"',
                          'order' => 'EvaluationSubmission.id ASC',
                          'limit' => '999',
                          'foreignKey' => 'evaluation_submission_id',
                          'dependent' => true,
                          'exclusive' => false,
                          'finderSql' => ''
                        )
                 );

  // inserts all members into the groups_events table
  function insertGroups($id=null, $data=null){
  	for( $i=1; $i<=$data['group_count']; $i++ ){
  	  if (!empty($id) && !empty($data['group'.$i]))
  	  {
    	  $tmp = array( 'event_id'=>$id, 'group_id'=>$data['group'.$i], 'marked'=>'not reviewed' );
    	  $this->save($tmp);
    	  //reset the id field
    	  $this->id = null;
    	}
    }
  }

  // updates all the group events
  function updateGroups($id=null, $data=null){
//get old groupid's
	$tmp = $this->getGroupIDsByEventId($id);
	$oldGroups = array();
	$newGroups = array();
	$insertGroups = array();
	$deleteGroups = array();

	for ($i = 1; $i <= $data['group_count']; $i++) array_push($newGroups, $data['group'.$i]);
	for ($i = 0; $i < count($tmp); $i++) array_push($oldGroups, $tmp[$i]['GroupEvent']['group_id']);

//compare
	$insertGroups = array_diff($newGroups, $oldGroups);
	$deleteGroups = array_diff($oldGroups, $newGroups);

//insert/delete
	foreach ($insertGroups as $groupId) {
		$tmp = array('event_id'=>$id, 'group_id'=>$groupId, 'marked'=>'not reviewed');
		$this->save($tmp);
		$this->id = null;
	}
	foreach ($deleteGroups as $groupId) {
		$tmp = $this->find($conditions = array('event_id'=>$id,'group_id'=>$groupId), $fields = 'id');
		$this->del($tmp['GroupEvent']['id']);
		$this->id = null;
	}
  }

  // returns list of group id within the selected Event
  function getGroupIDsByEventId($eventId=null){
  	  if (empty($eventId) || is_null($eventId)) {
  	  	return;
  	  }
	  $tmp = $this->findAll($conditions = 'event_id='.$eventId, 'id, group_id, event_id, marked, grade');
	  return $tmp;
  }

  // returns list of group id within the selected Event
  function getGroupListByEventId($eventId=null){
  	  if (empty($eventId) || is_null($eventId)) {
  	  	return;
  	  }
	  $tmp = $this->findAll($conditions = 'event_id='.$eventId);
	  return $tmp;
  }

  // returns list of Group Event
  function getGroupEventByEventIdGroupId($eventId=null, $groupId=null){
    if (empty($groupId)||is_null($groupId))
      return;
	  $tmp = $this->find($conditions = 'event_id='.$eventId.' AND group_id='.$groupId);
	  return $tmp;
  }

  function getGroupEventByUserId($userId=null, $eventId=null)
  {
    $condition = 'GroupMember.user_id='.$userId.' AND GroupEvent.event_id='.$eventId;
    $fields = 'GroupMember.user_id, GroupEvent.group_id, GroupEvent.id, GroupEvent.event_id';
    $joinTable = array(' RIGHT JOIN groups_members as GroupMember ON GroupMember.group_id=GroupEvent.group_id');

    return $this->findAll($condition, $fields, null, null, null, null, $joinTable );
  }

  function getMemberCountByEventId($eventId=null)
  {
    $condition = 'GroupEvent.event_id='.$eventId;
    $fields = 'Count(GroupMember.user_id) AS count';
    $joinTable = array(' RIGHT JOIN groups_members as GroupMember ON GroupMember.group_id=GroupEvent.group_id');

    return $this->findAll($condition, $fields, null, null, null, null, $joinTable );
  }

  // returns list of group id within the selected Event
  function getToReviewGroupEventByEventId($eventId=null){
	  $tmp = $this->findCount($conditions = 'event_id='.$eventId.' AND marked="to review"');
	  return $tmp;
  }

  function getLowMark($eventId=null, $eventTypeId=null, $maxPercent=1, $minPercent=0) {
    $eventTypeEval = array(1=>'evaluation_simples',2=>'evaluation_rubrics',4=>'evaluation_mixevals');
    $eventType = array(1=>'simple_evaluations',2=>'rubrics',4=>'mixevals');
    switch ($eventTypeId) {
      case 1: //simple eval
        return $this->findBySql('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked FROM group_events AS GroupEvent WHERE GroupEvent.event_id='.$eventId.' AND GroupEvent.id IN (SELECT DISTINCT grp_event_id FROM '.$eventTypeEval[$eventTypeId].' WHERE event_id='.$eventId.' GROUP BY grp_event_id, evaluatee HAVING (SUM(score)/COUNT(evaluatee)) < '.$maxPercent.'*(SELECT point_per_member FROM '.$eventType[$eventTypeId].' WHERE id IN (SELECT template_id AS id FROM events WHERE id=GroupEvent.event_id)) AND (SUM(score)/COUNT(evaluatee)) > '.$minPercent.'*COUNT(evaluatee)*(SELECT point_per_member FROM '.$eventType[$eventTypeId].' WHERE id IN (SELECT template_id AS id FROM events WHERE id=GroupEvent.event_id))) ORDER BY GroupEvent.group_id');
        break;
      case 2||4:
        //echo $eventId.' '.$eventTypeId.' '.$maxPercent;
        return $this->findBySql('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked FROM group_events AS GroupEvent WHERE GroupEvent.event_id='.$eventId.' AND GroupEvent.id IN (SELECT DISTINCT grp_event_id FROM '.$eventTypeEval[$eventTypeId].' WHERE event_id='.$eventId.' GROUP BY grp_event_id, evaluatee HAVING (SUM(score)/COUNT(evaluatee)) < '.$maxPercent.'*(SELECT total_marks FROM '.$eventType[$eventTypeId].' WHERE id IN (SELECT template_id AS id FROM events WHERE id=GroupEvent.event_id)) AND (SUM(score)/COUNT(evaluatee)) > '.$minPercent.'*(SELECT total_marks FROM '.$eventType[$eventTypeId].' WHERE id IN (SELECT template_id AS id FROM events WHERE id=GroupEvent.event_id))) ORDER BY GroupEvent.group_id');
        break;
      default:
        return null;
    }
  }

  function getNotReviewed($eventId=null) {
    return $this->findBySql('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked FROM group_events AS GroupEvent WHERE (GroupEvent.marked="not reviewed" OR GroupEvent.marked="to review") AND GroupEvent.event_id='.$eventId.' ORDER BY GroupEvent.group_id');
  }

  function getLate($eventId) {
    return $this->findBySql('SELECT DISTINCT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked 
                            FROM group_events as GroupEvent,
                              (SELECT id
                              FROM
                                (SELECT group_events.id, count( * ) as count1
                                FROM group_events
                                LEFT JOIN groups_members ON group_events.group_id = groups_members.group_id
                                WHERE group_events.event_id = '.$eventId.'
                                GROUP BY group_events.group_id) AS tb1
                                LEFT JOIN
                                (SELECT evaluation_submissions.grp_event_id, count( * ) as count2
                                FROM evaluation_submissions
                                WHERE evaluation_submissions.event_id = '.$eventId.'
                                GROUP BY evaluation_submissions.grp_event_id) AS tb2
                                ON tb1.id=tb2.grp_event_id
                              WHERE count1 > count2 OR count2 IS NULL) AS tb3
                            WHERE tb3.id=GroupEvent.id');
  }
}

?>