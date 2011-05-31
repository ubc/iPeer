<?php

class SurveyQuestionFixture extends CakeTestFixture {
  var $name = 'SurveyQuestion';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'survey_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'number' => array('type' => 'integer', 'null' => false, 'default' => '9999'),
		'question_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'question_id' => array('column' => 'question_id', 'unique' => 0), 'survey_id' => array('column' => 'survey_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
}


