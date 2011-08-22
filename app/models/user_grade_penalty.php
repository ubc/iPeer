<?php
class UserGradePenalty extends AppModel
{
  var $name = 'UserGradePenalty';

  function getUserGradePenaltyById($id) {
  	return $this->find('first', array('conditions' => array('UserGradePenalty.id' => $id)));
  }
  
  function getByUserIdGrpEventId($grpEventId, $userId) {
  	return $this->find('first', array('conditions' => 
  							array('grp_event_id' => $grpEventId, 'user_id' => $userId)));
  }
  
}
?>