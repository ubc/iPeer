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
    public $actsAs = array('Traceable');

    public $belongsTo = array(
        'Event' =>
        array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );

    /**
     * getEvalSubmissionByGrpEventIdSubmitter
     *
     * @param bool $grpEventId group event id
     * @param bool $submitter  submitter
     *
     * @access public
     * @return void
     */
    function getEvalSubmissionByGrpEventIdSubmitter($grpEventId=null, $submitter=null)
    {
        //return $this->find('grp_event_id='.$grpEventId.' AND submitter_id='.$submitter);
        return $this->find('first', array(
            'conditions' => array('EvaluationSubmission.grp_event_id' => $grpEventId, 'EvaluationSubmission.submitter_id' => $submitter)
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
    function getEvalSubmissionByEventIdSubmitter($eventId=null, $submitter=null)
    {
        //return $this->find('event_id='.$eventId.' AND submitter_id='.$submitter);
        return $this->find('first', array(
            'conditions' => array('event_id' => $eventId, 'submitter_id' => $submitter)
        ));
    }


    /**
     * numInGroupCompleted
     * check if an entire group has completed all the evaluations
     * for a particular assignment
     *
     * @param bool $groupId      group id
     * @param bool $groupEventId group event id
     *
     * @access public
     * @return void
     */
    function numInGroupCompleted($groupId=null, $groupEventId=null)
    {
        //        $condition = 'EvaluationSubmission.submitted = 1 AND GroupMember.group_id='.$groupId.' AND EvaluationSubmission.grp_event_id='.$groupEventId;
        //        $fields = 'GroupMember.user_id, EvaluationSubmission.submitter_id, EvaluationSubmission.submitted';
        //        $joinTable = array(' LEFT JOIN groups_members as GroupMember ON GroupMember.user_id=EvaluationSubmission.submitter_id');
        //
        //        return $this->find('all', $condition, $fields, null, null, null, null, $joinTable );
        return $this->find('all', array(
            'conditions' => array('EvaluationSubmission.submitted' => 1, 'GroupMember.group_id' => $groupId, 'EvaluationSubmission.grp_event_id' => $groupEventId),
            'fields' => array('GroupMember.user_id', 'EvaluationSubmission.submitter_id', 'EvaluationSubmission.submitted'),
            'joins' => array(
                array(
                    'table' => 'groups_members',
                    'alias' => 'GroupMember',
                    'type' => 'LEFT',
                    'conditions' => array('GroupMember.user_id = EvaluationSubmission.submitter_id')
                )
            )
        ));
    }


    /**
     * numCountInGroupCompleted
     *
     * @param bool $groupId      group id
     * @param bool $groupEventId group event id
     *
     * @access public
     * @return void
     */
    function numCountInGroupCompleted($groupId=null, $groupEventId=null)
    {

        //        $condition = 'EvaluationSubmission.submitted = 1 AND GroupMember.group_id='.$groupId.' AND EvaluationSubmission.grp_event_id='.$groupEventId;
        //        $fields = 'Count(EvaluationSubmission.submitter_id) AS count';
        //        $joinTable = array(' LEFT JOIN groups_members as GroupMember ON GroupMember.user_id=EvaluationSubmission.submitter_id');
        //        return $this->find('all', $condition, $fields, null, null, null, null, $joinTable );

        return $this->find('count', array(
            'conditions' => array('EvaluationSubmission.submitted' => 1, 'GroupMember.group_id' => $groupId, 'EvaluationSubmission.grp_event_id' => $groupEventId),
            'joins' => array(
                array(
                    'table' => 'groups_members',
                    'alias' => 'GroupMember',
                    'type' => 'LEFT',
                    'conditions' => array('GroupMember.user_id = EvaluationSubmission.submitter_id')
                )
            )
        ));
    }



    /**
     * daysLate
     *
     * @param mixed $event          event
     * @param mixed $submissionDate submission date
     *
     * @access public
     * @return void
     */
    function daysLate($event, $submissionDate)
    {
        $days = 0;
        $dueDate = $this->Event->find('first', array('conditions' => array('Event.id' => $event), 'fields' => array('Event.due_date')));
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


    // Deprecated: replaced by virtual field in event model
    /*function numCountInEventCompleted($eventId=null)
{
//        $condition = 'EvaluationSubmission.submitted = 1 AND EvaluationSubmission.event_id='.$eventId;
//        $fields = 'Count(EvaluationSubmission.submitter_id) AS count';
//        $joinTable = array(' LEFT JOIN groups_members as GroupMember ON GroupMember.user_id=EvaluationSubmission.submitter_id');
//
//       // return $this->find('all', $condition, $fields, null, null, null, null, $joinTable );
//       return $this-> find('all', $condition, $fields, null, null, null, null );
            return $this->find('count', array(
                'conditions' => array('EvaluationSubmission.submitted' => 1, 'EvaluationSubmission.event_id' => $eventId)
            ));

    }*/
}
