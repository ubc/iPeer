<?php

class QuestionFixture extends CakeTestFixture {
  var $name = 'Question';
  var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'prompt' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
  		'type' => array('type' => 'string')
	);
  var $records = array(
  		array('id' => 1, 'prompt' => 'Did you learn a lot from this course ?', 'type' => 'M'),
  		array('id' => 2, 'prompt' => 'What was the hardest part ?', 'type' => 'M')
  );
}

