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
  		array('id' => 2, 'prompt' => 'What was the hardest part ?', 'type' => 'M'),
  		array('id' => 3, 'prompt' => 'First Question', 'type' => 'M'),
  		array('id' => 4, 'prompt' => 'Second Question', 'type' => 'M'),
  		array('id' => 5, 'prompt' => 'Third Question', 'type' => 'M'),
  		array('id' => 6, 'prompt' => 'Did u like the prof ?', 'type' => 'A')
  	);
}

