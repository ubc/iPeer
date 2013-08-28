<?php
App::import('Model', 'SurveyGroupMember');

class SurveyGroupMemberTestCase extends CakeTestCase
{
    public $name = 'SurveyGroupMember';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.oauth_token', 'app.sys_parameter',
        'app.evaluation_simple', 'app.survey_input', 'app.faculty',
        'app.user_faculty', 'app.user_tutor', 'app.department',
        'app.course_department', 'app.penalty', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval',
        'app.evaluation_mixeval_detail'
    );
    public $SurveyGroupMember = null;

    function startCase()
    {
        echo "Start SurveyGroupMember model test.\n";
        $this->SurveyGroupMember = ClassRegistry::init('SurveyGroupMember');
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

    function testSurveyGroupMemberInstance()
    {
        $this->assertTrue(is_a($this->SurveyGroupMember, 'SurveyGroupMember'));
    }

    function testGetIdsByGroupSetId()
    {
        // Set up test data
        $data = $this->SurveyGroupMember->getIdsByGroupSetId(3);
        // Assert the queried data is infact the fixture data
        for ($i = 25; $i <= 36; $i++) {
            $this->assertEqual($data[$i - 25]['SurveyGroupMember']['id'], $i);
        }
    }
}
