<?php
App::import('Model', 'Survey');

class SurveyTestCase extends CakeTestCase
{
    public $name = 'Survey';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $Survey = null;

    function startCase() {
        echo "Start Survey model test.\n";
        $this->Survey = ClassRegistry::init('Survey');
    }

    function endCase() {
    }

    function startTest($method) {
    }

    function endTest($method) {
    }
}
