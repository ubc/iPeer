<?php
App::import('Model', 'UserEnrol');

class UserEnrolTestCase extends CakeTestCase
{
    public $name = 'UserEnrol';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.faculty',
        'app.user_faculty', 'app.department', 'app.course_department',
        'app.sys_parameter', 'app.user_tutor'
    );
    public $Course = null;

    function startCase()
    {
        $this->UserEnrol = ClassRegistry::init('UserEnrol');
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


    function testRemoveStudentFromCourse()
    {
        //Test remove student
        $this->UserEnrol->removeStudentFromCourse(26, 1);
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 1);
        $this->assertFalse($user);

        //Test remove student twice
        $this->UserEnrol->removeStudentFromCourse(26, 1);
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 1);
        $this->assertFalse($user);

        //Test remove student from wrong course
        $this->UserEnrol->removeStudentFromCourse(27, 1);
        $user = $this->UserEnrol->isEnrolledInByUsername('40289059', 2);
        $this->assertTrue($user);

        //Test remove invalid student from  course
        $this->UserEnrol->removeStudentFromCourse(999, 2);
        $user = $this->UserEnrol->isEnrolledInByUsername('40289059', 2);
        $this->assertTrue($user);

        //Test remove valid student from invalid course
        $this->UserEnrol->removeStudentFromCourse(27, 999);
        $user = $this->UserEnrol->isEnrolledInByUsername('40289059', 2);
        $this->assertTrue($user);

        //Test remove invalid student from invalid course
        $this->UserEnrol->removeStudentFromCourse(999, 999);
        $user = $this->UserEnrol->isEnrolledInByUsername('40289059', 2);
        $this->assertTrue($user);

    }

    function testIsEnrolledInByUsername()
    {

        //Test valid student enrolled in valid course
        $user = $this->UserEnrol->isEnrolledInByUsername('88505045', 2);
        $this->assertTrue($user);

        //Test valid student enrolled not in valid course
        $user = $this->UserEnrol->isEnrolledInByUsername('88505045', 3);
        $this->assertFalse($user);

        //Test invalid student and valid course
        $user = $this->UserEnrol->isEnrolledInByUsername('invalid', 1);
        $this->assertFalse($user);

        //Test valid student and invalid course
        $user = $this->UserEnrol->isEnrolledInByUsername('88505045', 9999);
        $this->assertFalse($user);

        //Test invalid student and invalid course
        $user = $this->UserEnrol->isEnrolledInByUsername(9999, 9999);
        $this->assertFalse($user);

    }

    function testInsertCourses()
    {

        //Test insert valid student into valid courses
        $this->UserEnrol->insertCourses(26, array(1,2));
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 1);
        $this->assertTrue($user);
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 2);
        $this->assertTrue($user);

        //Test insert invalid student into valid course
        $this->UserEnrol->insertCourses(999, array(1));
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 1);
        $this->assertTrue($user);

        //Test insert invalid student into invalid course
        $this->UserEnrol->insertCourses(9999, array(999));
        $user = $this->UserEnrol->isEnrolledInByUsername('19524032', 1);
        $this->assertTrue($user);

    }

    function testGetEnrolledCourses()
    {

        //Test valid student enrolled in 2 courses
        $courses = $this->UserEnrol->getEnrolledCourses(7);
        $this->assertEqual(Set::extract('/UserEnrol/course_id', $courses), array(1,2));

        //Test valid user not enrolled in courses
        $this->UserEnrol->removeStudentFromCourse(20, 2);
        $courses = $this->UserEnrol->getEnrolledCourses(20);
        $this->assertEqual($courses, null);
        $this->UserEnrol->insertCourses(20, array(2));

        //Test invalid user
        $courses = $this->UserEnrol->getEnrolledCourses(999);
        $this->assertEqual($courses, null);

    }
}
