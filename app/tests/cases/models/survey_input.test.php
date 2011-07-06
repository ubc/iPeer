<?php
App::import('Model', 'SurveyInput');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SurveyInputTestCase extends CakeTestCase{

  var $name = 'SurveyInput';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment',
  						'app.survey_input', 'app.survey'
                       );
  var $SurveyInput = null;
  
  function startCase() {
	$this->SurveyInput = ClassRegistry::init('SurveyInput');
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

  function testSurveyInputInstance() {
    $this->assertTrue(is_a($this->SurveyInput, 'SurveyInput'));
  }

  function testGetAllSurveyInputBySurveyIdUserId() {
  	$result1 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(1,1);
  	$expect1 = array(
  					'0' => array(
  						'SurveyInput' => array(
  								'id' => 1,
  								'survey_id' => 1,
  								'user_id' => 1,
  								'question_id' => 1,
  								'sub_id' => null,
  								'response_text' => null,
  								'response_id' => null)
  								)
  					);
  	$this->assertEqual($result1, $expect1);
  	
  	$result2 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(2,2);
  	$expect2 = array(
  					'0' => array(
  						'SurveyInput' => array(
  								'id' => 2,
  								'survey_id' => 2,
  								'user_id' => 2,
  								'question_id' => 2,
  								'sub_id' => null,
  								'response_text' => null,
  								'response_id' => null)
  								)
  					);
	$this->assertEqual($result2, $expect2);
	
	// Test faulty inputs
	$invalid1 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(1,21312);
	$this->assertTrue(empty($invalid1));
	$invalid2 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(23123,1);
	$this->assertTrue(empty($invalid2));
  	$nullInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(null, null);
  	$this->assertTrue(empty($nullInput));
  }
  
  function testGetAllSurveyInputBySurveyIdUserIdQuestionId() {
  	
  	$result1 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(1,1,1);
  	$expect1 = array(
  					'0' => array(
  						'SurveyInput' => array(
  								'id' => 1,
  								'survey_id' => 1,
  								'user_id' => 1,
  								'question_id' => 1,
  								'sub_id' => null,
  								'response_text' => null,
  								'response_id' => null)
  								)
  					);
  	$this->assertEqual($result1, $expect1);
  	
  	$result2 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(2,2,2);
  	$expect2 = array(
  					'0' => array(
  						'SurveyInput' => array(
  								'id' => 2,
  								'survey_id' => 2,
  								'user_id' => 2,
  								'question_id' => 2,
  								'sub_id' => null,
  								'response_text' => null,
  								'response_id' => null)
  								)
  					);
	$this->assertEqual($result2, $expect2);
	
	// Test faulty inputs
	$invalid1 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(1,21312,1);
	$this->assertTrue(empty($invalid1));
	$invalid2 = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(23123,1,1);
	$this->assertTrue(empty($invalid2));
  	$nullInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId(null, null);
  	$this->assertTrue(empty($nullInput));
  }
  
  function testDelAllSurveyInputBySurveyIdUserIdQuestionId() {
  	// Before delete tuple 1
  	$searchBefore1 = $this->SurveyInput->find('first', array('conditions' => array('survey_id' => 1, 'user_id' => 1, 'question_id' => 1)));
  	$this->assertTrue(!empty($searchBefore1));
  	// After delete
  	$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId(1,1,1);
  	$searchAfter1 = $this->SurveyInput->find('first', array('conditions' => array('survey_id' => 1, 'user_id' => 1, 'question_id' => 1)));
  	$this->assertTrue(empty($searchAfter1));
  	
  	// Before delete tuple 2
  	$searchBefore2 = $this->SurveyInput->find('first', array('conditions' => array('survey_id' => 2, 'user_id' => 2, 'question_id' => 2)));
  	$this->assertTrue(!empty($searchBefore2));
  	// After delete
    $this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId(2,2,2);
  	$searchAfter1 = $this->SurveyInput->find('first', array('conditions' => array('survey_id' => 1, 'user_id' => 1, 'question_id' => 1)));
  	$this->assertTrue(empty($searchAfter2));
  }
  
  function testFindCountInSurveyGroup() {
  	// Test for survey with three enrollment
  	$count = $this->SurveyInput->findCountInSurveyGroup(1,1,1,1);
  	var_dump($count);
  }
}
?>