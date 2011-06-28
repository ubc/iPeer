<?php

class GroupsMembers extends AppModel
{
  var $name = 'GroupsMembers';

  // inserts all members into the groups_members table
  function insertMembers($id=null, $data=null){
    for( $i=1; $i<=$data['member_count']; $i++ ){
      $tmp = array( 'group_id'=>$id, 'user_id'=>$data['member'.$i] );
      $this->save($tmp);
      //reset the id field
      $this->id = null;
    }
  }

  /**
   * Updates group members
   * @param $id group id
   * @param $data members info
   	*/

  function updateMembers($id=null, $data=null){
    //get old userid's
    $tmp = $this->getMembers($id);

    $oldUsers = array();
    $newUsers = array();
    $insertUsers = array();
    $deleteUsers = array();

    for ($i = 1; $i <= $data['member_count']; $i++) array_push($newUsers, $data['member'.$i]);
    foreach($tmp as $user_id) {
      array_push($oldUsers, $user_id);
    }
  //  for ($i = 0; $i < $tmp['member_count']; $i++) array_push($oldUsers, $tmp[$i]['GroupsMembers']['user_id']);

      
    //compare
    $insertUsers = array_diff($newUsers, $oldUsers);
    $deleteUsers = array_diff($oldUsers, $newUsers);

    //insert/delete
    foreach ($insertUsers as $userId) {
      $tmp = array('group_id'=>$id, 'user_id'=>$userId);
      $this->save($tmp);
      $this->id = null;
    }
    foreach ($deleteUsers as $userId) {
      $tmp = $this->find('first',array(
          'conditions' => array('group_id'=>$id,'user_id'=>$userId),
          //'fields' => array('GroupMember.id')
          ));
      $this->delete($tmp['GroupsMembers']['id']);
      $this->id = null;
    }
 }

  /**
   *
   * Get members in a group
   * @param  $id Group Id
   * @return $tmp All member ids within the group
   */
  function getMembers($id){
    $tmp = $this->find('list',array('conditions' => array('group_id' => $id),
      'fields' => array('user_id')));
    //$tmp['member_count'] = count($tmp);
    return $tmp;
  }

  /**
   *
   * Get member count in a group
   * @param  $groupID Group Id
   * @return Number of members within the group
   */
  function countMembers($groupID) {
    if (!is_numeric($groupID)) {
      return -1;
    } else {
      return $this->find('count', array('conditions' => array('group_id' => $groupID)));
    }
  }

  /**
   * Get members in a group in event
   * @param <type> $groupId Group Id
   * @param <type> $selfEval Check whether Self Evaluation is allowed or not
   * @param <type> $userId User Id
   * @return <type> Group members
   */
  function getEventGroupMembers ($groupId, $selfEval, $userId)
  {
    $conditions['GroupsMembers.group_id'] = $groupId;
    if(!$selfEval)
      $conditions['GroupsMembers.user_id !='] = $userId;

    return $this->find('all', array(
      'conditions' => $conditions,
      'fields' => array('User.*'),
      'joins' => array(
          array(
            'table' => 'users',
            'alias' => 'User',
            'type' => 'LEFT',
            'conditions' => array('User.id = GroupsMembers.user_id')
          )
        ),
    ));
  }

  /**
   * Get groups by user id
   * @param <type> $userId User Id
   * @return <type> Groups that the user is in
   */
  function getGroupsByUserId($userId){
    return $this->find('all', array(
        'conditions' => array('user_id' => $userId)
    ));
  }
  
  /**
   * Check if user is in a group
   * @param <type> $groupId Group Id
   * @param <type> $userId User Id
   * @return <type> Boolean value whether the user is in the group or not
   */
  function checkMembershipInGroup($groupId, $userId){
    return $this->find('count', array(
        'conditions' => array('GroupsMembers.group_id' => $groupId,
            'GroupsMembers.user_id' => $userId)
    ));
  }

  /**
   * Get list of users in groups
   * @param $group_ids group id
   * @return 1 if user is in the group, 0 otherwise
   */
  function getUserListInGroups($group_ids){
    $this->displayField = 'user_id';
    return $this->find('list', array(
        'conditions' => array('group_id' => $group_ids),
        'group' => 'user_id'
    ));
  }
}

?>
