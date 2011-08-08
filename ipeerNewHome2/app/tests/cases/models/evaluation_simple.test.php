<?php
App::import('Model', 'EvaluationSimple');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationSimpleTestCase extends CakeTestCase {
  var $name = 'EvaluationSimple';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_simple'
                       );
  var $Course = null;

  function startCase() {
	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
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
    $this->assertTrue(is_a($this->EvaluationSimple, 'EvaluationSimple'));
  }
  
  function testGetEvalMarkByGrpEventIdEvaluatorEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee(3,3,4);
  	// Assert the queried data is correct
  	$this->assertTrue(!empty($result));
  	$this->assertEqual($result['EvaluationSimple']['grp_event_id'], 3);
  	$this->assertEqual($result['EvaluationSimple']['evaluator'], 3);
  	$this->assertEqual($result['EvaluationSimple']['evaluatee'], 4);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee(223,1,1);
  	$invalidInputs1 = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee(2,1231,1);
  	$invalidInputs2 = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee(2,1,1231);
  	$nullInputs = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee(null,null,null);
  	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($invalidInputs2));
  	$this->assertTrue(empty($nullInputs));
  }

  function testGetResultsByEvaluator() {
  	// Set up test data
  	$result = $this->EvaluationSimple->getResultsByEvaluator(3, 3);	
  	// Assert the queried data matches and is correctly ordered according to the fixture data
  	$this->assertTrue(!empty($result[0]));
  	$this->assertEqual($result[0]['EvaluationSimple']['grp_event_id'], 3);
  	$this->assertEqual($result[0]['EvaluationSimple']['score'], 15);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationSimple->getResultsByEvaluator(3, 323);
  	$invalidInputs1 = $this->EvaluationSimple->getResultsByEvaluator(232, 3);
  	$nullInput = $this->EvaluationSimple->getResultsByEvaluator(null, null);
	  $this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($nullInputs));
  }  
  
  function testGetResultsByEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationSimple->getResultsByEvaluatee(3, 3);
  	// Assert the queried data matches and is correctly ordered according to the fixture data
  	$this->assertTrue(!empty($result[0]));
  	$this->assertEqual($result[0]['EvaluationSimple']['grp_event_id'], 3);
  	$this->assertEqual($result[0]['EvaluationSimple']['score'], 5);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationSimple->getResultsByEvaluatee(3, 323);
  	$invalidInputs1 = $this->EvaluationSimple->getResultsByEvaluatee(232, 3);
  	$nullInput = $this->EvaluationSimple->getResultsByEvaluatee(null, null);
  	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($nullInputs));
  }

  
  function testGetReceivedTotalScore() {
  	// Set up test data
  	$result = $this->EvaluationSimple->getReceivedTotalScore(4,3);
  	// Assert the queried data matches with fixture data
  	$this->assertTrue(!empty($result));
  	$this->assertEqual($result[0][0]['received_total_score'], 30);
  }
  
  function testSetAllEventCommentRelease() {
  	// Set up test data
  	$this->EvaluationSimple->setAllEventCommentRelease(2, 1);
  	// Assert EvaluationSimple.event_id is updated
  	$searched = $this->EvaluationSimple->find('all', array('conditions' => array('event_id' => 2)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationSimple']['release_status'], 1);
  	$this->assertEqual($searched[1]['EvaluationSimple']['release_status'], 1);
  	$this->assertEqual($searched[2]['EvaluationSimple']['release_status'], 1);
  	
  	// Revert grade release back to 0, and test again
  	$this->EvaluationSimple->setAllEventCommentRelease(2, 0);
  	$searched = $this->EvaluationSimple->find('all', array('conditions' => array('event_id' => 2)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationSimple']['release_status'], 0);
  	$this->assertEqual($searched[1]['EvaluationSimple']['release_status'], 0);
  	$this->assertEqual($searched[2]['EvaluationSimple']['release_status'], 0);
  }

  function testSetAllEventGradeRelease() {
  	// Set up test data
  	$this->EvaluationSimple->setAllEventGradeRelease(2, 1);
  	// Assert EvaluationSimple.event_id is updated
  	$searched = $this->EvaluationSimple->find('all', array('conditions' => array('event_id' => 2)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationSimple']['grade_release'], 1);
  	$this->assertEqual($searched[1]['EvaluationSimple']['grade_release'], 1);
  	$this->assertEqual($searched[2]['EvaluationSimple']['grade_release'], 1);
  	
  	// Revert grade release back to 0, and test again
  	$this->EvaluationSimple->setAllEventGradeRelease(2, 0);
  	$searched = $this->EvaluationSimple->find('all', array('conditions' => array('event_id' => 2)));
  	$this->assertTrue(!empty($searched));
  	$this->assertEqual($searched[0]['EvaluationSimple']['grade_release'], 0);
  	$this->assertEqual($searched[1]['EvaluationSimple']['grade_release'], 0);
  	$this->assertEqual($searched[2]['EvaluationSimple']['grade_release'], 0);
  }
    
	function testGetGroupResultsByGroupEventId() {

	  $result = $this->EvaluationSimple->getGroupResultsByGroupEventId(3);
	  $this->assertEqual($result[0][0]['received_total_score'], 20);
	  
	  $result = $this->EvaluationSimple->getGroupResultsByGroupEventId(999);
	  $this->assertEqual($result[0][0]['received_total_score'], null);
	  
	  $result = $this->EvaluationSimple->getGroupResultsByGroupEventId(null);
	  $this->assertEqual($result[0][0]['received_total_score'], null);
	}  
	

	function testGetGroupResultsCountByGroupEventId() {
	  
    $result = $this->EvaluationSimple->getGroupResultsCountByGroupEventId(3);
    $this->assertEqual($result, 2);
    $result = $this->EvaluationSimple->getGroupResultsCountByGroupEventId(999);
    $this->assertEqual($result, null);    
    $result = $this->EvaluationSimple->getGroupResultsCountByGroupEventId(null);
    $this->assertEqual($result, null);        
	}
  
	function testGetAllComments () {
    
    $result = $this->EvaluationSimple->getAllComments(4, 3);
	  $this->assertEqual($result[0]['EvaluationSimple']['eval_comment'], 'eval comment3');
	  $this->assertEqual($result[1]['EvaluationSimple']['eval_comment'], 'eval comment4');    
	  $this->assertEqual($result[2]['EvaluationSimple']['eval_comment'], 'eval comment5');    
	}	
	

  function testGetOppositeGradeReleaseStatus() {
  	// Set up test data
  	$groupEvent2NotReleasedCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus(3, 1);
  	$groupEvent2ReleasedCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus(3, 0);
  	$groupEvent1NotReleasedCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus(4, 1);
  	$groupEvent1ReleasedCount = $this->EvaluationSimple->getOppositeGradeReleaseStatus(4, 0);
  	// Test against fixture data
  	$this->assertEqual($groupEvent2NotReleasedCount, 1);
  	$this->assertEqual($groupEvent2ReleasedCount, 1);
  	$this->assertEqual($groupEvent1NotReleasedCount, 1);
  	$this->assertEqual($groupEvent1ReleasedCount, 2);
  	
  	$groupEventInvalid = $this->EvaluationSimple->getOppositeGradeReleaseStatus(999, 0);
  	$this->assertFalse($groupEventInvalid);
  	
  	$groupEventInvalid = $this->EvaluationSimple->getOppositeGradeReleaseStatus(null, 0);
  	$this->assertFalse($groupEventInvalid);
  }

  function testGetOppositeCommentReleaseStatus() {
  	// Set up test data
  	$groupEvent2NotReleasedCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus(3, 1);
  	$groupEvent2ReleasedCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus(3, 0);
  	$groupEvent1NotReleasedCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus(4, 1);
  	$groupEvent1ReleasedCount = $this->EvaluationSimple->getOppositeCommentReleaseStatus(4, 0);
  	// Test against fixture data
  	$this->assertEqual($groupEvent2NotReleasedCount, 1);
  	$this->assertEqual($groupEvent2ReleasedCount, 1);
  	$this->assertEqual($groupEvent1NotReleasedCount, 2);
  	$this->assertEqual($groupEvent1ReleasedCount, 1);  
  	
  	$groupEventInvalid = $this->EvaluationSimple->getOppositeCommentReleaseStatus(999, 0);
  	$this->assertFalse($groupEventInvalid);
  	
  	$groupEventInvalid = $this->EvaluationSimple->getOppositeCommentReleaseStatus(null, 0);
  	$this->assertFalse($groupEventInvalid);
  	
  }

  function testGetTeamReleaseStatus() {
     
    $result = $this->EvaluationSimple -> getTeamReleaseStatus(3);
    $this->assertEqual($result[3]['release_status'], 1);
    $this->assertEqual($result[3]['grade_release'], 0);
    $this->assertEqual($result[4]['release_status'], 0);
    $this->assertEqual($result[4]['grade_release'], 1);        
    
    $result = $this->EvaluationSimple -> getTeamReleaseStatus(999);
    $this->assertFalse($result);
    $result = $this->EvaluationSimple -> getTeamReleaseStatus(null);
    $this->assertFalse($result);            
  }  

}
?>