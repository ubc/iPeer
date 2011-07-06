<?php
App::import('Component', 'Evaluation');

class FakeEvaluationController extends Controller {
  var $name = 'FakeEvaluationController';
  var $components = array('Evaluation');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EvaluationTestCase extends CakeTestCase {
  	var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
                        );
                    
  function startCase() {
	$this->EvaluationComponentTest = new EvaluationComponent();
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
	 
	 
	 
   }
}  