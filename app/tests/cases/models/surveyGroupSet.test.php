<?php
App::import('Model', 'SurveyGroupSet');
App::import('Controller', 'SurveyGroupSet');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SurveyGroupSetTestCase extends CakeTestCase {
  var $name = 'SurveyGroupSet';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 'app.survey',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $SurveyGroupSet = null;

  function startCase() {
	$this->SurveyGroupSet = ClassRegistry::init('SurveyGroupSet');
	$this->Survey = ClassRegistry::init('Survey');
	$this->Question = ClassRegistry::init('Question');
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

  function testSurveyGroupSetInstance() {
    $this->assertTrue(is_a($this->SurveyGroupSet, 'SurveyGroupSet'));
  }

  function setUpTestInput() {
  	$tmp = array(
  		'SurveyGroupSet' => array('id' => 1, 'survey_id' => 1, 'set_description' => 'First Group',
							'num_groups' => 1, 'date' => null, 'released' => 0)
  				);
  }
}
?>