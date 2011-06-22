<?php

class SurveyGroupMemberFixture extends CakeTestFixture {
  var $name = 'SurveyGroupMember';
  /*var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_set_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);*/
  var $import = 'SurveyGroupMember';
  var $records = array(
  			array('id' => 1, 'group_set_id' => 1, 'group_id' => 1, 'user_id' => 1),
  			array('id' => 2, 'group_set_id' => 1, 'group_id' => 2, 'user_id' => 2)
  	);
}

