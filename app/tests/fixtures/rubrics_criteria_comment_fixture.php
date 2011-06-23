<?php

class RubricsCriteriaCommentFixture extends CakeTestFixture {
  var $name = 'RubricsCriteriaComment';
  var $import = 'RubricsCriteriaComment';
  
  var $records = array(
		array('id' => 1, 'criteria_id' => 1, 'rubrics_loms_id' => 1,
			  'criteria_comment' => 'HELLO 11'),
		array('id' => 2, 'criteria_id' => 1, 'rubrics_loms_id' => 2,
			  'criteria_comment' => 'HELLO 12'),
		array('id' => 3, 'criteria_id' => 2, 'rubrics_loms_id' => 1,
			  'criteria_comment' => 'HELLO 21'),
		array('id' => 4, 'criteria_id' => 2, 'rubrics_loms_id' => 2,
			  'criteria_comment' => 'HELLO 22')
		);
}
