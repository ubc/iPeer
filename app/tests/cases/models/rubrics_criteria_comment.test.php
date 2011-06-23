<?php
App::import('Model', 'RubricsCriteriaComment');
App::import('Controller', 'RubricsCriteriaComments');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class RubricsCriteriaCommentTestCase extends CakeTestCase {
  var $name = 'RubricsCriteriaComment';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group', 'app.survey',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $RubricsCriteriaComment = null;

  function startCase() {
	$this->RubricsCriteriaComment = ClassRegistry::init('RubricsCriteriaComment');
	$this->Rubric = ClassRegistry::init('Rubric');
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

  function testRubricsCriteriaCommentInstance() {
    $this->assertTrue(is_a($this->RubricsCriteriaComment, 'RubricsCriteriaComment'));
  }
  
  function testGetCriteriaComment() {
	// Set up test data by querying from fixture
	$rubric = $this->Rubric->find('all', array('conditions' => array('Rubric.id' => 4)));
	$result = $this->RubricsCriteriaComment->getCriteriaComment($rubric);
	// Assert that the query was successful
	$this->assertTrue(!empty($result));
	$this->assertNotNull($result);
	// Assert the queried result matches with the fixture data
	$this->assertEqual($result['criteria_comment_1_1'], 'HELLO 11');
	$this->assertEqual($result['criteria_comment_1_2'], 'HELLO 12');
	$this->assertEqual($result['criteria_comment_2_1'], 'HELLO 21');
	$this->assertEqual($result['criteria_comment_2_2'], 'HELLO 22');
  }
  
  function testDeleteCriteriaComments() {
  	// Assert data was initially in fixtures
  	$criteria1 = $this->RubricsCriteriaComment->find('all', array(
  											'conditions' => array('criteria_id' => 1)));
  	$criteria2 = $this->RubricsCriteriaComment->find('all', array(
  											'conditions' => array('criteria_id' => 2)));
  	$this->assertTrue(!empty($criteria1));
  	$this->assertTrue(!empty($criteria2));
  	// Set up test data
  	$this->RubricsCriteriaComment->deleteCriteriaComments(4);
  	$criteria1 = $this->RubricsCriteriaComment->find('all', array(
  											'conditions' => array('criteria_id' => 1)));
  	$criteria2 = $this->RubricsCriteriaComment->find('all', array(
  											'conditions' => array('criteria_id' => 2)));
  	// Assert the queried tuples have been deleted
  	$this->assertTrue(empty($criteria1));
  	$this->assertTrue(empty($criteria2));
  } 
}
?>