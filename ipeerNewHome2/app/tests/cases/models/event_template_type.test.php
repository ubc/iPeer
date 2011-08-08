<?php
App::import('Model', 'EventTemplateType');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EventTemplateTypeTestCase extends CakeTestCase {
  var $name = 'EventTemplateType';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $EventTemplateType = null;

  function startCase() {
	$this->EventTemplateType = ClassRegistry::init('EventTemplateType');
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

  function testCourseInstance() {
    $this->assertTrue(is_a($this->EventTemplateType, 'EventTemplateType'));
  }

  function testGetEventTemplateTypeList() {
  	// Test for returning ALL template results
  	$result = $this->EventTemplateType->getEventTemplateTypeList(false);
  	$this->assertTrue(!empty($result));
  	$this->assertEqual($result[1], 'RUBRIC');
  	$this->assertEqual($result[2], 'SIMPLE');
  	$this->assertEqual($result[3], 'SIMPLE');
  	// Test for returning ONLY display_for_selection==1 templates
  	$result = $this->EventTemplateType->getEventTemplateTypeList(true);
  	$this->assertTrue(!empty($result));
  	$this->assertTrue($result[1], 'RUBRIC');
  }
}

?>