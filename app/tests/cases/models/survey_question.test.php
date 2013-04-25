<?php
App::import('Model', 'SurveyQuestion');

class SurveyQuestionTestCase extends CakeTestCase
{
    public $name = 'SurveyQuestion';
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
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $SurveyQuestion = null;

    function startCase()
    {
        echo "Start SurveyQuestion model test.\n";
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Survey = ClassRegistry::init('Survey');
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


    function testReorderQuestions()
    {
        // Set up test data
        $ret = $this->SurveyQuestion->reorderQuestions(1);

        /* Assert the function returns a result with correctly ordered
         * question number, instead of the default 9999.
         */
        $this->assertEqual($ret[0]['SurveyQuestion']['number'], 1);
        $this->assertEqual($ret[1]['SurveyQuestion']['number'], 2);

        // Assert the result is saved in the fixtures
        $DBResult1 = $this->SurveyQuestion->find('first',
            array('conditions' => array('SurveyQuestion.id' => 1)));
        $DBResult2 = $this->SurveyQuestion->find('first',
            array('conditions' => array('SurveyQuestion.id' => 2)));
        $this->assertEqual($DBResult1['SurveyQuestion']['number'], 1);
        $this->assertEqual($DBResult2['SurveyQuestion']['number'], 2);
    }

    function testMoveQuestion()
    {
        /*
         * The data structure is set up as follows
         * Question_id = 1 | Question_num = 1
         * Question_id = 2 | Question_num = 2
         */
        // Test move TOP
        $this->SurveyQuestion->moveQuestion(1, 2, 'TOP');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 2)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);

        // Test move BOTTOM
        $this->SurveyQuestion->moveQuestion(1, 1, 'BOTTOM');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 1)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 2);

        // Test move UP
        $this->SurveyQuestion->moveQuestion(1, 2, 'UP');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 2)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);

        // Test move DOWN
        $this->SurveyQuestion->moveQuestion(1, 1, 'DOWN');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 1)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 2);

        // Test move BOTTOM on the bottom question
        $this->SurveyQuestion->moveQuestion(1, 2, 'BOTTOM');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 2)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 2);

        // Test move DOWN on the bottom question
        $this->SurveyQuestion->moveQuestion(1, 2, 'DOWN');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 2)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 2);

        // Test move TOP on the TOP question
        $this->SurveyQuestion->moveQuestion(1, 1, 'TOP');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 1)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);

        // Test move UP on the top question
        $this->SurveyQuestion->moveQuestion(1, 1, 'UP');
        $movedQuestion = $this->SurveyQuestion->find('first',
            array('conditions' => array('question_id' => 1)));
        $this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);
    }

    function testGetQuestionsID()
    {
        // Set question data
        $ret = $this->SurveyQuestion->getQuestionsID(1);

        $this->assertEqual(Set::extract('/SurveyQuestion/question_id', $ret), array(1,2));
        $this->assertEqual(count($ret), 2);

        $ret = $this->SurveyQuestion->getQuestionsID(null);
        $this->assertEqual($ret, array());

    }

    function testGetLastSurveyQuestionNum()
    {
        $lastQuestionNum = $this->SurveyQuestion->getLastSurveyQuestionNum(1);
        $this->assertEqual($lastQuestionNum, 2);
    }
    
    function testAssignNumber()
    {
        //TODO
    }
}
