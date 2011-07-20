<?php
class EvaluationRubricDetailFixture extends CakeTestFixture {
  var $name = 'EvaluationRubricDetail';
  var $import = 'EvaluationRubricDetail';
  
  var $records = array(
  		array('id' => 1, 'evaluation_rubric_id' => 2, 'criteria_number'=>'1', 'criteria_comment'=>'criteria comment1',
  			  'selected_lom' => 0, 'grade' => 10, 'record_status' => 'A'),
  		array('id' => 2, 'evaluation_rubric_id' => 2, 'criteria_number'=>'2', 'criteria_comment'=>'criteria comment2',
  			  'selected_lom' => 1, 'grade' => 5, 'record_status' => 'A'),
  		array('id' => 3, 'evaluation_rubric_id' => 1, 'criteria_number'=>'1', 'criteria_comment'=>'criteria comment1',
  			  'selected_lom' => 0, 'grade' => 10, 'record_status' => 'A'),
  		array('id' => 4, 'evaluation_rubric_id' => 3, 'criteria_number'=>'2', 'criteria_comment'=>'criteria comment2',
  			  'selected_lom' => 1, 'grade' => 5, 'record_status' => 'A'),
  		);
}

