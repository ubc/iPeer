<?php

class ResponseFixture extends CakeTestFixture {
  public $name = 'Response';

  public $import = 'Response';

  public $records = array(
  		array('id' => 1, 'question_id' => 1, 'response' => 'YES FOR Q1'),
  		array('id' => 5, 'question_id' => 1, 'response' => 'NO FOR Q1'),
  		array('id' => 2, 'question_id' => 2, 'response' => 'NO FOR Q2'),
  		array('id' => 3, 'question_id' => 6, 'response' => 'YES FOR Q3'),
  		array('id' => 4, 'question_id' => 4, 'response' => 'YES FOR Q4')
  	);
}


