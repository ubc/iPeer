<?php

class PenaltyController extends AppController
{
  var $name = 'Penalty';
  var $components = array('rdAuth');
  
  
  function index() {
  	
  }
  
  function save() {
  	// Make sure the present user is not a student
  	$this->rdAuth->noStudentsAllowed();
  	if(isset($this->params['form']) && !empty($this->params['form'])){
  	  $this->autoRender = false;
  	  var_dump($this->params['form']);
  	}
  	else {
	  
	}
  }
  
}
?>