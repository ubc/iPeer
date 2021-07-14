<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Searchs');

class SearchsControllerTest extends CakeTestCase {
    var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.lti_user', 'app.lti_nonce', 'app.lti_tool_registration',
        'app.lti_resource_link', 'app.lti_context',
        'app.roles_user', 'app.event', 'app.event_template_type', 'app.rubrics_lom',
        'app.group_event', 'app.evaluation_submission', 'app.rubrics_criteria_comment',
        'app.survey_group_set', 'app.survey_group', 'app.rubrics_criteria',
        'app.survey_group_member', 'app.question', 'app.rubric',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize', 'app.sys_parameter',
    );

    function startCase() {
        echo "Start Search controller test.\n";
    }

    function endCase() {
    }

    function startTest($method) {
    /*    $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
        $controller = new FakeController();
        $controller->constructClasses();
        $controller->startupProcess();
        $controller->Component->startup($controller);
        $controller->Auth->startup($controller);
        $admin = array('User' => array('username' => 'Admin',
            'password' => 'passwordA'));
        $controller->Auth->login($admin);*/
    }

    function endTest($method) {
    }

    function testIndex() {
    /*    $result = $this->testAction('/home/index', array('connection' => 'test_suite', 'return' => 'contents'));
        var_dump($result);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['group_num'], '1');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['group_num'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['group_num'], '3');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['member_count'], '0');*/
    }

}
