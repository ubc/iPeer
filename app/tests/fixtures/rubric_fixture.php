<?php
/**
 * RubricFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricFixture extends CakeTestFixture
{
    public $name = 'Rubric';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'zero_mark' => array('type' => 'boolean', 'null' => false, 'default' => null),
        'lom_max' => array('type' => 'integer', 'null' => true, 'default' => null),
        'criteria' => array('type' => 'integer', 'null' => true, 'default' => null),
        'availability' => array('type' => 'string', 'null' => false, 'default' => 'public', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'template' => array('type' => 'string', 'null' => false, 'default' => 'horizontal', 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array(
            'id' => 4,
            'name' => 'Some Rubric',
            'zero_mark' => 0,
            'lom_max' => 2,
            'criteria' => 2
        )
    );
}
