<?php

class MixevalsQuestionDescFixture extends CakeTestFixture {
  public $name = 'MixevalsQuestionDesc';

  public $import = 'MixevalsQuestionDesc';
  
  public $records = array(
  	  array('id' => 1, 'question_id' => 1, 'scale_level' => 0, 'descriptor' => 'HELLO'),
  	  array('id' => 3, 'question_id' => 1, 'scale_level' => 0, 'descriptor' => 'HELLO 1'),
  	  array('id' => 4, 'question_id' => 2, 'scale_level' => 0, 'descriptor' => 'HELLO 2')
  );
}
