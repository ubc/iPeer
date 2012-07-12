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
    
    public $import = 'EvaluationSubmission';

    public $records = array(
        array('id' => 1, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
        // array('id' => 2, 'event_id' => 1, 'grp_event_id' => 1, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2010-06-25 00:00:00' ),
        array('id' => 3, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>3, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00'),
        array('id' => 4, 'event_id' => 2, 'grp_event_id' => 3, 'submitter_id' =>4, 'submitted' => 1, 'date_submitted' => '2012-06-25 00:00:00' ),

    );
}

