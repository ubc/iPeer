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
}
