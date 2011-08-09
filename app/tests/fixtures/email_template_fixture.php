<?php

class EmailTemplateFixture extends CakeTestFixture {
  var $name = 'EmailTemplate';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
                'name' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'subject' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                'description' => array('type' => 'text', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'content' => array('type' => 'text', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                'availability' => array('type' => 'integer', 'null' => false, 'default' => '0'),
                'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
                'updater_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

  var $records = array(
    array('id' => 1, 'name' => 'Test Template for 1', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '1', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00' ),
    array('id' => 2, 'name' => 'Test Template for 2', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '2', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00' ),
    array('id' => 3, 'name' => 'Test Template public', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '1', 'creator_id' => '2', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00' ),
  );

}

?>

