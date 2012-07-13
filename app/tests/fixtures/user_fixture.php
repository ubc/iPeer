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

    public $import = array('model' => 'User', 'records' => true);
}
