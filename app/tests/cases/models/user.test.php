
<?php

class UserTTestCase extends CakeTestCase {

	function testGetByUsername(){
		
		$this->User= & ClassRegistry::init('User');
		$empty=null;
		$this->flushDatabase();
	
		//Test on valid student input
		##Set up test data
		$this->createUserHelper(1,'KevinLuk','S','Password');
		##Run tests
		$studentName = $this->User->getByUsername('KevinLuk');
		$this->assertEqual($studentName['User']['username'], "KevinLuk");
		
		//Test on valid instructor input
		##Set up test data
		$this->createUserHelper(2,'Peterson','I','Password');
		##Run Tests
		$instructorName = $this->User->getByUserName('Peterson');
		$this->assertEqual($instructorName['User']['username'], "Peterson");
		
		//Test on valid admin input
		##Set up test data
		$this->createUserHelper(3,'tonychiu','A','Password');
		##Run tests
		$adminName = $this->User->getByUserName('tonychiu');
		$this->assertEqual($adminName['User']['username'], "tonychiu");

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
		$this->createUserHelper(1,'KevinLuk','S','Password1');
		$validStudent = $this->User->findUser('KevinLuk', 'Password1');
		$this->assertEqual($validStudent['User']['username'], "KevinLuk");
		
		##Instructor
		$this->createUserHelper(2,'Peterson','I','Password2');
		$validInstructor = $this->User->findUser('Peterson', 'Password2');
		$this->assertEqual($validInstructor['User']['username'], "Peterson");
		
		##Admin
		$this->createUserHelper(3,'tonychiu','A','Password3');
		$validAdmin = $this->User->findUser('tonychiu', 'Password3');
		$this->assertEqual($validAdmin['User']['username'],"tonychiu");
		
		
		//On invalid user_name
		$invalidUserName = $this->User->findUser('invalidUser', 'Password1');
		$this->assertEqual($invalidUserName, $empty);
		
		//On invalid passWord
		$invalidPassword = $this->User->findUser('KevinLuk', 'invalidPassword');
		$this->assertEqual($invalidPassword, $empty);
		
		//ALL invalid paramters
		$allInvalid = $this->User->findUser('invalid', 'invalid');
		$this->assertEqual($allInvalid, $empty);
		
		//null inputs
		$nullInput = $this->User->findUser(null, 'password1');
		$this->assertEqual($nullInput, $empty);
		
		$nullInput = $this->User->findUser( 'KevinLuk' , null);
		$this->assertEqual($nullInput, $empty);
		
		$nullInput = $this->User->findUser(null, null);
		$this->assertEqual($nullInput, $empty);
		
		//clear data tables
		$this->flushDatabase();
		
		
		//On duplicate password
		$this->createUserHelper(1,'KevinLuk','S','duplicate');
		$this->createUserHelper(2,'Steven','S','duplicate');
		$checkDuplicate1 = $this->User->findUser('KevinLuk', 'duplicate');
		$checkDuplicate2 = $this->User->findUser('Steven', 'duplicate');
		##run tests
		$this->assertEqual($checkDuplicate1['User']['username'], "KevinLuk");
		$this->assertEqual($checkDuplicate2['User']['username'], "Steven");
		
		
		
		$this->flushDatabase();
	}
	
	
	function testFindUserByStudentNo(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;	
		
		//Test findUserByStudentNo() on valid users
		
		##Students
		$this->createUserHelper(1,'KevinLuk','S','password',123);
		$student = $this->User->findUserByStudentNo(123);
		$this->assertEqual($student['User']['username'], "KevinLuk");
		
		##Instructors
		$this->createUserHelper(2,'GSlade','I','password',321);
		$instructors = $this->User->findUserByStudentNo(321);
		$this->assertEqual($instructors['User']['username'], "GSlade");
      	
		##Admin
		$this->createUserHelper(3, 'tonychiu','A','password',111);
		$admin = $this->User->findUserByStudentNo(111);
		$this->assertEqual($admin['User']['username'], "tonychiu");
		
		##Test studentNo==0
		$this->createUserHelper(4, 'Zero','S','password',0);
		$zero  = $this->User->findUserByStudentNo(0);
		$zero1 = $this->User->findUserByStudentNo(000000); 
		$this->assertEqual($zero['User']['username'], $zero1['User']['username']);
		
		//Test invalid student number : 2332353 (invalid)
		$invalidStudentNo = $this->User->findUserByStudentNo(2332353);
		$this->assertEqual($invalidStudentNo, $empty);
		
		//Test null student number input
		$nullInput = $this->User->findUserByStudentNo(null);
		$this->assertEqual($nullInput, $empty);
		
		
		//erase data
		$this->flushDatabase();
	}
	
	
	function TestGetEnrolledStudents() {

		$this->User =& ClassRegistry::init('User');
		$empty=null;	
		
		//Test on a valid course with some student enrollment
		##Set up test data
		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		$this->createUserHelper(1,'KevinLuk','S','password',1);
		$this->createUserHelper(2,'Steven','S','password',2);
		$this->createUserHelper(3,'Thomas','S','password',3);
		$this->createUserHelper(4,'Ruth','S','password',4);
		$this->enrollUserHelper(1,1,1,'S');
		$this->enrollUserHelper(2,2,1,'S');
		$this->enrollUserHelper(3,3,1,'S');
		$this->enrollUserHelper(4,4,1,'S');
		$this->createUserHelper(5,'Instructor','I','password',5);
		$this->enrollUserHelper(5,5,1,'I');
		##Run tests
		$enrolledStudentList = $this->User->getEnrolledStudents(1);
		$enrolledStudentArray=array();
		foreach($enrolledStudentList as $student){
			array_push($enrolledStudentArray, $student['User']['username']);
		}		
		$expect = array('KevinLuk','Steven','Thomas','Ruth');
		$this->assertEqual($enrolledStudentArray, $expect);
		
		//Test a course with no students enrolled
		$this->createCoursesHelper(2, 'Math321', 'AnalysisII');
		$enrolledStudentList = $this->User->getEnrolledStudents(2);
		$this->assertEqual($enrolledStudentList, $empty);
		
		//Test an invalid corse, course_id==231321 (invalid)
		$invalidCourse = $this->User->getEnrolledStudents(231321);
		$this->assertEqual($invalidCourse, $empty);
		
		
		$this->flushDatabase();
	}
	
	
	function TestGetEnrolledStudentsForList()
	{
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Test on a valid course with some student enrollment
		##Set up test data
		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		$this->createUserHelper(1,'KevinLuk','S','password',1);
		$this->createUserHelper(2,'Steven','S','password',2);
		$this->createUserHelper(3,'Thomas','S','password',3);
		$this->createUserHelper(4,'Ruth','S','password',4);
		$this->enrollUserHelper(1,1,1,'S');
		$this->enrollUserHelper(2,2,1,'S');
		$this->enrollUserHelper(3,3,1,'S');
		$this->enrollUserHelper(4,4,1,'S');
		$this->createUserHelper(5,'Instructor','I','password',5);
		$this->enrollUserHelper(5,5,1,'I');
		
		$temp = $this->User->getEnrolledStudentsForList(1);
		$this->User->printHelp($temp);

	}

	
	function TestCanRemoveCourse(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		$this->flushDatabase();
		
		//Test on valid admin and valid course
		##Set up test data
		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		$this->createUserHelper(1,'tonychiu','A');
		$admin = $this->User->getByUsername('tonychiu');
		$canRemove = $this->User->canRemoveCourse($admin, 1);
		$this->assertEqual($canRemove,true);
		
		//Test if instructor can remove a course
		$this->createUserHelper(2,'GSlade','I');
		$instructor = $this->User->getByUsername('GSlade');
		$canRemove = $this->User->canRemoveCourse($instructor, 1);
		$this->assertEqual($canRemove, false);
		
		//Test if student can remove a course
		$this->createUserHelper(3,'KevinLuk','S');
		$student = $this->User->getByUsername('KevinLuk');
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
		$this->assertEqual($canRemove, false);				
		
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
		
		
		//Clear database
		$this->flushDatabase();
		
	}
	
