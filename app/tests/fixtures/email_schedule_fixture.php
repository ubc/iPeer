<?php

class EmailScheduleFixture extends CakeTestFixture {
  var $name = 'EmailSchedule';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'content' => array('type' => 'text', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                'date' => array('type' => 'datetime', 'null' => false),
                'from' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                'to' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                'course_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
                'event_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
                'grp_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
                'sent' => array('type' => 'integer', 'null' => false, 'default' => '0'),
                'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

  var $records = array(
    array('id' => 1, 'subject' => 'To send', 'content' => 'This is Test Email', 'date' => '2011-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 0, 'creator_id' => 1, 'created' => '2011-06-10 00:00:00' ),
    array('id' => 2, 'subject' => 'Sent', 'content' => 'This is Test Email', 'date' => '2011-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 1, 'creator_id' => 2, 'created' => '2011-06-10 00:00:00' ),
    array('id' => 3, 'subject' => 'Not Yet', 'content' => 'This is Test Email', 'date' => '2015-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 0, 'creator_id' => 3, 'created' => '2011-06-10 00:00:00' ),
  );

}

?>
