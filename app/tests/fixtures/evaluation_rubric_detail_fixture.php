<?php
/**
 * EvaluationRubricDetailFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationRubricDetailFixture extends CakeTestFixture
{
    public $name = 'EvaluationRubricDetail';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'evaluation_rubric_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'criteria_number' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'criteria_comment' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'selected_lom' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'grade' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'evaluation_rubric_id' => 2, 'criteria_number'=>'1', 'criteria_comment'=>'criteria comment1',
        'selected_lom' => 0, 'grade' => 10, 'record_status' => 'A'),
        array('id' => 2, 'evaluation_rubric_id' => 2, 'criteria_number'=>'2', 'criteria_comment'=>'criteria comment2',
        'selected_lom' => 1, 'grade' => 5, 'record_status' => 'A'),
        array('id' => 3, 'evaluation_rubric_id' => 1, 'criteria_number'=>'1', 'criteria_comment'=>'criteria comment1',
        'selected_lom' => 0, 'grade' => 10, 'record_status' => 'A'),
        array('id' => 4, 'evaluation_rubric_id' => 3, 'criteria_number'=>'2', 'criteria_comment'=>'criteria comment2',
        'selected_lom' => 1, 'grade' => 5, 'record_status' => 'A'),
    );
}

