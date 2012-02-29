<?php
/**
 * EvaluationMixevalDetailFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationMixevalDetailFixture extends CakeTestFixture
{
    public $name = 'EvaluationMixevalDetail';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'evaluation_mixeval_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'question_number' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'question_comment' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'selected_lom' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'grade' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );
    public $records = array(
        array('id' => 1, 'evaluation_mixeval_id' => 1, 'question_number' => 1,
        'question_comment' => 'Q1', 'selected_lom' => 1, 'grade' => 10,
        'record_status' => 'A'),
        array('id' => 2, 'evaluation_mixeval_id' => 1, 'question_number' => 2,
        'question_comment' => 'Q2', 'selected_lom' => 1, 'grade' => 20,
        'record_status' => 'A')
    );
}

