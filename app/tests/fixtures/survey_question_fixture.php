<?php

class SurveyQuestionFixture extends CakeTestFixture {
  var $name = 'SurveyQuestion';
  var $import = 'SurveyQuestion';
  var $records = array(
  		array('id' => 1, 'survey_id' => 1, 'number' => 1, 'question_id' => 1),
  		array('id' => 2, 'survey_id' => 1, 'number' => 2, 'question_id' => 2),
  		array('id' => 6, 'survey_id' => 1, 'number' => 3, 'question_id' => 6),
  		array('id' => 3, 'survey_id' => 2, 'number' => 9999, 'question_id' => 3),
  		array('id' => 4, 'survey_id' => 2, 'number' => 9999, 'question_id' => 4),
  		array('id' => 5, 'survey_id' => 2, 'number' => 9999, 'question_id' => 5)
  		);
}