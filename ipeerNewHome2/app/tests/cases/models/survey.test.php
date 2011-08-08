<?php
App::import('Model', 'Survey');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SurveyTestCase extends CakeTestCase{

  var $name = 'Survey';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $Survey = null;
  
  function startCase() {
	$this->Survey = ClassRegistry::init('Survey');
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

  function testRubricInstance() {
    $this->assertTrue(is_a($this->Survey, 'Survey'));
  }
  
  function testGetSurveyIdByCourseIdTitle() {
  	
  	// Set up test data
  	$SurveyId = $this->Survey->getSurveyIdByCourseIdTitle(1, 'Math303 Survey');
  	$SurveyInFixture = $this->Survey->find('first', array('conditions' => array('name' => 'Math303 Survey')));
  	
  	// Assert on valid data
  	$this->assertEqual($SurveyId['Survey'], $SurveyInFixture['Survey']['id']);
  	
  	// Test on faulty inputs
  	$faultyInputs1 = $this->Survey->getSurveyIdByCourseIdTitle(1, 'Faulty');
  	$faultyInputs2 = $this->Survey->getSurveyIdByCourseIdTitle(999, 'Math303 Survey');
  	$faultyInputs3 = $this->Survey->getSurveyIdByCourseIdTitle(999, null);
  	$faultyInputs4 = $this->Survey->getSurveyIdByCourseIdTitle(null, null);
  	$this->assertNull($faultyInputs1);
  	$this->assertNull($faultyInputs2);
  	$this->assertNull($faultyInputs3);
  	$this->assertNull($faultyInputs4);
  }
  
  function testGetSurveyWithSubmissionById() {
  	
  	
  }

  /*
	function TestGetSurveyTitleById(){
		
		$this->Survey= & ClassRegistry::init('Survey');
		$empty=null;
//		$this->flushDatabase();
		
		//Test function for two different valid surveys
		##Set up test data
//		$this->createSurvey(1,2, 'Math321 Survey');
//		$this->createSurvey(2,1, 'Math320 Survey');
		##Run tests
		$SurveyTitle1 = $this->Survey->getSurveyTitleById(1);
		$SurveyTitle2 = $this->Survey->getSurveyTitleById(2);
		$this->assertEqual($SurveyTitle1,'Math321 Survey');
		$this->assertEqual($SurveyTitle2,'Math320 Survey');
		
		//Test function on surveys with same name
		##Set up test data
//		$this->createSurvey(3,1, 'Same name');
//		$this->createSurvey(4,1, 'Same name');
		##Run tests
		$sameTitle1 = $this->Survey->getSurveyTitleById(2);
		$sameTitle2 = $this->Survey->getSurveyTitleById(3);
		$this->assertEqual($sameTitle1, $sameTitle2);
		
		//Test function on invalid survey_id
		$invalidSurvey = $this->Survey->getSurveyTitleById(99999);
		$this->assertEqual($invalidSurvey, $empty);
		
		//Test function on null input
		$nullInput= $this->Survey->getSurveyTitleById(null);
		$this->assertEqual($nullInput, $empty);
		
		
		//Clear database
		$this->flushDatabase();
	}
	

	function TestGetSurveyIdByCourseIdTitle(){

		$this->Survey= & ClassRegistry::init('Survey');
		$empty = null;
		
		//Test function for two different valid surveys
		##Set up test data
	//	$this->createSurvey(1,2, 'Math321 Survey');
//		$this->createSurvey(2,1, 'Math320 Survey');
		##Run tests
		$Survey1Id = $this->Survey->getSurveyIdByCourseIdTitle(2, 'Math321 Survey');
		$Survey2Id = $this->Survey->getSurveyIdByCourseIdTitle(1, 'Math320 Survey');
		$this->assertEqual($Survey1Id,1);
		$this->assertEqual($Survey2Id,2);
		
		//Test function for invalid course_id input course_id=999 (invalid)
		$invalidCourseId = $this->Survey->getSurveyIdByCourseIdTitle(999, 'Math321 Survey');
		$this->assertEqual($invalidCourseId, $empty);
		
		//Test function for invalid course name
		$invalidCourseName = $this->Survey->getSurveyIdByCourseIdTitle(1, 'Invalid Name');
		$this->assertEqual($invalidCourseName, $empty);
		
		//Test function for null inputs; shoudl return null
		$nullCourseID = $this->Survey->getSurveyIdByCourseIdTitle(null, 'Math321 Survey');
		$nullCourseName = $this->Survey->getSurveyIdByCourseIdTitle(1, null);
		$allNull = $this->Survey->getSurveyIdByCourseIdTitle(null, null);
		$this->assertEqual($nullCourseID, null);
		$this->assertEqual($nullCourseName, null);
		$this->assertEqual($allNull, null);
		
	}

	function TestGetSurveyWithSubmissionById(){
		
		$this->Survey= & ClassRegistry::init('Survey');
		$empty = null;
		
		$temp = $this->Survey->getSurveyWithSubmissionById(2);
		$this->assertEqual($temp['Survey']['name'], "Math320 Survey");
	}
	
###### Helper Functions ######
	function createSurvey($id='', $course_id='' ,$surveyName=''){
		
		$this->Survey= & ClassRegistry::init('Survey');
		$sql = "INSERT INTO surveys 
				VALUES ('$id', '$course_id', '1', '$surveyName', NULL , NULL , NULL , '0', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->Survey->query($sql);
	}
	
	function flushDatabase(){		
		$this->Survey= & ClassRegistry::init('Survey');		
		$this->Survey->deleteAllTuples('surveys');
	}
	*/
}

?>