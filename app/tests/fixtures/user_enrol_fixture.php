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

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'course_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'course_id' => 1, 'user_id' => 2, 'record_status' => 'A'),
        array('id' => 2, 'course_id' => 1, 'user_id' => 3, 'record_status' => 'A'),
        array('id' => 3, 'course_id' => 1, 'user_id' => 4, 'record_status' => 'A'),
        array('id' => 4, 'course_id' => 2, 'user_id' => 3, 'record_status' => 'A'),
    );

}

