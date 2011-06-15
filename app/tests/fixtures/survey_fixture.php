<?php
class SurveyFixture extends CakeTestFixture {
  var $name = 'Survey';
  var $import = 'Survey';

  
  var $records = array(
    array('id' => 1, 'course_id' => 2, 'name' => 'Math321 Survey'),
    array('id' => 2, 'course_id' => 1, 'name' => 'Math320 Survey'),
  );
}