<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Lib', 'ExtendedTestCase');
App::import('Controller', 'Courses');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('CoursesController',
    'MockCoursesController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

/**
 * CoursesControllerTest Course controller test case
 *
 * @uses ExtendedTestCase
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class CoursesControllerTest extends ExtendedTestCase
{
    private $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize',
    );

    /* public startCase() {{{ */
    /**
     * startCase case startup
     *
     * @access public
     * @return void
     */
    public function startCase()
    {
        echo '<h1>Starting Test Case</h1>';
    }
    /* }}} */

    /* public endCase() {{{ */
    /**
     * endCase case ending
     *
     * @access public
     * @return void
     */
    public function endCase()
    {
        echo '<h1>Ending Test Case</h1>';
    }
    /* }}} */

    /* public startTest() {{{ */
    /**
     * startTest prepare tests
     *
     * @access public
     * @return void
     */
    public function startTest()
    {
        $this->testController = new MockCoursesController();
        $this->testController->params['action'] = 'test';
        $this->testController->__construct();
        $this->testController->constructClasses();
        $admin = array('User' => array('username' => 'root',
            'password' => 'ipeer'));
        //$this->testController->Auth->login($admin);
        $this->testController = $this->testController;
    }
    /* }}} */

    /* public endTest() {{{ */
    /**
     * endTest clean up
     *
     * @access public
     * @return void
     */
    public function endTest()
    {
        unset($this->testController);
        ClassRegistry::flush();
    }
    /* }}} */

    /* public testIndex() {{{ */
    /**
     * testIndex testing index action
     *
     * @access public
     * @return void
     */
    public function testIndex()
    {
        $result = $this->testAction('/courses/index');
        debug($result['coursesList']);
        exit;
    }
    /* }}} */
}
