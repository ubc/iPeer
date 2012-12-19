<?php
App::import('Model', 'SurveyInput');

class SurveyInputTestCase extends CakeTestCase
{
    public $name = 'SurveyInput';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.survey_input', 'app.survey'
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

    function testGetAllSurveyInputBySurveyIdUserId()
    {
        $result1 = $this->SurveyInput->getBySurveyIdUserId(1, 7);
        $expect1 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 1,
                    'survey_id' => 1,
                    'user_id' => 7,
                    'question_id' => 1,
                    'sub_id' => null,
                    'chkbx_id' => null,
                    'response_text' => '4+',
                    'response_id' => 1)
            ),
            '1' => array(
                'SurveyInput' => array(
                    'id' => 2,
                    'survey_id' => 1,
                    'user_id' => 7,
                    'question_id' => 2,
                    'sub_id' => null,
                    'chkbx_id' => null,
                    'response_text' => 'yes',
                    'response_id' => 5)
            )
        );
        $this->assertEqual($result1, $expect1);

        // Test faulty inputs
        $invalid1 = $this->SurveyInput->getBySurveyIdUserId(1, 21312);
        $this->assertTrue(empty($invalid1));
        $invalid2 = $this->SurveyInput->getBySurveyIdUserId(23123, 1);
        $this->assertTrue(empty($invalid2));
        $nullInput = $this->SurveyInput->getBySurveyIdUserId(null, null);
        $this->assertTrue(empty($nullInput));
    }

    function testGetAllSurveyInputBySurveyIdUserIdQuestionId()
    {

        $result1 = 
            $this->SurveyInput->getBySurveyIdUserIdQuestionId(1, 7, 1);
        $expect1 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 1,
                    'survey_id' => 1,
                    'user_id' => 7,
                    'question_id' => 1,
                    'sub_id' => null,
                    'chkbx_id' => null,
                    'response_text' => '4+',
                    'response_id' => 1)
                )
            );
        $this->assertEqual($result1, $expect1);

        $result2 = $this->SurveyInput->getBySurveyIdUserIdQuestionId(1,7,2);
        $expect2 = array(
            '0' => array(
                'SurveyInput' => array(
                    'id' => 2,
                    'survey_id' => 1,
                    'user_id' => 7,
                    'question_id' => 2,
                    'sub_id' => null,
                    'chkbx_id' => null,
                    'response_text' => 'yes',
                    'response_id' => 5)
                )
            );
        $this->assertEqual($result2, $expect2);

        // Test faulty inputs
        $invalid1 = $this->SurveyInput->getBySurveyIdUserIdQuestionId(1, 21312, 1);
        $this->assertTrue(empty($invalid1));
        $invalid2 = $this->SurveyInput->getBySurveyIdUserIdQuestionId(23123, 1, 1);
        $this->assertTrue(empty($invalid2));
        $nullInput = $this->SurveyInput->getBySurveyIdUserIdQuestionId(null, null, null);
        $this->assertTrue(empty($nullInput));
    }
}
