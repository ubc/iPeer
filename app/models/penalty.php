<?php
/**
 * Penalty
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Penalty extends AppModel
{
    public $name = 'Penalty';

    public $validate = array(
        'days_late' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter a value for number of days late',
                'allowEmpty' => false
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter a numerical value'
            )
        )
    );

    public $belongsTo = array('Event');
    public $actsAs = array('Containable');

    /**
     * getPenaltyById
     *
     * @param mixed $penaltyId
     *
     * @access public
     * @return void
     */
    function getPenaltyById($penaltyId)
    {
        return $this->find('first', array('conditions' => array('Penalty.id' => $penaltyId)));
    }


    /**
     * getPenaltyByEventId
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function getPenaltyByEventId($eventId)
    {
        $penalties = $this->find('all', array(
            'conditions' => array('Penalty.event_id' => $eventId),
            'order' => array('Penalty.days_late'),
            'contain' => false,
        ));
        return $penalties;
    }


    /**
     * getPenaltyFinal
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function getPenaltyFinal($eventId)
    {
        $final = $this->find('all', array(
            'conditions' => array('Penalty.event_id' => $eventId),
            'order' => array('Penalty.days_late'),
            'contain' => false,
        ));
        return array_pop($final);
    }


    /**
     * getPenaltyDays
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function getPenaltyDays($eventId)
    {
        $count = $this->find('count', array(
            'conditions'=>array('Penalty.event_id' => $eventId),
            'contain' => false,
        ));
        if ($count > 0) {
            $count--;
        }
        return $count;
    }


    /**
     * getPenaltyByEventAndDaysLate
     *
     * @param mixed $eventId  event id
     * @param mixed $daysLate days late
     *
     * @access public
     * @return void
     */
    function getPenaltyByEventAndDaysLate($eventId, $daysLate)
    {
        $penalty = $this->find('all',
            array('conditions' => array('event_id' => $eventId, 'days_late >=' => $daysLate),
            'order' => array('days_late' => 'ASC'))
        );
        // returns the max late penalty index
        if (0 >= $daysLate) {
            return null;
        } else if (!empty($penalty)) {
            return $penalty[0];
        } else {
            return $this->getPenaltyFinal($eventId);
        }
    }


    /**
     * getPenaltyByPenaltiesAndDaysLate
     *
     * @param mixed $penalties penalty list
     * @param mixed $daysLate days late
     *
     * @access public
     * @return void
     */
    function getPenaltyByPenaltiesAndDaysLate($penalties, $daysLate)
    {
        if(empty($penalties) || 0.0 >= $daysLate) {
            return null;
        }
        
        $max_penalty = null;
        foreach($penalties as $penalty) {
            $max_penalty = $penalty;
            //return first penalty that if above daysLate
            if($penalty["Penalty"]["days_late"] >= $daysLate) {
                return $penalty;
            }
        }
        //else daysLate after last penalty so return max penalty
        return $max_penalty;
    }

    function getPenaltyByEventSubmissionAndDue($eventId, $event_due, $submissionDate)
    {
        $late_diff = strtotime($submissionDate) - strtotime($event_due);
        // late
        if (0 < $late_diff) {
            $days_late = $late_diff/(24*60*60);
            $penalty = $this->getPenaltyByEventAndDaysLate($eventId, $days_late);
            return $penalty['Penalty']['percent_penalty'];
        } else {
            return 0;
        }
    }

    function getPenaltyForMembers($memberIds, $event, $submissions) {
        $userPenalty = array();
        $userPenalty = array_fill_keys($memberIds, 0);
        $end = strtotime($event['release_date_end']);
        $now = time();
        foreach ($submissions as $submission) {
            $userPenalty[$submission['EvaluationSubmission']['submitter_id']] =
                $this->getPenaltyByEventSubmissionAndDue(
                    $event['id'], $event['due_date'], $submission['EvaluationSubmission']['date_submitted']
                );
        }

        // no submission - if now is after release date end then - gets final deduction
        $penalty = $this->getPenaltyFinal($event['id']);
        if ($now >= $end) {
            $noSubmissions = array_diff($memberIds, Set::extract($submissions, '/EvaluationSubmission/submitter_id'));
            foreach ($noSubmissions as $userId) {
                $userPenalty[$userId] = $penalty['Penalty']['percent_penalty'];
            }
        }

        return $userPenalty;
    }
    
    /**
     * getPenaltyPercent
     *
     * @param mixed $event_sub event submission
     *
     * @access public
     * @return void
     */
    function getPenaltyPercent($event_sub)
    {
        // storing the timestamp of the due date/end date of the event
        $event_due = strtotime($event_sub['Event']['due_date']);
        $event_end = strtotime($event_sub['Event']['release_date_end']);
        
        // assign penalty to user if they submitted late or never submitted by release_date_end
        $scorePenalty = null;
        // no submission - if now is after the release date end then - gets final deduction
        if (empty($event_sub['EvaluationSubmission'])) {
            if (time() > $event_end) {
                $scorePenalty = $this->getPenaltyFinal($event_sub['Event']['id']);
            } 
        // there is submission - may be on time or late
        } else {
            $dateSub = isset($event_sub['EvaluationSubmission'][0]['date_submitted']) ? $event_sub['EvaluationSubmission'][0]['date_submitted'] :
                $event_sub['EvaluationSubmission']['date_submitted'];
            $late_diff = strtotime($dateSub) - $event_due;
            // late
            if (0 < $late_diff) {
                $days_late = $late_diff/(24*60*60);
                $scorePenalty = $this->getPenaltyByEventandDaysLate($event_sub['Event']['id'], $days_late);
                
            }
        }
        return $scorePenalty['Penalty']['percent_penalty'];
    }
}
