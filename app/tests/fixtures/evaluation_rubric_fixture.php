<?php
class EvaluationRubricFixture extends CakeTestFixture {
  var $name = 'EvaluationRubric';
  var $import = 'EvaluationRubric';
  
  var $records = array(
  		array('id' => 1, 'evaluator' => 3, 'evaluatee' => 4, 'general_comment' => 'general comment1', 'score' => 15, 'comment_release' => 0,
  			  'grade_release' => 0, 'grp_event_id' => 1, 'event_id' => 1, 'record_status' => 'A',
  			  'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 2, 'evaluator' => 4, 'evaluatee' => 3, 'general_comment' => 'general comment2', 'score' => 10, 'comment_release' => 0,
  			  'grade_release' => 0, 'grp_event_id' => 2, 'event_id' => 2, 'record_status' => 'A',
  			  'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 3, 'evaluator' => 4, 'evaluatee' => 3, 'general_comment' => 'general comment3','score' => 10, 'comment_release' => 1,
  			  'grade_release' => 1, 'grp_event_id' => 1, 'event_id' => 1, 'record_status' => 'A',
  			  'creator_id' => 0, 'created' => 0, 'updater_id' => null, 'modified' => null)
  		
  );
}

