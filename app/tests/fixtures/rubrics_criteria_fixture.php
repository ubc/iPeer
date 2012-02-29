<?php
/**
 * RubricsCriteriaFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsCriteriaFixture extends CakeTestFixture
{
    public $name = 'RubricsCriteria';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'rubric_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'criteria_num' => array('type' => 'integer', 'null' => false, 'default' => '999'),
        'criteria' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'multiplier' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    );

    public $records = array(
        array('id' => 1, 'rubric_id' => 4, 'criteria_num' => 1, 'criteria' => 'CRITERIA 1',
        'multiplier' => 1),
        array('id' => 2, 'rubric_id' => 4, 'criteria_num' => 2, 'criteria' => 'CRITERIA 2',
        'multiplier' => 1)
    );

}
