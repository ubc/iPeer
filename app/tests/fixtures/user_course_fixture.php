<?php

class UserCourseFixture extends CakeTestFixture {
  var $name = 'UserCourse';
//  var $import = 'UserCourse';

    var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 11),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 11),
		'access_right' => array('type' => 'string', 'null' => false, 'default' => 'O', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
    	'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length'=>11),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

  
  
  var $records = array(
    array('id' => 1, 'user_id' => 1, 'course_id' => 1),
    array('id' => 2, 'user_id' => 1, 'course_id' => 2),
    array('id' => 3, 'user_id' => 2, 'course_id' => 3),
    array('id' => 4, 'user_id' => 2, 'course_id' => 1),
    
    );
}


