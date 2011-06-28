<?php
class EvaluationSimpleFixture extends CakeTestFixture {
  var $name = 'EvaluationSimple';
  var $import = 'EvaluationSimple';
  
  var $records = array(
  		array('id' => 1, 'evaluator' => 3, 'evaluatee' => 4, 'eval_comment' => 'eval comment1', 'score' => 15, 'release_status' => 0, 'grade_release' => 1,
  			  'grp_event_id' => 3, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 2, 'evaluator' => 4, 'evaluatee' => 3, 'eval_comment' => 'eval comment2', 'score' => 5, 'release_status' => 1, 'grade_release' => 0,
  			  'grp_event_id' => 3, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 3, 'evaluator' => 4, 'evaluatee' => 3, 'eval_comment' => 'eval comment3', 'score' => 10, 'release_status' => 0, 'grade_release' => 1,
  			  'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 4, 'evaluator' => 5, 'evaluatee' => 3, 'eval_comment' => 'eval comment4', 'score' => 5, 'release_status' => 1, 'grade_release' => 0,
  			  'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
   		array('id' => 5, 'evaluator' => 6, 'evaluatee' => 3, 'eval_comment' => 'eval comment5', 'score' => 15, 'release_status' => 0, 'grade_release' => 1,
  			  'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
     		
  );
}

