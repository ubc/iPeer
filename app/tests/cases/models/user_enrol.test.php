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
        'app.sys_parameter', 'app.user_tutor', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start UserEnrol model test.\n";
        $this->UserEnrol = ClassRegistry::init('UserEnrol');
        $this->User = ClassRegistry::init('User');
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

    function testInsertCourses()
    {
        //Test insert valid student into valid courses
        $user = $this->User->UserEnrol->field('id',
            array('user_id' => 26, 'course_id' => 2));
        $this->assertFalse($user);
        $user = $this->User->UserEnrol->field('id',
            array('user_id' => 26, 'course_id' => 3));
        $this->assertFalse($user);

        $this->UserEnrol->insertCourses(26, array(2,3));

        $user = $this->User->UserEnrol->field('id',
            array('user_id' => 26, 'course_id' => 2));
        $this->assertTrue($user);
        $user = $this->User->UserEnrol->field('id',
            array('user_id' => 26, 'course_id' => 3));
        $this->assertTrue($user);
    }
}
