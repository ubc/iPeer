<?php

class GroupsMemberFixture extends CakeTestFixture {
  var $name = 'GroupsMember';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	
  var $records = array(
    array('id' => 1, 'group_id' => '1', 'user_id' => '3'),
    array('id' => 2, 'group_id' => '1', 'user_id' => '4'),
    array('id' => 3, 'group_id' => '2', 'user_id' => '3'),
    array('id' => 4, 'group_id' => '2', 'user_id' => '4'),    
  	array('id' => 5, 'group_id' => '4', 'user_id' => '4'),    
    );	
	
}



