<?php
/**
 * FacultyAcoUser
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class FacultyAcoUser extends AppModel
{

    public $name = 'FacultyAcoUser';

    /**
     * getAccessPermissionByFacultyAcoIdUserId
     *
     * @param bool $faculty_aco_id faculty aco id
     * @param bool $user_id        user id
     *
     * @access public
     * @return void
     */
    function getAccessPermissionByFacultyAcoIdUserId($faculty_aco_id=null, $user_id=null)
    {

        return $this->find('first', array('conditions' =>
            array('faculty_aco_id' => $faculty_aco_id, 'dept_id' => $user_id)));
    }


    /**
     * getAllChildren
     *
     * @param mixed $parentNodeId
     *
     * @access public
     * @return void
     */
    function getAllChildren($parentNodeId)
    {
        return $this->children($parentNodeId);
    }


    /**
     * getAco
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function getAco($userId)
    {
        return $this->find('first', array('conditions' => array('dept_id' => $userId), 'fields' => array('faculty_aco_id')));
    }
}