	function TestGetUserIdByStudentNo(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		
		//Test function on valid users
		##Set up test data
		$this->createUserHelper(1,'KevinLuk','S','password',100);
		$this->createUserHelper(2,'Steven','S','password',200);
		$this->createUserHelper(3,'GSlade','I','password',300);
		$this->createUserHelper(4,'tonychiu','A','password',400);
		##Run tests
		$student1ID = $this->User->getUserIdByStudentNo(100);
		$student2ID = $this->User->getUserIdByStudentNo(200);
		$instructorID =$this->User->getUserIdByStudentNo(300);
		$adminID = $this->User->getUserIdByStudentNo(400);
		$this->assertEqual($student1ID, 1);
		$this->assertEqual($student2ID, 2);
		$this->assertEqual($instructorID, 3);
		$this->assertEqual($adminID, 4);
		
		//Test function on invalid users
		$invalid = $this->User->getUserIdByStudentNo(23123123);
		$this->assertEqual($invalid, $empty);
		//Test function on null input
		$null =  $this->User->getUserIdByStudentNo(null);
		$this->assertEqual($null, $empty);
		
		
		//Clear database
		$this->flushDatabase();
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
		$this->flushDatabase();
		
		//Set up test data
		$this->createUserHelper(1,'KevinLuk','SA','password',100);
		$this->createUserHelper(3,'Steven','S','password',200);
		$this->createUserHelper(13,'GSlade','I','password',300);
		$this->createUserHelper(20,'tonychiu','A','password',400);
		
		//user_id==1 : role(superadmin)
		$superAdminRole=$this->User->getRoleById(1);
		$this->assertEqual($superAdminRole, 'superadmin');
		
		//user_id==3 : role(student)
		$studentRole=$this->User->getRoleById(3);
		$this->assertEqual($studentRole, 'student');
		
		//user_id==13 : role(instructor)
		$instructorRole=$this->User->getRoleById(13);
		$this->assertEqual($instructorRole, 'instructor');
		
		//user_id==20 : role(admin)
		$adminRole = $this->User->getRoleById(20);
		$this->assertEqual($adminRole,'admin');
		
		//user_id==9 : role(unassigned)
		$unassignedRole = $this->User->getRoleById(9);
		$this->assertEqual($unassignedRole, $empty);
		
		//null input
		$nullInput = $this->User->getRoleById(null);
		$this->assertEqual($nullInput, $empty);

	}
	
