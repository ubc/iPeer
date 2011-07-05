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
                        'app.user_enrol', 'app.groups_member', 'app.survey'
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

  
	function testGetAllInstructors(){
		
		$instructors = $this->Course->getAllInstructors('all', array());
	  $inst = array();
    foreach($instructors as $instructor){
      array_push($inst, $instructor['User']['id']); 
    }
    $this->assertEqual($inst, array('5','6','7','2','1')); 		    
	}
  
  function testGetCourseByInstructor(){
  
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

		$this->assertEqual($course[0]['Course']['course'], $expected[0]['Course']['course']); 
		$this->assertEqual($course[0]['Course']['title'], $expected[0]['Course']['title']); 
		$this->assertEqual($course[0]['Course']['instructor_id'], $expected[0]['Course']['instructor_id']); 
		$this->assertEqual($course[1]['Course']['course'], $expected[1]['Course']['course']); 
		$this->assertEqual($course[1]['Course']['title'], $expected[1]['Course']['title']); 
		$this->assertEqual($course[1]['Course']['instructor_id'], $expected[0]['Course']['instructor_id']); 
		
		/*
		 * Test valid instructor with unassigned course
		 * user_id == 7 : "INSTRUCTOR3"
		 */
		$unassignedInstructor = $this->Course->getCourseByInstructor(7);
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
		 * Test course with multiple enrolled students
		 * Course_id == 1
		 */
		
		
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1);
		$this->assertEqual($enrollmentCount, 4);
		## Delete test data
		$this->flushDatabase();
		
			/*
		 * Test Course with zero students enrolled
		 * Course_id ==1
		 */
		

		## Run test
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1);
		$this->assertEqual($enrollmentCount, 0);
		## Delete database
		$this->flushDatabase();
		
		/*
		 * Test invalid course_id
		 * Course_id == 1000 (invalid)*/
		
		## Run tests
		$enrollmentCount = $this->Course->getEnrolledStudentCount(1000);
		$this->assertEqual($enrollmentCount, $empty);
	}	
	
	
function testGetCourseName(){
	
		$empty=null;
	//	$this->flushDatabase();
		
		##"Math320"
	//	$this->Course->createCoursesHelper(5, 'Math320', 'Analysis I');
		$courseName = $this->Course->getCourseName(1);
		$this->assertEqual($courseName, 'Math303');
		
		##Invalid course_id
		$courseName = $this->Course->getCourseName(1231);
		$this->assertEqual($courseName, $empty);
		
		##Test null course_id input
		$courseName = $this->Course->getCourseName(null);
		$this->assertEqual($courseName, $empty);
		
	}
	
	
	
	function testAddInstructor(){
		$empty = null;
	  
		/*
		 * Test assigning a valid instructor to a valid course:
		 * user_id==13 : "INSTRUCTOR3"
		 * course_id==3 : "Math100"
		 */
		##Set up test data	
		$this->Course->addInstructor(3,6);
		##Run tests
		$courseTaught = $this->Course->getCourseByInstructor(6);  //function has been tested
		$this->assertEqual($courseTaught[0]['Course']['course'], 'Math100');		
		
		/*
		 * Test assigning multiple valid instructors to a single valid course; 
		 * should successfully add both users.
		 * User_id==5 : "INSTRUCTOR1" , User_id==5 : "INSTRUCTOR1"
		 * course_id==3 : "Math100"
		 */
		##Run Tests
		$this->Course->addInstructor(3, 5);
		$this->Course->addInstructor(3, 6);
		$courseTaughtByIns5 = $this->Course->getCourseByInstructor(5);
		$courseTaughtByIns6 = $this->Course->getCourseByInstructor(6);
		$this->assertEqual($courseTaughtByIns5[0]['Course']['course'], $courseTaughtByIns6[0]['Course']['course']);
		
		/*
		 * Test adding a valid instructor to an invalid course;
		 * User_id==15 : "Peterson" (valid)
		 * Course_id=231231: (invalid)
		 */
		##Set up test data
		$this->Course->addInstructor(231231, 7);
		##Run tests.
		$courseTaughtByIns7 = $this->Course->getCourseByInstructor(7);
		$this->assertEqual($courseTaughtByIns7, $empty);
		
		/*
		 * Test adding an invalid instructor to a valid course
		 * User_id==323123 (invalid)
		 * Course_id==2 : "Math220"
		 */
		##Set up test data
		##Run tests
		$this->Course->addInstructor(2, 323123);
		$courseTaughtByIns323123 = $this->Course->getCourseByInstructor(323123);
		$this->assertEqual($courseTaughtByIns323123, $empty);

		/*	******** THIS FUNCTION NEEDS FIXING, CURRENTLY WE CAN ADD STUDENTS AS INSTRUCTORS********
		 * Test adding student as course Instructor; should not work
		 * User_id==3 : "KevinLuk" (Student)
		 * Course_id==3 : "Math100"
		 */
		##Run tests
		$this->Course->addInstructor(3,3);
		$courseTaughtByIns3 = $this->Course->getCourseByInstructor(3);
		$this->assertEqual($courseTaughtByIns3, $empty);
		
	}
	
