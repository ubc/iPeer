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
  var $actsAs = array('Traceable');
  // inserts all members into the groups_events table
  function insertGroups($id=null, $data=null){
  	for( $i=0; $i<count($data['Member']); $i++ ){
  	  if (!empty($id) && !empty($data['Member'][$i]))
  	  {
    	  $tmp = array( 'group_id'=>$data['Member'][$i],'event_id'=>$id, 'marked'=>'not reviewed' );
    	  $this->save($tmp);
    	  //reset the id field
    	  $this->id = null;
    	}
    }
  }

  /**
   * Updates all the group events
   * @param $id Event id
   * @param $data new goups for the event
   * 
   */

  function updateGroups($id=null, $data=null){
//get old groupid's
	$tmp = $this->getGroupIDsByEventId($id);
	$oldGroups = array();
	$newGroups = array();
	$insertGroups = array();
	$deleteGroups = array();
//	for ($i = 1; $i <= $data['group_count']; $i++) array_push($newGroups, $data['group'.$i]);
	for ($i = 0; $i < count($data['Member']); $i++)if(!empty($data['Member']))array_push($newGroups, $data['Member'][$i]);
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
		//$tmp = $this->find($conditions = array('event_id'=>$id,'group_id'=>$groupId), $fields = 'id');
                $tmp = $this->find('first',array(
                    'conditions' => array('event_id'=>$id,'group_id'=>$groupId),
                    'fields' => array('GroupEvent.id')
                ));
		$this->delete($tmp['GroupEvent']['id']);
		$this->id = null;
	}
  }
