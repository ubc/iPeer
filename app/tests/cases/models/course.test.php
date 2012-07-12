<?php
App::import('Model', 'Course');

class CourseTestCase extends CakeTestCase {
    public $name = 'Course';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.user_faculty', 'app.faculty', 'app.department',
        'app.course_department', 'app.user_tutor'
    );
    public $Course = null;

    function startCase()
    {
        $this->Course = ClassRegistry::init('Course');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }

    function endTest($method)
    {
        $this->flushDatabase();
    }

    function testCourseInstance()
    {
        $this->assertTrue(is_a($this->Course, 'Course'));
    }


    function testGetAllInstructors()
    {
        $instructors = $this->Course->getAllInstructors('all', array());
        $instructorIds = Set::extract('/User/id', $instructors);
        sort($instructorIds);
        $this->assertEqual($instructorIds, array('1', '2', '5', '6', '7'));
    }

    function testGetCourseByInstructor()
    {
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

    function testGetCourseName()
    {
        $empty=null;
        //	$this->flushDatabase();

        //"Math320"
        //	$this->Course->createCoursesHelper(5, 'Math320', 'Analysis I');
        $courseName = $this->Course->getCourseName(1);
        $this->assertEqual($courseName, 'Math303');

        //Invalid course_id
        $courseName = $this->Course->getCourseName(1231);
        $this->assertEqual($courseName, $empty);

        //Test null course_id input
        $courseName = $this->Course->getCourseName(null);
        $this->assertEqual($courseName, $empty);
    }

    function testAddInstructor()
    {
        $empty = null;

        /*
         * Test assigning a valid instructor to a valid course:
         * user_id==13 : "INSTRUCTOR3"
         * course_id==3 : "Math100"
         */
        //Set up test data
        $this->Course->addInstructor(3, 6);
        //Run tests
        $courseTaught = $this->Course->getCourseByInstructor(6);  //function has been tested
        $this->assertEqual($courseTaught[0]['Course']['course'], 'Math100');

        /*
         * Test assigning multiple valid instructors to a single valid course;
         * should successfully add both users.
         * User_id==5 : "INSTRUCTOR1" , User_id==5 : "INSTRUCTOR1"
         * course_id==3 : "Math100"
         */
        //Run Tests
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
        //Set up test data
        $this->Course->addInstructor(231231, 7);
        //Run tests.
        $courseTaughtByIns7 = $this->Course->getCourseByInstructor(7);
        $this->assertEqual($courseTaughtByIns7, $empty);

        /*
         * Test adding an invalid instructor to a valid course
         * User_id==323123 (invalid)
         * Course_id==2 : "Math220"
         */
        //Set up test data
        //Run tests
        $this->Course->addInstructor(2, 323123);
        $courseTaughtByIns323123 = $this->Course->getCourseByInstructor(323123);
        $this->assertEqual($courseTaughtByIns323123, $empty);
    }

    function testDeleteInstructor()
    {
        $empty=null;

        /*
         * Try to delete an valid instructor from a valid assigned course
         * Inputs: Course_id==2 : "Mth321"	(Valid)
         *		   User_id==6 : "INSTRUCTOR2"	(Valid)
         */
        //Run tests
        $this->Course->addInstructor(2, 6);
        $this->Course->deleteInstructor(2, 6);
        $deletedInstructor=$this->Course->getCourseByInstructor(6);
        $this->assertEqual($empty, $deletedInstructor);

        /*
         * Test deleteInstructor() using invalid "course_id" input:
         * Inputs: Course_id==999999 : (Invalid)
         * 		   Course_id==2 : "Math321"	(Valid)
         * 		   User_id==2 : "Peterson"	(Valid)
         */
        //Run tests
        $this->Course->deleteInstructor(999999, 2);
        $invalidCourseInstructor = $this->Course->getCourseByInstructor(999999);
        $this->assertEqual($invalidCourseInstructor, $empty);


        /*
         * Test deleteInstructor() invalid "user_id" input:
         * Inputs: Course_id==2 : "Math321"	(Valid)
         * 		   User_id==13 : 	(inValid)
         */
        //Reused data from previous setup
        $this->Course->deleteInstructor(2, 13);
        $invalidCourseInstructor = $this->Course->getCourseByInstructor(13);
        $this->assertEqual($invalidCourseInstructor, $empty);



        /*
         * Test deleteInstructor on course without instructor
         * Inputs: Course_id==1 : "Math303" (Valid)
         * 		   User_id==4 : "INSTRUCTOR1" 	(Valid)
         */
        //Reused data from previous setup
        $this->Course->addInstructor(1, 5);
        $this->Course->deleteInstructor(1, 5);
        $this->Course->deleteInstructor(1, 5);
        $course= $this->Course->getCourseByInstructor(5);
        $this->assertEqual($course, $empty);


        //Test null inputs;
        //Set up data
        $this->Course->addInstructor(1, 1);
        //Run tests, should have no effects whatsoever
        $this->Course->deleteInstructor(null, 1);
        $this->Course->deleteInstructor(1, null);
        $course = $this->Course->getCourseByInstructor(1);
        $this->assertTrue(in_array('Math303', Set::Extract('/Course/course', $course)));
        //Flush database
        $this->flushDatabase();
    }

    function testGetInactiveCourses()
    {
        $empty=null;

        //set up test data
        //	$this->Course->createInactiveCoursesHelper(1, 'InactiveCourse1', 'Calculus I');
        //	$this->Course->createInactiveCoursesHelper(2, 'InactiveCourse2', 'Calculus II');
        $inactiveCourses = $this->Course->getInactiveCourses();
        $expectedResults = array('InactiveCourse1', 'InactiveCourse2');
        $inactiveCoursesArray=array();
        foreach ($inactiveCourses as $courses) {
            array_push($inactiveCoursesArray, $courses['Course']['course']);
        }
        //Run tests
        $this->assertEqual($inactiveCoursesArray, $expectedResults);
    }

//    function testFindAccessibleCoursesListByUserIdRole()
//    {
//        $empty = null;
//
//        /*
//         * Check findAccessibleCourseList for student
//         * user_id==3 : "StudentY"
//         */
//        //Run Tests
//        $testStudentAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(3, 'S', null);
//        $studentAccessibleCourseArray=array();
//        foreach ($testStudentAccessibleCourses as $course) {
//            array_push($studentAccessibleCourseArray, $course['Course']['course']);
//        }
//        sort($studentAccessibleCourseArray);
//        $expectedCourseIDArray=array('Math303', 'Math321');
//        $this->assertEqual($studentAccessibleCourseArray, $expectedCourseIDArray);
//
//        /*
//         * Check findAccessibleCourseList for instructor
//         * user_id==2 : "Peterson"
//         */
//
//        $this->Course->addInstructor(1, 2);
//        $this->Course->addInstructor(2, 2);
//
//        $testInstructorAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(2, 'I', null);
//        $instructorAccessibleCourseArray=array();
//        foreach ($testInstructorAccessibleCourses as $course) {
//            array_push($instructorAccessibleCourseArray, $course['Course']['course']);
//        }
//        $expectedCourseIDArray = array('Math303', 'Math321', 'Math100');
//        $this->assertEqual($instructorAccessibleCourseArray, $expectedCourseIDArray);
//
//        /*
//         * Check findAccessibleCourseList for admin
//         * user_id==8 : "Admin"
//         * Admin should have access to all course
//         */
//        //Run test
//        $testAdminAccessibleCourses = $this->Course->findAccessibleCoursesListByUserIdRole(8, 'A', null);
//        $adminAccessibleCourseArray=array();
//        foreach ($testAdminAccessibleCourses as $course) {
//            array_push($adminAccessibleCourseArray, $course['Course']['course']);
//        }
//        sort($adminAccessibleCourseArray);
//        $expectedCourseIDArray=array('Math303', 'Math321', 'Math100', 'Math200', 'Math250', 'InactiveCourse1', 'InactiveCourse2');
//        sort($expectedCourseIDArray);
//        $this->assertEqual($adminAccessibleCourseArray, $expectedCourseIDArray);
//
//        /*
//         * Check invlid user_id input;
//         * should return NULL
//         */
//        //Run tests
//        $testInvalidStudentID = $this->Course->findAccessibleCoursesListByUserIdRole(434324, 'S', null);
//        $this->assertEqual($testInvalidStudentID, $empty);
//        $testInvalidInstructorID = $this->Course->findAccessibleCoursesListByUserIdRole(2312124, 'I', null);
//        $this->assertEqual($testInvalidInstructorID, $empty);
//        $testInvaldAdminId = $this->Course->findAccessibleCoursesListByUserIdRole(67868767, 'A', null);
//
//
//    }

    function testFindAccessibleCoursesCount()
    {
        $empty= null;
        /*
         * Check findAccessibleCoursesCount() on valid student input
         * user_id==3 : "StudentY"
         */
        //Run tests
        $testStudentCourseCount = $this->Course->findAccessibleCoursesCount(3, 'S', null);
        ($testStudentCourseCount);
        $this->assertEqual($testStudentCourseCount, 2);

        /*
         * Check findAccessibleCoursesCount() on valid instructor input
         * uder_id==2 : "Peterson"
         */

        $this->Course->addInstructor(2, 2);
        $this->Course->addInstructor(3, 2);
        //Run tests
        $testInstructor = $this->Course->findAccessibleCoursesCount(2, 'I', null);
        $this->assertEqual($testInstructor, 3);

        /*
         * Check findAccessibleCoursesCount for valid admin input
         * user_id==8 (valid admin)
         * Check "user_courses" database table for data validation
         */
        $testAdmin = $this->Course->findAccessibleCoursesCount(8, 'A', null);
        $this->assertEqual($testAdmin, 7);

        /*
         * Check findAccessibleCoursesCount for invalid user_id inputs;
         * ALL return values should be ZERO
         */
        $testInvalidStudentId = $this->Course->findAccessibleCoursesCount(1323123, 'S', null);
        $this->assertEqual($testInvalidStudentId[0][0]['total'], 0);
        $testInvalidInstructorId = $this->Course->findAccessibleCoursesCount(91203, 'I', null);
        $this->assertEqual($testInvalidInstructorId[0][0]['total'], 0);
        $testInvalidAdminId = $this->Course->findAccessibleCoursesCount(123802, 'A', null);
        $this->assertEqual($testInvalidAdminId[0][0]['total'], 0);
    }

    function testGetCourseByCourse()
    {
        //test valid course
        $empty = null;
        $course = $this->Course->getCourseByCourse('Math100', array());
        $expected = 'Math100';
        $this->assertEqual($course[0]['Course']['course'], $expected);
    }

    function testGetCourseByGroupId()
    {
        $this->Course = ClassRegistry::init('Course');

        //test valid group id
        $empty = null;
        $course = $this->Course->getCourseByGroupId(1);
        $this->assertEqual($course['Course']['course'], 'Math303');

        // invalid group id
        $course = $this->Course->getCourseByGroupId(999);
        $this->assertEqual($course, array());
    }

    function testDeleteAll()
    {
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


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////#
    //////////////////////////////////////////////#     HELPER FUNCTIONS     ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////#

    function flushDatabase()
    {
        $this->Course= & ClassRegistry::init('Course');

        $this->deleteAllTuples('courses');
        $this->deleteAllTuples('users');
        $this->deleteAllTuples('user_courses');
        $this->deleteAllTuples('user_enrols');
    }

    function deleteAllTuples($table)
    {
        $sql = "DELETE FROM $table";
        $this->Course->query($sql);
    }

    function toCourseNameArray($userEnrol)
    {
        $courseNameArray = array();
        foreach ($userEnrol as $courses) {
            array_push($courseNameArray, $courses['Course']['course']);
        }
        return $courseNameArray;
    }
}
