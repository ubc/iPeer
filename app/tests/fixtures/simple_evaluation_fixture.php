<?php
/**
 * SimpleEvaluationFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SimpleEvaluationFixture extends CakeTestFixture
{
    public $name = 'SimpleEvaluation';

    public $import = 'SimpleEvaluation';

    public $records = array(
        array('id' => 1, 'name' => 'SimpleE1', 'description' => 'descr', 'point_per_member' => 5, 'record_status' => 'A', 'creator_id' => 1 ),
        array('id' => 2, 'name' => 'SimpleE2', 'description' => 'descr1', 'point_per_member' => 10, 'record_status' => 'A' ),
        //   array('id' => 3, 'name' => 'SimpleE3', 'role' => 'S', 'password'=>'password1', 'student_no'=>123),
    );
}
