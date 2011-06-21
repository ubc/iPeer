<?php
class SurveyFixture extends CakeTestFixture {
  var $name = 'Survey';
  var $import = 'Survey'; 

  var $records = array(
    array('id' => 1, 'course_id' => 1, 'user_id' => 3, 'name' => 'Math303 Survey', 'due_date' => '2012-06-16 12:28:0',
          'release_date_begin' => '2011-06-16 12:28:07', 'release_date_end' => '2013-06-16 12:28:07', 'released' => 0,
          'creator_id' => 0, 'created' => '2011-06-16 12:28:07', 'updater_id' => null, 'modified' => null)
    );
}
