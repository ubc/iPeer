<?php
App::import('Model', 'EvaluationMixeval');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationMixevalTestCase extends CakeTestCase {
  var $name = 'EvaluationMixeval';
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
	$this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
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
    $this->assertTrue(is_a($this->EvaluationMixeval, 'EvaluationMixeval'));
  }
  
  function testGetEvalMixevalByGrpEventIdEvaluatorEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(2,1,1);
  	// Assert the queried data is correct
  	$this->assertTrue(!empty($result));
  	$this->assertEqual($result['EvaluationMixeval']['grp_event_id'], 2);
  	$this->assertEqual($result['EvaluationMixeval']['evaluator'], 1);
  	$this->assertEqual($result['EvaluationMixeval']['evaluatee'], 1);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(223,1,1);
  	$invalidInputs1 = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(2,1231,1);
  	$invalidInputs2 = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(2,1,1231);
  	$nullInputs = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(null,null,null);
  	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($invalidInputs2));
  	$this->assertTrue(empty($nullInputs));
  }
  
  function testGetResultsByEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationMixeval->getResultsByEvaluatee(2, 1);
  	// Assert the queried data matches and is correctly ordered according to the fixture data
  	var_dump($result);	
  	$this->assertTrue(!empty($result[0]));
  	$this->assertTrue(!empty($result[1]));
  	$this->assertEqual($result[0]['EvaluationMixeval']['grp_event_id'], 2);
  	$this->assertEqual($result[0]['EvaluationMixeval']['score'], 10);
  	$this->assertEqual($result[1]['EvaluationMixeval']['grp_event_id'], 2);
  	$this->assertEqual($result[1]['EvaluationMixeval']['score'], 15);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationMixeval->getResultsByEvaluatee(2, 323);
  	$invalidInputs1 = $this->EvaluationMixeval->getResultsByEvaluatee(232, 1);
  	$nullInput = $this->EvaluationMixeval->getResultsByEvaluatee(null, null);
	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($nullInputs));
  }
  
  function testGetResultsDetailByEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationMixeval->getResultsDetailByEvaluatee(2,1);
  	// Assert the queried data matches with fixture data
  	$this->assertTrue(!empty($result));
  	$this->assertTrue(!empty($result[0]['EvaluationMixevalDetail']));
  	$this->assertTrue(!empty($result[1]['EvaluationMixevalDetail']));
  	$evalMixDetail1 = $result[0]['EvaluationMixevalDetail']['question_comment'];
  	$evalMixDetail2 = $result[1]['EvaluationMixevalDetail']['question_comment'];
  	$this->assertEqual($evalMixDetail1, 'Q1');
  	$this->assertEqual($evalMixDetail2, 'Q2');
  }
  
  function testGetResultsDetailByEvaluator() {
  	// Set up test data
  	$result = $this->EvaluationMixeval->getResultsDetailByEvaluator(2,2);
  	// Assert the queried data matches with fixture data
  	$this->assertTrue(!empty($result));
  	$this->assertTrue(!empty($result[0]['EvaluationMixevalDetail']));
  	$this->assertTrue(!empty($result[1]['EvaluationMixevalDetail']));
  	$evalMixDetail1 = $result[0]['EvaluationMixevalDetail']['question_comment'];
  	$evalMixDetail2 = $result[1]['EvaluationMixevalDetail']['question_comment'];
  	$this->assertEqual($evalMixDetail1, 'Q1');
  	$this->assertEqual($evalMixDetail2, 'Q2');
  }
  
  function testGetReceivedTotalScore() {
  	// Set up test data
  	$result = $this->EvaluationMixeval->getReceivedTotalScore(2,1);
	// Assert the queried data matches with fixture data
	$this->assertTrue(!empty($result));
	$this->assertEqual($result[0][0]['received_total_score'], 12.5);
  }
  
  function testGetReceivedTotalEvaluatorCount() {
  	// Set up test data
  	$grpEvent2 = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(2, 1);
  	// Assert the queried data matches with fixture data
	$this->assertTrue(!empty($grpEvent2));
	$this->assertEqual($grpEvent2, 2);
	$grpEvent1 = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(1, 1);
	$this->assertTrue(!empty($grpEvent1));
	$this->assertEqual($grpEvent1, 1);
  }
  
  function testGetOppositeGradeReleaseStatus() {
  	// Set up test data
  	$groupEvent2NotReleasedCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(2, 1);
  	$groupEvent2ReleasedCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(2, 0);
  	$groupEvent1NotReleasedCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(1, 1);
  	$groupEvent1ReleasedCount = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(1, 0);
  	// Test against fixture data
  	$this->assertEqual($groupEvent2NotReleasedCount, 2);
  	$this->assertEqual($groupEvent2ReleasedCount, 0);
  	$this->assertEqual($groupEvent1NotReleasedCount, 1);
  	$this->assertEqual($groupEvent1ReleasedCount, 0);
  }

  function testGetOppositeCommentReleaseStatus() {
  	// Set up test data
  	$groupEvent2NotReleasedCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(2, 1);
  	$groupEvent2ReleasedCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(2, 0);
  	$groupEvent1NotReleasedCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(1, 1);
  	$groupEvent1ReleasedCount = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(1, 0);
  	// Test against fixture data
  	$this->assertEqual($groupEvent2NotReleasedCount, 2);
  	$this->assertEqual($groupEvent2ReleasedCount, 0);
  	$this->assertEqual($groupEvent1NotReleasedCount, 1);
  	$this->assertEqual($groupEvent1ReleasedCount, 0);  
  }
  
  function testSetAllEventGradeRelease() {
  	// Set up test data
  	$this->EvaluationMixeval->setAllEventGradeRelease(1, 1);
  	// Assert EvaluationMixeval.event_id is updated
  	$searched = $this->EvaluationMixeval->find('all', array('conditions' => array('event_id' => 1)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationMixeval']['grade_release'], 1);
  	$this->assertEqual($searched[1]['EvaluationMixeval']['grade_release'], 1);
  	$this->assertEqual($searched[2]['EvaluationMixeval']['grade_release'], 1);
  	
  	// Revert grade release back to 0, and test again
  	$this->EvaluationMixeval->setAllEventGradeRelease(1, 0);
  	$searched = $this->EvaluationMixeval->find('all', array('conditions' => array('event_id' => 1)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationMixeval']['grade_release'], 0);
  	$this->assertEqual($searched[1]['EvaluationMixeval']['grade_release'], 0);
  	$this->assertEqual($searched[2]['EvaluationMixeval']['grade_release'], 0);
  }
}
?>