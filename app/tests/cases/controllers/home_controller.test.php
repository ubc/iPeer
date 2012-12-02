<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Home');

// mock instead of needing to create a new controller for every test
print Mock::generatePartial(
    'HomeController',
    'MockHomeController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

class HomeControllerTest extends ExtendedAuthTestCase {
    var $fixtures = array(
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
        'app.oauth_token'
    );

    public function getController()
    {
        $controller = new MockHomeController;
        $controller->components = array_diff($controller->components, array("Guard.Guard"));
        $controller->params['action'] = 'test';
        $controller->__construct();
        $controller->constructClasses();
        return $controller;
    }

    function startCase()
    {
        echo '<h1>Starting Test Case</h1>';
    }

    function endCase()
    {
        echo '<h1>Ending Test Case</h1>';
    }

    function startTest()
    {
/*    $controller = new FakeController();
    $controller->constructClasses();
    $controller->startupProcess();
    $controller->Component->startup($controller);
    $controller->Auth->startup($controller);
    $admin = array('User' => array('username' => 'Admin',
                                   'password' => 'passwordA'));
$controller->Auth->login($admin);*/
        echo 'Start Test';
    }

    function endTest()
    {
        echo '<hr />';
    }

    function testIndex() {
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        var_dump($result);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['group_num'], '1');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['group_num'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['group_num'], '3');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['member_count'], '0');
    }

}
