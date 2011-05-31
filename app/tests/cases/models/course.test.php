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
		$this->flushDatabase();
  }

  function testCourseInstance() {
    $this->assertTrue(is_a($this->Course, 'Course'));
  }
	//modified
	function testGetCourseByInstructor(){
    $empty = null;

		/*
		 * user_id==1 : "GSlade"
		 * course_id==1 : "Math303"
		 */
		$course = $this->Course->getCourseByInstructor(1);
		$expected = array(
      array('Course' => array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process')),
      array('Course' => array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II')),
    );

		$this->assertEqual($course[0]['Course']['course'], $expected[0]['Course']['course']); 
		$this->assertEqual($course[0]['Course']['title'], $expected[0]['Course']['title']); 
		$this->assertEqual($course[1]['Course']['course'], $expected[1]['Course']['course']); 
		$this->assertEqual($course[1]['Course']['title'], $expected[1]['Course']['title']); 
		
		/*
		 * Test valid instructor with unassigned course
		 * user_id == 2 : "Peterson"
		 */
		$unassignedInstructor = $this->Course->getCourseByInstructor(2);
		$this->assertEqual($unassignedInstructor, $empty);
		
		/*
		 * Testing invald instructor user_id
		 * user_id == 312321 (invalid)
		 */
		$instructor = $this->Course->getCourseByInstructor(312321);
		$this->assertEqual($instructor, $empty);
		
		
		/*
		 * Testing null inputs
		 */
		$instructor = $this->Course->getCourseByInstructor(null);
		$this->assertEqual($instructor, $empty);
	}
	
	
	
	function testGetEnrolledStudentCount(){
		
		$empty=null;
		
		/*
		 * Test Course with zero students enrolled
		 * Course_id ==1
		 */
		## Set up test data
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis I');
		## Run test
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1);
		$this->assertEqual($enrollmentCount, 0);
		## Delete database
		$this->flushDatabase();
		
		
		/*
		 * Test course with multiple enrolled students
		 * Course_id == 1
		 */
		## Set up test data
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis I');
		$this->Course->createUserHelper(1, 'GSlade', 'I');
		$this->Course->createUserHelper(2, 'StudentX', 'S');
		$this->Course->createUserHelper(3, 'StudentY', 'S');
		$this->Course->createUserHelper(4, 'StudentZ', 'S');
		$this->Course->enrollUserHelper(1,1,1,'I');
		$this->Course->enrollUserHelper(2,2,1,'S');
		$this->Course->enrollUserHelper(3,3,1,'S');
		$this->Course->enrollUserHelper(4,4,1,'S');
		
		## Run tests
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1);
		$this->assertEqual($enrollmentCount, 3);
		## Delete test data
		$this->flushDatabase();
		
		
		/*
		 * Test invalid course_id
		 * Course_id == 1000 (invalid)
		 */
		## Run tests
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1000);
		$this->assertEqual($enrollmentCount, $empty);
	}	
	
	
	function testGetCourseName(){
	
		$empty=null;
		$this->flushDatabase();
		
		##"Math320"
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis I');
		$courseName = $this->Course->getCourseName(1);
		$this->assertEqual($courseName, 'Math320');
		
		##Invalid course_id
		$courseName = $this->Course->getCourseName(1231);
		$this->assertEqual($courseName, $empty);
		
		##Test null course_id input
		$courseName = $this->Course->getCourseName(null);
		$this->assertEqual($courseName, $empty);
		
		##delete test data
		$this->flushDatabase();
	}
	
	
	function testAddInstructor(){
		
		$empty=null;
				
		/*
		 * Test assigning a valid instructor to a valid course:
		 * user_id==13 : "GSlade"
		 * course_id==3 : "Math303"
		 */
		##Set up test data	
		$this->Course->createUserHelper(13, 'GSlade', 'I');
		$this->Course->createCoursesHelper(3, 'Math303', 'Probability');
		$this->Course->addInstructor(3,13);
		##Run tests
		$courseTaught = $this->Course->getCourseByInstructor(13);  //function has been tested
		$this->assertEqual($courseTaught[0]['Course']['course'], 'Math303');
		##flush database
		$this->flushDatabase();
		
		
		
		
		/*
		 * Test assigning multiple valid instructors to a single valid course; 
		 * should successfully add both users.
		 * User_id==15 : "Peterson" , User_id==13 : "GSlade"
		 * course_id==2 : "Math220"
		 */
		##Set up test data
		$this->Course->createUserHelper(13, 'GSlade', 'I');
		$this->Course->createUserHelper(15, 'Peterson', 'I');
		$this->Course->createCoursesHelper(2, 'Math220', 'Proofs');
		##Run Tests
		$this->Course->addInstructor(2, 15);
		$this->Course->addInstructor(2, 13);
		$courseTaughtByIns13 = $this->Course->getCourseByInstructor(13);
		$courseTaughtByIns15 = $this->Course->getCourseByInstructor(15);
		$this->assertEqual($courseTaughtByIns13[0]['Course']['course'], $courseTaughtByIns15[0]['Course']['course']);
		##Flush database
		$this->flushDatabase();
		
		/*
		 * Test adding a valid instructor to an invalid course;
		 * User_id==15 : "Peterson" (valid)
		 * Course_id=231231: (invalid)
		 */
		##Set up test data
		$this->Course->createUserHelper(15, 'Peterson', 'I');
		$this->Course->addInstructor(231231, 15);
		##Run tests.
		$courseTaughtByIns15 = $this->Course->getCourseByInstructor(15);
		$this->assertEqual($courseTaughtByIns15, $empty);
		##Flush database
		$this->flushDatabase();
		
		
		/*
		 * Test adding an invalid instructor to a valid course
		 * User_id==323123 (invalid)
		 * Course_id==2 : "Math220"
		 */
		##Set up test data
		$this->Course->createCoursesHelper(2, 'Math220', 'Proofs');
		##Run tests
		$this->Course->addInstructor(2, 323123);
		$courseTaughtByIns323123 = $this->Course->getCourseByInstructor(323123);
		$this->assertEqual($courseTaughtByIns323123, $empty);
		##Flush database
		$this->flushDatabase();

		/*	******** THIS FUNCTION NEEDS FIXING, CURRENTLY WE CAN ADD STUDENTS AS INSTRUCTORS********
		 * Test adding student as course Instructor; should not work
		 * User_id==3 : "KevinLuk" (Student)
		 * Course_id==5 : "Envr200"
		 */
		##Set up test data
		$this->Course->createUserHelper(3, 'KevinLuk', 'S');
		$this->Course->createCoursesHelper(5, 'Envr200', 'Environment Course');
		##Run tests
		$this->Course->addInstructor(5, 3);
		$courseTaughtByStudent3 = $this->Course->getCourseByInstructor(3);
	}

		function testDeleteInstructor(){
		
		$empty=null;
		$this->flushDatabase();

		
		/*
		 * Try to delete an valid instructor from a valid assigned course
		 * Inputs: Course_id==6 : "Cpsc111"	(Valid)
		 *		   User_id==13 : "GSlade"	(Valid)
		 */
		##Set up test data
		$this->Course->createUserHelper(13, 'GSlade', 'I');
		$this->Course->createCoursesHelper(6, 'Cpsc111', 'Java');
		$this->Course->addInstructor(6,13);		//addInstructor() function has been tested
		##Run tests
		$this->Course->deleteInstructor(6,13);
		$deletedInstructor=$this->Course->getCourseByInstructor(13);
 		$this->assertEqual($empty, $deletedInstructor);
 		##flush database
 		$this->flushDatabase();
		
 		
		/*
		 * Test deleteInstructor() using invalid "course_id" input:
		 * Inputs: Course_id==999999 : (Invalid)
		 * 		   Course_id==6 : "Cpsc111"	(Valid)
		 * 		   User_id==13 : "GSlade"	(Valid)
		 */
 		##Set up test data
		$this->Course->createUserHelper(13, 'GSlade', 'I');
		$this->Course->createCoursesHelper(6, 'Cpsc111', 'Java');
		$this->Course->addInstructor(6,13);
		##Run tests
		$this->Course->deleteInstructor(999999,13);
		$validCourseInstructor = $this->Course->getCourseByInstructor(13);
		$invalidCourseInstructor = $this->Course->getCourseByInstructor(999999);
 		$this->assertEqual($validCourseInstructor[0]['Course']['course'], 'Cpsc111');
 		$this->assertEqual($invalidCourseInstructor, $empty);

		
		/*
		 * Test deleteInstructor() invalid "user_id" input:
		 * Inputs: Course_id==6 : "Cpsc111"	(Valid)
		 * 		   User_id==13 : "GSlade"	(Valid)
		 * 		   User_id==12321: (Invalid)
		 */
 		##Reused data from previous setup
		$validCourseInstructor = $this->Course->getCourseByInstructor(13);
		$invalidCourseInstructor = $this->Course->getCourseByInstructor(12321);
 		$this->assertEqual($validCourseInstructor[0]['Course']['course'],'Cpsc111');
 		$this->assertEqual($invalidCourseInstructor, $empty);
		//Flush database
			
		
		/*
		 * Test deleteInstructor on course without instructor
		 * Inputs: Course_id==6 : "Cpsc111" (Valid)
		 * 		   User_id==13 : "GSlade" 	(Valid)
		 */
		##Reused data from previous setup
		$this->Course->addInstructor(6,13);
		$this->Course->deleteInstructor(6,13);		
		$this->Course->deleteInstructor(6,13);
		$courseInstructor = $this->Course->getInstructorByCourseById(6);
 		$this->assertEqual($courseInstructor, $empty);
 		
 		##Flush database
 		$this->flushDatabase();
 		
 		
 		//Test null inputs;
 		##Set up data
 		$this->Course->createUserHelper(1, 'Peterson', 'I');
 		$this->Course->createCoursesHelper(1, 'Math330', 'Math');
 		$this->Course->addInstructor(1,1);
 		##Run tests, should have no effects whatsoever
 		$this->Course->deleteInstructor(null,1);
 		$this->Course->deleteInstructor(1,null);
 		$courseInstructor = $this->Course->getInstructorByCourseById(1);
 		$this->assertEqual($courseInstructor[0] ,'Peterson');
 		##Flush database
 		$this->flushDatabase();
 		
	}	
	
	
	function testGetInactiveCourses(){
		
		$empty=null;
		
		##set up test data
		$this->Course->createInactiveCoursesHelper(1,'InactiveCourse1','Calculus I');
		$this->Course->createInactiveCoursesHelper(2,'InactiveCourse2','Calculus II');
		$inactiveCourses = $this->Course->getInactiveCourses();
		$expectedResults = array('InactiveCourse1', 'InactiveCourse2');
		$inactiveCoursesArray=array();
		foreach($inactiveCourses as $courses){
			array_push($inactiveCoursesArray, $courses['Course']['course']);
		}
		##Run tests
		$this->assertEqual($inactiveCoursesArray, $expectedResults);
	}

	
	function testFindAccessibleCoursesListByUserIdRole(){
		
		
		$empty = null;
		$this->flushDatabase();
	

		/*
		 * Check findAccessibleCourseList for student
		 * user_id==1 : "KevinLuk"
		 */
		##Set up test data.
		$this->Course->createUserHelper(1, 'KevinLuk', 'S');
 		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->enrollUserHelper(1,1,1,'S');
 		$this->Course->enrollUserHelper(2,1,2,'S');
		##Run Tests
		$testStudentAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(1, 'S',null);
		$studentAccessibleCourseArray=array();
		foreach($testStudentAccessibleCourses as $course){
			array_push($studentAccessibleCourseArray, $course['courses']['course']);
		}
		sort($studentAccessibleCourseArray);
		$expectedCourseIDArray=array('Math320','Math321');
 		$this->assertEqual($studentAccessibleCourseArray, $expectedCourseIDArray);
		##flush database
		$this->flushDatabase();
 		
 		
		/*
		 * Check findAccessibleCourseList for instructor
		 * user_id==2 : "Peterson"
		 */
		##Set up test data
		$this->Course->createUserHelper(2, 'Peterson', 'I');
 		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->addInstructor(1,2);
 		$this->Course->addInstructor(2,2);
		#Run tests
		$testInstructorAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(2, 'I',null);
		$instructorAccessibleCourseArray=array();
		foreach($testInstructorAccessibleCourses as $course){
			array_push($instructorAccessibleCourseArray, $course['courses']['course']);
		}
		$expectedCourseIDArray = array('Math320','Math321');
 		$this->assertEqual($instructorAccessibleCourseArray, $expectedCourseIDArray);
 		##Flush database
 		$this->flushDatabase();
		
		/*
		 * Check findAccessibleCourseList for admin
		 * user_id==1 : "Admin"
		 * Admin should have access to all course
		 */
		##Set up test data
		$this->Course->createUserHelper(1, 'Admin', 'A');
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		##Run test
		$testAdminAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(1, 'A',null);
		$adminAccessibleCourseArray=array();
		foreach($testAdminAccessibleCourses as $course){
			array_push($adminAccessibleCourseArray, $course['courses']['course']);
		}
		sort($adminAccessibleCourseArray);
		$expectedCourseIDArray=array('Cpsc111', 'Math320', 'Math321');
 		$this->assertEqual($adminAccessibleCourseArray, $expectedCourseIDArray);

		/*
		 * Check invlid user_id input;
		 * should return NULL
		 */
 		##Run tests
		$testInvalidStudentID = $this->Course->findAccessibleCoursesListByUserIdRole(434324, 'S',null);
 		$this->assertEqual($testInvalidStudentID,$empty);
		$testInvalidInstructorID = $this->Course->findAccessibleCoursesListByUserIdRole(2312124,'I',null);
 		$this->assertEqual($testInvalidInstructorID, $empty);
		$testInvaldAdminId = $this->Course->findAccessibleCoursesListByUserIdRole(67868767, 'A', null);
		

		##Flush database
		$this->flushDatabase();
	}
		
	function testFindAccessibleCoursesCount(){
		
		$empty= null;
		
		##Set up test courses
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		$this->Course->createCoursesHelper(4, 'Cpsc211', 'Java II');
		
		
		/*
		 * Check findAccessibleCoursesCount() on valid student input
		 * user_id==1 : "KevinLuk"
		 */
		##Set up test data
		$this->Course->createUserHelper(1, 'KevinLuk', 'S');
 		$this->Course->enrollUserHelper(1,1,1,'S');
 		$this->Course->enrollUserHelper(2,1,2,'S');
 		$this->Course->enrollUserHelper(3,1,3,'S');
 		$this->Course->enrollUserHelper(4,1,4,'S');
 		##Run tests
		$testStudentCourseCount = $this->Course->findAccessibleCoursesCount(1, 'S',null);
	 	$this->assertEqual($testStudentCourseCount[0][0]['total'],4);
	 	
	 	
		/*
		 * Check findAccessibleCoursesCount() on valid instructor input
		 * uder_id==1 : "GSlade"
		 */
	 	$this->Course->createUserHelper(1, 'GSlade', 'I');
 		$this->Course->enrollUserHelper(1,1,1,'I');
 		$this->Course->enrollUserHelper(2,1,2,'I');
 		$this->Course->enrollUserHelper(3,1,3,'I');
 		$this->Course->enrollUserHelper(4,1,4,'I');
 		##Run tests
		$testInstructor = $this->Course->findAccessibleCoursesCount(1, 'I',null);
	 	$this->assertEqual($testInstructor[0][0]['total'], 4);
		
		
		/*
		 * Check findAccessibleCoursesCount for valid admin input
		 * user_id==1 (valid admin)
		 * Check "user_courses" database table for data validation
		 */
		$testAdmin = $this->Course->findAccessibleCoursesCount(1, 'A',null);
	 	$this->assertEqual($testAdmin[0][0]['total'], 4);
		

	 	/*
		 * Check findAccessibleCoursesCount for invalid user_id inputs;
		 * ALL return values should be ZERO
		 */			
		$testInvalidStudentId = $this->Course->findAccessibleCoursesCount(1323123, 'S',null);
	 	$this->assertEqual($testInvalidStudentId[0][0]['total'], 0);
		$testInvalidInstructorId = $this->Course->findAccessibleCoursesCount(91203, 'I',null);
	 	$this->assertEqual($testInvalidInstructorId[0][0]['total'], 0);
		$testInvalidAdminId = $this->Course->findAccessibleCoursesCount(123802, 'A',null);
	 	$this->assertEqual($testInvalidAdminId[0][0]['total'], 0);	

	 	##flush database
	 	$this->flushDatabase();
	}
	
	
	
	
	function testFindRegisteredCoursesList(){
		
		$empty=null;
		
		##Set up test courses
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		$this->Course->createCoursesHelper(4, 'Cpsc211', 'Java II');
		
		
		/*
		 * Check function access for valid student
		 * "user_id" == 3 : KevinLuk
		 */
 		##Set up data
 		$this->Course->createUserHelper(1, 'KevinLuk', 'S');
 		$this->Course->enrollUserHelper(1,1,1,'S');
 		$this->Course->enrollUserHelper(2,1,2,'S');
 		$this->Course->enrollUserHelper(3,1,3,'S');
 		$this->Course->enrollUserHelper(4,1,4,'S');
 		##Run tests; NOTE 'S' role does not have access to this funcion
		$studentAccess = $this->Course->findRegisteredCoursesList(1,'KevinLuk','S');
		$this->assertEqual($empty, $studentAccess);
		
		
		/*
		 * Check function access for valid instructor
		 */
		##
		$instructorAccess = $this->Course->findRegisteredCoursesList(13, 'GSlade', 'I');
		$courseNameArray=$this->toCourseNameArray($instructorAccess);
		$expectedCoursesID = array(2,3);  
 		$this->assertEqual($empty, $instructorAccess);
		
		//check admin access for this function
		$adminAccess = $this->Course->findRegisteredCoursesList(3, 'tonychiu', 'A');
		$courseNameArray=$this->toCourseNameArray($adminAccess);
		$expectedCoursesID = array(1,2,3,4,9);
 		$this->assertEqual($courseNameArray, $expectedCoursesID);
		
		//Check for invalid "$requester" input 
		$invalidStudentName = $this->Course->findRegisteredCoursesList(3,'invalidStudent','S');
 		$this->assertEqual($empty, $invalidStudentName);
		
		$invalidInstructorName = $this->Course->findRegisteredCoursesList(3,'invalidInstructor','I');
 		$this->assertEqual($empty, $invalidInstructorName);

		$invalidAdminName = $this->Course->findRegisteredCoursesList(3,'invalidAdmin','A');
 		$this->assertEqual($empty, $invalidAdminName);
		
		//Check for invalid "$requester_role" input
		//student
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(3,'KevinLuk','X');
 		$this->assertEqual($empty, $invalidRequester_role);
		//instructor
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(3,'GSlade','X');
 		$this->assertEqual($empty, $invalidRequester_role);
		//admin
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(3,'tonychiu','X');
 		$this->assertEqual($empty, $invalidRequester_role);

		//check for invalid "user_id" input
		//Student access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'KevinLuk','S');
 		$this->assertEqual($empty, $invalidRequester_role); 
		//Instructor access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'GSlade','I');
 		$this->assertEqual($empty, $invalidRequester_role);
		//admin access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'tonychiu','A');
 		$this->assertEqual($empty, $invalidRequester_role);
		
		//check for NULL inputs
		$nullInput = $this->Course->findRegisteredCoursesList(null);
 		$this->assertEqual($empty, $nullInput);
 		
 		$this->flushDatabase();
		   
	}
		
		
	function testFindNonRegisteredCoursesList(){

		$empty = null;
		$this->flushDatabase();

		//Set up the test courses
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		$this->Course->createCoursesHelper(4, 'Cpsc211', 'Java II');

		//Test valids inputs for student role; should return null since stuedent cannot access this function
		##Set up test data
		$this->Course->createUserHelper(1, 'KevinLuk', 'S');
 		$this->Course->enrollUserHelper(1,1,1,'S');
		##Run tests
		$studentNonRegistered = $this->Course->findNonRegisteredCoursesList(1,'KevinLuk','S');
		$this->assertEqual($studentNonRegistered, $empty);
		
		//Test valid inputs for instructor role
		##Set up test data
		$this->Course->createUserHelper(3, 'Peterson', 'I');
		$this->Course->addInstructor(1,3);
		$instructorAccess = $this->Course->findNonRegisteredCoursesList(3,'Peterson','I');
		$nonRegisteredCourses = $this->toCourseNameArray($instructorAccess);
		$expectedArray = array('Math321', 'Cpsc111', 'Cpsc211');
		$this->assertEqual($nonRegisteredCourses, $expectedArray);
		
		//Test valid inputs for admin role; should return null since admin has access to all courses
		$adminAccess = $this->Course->findNonRegisteredCoursesList(2,'tonychiu','A');
		$courseNameArray = $this->toCourseNameArray($adminAccess);
		$this->assertEqual($empty, $courseNameArray);
		
		//Test for invalid "requester" input; should return null.
		$invalidStudentName = $this->Course->findNonRegisteredCoursesList(1,'invalidStudent','S');
		$invalidInstructorName = $this->Course->findNonRegisteredCoursesList(3,'invalidInstructor','I');
		$invalidAdminName = $this->Course->findNonRegisteredCoursesList(2,'invalidAdmin','A'); 
		$this->assertEqual($invalidStudentName, $empty);
		$this->assertEqual($invalidInstructorName, $empty);
		$this->assertEqual($invalidAdminName, $empty);
		
		//Test for invalid "requester_role" input; should return null.
		//For student: "ZionAu"
		$invalidRole = $this->Course->findNonRegisteredCoursesList(1,'ZionAu','X');
		$this->assertEqual($invalidRole, $empty);
		//For instructor: "Peterson"
		$invalidRole = $this->Course->findNonRegisteredCoursesList(3,'Peterson','X');
		$this->assertEqual($invalidRole, $empty);
		//for admin: "tonychiu"
		$invalidRole= $this->Course->findNonRegisteredCoursesList(2,'tonychiu','X');
		$this->assertEqual($invalidRole, $empty);
		
		//Test for invalid "user_id" input; should return null
		$invalid_userID = $this->Course->findNonRegisteredCoursesList(2313243,'ZionAu','S');
		$this->assertEqual($invalid_userID);
		
		//Test for incomplete parameter inputs; should return null
		//Missing "user_id" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null ,'ZionAu','S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null,3,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester_role" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null,3,'ZionAu');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null,null,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" AND "requester_role" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(3,null,null);
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester_role" input
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null,'ZionAu',null);
		$this->assertEqual($incompleteInput,$empty);
		//Missing ALL inputs
		$nullInputs = $this->Course->findNonRegisteredCoursesList(null,null,null);
		$this->assertEqual($nullInputs, $empty);
		
		$this->flushDatabase();
	}
	
		
	function testFindNonRegisteredCoursesCount(){
		
		$empty = null;
		
		//Set up test course data
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		$this->Course->createCoursesHelper(4, 'Cpsc211', 'Java II');

		//Test Student access; should return null since student does not have access
		##Set up test data
		$this->Course->createUserHelper(1, 'KevinLuk', 'S');
 		$this->Course->enrollUserHelper(1,1,1,'S');
 		##Run tests
		$studentNonRegisteredCount = $this->Course->findNonRegisteredCoursesCount(1, 'KevinLuk', 'S');
		$this->assertEqual($studentNonRegisteredCount, $empty);
		

		//Test valid Instructor access
		##Set up test data
		$this->Course->createUserHelper(2, 'Peterson', 'I');
		$this->Course->addInstructor(1, 2);
		##Run tests
		$instructor = $this->Course->findNonRegisteredCoursesCount(2, 'Peterson', 'I');
		$this->assertEqual($instructor[0][0][total], 3);
		
		//Test valid admin access; admins are actaully not enrolled in any course.
		##Set up test data
		$this->Course->createUserHelper(3, 'tonychiu', 'A');
		$admin = $this->Course->findNonRegisteredCoursesCount(3, 'tonychiu', 'A');
		$this->assertEqual($admin[0][0][total], 4);

		//Test for invalid requester input; the fallow tests should all return NULL
		$invalidStudentName = $this->Course->findNonRegisteredCoursesCount(1,'invalidStudent','S');
		$invalidInstructorName = $this->Course->findNonRegisteredCoursesCount(2,'invalidInstructor','I');
		$invalidAdminName = $this->Course->findNonRegisteredCoursesCount(3,'invalidAdmin','A'); 
		$this->assertEqual($invalidStudentName, $empty);
		$this->assertEqual($invalidInstructorName, $empty);
		$this->assertEqual($invalidAdminName, $empty);
		
		//Test for invalid "requester_role" input; should ALL return null.
		//For student: "ZionAu"
		$invalidStudentRole = $this->Course->findNonRegisteredCoursesCount(1,'KevinLuk','X');
		$this->assertEqual($invalidStudentRole, $empty);
		//For instructor: "Peterson"
		$invalidInstructorRole = $this->Course->findNonRegisteredCoursesCount(2,'Peterson','X');
		$this->assertEqual($invalidInstructorRole, $empty);
		//for admin: "tonychiu"
		$invalidAdminRole= $this->Course->findNonRegisteredCoursesCount(3,'tonychiu','X');
		$this->assertEqual($invalidAdminRole, $empty);
		
		//Test for invalid "user_id" input; should return null
		$invalid_userID = $this->Course->findNonRegisteredCoursesCount(2313243,'KevinLuk','S');
		$this->assertEqual($invalid_userID, $empty);
		
		//Test for incomplete parameter inputs; should return null
		//Missing "user_id" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null ,'ZionAu','S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,3,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester_role" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,3,'ZionAu');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,null,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" AND "requester_role" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(3,null,null);
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester_role" input
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,'ZionAu',null);
		$this->assertEqual($incompleteInput,$empty);
		//Missing ALL inputs
		$nullInputs = $this->Course->findNonRegisteredCoursesCount(null,null,null);
		$this->assertEqual($nullInputs, $empty);		
	}
		
	function testDeleteAll(){
		
		$empty = null;
		
		//Set up test course data
		$this->Course->createCoursesHelper(1, 'Math320', 'Analysis');
 		$this->Course->createCoursesHelper(2, 'Math321', 'Analysis II');
 		$this->Course->createCoursesHelper(3, 'Cpsc111', 'Java I');
 		$this->Course->createCoursesHelper(4, 'Cpsc211', 'Java II');

 		$this->Course->deleteAll();
 		//Query the course table, should return count==0 for course
		$sql = "SELECT COUNT FROM courses";
		$courseCount = $this->Course->query($sql);
		$this->assertEqual($courseCount, 0);	

		//Test calling deleteAll on an empty course list; should retrun zero
		$this->Course->deleteAll();
		$sql = "SELECT COUNT FROM courses";
		$courseCount = $this->Course->query($sql);
		$this->assertEqual($courseCount);
		
		
		//Clear database
		$this->flushDatabase();
		
	}
	
	function testGetAllInstructors(){
		
		$empty = null;
		
		
	}
	
	
#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################

	function flushDatabase(){

		$this->Course= & ClassRegistry::init('Course');
		
		$this->Course->deleteAllTuples('courses');
		$this->Course->deleteAllTuples('users');
		$this->Course->deleteAllTuples('user_courses');
		$this->Course->deleteAllTuples('user_enrols');
		
	}
	
	function toCourseNameArray($userEnrol){
		$courseNameArray = array();
			foreach ($userEnrol as $courses){
			array_push($courseNameArray, $courses['C']['course']); 
		}
		return $courseNameArray;
	}
}		
	
?>
