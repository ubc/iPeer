<?php
App::import('Model', 'Question');
App::import('Controller', 'Question');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class QuestionTestCase extends CakeTestCase {
  var $name = 'Question';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 'app.survey',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $Question = null;

  function startCase() {
  	$this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
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

  function testQuestionInstance() {
    $this->assertTrue(is_a($this->Question, 'Question'));
  }
  
  function testSetupTestInput() {
	$result = $this->setupTestInput();
	// Compare with fixture data
	$this->assertEqual($result[0]['SurveyQuestion']['question_id'], 1);
	$this->assertEqual($result[1]['SurveyQuestion']['question_id'], 2);
  }
  
  function testFillQuestion() {
  	$input = $this->setUpTestInput();
  	$result = $this->Question->fillQuestion($input);
  	// Compare the result with fixture data
  	$firstQuestion = $result[0]['Question'];
  	$this->assertEqual($firstQuestion['prompt'], 'Did you learn a lot from this course ?');
  	$this->assertEqual($firstQuestion['id'], 1);
  	$this->assertEqual($firstQuestion['number'], 1);
  	$secondQuestion = $result[1]['Question'];
	$this->assertEqual($secondQuestion['prompt'], 'What was the hardest part ?');
  	$this->assertEqual($secondQuestion['id'], 2);
  	$this->assertEqual($secondQuestion['number'], 2);
  	// Check that Survey Question has been unset
  	$this->assertFalse(isset($firstQuestion['SurveyQuestion']));
  	$this->assertFalse(isset($secondQuestion['SurveyQuestion']));
  }
  
  function testGetTypeById() {
  	// Set up test data
  	$Q1Type = $this->Question->getTypeById(1);
  	$Q2Type = $this->Question->getTypeById(2);
  	// Compare with fixture data
  	$this->assertEqual($Q1Type, 'M');
  	$this->assertEqual($Q2Type, 'M');
  	// Check for faulty inputs
  	$faultyId = $this->Question->getTypeById(332);
  	$nullId = $this->Question->getTypeById(null);
  	$this->assertNull($faultyId);
  	$this->assertNull($nullId);
  }
  
  function setupTestInput() {
	$data = $this->SurveyQuestion->find('all', array('conditions'=> array('survey_id' => 1),
                                     'fields' => array('number', 'question_id', 'id'),
                                     'order' => 'number'));
    $data['count'] = count($data);
    return $data;
  }
}
?>