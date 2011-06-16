<?php
App::import('Model', 'Course');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class CourseTestCase extends CakeTestCase {
  var $name = 'Course';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member',
                       );
  var $Course = null;

  function startCase() {
	$this->Course = ClassRegistry::init('Course');
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
    $this->assertTrue(is_a($this->Course, 'Course'));
  }
	//modified
  function testGetCourseByInstructor(){
  	var_dump($this->Course->find('all'));
    $empty = null;
	/*
	 * user_id==1 : "GSlade"
	 * course_id==1 : "Math303"
	 */
	$course = $this->Course->getCourseByInstructor(1);
	$expected = array(
      array('Course' => array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process', 'instructor_id' => 1)),
      array('Course' => array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II', 'instructor_id' => 1)),
    );
   	for($i=0; $i<count($course); $i++) {
   		$this->assertEqual($course[$i]['Course']['id'], $expected[$i]['Course']['id']);
   	}
   	
   	// Test null inputs
   	$testNullInput = $this->Course->getCourseByInstructor(null);
   	$this->assertEqual($testNullInput, $empty);
   		
   	// Test invalid instructor_id
   	$testInvalidInstructorId = $this->Course->getCourseByInstructor(3333);
   	$this->assertEqual($testInvalidInstructorId, $empty);
   		
  }
}
?>