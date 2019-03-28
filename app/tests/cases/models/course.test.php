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
        'app.course_department', 'app.user_tutor', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.sys_parameter', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start Course model test.\n";
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
    }

    function testCourseInstance()
    {
        $this->assertTrue(is_a($this->Course, 'Course'));
    }

    function testGetCourseByInstructor()
    {
        $empty = null;

        // test courses taught by instructor1
        $course = $this->Course->getCourseByInstructor(2);

        $this->assertEqual($course[0]['Course']['course'], "MECH 328");
        $this->assertEqual(
            $course[0]['Course']['title'],
            "Mechanical Engineering Design Project"
        );

        // Testing invalid instructor user_id
        $instructor = $this->Course->getCourseByInstructor(312321);
        $this->assertEqual($instructor, $empty);


        // Testing null inputs
        $instructor = $this->Course->getCourseByInstructor(null);
        $this->assertEqual($instructor, $empty);
    }

    function testGetCourseByUserIdFilterPermission() {
        // test courses admin'd by admin1 (Fac of AppSci)
        $course = $this->Course->getAllAccessibleCourses(34, Course::FILTER_PERMISSION_FACULTY);
        $this->assertEqual($course[0]['Course']['course'], "APSC 201");
        $this->assertEqual($course[1]['Course']['course'], "CPSC 101"); // is both AppSci and Sci
        $this->assertEqual($course[2]['Course']['course'], "MECH 328");
        $this->assertEqual(sizeof($course), 3);

        // test courses admin'd by admin2 (Fac of Science)
        $course = $this->Course->getAllAccessibleCourses(38, Course::FILTER_PERMISSION_FACULTY);
        $this->assertEqual($course[0]['Course']['course'], "CPSC 101");
        $this->assertEqual($course[1]['Course']['course'], "CPSC 404");
        $this->assertEqual(sizeof($course), 2);

        // test courses admin'd (Fac of AppSci) or taught by admin3
        $course = $this->Course->getAllAccessibleCourses(39, Course::FILTER_PERMISSION_FACULTY);
        $this->assertEqual($course[0]['Course']['course'], "APSC 201"); //all sorted alphabetically
        $this->assertEqual($course[1]['Course']['course'], "CPSC 101");
        $this->assertEqual($course[2]['Course']['course'], "CPSC 404");
        $this->assertEqual($course[3]['Course']['course'], "MECH 328");
        $this->assertEqual(sizeof($course), 4);

        // test to get first course admin'd (Fac of AppSci) or taught by admin3
        $course = $this->Course->getAllAccessibleCourses(34, Course::FILTER_PERMISSION_FACULTY, 'first');
        $this->assertEqual($course['Course']['course'], "APSC 201"); //all sorted alphabetically

        // test courses admin'd (Fac of AppSci) or taught by admin4 - both admin and teach APSC 201
        $course = $this->Course->getAllAccessibleCourses(40, Course::FILTER_PERMISSION_FACULTY);
        $this->assertEqual($course[0]['Course']['course'], "APSC 201"); //admin'd sorted alphabetically
        $this->assertEqual($course[1]['Course']['course'], "CPSC 101");
        $this->assertEqual($course[2]['Course']['course'], "MECH 328");
        $this->assertEqual(sizeof($course), 3);

        // test courses taught by instructor3
        $course = $this->Course->getAllAccessibleCourses(4, Course::FILTER_PERMISSION_OWNER);
        $this->assertEqual($course[0]['Course']['course'], "APSC 201");
        $this->assertEqual($course[1]['Course']['course'], "CPSC 101");
        $this->assertEqual(sizeof($course), 2);

        $course = $this->Course->getAllAccessibleCourses(4, Course::FILTER_PERMISSION_OWNER, 'first');
        $this->assertEqual($course['Course']['course'], "APSC 201");
    }


    function testGetCourseName()
    {
        $empty=null;

        // Valid course
        $courseName = $this->Course->getCourseName(1);
        $this->assertEqual($courseName, 'MECH 328');

        // Invalid course_id
        $courseName = $this->Course->getCourseName(1231);
        $this->assertEqual($courseName, $empty);

        // Test null course_id input
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
        $this->Course->addInstructor(2, 2);
        //Run tests
        $courseTaught = $this->Course->getCourseByInstructor(2);// assume tested
        $this->assertEqual($courseTaught[1]['Course']['course'], 'MECH 328');

        /*
         * Test adding a valid instructor to an invalid course;
         * User_id==15 : "Peterson" (valid)
         * Course_id=231231: (invalid)
         */
        //Set up test data
        $this->Course->addInstructor(301430, 3);
        //Run tests.
        $ret = $this->Course->getCourseByInstructor(3);
        $this->assertEqual(count($ret), 1);

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
        $this->assertTrue(in_array('MECH 328',
            Set::Extract('/Course/course', $course)));
    }

    function testGetInactiveCourses()
    {
        $ret = $this->Course->getInactiveCourses();
        $this->assertEqual(count($ret), 1);
        $this->assertEqual($ret[0]['Course']['course'], "CPSC 101");
    }

    function testGetCourseByCourse()
    {
        //test valid course
        $course = $this->Course->getCourseByCourse('APSC 201', array());
        $this->assertEqual($course[0]['Course']['course'], "APSC 201");
    }

    function testGetCourseByGroupId()
    {
        $this->Course = ClassRegistry::init('Course');

        //test valid group id
        $empty = null;
        $course = $this->Course->getCourseByGroupId(1);
        $this->assertEqual($course['Course']['course'], 'MECH 328');

        // invalid group id
        $course = $this->Course->getCourseByGroupId(999);
        $this->assertEqual($course, array());
    }

    function testDeleteAll()
    {
        $empty = null;
        /*
         * Name is misleading, the function actually deletes a single course defined by id and all event related to it.
         *
         * */
        $this->Course->deleteWithRelated(1);
        //Query the course table, should return count==0 for course
        //$sql = "SELECT count(*) FROM courses";
        $course = $this->Course->getCourseName(1);
        $this->assertEqual($course, $empty);

/*		//Test calling deleteAll on an empty course list; should retrun zero
        $this->Course->deleteWithRelated(1);
        $course = $this->Course->getCourseName(1);
        $this->assertEqual($course, $empty);
 */

    }

    function testGetByDepartments()
    {
        $this->UserFaculty = Classregistry::init('UserFaculty');
        $this->Course = ClassRegistry::init('Course');
        $this->Department = ClassRegistry::init('Department');

        $expected = array(
            "1" => "MECH 328 - Mechanical Engineering Design Project",
            "2" => "APSC 201 - Technical Communication",
            "3" => "CPSC 101 - Connecting with Computer Science",
            "4" => "CPSC 404 - Advanced Software Engineering"
        );
        $empty = null;

        // super admin: user id 1, should see all courses
        $dep = array(
            array('Department' => array('id' => 1)),
            array('Department' => array('id' => 2)),
            array('Department' => array('id' => 3)),
        );
        $course = $this->Course->getByDepartments($dep, 'list');
        $this->assertEqual($course, $expected);

        // if no departments in array, should return nothing
        $course = $this->Course->getByDepartments(array(), 'all');
        $this->assertEqual($course, $empty);

    }

    function testGetByDepartmentIds()
    {
    }

    function testGetAccessibleCourses()
    {
    }

    function testGetAccessibleCourseById()
    {
    }

    function testGetuserListByCourse()
    {
    }

    function testGetCourseList()
    {
    }
}
