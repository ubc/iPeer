<?php
/**
 * GroupsMemberFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class GroupsMemberFixture extends CakeTestFixture
{
    public $name = 'GroupsMember';

    public $import = 'GroupsMember';

    public $records = array(
        array('id' => 1, 'group_id' => '1', 'user_id' => '3'),
        array('id' => 2, 'group_id' => '1', 'user_id' => '4'),
        array('id' => 3, 'group_id' => '2', 'user_id' => '2'),
        array('id' => 4, 'group_id' => '2', 'user_id' => '3'),
        array('id' => 5, 'group_id' => '2', 'user_id' => '4'),
        array('id' => 6, 'group_id' => '4', 'user_id' => '4'),
    );
}
