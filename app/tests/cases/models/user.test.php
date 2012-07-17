<?php
/* User Test cases generated on: 2012-07-12 16:56:56 : 1342137416*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.course', 'app.group', 'app.group_event', 'app.groups_member', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.user_course', 'app.user_tutor', 'app.user_enrol', 'app.department', 'app.faculty', 'app.course_department', 'app.user_faculty', 'app.role', 'app.roles_user');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function endTest() {
	}

	function testFindUserByidWithField() {
        // no field restrictions, should be a flat array of 
        // only data from the user field, no associations
        $ret = $this->User->findUserByidWithFields(4);
        $this->assertEqual($ret['id'], 4);
        $this->assertEqual($ret['username'], 'instructor3');
        // field restrictions, for some reason, means it's no longer
        // a flat array of data, it includes all the associational data too
        $ret = $this->User->findById(4, array('username'));
        $this->assertEqual(count($ret['User']), 2);
        $this->assertEqual($ret['User']['username'], 'instructor3');
        $this->assertEqual($ret['User']['id'], 4);
	}

	function testGetByUsername() {
        $empty=null;

        //Test on valid student input
        //Run tests
        $studentName = $this->User->getByUsername('65498451');
        $this->assertEqual($studentName['User']['username'], "65498451");

        //Test on valid instructor input
        $instructorName = $this->User->getByUserName('instructor1');
        $this->assertEqual($instructorName['User']['username'], "instructor1");

        //Test on valid admin input
        $adminName = $this->User->getByUserName('admin1');
        $this->assertEqual($adminName['User']['username'], "admin1");

        //Testing invalid inputs; all tests should return NULL
        //invalid username input
        $invalidUsername = $this->User->getByUserName('fadslkfjasdkljf');
        $this->assertEqual($invalidUsername['username'], $empty);

        //null input
        $nullInput = $this->User->getByUserName(null);
        $this->assertEqual($nullInput['username'], $empty);
	}

	function testGetEnrolledStudent() {
        //Run tests
        $enrolledStudentList = $this->User->getEnrolledStudents(1);
        $actual = array();
        foreach ($enrolledStudentList as $student) {
            array_push($actual, $student['User']['username']);
        }
        $expect = array(
            10186039,
            19524032,
            19803030,
            22784037,
            37116036,
            38058020,
            48877031,
            51516498,
            65468188,
            65498451,
            84188465,
            90938044,
            98985481
        );
        $this->assertTrue(count(array_diff($actual, $expect)) == 0);

        //Test an invalid corse, course_id==231321 (invalid)
        $invalidCourse = $this->User->getEnrolledStudents(231321);
        $this->assertEqual($invalidCourse, null);
	}

	function testGetEnrolledStudentsForList() {
        //Test on a valid course with some student enrollment
        //Set up test data
        $ret = $this->User->getEnrolledStudentsForList(1);
        $expected = array(
            '31' => '10186039 Hui Student',
            '26' => '19524032 Bill Student',
            '32' => '19803030 Bowinn Student',
            '21' => '22784037 Nicole Student',
            '17' => '37116036 Edna Student',
            '28' => '38058020 Michael Student',
            '15' => '48877031 Jennifer Student',
            '33' => '51516498 Joe Student',
            '6' => '65468188 Alex Student',
            '5' => '65498451 Ed Student',
            '13' => '84188465 Damien Student',
            '19' => '90938044 Jonathan Student',
            '7' => '98985481 Matt Student'
        );
        $this->assertTrue(count(array_diff_assoc($ret, $expected)) == 0);
	}

	function testHashPassword() {
        $input = array('User' => array('password' => 'frogleg'));
        $ret = $this->User->hashPasswords($input);
        $this->assertEqual($ret['User']['password'], 
            '6f40a1a25eec7d325310dea310949005');
	}

	function testGetRoleName() {
        //user_id==1 : role(superadmin)
        $superAdminRole=$this->User->getRoleName(1);
        $this->assertEqual($superAdminRole, 'superadmin');

        //user_id==5 : role(student)
        $studentRole=$this->User->getRoleName(5);
        $this->assertEqual($studentRole, 'student');

        //user_id==2 : role(instructor)
        $instructorRole=$this->User->getRoleName(2);
        $this->assertEqual($instructorRole, 'instructor');

        //user_id==34 : role(admin)
        $adminRole = $this->User->getRoleName(34);
        $this->assertEqual($adminRole, 'admin');

        //user_id==9999 : role(unassigned)
        $unassignedRole = $this->User->getRoleName(9999);
        $this->assertEqual($unassignedRole, null);
	}

	function testGetRoleId() {
        //user_id==1 : role(superadmin)
        $superAdminRole = $this->User->getRoleId(1);
        $this->assertEqual($superAdminRole, '1');

        //user_id==5 : role(student)
        $studentRole=$this->User->getRoleId(5);
        $this->assertEqual($studentRole, '5');

        //user_id==2 : role(instructor)
        $instructorRole=$this->User->getRoleId(2);
        $this->assertEqual($instructorRole, '3');

        //user_id==34 : role(admin)
        $adminRole = $this->User->getRoleId(34);
        $this->assertEqual($adminRole, '2');

        //user_id==9999 : role(unassigned)
        $unassignedRole = $this->User->getRoleId(9999);
        $this->assertEqual($unassignedRole, null);
	}

	function testGetRole() {
        // NOTE: that we don't officially support multiple roles for
        // one user yet, so that's not tested.
        
        // test single user's role
        $ret = $this->User->getRoles(1);
        $this->assertEqual($ret[1], 'superadmin');
	}

	function testGetInstructor() {
        $actual = $this->User->GetInstructors('all', array());
        $actual = Set::extract('/User/id', $actual);
        $expected = array('0' => 4, '1' => 2, '2' => 3);
        $this->assertTrue(count(array_diff_assoc($actual, $expected) == 0));
	}

	function testLoadRole() {
        $ret = $this->User->loadRoles(1);
        $this->assertEqual($ret['1'], 'superadmin');
        $ret = $this->User->loadRoles(2);
        $this->assertEqual($ret['3'], 'instructor');
	}

	function testGetCourseTutorsForList() {
        // TODO
	}

	function testRegisterRole() {
        // TODO
	}

	function testGetMyCourse() {
        // TODO
	}

	function testGetMyCourseList() {
        // TODO
	}

	function testAddUserByArray() {
        // TODO
	}

	function testGetCurrentLoggedInUser() {
        // can't test, no session without a browser!
	}

	function testGetInstance() {
        // can't test, no session without a browser!
	}

	function testStore() {
        // can't test, no session without a browser!
	}

	function testGet() {
        // can't test, no session without a browser!
	}

	function testIsLoggedIn() {
        // can't test, no session without a browser!
	}

	function testHasRole() {
        // can't test, no session without a browser!
	}

	function testGetRoleArray() {
        // can't test, no session without a browser!
	}

	function testGetPermission() {
        // can't test, no session without a browser!
	}

	function testHasPermission() {
        // can't test, no session without a browser!
	}

}
