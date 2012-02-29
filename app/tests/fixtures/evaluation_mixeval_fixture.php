<?php
/**
 * EvaluationMixevalFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationMixevalFixture extends CakeTestFixture
{
    public $name = 'EvaluationMixeval';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'evaluator' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'evaluatee' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'score' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
        'comment_release' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
        'grade_release' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
        'grp_event_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'event_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'evaluator' => 2, 'evaluatee' => 1, 'score' => 15, 'comment_release' => 0,
        'grade_release' => 0, 'grp_event_id' => 2, 'event_id' => 1, 'record_status' => 'A',
        'creator_id' => 0, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 2, 'evaluator' => 1, 'evaluatee' => 1, 'score' => 10, 'comment_release' => 0,
        'grade_release' => 0, 'grp_event_id' => 2, 'event_id' => 1, 'record_status' => 'A',
        'creator_id' => 0, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 3, 'evaluator' => 1, 'evaluatee' => 1, 'score' => 10, 'comment_release' => 0,
        'grade_release' => 0, 'grp_event_id' => 1, 'event_id' => 1, 'record_status' => 'A',
        'creator_id' => 0, 'created' => 0, 'updater_id' => null, 'modified' => null)
    );
}
