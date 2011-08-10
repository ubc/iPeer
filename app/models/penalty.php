<?php

class Penalty extends AppModel {

  var $name = 'Penalty';

  function getPenaltyById($penaltyId) {
  	return $this->find('first', array('conditions' => array('Penalty.id' => $penaltyId)));
  }
  
  function getPenaltyByEventAndDaysLate($eventId, $daysLate) {
  	return $this->find('first', array('conditions' => 
  								  array('event_id' => $eventId, 'days_late' => $daysLate)));
  }
  
}
?>