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

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'mixeval_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'question_num' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'title' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'instructions' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'question_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'required' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
        'multiplier' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'scale_level' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'response_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
    );

    public $records = array(
        array('id' => 1, 'mixeval_id' => 1, 'question_num' => 0, 'title' => '1st Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null),
        array('id' => 2, 'mixeval_id' => 1, 'question_num' => 1, 'title' => '2nd Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null),
        array('id' => 3, 'mixeval_id' => 1, 'question_num' => 2, 'title' => '3rd Question', 'instructions' => null,
        'question_type' => 'S', 'required' => 1, 'multiplier' => 15, 'scale_level' => 0, 'response_type' => null)
    );

}
