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

    public function startCase() {
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
        $this->MixevalQuestionType =&
            ClassRegistry::init('MixevalQuestionType');

        // shared data
        $this->testMixevalData = array(
            "Mixeval" => array(
                "name" => 'TestMixevalForTestCase',
                "availability" => 'private',
                "zero_mark" => 0,
                "self_eval" => 0,
                "MixevalQuestionType" => 3
            ),
            "MixevalQuestion" => array(
                "0" => array(
                    "title" => 'Likert Question',
                    "instructions" => 'Likert Instructions',
                    "required" => 1,
                    "self_eval" => 0,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 1,
                    "multiplier" => 1
                ),
                "1" => array(
                    "title" => 'Paragraph Question',
                    "instructions" => 'Paragraph Instructions',
                    "required" => 0,
                    "self_eval" => 0,
                    "mixeval_question_type_id" => 2,
                    "question_num" => 2
                ),
                "2" => array(
                    "title" => 'Sentence Question',
                    "instructions" => 'Sentence Instructions',
                    "required" => 1,
                    "self_eval" => 0,
                    "mixeval_question_type_id" => 3,
                    "question_num" => 3
                ),
            ),
            "MixevalQuestionDesc" => array(
                "0" => array(
                    "descriptor" => 'Likert Scale 1',
                    "question_index" => 0
                ),
                "1" => array(
                    "descriptor" => 'Likert Scale 2',
                    "question_index" => 0
                ),
                "2" => array(
                    "descriptor" => 'Likert Scale 3',
                    "question_index" => 0
                ),
            ),
        );
    }

    public function endCase() {
    }

    public function startTest($method) {
        echo $method.TEST_LB;
        $this->controller = new MockMixevalsController();
    }

    public function endTest($method) {
    }

    public function getController()
    {
        return $this->controller;
    }

    public function testIndex() {
        $result = $this->testAction('/mixevals/index', array('return' => 'vars'));

        $this->assertEqual(count($result['paramsForList']['data']['entries']), 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['id'], 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['name'], 'Default Mix Evaluation');
        $this->assertEqual($result['paramsForList']['data']['count'], 1);
    }

    public function testView() {
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

    public function testAdd() {
        $result = $this->testAction('/mixevals/add',
            array('return' => 'vars'));
        // make sure the list of question types is set
        $this->assertTrue(isset($result['mixevalQuestionTypes']));
        $this->assertEqual($this->MixevalQuestionType->find('list'),
            $result['mixevalQuestionTypes']);

        // Test a regular save with 1 of each question type
        $data = $this->testMixevalData;
        $result = $this->testAction(
            '/mixevals/add',
            array(
                'method' => 'post',
                'data' => $data,
                'return' => 'vars',
            )
        );
        // Make sure that the save went through successfully
        $mixeval = $this->Mixeval->findByName($data['Mixeval']['name']);
        $this->assertTrue($mixeval);
        // Make sure that the data entered into the database is what we expected
        $this->_verifyMixeval($data, $mixeval);

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
                "self_eval" => 0,
                "MixevalQuestionType" => 3
            ),
            "MixevalQuestion" => array(
                "4" => array(
                    "title" => 'Likert Question 1',
                    "instructions" => 'Likert Instructions 1',
                    "required" => 0,
                    "self_eval" => 0,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 1,
                    "multiplier" => 1
                ),
                "2" => array(
                    "title" => 'Likert Question 2',
                    "instructions" => 'Likert Instructions 2',
                    "required" => 0,
                    "self_eval" => 0,
                    "mixeval_question_type_id" => 1,
                    "question_num" => 2,
                    "multiplier" => 1
                ),
            ),
            "MixevalQuestionDesc" => array(
                "2" => array(
                    "descriptor" => 'Likert Q1 Scale 1',
                    "question_index" => 4
                ),
                "4" => array(
                    "descriptor" => 'Likert Q1 Scale 2',
                    "question_index" => 4
                ),
                "1" => array(
                    "descriptor" => 'Likert Q2 Scale 1',
                    "question_index" => 2
                ),
                "6" => array(
                    "descriptor" => 'Likert Q2 Scale 2',
                    "question_index" => 2
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
        $mixeval = $this->Mixeval->findByName($data['Mixeval']['name']);
        $this->assertTrue($mixeval);

        // Make sure that the data entered into the database is what we expected
        $this->_verifyMixeval($data, $mixeval);
    }

    public function testEdit() {
        // Add a mixeval so we have one to edit
        $expected = $this->testMixevalData;
        $result = $this->testAction(
            '/mixevals/add',
            array(
                'method' => 'post',
                'data' => $expected,
                'return' => 'vars',
            )
        );
        // Make sure that the save went through successfully
        $mixeval = $this->Mixeval->findByName($expected['Mixeval']['name']);
        $this->assertTrue($mixeval);
        $mixevalId = $mixeval['Mixeval']['id'];
        $this->assertTrue($mixevalId > 0);

        // Try accessing the edit page for the mixeval we just added
        echo "testEdit: Loading existing data, ";
        $result = $this->testAction("/mixevals/edit/$mixevalId",
            array('return' => 'vars'));
        // make sure the list of question types is set
        $this->assertTrue(isset($result['mixevalQuestionTypes']));
        $this->assertEqual($this->MixevalQuestionType->find('list'),
            $result['mixevalQuestionTypes']);

        // Check that edit is correctly loading existing data.
        $actual = $this->controller->data;
        // Need to verify the 3 components of controller data: Mixeval,
        // MixevalQuestion, MixevalQuestionDesc.
        $this->_verifyMixeval($expected, $actual); // verify Mixeval part
        // Because _verifyMixeval uses the the Mixeval part of its
        // $actual param to call up MixevalQuestion and MixevalQuestionDesc
        // from database, it ignores the MixevalQuestion and MixevalQuestionDesc
        // that $actual may have. So as a lazy workaround, we give $actual to
        // _verifyMixeval() twice, making it check the MixevalQuestion and
        // MixevalQuestionDesc in $actual against the database.
        $this->_verifyMixeval($actual, $actual); // verify Question and Desc

        // Assuming that $actul passes all the tests, we can now use it as
        // a base for data submissions to edit
        $expected = $actual;
        $expected['Mixeval']['self_eval'] = 0;
        unset($expected['Event']);
        // Add 2 likert questions
        echo "Add likert questions, ";
        array_push($expected['MixevalQuestion'],
            array(
                "title" => 'Likert Question 1',
                "instructions" => 'Likert Instructions 1',
                "required" => 0,
                "self_eval" => 0,
                "mixeval_question_type_id" => 1,
                "question_num" => 4,
                "multiplier" => 1
            )
        );
        array_push($expected['MixevalQuestion'],
            array(
                "title" => 'Likert Question 2',
                "instructions" => 'Likert Instructions 2',
                "required" => 0,
                "self_eval" => 0,
                "mixeval_question_type_id" => 1,
                "question_num" => 5,
                "multiplier" => 1
            )
        );
        array_push($expected['MixevalQuestionDesc'],
            array(
                "descriptor" => 'Likert Q1 Scale 1',
                "question_index" => 3
            )
        );
        array_push($expected['MixevalQuestionDesc'],
            array(
                "descriptor" => 'Likert Q1 Scale 2',
                "question_index" => 3
            )
        );
        array_push($expected['MixevalQuestionDesc'],
            array(
                "descriptor" => 'Likert Q2 Scale 1',
                "question_index" => 4
            )
        );
        array_push($expected['MixevalQuestionDesc'],
            array(
                "descriptor" => 'Likert Q2 Scale 2',
                "question_index" => 4
            )
        );
        // Try to save these new questions
        $result = $this->testAction(
            "/mixevals/edit/$mixevalId",
            array(
                'method' => 'post',
                'data' => $expected,
                'return' => 'vars',
            )
        );
        // Try accessing the edit page for the mixeval we just saved
        $result = $this->testAction("/mixevals/edit/$mixevalId",
            array('return' => 'vars'));
        // Check that the altered data was correctly saved and loaded
        $actual = $this->controller->data;
        $this->_verifyMixeval($expected, $actual); // verify Mixeval part
        $this->_verifyMixeval($actual, $actual); // verify Question and Desc

        // Now try to delete questions
        echo "Delete questions, ";
        $expected = $actual;
        unset($expected['Event']);
        // Remove 2 questions
        unset($expected['MixevalQuestion'][1]); // rm the paragraph question
        unset($expected['MixevalQuestion'][3]); // rm a likert question
        unset($expected['MixevalQuestionDesc'][3]); // the likert's desc 1
        unset($expected['MixevalQuestionDesc'][4]); // the likert's desc 2
        $expected['Mixeval']['self_eval'] = 0; // set the self_eval field
        // Try to save these changes
        $result = $this->testAction(
            "/mixevals/edit/$mixevalId",
            array(
                'method' => 'post',
                'data' => $expected,
                'return' => 'vars',
            )
        );
        // Try accessing the edit page for the mixeval we just saved
        $result = $this->testAction("/mixevals/edit/$mixevalId",
            array('return' => 'vars'));
        // Check that the altered data was correctly saved and loaded
        $actual = $this->controller->data;
        $this->_verifyMixeval($expected, $actual); // verify Mixeval part
        $this->_verifyMixeval($actual, $actual); // verify Question and Desc

        // Try to delete question descs only
        echo "Delete question descs, ";
        $expected = $actual;
        unset($expected['Event']);
        // Remove all Likert Scale 1 desc
        unset($expected['MixevalQuestionDesc'][0]);
        unset($expected['MixevalQuestionDesc'][3]);
        $expected['Mixeval']['self_eval'] = 0;
        // Try to save these changes
        $result = $this->testAction(
            "/mixevals/edit/$mixevalId",
            array(
                'method' => 'post',
                'data' => $expected,
                'return' => 'vars',
            )
        );
        // Try accessing the edit page for the mixeval we just saved
        $result = $this->testAction("/mixevals/edit/$mixevalId",
            array('return' => 'vars'));
        // Check that the altered data was correctly saved and loaded
        $actual = $this->controller->data;
        $this->_verifyMixeval($expected, $actual); // verify Mixeval part
        $this->_verifyMixeval($actual, $actual); // verify Question and Desc

        // Try to reorder the questions
        echo "Question reordering, ";
        $expected = $actual;
        unset($expected['Event']);
        // assign the existing questions to new indexes and new orders
        $expected['Mixeval']['self_eval'] = 0;
        $expected['MixevalQuestion'][3] = $expected['MixevalQuestion'][2];
        $expected['MixevalQuestion'][3]['question_num'] = 2;
        $expected['MixevalQuestion'][5] = $expected['MixevalQuestion'][0];
        $expected['MixevalQuestion'][5]['question_num'] = 3;
        $expected['MixevalQuestion'][9] = $expected['MixevalQuestion'][1];
        $expected['MixevalQuestion'][9]['question_num'] = 1;
        // remove the existing questions now that they're in new indexes
        unset($expected['MixevalQuestion'][0]);
        unset($expected['MixevalQuestion'][1]);
        unset($expected['MixevalQuestion'][2]);
        // update question desc to match their new indexes
        $expected['MixevalQuestionDesc'][0]['question_index'] = 5;
        $expected['MixevalQuestionDesc'][1]['question_index'] = 5;
        $expected['MixevalQuestionDesc'][2]['question_index'] = 3;
        // also move the question desc indexes around a bit, but keep in mind
        // that each question's scale level is determined sequentially
        $expected['MixevalQuestionDesc'][4] =
            $expected['MixevalQuestionDesc'][0];
        $expected['MixevalQuestionDesc'][5] =
            $expected['MixevalQuestionDesc'][2];
        $expected['MixevalQuestionDesc'][6] =
            $expected['MixevalQuestionDesc'][1];
        unset($expected['MixevalQuestionDesc'][0]);
        unset($expected['MixevalQuestionDesc'][1]);
        unset($expected['MixevalQuestionDesc'][2]);
        // Try to save these changes
        $result = $this->testAction(
            "/mixevals/edit/$mixevalId",
            array(
                'method' => 'post',
                'data' => $expected,
                'return' => 'vars',
            )
        );
        // The controller should unscramble all the order changes, so we'll
        // have to do that too to get the correct expected data
        $expected['MixevalQuestion'][0] = $expected['MixevalQuestion'][9];
        $expected['MixevalQuestion'][1] = $expected['MixevalQuestion'][3];
        $expected['MixevalQuestion'][2] = $expected['MixevalQuestion'][5];
        unset($expected['MixevalQuestion'][9]);
        unset($expected['MixevalQuestion'][3]);
        unset($expected['MixevalQuestion'][5]);
        $expected['MixevalQuestionDesc'][4]['question_index'] = 2;
        $expected['MixevalQuestionDesc'][6]['question_index'] = 2;
        $expected['MixevalQuestionDesc'][5]['question_index'] = 1;
        $expected['MixevalQuestionDesc'][0] =
            $expected['MixevalQuestionDesc'][4];
        $expected['MixevalQuestionDesc'][1] =
            $expected['MixevalQuestionDesc'][6];
        $expected['MixevalQuestionDesc'][2] =
            $expected['MixevalQuestionDesc'][5];
        unset($expected['MixevalQuestionDesc'][4]);
        unset($expected['MixevalQuestionDesc'][5]);
        unset($expected['MixevalQuestionDesc'][6]);
        // Try accessing the edit page for the mixeval we just saved
        $result = $this->testAction("/mixevals/edit/$mixevalId",
            array('return' => 'vars'));
        // Check that the altered data was correctly saved and loaded
        $actual = $this->controller->data;
        $this->_verifyMixeval($expected, $actual); // verify Mixeval part
        $this->_verifyMixeval($actual, $actual); // verify Question and Desc
        echo "<br />\n";
    }

    /**
     * Helper method that compares 2 mixeval entries to see if they have the
     * content.
     *
     * @param array $expected - This is the data as it would be seen in a form
     * submit. With Mixeval, MixevalQuestion, and MixevalQuestionDesc all in
     * one place.
     * @param array $actual - This is the data that comes out of the database
     * from a model's find method. Note that we only expect Mixeval fields
     * here, MixevalQuestion and MixevalQuestionDesc are obtained in another
     * query based on the actual Mixeval's id.
     */
    private function _verifyMixeval($expected, $actual) {
        // Check that the mixeval entry is correct
        $this->assertEqual($expected['Mixeval']['name'],
            $actual['Mixeval']['name']);
        $this->assertEqual($expected['Mixeval']['availability'],
            $actual['Mixeval']['availability']);
        $this->assertEqual($expected['Mixeval']['zero_mark'],
            $actual['Mixeval']['zero_mark']);

        // Check that the question entries are correct
        $actualId = $actual['Mixeval']['id'];
        $actualQs = $this->MixevalQuestion->findAllByMixevalId($actualId,
            array(), array('question_num' => 'asc'));
        // make sure that we have the same number of questions
        $this->assertEqual(count($actualQs), count($expected['MixevalQuestion']));
        $expectedQuestions = $expected['MixevalQuestion'];
        // make sure that the question are stored same as entered
        $i = 0;
        foreach ($expected['MixevalQuestion'] as $expectedQ) {
            $this->assertTrue(isset($actualQs[$i]));
            $actualQ = $actualQs[$i];
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
            else {
                $this->assertEqual(0,
                    $actualQ['MixevalQuestion']['multiplier']);
            }
            $i++;
        }

        // Check that the question descriptor entries are correct
        // get a mapping of question num to descs for expected data
        $scaleTracking = array(); // have to insert an expected scale, since
                                // it's assigned by the controller, not
                                // submitted with the form
        $expectedDescs = array();
        foreach ($expected['MixevalQuestionDesc'] as $expectedD) {
            $expectedQIndex = $expectedD['question_index'];
            $expectedQNum =
                $expected['MixevalQuestion'][$expectedQIndex]['question_num'];
            if (!isset($expectedDescs[$expectedQNum])) {
                $expectedDescs[$expectedQNum] = array();
                $scaleTracking[$expectedQNum] = 1;
            }
            $expectedD['scale_level'] = $scaleTracking[$expectedQNum];
            array_push($expectedDescs[$expectedQNum], $expectedD);
            $scaleTracking[$expectedQNum]++;
        }
        // get a mapping of question num to descs for actual data
        $actualDescs = array();
        foreach ($actualQs as $q) {
            $descs = $q['MixevalQuestionDesc'];
            if (!empty($descs)) {
                $actualDescs[$q['MixevalQuestion']['question_num']] = $descs;
            }
        }

        // check expected descs with actual, can't do a simple assertEqual
        // since we have mismatched keys. Shouldn't need to check the IDs since
        // the data wouldn't be matched correctly with their questions if
        // the IDs were wrong.
        foreach ($expectedDescs as $qNum => $expectedQDescs) {
            $actualQDescs = $actualDescs[$qNum];
            $this->assertEqual(count($expectedQDescs), count($actualQDescs));
            $i = 0;
            foreach ($expectedQDescs as $expectedD) {
                $actualD = $actualQDescs[$i];
                $this->assertEqual($expectedD['descriptor'],
                    $actualD['descriptor']);
                $this->assertEqual($expectedD['scale_level'],
                    $actualD['scale_level']);
                $i++;
            }
        }
    }

    public function testCopy() {
    }

    public function testDelete() {
    }
}
