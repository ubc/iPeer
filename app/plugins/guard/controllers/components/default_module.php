<?php

/**
 * DefaultModule the default authentication module, using AuthComponent
 *
 * @uses AuthModule
 * @package Plugins.Guard
 * @version //autogen//
 * @copyright Copyright (C) 2010 CTLT
 * @author Compass
 * @license LGPL {@link http://www.gnu.org/copyleft/lesser.html}
 */
class DefaultModule extends AuthModule {
    /**
     * name the name of the module
     *
     * @var string
     * @access protected
     */
    var $name         = 'Default';

    /**
     * hasLoginForm this module has login form
     *
     * @var boolean true
     * @access protected
     */
    var $hasLoginForm = true;

    /**
     * authenticate provide the authenticate method. Checking against the
     * internal user table in the database. The user table can be defined by
     * UserModel variable. The method also creates the user session by using
     * AuthComponent::login().
     *
     * @access public
     * @return boolean true, if the user is successfully authenticated. false,
     * if not
     */
  function authenticate()
  {
    $model =& $this->guard->getModel();
    
    $data = $this->getLoginData();
    $username = $data[$this->fields['username']];
    $password = $data[$this->fields['password']];
    
    if (empty($username)) {
      return $this->Session->setFlash('Username cannot be empty', 'error');
    } else if (empty($password)) {
      return $this->Session->setFlash('Password cannot be empty', 'error');
    }
    
    $data = $this->guard->hashPasswords(array($model->alias => $data));
    
    $data = array(
      $model->alias . '.' . $this->fields['username'] => $data[$model->alias][$this->fields['username']],
      $model->alias . '.' . $this->fields['password'] => $data[$model->alias][$this->fields['password']]
    );
    
    if ($this->guard->login($data)) {
      return $this->guard->login($data);
    } else {
      return $this->Session->setFlash('Invalid Login Credentials', 'error');
    }
  }
}

