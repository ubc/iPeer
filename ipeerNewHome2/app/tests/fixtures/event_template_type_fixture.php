<?php

class EventTemplateTypeFixture extends CakeTestFixture {
  var $name = 'EventTemplateType';
  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type_name' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'table_name' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model_name' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'display_for_selection' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL)
	);
  var $records = array(
  	array('id' => 1, 'type_name' => 'RUBRIC', 'table_name' => 'rubrics', 'model_name' => 'RUBRIC',
  		  'display_for_selection' => 1, 'record_status' => 'A', 'creator_id' => 1,
  		  'created' => 0, 'updater_id' => null, 'modified' => null),
  	array('id' => 2, 'type_name' => 'SIMPLE', 'table_name' => 'simple_evaluations', 'model_name' => 'SimpleEvaluation',
  		  'display_for_selection' => 0, 'record_status' => 'A', 'creator_id' => 1,
  		  'created' => 0, 'updater_id' => null, 'modified' => null),
  	array('id' => 3, 'type_name' => 'SIMPLE', 'table_name' => 'simple_evaluations', 'model_name' => 'SimpleEvaluation',
  		  'display_for_selection' => 0, 'record_status' => 'A', 'creator_id' => 1,
  		  'created' => 0, 'updater_id' => null, 'modified' => null)
  	);
}
