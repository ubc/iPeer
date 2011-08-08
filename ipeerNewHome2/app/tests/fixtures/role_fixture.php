<?php

class RoleFixture extends CakeTestFixture {
  var $name = 'Role';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	  var $records = array(
    array('id' => 1, 'name' => 'superadmin'),
    array('id' => 2, 'name' => 'admin'),
    array('id' => 3, 'name' => 'instructor'),
    array('id' => 4, 'name' => 'student'),
            
  );
	
}

