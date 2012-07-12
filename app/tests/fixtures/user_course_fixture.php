<?php
/**
 * UserCourseFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserCourseFixture extends CakeTestFixture {
    public $name = 'UserCourse';

    public $import = 'UserCourse';

    public $records = array(
        array('id' => 1, 'user_id' => 1, 'course_id' => 1),
        array('id' => 2, 'user_id' => 1, 'course_id' => 2),
        array('id' => 3, 'user_id' => 2, 'course_id' => 3),
        array('id' => 4, 'user_id' => 2, 'course_id' => 1),

    );
}


