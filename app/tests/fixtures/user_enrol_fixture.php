<?php

class UserEnrolFixture extends CakeTestFixture {
  var $name = 'UserEnrol';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
    'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'course_id' => array('column' => array('course_id', 'user_id'), 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'user_id_index' => array('column' => 'user_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	
  var $records = array(
    array('id' => 1, 'course_id' => 1, 'user_id' => 1, 'record_status' => 'I'),
    array('id' => 2, 'course_id' => 1, 'user_id' => 2, 'record_status' => 'S'),
    array('id' => 3, 'course_id' => 1, 'user_id' => 3, 'record_status' => 'S'),
    array('id' => 4, 'course_id' => 1, 'user_id' => 4, 'record_status' => 'S'),  
    array('id' => 5, 'course_id' => 2, 'user_id' => 3, 'record_status' => 'S'),
  );

}

