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

    public $import = 'Rubric';

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
