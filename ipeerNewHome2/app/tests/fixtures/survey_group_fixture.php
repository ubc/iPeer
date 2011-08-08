<?php

class SurveyGroupFixture extends CakeTestFixture {
  var $name = 'SurveyGroup';
  /*var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'survey_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'set_description' => array('type' => 'integer', 'null' => false, 'default' => '0'),
  		'num_groups' => array('type' => 'integer'),
		'date' => array('type' => 'date'),
		'released' => array('type' => 'date')
	);*/
  var $import = 'SurveyGroup';
  var $records = array(
  		array('id' => 1, 'group_set_id' => 1, 'group_number' => 1)
  	);
}