	function TestGetMembersByGroupId(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		$this->flushDatabase();
		
		//Set up test data students
		$this->createUserHelper(1,'KevinLuk','S','password',100);
		$this->createUserHelper(2,'Steven','S','password',200);
		$this->createUserHelper(3,'Rod','S','password',300);
		$this->createUserHelper(4,'Tod','S','password',400);
		$this->createGroupHelper(1, 1);
		$this->createGroupHelper(2, 2);
		//Add members to group 1
		$this->addGroupMemberHelper(1,1,1);
		$this->addGroupMemberHelper(2,1,2);
		$this->addGroupMemberHelper(3,1,3);
		$this->addGroupMemberHelper(4,1,4);
		
		//Test function on students assigned to multiple groups
		$this->createUserHelper(5,'Alex','S','password',500);
		$this->createGroupHelper(3, 3);
		$this->createGroupHelper(4, 4);
		$this->addGroupMemberHelper(5,3,5);
		$this->addGroupMemberHelper(5,4,5);
		$group3=$this->User->getMembersByGroupId(3);
		$group4=$this->User->getMembersByGroupId(4);
		$this->assertEqual($group3[0]['User']['username'], $group4[0]['User']['username']);
		
		//Run tests on group with multiple members
		$groupMembers = $this->User->getMembersByGroupId(1);
		$groupMembersArray=array();
		foreach($groupMembers as $student){
			array_push($groupMembersArray, $student['User']['username']);
		}
		$expect=array('KevinLuk', 'Steven','Rod', 'Tod');
		$this->assertEqual($groupMembersArray, $expect);
		
		//Run test on group with NO members
		$groupMembers = $this->User->getMembersByGroupId(2);
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
		$this->flushDatabase();
		
		//Test function for valid users and course
		##Create users and course data
		$this->createUserHelper(1,'KevinLuk','S','password',100);
		$this->createUserHelper(2,'Steven','S','password',200);
		$this->createUserHelper(3,'Rod','S','password',300);
		$this->createUserHelper(4,'Tod','S','password',400);
		$this->createGroupHelper(1, 1);
		##Add members to group one; only add students 1 and 2
		$this->addGroupMemberHelper(1,1,1);
		$this->addGroupMemberHelper(2,1,2);
		##Run test
		$studentNotInGroup = $this->User->getStudentsNotInGroup(1);
		$studentsArray=array();
		foreach($studentNotInGroup as $student){
			array_push($studentsArray, $student['U']['username']);		
		}
		$expect = array('Rod', 'Tod');
		$this->User->printHelp($studentNotInGroup);
		$this->assertEqual($studentsArray, $expect);		

		//Test function for invalid course_id input, course_id==99999 (invalid)
		$studentNotInGroup = $this->User->getStudentsNotInGroup(99999);
		$this->assertEqual($studentNotInGroup, $empty);
		
		//Test function for NULL course_id input
		$studentNotInGroup = $this->User->getStudentsNotInGroup(null);
		$this->assertEqual($studentNotInGroup, $empty);
		
		
		//clear data
		$this->flushDatabase();
	}

