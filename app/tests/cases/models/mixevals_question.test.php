<?php
App::import('Model', 'MixevalsQuestion');

class MixevalsQuestionTestCase extends CakeTestCase
{
    public $name = 'MixevalsQuestion';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.mixeval', 'app.mixevals_question',
        'app.mixevals_question_desc'
    );
    public $MixevalsQuestion = null;

    function startCase()
    {
        echo "Start MixevalsQuestion model test.\n";
        $this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
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

    function testMixevalsQuestionInstance()
    {
        $this->assertTrue(is_a($this->MixevalsQuestion, 'MixevalsQuestion'));
    }

    function testInsertQuestion()
    {
        // set up test input
        $input = $this->setUpInserQuestionTestData();
        // set up test data
        $result = $this->MixevalsQuestion->insertQuestion(55, $input);
        // Assert the data was saved in database
        $searched = $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id' => 55)));
        $this->assertEqual($searched[0]['MixevalsQuestion']['mixeval_id'], 55);
        $this->assertEqual($searched[1]['MixevalsQuestion']['mixeval_id'], 55);
        $this->assertEqual($searched[0]['MixevalsQuestion']['title'], 'Licket Q1');
        $this->assertEqual($searched[1]['MixevalsQuestion']['title'], 'Comment Q2');

        // Test for incorrect inputs
        $incorrectResult1 = $this->MixevalsQuestion->insertQuestion(null, $input);
        $incorrectResult2 = $this->MixevalsQuestion->insertQuestion(55, null);
        $incorrectResult3 = $this->MixevalsQuestion->insertQuestion(null, null);
        $this->assertFalse($incorrectResult1);
        $this->assertFalse($incorrectResult2);
        $this->assertFalse($incorrectResult3);
    }

    function testGetQuestion()
    {
        $result = $this->MixevalsQuestion->getQuestion(1);
        $this->assertEqual($result[0]['MixevalsQuestion']['id'], 1);
        $this->assertEqual($result[1]['MixevalsQuestion']['id'], 2);
        $this->assertEqual($result[2]['MixevalsQuestion']['id'], 3);
        $this->assertEqual($result[0]['MixevalsQuestion']['title'], 
            'Participated in Team Meetings');
        $this->assertEqual($result[1]['MixevalsQuestion']['title'], 
            'Was Helpful and co-operative');
        $this->assertEqual($result[2]['MixevalsQuestion']['title'], 
            'Submitted work on time');
    }

    function setUpInserQuestionTestData()
    {
        $tmp = array(
            '0' => array(
                'id' => '',
                'question_type' => 'S',
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
                'question_type' => 'T',
                'question_num' => 1,
                'title' => 'Comment Q2',
                'instructions' => 'Comment',
                'response_type' => 'L'
            )
        );
        return $tmp;
    }
}
