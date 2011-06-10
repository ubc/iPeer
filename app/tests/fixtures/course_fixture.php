<?php

class CourseFixture extends CakeTestFixture {
  var $name = 'Course';
  var $import = 'Course';

  var $records = array(
    array('id' => 1, 'course' => 'Math320', 'title' => 'Stochastic Process', 'instructor_id' => 1),
    array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II', 'instructor_id' => 1),
  );
}
