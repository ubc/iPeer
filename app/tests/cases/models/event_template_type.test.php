<?php
App::import('Model', 'EventTemplateType');

class EventTemplateTypeTestCase extends CakeTestCase {
    public $name = 'EventTemplateType';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.user_faculty', 'app.user_tutor',
        'app.department', 'app.course_department', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.sys_parameter', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $EventTemplateType = null;

    function startCase()
    {
        echo "Start EventTemplateType model test.\n";
        $this->EventTemplateType = ClassRegistry::init('EventTemplateType');
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

    function testGetEventTemplateTypeList()
    {
        // Test for returning ALL template results
        $result = $this->EventTemplateType->getEventTemplateTypeList(false);
        $this->assertTrue(!empty($result));
        $this->assertEqual($result[1], 'SIMPLE');
        $this->assertEqual($result[2], 'RUBRIC');
        $this->assertEqual($result[3], 'SURVEY');
        $this->assertEqual($result[4], 'MIX EVALUATION');

        // Test for returning ONLY display_for_selection==1 templates
        $result = $this->EventTemplateType->getEventTemplateTypeList(true);
        $this->assertTrue(!empty($result));
        $this->assertTrue($result[1], 'SIMPLE');
    }
}
