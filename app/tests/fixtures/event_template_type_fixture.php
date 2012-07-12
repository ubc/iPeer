<?php

class EventTemplateTypeFixture extends CakeTestFixture {
  public $name = 'EventTemplateType';

  public $import = 'EventTemplateType';
  
  public $records = array(
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
