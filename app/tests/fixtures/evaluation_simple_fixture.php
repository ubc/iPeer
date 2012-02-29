<?php
/**
 * EvaluationSimpleFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationSimpleFixture extends CakeTestFixture
{
    public $name = 'EvaluationSimple';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'evaluator' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'evaluatee' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'score' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
        'eval_comment' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'release_status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
        'grp_event_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'event_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'date_submitted' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'grade_release' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'evaluator' => 3, 'evaluatee' => 4, 'eval_comment' => 'eval comment1', 'score' => 15, 'release_status' => 0, 'grade_release' => 1,
        'grp_event_id' => 3, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 2, 'evaluator' => 4, 'evaluatee' => 3, 'eval_comment' => 'eval comment2', 'score' => 5, 'release_status' => 1, 'grade_release' => 0,
        'grp_event_id' => 3, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 3, 'evaluator' => 4, 'evaluatee' => 3, 'eval_comment' => 'eval comment3', 'score' => 10, 'release_status' => 0, 'grade_release' => 1,
        'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 4, 'evaluator' => 5, 'evaluatee' => 3, 'eval_comment' => 'eval comment4', 'score' => 5, 'release_status' => 1, 'grade_release' => 0,
        'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),
        array('id' => 5, 'evaluator' => 6, 'evaluatee' => 3, 'eval_comment' => 'eval comment5', 'score' => 15, 'release_status' => 0, 'grade_release' => 1,
        'grp_event_id' => 4, 'event_id' => 2, 'record_status' => 'A', 'creator_id' => 1, 'created' => 0, 'updater_id' => null, 'modified' => null),

    );
}

