<?php

class RolesUserFixture extends CakeTestFixture {
  var $name = 'RolesUser';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	
 var $records = array(
    array('id' => 1, 'role_id' => '3', 'user_id' => 1),
    array('id' => 2, 'role_id' => '3', 'user_id' => 2),
    array('id' => 3, 'role_id' => '4', 'user_id' => 3),
    array('id' => 4, 'role_id' => '4', 'user_id' => 4),
    array('id' => 5, 'role_id' => '3', 'user_id' => 5),
    array('id' => 6, 'role_id' => '3', 'user_id' => 6),
    array('id' => 7, 'role_id' => '3', 'user_id' => 7),
    array('id' => 8, 'role_id' => '1', 'user_id' => 8),
    array('id' => 9, 'role_id' => '2', 'user_id' => 9),
    array('id' => 10, 'role_id' => '4', 'user_id' => 10),
    array('id' => 11, 'role_id' => '4', 'user_id' => 11),    
  );
	
	
}
