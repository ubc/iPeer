<?php
App::import('Model', 'EvaluationMixevalDetail');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationMixevalDetailTestCase extends CakeTestCase {
  var $name = 'EvaluationMixevalDetail';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
                       );
  var $Course = null;

  function startCase() {
	$this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
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
    $this->assertTrue(is_a($this->EvaluationMixevalDetail, 'EvaluationMixevalDetail'));
  }
  
  function testGetByEvalMixevalIdCritera() {
  	// Run test on valid data
  	$mixEvalDetail1 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(1, 1);
  	$mixEvalDetail2 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(1, 2);
  	$this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['id'], 1);
  	$this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['question_number'], 1);
  	$this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['question_comment'], 'Q1');
  	$this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['id'], 2);
  	$this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['question_number'], 2);
  	$this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['question_comment'], 'Q2');
  	// Run test on one valid input
  	$mixEvalDetail3 = $invalid3 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(1, null);
  	$this->assertTrue(!empty($mixEvalDetail3));
  	$this->assertEqual($mixEvalDetail3[0]['EvaluationMixevalDetail']['id'], 1);
  	$this->assertEqual($mixEvalDetail3[1]['EvaluationMixevalDetail']['id'], 2);
  	$this->assertEqual($mixEvalDetail3[0]['EvaluationMixevalDetail']['question_comment'], 'Q1');
  	$this->assertEqual($mixEvalDetail3[1]['EvaluationMixevalDetail']['question_comment'], 'Q2');
  	// Run tests on invalid data
  	$invalid1 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(232, 1);
  	$invalid2 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(1, 1232);
  	$invalid3 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCritera(null, 1);
  	$this->assertTrue(empty($invalid1));
  	$this->assertTrue(empty($invalid2));
  	$this->assertTrue(empty($invalid3));
  }
}
?>