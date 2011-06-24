<?php
class EvaluationMixevalDetailFixture extends CakeTestFixture {
  var $name = 'EvaluationMixevalDetail';
  var $import = 'EvaluationMixevalDetail';
  
  var $records = array(
  		array('id' => 1, 'evaluation_mixeval_id' => 1, 'question_number' => 1,
  			  'question_comment' => 'Q1', 'selected_lom' => 1, 'grade' => 10,
  			  'record_status' => 'A'),
  		array('id' => 2, 'evaluation_mixeval_id' => 1, 'question_number' => 2,
  			  'question_comment' => 'Q2', 'selected_lom' => 1, 'grade' => 20,
  			  'record_status' => 'A')
  		);
}

