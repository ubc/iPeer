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
        'app.rubrics_criteria', 'app.rubrics_criteria_comment'
    );
    public $SurveyGroupMember = null;

    function startCase()
    {
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
        $data = $this->SurveyGroupMember->getIdsByGroupSetId(1);
        // Assert the queried data is infact the fixture data
        $entry0 = $data[0]['SurveyGroupMember'];
        $entry1 = $data[1]['SurveyGroupMember'];
        $this->assertEqual($entry0['id'], 1);
        $this->assertEqual($entry1['id'], 2);
    }
}
