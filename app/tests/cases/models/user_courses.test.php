<?php
App::import('Model', 'UserCourse');

class UserCourseTestCase extends CakeTestCase
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
        'app.sys_parameter', 'app.user_tutor', 'app.penalty'
    );
    public $UserCourse = null;

    function startCase()
    {
        echo "Start UserCourse model test.\n";
        $this->UserCourse = ClassRegistry::init('UserCourse');
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

}
