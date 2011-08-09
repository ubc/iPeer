<?php

class Penalty extends AppModel {

  var $name = 'Penalty';

  var $belongsTo = array('GroupEvent' => array(
								'className' => 'GroupEvent',
								'foreignKey' => 'group_events_id'));

  function getPenaltyById($penaltyId) {
  	return $this->find('first', array('conditions' => array('Penalty.id' => $penaltyId)));
  }
  

}
?>