<?php
/**
 * EvaluationSubmission
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationSubmission extends AppModel
{
    public $name = 'EvaluationSubmission';
    public $actsAs = array('Traceable', 'Containable');

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
        'GroupEvent' => array(
            'className' => 'GroupEvent',
            'foreignKey' => 'grp_event_id',
        ),
    );

    /**
     * getEvalSubmissionsByEventId
     *
     * @param mixed $eventId event id
     *
     * @access public
     * @return array submissions
     */
    function getEvalSubmissionsByEventId($eventId)
    {
        return $this->find('all', array(
            'conditions' => array(
                $this->alias.'.event_id' => $eventId,
                $this->alias.'.submitted' => '1',
            ),
            'contain' => false,
        ));
    }

    /**
     * getEvalSubmissionsByGroupEventId
     *
     * @param mixed $groupEventId
     *
     * @access public
     * @return array submissions
     */
    function getEvalSubmissionsByGroupEventId($groupEventId)
    {     
        return $this->find('all', array(
            'conditions' => array(
                $this->alias.'.grp_event_id' => $groupEventId,
                $this->alias.'.submitted' => '1',
            ),
            'contain' => false,
        ));
    }

    /**
     * getEvalSubmissionByGrpEventIdSubmitter
     *
     * @param bool $grpEventId group event id
     * @param bool $submitter  submitter
     *
     * @access public
     * @return void
     */
    function getEvalSubmissionByGrpEventIdSubmitter($grpEventId, $submitter)
    {
        $findMethod = 'first';
        // if grpEventId is array, there might be multple submissions matching
        if (is_array($grpEventId)) {
            $findMethod = 'all';
        }
        return $this->find($findMethod, array(
            'conditions' => array(
                $this->alias.'.grp_event_id' => $grpEventId,
                $this->alias.'.submitter_id' => $submitter,
                $this->alias.'.submitted' => '1',
            ),
        ));
    }

    /**
     * getEvalSubmissionByEventIdGroupIdSubmitter
     *
     * @param bool $eventId   event id
     * @param bool $groupId   group id
     * @param bool $submitter submitter
     *
     * @access public
     * @return void
     */
    function getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $submitter)
    {
        return $this->find('first', array(
            'conditions' => array(
                $this->alias.'.submitter_id' => $submitter,
                $this->alias.'.submitted' => 1,
                'GroupEvent.group_id' => $groupId,
                'GroupEvent.event_id' => $eventId,
            ),
            'contain' => array('GroupEvent')
        ));
    }

    /**
     * getEvalSubmissionByEventIdSubmitter
     *
     * @param bool $eventId   event id
     * @param bool $submitter submitter
     *
     * @access public
     * @return void
     */
    function getEvalSubmissionByEventIdSubmitter($eventId, $submitter)
    {
        return $this->find('first', array(
            'conditions' => array(
                $this->alias.'.event_id' => $eventId,
                $this->alias.'.submitter_id' => $submitter,
                $this->alias.'.submitted' => 1,
            ),
            'contain' => false,
        ));
    }

    /**
     * numCountInGroupCompleted
     *
     * @param bool $groupEventId group event id
     *
     * @access public
     * @return void
     */
    function numCountInGroupCompleted($groupEventId)
    {
        $members = Set::extract($this->GroupEvent->getGroupMembers($groupEventId), '/GroupsMembers/user_id');
        return $this->find('count', array(
            'conditions' => array(
                $this->alias.'.submitted' => 1,
                $this->alias.'.grp_event_id' => $groupEventId,
                $this->alias.'.submitter_id' => $members,
        )));
    }

    /**
     * daysLate
     *
     * @param mixed $eventId        event id
     * @param mixed $submissionDate submission date
     *
     * @access public
     * @return void
     */
    function daysLate($eventId, $submissionDate)
    {
        $days = 0;
        $dueDate = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId), 'fields' => array('Event.due_date')));
        $dueDate = $dueDate['Event']['due_date'];
        $seconds = strtotime($dueDate) - strtotime($submissionDate);
        $diff = $seconds / 60 /60 /24;
        if ($diff<0) {
            $days = abs(floor($diff));
        }

        return $days;
    }

    /**
     * countSubmissions
     *
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function countSubmissions($grpEventId)
    {
        return $this->find('count', array('conditions' => array('grp_event_id' => $grpEventId,)));
    }
    
    /**
     * getGrpEventIdEvalSub
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function getGrpEventIdEvalSub($userId)
    {
        return $this->find('list', array(
            'conditions' => array('EvaluationSubmission.submitter_id' => $userId, 'EvaluationSubmission.grp_event_id !=' => null),
            'fields' => array('EvaluationSubmission.grp_event_id')
        ));
    }
    
    /**
     * getEventIdSurveySub
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function getEventIdSurveySub($userId)
    {
        return $this->find('list', array(
            'conditions' => array('EvaluationSubmission.submitter_id' => $userId, 'EvaluationSubmission.grp_event_id' => null),
            'fields' => array('EvaluationSubmission.event_id')
        ));
    }
}
