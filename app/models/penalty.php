<?php

class Penalty extends AppModel {

  var $name = 'Penalty';

  function getPenaltyById($penaltyId) {
  	return $this->find('first', array('conditions' => array('Penalty.id' => $penaltyId)));
  }
  
  function getPenaltyByEventAndDaysLate($eventId, $daysLate) {
  	$penalty = $this->find('all', 
  		array('conditions' => array('event_id' => $eventId, 'days_late <=' => $daysLate),
  			  'order' => array('days_late' => 'DESC'))
	);
	// returns the max late penalty index
	if(!empty($penalty)) {
	  return $penalty[0];	
	}
	else return null;
  }
  
}
?>