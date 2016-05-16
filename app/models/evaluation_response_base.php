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
     * @param mixed $groupEventId       group event id
     * @param mixed $include_partial    include partial submissions
     * @param mixed $contain            contain
     *
     * @access public
     * @return void
     */
    public function getSubmittedResultsByGroupEvent($groupEventId, $include_partial = false, $contain = false)
    {
        // find all submissions
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $submissions = $this->EvaluationSubmission->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'submitted' => 1),
            'contain' => false
        ));
        
        if($include_partial) {
            // get partial and compelted evalutation data for all group members 
            $conditions = array('grp_event_id' => $groupEventId);
        } else {
            // get evalutation dataonly for group members who have submitted the evalutations
            $conditions = array();
            foreach ($submissions as $submission) {
                $submission = $submission['EvaluationSubmission'];
                $conditions[] = sprintf('(grp_event_id = %d AND evaluator = %d)', $submission['grp_event_id'], $submission['submitter_id']);
            }
            
            $conditions = array(join(' OR ', $conditions));
        }
        
        $responses = array();
        /*
         * Note: The reason for using paging for results even though we are retrieving everthing is because in cakephp 1.3 associated are fetched 
         * using the list of result ids in a where id in (list of ids). This is very bad for MySQL performance with very large lists which can happen
         * in larger classes. 100 items seems to be a good balance based on development enviroment testing.$_COOKIE
         * See cake/libs/model/datasources/dbo_source.php functions queryAssociation and fetchAssociated 
        */
        // chunk find
        $total = $this->find('count', array('conditions' => $conditions, 'contain' => $contain));
        $limit = 100;
        $pages = ceil($total / $limit);
        for ($page = 1; $page < $pages + 1; $page++) {
            $list = $this->find('all', array('conditions' => $conditions, 'contain' => $contain, 'limit' => $limit, 'page' => $page));
            
            $responses = array_merge_recursive($responses, $list);
        }
        // cleanup
        unset($list);
        
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
