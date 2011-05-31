<?php

class SurveyGroupFixture extends CakeTestFixture {
  var $name = 'SurveyGroup';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_set_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'group_number' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
}

