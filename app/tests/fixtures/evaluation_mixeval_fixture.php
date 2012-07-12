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

    public $import = 'EvaluationMixeval';

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
