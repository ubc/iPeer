<?php

class MixevalFixture extends CakeTestFixture {
  var $name = 'Mixeval';
  var $fields = array(
    'id' => array('type' => 'integer', 'key' => 'primary'),
    'name' => array('type' => 'string'),
    'zero_mark' => array('type' => 'integer'),
    'total_question' => array('type' => 'integer'),
    'lickert_question_max' => array('type' => 'integer'),
    'scale_max' => array('type' => 'integer'),
    'prefill_question_max' => array('type' => 'integer'),
    'availability' => array('type' => 'integer'),
    'creator_id' => array('type' => 'integer'),
    'created' => array('type' => 'datetime'),
    'updater_id' => array('type' => 'integer'),
    'modified' => array('type' => 'datetime')
  );
  
  var $records = array(
  
	array('id' => 2, 'name' => 'Rubric', 'zero_mark' => 0, 'total_question' => 0, 'lickert_question_max' => 0,
		  'scale_max' => 0, 'prefill_question_max' => null, 'availability' => 0, 'creator_id' => 1, 'created' => '0000-00-00 00:00:00',
		  'updater_id' => null, 'modified' => '0000-00-00 00:00:00')
   );
}
