<?php
/* CourseDepartment Fixture generated on: 2012-05-22 12:23:47 : 1337714627 */
class CourseDepartmentFixture extends CakeTestFixture {
	public $name = 'CourseDepartment';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'department_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'course_id' => array('column' => 'course_id', 'unique' => 0), 'department_id' => array('column' => 'department_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'course_id' => 1,
			'department_id' => 1
		),
	);
}
