<?php
/**
 * RoleFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RoleFixture extends CakeTestFixture
{
    public $name = 'Role';

    public $import = 'Role';

    public $records = array(
        array('id' => 1, 'name' => 'superadmin'),
        array('id' => 2, 'name' => 'admin'),
        array('id' => 3, 'name' => 'instructor'),
        array('id' => 4, 'name' => 'tutor'),
        array('id' => 5, 'name' => 'student'),

    );

}

