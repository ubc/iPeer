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

    public $import = 'RubricsCriteria';

    public $records = array(
        array('id' => 1, 'rubric_id' => 4, 'criteria_num' => 1, 'criteria' => 'CRITERIA 1',
        'multiplier' => 1),
        array('id' => 2, 'rubric_id' => 4, 'criteria_num' => 2, 'criteria' => 'CRITERIA 2',
        'multiplier' => 1)
    );

}
