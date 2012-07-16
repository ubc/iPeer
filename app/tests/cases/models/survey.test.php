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
        'app.course_department', 'app.sys_parameter', 'app.user_tutor'
    );
    public $Survey = null;

    function startCase() {
        $this->Survey = ClassRegistry::init('Survey');
    }

    function endCase() {
    }

    function startTest($method) {
    }

    function endTest($method) {
    }

    function testGetSurveyIdByCourseIdTitle() {
        // grab test data
        $ret = $this->Survey->getSurveyIdByCourseIdTitle(1, 
            'Team Creation Survey');

        // Assert on valid data
        $this->assertEqual($ret, 1);

        // Test on faulty inputs
        $faultyInputs1 = $this->Survey->getSurveyIdByCourseIdTitle(1, 'Faulty');
        $faultyInputs2 = $this->Survey->getSurveyIdByCourseIdTitle(999, 'Math303 Survey');
        $faultyInputs3 = $this->Survey->getSurveyIdByCourseIdTitle(999, null);
        $faultyInputs4 = $this->Survey->getSurveyIdByCourseIdTitle(null, null);
        $this->assertNull($faultyInputs1);
        $this->assertNull($faultyInputs2);
        $this->assertNull($faultyInputs3);
        $this->assertNull($faultyInputs4);
    }
}
