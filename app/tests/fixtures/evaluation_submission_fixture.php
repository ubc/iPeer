<?php
/**
 * EvaluationSubmissionFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationSubmissionFixture extends CakeTestFixture
{
    public $name = 'EvaluationSubmission';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'event_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
        'grp_event_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
        'submitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'submitted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
        'date_submitted' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
        // array('id' => 2, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2010-06-25 00:00:00' ),
        array('id' => 3, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
        array('id' => 4, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00' ),

    );
}

