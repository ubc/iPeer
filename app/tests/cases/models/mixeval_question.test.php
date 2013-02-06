<?php
App::import('Model', 'MixevalQuestion');

class MixevalQuestionTestCase extends CakeTestCase
{
    public $name = 'MixevalQuestion';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission', 'app.oauth_token',
        'app.survey_group_set', 'app.survey_group', 'app.sys_parameter',
        'app.survey_group_member', 'app.question', 'app.evaluation_simple',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.mixeval', 
        'app.mixeval_question', 'app.mixeval_question_desc', 'app.survey_input',
        'app.survey', 'app.faculty', 'app.user_faculty', 'app.user_tutor',
        'app.department', 'app.course_department', 'app.penalty',
        'app.mixeval_question_type'
    );
    public $MixevalQuestion = null;

    function startCase()
    {
        echo "Start MixevalQuestion model test.\n";
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
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

    function testMixevalQuestionInstance()
    {
        $this->assertTrue(is_a($this->MixevalQuestion, 'MixevalQuestion'));
    }

    function testInsertQuestion()
    {
        // set up test input
        $input = $this->setUpInserQuestionTestData();
        // set up test data
        $result = $this->MixevalQuestion->insertQuestion(55, $input);
        // Assert the data was saved in database
        $searched = $this->MixevalQuestion->find('all', array('conditions' => array('mixeval_id' => 55)));
        $this->assertEqual($searched[0]['MixevalQuestion']['mixeval_id'], 55);
        $this->assertEqual($searched[1]['MixevalQuestion']['mixeval_id'], 55);
        $this->assertEqual($searched[0]['MixevalQuestion']['title'], 'Licket Q1');
        $this->assertEqual($searched[1]['MixevalQuestion']['title'], 'Comment Q2');

        // Test for incorrect inputs
        $incorrectResult1 = $this->MixevalQuestion->insertQuestion(null, $input);
        $incorrectResult2 = $this->MixevalQuestion->insertQuestion(55, null);
        $incorrectResult3 = $this->MixevalQuestion->insertQuestion(null, null);
        $this->assertFalse($incorrectResult1);
        $this->assertFalse($incorrectResult2);
        $this->assertFalse($incorrectResult3);
    }

    function testGetQuestion()
    {
        $result = $this->MixevalQuestion->getQuestion(1);
        $this->assertEqual($result[0]['MixevalQuestion']['id'], 1);
        $this->assertEqual($result[1]['MixevalQuestion']['id'], 2);
        $this->assertEqual($result[2]['MixevalQuestion']['id'], 3);
        $this->assertEqual($result[0]['MixevalQuestion']['title'],
            'Participated in Team Meetings');
        $this->assertEqual($result[1]['MixevalQuestion']['title'],
            'Was Helpful and co-operative');
        $this->assertEqual($result[2]['MixevalQuestion']['title'],
            'Submitted work on time');
    }

    function setUpInserQuestionTestData()
    {
        $tmp = array(
            '0' => array(
                'id' => '',
                'mixeval_question_type_id' => '1',
                'question_num' => 0,
                'title' => 'Licket Q1',
                'multiplier' => 15,
                'Description' => array(
                    '0' => array(
                        'id' => '',
                        'descriptor' => 'Des1'
                    ),
                    '1' => array(
                        'id' => '',
                        'descriptor' => 'Des2'
                    )
                )
            ),
            '1' => array(
                'id' => '',
                'mixeval_question_type_id' => '2',
                'question_num' => 1,
                'title' => 'Comment Q2',
                'instructions' => 'Comment',
            )
        );
        return $tmp;
    }
}
