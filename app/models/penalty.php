<?php

class Penalty extends AppModel {

  var $name = 'Penalty';

  function getPenaltyById($penaltyId) {
  	return $this->find('first', array('conditions' => array('Penalty.id' => $penaltyId)));
  }
  
  function getPenaltyByEventId($eventId){
    return $this->find('all', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late >' => 0), 'order' => array('Penalty.days_late')));
  }
  
  function getPenaltyFinal($eventId){
    return $this->find('first', array('conditions' => array('Penalty.event_id' => $eventId, 'Penalty.days_late <' => 0)));        
  }
  
  function getPenaltyType($eventId){   
    return $this->find('first', 
      array('conditions'=>array('Penalty.event_id' => $eventId, 'Penalty.days_late <' => 0),
            'fields' => 'days_late'));    
  }
  
  function getPenaltyDays($eventId){   
    return $this->find('count', 
      array('conditions'=>array('Penalty.event_id' => $eventId, 'Penalty.days_late >' => 0)));    
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