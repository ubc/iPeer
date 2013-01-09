<?php
App::import('Model', 'UserCourse');

class UserCourseTestCase extends CakeTestCase
{
    public $name = 'UserEnrol';
    public $fixtures = array(
        'app.user_course', 'app.user', 'app.evaluation_submission', 'app.event',
        'app.event_template_type', 'app.course', 'app.group', 'app.group_event',
        'app.evaluation_simple', 'app.survey_input', 'app.faculty', 
        'app.user_faculty', 'app.survey', 'app.survey_group_set', 
        'app.survey_group', 'app.survey_group_member', 'app.question', 
        'app.response', 'app.survey_question', 'app.role', 'app.roles_user',
        'app.user_tutor', 'app.user_enrol', 'app.groups_member', 
        'app.department', 'app.course_department', 'app.penalty'
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
