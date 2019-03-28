<?php
/* User Test cases generated on: 2012-07-12 16:56:56 : 1342137416*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
    var $fixtures = array(
        'app.user', 'app.evaluation_submission', 'app.event',
        'app.event_template_type', 'app.course', 'app.group',
        'app.group_event', 'app.groups_member', 'app.survey',
        'app.survey_group_set', 'app.survey_group', 'app.survey_group_member',
        'app.question', 'app.response', 'app.survey_question', 'app.user_course',
        'app.user_tutor', 'app.user_enrol', 'app.department', 'app.faculty',
        'app.course_department', 'app.user_faculty', 'app.role', 'app.roles_user',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail', 'app.evaluation_mixeval',
        'app.evaluation_mixeval_detail', 'app.sys_parameter'
    );

    function startCase()
    {
        echo "Start User model test.\n";
        $this->User =& ClassRegistry::init('User');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }

    function endTest($method)
    {
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
        $studentName = $this->User->getByUsername('redshirt0001');
        $this->assertEqual($studentName['User']['username'], "redshirt0001");

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
            'redshirt0027',
            'redshirt0022',
            'redshirt0028',
            'redshirt0017',
            'redshirt0013',
            'redshirt0024',
            'redshirt0011',
            'redshirt0029',
            'redshirt0002',
            'redshirt0001',
            'redshirt0009',
            'redshirt0015',
            'redshirt0003',
        );
        $this->assertTrue(count(array_diff($actual, $expect)) == 0);

        //Test an invalid corse, course_id==231321 (invalid)
        $invalidCourse = $this->User->getEnrolledStudents(231321);
        $this->assertEqual($invalidCourse, null);
    }

    function testGetEnrolledStudentsForList()
    {
        //Test on a valid course with some student enrollment
        $ret = $this->User->getEnrolledStudentsForList(1);
        // should return an array with ids as key and student number + firstname +
        // last name as value
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

    function testHashPassword()
    {
        $input = array('User' => array('password' => 'frogleg'));
        $ret = $this->User->hashPasswords($input);
        $this->assertEqual($ret['User']['password'],
            '6f40a1a25eec7d325310dea310949005');
    }

    function testGetRoleName()
    {
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
        $expected = array('0' => 2, '1' => 3, '2' => 4);
        $this->assertEqual(count(array_diff_assoc($actual, $expected)), 0);
    }

    function testLoadRole() {
        $ret = $this->User->loadRoles(1);
        $this->assertEqual($ret['1'], 'superadmin');
        $ret = $this->User->loadRoles(2);
        $this->assertEqual($ret['3'], 'instructor');
    }

    /*
     * Test adding a student to a course.
     */
    function testAddStudent()
    {
        // Try adding students not yet enrolled, should work
        $ret = $this->User->addStudent(15, 1);
        $this->assertTrue($ret);
        // NOTE: Can't test failure cases as they're being
        // handled by SQL constraints and the fixtures don't
        // duplicate the constraints.
    }

    function testRemoveStudent()
    {
        //Test remove student
        $ret = $this->User->removeStudent(26, 1);
        $this->assertTrue($ret); // operation successful
        $user = $this->User->UserEnrol->field('id',
            array('user_id' => 26, 'course_id' => 1));
        $this->assertFalse($user); // make sure user really is gone

        //Test remove student twice
        $ret = $this->User->removeStudent(26, 1);
        $this->assertFalse($ret); // operation should fail

        //Test remove student from wrong course
        $ret = $this->User->removeStudent(27, 1);
        $this->assertFalse($user); // operation should fail

        //Test remove invalid student from  course
        $ret = $this->User->removeStudent(999, 2);
        $this->assertFalse($user); // operation should fail

        //Test remove valid student from invalid course
        $ret = $this->User->removeStudent(27, 999);
        $this->assertFalse($user); // operation should fail

        //Test remove invalid student from invalid course
        $ret = $this->User->removeStudent(999, 999);
        $this->assertFalse($ret);
    }

    /*
     * Test adding an instructor to a course.
     */
    function testAddInstructor()
    {
        // Try adding students not yet enrolled, should work
        $ret = $this->User->addInstructor(4, 1);
        $this->assertTrue($ret);
        // NOTE: Can't test failure cases as they're being
        // handled by SQL constraints and the fixtures don't
        // duplicate the constraints.
    }

    function testRemoveInstructor()
    {
        //Test remove instructor
        $ret = $this->User->removeInstructor(4, 3);
        $this->assertTrue($ret); // operation successful
        $user = $this->User->UserCourse->field('id',
            array('user_id' => 4, 'course_id' => 3));
        $this->assertFalse($user); // make sure user really is gone

        //Test remove instructor twice
        $ret = $this->User->removeInstructor(4, 3);
        $this->assertFalse($ret); // operation should fail

        //Test remove instructor from wrong course
        $ret = $this->User->removeInstructor(4, 1);
        $this->assertFalse($ret); // operation should fail

        //Test remove invalid instructor from  course
        $ret = $this->User->removeInstructor(999, 2);
        $this->assertFalse($ret); // operation should fail

        //Test remove valid instructor from invalid course
        $ret = $this->User->removeInstructor(4, 999);
        $this->assertFalse($ret); // operation should fail

        //Test remove invalid instructor from invalid course
        $ret = $this->User->removeInstructor(999, 999);
        $this->assertFalse($ret);
    }

    /*
     * Test adding a tutor to a course.
     */
    function testAddTutor()
    {
        // Try adding students not yet enrolled, should work
        $ret = $this->User->addTutor(37, 1);
        $this->assertTrue($ret);
        // NOTE: Can't test failure cases as they're being
        // handled by SQL constraints and the fixtures don't
        // duplicate the constraints.
    }

    function testRemoveTutor()
    {
        //Test remove Tutor
        $ret = $this->User->removeTutor(37, 3);
        $this->assertTrue($ret); // operation successful
        $user = $this->User->UserTutor->field('id',
            array('user_id' => 37, 'course_id' => 3));
        $this->assertFalse($user); // make sure user really is gone

        //Test remove Tutor twice
        $ret = $this->User->removeTutor(37, 3);
        $this->assertFalse($ret); // operation should fail

        //Test remove Tutor from wrong course
        $ret = $this->User->removeTutor(37, 1);
        $this->assertFalse($user); // operation should fail

        //Test remove invalid Tutor from  course
        $ret = $this->User->removeTutor(999, 3);
        $this->assertFalse($user); // operation should fail

        //Test remove valid Tutor from invalid course
        $ret = $this->User->removeTutor(37, 999);
        $this->assertFalse($user); // operation should fail

        //Test remove invalid Tutor from invalid course
        $ret = $this->User->removeTutor(999, 999);
        $this->assertFalse($ret);
    }

    function testGetMembersByGroupId() {
        $users = $this->User->getMembersByGroupId(1);
        $users = Set::sort($users, '{n}.User.id', 'asc');
        $this->assertEqual(Set::extract($users, '/User/id'), array(5,6,7,35));

        // test invalid group id
        $users = $this->User->getMembersByGroupId(999);
        $this->assertFalse($users);

        // test excluding member
        $users = $this->User->getMembersByGroupId(1, 6);
        $users = Set::sort($users, '{n}.User.id', 'asc');
        $this->assertEqual(Set::extract($users, '/User/id'), array(5,7,35));

        // test excluding invalid member
        $users = $this->User->getMembersByGroupId(1, 8);
        $users = Set::sort($users, '{n}.User.id', 'asc');
        $this->assertEqual(Set::extract($users, '/User/id'), array(5,6,7,35));
    }

    /**
     * testSaveAllWithFailedResult
     *
     * Testing cakephp saveall bug. When the failed entry
     * is the last one in the array, cakephp fails to save the rest.
     *
     * @access public
     * @return void
     */
    function testSaveAllWithFailedResult() {
        $data = array(
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist1',
                    'email' => 'noonlist1@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 3)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'redshirt0001',
                    'email' => 'onlist1@example.com',
                    'first_name' => 'On',
                    'last_name' => 'list1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist2',
                    'email' => 'noonlist2@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist2',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
        );

        $result = $this->User->saveAll($data, array('atomic' => false));
        $this->assertTrue($result[0]);
        $this->assertTrue($result[2]);
        $this->assertFalse($result[1]);
        $this->assertEqual($this->User->validationErrors, array(1 => array(
            'username' => 'Duplicate Username found. Please select another.'
        )));

        $usernames = Set::extract($data, '/User/username');
        $sbody = $this->User->find('all', array(
            'conditions' => array('username' => $usernames),
            'fields' => array('User.id', 'username', 'last_name', 'first_name'),
            'contain' => false,
        ));

        $this->assertEqual(count($sbody), 3);
    }

    function testSaveAllWithLastOneFailed() {
        $data = array(
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist1',
                    'email' => 'noonlist1@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 3)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist2',
                    'email' => 'noonlist2@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist2',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'redshirt0001',
                    'email' => 'onlist1@example.com',
                    'first_name' => 'On',
                    'last_name' => 'list1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
        );

        $result = $this->User->saveAll($data, array('atomic' => false));
        $this->assertTrue($result[0]);
        $this->assertTrue($result[1]);
        $this->assertFalse($result[2]);
        $this->assertEqual($this->User->validationErrors, array(2 => array(
            'username' => 'Duplicate Username found. Please select another.'
        )));

        $usernames = Set::extract($data, '/User/username');
        $sbody = $this->User->find('all', array(
            'conditions' => array('username' => $usernames),
            'fields' => array('User.id', 'username', 'last_name', 'first_name'),
            'contain' => false,
        ));
        $this->assertEqual(count($sbody), 3);
    }

    function testSaveAllWithValidationFailed() {
        $data = array(
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist1',
                    'email' => 'noonlist1@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 3)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => 'notonlist2',
                    'email' => 'noonlist2@example.com',
                    'first_name' => 'Not',
                    'last_name' => 'Onlist2',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
            array(
                'User' => array(
                    'id' => 0,
                    'username' => '#####',
                    'email' => 'onlist1@example.com',
                    'first_name' => 'On',
                    'last_name' => 'list1',
                    'student_no' => '',
                ),
                'Role' => array('RoleUser' => array('role_id' => 5)),
            ),
        );

        $result = $this->User->saveAll($data, array('atomic' => false));
        $this->assertTrue($result[0]);
        $this->assertTrue($result[1]);
        $this->assertFalse($result[2]);
        $this->assertEqual($this->User->validationErrors, array(2 => array(
            'username' => 'Usernames may only have letters, numbers, underscore and dot.'
        )));

        $usernames = Set::extract($data, '/User/username');
        $sbody = $this->User->find('all', array(
            'conditions' => array('username' => $usernames),
            'fields' => array('User.id', 'username', 'last_name', 'first_name'),
            'contain' => false,
        ));

        $this->assertEqual(count($sbody), 2);
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

    function testremoveOldStudents() {
        // TODO
    }

    function testGetEnrolledCourses() {
        //Test valid student enrolled in 2 courses
        $courses = $this->User->getEnrolledCourses(7);
        $this->assertEqual($courses, array(1,2));

        //Test valid user not enrolled in courses
        $this->User->removeStudent(20,2);
        $courses = $this->User->getEnrolledCourses(20);
        $this->assertEqual($courses, null);
        $this->User->addStudent(20, 2);

        //Test invalid user
        $courses = $this->User->getEnrolledCourses(999);
        $this->assertEqual($courses, null);
    }

    function testGetEventGroupMembersNoTutors ()
    {
        //Test group, selfeval
        $members = $this->User->getEventGroupMembersNoTutors(1, true, 5);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0002', 'redshirt0003'));

        //Test group, no selfeval, valid used id
        $members = $this->User->getEventGroupMembersNoTutors(1, false, 6);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0003'));

        //Test group, no selfeval, invalid used id
        $members = $this->User->getEventGroupMembersNoTutors(1, false, 999);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0002', 'redshirt0003'));

        //Test invalid group
        $members = $this->User->getEventGroupMembersNoTutors(999, false, 3);
        $this->assertEqual(Set::extract('/User/username', $members), null);
    }

    function testgetFullNames() {
        // TODO
    }

    function testgetUsers() {
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
