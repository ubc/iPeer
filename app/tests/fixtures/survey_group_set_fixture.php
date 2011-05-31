<?php

class SurveyGroupSetFixture extends CakeTestFixture {
  var $name = 'SurveyGroupSet';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'survey_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'set_description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'num_groups' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'date' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'released' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'survey_id' => array('column' => 'survey_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
}

