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

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'point_per_member' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'name' => 'SimpleE1', 'description' => 'descr', 'point_per_member' => 5, 'record_status' => 'A', 'creator_id' => 1 ),
        array('id' => 2, 'name' => 'SimpleE2', 'description' => 'descr1', 'point_per_member' => 10, 'record_status' => 'A' ),
        //   array('id' => 3, 'name' => 'SimpleE3', 'role' => 'S', 'password'=>'password1', 'student_no'=>123),
    );
}
