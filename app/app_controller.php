<?php
/*
 * Custom AppController for iPeer
 *
 * @author      
 * @version     0.10.6.1865
 * @license		OPPL
 *
 */
uses('sanitize');

class AppController extends Controller  {
	var $startpage = 'pages';
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework');	
	var $beforeFilter =	 array('checkAccess');
  var $access = array ();
  var $actionList = array ();
  
  function __setAccess()
  {
    $access = $this->sysContainer->getAccessFunctionList();
    if (!empty($access)){
      $this->set('access', $access);
    }
  }
  
  function __setActions()
  {
    $actionList = $this->sysContainer->getActionList();
    if (!empty($actionList)){
      $this->set('action', $actionList);
    }
  }

  function __setCourses()
  {
    $coursesList = $this->sysContainer->getMyCourseList();
  
    if (!empty($coursesList)){
      $this->set('coursesList', $coursesList);
    }
  }

  function extractModel($model,$array,$field)
  {
  	$return=array();
  	foreach ($array as $row)
  	array_push($return,$row[$model][$field]);
  	return $return;
  }
    					
  function checkAccess()
	{			
	  
		//array of roles that is allowed to be an admin
		//$this->rdAuth->admins = array('A','I');
		$this->rdAuth->set();//set local vars from session
		//set access function list
		$this->__setAccess();
		$this->__setActions();
		$this->__setCourses();
//$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>"; 
		
		//Save the current URL in session
  	$pass = (!empty($this->params['pass'])) ? join('/',$this->params['pass']) : null;	  
    $URL = $this->params['controller'].'/'.$this->params['action'].'/'.$pass;
    //$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>"; 
    //check if user not logined
    if ($this->params['controller']=='' || $this->params['controller']=="loginout" || $this->params['controller']=="install")
    {
		  $this->set('rdAuth',$this->rdAuth);

		} else {
		
		  //Check whether the user is current login yet  
		  if (!isset($this->rdAuth->role)){
  	    $this->Session->write('URL', $URL);
  	    $this->Session->write('AccessErr', 'NO_LOGIN');
  	  	$redirect = 'loginout/login';
  	  	$this->redirect($redirect);
  	  	exit;      
      }
      
		  //check permission
  		if (!$this->rdAuth->check($this->params['controller'], $this->sysContainer->getActionList()))
  		{
          $this->Session->write('URL', $URL);
          $this->Session->write('AccessErr', 'NO_PERMISSION');
  		  	$redirect = 'loginout/login';
  		  	$this->redirect($redirect);
  		  	exit;
  		}
  		

  		//render the authorized controller
  		$this->set('rdAuth',$this->rdAuth);
  		$this->set('sysContainer', $this->sysContainer);
      $this->set('Output', $this->Output);

  		$this->Session->del('URL');
  		
      
  	}
		return true;
	 }

		 
}
?>