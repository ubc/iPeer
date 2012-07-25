<?php

/**
 * PenaltyController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class PenaltyController extends AppController
{
    public $name = 'Penalty';

    /**
     * save
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function save($eventId)
    {
        if (isset($this->params['form']) && !empty($this->params['form'])) {
            $this->autoRender = false;
            $data = $this->params['form'];
            for ($i=1; $i<=count($data)/2; $i++) {
                $tuple=array();
                $tuple['event_id'] = $eventId;
                $tuple['days_late'] = $data['day'.$i];
                $tuple['percent_penalty'] = $data['pen'.$i];
                $this->Penalty->save($tuple);
                $this->Penalty->id = null;
            }
        } else {
            $this->set('eventId', $eventId);
        }
    }
}
