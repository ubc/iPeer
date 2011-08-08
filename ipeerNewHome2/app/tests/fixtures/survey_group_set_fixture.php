<?php

class SurveyGroupSetFixture extends CakeTestFixture {
  var $name = 'SurveyGroupSet';
  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'survey_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'set_description' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'num_groups' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'date' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'released' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1)
	);
  var $records = array(
  		array('id' => 1, 'survey_id' => 1, 'set_description' => 'HELLO 1', 'num_groups' => 1, 'date' => 0, 'released' => 0)
  	);
}

