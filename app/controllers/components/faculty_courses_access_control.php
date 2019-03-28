<?php
/**
 * FacultyCoursesAccessControlComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class FacultyCoursesAccessControlComponent extends CakeObject
{

    public $FacultyAcoUser;
    public $FacultyAco;

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->FacultyAcoUser = ClassRegistry::init('FacultyAcoUser');
        $this->FacultyAco = ClassRegistry::init('FacultyAco');
        $this->DepartmentUser = ClassRegistry::init('DepartmentUser');
    }


    /**
     * grantAccessPermission
     *
     * @param mixed $facultyAcoId faculty aco id
     * @param mixed $userId       user id
     *
     * @access public
     * @return void
     */
    function grantAccessPermission($facultyAcoId, $userId)
    {
        $data['faculty_aco_id'] = $facultyAcoId;
        $data['user_id'] = $userId;
        $data['_create'] = 1;
        $data['_read'] = 1;
        $data['_update'] = 1;
        $data['_delete'] = 1;
        // Saves only if the parent node has been denied access permission
        $parentAccessPermission = $this->_getParentsAccessPermission($facultyAcoId, $userId);
        if (!$parentAccessPermission) {
            $this->FacultyAcoUser->save($data);
            return true;
        } else {
            return false;
        }
    }


    /**
     * denyAccessPermission
     *
     * @param mixed $facultyAcoId faculty aco id
     * @param mixed $userId       user id
     *
     * @access public
     * @return void
     */
    function denyAccessPermission($facultyAcoId, $userId)
    {
        $data['faculty_aco_id'] = $facultyAcoId;
        $data['user_id'] = $userId;
        $data['_create'] = -1;
        $data['_read'] = -1;
        $data['_update'] = -1;
        $data['_delete'] = -1;
        // Save only if it's the highest deny permission
        $parentAccessPermission = $this->_getParentsAccessPermission($facultyAcoId, $userId);
        if ($parentAccessPermission) {
            $this->FacultyAcoUser->save($data);
            return true;
        } else {
            return false;
        }
    }


    /**
     * getAccessPermissions
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function getAccessPermissions($userId)
    {
        $dept = $this->DepartmentUser->getDepartment($userId);
        $aco = $this->FacultyAcoUser->getAco($dept['UserDept']['dept_id']);


        $acoId = $aco['FacultyAcoUser']['faculty_aco_id'];
        return $this->FacultyAco->children($acoId, false, 'id');
    }


    /**
     * getReadPermission
     *
     * @param mixed $userId user id
     * @param mixed $aco    aco
     *
     * @access public
     * @return void
     */
    function getReadPermission($userId, $aco)
    {
        $dept = $this->DepartmentUser->getDepartment($userId);
        $dept = $dept['DepartmentUser']['dept_id'];

        return $this->getParentsReadPermission($aco, $dept);
    }


    /**
     * getParentsReadPermission
     *
     * @param mixed $childNodeId child node id
     * @param mixed $deptId      department id
     *
     * @access public
     * @return void
     */
    function getParentsReadPermission($childNodeId, $deptId)
    {
        $parentNode = $this->FacultyAco->getparentnode($childNodeId);
        $permission = $this->FacultyAcoUser->getAccessPermissionByFacultyAcoIdUserId($childNodeId, $deptId);
        $accessible = null;
        if (!empty($permission)) {
            $accessible = ($permission['FacultyAcoUser']['_read'] == 1 ? true : false);
            return $accessible;
        } else {
            $accessible = $this->getParentsReadPermission($parentNode['FacultyAco']['id'], $deptId);
        }
        return $accessible;
    }


    /**
     * _getParentsAccessPermission
     *
     * @param mixed $childNodeId child node id
     * @param mixed $userId      user id
     *
     * @access protected
     * @return void
     */
    function _getParentsAccessPermission($childNodeId, $userId)
    {
        $parentNode = $this->FacultyAco->getparentnode($childNodeId);
        $accessible = null;
        // current childNode is already root; else has parent.
        if (empty($parentNode)) {
            $rootPermission = $this->FacultyAcoUser->getAccessPermissionByFacultyAcoIdUserId($childNodeId, $userId);
            $accessible = ($rootPermission['FacultyAcoUser']['_read'] == 1 ? true : false);
        } else {
            $parentPermission = $this->FacultyAcoUser->getAccessPermissionByFacultyAcoIdUserId($parentNode['FacultyAco']['id'], $userId);
            $accessible = ($parentPermission['FacultyAcoUser']['_read'] == 1 ? true : false);
        }
        return $accessible;
    }
}
