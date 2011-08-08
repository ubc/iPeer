<?php
App::import('Model', 'SysFunction');
App::import('Controller', 'SysFunction');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $uses = null;
  var $params = array('action' => 'test');
}

class SysFunctionTestCase extends CakeTestCase {
  var $name = 'SysFunction';
  var $fixtures = array('app.sys_function'
                       );
  var $SysFunction = null;

  function startCase() {
	$this->SysFunction = ClassRegistry::init('SysFunction');
    $admin = array('User' => array('username' => 'root',
                                   'password' => 'ipeer'));
    $this->controller = new FakeController();
    $this->controller->constructClasses();
    $this->controller->startupProcess();
    ClassRegistry::addObject('view', new View($this->Controller));
    ClassRegistry::addObject('auth_component', $this->controller->Auth);
  }

  function endCase() {
    $this->controller->Component->shutdown($this->controller);
    $this->controller->shutdownProcess();
  }

  //Run before EVERY test.
  function startTest($method) {
  // extra setup stuff here
  }
	
  function endTest($method) {
  }

  function testSysFunctionInstance() {
    $this->assertTrue(is_a($this->SysFunction, 'SysFunction'));
  }
  
  function testGetTopAccessibleFunction() {
  	// Set up test data
  	$resultNotAccessible = $this->SysFunction->getTopAccessibleFunction('I');
  	$resultAccessible = $this->SysFunction->getTopAccessibleFunction('A');
  	$notAccessible1 = $resultNotAccessible[0]['SysFunction'];
  	$notAccessible2 = $resultNotAccessible[1]['SysFunction'];
  	$Accessible1 = $resultAccessible[0]['SysFunction'];
  	$expectNotAccessible1 = array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
  			  	'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1');
	$expectNotAccessible2 = array('id' => 2, 'function_code' => 'code2', 'function_name' => 'name2',
  			  'parent_id' => 0, 'controller_name' => 'controller2', 'url_link' => 'link2');
	$expectAccessible1 = array('id' => 3, 'function_code' => 'code3', 'function_name' => 'name3',
  			  'parent_id' => 0, 'controller_name' => 'controller3', 'url_link' => 'link3');
	// Assert the queried data results are as expected
	$this->assertEqual($notAccessible1, $expectNotAccessible1);
	$this->assertEqual($notAccessible2, $expectNotAccessible2);
	$this->assertEqual($Accessible1, $expectAccessible1);
	
	// Assert function only queries tuples with parent_id==0
	$this->assertEqual(count($resultAccessible), 1);
  }
  
  function testGetAllAccessibleFunction() {
	// Set up test data
  	$resultNotAccessible = $this->SysFunction->getAllAccessibleFunction('I');
  	$resultAccessible = $this->SysFunction->getAllAccessibleFunction('A');
  	$notAccessible1 = $resultNotAccessible[0]['SysFunction'];
  	$notAccessible2 = $resultNotAccessible[1]['SysFunction'];
  	$Accessible1 = $resultAccessible[0]['SysFunction'];
  	$Accessible2 = $resultAccessible[1]['SysFunction'];
  	$expectNotAccessible1 = array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
  			  'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1');
	$expectNotAccessible2 = array('id' => 2, 'function_code' => 'code2', 'function_name' => 'name2',
  			  'parent_id' => 0, 'controller_name' => 'controller2', 'url_link' => 'link2');
	$expectAccessible1 = array('id' => 3, 'function_code' => 'code3', 'function_name' => 'name3',
  			  'parent_id' => 0, 'controller_name' => 'controller3', 'url_link' => 'link3');
	$expectAccessible2 = array('id' => 4, 'function_code' => 'code4', 'function_name' => 'name4',
  			  'parent_id' => 1, 'controller_name' => 'controller4', 'url_link' => 'link4');
	// Assert the queried data results are as expected
	$this->assertEqual($notAccessible1, $expectNotAccessible1);
	$this->assertEqual($notAccessible2, $expectNotAccessible2);
	$this->assertEqual($Accessible1, $expectAccessible1);
	$this->assertEqual($Accessible2, $expectAccessible2);
  }
  
  function testBeforeSave() {
  	// Set up test input
  	$validSaveinput = array('SysFunction' => array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
  			 	   'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1',
  			  	   'permission_type' => 'I', 'record_status' => 'A', 'creator_id' => 0,
  			  	   'created' => 0, 'updater_id' => null, 'modified' => null));
  	$result = $this->beforeSaveDuplicate($validSaveinput);
  	$this->assertTrue($result);
  	
  	$falseInputType1 = array('SysFunction' => array('id' => 'fd', 'function_code' => 'code1', 'function_name' => 'name1',
  			 	   'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1',
  			  	   'permission_type' => 'I', 'record_status' => 'A', 'creator_id' => 0,
  			  	   'created' => 0, 'updater_id' => null, 'modified' => null));
  	$result = $this->beforeSaveDuplicate($falseInputType1);
  	$this->assertFalse($result);
  	
  	$falseInputType2 = array('SysFunction' => array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
  			 	   'parent_id' => 0, 'controller_name' => '', 'url_link' => 'link1',
  			  	   'permission_type' => 'I', 'record_status' => 'A', 'creator_id' => 0,
  			  	   'created' => 0, 'updater_id' => null, 'modified' => null));
  	$result = $this->beforeSaveDuplicate($falseInputType2);
  	$this->assertFalse($result);
  }
  
  function beforeSaveDuplicate($input)
  {
  	    $input[$this->name]['modified'] = date('Y-m-d H:i:s');
      // Ensure the name is not empty
    if (empty($input['SysFunction']['id']) || 
    	empty($input['SysFunction']['function_code']) || 
    	empty($input['SysFunction']['function_name']) || 
    	!isset($input['SysFunction']['parent_id']) ||
    	is_null($input['SysFunction']['parent_id']) ||
    	empty($input['SysFunction']['controller_name']) || 
    	empty($input['SysFunction']['url_link']) || 
    	empty($input['SysFunction']['permission_type'])){
    		$this->errorMessage = "All fields are required";
      		return false;
    }
     
	if (!is_numeric($input['SysFunction']['id'])){
    	 $this->errorMessage = "Id must be a number";
      	 return false;
	}
         //return parent::beforeSave();
         return true;
  } 	
}
?>