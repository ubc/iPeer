<?php>
class PenaltyFixture extends CakeTestFixture {
  var $name = 'Penalty';
 // var $import = 'Course';

  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'event_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
      'days_late' => array('type' => 'integer', 'null' => false, 'default' => NULL),
      'percent_penalty' => array('type' => 'integer', 'null' => false, 'default' => NULL),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'fk_events' => array('column' => 'event_id', 'unique' => 0)),
      'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
  );

  var $records = array(
  );
}