function testDeleteInstructor(){
		
		$empty=null;
		
		/*
		 * Try to delete an valid instructor from a valid assigned course
		 * Inputs: Course_id==2 : "Mth321"	(Valid)
		 *		   User_id==6 : "INSTRUCTOR2"	(Valid)
		 */
		##Run tests
		$this->Course->addInstructor(2,6);
		$this->Course->deleteInstructor(2,6);
		$deletedInstructor=$this->Course->getCourseByInstructor(6);
 		$this->assertEqual($empty, $deletedInstructor);
 		
		/*
		 * Test deleteInstructor() using invalid "course_id" input:
		 * Inputs: Course_id==999999 : (Invalid)
		 * 		   Course_id==2 : "Math321"	(Valid)
		 * 		   User_id==2 : "Peterson"	(Valid)
		 */
		##Run tests
		$this->Course->addInstructor(2,2);
		$this->Course->deleteInstructor(999999,2);
		$validCourseInstructor = $this->Course->getCourseByInstructor(2);
		$invalidCourseInstructor = $this->Course->getCourseByInstructor(999999);
 		$this->assertEqual($validCourseInstructor[2]['Course']['course'], 'Math321');
 		$this->assertEqual($invalidCourseInstructor, $empty);

		
		/*
		 * Test deleteInstructor() invalid "user_id" input:
		 * Inputs: Course_id==2 : "Math321"	(Valid)
		 * 		   User_id==13 : 	(inValid)
		 */
 		##Reused data from previous setup
 		$this->Course->deleteInstructor(2,13);
		$validCourseInstructor = $this->Course->getCourseByInstructor(2);
		$invalidCourseInstructor = $this->Course->getCourseByInstructor(13);
 		$this->assertEqual($validCourseInstructor[2]['Course']['course'], 'Math321');
 		$this->assertEqual($invalidCourseInstructor, $empty);

			
		
		/*
		 * Test deleteInstructor on course without instructor
		 * Inputs: Course_id==1 : "Math303" (Valid)
		 * 		   User_id==4 : "INSTRUCTOR1" 	(Valid)
		 */
		##Reused data from previous setup
		$this->Course->addInstructor(1,5);
		$this->Course->deleteInstructor(1,5);		
		$this->Course->deleteInstructor(1,5);
		$course= $this->Course->getCourseByInstructor(5);
 		$this->assertEqual($course, $empty);
 		
 		
 		//Test null inputs;
 		##Set up data
 		$this->Course->addInstructor(1,1);
 		##Run tests, should have no effects whatsoever
 		$this->Course->deleteInstructor(null,1);
 		$this->Course->deleteInstructor(1,null);
 		$course = $this->Course->getCourseByInstructor(1);
 		$this->assertEqual($course[0]['Course']['instructor_id'] ,'1');
 		##Flush database
 		$this->flushDatabase();
 		
	}	
	
	
	function testGetInactiveCourses(){
		
		$empty=null;
		
		##set up test data
	//	$this->Course->createInactiveCoursesHelper(1,'InactiveCourse1','Calculus I');
	//	$this->Course->createInactiveCoursesHelper(2,'InactiveCourse2','Calculus II');
		$inactiveCourses = $this->Course->getInactiveCourses();
		$expectedResults = array('InactiveCourse1', 'InactiveCourse2');
		$inactiveCoursesArray=array();
		foreach($inactiveCourses as $courses){
			array_push($inactiveCoursesArray, $courses['Course']['course']);
		}
		##Run tests
		$this->assertEqual($inactiveCoursesArray, $expectedResults);
	}
	
	
	

	function testFindAccessibleCoursesListByUserIdRole() {
		
		
		$empty = null;

		/*
		 * Check findAccessibleCourseList for student
		 * user_id==3 : "StudentY"
		 */
		##Run Tests
		$testStudentAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(3, 'S',null);
		$studentAccessibleCourseArray=array();
		foreach($testStudentAccessibleCourses as $course){
			array_push($studentAccessibleCourseArray, $course['Course']['course']);
		}
		sort($studentAccessibleCourseArray);
		$expectedCourseIDArray=array('Math303','Math321');
 		$this->assertEqual($studentAccessibleCourseArray, $expectedCourseIDArray);
	
		/*
		 * Check findAccessibleCourseList for instructor
		 * user_id==2 : "Peterson"
		 */

 		$this->Course->addInstructor(1,2);
 		$this->Course->addInstructor(2,2);
		#Run tests
		$testInstructorAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(2, 'I',null);
		$instructorAccessibleCourseArray=array();
		foreach($testInstructorAccessibleCourses as $course){
			array_push($instructorAccessibleCourseArray, $course['Course']['course']);
		}
		$expectedCourseIDArray = array('Math303','Math321', 'Math100');
 		$this->assertEqual($instructorAccessibleCourseArray, $expectedCourseIDArray);
		
		/*
		 * Check findAccessibleCourseList for admin
		 * user_id==8 : "Admin"
		 * Admin should have access to all course
		 */
 		##Run test
		$testAdminAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(8, 'A',null);
		$adminAccessibleCourseArray=array();
		foreach($testAdminAccessibleCourses as $course){
			array_push($adminAccessibleCourseArray, $course['Course']['course']);
		}
		sort($adminAccessibleCourseArray);
		$expectedCourseIDArray=array('Math303', 'Math321', 'Math100', 'Math200', 'Math250', 'InactiveCourse1', 'InactiveCourse2');
 		sort($expectedCourseIDArray); 		
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
		

	}	
		
	function testFindAccessibleCoursesCount(){
		
		$empty= null;		
		/*
		 * Check findAccessibleCoursesCount() on valid student input
		 * user_id==3 : "StudentY"
		 */
 		##Run tests
		$testStudentCourseCount = $this->Course->findAccessibleCoursesCount(3, 'S',null);
		($testStudentCourseCount);
	 	$this->assertEqual($testStudentCourseCount,2);	 	
	 	
		/*
		 * Check findAccessibleCoursesCount() on valid instructor input
		 * uder_id==2 : "Peterson"
		 */
 	
	 	$this->Course->addInstructor(2,2);
	 	$this->Course->addInstructor(3,2);
 		##Run tests
		$testInstructor = $this->Course->findAccessibleCoursesCount(2, 'I',null);
		$this->assertEqual($testInstructor, 3);		
		
		/*
		 * Check findAccessibleCoursesCount for valid admin input
		 * user_id==8 (valid admin)
		 * Check "user_courses" database table for data validation
		 */
		$testAdmin = $this->Course->findAccessibleCoursesCount(8, 'A',null);
	 	$this->assertEqual($testAdmin, 7);		
	 	
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
	}	

	function testFindRegisteredCoursesList(){
		
		$empty=null;
				
		/*
		 * Check function access for valid student
		 * "user_id" == 3 : StudentY
		 */

 		##Run tests; NOTE 'S' role does not have access to this funcion
		$studentAccess = $this->Course->findRegisteredCoursesList(3,'StudentY','S');
		$this->assertEqual($empty, $studentAccess);		
		
		/*
		 * Check function access for valid instructor
		 */
		##
		$instructorAccess = $this->Course->findRegisteredCoursesList(1, 'GSlade', 'I');
		$courseNameArray=$this->toCourseNameArray($instructorAccess);
		$expectedCoursesID = array('Math303','Math321');  
 		$this->assertEqual($expectedCoursesID, $courseNameArray);
		
		//Check for invalid "$requester" input 
		$invalidStudentName = $this->Course->findRegisteredCoursesList(3,'invalidStudent','S');
 		$this->assertEqual($empty, $invalidStudentName);
		
		$invalidInstructorName = $this->Course->findRegisteredCoursesList(1,'invalidInstructor','I');
 		$this->assertEqual($empty, $invalidInstructorName);

		$invalidAdminName = $this->Course->findRegisteredCoursesList(8,'invalidAdmin','A');
 		$this->assertEqual($empty, $invalidAdminName);
		
		//Check for invalid "$requester_role" input
		//student
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(3,'StudentY','X');
 		$this->assertEqual($empty, $invalidRequester_role);
		//instructor
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(1,'GSlade','X');
 		$this->assertEqual($empty, $invalidRequester_role);
		//admin
		$invalidRequester_role = $this->Course->findRegisteredCoursesList(8,'Admin','X');
 		$this->assertEqual($empty, $invalidRequester_role);

		//check for invalid "user_id" input
		//Student access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'StudentY','S');
 		$this->assertEqual($empty, $invalidRequester_role); 
		//Instructor access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'GSlade','I');
 		$this->assertEqual($empty, $invalidRequester_role);
		//admin access
		$invalidStudent_ID = $this->Course->findRegisteredCoursesList(231312,'Admin','A');
 		$this->assertEqual($empty, $invalidRequester_role);
		
		//check for NULL inputs
		$nullInput = $this->Course->findRegisteredCoursesList(null);
 		$this->assertEqual($empty, $nullInput);
 		
 		$this->flushDatabase();
		   
	}
	
