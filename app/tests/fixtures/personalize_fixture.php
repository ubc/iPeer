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

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'attribute_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'attribute_value' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'user_id' => 1, 'attribute_code' => 'code', 'attribute_value' => 'value'),
        array('id' => 2, 'user_id' => 2, 'attribute_code' => 'code2', 'attribute_value' => 'value2'),
    );
}
