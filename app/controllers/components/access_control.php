<?php
App::import('Model', 'Role');
App::import('Model', 'User');

/**
 * AccessControlComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class AccessControlComponent extends Object
{
    public $name = 'AccessControl';
    public $components = array('Session', 'Acl');
    public $controller = null;
    public $User = null;
    public $action_map = array(
        'DeleteUser' => array(
            'aco_prefix'    => 'functions/user',
            'action'        => 'delete'
        ),
        'DropUser'   => array(
            'aco_prefix'    => 'functions/user',
            'action'        => 'delete'
        ),
        'ViewUser'   => array(
            'aco_prefix'    => 'functions/user',
            'action'        => 'read'
        ),
        'PasswordReset' => array(
            'aco_prefix' => 'functions/user/password_reset',
            'action'     => 'update'
        ),
    );

    /**
     * initialize
     *
     * @param mixed &$controller controller
     * @param bool  $settings    settings
     *
     * @access public
     * @return void
     */
    function initialize(&$controller, $settings=array())
    {
        $this->controller = $controller;
        $this->User = new User;
    }


    /**
     * check
     *
     * @param mixed  $aco    aco
     * @param string $action action
     *
     * @access public
     * @return void
     */
    function check($aco, $action = '*')
    {
        if (!$this->hasPermission($aco, $action)) {
            $this->controller->cakeError('permissionDenied');
        }

        return true;
    }


    /**
     * hasPermission
     *
     * @param mixed  $aco    aco
     * @param string $action action
     *
     * @access public
     * @return void
     */
    function hasPermission($aco, $action = '*')
    {
        $pass = false;
        $role = new Role;

        // read user roles
        if (!($roles = $this->Session->read('ipeerSession.Roles'))) {
            $user = $this->User->find('first', array('conditions' => array('User.id' => User::get('id'))));
            $roles = $user['Role'];
            $this->Session->write('ipeerSession.Roles', $roles);
        }

        // check against the acl table
        foreach ($roles as $r) {
            $role->set('id', $r['id']);
            $pass = $pass | $this->Acl->check($role, $aco, $action);
        }

        return $pass;
    }


    /**
     * getEditableRoles
     *
     *
     * @access public
     * @return void
     */
    function getEditableRoles()
    {
        $all_roles = $this->User->Role->find('list', array('fields'=>array('id', 'name')));
        $roles = array();
        foreach ($all_roles as $key => $r) {
            if ($this->hasPermission('functions/user/'.$r, 'create')) {
                $roles[$key] = $r;
            }
        }
        return $roles;
    }


    /**
     * hasPermissionDoActionOnUserWithRoles
     *
     * @param mixed $action action
     * @param mixed $roles  roles
     *
     * @access public
     * @return void
     */
    function hasPermissionDoActionOnUserWithRoles($action, $roles)
    {
        $pass = false;

        // deny if the action is not exist in map
        if (!array_key_exists($action, $this->action_map)) {
            return false;
        }

        foreach ($roles as $role) {
            if ($pass = $pass | $this->hasPermission($this->action_map[$action]['aco_prefix'].'/'.$role,
                $this->action_map[$action]['action'])) {
                    break;
            }
        }
        return $pass;
    }

}
