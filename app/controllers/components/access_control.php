<?php
App::import('Model', 'Role');
App::import('Model', 'User');

class AccessControlComponent extends Object {
  var $name = 'AccessControl';
  var $components = array('Session', 'Acl');
  var $controller = null;
  var $User = null;
  var $action_map = array('DeleteUser' => array('aco_prefix'    => 'functions/user',
                                                'action'        => 'delete'),
                          'DropUser'   => array('aco_prefix'    => 'functions/user',
                                                'action'        => 'delete'),
                          'ViewUser'   => array('aco_prefix'    => 'functions/user',
                                                'action'        => 'read'),
                          'PasswordReset' => array('aco_prefix' => 'functions/user/password_reset',
                                                   'action'     => 'update'),
                          );

  function initialize(&$controller, $settings=array()) {
    $this->controller = $controller;
    $this->User = new User;
  }

  function check($aco, $action = '*') {
    if(!$this->hasPermission($aco, $action)) {
      $this->controller->cakeError('permissionDenied');
    }

    return true;
  }

  function hasPermission($aco, $action = '*') {
    $pass = false;
    $role = new Role;

    // read user roles
    if(!($roles = $this->Session->read('ipeerSession.Roles'))) {
      $user = $this->User->find('first',  array('conditions' => array('User.id' => User::get('id'))));
      $roles = $user['Role'];
      $this->Session->write('ipeerSession.Roles', $roles);
    }

    // check against the acl table
    foreach($roles as $r) {
      $role->set('id', $r['id']);
      $pass = $pass | $this->Acl->check($role, $aco, $action);
    }

    return $pass;
  }

  function getEditableRoles() {
    $all_roles = $this->User->Role->find('list',array('fields'=>array('id','name')));
    $roles = array();
    foreach($all_roles as $key => $r) {
      if($this->hasPermission('functions/user/'.$r, 'create')) {
        $roles[$key] = $r;
      }
    }
    return $roles;
  }

  function hasPermissionDoActionOnUserWithRoles($action, $roles) {
    $pass = false;

    // deny if the action is not exist in map
    if(!array_key_exists($action, $this->action_map)) {
      return false;
    }

    foreach($roles as $role) {
      if($pass = $pass | $this->hasPermission($this->action_map[$action]['aco_prefix'].'/'.$role, 
                                              $this->action_map[$action]['action'])) {
        break;
      }
    }
    return $pass;
  }
}
