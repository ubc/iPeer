<?php
/**
 * EvaluationResponseBase
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationResponseBase extends AppModel
{
    public static $types = array(1 => 'EvaluationSimple', 2 => 'EvaluationRubric', 4 => 'EvaluationMixeval');
    public $name = 'EvaluationResponseBase';
    public $useTable = false;
    public $actsAs = array('Containable', 'Traceable');

    /**
     * getSubmittedResultsByGroupEvent
     * Return already submitted responses with submission information
     *
     * @param mixed $groupEventId group event id
     * @param mixed $contain      contain
     *
     * @access public
     * @return void
     */
    public function getSubmittedResultsByGroupEvent($groupEventId, $contain = false)
    {
        // find all submissions
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $submissions = $this->EvaluationSubmission->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'submitted' => 1),
            'contain' => false
        ));

        // build conditions
        $conditions = array();
        foreach ($submissions as $submission) {
            $submission = $submission['EvaluationSubmission'];
            $conditions[] = sprintf('(grp_event_id = %d AND evaluator = %d)', $submission['grp_event_id'], $submission['submitter_id']);
        }

        $responses = $this->find('all', array('conditions' => array(join(' OR ', $conditions)), 'contain' => $contain));
        // combine submission information into responses;
        foreach ($responses as $key => $response) {
            foreach ($submissions as $submission) {
                if ($response[$this->name]['grp_event_id'] == $submission['EvaluationSubmission']['grp_event_id'] &&
                    $response[$this->name]['evaluator'] == $submission['EvaluationSubmission']['submitter_id']) {
                        $responses[$key]['EvaluationSubmission'] = $submission['EvaluationSubmission'];
                }
            }

        }

        return $responses;
    }

    /**
     * getSubmittedResultsByGroupIdEventIdAndEvaluator
     *
     * @param mixed $groupId   group id
     * @param mixed $eventId   event id
     * @param mixed $evaluator evaluator id
     * @param mixed $contain   contain array
     *
     * @access public
     * @return void
     */
    public function getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, $evaluator, $contain = false)
    {
        // get the group event id first
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $groupEvent = $this->GroupEvent->find('first', array(
            'conditions' => array('group_id' => $groupId, 'event_id' => $eventId),
            'fields' => array('id'),
            'contain' => false,
        ));
        if (empty($groupEvent)) {
            return false;
        }

        return $this->find('all', array(
            'conditions' => array('grp_event_id' => $groupEvent['GroupEvent']['id'], 'evaluator' => $evaluator),
            'contain' => $contain,
        ));
    }
}
