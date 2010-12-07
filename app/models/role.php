<?php
/* SVN FILE: $Id$ */

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
}
