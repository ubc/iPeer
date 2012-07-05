<?php
/* UserTutor Fixture generated on: 2012-07-05 16:00:07 : 1341529207 */
class UserTutorFixture extends CakeTestFixture {
	var $name = 'UserTutor';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'course_id' => array('column' => 'course_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'course_id' => 1,
			'creator_id' => 1,
			'created' => '2012-07-05 16:00:07',
			'updater_id' => 1,
			'modified' => '2012-07-05 16:00:07'
		),
	);
}
