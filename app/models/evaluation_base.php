<?php

/**
 * EvaluationBase
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationBase extends AppModel
{
    public static $types = array(1 => 'SimpleEvaluation', 2 => 'Rubric', 4 => 'Mixeval');
    public $name = 'EvaluationBase';
    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');
    public $useTable = false;
    // suppress the warning when using "cake schema generate"
    const TEMPLATE_TYPE_ID = 0;

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $c = get_class($this);
        $this->virtualFields['event_count'] = sprintf('SELECT count(*) as count FROM events as event WHERE event.event_template_type_id = %d AND event.template_id = %s.id', constant($c.'::TEMPLATE_TYPE_ID'), $this->alias);
    }


    /**
     * beforeSave
     *
     *
     * @access public
     * @return void
     */
    function beforeSave($options = array())
    {
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['name'])) {
           $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
           return false;
        }

        // Remove any signle quotes in the name, so that custom SQL queries are not confused.
        $this->data[$this->name]['name'] =
            str_replace("'", "", $this->data[$this->name]['name']);

        //check the duplicate name
        // Removed for enhancement #516 - "Allow duplicate event title"
        /*
        if (empty($this->data[$this->name]['id']) && !$this->__checkDuplicateName()) {
            return false;
        }
        */
        
        //check if questions are entered
        if (!empty($this->data['Question'])&&$this->name =='Mixeval') {
            foreach ($this->data['Question'] as $row) {
                if ($row['mixeval_question_type_id']== '1' &&(empty($row['Description'] ) || (count($row['Description'])) < 2)) {
                    $this->errorMessage = "Please add at least two descriptors for each of the Lickert questions.";
                    return false;
                }
            }
        }

        if (empty($this->data['Question'])&&($this->name =='Mixeval')) {
            $this->errorMessage = "Please add at least one question for this mixed evaluation.";
            return false;
        }
        return parent::beforeSave();
    }


    /**
     * __checkDuplicateName
     * Validation check on duplication of name
     *
     *
     * @access protected
     * @return void
     */
    function __checkDuplicateName()
    {
        $result = $this->find('first', array('conditions' => array('name' => $this->data[$this->name]['name'])));
        if ($result) {
            $this->errorMessage='Duplicate name found. Please change the name.';
            return false;
        }

        return true;
    }



    /**
     * getBelongingOrPublic
     * Returns the evaluations made by this user, and any other public ones.
     * Optionally include an additional evaluation by id that bypasses ownership and public restrictions.
     *
     * @param mixed $user_id
     * @param mixed $include_id
     *
     * @access public
     * @return void
     */
    function getBelongingOrPublic($user_id, $include_id=NULL)
    {
        if (!is_numeric($user_id)) {
            return false;
        }

        if (!is_null($include_id) && !is_numeric($include_id)) {
            return false;
        }

        $conditions = array(
            'creator_id' => $user_id,
            'availability' => 'public',
        );
        if (!is_null($include_id)) {
            $conditions['id'] = $include_id;
        }
        $conditions = array('OR' => $conditions);
        return $this->find('list', array('conditions' => $conditions, 'fields' => array('name'), 'order' => 'name ASC'));
    }


    /**
     * getEventCount
     *
     * @param mixed $evaluation_id
     *
     * @access public
     * @return void
     */
    function getEventCount($evaluation_id)
    {
        $eval = $this->read('event_count', $evaluation_id);
        return $eval[$this->alias]['event_count'];
    }

    /**
     * formatPenaltyArray return the array that student has penalty. key will
     * be the user id and value will be the penalty. The student without
     * penalty will be value 0.
     *
     * @param mixed $eventId      event id
     * @param mixed $groupId      group id
     * @param mixed $groupMembers group members
     *
     * @access public
     * @return array
     */
    function formatPenaltyArray($eventId, $groupId, $groupMembers)
    {
        $this->Penalty = ClassRegistry::init('Penalty');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');

        $memberIds = array_keys($groupMembers);

        // find the event
        $event = $this->Event->findById($eventId);

        // not due yet. no penalty
        if ($event['Event']['due_in'] >= 0) {
            return array_fill_keys($memberIds, 0);
        }

        // assign penalty to groupMember if they submitted late or never submitted by release_date_end
        $conditions = array(
            'EvaluationSubmission.event_id' => $eventId,
            'GroupEvent.group_id' => $groupId,
            'submitter_id' => $memberIds,
        );
        $submissions = $this->EvaluationSubmission->find('all', array('conditions' => $conditions));

        return $this->Penalty->getPenaltyForMembers($memberIds, $event['Event'], $submissions);
    }
    
    /**
     * get Event and Submission of Eval
     *
     * @param mixed $evalId eval id
     *
     * @access public
     * @return array
     */
    function getEventSub($evalId) {
        return $this->find('first', array(
            'conditions' => array('id' => $evalId),
            'contain' => array('Event' => 'EvaluationSubmission')
        ));
    }
}
