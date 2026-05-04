<?php
App::import('Controller', 'Guard.Guard');
App::import('Lib', 'ExtendedAuthTestCase');

Mock::generatePartial(
    'GuardController',
    'MockGuardController',
    array('_stop', 'header', 'render', 'redirect')
);

class GuardControllerTestCase extends ExtendedAuthTestCase {
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
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {}
    function endCase() {}

    function startTest($method) {
        echo $method . TEST_LB;
        $this->controller = new MockGuardController();
    }

    function endTest($method) {
        putenv('FORCE_SAML_LOGIN=');
        putenv('SAML_SETTINGS=');
        unset($_GET['defaultlogin']);
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }

    function getController() {
        return $this->controller;
    }

    // No authentication needed — we are testing the unauthenticated login page
    function login($controller) {}
    function afterLogin($controller) {}

    function testForceSamlLoginBlocksDefaultloginBypass() {
        putenv('SAML_SETTINGS={"dummy":"config"}');
        putenv('FORCE_SAML_LOGIN=true');
        $_GET['defaultlogin'] = 'true';

        $result = $this->testAction('/login', array('method' => 'get', 'return' => 'vars'));

        $this->assertTrue(
            isset($result['saml_logout_notice']),
            '?defaultlogin=true bypass should be blocked when FORCE_SAML_LOGIN is active'
        );
    }

    function testForceSamlLoginBlocksCraftedPost() {
        putenv('SAML_SETTINGS={"dummy":"config"}');
        putenv('FORCE_SAML_LOGIN=true');

        $this->testAction('/login', array(
            'method' => 'post',
            'return' => 'vars',
            'data' => array('Guard' => array('username' => 'root', 'password' => 'ipeeripeer')),
        ));

        $this->assertFalse(
            (bool) $this->controller->Auth->user(),
            'Crafted POST to /login should not authenticate when FORCE_SAML_LOGIN is active'
        );
    }
}
