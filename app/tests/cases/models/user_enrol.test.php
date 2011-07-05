<?php
App::import('Model', 'UserEnrol');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class UserEnrolTestCase extends CakeTestCase {
  var $name = 'UserEnrol';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $Course = null;

  function startCase() {
		$this->UserEnrol = ClassRegistry::init('UserEnrol');
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
		$this->flushDatabase();
  }

  function testUserEnrolInstance() {
    $this->assertTrue(is_a($this->UserEnrol, 'UserEnrol'));
  }
  
  
  function testRemoveStudentFromCourse() {

  	//Test remove student
  	$this->UserEnrol->removeStudentFromCourse(3,1);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 1);
  	$this->assertEqual($user, false);
  	
  	//Test remove student twice
  	$this->UserEnrol->removeStudentFromCourse(3,1);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 1);
  	$this->assertEqual($user, false);
  	
  	//Test remove student from wrong course
  	$this->UserEnrol->removeStudentFromCourse(3,5);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 5);
  	$this->assertEqual($user, false);
  	
  	//Test remove invalid student from  course
  	$this->UserEnrol->removeStudentFromCourse(999,2);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 2);
  	$this->assertEqual($user, true);
  	
  	//Test remove valid student from invalid course
  	$this->UserEnrol->removeStudentFromCourse(2,999);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 2);
  	$this->assertEqual($user, true);
  	
  	//Test remove invalid student from invalid course
  	$this->UserEnrol->removeStudentFromCourse(999,999);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 2);
  	$this->assertEqual($user, true);

  }
  
  function testIsEnrolledInByUsername() {

  	//Test valid student enrolled in valid course
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 1);
  	$this->assertEqual($user, true);

  	//Test valid student enrolled not in valid course  	
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 3);
  	$this->assertEqual($user, false);
  	
  	//Test invalid student and valid course  	
  	$user = $this->UserEnrol->isEnrolledInByUsername('invalid', 1);
  	$this->assertEqual($user, false);

  	//Test valid student and invalid course  	
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 9999);
  	$this->assertEqual($user, false);  	
  	
  	//Test invalid student and invalid course  	
  	$user = $this->UserEnrol->isEnrolledInByUsername(9999, 9999);
  	$this->assertEqual($user, false);  	
  	
  }

  function testInsertCourses() {

  	//Test insert valid student into valid courses
  	$this->UserEnrol->insertCourses(3, array(3,4));
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 3);
  	$this->assertEqual($user, true);
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 4);
  	$this->assertEqual($user, true);

  	 //Test insert invalid student into valid course
  	$this->UserEnrol->insertCourses(999, array(5));
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 5);
  	$this->assertEqual($user, false);
  	  	  	
  	//Test insert invalid student into invalid course
  	$this->UserEnrol->insertCourses(9999, array(999));
  	$user = $this->UserEnrol->isEnrolledInByUsername('StudentY', 1);
  	$this->assertEqual($user, true); 	
  	
  }
  
  function testGetEnrolledCourses() {
  	
  	//Test valid student enrolled in 2 courses
  	$courses = $this->UserEnrol->getEnrolledCourses(3);
    $courses = $this->toArray($courses);
  	$this->assertEqual($courses, array(1,2));

  	//Test valid user not enrolled in courses
  	$courses = $this->UserEnrol->getEnrolledCourses(5);
  	$this->assertEqual($courses, null);
  	
  	//Test valid invalid user
  	$courses = $this->UserEnrol->getEnrolledCourses(999);
  	$this->assertEqual($courses, null); 	
  	
  }
  
	
##########################################################################################################     
##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
##########################################################################################################        
	
		
	function deleteAllTuples($table){

		$this->UserEnrol= & ClassRegistry::init('UserEnrol');
		$sql = "DELETE FROM $table";
		$this->UserEnrol->query($sql);
	}
	
	function flushDatabase(){
			
		$this->deleteAllTuples('courses');
		$this->deleteAllTuples('users');
		$this->deleteAllTuples('user_courses');
		$this->deleteAllTuples('user_enrols');
		$this->deleteAllTuples('roles_users');
		$this->deleteAllTuples('groups');
		$this->deleteAllTuples('groups_members');
	}
	
	function toArray($events){
		$courseNameArray = array();
			foreach ($events as $event){
			array_push($courseNameArray, $event['UserEnrol']['course_id']); 
		}
		return $courseNameArray;
	}
}

?>