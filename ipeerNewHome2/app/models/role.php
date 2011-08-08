<?php
/* SVN FILE: $Id: role.php 509 2011-05-27 21:32:01Z tonychiu $ */

/**
 * Role Model
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */

class Role extends AppModel
{
	//The model name
	var $name = 'Role';
  var $test = 'a';

  var $hasAndBelongsToMany = array('User' => array('className' => 'User',
                                                   'joinTable' => 'roles_users',
                                                   'foreignKey' => 'role_id',
                                                   'associationForeignKey' => 'user_id',
                                                   'unique' => true,
                                                   'dependent' => true,)
                                  );

  var $actsAs = array('Acl' => array('type' => 'requester'));

  function parentNode() {
    return null;
  }
  
  function getRoleByRoleNumber($roleNum=''){
  	if($roleNum==1)return 'SA';
  	if($roleNum==2)return 'A';
  	if($roleNum==3)return 'I';
  	if($roleNum==4)return 'S';
  	else return null; 
  }
}
