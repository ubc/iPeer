<?php
/**
 * PersonalizeFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class PersonalizeFixture extends CakeTestFixture
{
    public $name = 'Personalize';

    public $import = 'Personalize';

    public $records = array(
        array('id' => 1, 'user_id' => 1, 'attribute_code' => 'code', 'attribute_value' => 'value'),
        array('id' => 2, 'user_id' => 2, 'attribute_code' => 'code2', 'attribute_value' => 'value2'),
    );
}
