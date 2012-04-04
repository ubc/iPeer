<?php

class GroupsMembers extends AppModel
{
  var $name = 'GroupsMembers';

    function isMemberOf($userId, $groupId) {
        $result = $this->findCount("where user_id=$userId AND group_id=$groupId");
        return $result > 0;
    }


  // inserts all members into the groups_members table
  function insertMembers($id=null, $data=null){
  	for( $i=1; $i<=$data['member_count']; $i++ ){
  	  $tmp = array( 'group_id'=>$id, 'user_id'=>$data['member'.$i] );
  	  $this->save($tmp);
  	  //reset the id field
  	  $this->id = null;
    }
  }

  // updates all the group members
  function updateMembers($id=null, $data=null){
//get old userid's
	$tmp = $this->getMembers($id);
	$oldUsers = array();
	$newUsers = array();
	$insertUsers = array();
	$deleteUsers = array();

	for ($i = 1; $i <= $data['member_count']; $i++) array_push($newUsers, $data['member'.$i]);
	for ($i = 0; $i < $tmp['member_count']; $i++) array_push($oldUsers, $tmp[$i]['GroupsMembers']['user_id']);

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
		$tmp = $this->find($conditions = array('group_id'=>$id,'user_id'=>$userId), $fields = 'id');
		$this->del($tmp['GroupsMembers']['id']);
		$this->id = null;
	}
 }

  // returns the user_id of all members in a specific group
  function getMembers($id=null){
  	  $tmp = $this->findAll($conditions = 'group_id='.$id, $fields = 'user_id');
	  $tmp['member_count'] = count($tmp); //$this->findCount($conditions = 'group_id='.$id);

	  return $tmp;
  }


    function countMembers($groupID) {
        if (!is_numeric($groupID)) {
            return -1;
        } else {
            return $this->findCount("`group_id`=$groupID");
        }
    }

  function getEventGroupMembers ($groupId, $selfEval, $userId)
  {
     if ($selfEval)
     {//Include self on evaluation
        $condition = 'GroupsMembers.group_id='.$groupId;
     }
     else {
        $condition = 'GroupsMembers.group_id='.$groupId.' AND User.id<>'.$userId;
     }
    $fields = 'User.id, User.role, User.username, User.first_name, User.last_name, User.student_no, User.title, User.email';
    $joinTable = array(' RIGHT JOIN users as User ON User.id=GroupsMembers.user_id');

    return $this->findAll($condition, $fields, 'User.last_name ASC', null, null, null, $joinTable );

  }
}

