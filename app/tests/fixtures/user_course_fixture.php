<?php

class UserCourseFixture extends CakeTestFixture {
  var $name = 'UserCourse';
  var $import = 'UserCourse';

  var $records = array(
    array('id' => 1, 'user_id' => 1, 'course_id' => 1),
    array('id' => 2, 'user_id' => 1, 'course_id' => 2),
  );
}


