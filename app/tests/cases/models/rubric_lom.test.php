<?php
App::import('Model', 'RubricsLom');
App::import('Controller', 'RubricsLoms');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class RubricsLomTestCase extends CakeTestCase {
  var $name = 'RubricsLom';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group', 'app.survey',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $RubricsLom = null;

  function startCase() {
	$this->RubricsLom = ClassRegistry::init('RubricsLom');
    $admin = array('User' => array('username' => 'root',
                                   'password' => 'ipeer'));
    $this->controller = new FakeController();
    $this->controller->constructClasses();
    $this->controller->startupProcess();
    $this->controller->Component->startup($this->controller);
    $this->controller->Auth->startup($this->controller);
    ClassRegistry::addObject('view', new View($this->Controller));
    ClassRegistry::addObject('auth_component', $this->controller->Auth);

    $this->controller->Auth->login($admin);
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

  function testRubricsLomInstance() {
    $this->assertTrue(is_a($this->RubricsLom, 'RubricsLom'));
  }
  
  function testGetLoms() {
  	// Set up test data
  	$rubricLom1 = $this->RubricsLom->getLoms(4, 1);
  	$rubricLom2 = $this->RubricsLom->getLoms(4, 2);
  	// Assert the function queried the fixture data
  	$this->assertEqual($rubricLom1[0]['RubricsLom']['rubric_id'], 4);
  	$this->assertEqual($rubricLom1[0]['RubricsLom']['lom_num'], 1);
  	$this->assertEqual($rubricLom2[0]['RubricsLom']['rubric_id'], 4);
  	$this->assertEqual($rubricLom2[0]['RubricsLom']['lom_num'], 2);
  	// Test faulty inputs
  	$nullLom = $this->RubricsLom->getLoms(null, null);
  	$invalidLom = $this->RubricsLom->getLoms(2312,231);
  	$invalidLom1 = $this->RubricsLom->getLoms(4,231);
  	$invalidLom2 = $this->RubricsLom->getLoms(1231,1);
  	$this->assertTrue(empty($nullLom));
  	$this->assertTrue(empty($invalidLom));
  	$this->assertTrue(empty($invalidLom1));
  	$this->assertTrue(empty($invalidLom2));
  }
}
?>