<?php
/**
 * MixevalFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalFixture extends CakeTestFixture
{
    public $name = 'Mixeval';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'zero_mark' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
        'scale_max' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'availability' => array('type' => 'string', 'null' => false, 'default' => 'public', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'name' => 'Mixeval', 'zero_mark' => 0,
        'scale_max' => 0, 'availability' => 1, 'creator_id' => 1, 'created' => '0000-00-00 00:00:00',
        'updater_id' => null, 'modified' => '0000-00-00 00:00:00')
    );
}
