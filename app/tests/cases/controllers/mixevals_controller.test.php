<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Mixevals');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('MixevalsController',
    'MockMixevalsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class MixevalsControllerTest extends ExtendedAuthTestCase {
    public $controller = null;

    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group', 'app.roles_user', 
        'app.event', 'app.event_template_type', 'app.group_event', 
        'app.evaluation_submission', 'app.survey_group_set', 
        'app.survey_group', 'app.survey_group_member', 'app.question', 
        'app.response', 'app.survey_question', 'app.user_course', 
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.personalize', 
        'app.penalty', 'app.evaluation_simple', 'app.faculty', 
        'app.user_tutor', 'app.course_department', 'app.evaluation_rubric', 
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval', 
        'app.evaluation_mixeval_detail', 'app.user_faculty', 'app.department', 
        'app.sys_parameter', 'app.oauth_token', 'app.rubric', 
        'app.rubrics_criteria', 'app.rubrics_criteria_comment', 
        'app.rubrics_lom', 'app.simple_evaluation', 'app.survey_input', 
        'app.mixeval_question', 'app.mixeval_question_desc', 'app.mixeval',
        'app.mixeval_question_type'
    );

    function startCase() {
        echo "Start Mixeval controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
        // init fixtures so we can use them
        $this->Mixeval =& ClassRegistry::init('Mixeval');
        $this->MixevalQuestion =& ClassRegistry::init('MixevalQuestion');
    }

    function endCase() {
    }

    function startTest($method) {
        echo $method.TEST_LB;
        $this->controller = new MockMixevalsController();
    }

    function endTest() {
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/mixevals/index', array('return' => 'vars'));

        $this->assertEqual(count($result['paramsForList']['data']['entries']), 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['id'], 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['name'], 'Default Mix Evaluation');
        $this->assertEqual($result['paramsForList']['data']['count'], 1);
    }

    function testView() {
        $result = $this->testAction('/mixevals/view/1', 
            array('return' => 'vars'));

        $this->assertEqual($result['mixeval']['id'], 1);
        $this->assertEqual(count($result['questions']), 6);
        $this->assertEqual(count($result['questions'][0]['MixevalQuestionDesc']), 5);
        $this->assertEqual(count($result['questions'][1]['MixevalQuestionDesc']), 5);
        $this->assertEqual(count($result['questions'][2]['MixevalQuestionDesc']), 5);
        $this->assertEqual(count($result['questions'][3]['MixevalQuestionDesc']), 0);
        $this->assertEqual(count($result['questions'][4]['MixevalQuestionDesc']), 0);
        $this->assertEqual(count($result['questions'][5]['MixevalQuestionDesc']), 0);
    }

    function testAdd() {
        $result = $this->testAction('/mixevals/add', 
            array('return' => 'vars'));
        // make sure the list of question types is set
        $this->assertTrue(isset($result['mixevalQuestionTypes']));

        // Test a regular save with 1 of each question type
        $data = array(
            "Mixeval" => array(
                "name" => 'TestMixevalForTestCase',
                "availability" => 'private',
                "zero_mark" => 0,
                "MixevalQuestionType" => 3
            ),
            "MixevalQuestion" => array(
                "1" => array(
                    "title" => 'Likert Question',
                    "instructions" => 'Likert Instructions',
                    "required" => 1,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 1,
                    "multiplier" => 1
                ),
                "2" => array(
                    "title" => 'Paragraph Question',
                    "instructions" => 'Paragraph Instructions',
                    "required" => 0,
                    "mixeval_question_type_id" => 2,
                    "question_num" => 2
                ),
                "3" => array(
                    "title" => 'Sentence Question',
                    "instructions" => 'Sentence Instructions',
                    "required" => 1,
                    "mixeval_question_type_id" => 3,
                    "question_num" => 3
                ),
            ),
            "MixevalQuestionDesc" => array(
                "1" => array(
                    "descriptor" => 'Likert Scale 1',
                    "question_id" => 1
                ),
                "2" => array(
                    "descriptor" => 'Likert Scale 2',
                    "question_id" => 1
                ),
                "3" => array(
                    "descriptor" => 'Likert Scale 3',
                    "question_id" => 1
                ),
            ),
        );
        $result = $this->testAction(
            '/mixevals/add', 
            array(
                'method' => 'post',
                'data' => $data,
                'return' => 'vars',
            )
        );
        // Make sure that the save went through successfully
        $mixeval = $this->Mixeval->findByName('TestMixevalForTestCase');
        $this->assertTrue($mixeval);

        // Check that the mixeval entry is correct
        $this->assertEqual($data['Mixeval']['name'], 
            $mixeval['Mixeval']['name']);
        $this->assertEqual($data['Mixeval']['availability'], 
            $mixeval['Mixeval']['availability']);
        $this->assertEqual($data['Mixeval']['zero_mark'], 
            $mixeval['Mixeval']['zero_mark']);

        // Check that the question entries are correct
        $mixevalId = $mixeval['Mixeval']['id'];
        $questions = $this->MixevalQuestion->findAllByMixevalId($mixevalId);
        // make sure that we have the same number of questions
        $this->assertEqual(count($questions), count($data['MixevalQuestion']));
        $expectedQuestions = $data['MixevalQuestion'];
        // make sure that the question are stored same as entered
        $i = 0;
        foreach ($data['MixevalQuestion'] as $expectedQ) {
            $this->assertTrue(isset($questions[$i]));
            $actualQ = $questions[$i];
            $this->assertEqual($expectedQ['title'], 
                $actualQ['MixevalQuestion']['title']);
            $this->assertEqual($expectedQ['instructions'], 
                $actualQ['MixevalQuestion']['instructions']);
            $this->assertEqual($expectedQ['required'], 
                $actualQ['MixevalQuestion']['required']);
            $this->assertEqual($expectedQ['mixeval_question_type_id'], 
                $actualQ['MixevalQuestion']['mixeval_question_type_id']);
            $this->assertEqual($expectedQ['question_num'], 
                $actualQ['MixevalQuestion']['question_num']);
            if (isset($expectedQ['multiplier'])) {
                $this->assertEqual($expectedQ['multiplier'], 
                    $actualQ['MixevalQuestion']['multiplier']);
            }
            $i++;
        }

        // Check that the question descriptor entries are correct
        $likertQ = $questions[0];
        $this->assertEqual(count($data['MixevalQuestionDesc']),
            count($likertQ['MixevalQuestionDesc']));
        $i = 0;
        foreach ($data['MixevalQuestionDesc'] as $expectedD) {
            $actualD = $likertQ['MixevalQuestionDesc'][$i];
            $this->assertEqual($expectedD['descriptor'],
                $actualD['descriptor']);
            $this->assertEqual($likertQ['MixevalQuestion']['id'],
                $actualD['question_id']);
            $this->assertEqual($i + 1, $actualD['scale_level']);
            $i++;
        }

        // Test that questions/descriptors can be indexed in random order but 
        // the save will make sure that the questions will end up being 
        // numbered and scaled correctly.
        // - question reordering must match what users want
        // - question descriptors need to be scaled properly
        $data = array(
            "Mixeval" => array(
                "name" => 'TestMixevalForTestCase2',
                "availability" => 'private',
                "zero_mark" => 0,
                "MixevalQuestionType" => 3
            ),
            "MixevalQuestion" => array(
                "4" => array(
                    "title" => 'Likert Question 1',
                    "instructions" => 'Likert Instructions 1',
                    "required" => 0,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 1,
                    "multiplier" => 1
                ),
                "2" => array(
                    "title" => 'Likert Question 2',
                    "instructions" => 'Likert Instructions 2',
                    "required" => 0,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 2,
                    "multiplier" => 1
                ),
            ),
            "MixevalQuestionDesc" => array(
                "2" => array(
                    "descriptor" => 'Likert Q1 Scale 1',
                    // the question_id here is expected to be question index 
                    // when submitted from the form, which for Question 1 is 4 
                    // in this case
                    "question_id" => 4 
                ),
                "4" => array(
                    "descriptor" => 'Likert Q1 Scale 2',
                    "question_id" => 4
                ),
                "1" => array(
                    "descriptor" => 'Likert Q2 Scale 1',
                    "question_id" => 2
                ),
                "6" => array(
                    "descriptor" => 'Likert Q2 Scale 2',
                    "question_id" => 2
                ),
            ),
        );
        $result = $this->testAction(
            '/mixevals/add', 
            array(
                'method' => 'post',
                'data' => $data,
                'return' => 'vars',
            )
        );

        // Make sure that the save went through successfully
        $mixeval = $this->Mixeval->findByName('TestMixevalForTestCase2');
        $this->assertTrue($mixeval);
        // Check that the question entries are correct
        $mixevalId = $mixeval['Mixeval']['id'];
        $questions = $this->MixevalQuestion->findAllByMixevalId($mixevalId);
        // make sure that we have the same number of questions
        $this->assertEqual(count($questions), count($data['MixevalQuestion']));
        $expectedQuestions = $data['MixevalQuestion'];
        // make sure that the question is correctly numbered
        $i = 0;
        foreach ($data['MixevalQuestion'] as $expectedQ) {
            $this->assertTrue(isset($questions[$i]));
            $actualQ = $questions[$i];
            $this->assertEqual($expectedQ['title'], 
                $actualQ['MixevalQuestion']['title']);
            $this->assertEqual($expectedQ['question_num'], 
                $actualQ['MixevalQuestion']['question_num']);
            $i++;
        }

        // Check that the question descriptor entries are correct
        $Q1Descs = array_slice($data['MixevalQuestionDesc'], 0, 2);
        $Q2Descs = array_slice($data['MixevalQuestionDesc'], 2, 2);
        $expectedDescs = array($Q1Descs, $Q2Descs);
        foreach ($expectedDescs as $key => $descs) {
            $expectedQ = $questions[$key];
            $this->assertEqual(count($descs),
                count($expectedQ['MixevalQuestionDesc']));
            $i = 0;
            foreach ($descs as $expectedD) {
                $actualD = $expectedQ['MixevalQuestionDesc'][$i];
                $this->assertEqual($expectedD['descriptor'],
                    $actualD['descriptor']);
                $this->assertEqual($expectedQ['MixevalQuestion']['id'],
                    $actualD['question_id']);
                $this->assertEqual($i + 1, $actualD['scale_level']);
                $i++;
            }
        }
    }

    function testDeleteQuestion() {
    }

    function testDeleteDescription() {
    }

    function testEdit() {
    }

    function testCopy() {
    }

    function testDelete() {
    }
}
