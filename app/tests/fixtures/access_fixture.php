<?php
/**
 * AccessFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class AccessFixture extends CakeTestFixture
{
    public $name = 'Access';

    public $table = 'aros_acos';

    public $import = array('table' => 'aros_acos', 'records' => true);
}