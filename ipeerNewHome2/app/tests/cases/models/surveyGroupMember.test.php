<?php
App::import('Model', 'SurveyGroupMember');
App::import('Controller', 'SurveyGroupMember');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SurveyGroupMemberTestCase extends CakeTestCase {
  var $name = 'SurveyGroupMember';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 'app.survey',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $SurveyGroupMember = null;

  function startCase() {
	$this->SurveyGroupMember = ClassRegistry::init('SurveyGroupMember');
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

  function testSurveyGroupMemberInstance() {
    $this->assertTrue(is_a($this->SurveyGroupMember, 'SurveyGroupMember'));
  }
  
  function testGetIdsByGroupSetId() {
  	// Set up test data
  	$data = $this->SurveyGroupMember->getIdsByGroupSetId(1);
  	// Assert the queried data is infact the fixture data
  	$entry0 = $data[0]['SurveyGroupMember'];
  	$entry1 = $data[1]['SurveyGroupMember'];
	$this->assertEqual($entry0['id'], 1);
	$this->assertEqual($entry1['id'], 2);
  }
}
?>