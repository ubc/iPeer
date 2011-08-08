<?php

class SurveyInputFixture extends CakeTestFixture {
  var $name = 'SurveyInput';
  var $import = 'SurveyInput';
  
  var $records = array(
  		array('id' => 1, 'survey_id' => 1, 'user_id' => 1, 'question_id' => 1),
  		array('id' => 2, 'survey_id' => 2, 'user_id' => 2, 'question_id' => 2),
  		array('id' => 3, 'survey_id' => 1, 'user_id' => 3, 'question_id' => 2),
  		array('id' => 4, 'survey_id' => 1, 'user_id' => 4, 'question_id' => 2)
  	);
}