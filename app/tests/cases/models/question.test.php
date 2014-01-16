<?php
App::import('Model', 'Question');

class QuestionTestCase extends CakeTestCase
{
    public $name = 'Question';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment', 'app.faculty',
        'app.user_faculty', 'app.department', 'app.course_department',
        'app.user_tutor', 'app.sys_parameter', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $Question = null;

    function startCase()
    {
        echo "Start Question model test.\n";
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
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

    function testGetTypeById()
    {
        // Set up test data
        $Q1Type = $this->Question->getTypeById(1);
        $Q2Type = $this->Question->getTypeById(2);
        // Compare with fixture data
        $this->assertEqual($Q1Type, 'M');
        $this->assertEqual($Q2Type, 'M');
        // Check for faulty inputs
        $faultyId = $this->Question->getTypeById(332);
        $nullId = $this->Question->getTypeById(null);

        $this->assertNull($faultyId);
        $this->assertNull($nullId);
    }
    
    function testCopyQuestions()
    {
    }

}
