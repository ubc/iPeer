<?php

class MixevalsQuestionDescFixture extends CakeTestFixture {
  var $name = 'MixevalsQuestionDesc';
  var $fields = array(
  	  'id' => array('type' => 'integer', 'key' => 'primary'),
	  'question_id' => array('type' => 'integer'),
  	  'scale_level' => array('type' => 'integer'),
  	  'descriptor' => array('type' => 'string')
  );
  
  var $records = array(
  	  array('id' => 1, 'question_id' => 1, 'scale_level' => 0, 'descriptor' => 'HELLO'),
  	  array('id' => 2, 'question_id' => 5, 'scale_level' => 0, 'descriptor' => 'HELLO 1'),
  	  array('id' => 3, 'question_id' => 6, 'scale_level' => 0, 'descriptor' => 'HELLO 2')
  );
}
