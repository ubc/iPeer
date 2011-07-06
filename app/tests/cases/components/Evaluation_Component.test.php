<?php
App::import('Component', 'Evaluation');

class FakeEvaluationController extends Controller {
  var $name = 'FakeEvaluationController';
  var $components = array('Evaluation', 'Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationTestCase extends CakeTestCase {
  	var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group', 'app.groups_member',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
  						'app.evaluation_simple'
                        );
                    
  function startCase() {
	$this->EvaluationComponentTest = new EvaluationComponent();
	$this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
	$this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
	$this->Event = ClassRegistry::init('Event');
	
	$admin = array('User' => array('username' => 'root',
                                   'password' => 'ipeer'));
    $this->controller = new FakeEvaluationController();
    $this->controller->constructClasses();
    $this->controller->startupProcess();
    $this->controller->Component->startup($this->controller);
    $this->controller->Auth->startup($this->controller);
    ClassRegistry::addObject('view', new View($this->Controller));
    ClassRegistry::addObject('auth_component', $this->controller->Auth);

    $this->controller->Auth->login($admin);
  }  
  
  function testFormatGradeReleaseStatus() { 
 	// Set up test data
 	$groupEventNone = array();
  	$groupEventNone['GroupEvent']['grade_release_status'] = 'None';
  	$groupEventSome = array();
 	$groupEventSome['GroupEvent']['grade_release_status'] = 'Some';
 	$groupEventAll = array();
 	$groupEventAll['GroupEvent']['grade_release_status'] = 'All';
	
 	// Case one: "grade_release_status" changed from None => Some
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventNone, true, 3);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'Some');
 	
 	// Case two: "grade_release_status" changed from Some => All
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventSome, true, 0);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'All');
 	
 	// Case three: "grade_release_status" changed from Some => None
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventSome, false, 0);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'None');

 	// Case four: "grade_release_status" changed from All => Some
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventAll, false, 0);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'Some');
 	
 	// Case five: "grade_release_status" stays the same
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventAll, true, 0);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'All');
 	
 	$result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventNone, false, 0);
 	$gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
 	$this->assertEqual($gradeReleaseStatus, 'None');
   }
                     
   function testGetGroupReleaseStatus() {
   	// Set up test data
   	$groupEvent = array();
   	$groupEvent['GroupEvent']['grade_release_status'] = 'Some';
   	$groupEvent['GroupEvent']['comment_release_status'] = 'Some';
   	$expect = array('grade_release_status' => 'Some', 
   					'comment_release_status' => 'Some');
   	// Run tests
   	$result = $this->EvaluationComponentTest->GetGroupReleaseStatus($groupEvent);
   	$this->assertEqual($result, $expect);
   }
   
   	function testFormatSimpleEvaluationResultsMatrix() {
     // Set up test data 
	 $evalResult = array();
	 $evalResult[0]['EvaluationSimple']['EvaluationSimple']['grade_release'] = 0;
	 $evalResult[0]['EvaluationSimple']['EvaluationSimple']['evaluatee'] = 'Kevin Luk';
	 $evalResult[0]['EvaluationSimple']['EvaluationSimple']['score'] = 25;
	 $evalResult[1]['EvaluationSimple']['EvaluationSimple']['grade_release'] = 0;
	 $evalResult[1]['EvaluationSimple']['EvaluationSimple']['evaluatee'] = 'Zion Au';
	 $evalResult[1]['EvaluationSimple']['EvaluationSimple']['score'] = 50;
	 $groupMembers = array();
	 $groupMembers[0]['User']['id'] = 1;
	 $groupMembers[1]['User']['id'] = 2;
	 // Run test
	 $result = $this->EvaluationComponentTest->formatSimpleEvaluationResultsMatrix(null, $groupMembers, $evalResult);
	 $expect = array('0' => array('Kevin Luk' => 25),
					 '1' => array('Zion Au' => 50));
	 $this->assertEqual($result, $expect);
	 
	 // Run tests for 'N/A' return value
	 $evalResultDuplicate1 = $evalResult;
	 unset($evalResultDuplicate1[0]['EvaluationSimple']['EvaluationSimple']);
	 unset($evalResultDuplicate1[1]['EvaluationSimple']['EvaluationSimple']);
	 $result = $this->EvaluationComponentTest->formatSimpleEvaluationResultsMatrix(null, $groupMembers, $evalResultDuplicate1);
	 $expect = array('0' => array('' => 'n/a'),
	 				 '1' => array('' => 'n/a'));
	 $this->assertEqual($result, $expect);
	 
	 $evalResultDuplicate2 = $evalResult;
	 unset($evalResultDuplicate1[0]['EvaluationSimple']);
	 unset($evalResultDuplicate1[1]['EvaluationSimple']);
	 $result = $this->EvaluationComponentTest->formatSimpleEvaluationResultsMatrix(null, $groupMembers, $evalResultDuplicate1);
	 $expect = array(array('1' => 'n/a', '2' => 'n/a'),
	 				 array('1' => 'n/a', '2' => 'n/a'));
	 $this->assertEqual($result, $expect);
   }
   
	function testFilterString() {
   	  $testString = "HELLO THIS IS A TEST";
   	  $result = $this->EvaluationComponentTest->filterString($testString);
   	  $this->assertEqual($testString, $result);
   	  
   	  $testString2 = "HELLO232_32";
   	  $result = $this->EvaluationComponentTest->filterString($testString2);
   	  $expect = "HELLO";
   	  $this->assertEqual($result, $expect);
   }
   
   function testSaveSimpleEvaluation() {
   	  // Assert data was not saved prior to running function
   	  $search1 = $this->EvaluationSimple->find('first', array('conditions' => array('eval_comment' => 'Kevin Luk was smart')));
   	  $search2 = $this->EvaluationSimple->find('first', array('conditions' => array('eval_comment' => 'Zion Au was also smart')));
   	  $searchEvalSubmission = $this->EvaluationSubmission->find('all', array('conditions' => array('grp_event_id' => 999)));
	  $this->assertFalse($search1);
	  $this->assertFalse($search2);
	  $this->assertFalse($searchEvalSubmission);
   	  
   	  // Set up test data
   	  $input = $this->setUpSimpleEvaluationTestData();
   	  $params = $input[0];
   	  $groupEvent = $input[1];
   	  $result1 = $this->EvaluationComponentTest->saveSimpleEvaluation($params, $groupEvent, null);
   	  $search1 = $this->EvaluationSimple->find('first', array('conditions' => array('eval_comment' => 'Kevin Luk was smart')));
   	  $search2 = $this->EvaluationSimple->find('first', array('conditions' => array('eval_comment' => 'Zion Au was also smart')));
   	  $searchEvalSubmission = $this->EvaluationSubmission->find('all', array('conditions' => array('grp_event_id' => 999)));
   	  
   	  // Run tests
   	  $this->assertTrue($search1);
   	  $this->assertTrue($search2);
   	  $this->assertTrue($searchEvalSubmission);
   	  $this->assertEqual($search1['EvaluationSimple']['eval_comment'], 'Kevin Luk was smart');
   	  $this->assertEqual($search1['EvaluationSimple']['score'], 25);
   	  $this->assertEqual($search1['EvaluationSimple']['grp_event_id'], 999);
   	  $this->assertEqual($search2['EvaluationSimple']['eval_comment'], 'Zion Au was also smart');
   	  $this->assertEqual($search2['EvaluationSimple']['score'], 50);
   	  $this->assertEqual($search2['EvaluationSimple']['grp_event_id'], 999);
   	  $this->assertEqual($searchEvalSubmission[0]['EvaluationSubmission']['event_id'], 999);
   	  $this->assertEqual($searchEvalSubmission[0]['EvaluationSubmission']['grp_event_id'], 999);
   }
   
   function testFormatStudentViewOfSimpleEvaluationResult() {
   	  $eventInput = $this->Event->find('first', array('conditions' => array('Event.id' => 1)));
   	  $result = $this->EvaluationComponentTest->formatStudentViewOfSimpleEvaluationResult($eventInput);
   	  var_dump($return);
   }
   
   function setUpSimpleEvaluationTestData() {
   	  $params = array();
   	  $params['form']['memberIDs'][0] = 1;
   	  $params['form']['memberIDs'][1] = 2;
   	  $params['form']['points'][0] = 25;
   	  $params['form']['points'][1] = 50;
   	  $params['form']['comments'][0] = "Kevin Luk was smart";
   	  $params['form']['comments'][1] = "Zion Au was also smart";
   	  $params['data']['Evaluation']['evaluator_id'] = 1;
   	  $params['data']['Evaluation']['evaluator_id'] = 2;
   	  $params['form']['evaluateeCount'] = 2;
   	  
   	  $groupEvent = array();
   	  $groupEvent['GroupEvent']['id'] = 999;
   	  $groupEvent['GroupEvent']['event_id'] = 999;
   	  $groupEvent['GroupEvent']['group_id'] = 999;
   	  
   	  $return = array($params, $groupEvent);
   	  return $return;   	  
   }
}  