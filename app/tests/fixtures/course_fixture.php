<?php

class CourseFixture extends CakeTestFixture {
  var $name = 'Course';
  var $import = 'Course';

  var $records = array(
    array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process'),
    array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II'),
  );
}
