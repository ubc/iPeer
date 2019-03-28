<?php
/**
 * AccessControlComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class AccessControlComponent extends CakeObject
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

    protected $options = array('model'=>'Aco', 'field'=>'alias');
    //used for recursive variable setting/checking
    protected $perms = array();   //for ACL defined permissions
    protected $permissionsArray = array();    //for all permissions
    protected $inheritPermission = array();   //array indexed by level to hold the inherited permission

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
        $this->User = ClassRegistry::init('User');
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
        if (!User::hasPermission($aco, $action)) {
            $this->controller->cakeError('permissionDenied');
        }

        return true;
    }


    /**
     * hasPermission test if the user has permssion to aco and action
     *
     * @param mixed  $aco    aco
     * @param string $action action
     *
     * @access public
     * @return void
     */
/*    function hasPermission($aco, $action = '*')
    {
        $pass = false;

        $perms = $this->getPermissions();

        // checking through the aco path, each loop will go one level higher
        while (!empty($aco)) {
            foreach ($perms as $aroNode) {
                // if the action is wildcard *, we check if the permissions are
                // all 1
                if ($action == '*') {
                    $access = Set::extract('/Aco[alias=/^'.$aco.'$/i]/Permission/.', $aroNode);
                    //var_dump($aco, $access, $aroNode);

                    if (empty($access)) {
                        // didn't find any permssion for current aco path, try
                        // one level up
                        continue;
                    }

                    // found the permission, let's see if all permissions are granted
                    foreach ($access[0] as $key => $p) {
                        // looking for the records start with '_' for
                        // permissions
                        if (substr($key, 0, 1) != '_') {
                            continue;
                        }

                        if ($p != 1) {
                            return false;
                        }

                    }

                    return true;
                }

                // check for permissions on specific controller action
                $access = Set::extract(
                    sprintf(
                        '/Aco[alias=/%1$s/]/Permission[_%2$s!=0]/_%2$s',
                        $aco,
                        $action
                    ),
                    $aroNode
                );

                if (!empty($access)) {
                    if ($access[0] == 1) {
                        // explicitly allow
                        return true;
                    } else if ($access[0] == -1) {
                        // explicitly deny
                        return false;
                    }
                }
            }

            // trace to a higher level of aco to see if we have permission there
            $aco = explode('/', $aco);
            array_pop($aco);
            $aco = implode('/', $aco);
        }

        return $pass;
    }*/


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
            if (User::hasPermission('functions/user/'.$r, 'create')) {
                $roles[$key] = $r;
            }
        }
        return $roles;
    }

   /**
    * getViewableRoles
    *
    *
    * @access public
    * @return void
    */
    function getViewableRoles()
    {
        $all_roles = $this->User->Role->find('list', array('fields'=>array('id', 'name')));
        $roles = array();
        foreach ($all_roles as $key => $r) {
            if (User::hasPermission('functions/user/'.$r, 'read')) {
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
            if ($pass = $pass | User::hasPermission($this->action_map[$action]['aco_prefix'].'/'.$role,
                $this->action_map[$action]['action'])) {
                    break;
            }
        }
        return $pass;
    }

    /**
     * getRoles get user roles and save it to session
     *
     *
     * @access public
     * @return array role list
     */
    function getRoles()
    {
        $role = ClassRegistry::init('Role');
        $roles = array();
        if (!($roles = $this->Session->read('ipeerSession.Roles'))) {
            $roles = $role->find(
                'all',
                array(
                    'conditions' => array('User.id' => User::get('id'),),
                    'fields' => array('id', 'name'),
                    'recursive' => 0,
                )
            );
            // habtmable doesn't work with find list, so we make the list ourselves
            $roles = Set::combine($roles, '{n}.Role.id', '{n}.Role.name');
            $this->Session->write('ipeerSession.Roles', $roles);
        }

        return $roles;
    }

    /**
     * getPermissions get the permssions and cache them into session
     *
     *
     * @access public
     * @return array all the permissions for current user
     */
    function getPermissions()
    {
        if (!($this->permissionArray = $this->Session->read('ipeerSession.Permissions'))) {
            $this->loadPermissions();
        }

        return $this->permissionsArray;
    }

    /**
     * loadPermissions load permissions from database and cache them in seesion
     *
     * @access public
     * @return void
     */
    function loadPermissions()
    {
        $this->permissionsArray = array();
        $roles = $this->getRoles();
        if (!empty($roles)) {
            $roleIds = array_keys($roles);

            //GET ACL PERMISSIONS
            $acos = $this->Acl->Aco->find('threaded');
            $group_aro = $this->Acl->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>$roleIds, 'Aro.model'=>'Role')));
            $group_perms = Set::extract('{n}.Aco', $group_aro);
            $gpAco = array();
            foreach ($group_perms[0] as $value) {
                $gpAco[$value['id']] = $value;
            }

            $this->perms = $gpAco;
            $this->_addPermissions($acos, 0);
        }

        $this->Session->write('ipeerSession.Permissions', $this->permissionsArray);
    }


    /**
     * _addPermissions
     *
     * @param mixed $acos              all acos
     * @param mixed $level             level
     * @param mixed $alias             alias
     * @param array $inheritPermission inherit permission
     *
     * @access protected
     * @return void
     */
    function _addPermissions($acos, $level, $alias = '', $inheritPermission = array())
    {
        //echo "Going into level $level<br />";
        foreach ($acos as $val) {
            $thisAlias = $alias . $val[$this->options['model']][$this->options['field']];
            //echo "alias: $thisAlias<br />";

            if (isset($this->perms[$val[$this->options['model']]['id']])) {
                $curr_perm = $this->perms[$val[$this->options['model']]['id']];
                $access = array();
                foreach ($curr_perm['Permission'] as $key => $p) {
                    // looking for the records start with '_' for permissions
                    if (substr($key, 0, 1) != '_') {
                        continue;
                    }

                    if ($p == 1) {
                        $access[] = substr($key, 1);
                    }
                }
                if (!empty($access)) {
                    $this->permissionsArray[strtolower($thisAlias)] = $access;
                    $inheritPermission[$level] = $access;
                } else {
                    $inheritPermission[$level] = -1;
                }
            } else {
                //echo "perms not found<br />";
                if (!empty($inheritPermission)) {
                    //echo $level.'::'.$thisAlias;
                    //var_dump($inheritPermission);
                    //check for inheritedPermissions, by checking closest array element
                    if ($inheritPermission[$level-1] != -1) {
                        //the level above was set to 1, so this should be a 1
                        $this->permissionsArray[strtolower($thisAlias)] = $inheritPermission[$level-1];
                    }
                    $inheritPermission[$level] = $inheritPermission[$level-1];
                }
            }

            //var_dump($this->permissionsArray);
            if (isset($val['children'][0])) {
                $old_alias = $alias;
                $alias .= $val[$this->options['model']][$this->options['field']] .'/';
                //var_dump('Child '.$alias);
                $this->_addPermissions($val['children'], $level+1, $alias, $inheritPermission);
                // clear the inherit permission for next branch
                unset($inheritPermission[$level+1]);  //don't want the last level's inheritance, in case it was set
                $alias = $old_alias;
            }
        }

        return;
    }



}
