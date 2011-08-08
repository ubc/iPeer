<?php

class ResponseFixture extends CakeTestFixture {
  var $name = 'Response';

  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'question_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'response' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
	);

  var $records = array(
  		array('id' => 1, 'question_id' => 1, 'response' => 'YES FOR Q1'),
  		array('id' => 5, 'question_id' => 1, 'response' => 'NO FOR Q1'),
  		array('id' => 2, 'question_id' => 2, 'response' => 'NO FOR Q2'),
  		array('id' => 3, 'question_id' => 6, 'response' => 'YES FOR Q3'),
  		array('id' => 4, 'question_id' => 4, 'response' => 'YES FOR Q4')
  	);
}


