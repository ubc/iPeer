<?php

class SimpleEvaluationFixture extends CakeTestFixture {
  var $name = 'SimpleEvaluation';

 var $import = 'SimpleEvaluation';

 

  var $records = array(
    array('id' => 1, 'name' => 'SimpleE1', 'description' => 'descr', 'point_per_member' => 5, 'record_status' => 'A', 'creator_id' => 1 ),
    array('id' => 2, 'name' => 'SimpleE2', 'description' => 'descr1', 'point_per_member' => 10, 'record_status' => 'A' ),
 //   array('id' => 3, 'name' => 'SimpleE3', 'role' => 'S', 'password'=>'password1', 'student_no'=>123),
  );
}
?>