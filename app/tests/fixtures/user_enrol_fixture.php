<?php
/**
 * UserEnrolFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserEnrolFixture extends CakeTestFixture
{
    public $name = 'UserEnrol';

    public $import = 'UserEnrol';

    public $records = array(
        array('id' => 1, 'course_id' => 1, 'user_id' => 2, 'record_status' => 'A'),
        array('id' => 2, 'course_id' => 1, 'user_id' => 3, 'record_status' => 'A'),
        array('id' => 3, 'course_id' => 1, 'user_id' => 4, 'record_status' => 'A'),
        array('id' => 4, 'course_id' => 2, 'user_id' => 3, 'record_status' => 'A'),
    );

}

