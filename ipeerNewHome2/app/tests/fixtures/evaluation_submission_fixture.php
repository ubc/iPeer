<?php

class EvaluationSubmissionFixture extends CakeTestFixture {
  var $name = 'EvaluationSubmission';
  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'event_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'grp_event_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'submitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'submitted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'date_submitted' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'grp_event_id' => array('column' => array('grp_event_id', 'submitter_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	
  var $records = array(
  array('id' => 1, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
 // array('id' => 2, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2010-06-25 00:00:00' ),
  array('id' => 3, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
  array('id' => 4, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00' ),
  
  );
}