function testFindNonRegisteredCoursesList(){

		$empty = null;

		//Test valids inputs for student role; should return null since stuedent cannot access this function

		##Run tests
		$studentNonRegistered = $this->Course->findNonRegisteredCoursesList(3,'StudentZ','S');
		$this->assertEqual($studentNonRegistered, $empty);
		
		//Test valid inputs for instructor role
		$instructorAccess = $this->Course->findNonRegisteredCoursesList(1,'GSlade','I');
		$nonRegisteredCourses = $this->toCourseNameArray($instructorAccess);
		$expectedArray = array('Math100', 'Math200', 'Math250', 'InactiveCourse1' , 'InactiveCourse2');
		$this->assertEqual($nonRegisteredCourses, $expectedArray);
		
		//Test for invalid "requester" input; should return null.
		$invalidStudentName = $this->Course->findNonRegisteredCoursesList(1,'invalidStudent','S');
		$invalidInstructorName = $this->Course->findNonRegisteredCoursesList(3,'invalidInstructor','I');
		$invalidAdminName = $this->Course->findNonRegisteredCoursesList(2,'invalidAdmin','A'); 
		$this->assertEqual($invalidStudentName, $empty);
		$this->assertEqual($invalidInstructorName, $empty);
		$this->assertEqual($invalidAdminName, $empty);
		
		//Test for invalid "requester_role" input; should return null.
		//For student: "ZionAu"
		$invalidRole = $this->Course->findNonRegisteredCoursesList(3,'StudentY','X');
		$this->assertEqual($invalidRole, $empty);
		//For instructor: "Peterson"
		$invalidRole = $this->Course->findNonRegisteredCoursesList(1,'GSlade','X');
		$this->assertEqual($invalidRole, $empty);
		//for admin: "tonychiu"
		$invalidRole= $this->Course->findNonRegisteredCoursesList(8,'Admin','X');
		$this->assertEqual($invalidRole, $empty);
		
		//Test for invalid "user_id" input; should return null
		$invalid_userID = $this->Course->findNonRegisteredCoursesList(2313243,'StudentY','S');
		$this->assertEqual($invalid_userID, $empty);
		
		//Test for incomplete parameter inputs; should return null
		//Missing "user_id" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesList(null ,'StudentY','S');
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

	}
	
	
	
	function testFindNonRegisteredCoursesCount(){
		
		$empty = null;


 		##Run tests
		$studentNonRegisteredCount = $this->Course->findNonRegisteredCoursesCount(3, 'StudentY', 'S');
		$this->assertEqual($studentNonRegisteredCount, $empty);
		

		//Test valid Instructor access
		##Run tests
		$instructor = $this->Course->findNonRegisteredCoursesCount(1, 'GSlade', 'I');
		$this->assertEqual($instructor, 5);
		
		//Test valid admin access; admins are actaully not enrolled in any course.
		$admin = $this->Course->findNonRegisteredCoursesCount(8, 'Admin', 'A');
		$this->assertEqual($admin, 7);

		//Test for invalid requester input; the fallow tests should all return NULL
		$invalidStudentName = $this->Course->findNonRegisteredCoursesCount(3,'invalidStudent','S');
		$invalidInstructorName = $this->Course->findNonRegisteredCoursesCount(1,'invalidInstructor','I');
		$invalidAdminName = $this->Course->findNonRegisteredCoursesCount(8,'invalidAdmin','A'); 
		$this->assertEqual($invalidStudentName, $empty);
		$this->assertEqual($invalidInstructorName, $empty);
		$this->assertEqual($invalidAdminName, $empty);
		
		//Test for invalid "requester_role" input; should ALL return null.
		$invalidStudentRole = $this->Course->findNonRegisteredCoursesCount(3,'StudentY','X');
		$this->assertEqual($invalidStudentRole, $empty);
		//For instructor: "Peterson"
		$invalidInstructorRole = $this->Course->findNonRegisteredCoursesCount(1,'GSlade','X');
		$this->assertEqual($invalidInstructorRole, $empty);
		//for admin: "tonychiu"
		$invalidAdminRole= $this->Course->findNonRegisteredCoursesCount(8,'Admin','X');
		$this->assertEqual($invalidAdminRole, $empty);
		
		//Test for invalid "user_id" input; should return null
		$invalid_userID = $this->Course->findNonRegisteredCoursesCount(2313243,'StudentY','S');
		$this->assertEqual($invalid_userID, $empty);
		
		//Test for incomplete parameter inputs; should return null
		//Missing "user_id" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null ,'StudentY','S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,3,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester_role" parameter
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,3,'StudentY');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,null,'S');
		$this->assertEqual($incompleteInput, $empty);
		//Missing "requester" AND "requester_role" inputs
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(3,null,null);
		$this->assertEqual($incompleteInput, $empty);
		//Missing "user_id" AND "requester_role" input
		$incompleteInput = $this->Course->findNonRegisteredCoursesCount(null,'StudentY',null);
		$this->assertEqual($incompleteInput,$empty);
		//Missing ALL inputs
		$nullInputs = $this->Course->findNonRegisteredCoursesCount(null,null,null);
		$this->assertEqual($nullInputs, $empty);		
	}
	
	function testGetCourseByCourse() {
		
		//test valid course
		
		$empty = null;
		$course = $this->Course->getCourseByCourse('Math100', array());
		$expected = 'Math100';
		$this->assertEqual($course[0]['Course']['course'], $expected);
		
	}
	
	function testDeleteAll(){
		
		$empty = null;
 /*
  * Name is missleading, the function actually deletes a single course defined by id and all event related to it.
  * 
  * */
 		$this->Course->deleteAll(1);
 		//Query the course table, should return count==0 for course
		//$sql = "SELECT count(*) FROM courses";
		$course = $this->Course->getCourseName(1);
		$this->assertEqual($course, $empty);	

/*		//Test calling deleteAll on an empty course list; should retrun zero
		$this->Course->deleteAll(1);
		$course = $this->Course->getCourseName(1);
		$this->assertEqual($course, $empty);	
*/
		
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
			array_push($courseNameArray, $courses['Course']['course']); 
		}
		return $courseNameArray;
	}
}		
	
?>
