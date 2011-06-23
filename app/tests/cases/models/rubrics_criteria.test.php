<?php
App::import('Model', 'RubricsCriteria');
App::import('Controller', 'RubricsCriterias');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class RubricsCriteriaTestCase extends CakeTestCase {
  var $name = 'RubricsCriteria';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group', 'app.survey',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $RubricsCriteria = null;

  function startCase() {
	$this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
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

  function testRubricsCriteriaInstance() {
    $this->assertTrue(is_a($this->RubricsCriteria, 'RubricsCriteria'));
  }
  
  function testGetCriteria() {
  	// Set up test data
  	$criteria1 = $this->RubricsCriteria->getCriteria(4);
  	// Compare result to fixture data
  	$this->assertTrue(!empty($criteria1));
  	$this->assertNotNull($criteria1);
  	$this->assertEqual($criteria1[0]['RubricsCriteria']['criteria'], 'CRITERIA 1');
  	$this->assertEqual($criteria1[1]['RubricsCriteria']['criteria'], 'CRITERIA 2');
  	
  }
}
?>