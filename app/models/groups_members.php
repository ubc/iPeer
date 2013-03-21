<?php

/**
 * GroupsMembers
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class GroupsMembers extends AppModel
{
    public $name = 'GroupsMembers';

    /**
     * insertMembers
     * inserts all members into the groups_members table
     *
     * @param int   $id   id
     * @param array $data data
     *
     * @access public
     * @return void
     */
    function insertMembers($id=null, $data=null)
    {
        for ($i=1; $i<=$data['member_count']; $i++) {
            $tmp = array( 'group_id'=>$id, 'user_id'=>$data['member'.$i] );
            $this->save($tmp);
            //reset the id field
            $this->id = null;
        }
    }


    /**
     * Updates group members
     *
     * @param int   $id   group id
     * @param array $data members info
     */

    function updateMembers($id=null, $data=null)
    {
        //get old userid's
        $tmp = $this->getMembers($id);

        $oldUsers = array();
        $newUsers = array();
        $insertUsers = array();
        $deleteUsers = array();

        for ($i = 1; $i <= $data['member_count']; $i++) {
            array_push($newUsers, $data['member'.$i]);
        }
        foreach ($tmp as $user_id) {
            array_push($oldUsers, $user_id);
        }

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
            $tmp = $this->find('first', array(
                'conditions' => array('group_id'=>$id, 'user_id'=>$userId),
                //'fields' => array('GroupMember.id')
            ));
            $this->delete($tmp['GroupsMembers']['id']);
            $this->id = null;
        }
    }

    /**
     * Get members in a group
     *
     * @param int $id Group Id
     *
     * @return $tmp All member ids within the group
     */
    function getMembers($id)
    {
        $tmp = $this->find('list', array('conditions' => array('group_id' => $id),
            'fields' => array('user_id')));
        return $tmp;
    }

    /**
     * Get member count in a group
     *
     * @param int $groupID Group Id
     *
     * @return Number of members within the group
     */
    function countMembers($groupID)
    {
        if (!is_numeric($groupID)) {
            return -1;
        } else {
            return $this->find('count', array('conditions' => array('group_id' => $groupID)));
        }
    }

    /**
     * Get members in a group in event
     *
     * @param int  $groupId  Group Id
     * @param bool $selfEval Check whether Self Evaluation is allowed or not
     * @param int  $userId   User Id
     *
     * @return <type> Group members
     */
    function getEventGroupMembers ($groupId, $selfEval, $userId)
    {
        $conditions['GroupsMembers.group_id'] = $groupId;
        if (!$selfEval) {
            $conditions['GroupsMembers.user_id !='] = $userId;
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array('User.*', 'Role.*'),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = GroupsMembers.user_id')
                ),
                array(
                    'table' => 'roles_users',
                    'alias'  => 'Role',
                    'type' => 'LEFT',
                    'conditions' => array('Role.user_id = GroupsMembers.user_id')
                ),
            ),
        ));
    }
    
    /**
     * Check if user is in a group
     *
     * @param int $groupId Group Id
     * @param int $userId  User Id
     *
     * @return <type> Boolean value whether the user is in the group or not
     */
    function checkMembershipInGroup($groupId, $userId)
    {
        return $this->find('count', array(
            'conditions' => array('GroupsMembers.group_id' => $groupId,
            'GroupsMembers.user_id' => $userId)
        ));
    }

    /**
     * Get list of users in groups
     *
     * @param array $group_ids group id
     *
     * @return 1 if user is in the group, 0 otherwise
     */
    function getUserListInGroups($group_ids)
    {
        $this->displayField = 'user_id';
        return $this->find('list', array(
            'conditions' => array('group_id' => $group_ids),
            'group' => 'user_id'
        ));
    }
}
