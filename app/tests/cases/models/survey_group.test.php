<?php
App::import('Model', 'SurveyGroup');

class SurveyGroupTestCase extends CakeTestCase {
    public $name = 'SurveyGroup';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail'
    );
    public $SurveyGroup = null;

    function startCase()
    {
        echo "Start SurveyGroup model test.\n";
        $this->SurveyGroup = ClassRegistry::init('SurveyGroup');
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

    function testGetIdsByGroupSetId()
    {
        // Grab data from fixture
        $data = $this->SurveyGroup->getIdsByGroupSetId(3);
        // Assert the function successfully queried the data
        $this->assertNotNull($data);
        $this->assertTrue(isset($data));
        $this->assertTrue(!empty($data));
        // Assert the data is correct
        $this->assertEqual($data[0]['SurveyGroup']['id'], 5);
        $this->assertEqual($data[1]['SurveyGroup']['id'], 6);
        $this->assertEqual($data[2]['SurveyGroup']['id'], 7);
    }
}
