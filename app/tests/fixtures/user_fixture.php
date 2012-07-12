<?php
/**
 * UserFixture User Fixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserFixture extends CakeTestFixture
{
    public $name = 'User';

    public $import = 'User';

    public $records = array(
        array('id' => 1, 'username' => 'GSlade', 'role' => 'I', 'first_name' => 'steve', 'last_name' => 'slade'),
        array('id' => 2, 'username' => 'Peterson', 'role' => 'I', 'first_name' => 'sam', 'last_name' => 'peterson'),
        array('id' => 3, 'username' => 'StudentY', 'role' => 'S', 'password'=>'password1', 'student_no'=>123, 'email' => 'email1', 'first_name' => 'TestName', 'last_name' => 'TestLastname'),
        array('id' => 4, 'username' => 'StudentZ', 'role' => 'S', 'password'=>'password1', 'student_no'=>100, 'first_name' => 'name', 'last_name' => 'lastname'),
        array('id' => 5, 'username' => 'INSTRUCTOR1', 'role' => 'I', 'password'=>'password2', 'student_no'=>321),
        array('id' => 6, 'username' => 'INSTRUCTOR2', 'role' => 'I'),
        array('id' => 7, 'username' => 'INSTRUCTOR3', 'role' => 'I', 'student_no'=>0),
        array('id' => 8, 'username' => 'Admin', 'role' => 'A', 'password'=>'passwordA', 'student_no'=>111),
        array('id' => 9, 'username' => 'SuperAdmin', 'role' => 'SA', 'password'=>'passwordA', 'student_no'=>112),
        array('id' => 10, 'username' => 'kevin', 'password' => 'kevin', 'role' => 'S', 'record_status' => 'A', 'creator_id' => 8, 'created' => 0),
        array('id' => 11, 'username' => 'zion', 'password' => 'zion', 'role' => 'S', 'record_status' => 'A', 'creator_id' => 8, 'created' => 0),
    );
}
