<?php

  var $name = 'UserGradePenalty';
  var $belongsTo = array('GroupEvent' => array(
								'className' => 'GroupEvent',
								'foreignKey' => 'group_events_id'));
  
  function getUserGradePenaltyById($id) {
  	return $this->find('first', array('conditions' => array('UserGradePenalty.id' => $id)));
  }

?>