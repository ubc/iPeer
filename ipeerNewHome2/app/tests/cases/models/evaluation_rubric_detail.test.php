<?php
App::import('Model', 'EvaluationRubricDetail');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationRubricDetailTestCase extends CakeTestCase {
  var $name = 'EvaluationRubricDetail';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
                       );
  var $Course = null;

  function startCase() {
	$this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
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
    $this->assertTrue(is_a($this->EvaluationRubricDetail, 'EvaluationRubricDetail'));
  }
  
  function testGetByEvalRubricIdCritera() {
  	// Run test on valid data
  	$rubricEvalDetail1 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, 1);
  	$rubricEvalDetail2 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, 2);
  	$this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['id'], 1);
  	$this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['criteria_number'], 1);
  	$this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['criteria_comment'], 'criteria comment1');
  	$this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['id'], 2);
  	$this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['criteria_number'], 2);
  	$this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['criteria_comment'], 'criteria comment2');
  	// Run test on one valid input
  	$rubricEvalDetail3 = $invalid3 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, null);
  	$this->assertFalse($rubricEvalDetail3);
  	// Run tests on invalid data
  	$invalid1 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(232, 1);
  	$invalid2 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(1, 1232);
  	$invalid3 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(null, 1);
  	$this->assertTrue(empty($invalid1));
  	$this->assertTrue(empty($invalid2));
  	$this->assertTrue(empty($invalid3));
  }
  
  
	function testGetAllByEvalRubricId() {

	  $rubricEvalDetail  = $this->EvaluationRubricDetail->getAllByEvalRubricId(2);
  	$this->assertTrue(!empty($rubricEvalDetail));
  	$this->assertEqual($rubricEvalDetail[0]['EvaluationRubricDetail']['id'], 1);
  	$this->assertEqual($rubricEvalDetail[1]['EvaluationRubricDetail']['id'], 2);
  	$this->assertEqual($rubricEvalDetail[0]['EvaluationRubricDetail']['criteria_comment'], 'criteria comment1');
  	$this->assertEqual($rubricEvalDetail[1]['EvaluationRubricDetail']['criteria_comment'], 'criteria comment2');
  	// Run tests on invalid data
  	$invalid1 = $this->EvaluationRubricDetail->getAllByEvalRubricId(232);
  	$invalid2 = $this->EvaluationRubricDetail->getAllByEvalRubricId(null);
  	$this->assertTrue(empty($invalid1));
  	$this->assertTrue(empty($invalid2));
	}	
}
?>