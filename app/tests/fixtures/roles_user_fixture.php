<?php
/**
 * RolesUserFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RolesUserFixture extends CakeTestFixture
{
    public $name = 'RolesUser';

    public $import = 'RolesUser';

    public $records = array(
        array('id' => 1, 'role_id' => '3', 'user_id' => 1),
        array('id' => 2, 'role_id' => '3', 'user_id' => 2),
        array('id' => 3, 'role_id' => '5', 'user_id' => 3),
        array('id' => 4, 'role_id' => '5', 'user_id' => 4),
        array('id' => 5, 'role_id' => '3', 'user_id' => 5),
        array('id' => 6, 'role_id' => '3', 'user_id' => 6),
        array('id' => 7, 'role_id' => '3', 'user_id' => 7),
        array('id' => 8, 'role_id' => '1', 'user_id' => 8),
        array('id' => 9, 'role_id' => '2', 'user_id' => 9),
        array('id' => 10, 'role_id' => '5', 'user_id' => 10),
        array('id' => 11, 'role_id' => '5', 'user_id' => 11),
    );
}
