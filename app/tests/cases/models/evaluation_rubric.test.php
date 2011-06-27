<?php
App::import('Model', 'EvaluationRubric');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationRubricTestCase extends CakeTestCase {
  var $name = 'EvaluationRubric';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
                       );
  var $EvaluationRubric = null;

  function startCase() {
	$this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
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
    $this->assertTrue(is_a($this->EvaluationRubric, 'EvaluationRubric'));
  }
  
//getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null)  
  function testGetEvalRubricByGrpEventIdEvaluatorEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(1,3,4);
  	// Assert the queried data is correct
  	$this->assertTrue(!empty($result));

  	$this->assertEqual($result['EvaluationRubric']['grp_event_id'], 1);
  	$this->assertEqual($result['EvaluationRubric']['evaluator'], 3);
  	$this->assertEqual($result['EvaluationRubric']['evaluatee'], 4);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(999,3,4);
  	$invalidInputs1 = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(1,999,4);
  	$invalidInputs2 = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(1,1,999);
  	$nullInputs = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(null,null,null);
  	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($invalidInputs2));
  	$this->assertTrue(empty($nullInputs));
  }
  
  function testGetResultsByEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationRubric->getResultsByEvaluatee(1, 4);
  	// Assert the queried data matches and is correctly ordered according to the fixture data
  	$this->assertTrue(!empty($result[0]));
  	$this->assertEqual($result[0]['EvaluationRubric']['grp_event_id'], 1);
  	$this->assertEqual($result[0]['EvaluationRubric']['score'], 15);
  	// Test invalid inputs
  	$invalidInputs = $this->EvaluationRubric->getResultsByEvaluatee(1, 999);
  	$invalidInputs1 = $this->EvaluationRubric->getResultsByEvaluatee(999, 1);
  	$nullInput = $this->EvaluationRubric->getResultsByEvaluatee(null, null);
	$this->assertTrue(empty($invalidInputs));
  	$this->assertTrue(empty($invalidInputs1));
  	$this->assertTrue(empty($nullInputs));
  }
  
  function testGetResultsDetailByEvaluatee() {
  	// Set up test data
  	$result = $this->EvaluationRubric->getResultsDetailByEvaluatee(2,3);
  	// Assert the queried data matches with fixture data
  	$this->assertTrue(!empty($result));
  	$this->assertTrue(!empty($result[0]['EvaluationRubricDetail']));
  	$this->assertTrue(!empty($result[1]['EvaluationRubricDetail']));
  	$evalRubricDetail1 = $result[0]['EvaluationRubricDetail']['criteria_comment'];
  	$evalRubricDetail2 = $result[1]['EvaluationRubricDetail']['criteria_comment'];
  	$this->assertEqual($evalRubricDetail1, 'criteria comment1');
  	$this->assertEqual($evalRubricDetail2, 'criteria comment2');
  }
  
 function testGetResultsDetailByEvaluator() {
  	// Set up test data
  	$result = $this->EvaluationRubric->getResultsDetailByEvaluator(2,4);
  	// Assert the queried data matches with fixture data
  	$this->assertTrue(!empty($result));
  	$this->assertTrue(!empty($result[0]['EvaluationRubricDetail']));
  	$this->assertTrue(!empty($result[1]['EvaluationRubricDetail']));
  	$evalMixDetail1 = $result[0]['EvaluationRubricDetail']['criteria_comment'];
  	$evalMixDetail2 = $result[1]['EvaluationRubricDetail']['criteria_comment'];
  	$this->assertEqual($evalMixDetail1, 'criteria comment1');
  	$this->assertEqual($evalMixDetail2, 'criteria comment2');
  }
  
  
  function testGetCriteriaResults() {
 	
    $result = $this->EvaluationRubric->getCriteriaResults(2,3);
    $this->assertEqual($result[1],10);
    $this->assertEqual($result[2], 5);  	
 } 
  
 function testGetReceivedTotalScore() {
  	// Set up test data
    $result = $this->EvaluationRubric->getReceivedTotalScore(2,3);
	  // Assert the queried data matches with fixture data
	  $this->assertTrue(!empty($result));
	  $this->assertEqual($result[0][0]['received_total_score'], 10);  
 }
 
  function testGetAllComments() {
    
    $result = $this->EvaluationRubric->getAllComments(2,3);
    $this->assertEqual($result[0]['EvaluationRubric']['general_comment'], 'general comment2');    
  }
    
  function testGetReceivedTotalEvaluatorCount() {
  	// Set up test data
  	$grpEvent1 = $this->EvaluationRubric->getReceivedTotalEvaluatorCount(2, 3);
  	// Assert the queried data matches with fixture data
	  $this->assertTrue(!empty($grpEvent1));
	  $this->assertEqual($grpEvent1, 1);
	  $grpEvent2 = $this->EvaluationRubric->getReceivedTotalEvaluatorCount(1, 4);
	  $this->assertTrue(!empty($grpEvent2));
	  $this->assertEqual($grpEvent2, 1);
  }
  
	function testGetOppositeGradeReleaseStatus(){
    $result = $this->EvaluationRubric->getOppositeGradeReleaseStatus(1,0);
    $this->assertEqual($result, 1);
    $result = $this->EvaluationRubric->getOppositeGradeReleaseStatus(1,1);   
    $this->assertEqual($result, 1); 
    $result = $this->EvaluationRubric->getOppositeGradeReleaseStatus(2,0);   
    $this->assertEqual($result, 0);         
	}

	function testGetOppositeCommentReleaseStatus(){

	  $result = $this->EvaluationRubric->getOppositeCommentReleaseStatus(1,0);
    $this->assertEqual($result, 1);
    $result = $this->EvaluationRubric->getOppositeCommentReleaseStatus(1,1);   
    $this->assertEqual($result, 1); 
    $result = $this->EvaluationRubric->getOppositeCommentReleaseStatus(2,0);   
    $this->assertEqual($result, 0);  	  
	}
	
	function testGetTeamReleaseStatus(){

    $result = $this->EvaluationRubric->getTeamReleaseStatus(1);
	  $this->assertEqual($result[0]['EvaluationRubric']['comment_release'], 1);
	  $this->assertEqual($result[0]['EvaluationRubric']['grade_release'], 1); 
	  $this->assertEqual($result[1]['EvaluationRubric']['comment_release'], 0); 
	  $this->assertEqual($result[1]['EvaluationRubric']['grade_release'], 0); 	  	  	   

    $result = $this->EvaluationRubric->getTeamReleaseStatus(999);
	  $this->assertFalse($result);	  	
	}
	

	function testSetAllEventCommentRelease() {

    $this->EvaluationRubric->setAllEventCommentRelease(1, 1);
    $result = $this->EvaluationRubric->getTeamReleaseStatus(1);
    $this->assertEqual($result[1]['EvaluationRubric']['comment_release'], 1); 
    
    $this->EvaluationRubric->setAllEventCommentRelease(999, 1);
    $result = $this->EvaluationRubric->getTeamReleaseStatus(999);
    $this->assertFalse($result); 	
	}

	function testSetAllEventGradeRelease() {

	  $this->EvaluationRubric->setAllEventGradeRelease(1,1);
    $result = $this->EvaluationRubric->getTeamReleaseStatus(1);
    $this->assertEqual($result[1]['EvaluationRubric']['grade_release'], 1); 
    
    $this->EvaluationRubric->setAllEventGradeRelease(999, 1);
    $result = $this->EvaluationRubric->getTeamReleaseStatus(999);
    $this->assertFalse($result); 		  
	  
	}
}
?>