	function TestFindUserByid(){
		
		$this->User =& ClassRegistry::init('User');
		$empty=null;
		$this->flushDatabase();
		
		//Test function for valid user_id
		##Set up test data
		$this->createUserHelper(1,'KevinLuk','S','password',100);
		$this->createUserHelper(2,'Steven','S','password',200);
		$this->createUserHelper(3,'GSlade','I','password',300);
		$this->createUserHelper(4,'tonychiu','A','password',400);
		$this->createUserHelper(5,'superAdmin','SA','password',500);
		
		##For students
		$student=$this->User->findUserByid(1);
		$this->assertEqual($student['User']['username'],"KevinLuk");
		##For instructors
		$instructors = $this->User->findUserByid(3);
		$this->assertEqual($instructors['User']['username'],"GSlade");
		##For admin
		$admin = $this->User->findUserByid(4);
		$this->assertEqual($admin['User']['username'],"tonychiu"); 
		##For Super-admin
		$superAdmin = $this->User->findUserByid(5);
		$this->assertEqual($superAdmin['User']['username'],"superAdmin");
		
		//Test function for invalid user_id
		##user_id==323123 (invalid)
		$invalidUser = $this->User->findUserByid(323123);
		$this->assertEqual($invalidUser, $empty);
		
		//Test function for null input
		$nullInput = $this->User->findUserByid(null);
		$this->assertEqual($nullInput, $empty);
		
		
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
	
	function TestRegisterEnrolment(){
		
		$this->User =& ClassRegistry::init('User');
		$this->Course =& ClassRegistry::init('Course');
		$empty=null;
		$this->flushDatabase();

		//Test for valid instructor and course
		//Set up test data
		$this->createUserHelper(1,'KevinLuk','S','Password');
		$this->createUserHelper(2,'GSlade','I','Password');
		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		//Run tests
		$this->User->registerEnrolment(1,1);
		$students=$this->User->getEnrolledStudentsForList(1);
		$this->assertEqual($students[0],'KevinLuk');
	}
	
	function TestDropEnrolment(){
		
		$this->User =& ClassRegistry::init('User');
		$this->Course =& ClassRegistry::init('Course');
		$empty=null;
		$this->flushDatabase();
		
		//set up test data
		$this->createUserHelper(1,'KevinLuk','S','Password');
		$this->createUserHelper(2,'GSlade','I','Password');
		$this->createCoursesHelper(1, 'Math320', 'AnalysisI');
		//Run tests
		$this->User->registerEnrolment(1,1);
		$this->User->DropEnrolment(1,1);
		$enrolCount=$this->User->getEnrolledStudentsForList(1);
		$this->User->printHelp($enrolCount);
	}
	
##########################################################################################################     
##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
##########################################################################################################        
	
	
	function createGroupHelper($id='', $group_num = ''){
		
		$this->User= & ClassRegistry::init('User');
		
		$sql = "INSERT INTO groups VALUES($id, $group_num, NULL, NULL, 'A', '0' ,'0000-00-00 00:00:00', NULL, NULL )";
		$this->User->query($sql);
	}
	
	function addGroupMemberHelper($id='',$group_id='', $user_id=''){
		
		$this->User= & ClassRegistry::init('User');
		
		$sql = "INSERT INTO groups_members VALUES($id, $group_id, $user_id)";
		$this->User->query($sql);
	}
	
  	function createUserHelper( $id ='' , $username='' , $role='', $password='' , $studentNo = '', $email=''){
	
  		$this->User= & ClassRegistry::init('User');
  		
		$query = "INSERT INTO users VALUES ('$id','$role' ,'$username' , '$password', NULL , NULL , '$studentNo' , NULL , '$email' , NULL , NULL , NULL , 'A', '0', '0000-00-00 00:00:00', NULL , NULL )";		
		$this->User->query($query);
		
		$user_role;
		switch($role){
			
			case 'SA': $user_role=1;
					break;
			case 'A': $user_role=2;
					break;
			case 'I': $user_role=3;
					break;
			case 'S': $user_role=4;
					break; 
			default: $user_role=0;
		}
		
		$query = "INSERT INTO roles_users VALUES ('$id', $user_role, '$id', '0000-00-00 00:00:00','0000-00-00 00:00:00' )";
		$this->User->query($query);
	}	
	
	function enrollUserHelper( $id=null, $user_id=null, $course_id=null, $role=''){
		
		$this->User= & ClassRegistry::init('User');
		
		if($role=='S'){
		$query = "INSERT INTO user_enrols VALUES ('$id', '$course_id', '$user_id','A', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->User->query($query);
		}
		
		if($role=='I'){
		$query = "INSERT INTO user_courses VALUES('$id' , '$user_id', '$course_id', 'O', 'A', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->User->query($query);
		}
	}
	
	function createCoursesHelper($id=null, $course=null, $title=null){
		
		$this->User= & ClassRegistry::init('User');
		
		$sql = "INSERT INTO courses VALUES ( '$id', '$course', '$title', NULL , 'off', NULL , 'A', '0', '0000-00-00 00:00:00', NULL , NULL , '0' ) "; 
		$this->User->query($sql);		
		
	}
	
	function createInactiveCoursesHelper($id=null, $course=null, $title=null){
		
		$this->User= & ClassRegistry::init('User');
		
		$sql = "INSERT INTO courses VALUES ( '$id', '$course', '$title', NULL , 'off', NULL , 'I', '0', '0000-00-00 00:00:00', NULL , NULL , '0' ) "; 
		$this->User->query($sql);		
		
	}
	
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