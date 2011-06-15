<?php

class CourseFixture extends CakeTestFixture {
  var $name = 'Course';
 // var $import = 'Course';

  var $fields = array(
    'id' => array('type' => 'integer', 'key' => 'primary'),
    'course' => array('type' => 'string', 'length' => 80, 'null' => false),
    'title' => array('type' => 'string', 'length' => 80, 'null' => true,  'default' => null, 'collate' => 'latin1_swedish_ci'),
    'homepage' => array('type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci'),
    'self_enroll' => array('type' => 'string', 'length' => 3, 'null' => true, 'default' => 'off', 'collate' => 'latin1_swedish_ci'),
    'password' => array('type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci'),
    'record_status' => array('type' => 'string', 'length' => 1, 'null' => false, 'default' => 'A', 'collate' => 'latin1_swedish_ci'),
    'creator_id' => array('type' => 'integer', 'length' => 11, 'null' => false, 'default' => 0),
    'created' => array('type' => 'datetime','null' => false, 'default' => '0000-00-00 00:00:00'),
    'updater_id' => array('type' => 'integer', 'length' => 11, 'null' => true, 'default' => null),
    'modified' => array('type' => 'datetime','null' => true, 'default' => null),
    'instructor_id' => array('type' => 'integer', 'length' => 11, 'null' => false, 'default' => 0)
  ); 
  
  
  var $records = array(

    array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process', 'instructor_id' => 1),
    array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II', 'instructor_id' => 1),
    array('id' => 3, 'course' => 'Math100', 'title' => 'Math'),
    array('id' => 4, 'course' => 'Math200', 'title' => 'Math II', 'instructor_id' => 2),
    array('id' => 5, 'course' => 'Math250', 'title' => 'Math III'),
    array('id' => 6, 'course' => 'InactiveCourse1', 'title' => 'InactiveCourse1', 'record_status' => 'I'),
    array('id' => 7, 'course' => 'InactiveCourse2', 'title' => 'InactiveCourse2', 'record_status' => 'I'),

  );
}
