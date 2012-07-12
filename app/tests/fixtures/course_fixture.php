<?php
/**
 * CourseFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CourseFixture extends CakeTestFixture
{
    public $name = 'Course';

    public $import = 'Course';

    public $records = array(
        array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process', 'homepage' => null, 'self_enroll' => null, 'password' => null, 'record_status' => null, 'creator_id' => null, 'created' => null, 'updater_id' => null, 'modified' => null, 'instructor_id' => null),
        array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II', 'homepage' => null, 'self_enroll' => null, 'password' => null, 'record_status' => null, 'creator_id' => null, 'created' => null, 'updater_id' => null, 'modified' => null, 'instructor_id' => null),
        array('id' => 3, 'course' => 'Math100', 'title' => 'Math', 'homepage' => null, 'self_enroll' => null, 'password' => null, 'record_status' => null, 'creator_id' => null, 'created' => null, 'updater_id' => null, 'modified' => null, 'instructor_id' => null),
        array('id' => 4, 'course' => 'Math200', 'title' => 'Math II', 'homepage' => null, 'self_enroll' => null, 'password' => null, 'record_status' => null, 'creator_id' => null, 'created' => null, 'updater_id' => null, 'modified' => null, 'instructor_id' => null),
        array('id' => 5, 'course' => 'Math250', 'title' => 'Math III', 'homepage' => null, 'self_enroll' => null, 'password' => null, 'record_status' => null, 'creator_id' => null, 'created' => null, 'updater_id' => null, 'modified' => null, 'instructor_id' => null),
        array('id' => 6, 'course' => 'InactiveCourse1', 'title' => 'InactiveCourse1', 'record_status' => 'I'),
        array('id' => 7, 'course' => 'InactiveCourse2', 'title' => 'InactiveCourse2', 'record_status' => 'I'),
    );
}
