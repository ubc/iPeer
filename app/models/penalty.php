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
    
    public $belongsTo = array('Event');

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
        //return $this->find('all', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late >' => 0), 'order' => array('Penalty.days_late')));
        $penalties = $this->find('all', array('conditions' => array('Penalty.event_id' => $eventId), 'order' => array('Penalty.days_late')));
        // pop off the final deduction
        array_pop($penalties);
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
        //return $this->find('first', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late <' => 0)));
        $final = $this->find('all', array('conditions' => array('Penalty.event_id' => $eventId)));
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
        // subtract the final deduction from the count
        $count = $this->find('count',
            array('conditions'=>array('Penalty.event_id' => $eventId)));
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
}
