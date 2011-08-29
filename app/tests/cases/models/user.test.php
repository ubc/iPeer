<?php
App::import('Model', 'User');
App::import('Model', 'UserCourse');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class UserTestCase extends CakeTestCase {
  var $name = 'User';
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
		$this->User = ClassRegistry::init('User');
		$this->UserCourse = ClassRegistry::init('UserCourse');		
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
    $this->assertTrue(is_a($this->User, 'User'));
  }
	function testGetByUsername(){
		
		$this->User= & ClassRegistry::init('User');
		$empty=null;
	
		//Test on valid student input
		##Run tests
		$studentName = $this->User->getByUsername('StudentY');
		$this->assertEqual($studentName['User']['username'], "StudentY");
		
		//Test on valid instructor input
		##Run Tests
		$instructorName = $this->User->getByUserName('Peterson');
		$this->assertEqual($instructorName['User']['username'], "Peterson");
		
		//Test on valid admin input
		##Set up test data
//		$this->createUserHelper(3,'tonychiu','A','Password');
		##Run tests
		$adminName = $this->User->getByUserName('Admin');
		$this->assertEqual($adminName['User']['username'], "Admin");

		//Testing invalid inputs; all tests should return NULL
		##invalid username input
		$invalidUsername = $this->User->getByUserName('fadslkfjasdkljf');
		$this->assertEqual($invalidUsername['username'], $empty);
		
		##null input
		$nullInput = $this->User->getByUserName(null);
		$this->assertEqual($nullInput['username'], $empty);
			
		
		##delete previous test data
		$this->flushDatabase();
	}
		
	
	function testFindUser()
	{
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//On valid student user_name and password
		
		##Students
		$validStudent = $this->User->findUser('StudentY', 'password1');
		$this->assertEqual($validStudent['User']['username'], "StudentY");
		
		##Instructor
		$validInstructor = $this->User->findUser('INSTRUCTOR1', 'password2');
		$this->assertEqual($validInstructor['User']['username'], "INSTRUCTOR1");
		
		##Admin
		$validAdmin = $this->User->findUser('Admin', 'passwordA');
		$this->assertEqual($validAdmin['User']['username'],"Admin");
		
		
		//On invalid user_name
		$invalidUserName = $this->User->findUser('invalidUser', 'password1');
		$this->assertEqual($invalidUserName, $empty);
		
		//On invalid passWord
		$invalidPassword = $this->User->findUser('StudentY', 'invalidPassword');
		$this->assertEqual($invalidPassword, $empty);
		
		//ALL invalid paramters
		$allInvalid = $this->User->findUser('invalid', 'invalid');
		$this->assertEqual($allInvalid, $empty);
		
		//null inputs
		$nullInput = $this->User->findUser(null, 'password1');
		$this->assertEqual($nullInput, $empty);
		
		$nullInput = $this->User->findUser( 'StudentY' , null);
		$this->assertEqual($nullInput, $empty);
		
		$nullInput = $this->User->findUser(null, null);
		$this->assertEqual($nullInput, $empty);
	
		
		//On duplicate password
		$checkDuplicate1 = $this->User->findUser('StudentY', 'password1');
		$checkDuplicate2 = $this->User->findUser('StudentZ', 'password1');
		##run tests
		$this->assertEqual($checkDuplicate1['User']['username'], "StudentY");
		$this->assertEqual($checkDuplicate2['User']['username'], "StudentZ");
		
	}
	
	
	
	function testFindUserByStudentNo(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;	
		
		//Test findUserByStudentNo() on valid users
		
		##Students
		$student = $this->User->findUserByStudentNo(123);
		$this->assertEqual($student['User']['username'], "StudentY");
		
		##Instructors
		$instructors = $this->User->findUserByStudentNo(321);
		$this->assertEqual($instructors['User']['username'], "INSTRUCTOR1");
      	
		##Admin
		$admin = $this->User->findUserByStudentNo(111);
		$this->assertEqual($admin['User']['username'], "Admin");
		
		##Test studentNo==0
		$zero  = $this->User->findUserByStudentNo(0);
		$zero1 = $this->User->findUserByStudentNo(000000); 
		$this->assertEqual($zero['User']['username'], $zero1['User']['username']);
		
		//Test invalid student number : 2332353 (invalid)
		$invalidStudentNo = $this->User->findUserByStudentNo(2332353);
		$this->assertEqual($invalidStudentNo, $empty);
		
		//Test null student number input
		$nullInput = $this->User->findUserByStudentNo(null);
		$this->assertEqual($nullInput, $empty);
		
	}
	
	
	function TestGetEnrolledStudents() {

		$this->User =& ClassRegistry::init('User');
		$empty=null;	

		##Run tests
		$enrolledStudentList = $this->User->getEnrolledStudents(1);
		$enrolledStudentArray=array();
		foreach($enrolledStudentList as $student){
			array_push($enrolledStudentArray, $student['User']['username']);
		}		
		$expect = array('GSlade','Peterson','StudentY','StudentZ');
		$this->assertEqual(sort($enrolledStudentArray), sort($expect));
		
		//Test a course with no students enrolled
		$enrolledStudentList = $this->User->getEnrolledStudents(3);
		$this->assertEqual($enrolledStudentList, $empty);
		
		//Test an invalid corse, course_id==231321 (invalid)
		$invalidCourse = $this->User->getEnrolledStudents(231321);
		$this->assertEqual($invalidCourse, $empty);
	}
		
	function TestGetEnrolledStudentsForList()
	{

		/* TO DO */
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Test on a valid course with some student enrollment
		##Set up test data
	
		$temp = $this->User->getEnrolledStudentsForList(1);
		$this->User->printHelp($temp);

	}

  function testGetUserByEmail() {
  
    $user = $this->User->getUserByEmail('email1');
    $this->assertEqual($user['User']['username'], 'StudentY');    
    
    $user = $this->User->getUserByEmail('invalid email');
    $this->assertFalse($user);    
  }
	
  function testFindUserByEmailAndStudentNo() {

    $user = $this->User->findUserByEmailAndStudentNo('email1', 123);
    $this->assertEqual($user['User']['username'], 'StudentY');    
  }		
	
	function testCanRemoveCourse(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
				
		//Test on valid admin and valid course
		##Set up test data
		$admin = $this->User->getByUsername('Admin');
		$canRemove = $this->User->canRemoveCourse($admin, 1);
		$this->assertEqual($canRemove,true);
		
		//Test if instructor can remove a course
		$instructor = $this->User->getByUsername('GSlade');
		$canRemove = $this->User->canRemoveCourse($instructor, 1);
		$this->assertEqual($canRemove, true);
		
	//Test if instructor can remove a course
		$instructor = $this->User->getByUsername('GSlade');
		$canRemove = $this->User->canRemoveCourse($instructor, 3);
		$this->assertEqual($canRemove, false);
		//Test if student can remove a course
		$student = $this->User->getByUsername('StudentY');
		$canRemove = $this->User->canRemoveCourse($student, 1);
		$this->assertEqual($canRemove, false);
		
		//Test if user can remove an invalid course, course_id==23123 (invalid)
		##Student
		$canRemove = $this->User->canRemoveCourse($student, 23123);
		$this->assertEqual($canRemove, false);
		##Instructor
		$canRemove = $this->User->canRemoveCourse($instructor, 23123);
		$this->assertEqual($canRemove, false);
		##Admin, should really return false
		$canRemove = $this->User->canRemoveCourse($admin, 23123);
		$this->assertEqual($canRemove, true);				
		
		//Test invalid user
		$canRemove = $this->User->canRemoveCourse('invalid', 1);
		$this->assertEqual($canRemove, false);

		//Test null inputs
		$canRemove = $this->User->canRemoveCourse(null,1);
		$this->assertEqual($canRemove, false);
		$canRemove = $this->User->canRemoveCourse($student, null);
		$this->assertEqual($canRemove, false);
		$canRemove = $this->User->canRemoveCourse(null, null);
		$this->assertEqual($canRemove, false);		
		
	}
	
	function TestGetUserIdByStudentNo(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Test function on valid users
		$student1ID = $this->User->getUserIdByStudentNo(123);
		$student2ID = $this->User->getUserIdByStudentNo(100);
		$instructorID =$this->User->getUserIdByStudentNo(321);
		$adminID = $this->User->getUserIdByStudentNo(111);
		$this->assertEqual($student1ID, 3);
		$this->assertEqual($student2ID, 4);
		$this->assertEqual($instructorID, 5);
		$this->assertEqual($adminID, 8);
		
		//Test function on invalid users
		$invalid = $this->User->getUserIdByStudentNo(23123123);
		$this->assertEqual($invalid, $empty);
		//Test function on null input
		$null =  $this->User->getUserIdByStudentNo(null);
		$this->assertEqual($null, $empty);

	}

	function TestGetRoleText(){
		
		$this->User =& ClassRegistry::init('User');
		$empty = null;

		$student = $this->User->getRoleText('S');
		$this->assertEqual($student, 'Student');
		
		$TA = $this->User->getRoleText('T');
		$this->assertEqual($TA,'TA');
		
		$instructor = $this->User->getRoleText('I');
		$this->assertEqual($instructor, 'Instructor');
		
		$admin = $this->User->getRoleText('A');
		$this->assertEqual($admin, 'Administrator');
		
		$invalidRole = $this->User->getRoleText('X');
		$this->assertEqual($invalidRole, 'Unknown');
		
		$nullInput = $this->User->getRoleText(null);
		$this->assertEqual($nullInput, $empty);

	}

	function TestGetRoleById(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
	
		//user_id==1 : role(superadmin)
		$superAdminRole=$this->User->getRoleById(8);
		$this->assertEqual($superAdminRole, 'superadmin');
		
		//user_id==3 : role(student)
		$studentRole=$this->User->getRoleById(3);
		$this->assertEqual($studentRole, 'student');
		
		//user_id==13 : role(instructor)
		$instructorRole=$this->User->getRoleById(1);
		$this->assertEqual($instructorRole, 'instructor');
		
		//user_id==20 : role(admin)
		$adminRole = $this->User->getRoleById(9);
		$this->assertEqual($adminRole,'admin');
		
		//user_id==9 : role(unassigned)
		$unassignedRole = $this->User->getRoleById(13);
		$this->assertEqual($unassignedRole, $empty);
		
		//null input
		$nullInput = $this->User->getRoleById(null);
		$this->assertEqual($nullInput, $empty);

	}
	
	function TestGetMembersByGroupId(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Set up test data students

		$group1=$this->User->getMembersByGroupId(1);
		$group2=$this->User->getMembersByGroupId(2);
		$this->assertEqual($group1[0]['User']['username'], $group2[0]['User']['username']);
		
		//Run tests on group with multiple members
		$groupMembers = $this->User->getMembersByGroupId(1);
		$groupMembersArray=array();
		foreach($groupMembers as $student){
			array_push($groupMembersArray, $student['User']['username']);
		}
		$expect=array('StudentZ', 'StudentY');
		$this->assertEqual($groupMembersArray, $expect);
		
		//Run test on group with NO members
		$groupMembers = $this->User->getMembersByGroupId(3);
		$this->assertEqual($groupMembers, $empty);
		
		//Run test on invalid group; "group_id==213123" (invalid)
		$invalidGroup = $this->User->getMembersByGroupId(213123);
		$this->assertEqual($invalidGroup, $empty);
		
		//Run test on NULL group_id input
		$nullGroupId = $this->User->getMembersByGroupId(null);
		$this->assertEqual($nullGroupId, $empty);
	}


	function TestGetStudentsNotInGroup(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Test function for valid users and course
		##Run test
		$studentNotInGroup = $this->User->getStudentsNotInGroup(3);
		$studentsArray=array();
		foreach($studentNotInGroup as $student){
			array_push($studentsArray, $student['User']['username']);		
		}
		
		$expect = array('StudentZ', 'StudentY',);
		$this->User->printHelp($studentNotInGroup);
		$this->assertEqual($studentsArray, $expect);		

		//Test function for invalid course_id input, course_id==99999 (invalid)
		$studentNotInGroup = $this->User->getStudentsNotInGroup(99999);
		$this->assertEqual($studentNotInGroup, $empty);
		
		//Test function for NULL course_id input
		$studentNotInGroup = $this->User->getStudentsNotInGroup(null);
		$this->assertEqual($studentNotInGroup, $empty);
		

	}

	function TestFindUserByid(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		##For students
		$student=$this->User->findUserByid(3);
		$this->assertEqual($student['User']['username'],"StudentY");
		##For instructors
		$instructors = $this->User->findUserByid(1);
		$this->assertEqual($instructors['User']['username'],"GSlade");
		##For admin
		$admin = $this->User->findUserByid(8);
		$this->assertEqual($admin['User']['username'],"Admin"); 
		##For Super-admin
		$superAdmin = $this->User->findUserByid(9);
		$this->assertEqual($superAdmin['User']['username'],"SuperAdmin");
		
		//Test function for invalid user_id
		##user_id==323123 (invalid)
		$invalidUser = $this->User->findUserByid(323123);
		$this->assertEqual($invalidUser, $empty);
		
		//Test function for null input
		$nullInput = $this->User->findUserByid(null);
		$this->assertEqual($nullInput, $empty);
		
		
	}
	
	function testGetRoles() {
	 	  
	  $roles = $this->User->getRoles(1);
		$role = array();
	  foreach($roles as $r){
			array_push($role, $r);		
		}
    
		$this->assertEqual($role, array('instructor'));		
	  
    $roles = $this->User->getRoles(3);   
		$role = array();
	  
		foreach($roles as $r){
			array_push($role, $r);		
		}
    
    $this->assertEqual($role, array('student'));   
         
	  $role = $this->User->getRoles(999);
    $this->assertFalse($role);
    
	  $role = $this->User->getRoles(null);
    $this->assertFalse($role);    
     
	}

	function testHasTitle() {
	  
	  $roles = $this->User->getRoles(3);
	  $roles = $this->User->hasTitle($roles);
	 	$this->assertFalse($roles);

	  $roles = $this->User->getRoles(1);
	  $roles = $this->User->hasTitle($roles);
	 	$this->assertTrue($roles);	 	
	 	
	  $roles = $this->User->getRoles(999);
	  $roles = $this->User->hasTitle($roles);
	 	$this->assertFalse($roles);		 	
	 	
	  $roles = $this->User->getRoles(null);
	  $roles = $this->User->hasTitle($roles);
	 	$this->assertFalse($roles);	
	 	
	}

	function testHasStudentNo() {
	  
	  $roles = $this->User->getRoles(3);
	  $roles = $this->User->hasStudentNo($roles);
	 	$this->assertTrue($roles);

	  $roles = $this->User->getRoles(1);
	  $roles = $this->User->hasStudentNo($roles);
	 	$this->assertFalse($roles);	 	
	 	
	  $roles = $this->User->getRoles(999);
	  $roles = $this->User->hasStudentNo($roles);
	 	$this->assertFalse($roles);		 	
	 	
	  $roles = $this->User->getRoles(null);
	  $roles = $this->User->hasStudentNo($roles);
	 	$this->assertFalse($roles);		  
	 	  
	}
			
	function TestGetRolesByRole(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		$this->flushDatabase();
		
		$roles = array('Admin','Instructor','Student');
		$temp=$this->User->getRolesByRole($roles);
		$this->assertEqual($roles['0'], 'Admin');
		$this->assertEqual($roles['1'], 'Instructor');
		$this->assertEqual($roles['2'], 'Student');
	}

	function testGetInstructors() {	  
	
	  $instructors = $this->User->GetInstructors('all', array());

		$instructor = array();
	  foreach($instructors as $c){
      array_push($instructor, $c['User']['id']);		
	  }
	  $this->assertEqual($instructor, array(5,6,7,2,1));	  
	}
			
	//is not used anywhere
	
	function TestRegisterEnrolment(){
		/* TO DO */
		
		/*
		$this->User =& ClassRegistry::init('User');
		$this->Course =& ClassRegistry::init('Course');
		$empty=null;
//		$this->flushDatabase();

		//Test for valid instructor and course
		//Set up test data
	//	$this->createUserHelper(1,'KevinLuk','S','Password');
	//	$this->createUserHelper(2,'GSlade','I','Password');
//		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		//Run tests
		$this->User->registerEnrolment(3,3);
		$students=$this->User->getEnrolledStudentsForList(3);
		$this->assertEqual($students[0],'StudentY');
		*/
	}
	
	function TestDropEnrolment(){
		
		$this->User =& ClassRegistry::init('User');
		$this->Course =& ClassRegistry::init('Course');
		$empty=null;
//	$this->flushDatabase();
		
		//set up test data
//	$this->createUserHelper(1,'KevinLuk','S','Password');
//		$this->createUserHelper(2,'GSlade','I','Password');
	//	$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		//Run tests
		$this->User->registerEnrolment(1,1);
		$this->User->DropEnrolment(1,1);
		$enrolCount=$this->User->getEnrolledStudentsForList(1);
		$this->User->printHelp($enrolCount);
	}
	
##########################################################################################################     
##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
##########################################################################################################        
	
	
	
	function deleteAllTuples($table){

		$this->User= & ClassRegistry::init('User');
		$sql = "DELETE FROM $table";
		$this->User->query($sql);
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
}

?>