/**
 * Returns list of group id within the selected Event
 * @param $eventId event id
 * @return array of group ids associated with the event
 */

  function getGroupIDsByEventId($eventId=null){
    if(empty($eventId) || is_null($eventId)) {
  	  	return;
  	  }
  	return $this->find('all', array('conditions'=>array('event_id'=>$eventId), 'fields' => 'group_id'));
  }

  
  /**
   * Returns list of group events for the correspoding eventId input
   * @param $eventId event id
   * @return array of grops associated with the event
   */

  function getGroupsByEventId($eventId=null){
    if(empty($eventId) || is_null($eventId)) {
  	  	return;
  	  }
  	return $this->find('all', array('conditions'=>array('event_id'=>$eventId)));
  }
  /**
   * Returns list of group id within the selected Event
   * @param $eventId event id
   * @return list of groups by event id
   */

  function getGroupListByEventId($eventId=null){
  	  if (empty($eventId) || is_null($eventId)) {
  	  	return;
  	  }
	  $tmp = $this->find('all',array(
              'conditions' => array('event_id' => $eventId), 'fields'=>array('id', 'group_id')
          ));
	  return $tmp;
  }

  /**
   * returns list of Group Event
   * @param $eventId event id
   * @param $groupId group id
   * @return GroupEvent 
   */
  
  function getGroupEventByEventIdGroupId($eventId=null, $groupId=null){
    if (empty($groupId)||is_null($groupId))
      return;
          $tmp = $this->find('first',array(
              'conditions' => array('event_id' => $eventId, 'group_id' => $groupId)
          ));
	  return $tmp;
  }
  
  /**
   * Get GroupEvent by user id 
   * @param unknown_type $userId
   * @param unknown_type $eventId
   * @return GroupEvents associated with the member
   */
  
  function getGroupEventByUserId($userId=null, $eventId=null)
  {
    return $this->find('all', array(
                    'conditions' => array('GroupEvent.event_id'=>$eventId, 'GroupMember.user_id'=>$userId),
                    'fields' => array('GroupEvent.id', 'GroupEvent.group_id', 'GroupEvent.event_id'),
                    'joins' => array(
                        array(
                            'table' => 'groups_members',
                            'alias' => 'GroupMember',
                            'type' => 'RIGHT',
                            'conditions' => array('GroupMember.group_id = GroupEvent.group_id')
                        )
                    )
                ));
  }

  /**
   * 
   * @param $eventId
   * @param $eventTypeId
   * @param $maxPercent
   * @param $minPercent
   */
  
  function getLowMark($eventId=null, $eventTypeId=null, $maxPercent=1, $minPercent=0) {
    $eventTypeEval = array(1=>'evaluation_simples',2=>'evaluation_rubrics',4=>'evaluation_mixevals');
    $eventType = array(1=>'simple_evaluations',2=>'rubrics',4=>'mixevals');
    switch ($eventTypeId) {
      case 1: //simple eval
        return $this->query('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked
                                FROM group_events AS GroupEvent
                                WHERE GroupEvent.event_id='.$eventId.'
                                AND GroupEvent.id IN (SELECT DISTINCT grp_event_id
                                                      FROM '.$eventTypeEval[$eventTypeId].'
                                                      WHERE event_id='.$eventId.'
                                                      GROUP BY grp_event_id, evaluatee
                                                      HAVING (SUM(score)/COUNT(evaluatee)) < '.$maxPercent.'*(SELECT point_per_member
                                                                                                              FROM '.$eventType[$eventTypeId].'
                                                                                                              WHERE id IN (SELECT template_id AS id
                                                                                                                           FROM events WHERE id=GroupEvent.event_id)
                                                                                                              )
                                                      AND (SUM(score)/COUNT(evaluatee)) > '.$minPercent.'*COUNT(evaluatee)*(SELECT point_per_member
                                                                                                                            FROM '.$eventType[$eventTypeId].'
                                                                                                                            WHERE id IN (SELECT template_id AS id
                                                                                                                                         FROM events
                                                                                                                                         WHERE id=GroupEvent.event_id)
                                                                                                                            )
                                                      )
                                ORDER BY GroupEvent.group_id');
        break;
      case 2||4:
        //echo $eventId.' '.$eventTypeId.' '.$maxPercent;
        return $this->query('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked
                                FROM group_events AS GroupEvent
                                WHERE GroupEvent.event_id='.$eventId.' AND GroupEvent.id
                                IN (SELECT DISTINCT grp_event_id
                                    FROM '.$eventTypeEval[$eventTypeId].'
                                    WHERE event_id='.$eventId.'
                                    GROUP BY grp_event_id, evaluatee
                                    HAVING (SUM(score)/COUNT(evaluatee)) < '.$maxPercent.'*(SELECT (SELECT sum(multiplier) as sum FROM rubrics_criterias as rc WHERE rc.rubric_id = id ) 
                                                                                            FROM '.$eventType[$eventTypeId].'
                                                                                            WHERE id IN (SELECT template_id AS id
                                                                                                         FROM events
                                                                                                         WHERE id = GroupEvent.event_id)
                                                                                            )
                                    AND    (SUM(score)/COUNT(evaluatee)) > '.$minPercent.'*(SELECT (SELECT sum(multiplier) as sum FROM rubrics_criterias as rc WHERE rc.rubric_id = id )
                                                                                            FROM '.$eventType[$eventTypeId].'
                                                                                            WHERE id IN (SELECT template_id AS id
                                                                                                         FROM events
                                                                                                         WHERE id=GroupEvent.event_id)
                                                                                           )
                                   )
                                ORDER BY GroupEvent.group_id');
        break;
      default:
        return null;
    }
  }

  /**
   * Get all not reviewed GroupEvents
   * @param $eventId event id
   * @return not reviewed GroupEvents
   */
  function getNotReviewed($eventId=null) {
    return $this->find('all', array(
        'conditions' => array('GroupEvent.group_id !=' => '0', 'GroupEvent.event_id' => $eventId,
            "OR" => array(
                array('GroupEvent.marked' => "not reviewed"),
                array('GroupEvent.marked' => "to review"))),
        'order' => 'GroupEvent.group_id'
    ));
  }
  
  function getGroupMembers($groupEventId) {
  	return $this->find('all', array(
  						'conditions' => array('GroupEvent.id' => $groupEventId),
  						'fields' => array('GroupsMembers.*'),
  						'joins' => array(
  							array(
  								'table' => 'groups_members',
  								'alias' => 'GroupsMembers',
  								'type' => 'LEFT',
  								'conditions' => array('GroupEvent.group_id = GroupsMembers.group_id')
  								)
  							),
  						 'order' => array('GroupsMembers.user_id' => 'ASC')	
  				));
  }

  /**
   * Get group members with late evaluations
   * @param $groupEventId GroupEvent id
   * @return late Group Members
   */
    function getLateGroupMembers($groupEventId) {
        return $this->find('count', array(
                    'conditions' => array('GroupEvent.id' => $groupEventId, 'EvaluationSubmission.date_submitted > Event.due_date'),
                    'joins' => array(
                        array(
                            'table' => 'events',
                            'alias' => 'Event',
                            'type' => 'LEFT',
                            'conditions' => array('GroupEvent.event_id = Event.id')
                        ),
                        array(
                            'table' => 'evaluation_submissions',
                            'alias' => 'EvaluationSubmission',
                            'type' => 'LEFT',
                            'conditions' => array('GroupEvent.id = EvaluationSubmission.grp_event_id')
                        )
                    )
                ));
    }

  /**
   * Get all late GroupEvents for an event
   * @param $eventId event id
   * @return array of late group members
   */  
    
  function getLate($eventId) {
    return $this->query('SELECT GroupEvent.id,GroupEvent.group_id,GroupEvent.marked
                            FROM group_events as GroupEvent
                            LEFT JOIN events as Event ON GroupEvent.event_id = Event.id
                            LEFT JOIN evaluation_submissions as es  on GroupEvent.id=es.grp_event_id
                            WHERE GroupEvent.id IN  (SELECT id
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
                              WHERE count1 > count2 OR count2 IS NULL)
                            AND GroupEvent.group_id != 0 AND (Event.due_date < now() OR date_submitted > due_date)'
                            );
  }
  
  /**
   * 
   * Get GroupEvents by event id
   * @param $eventId event id
   * @return groupEvents
   */
  
  function getGroupEventByEventId($eventId){
    return $this->find('all', array(
        'conditions' => array('GroupEvent.event_id' => $eventId)
    ));
  }
  
  /**
   * 
   * Get GroupEvent by group id and event id
   * @param $eventId event id
   * @param  $groupId group id
   * @return GroupEvent 
   */
  
  function getGroupEventByEventIdAndGroupId($eventId, $groupId){
    $returning = $this->find('first', array(
        'conditions' => array('GroupEvent.event_id' => $eventId, 'GroupEvent.group_id' => $groupId),
        'fields' => array('GroupEvent.id')
    ));
    return $returning['GroupEvent']['id'];
  }
  
  /**
   * Returns the type of evaluation for the group_event
   * 
   * @param INT $grpEventId : group_event_id
   * @return The type of evaluation corresponding to this grpEvent
   */
  function getEvalType($grpEventId){
  	$returning = $this->find('first', array(
  				'conditions' => array('GroupEvent.id' => $grpEventId),
  				'joins' => array(
  							array(
  								'table' => 'events',
  								'alias' => 'Event',
  								'type' => 'LEFT',
  								'conditions' => array('GroupEvent.event_id = Event.id')
  								)
  							),
  				 'fields' => array('Event.event_template_type_id')
  			));
	return $returning['Event']['event_template_type_id'];
  }

  /**
   * Returns a grpEvent tuple with the desired fields.
   * @param INT $grpEventid : group_event_id
   * @return group_event_tuple.
   */
  function getGrpEvent($grpEventid=null, $fields=array()){
  	return $this->find('first', array('conditions' => array('GroupEvent.id' => $grpEventid),
  									  'fields' => $fields));
  }
  
  function getGrpEventByEventId($eventId) {
  	return $this->find('all', array('conditions' => array('event_id' => $eventId)));
  }
}
?>
