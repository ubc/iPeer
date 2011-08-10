<?php

class PenaltyController extends AppController
{
  var $name = 'Penalty';
  //var $components = array('rdAuth');
   
  function save($eventId) {
  	// Make sure the present user is not a student
  	//$this->rdAuth->noStudentsAllowed();
  	if(isset($this->params['form']) && !empty($this->params['form'])){
  	  $this->autoRender = false;
  	  $data = $this->params['form'];
  	  for($i=1; $i<=count($data)/2; $i++) {
  	  	$tuple=array();
  	  	$tuple['event_id'] = $eventId;
  	  	$tuple['days_late'] = $data['day'.$i];
  	  	$tuple['percent_penalty'] = $data['pen'.$i];
  	  	$this->Penalty->save($tuple);
  	  	$this->Penalty->id = null;
  	  	}
  	  }
  	else {
	  $this->set('eventId', $eventId);
	}
  }
}
?>