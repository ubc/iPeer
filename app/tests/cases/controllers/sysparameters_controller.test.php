<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Sysparameters');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('SysParametersController',
    'MockSysParametersController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class SysparametersControllerTest extends ExtendedAuthTestCase {
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
        'app.mixeval_question_desc', 'app.mixeval', 'app.survey_input',
    );

    function startCase() {
        echo "Start SysParameter controller test.\n";
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
        $this->controller = new MockSysParametersController();
    }

    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
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

    // This code may be copied from SysParameter, need to be changed
    function testIndex() {
        $result = $this->testAction('/sysparameters/index', array('return' => 'vars'));
        $this->assertEqual(count($result['paramsForList']['data']['entries']), 20);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['parameter_code'], 'system.super_admin');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['parameter_value'], 'root');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['parameter_type'], 'S');
    }


    function testView() {
        $result = $this->testAction('/sysparameters/view/1', array('return' => 'vars'));
        $this->assertEqual($this->controller->data['SysParameter']['parameter_code'], 'system.super_admin');
        $this->assertEqual($this->controller->data['SysParameter']['parameter_value'], 'root');
        $this->assertEqual($this->controller->data['SysParameter']['parameter_type'], 'S');
        $this->assertEqual($this->controller->data['SysParameter']['record_status'], 'A');
    }

    //TODO test saving
    function testEdit() {
        $result = $this->testAction('/sysparameters/edit/1', array('return' => 'vars'));

        $this->assertEqual($this->controller->data['SysParameter']['parameter_code'], 'system.super_admin');
        $this->assertEqual($this->controller->data['SysParameter']['parameter_value'], 'root');
        $this->assertEqual($this->controller->data['SysParameter']['parameter_type'], 'S');
        $this->assertEqual($this->controller->data['SysParameter']['record_status'], 'A');

    }

    //TODO test redirect
    function testDelete() {
        $result = $this->testAction('/sysparameters/delete/1', array('return' => 'vars'));

        $model = ClassRegistry::init('SysParameter');
        $found = $model->find('first', array( 'conditions' => array('id' => 1)));
        $this->assertFalse($found);
    }

}
