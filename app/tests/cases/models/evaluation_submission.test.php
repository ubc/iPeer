<?php
App::import('Model', 'EvaluationSubmission');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationSubmissionTestCase extends CakeTestCase {
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
	$this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
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

  function testEvaluationSubmissionInstance() {
    $this->assertTrue(is_a($this->EvaluationSubmission, 'EvaluationSubmission'));
  }

  function testGetEvalSubmissionByGrpEventIdSubmitter() {
    
    $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(1,3);
    $this->assertEqual($result['EvaluationSubmission']['id'], 1);
    $this->assertEqual($result['EvaluationSubmission']['grp_event_id'], 1);    
    $this->assertEqual($result['EvaluationSubmission']['submitter_id'], 3);    
    
    $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(null,3);
    $this->assertFalse($result);    
    $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(1,null);
    $this->assertFalse($result);  
    
  }

  function testGetEvalSubmissionByEventIdSubmitter() {

    $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(2,3);
    $this->assertEqual($result['EvaluationSubmission']['id'], 3);
    $this->assertEqual($result['EvaluationSubmission']['event_id'], 2);    
    $this->assertEqual($result['EvaluationSubmission']['submitter_id'], 3);    
    
    $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(null,3);
    $this->assertFalse($result);    
    $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(2,null);
    $this->assertFalse($result);      
    
  }

  function testNumInGroupCompleted() {

    $result = $this->EvaluationSubmission->numInGroupCompleted(2,1);
    $this->assertEqual($result[0]['GroupMember']['user_id'], 3);
    
    $result = $this->EvaluationSubmission->numInGroupCompleted(null,1);
    $this->assertFalse($result);    
    $result = $this->EvaluationSubmission->numInGroupCompleted(2,null);    
    $this->assertFalse($result);
  }
		
  function testNumCountInGroupCompleted() {
  
    $result = $this->EvaluationSubmission->numCountInGroupCompleted(2,1);  
    $this->assertEqual($result, 1);
    
    $result = $this->EvaluationSubmission->numCountInGroupCompleted(null,1);
    $this->assertFalse($result);    
    $result = $this->EvaluationSubmission->numCountInGroupCompleted(2,null);    
    $this->assertFalse($result);
  
  } 

  function testDaysLate() {
    $result = $this->EvaluationSubmission->daysLate(1, '2011-06-10 00:00:01');
    var_dump($result);
    
  }
  
}
?>