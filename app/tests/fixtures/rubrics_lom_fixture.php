<?php
/**
 * RubricsLomFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsLomFixture extends CakeTestFixture
{
    public $name = 'RubricsLom';

    public $import = 'RubricsLom';

    public $records = array(
        array(
            'id' => 1,
            'rubric_id' => 4,
            'lom_num' => 1,
            'lom_comment' => 'LOM 1'),
        array(
            'id' => 2,
            'rubric_id' => 4,
            'lom_num' => 2,
            'lom_comment' => 'LOM 2'
        )
    );
}
