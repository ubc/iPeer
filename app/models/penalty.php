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
        return $this->find('all', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late >' => 0), 'order' => array('Penalty.days_late')));
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
        return $this->find('first', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late <' => 0)));
    }


    /**
     * getPenaltyType
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function getPenaltyType($eventId)
    {
        return $this->find('first',
            array('conditions'=>array('Penalty.event_id' => $eventId, 'Penalty.days_late <' => 0),
            'fields' => 'days_late'));
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
        return $this->find('count',
            array('conditions'=>array('Penalty.event_id' => $eventId, 'Penalty.days_late >' => 0)));
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
