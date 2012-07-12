<?php
/**
 * MixevalsQuestionFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalsQuestionFixture extends CakeTestFixture
{
    public $name = 'MixevalsQuestion';

    public $import = 'MixevalsQuestion';

    public $records = array(
        array('id' => 1, 'mixeval_id' => 1, 'question_num' => 0, 'title' => '1st Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null),
        array('id' => 2, 'mixeval_id' => 1, 'question_num' => 1, 'title' => '2nd Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null),
        array('id' => 3, 'mixeval_id' => 1, 'question_num' => 2, 'title' => '3rd Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null)
    );

}
