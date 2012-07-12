<?php
/**
 * SysParameterFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SysParameterFixture extends CakeTestFixture
{
    public $name = 'SysParameter';

    public $import = 'SysParameter';

    public $records = array(
        array('id' => 1, 'parameter_code' => 'code1', 'parameter_value' => 'value1', 'parameter_type' => 'S', 'record_status' => 'A' ),
        array('id' => 2, 'parameter_code' => 'code2', 'parameter_value' => 'value2', 'parameter_type' => 'S', 'record_status' => 'A' ),

    );
}
