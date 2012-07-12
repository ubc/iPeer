<?php
/**
 * GroupFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class GroupFixture extends CakeTestFixture
{
    public $name = 'Group';

    public $import = 'Group';

    public $records = array(
        array('id' => 1, 'group_num' => '1', 'group_name' => 'group1', 'course_id' =>1, 'record_status' => 'A'),
        array('id' => 2, 'group_num' => '2', 'group_name' => 'group2', 'course_id' =>1, 'record_status' => 'A'),
        array('id' => 3, 'group_num' => '3', 'group_name' => 'group3', 'course_id' =>1, 'record_status' => 'A'),
        array('id' => 4, 'group_num' => '4', 'group_name' => 'group4', 'course_id' =>1, 'record_status' => 'A'),
    );
}
