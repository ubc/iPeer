<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Rubrics');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('RubricsController',
    'MockRubricsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class RubricsControllerTest extends ExtendedAuthTestCase {
    public $controller = null;

    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize', 'app.penalty', 'app.evaluation_simple',
        'app.faculty', 'app.user_tutor', 'app.course_department',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.user_faculty', 'app.department', 'app.sys_parameter',
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Rubric controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }

    function endCase() {
    }

    function startTest($method) {
        echo $method.TEST_LB;
        $this->controller = new MockRubricsController();
    }

    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destroyed.
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/rubrics/index', array('connection' => 'test_suite', 'return' => 'contents'));
        //$this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
    }
    
    function testPostProcess() {
        
    }
    
    function testSetUpAjaxList() {
    
    }
    
    function testAjaxList() {
    
    }
    
    function testView() {
    
    }
    
    function testAdd() {
        $result = $this->testAction('/rubrics/add', array('connection' => 'test_suite', 'return' => 'vars'));
    }
    
    function testEdit() {
    
    }
    
    public function testHasOnlyOwnIdsForANewRubric()
    {
        $controller = new RubricsController();
        $controller->constructClasses();
        // The forms post empty ids for new rows.
        $posted = [
            'Rubric' => ['id' => '', 'name' => 'New'],
            'RubricsLom' => [['id' => '', 'lom_comment' => 'Low']],
            'RubricsCriteria' => [[
                'id' => '',
                'criteria' => 'Effort',
                'RubricsCriteriaComment' => [['id' => '', 'criteria_comment' => 'Little']],
            ]],
        ];

        $controller->data = $posted;
        $this->assertTrue($controller->_hasOnlyOwnIds(null));
        $this->assertFalse(isset($controller->data['Rubric']['id']));
        $this->assertEqual('Effort', $controller->data['RubricsCriteria'][0]['criteria']);

        $paths = [
            'Rubric',
            'RubricsLom.0',
            'RubricsCriteria.0',
            'RubricsCriteria.0.RubricsCriteriaComment.0',
        ];
        foreach ($paths as $path) {
            $controller->data = Set::insert($posted, "$path.id", '1');
            $this->assertFalse($controller->_hasOnlyOwnIds(null), "$path.id");
        }
    }

    public function testHasOnlyOwnIdsForAnEditedRubric()
    {
        $controller = new RubricsController();
        $controller->constructClasses();
        // Seeded rubric 1 has levels 1 to 5, criteria 1 to 3 and comments 1
        // to 15.
        $posted = [
            'Rubric' => ['id' => '1', 'name' => 'Edited'],
            'RubricsLom' => [
                ['id' => '5', 'lom_comment' => 'Own level'],
                ['id' => '', 'lom_comment' => 'New level'],
            ],
            'RubricsCriteria' => [[
                'id' => '3',
                'criteria' => 'Own criterion',
                'RubricsCriteriaComment' => [
                    ['id' => '15', 'criteria_comment' => 'Own comment'],
                    ['id' => '', 'criteria_comment' => 'New comment'],
                ],
            ]],
        ];

        $controller->data = $posted;
        $this->assertTrue($controller->_hasOnlyOwnIds(1));
        $this->assertIdentical(1, $controller->data['Rubric']['id']);
        $this->assertEqual('5', $controller->data['RubricsLom'][0]['id']);

        $refused = [
            'another rubric id' => Set::insert($posted, 'Rubric.id', '2'),
            'an unknown level' => Set::insert($posted, 'RubricsLom.1.id', '999'),
            'an unknown criterion' => Set::insert($posted, 'RubricsCriteria.0.id', '999'),
            'an unknown comment' => Set::insert(
                $posted,
                'RubricsCriteria.0.RubricsCriteriaComment.1.id',
                '999'
            ),
        ];
        foreach ($refused as $case => $data) {
            $controller->data = $data;
            $this->assertFalse($controller->_hasOnlyOwnIds(1), $case);
        }

        // The rows of rubric 1 are not the rows of any other rubric.
        $controller->data = Set::insert($posted, 'Rubric.id', '999');
        $this->assertFalse($controller->_hasOnlyOwnIds(999));
    }

    function testCopy() {
    
    }
    
    function testDelete() {
    
    }

}
