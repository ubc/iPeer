<?php
App::import('Model', 'SurveyInput');

class SurveyInputTestCase extends CakeTestCase
{
    public $name = 'SurveyInput';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group', 'app.penalty',
        'app.survey_group_member', 'app.question', 'app.user_tutor',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.survey_input', 'app.survey', 'app.faculty', 'app.user_faculty',
        'app.department', 'app.course_department', 'app.evaluation_simple',
        'app.user_tutor', 'app.penalty', 'app.oauth_token', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval_detail',
        'app.evaluation_mixeval', 'app.sys_parameter'
    );
    public $SurveyInput = null;

    function startCase()
    {
        echo "Start SurveyInput model test.\n";
        $this->SurveyInput = ClassRegistry::init('SurveyInput');
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

    function testGetByEventIdUserId()
    {
        $result1 = $this->SurveyInput->getByEventIdUserId(4, 7);
        $expect1 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 1,
                    'event_id' => 4,
                    'user_id' => 7,
                    'question_id' => 1,
                    'response_text' => '4+',
                    'response_id' => 1)
            ),
            '1' => array(
                'SurveyInput' => array(
                    'id' => 2,
                    'event_id' => 4,
                    'user_id' => 7,
                    'question_id' => 2,
                    'response_text' => 'yes',
                    'response_id' => 5)
            )
        );
        $this->assertEqual($result1, $expect1);

        // Test faulty inputs
        $invalid1 = $this->SurveyInput->getByEventIdUserId(4, 21312);
        $this->assertTrue(empty($invalid1));
        $invalid2 = $this->SurveyInput->getByEventIdUserId(23123, 4);
        $this->assertTrue(empty($invalid2));
        $nullInput = $this->SurveyInput->getByEventIdUserId(null, null);
        $this->assertTrue(empty($nullInput));
    }

    function testEventIdUserIdQuestionId()
    {

        $result1 =
            $this->SurveyInput->getByEventIdUserIdQuestionId(4, 7, 1);
        $expect1 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 1,
                    'event_id' => 4,
                    'user_id' => 7,
                    'question_id' => 1,
                    'response_text' => '4+',
                    'response_id' => 1)
                )
            );
        $this->assertEqual($result1, $expect1);

        $result2 = $this->SurveyInput->getByEventIdUserIdQuestionId(4,7,2);
        $expect2 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 2,
                    'event_id' => 4,
                    'user_id' => 7,
                    'question_id' => 2,
                    'response_text' => 'yes',
                    'response_id' => 5)
                )
            );
        $this->assertEqual($result2, $expect2);

        // Test faulty inputs
        $invalid1 = $this->SurveyInput->getByEventIdUserIdQuestionId(1, 21312, 1);
        $this->assertTrue(empty($invalid1));
        $invalid2 = $this->SurveyInput->getByEventIdUserIdQuestionId(23123, 1, 1);
        $this->assertTrue(empty($invalid2));
        $nullInput = $this->SurveyInput->getByEventIdUserIdQuestionId(null, null, null);
        $this->assertTrue(empty($nullInput));
    }
}
