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

    public $import = 'EvaluationRubricDetail';

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

