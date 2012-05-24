<?php
/* Department Fixture generated on: 2012-05-17 17:09:48 : 1337299788 */
class DepartmentFixture extends CakeTestFixture {
	var $name = 'Department';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 80, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'faculty_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1), 'faculty_id' => array('column' => 'faculty_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'faculty_id' => 1,
			'creator_id' => 1,
			'created' => '2012-05-17 17:09:48',
			'updater_id' => 1,
			'modified' => '2012-05-17 17:09:48'
		),
	);
}
