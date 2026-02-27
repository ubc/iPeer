<?php
App::import('Controller', 'Departments');
App::import('Lib', 'ExtendedAuthTestCase');

// Mock _stop and header so the test process survives redirects.
// redirect() is overridden in the subclass to capture the URL.
Mock::generatePartial('DepartmentsController', 'MockCsrfBaseController', array('_stop', 'header'));

class MockCsrfController extends MockCsrfBaseController {
    var $autoRender = false;
    var $redirectUrl = null;

    function redirect($url, $status = null, $exit = true) {
        // Only store the first redirect. In real dispatch, redirect() calls
        // _stop() which exits PHP, so nothing after it runs. In tests _stop()
        // is suppressed, meaning the action can issue a second redirect after
        // beforeFilter() already redirected (e.g. a CSRF block). Keeping only
        // the first call matches what the real dispatcher would have observed.
        if ($this->redirectUrl === null) {
            $this->redirectUrl = $url;
        }
    }
}

class CsrfControllerTestCase extends ExtendedAuthTestCase {
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
        echo "Start CSRF controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
            )
        );
    }

    function endCase() {}

    function startTest($method) {
        $this->controller = new MockCsrfController();
        putenv('IPEER_ENFORCE_REFERRER=1');
    }

    function testAction($url = '', $params = array()) {
        $_SERVER['REQUEST_METHOD'] = strtoupper($params['method'] ?? 'GET');
        if (empty($_SERVER['HTTP_HOST'])) {
            $_SERVER['HTTP_HOST'] = 'localhost';
        }
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        }
        return parent::testAction($url, $params);
    }

    function endTest($method) {
        unset($_SERVER['HTTP_REFERER']);
        putenv('IPEER_ENFORCE_REFERRER');
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }

    function getController() {
        return $this->controller;
    }

    function testCrossOriginRefererIsBlocked() {
        $_SERVER['HTTP_REFERER'] = 'http://other.com/url';
        $this->testAction('/departments/index', array('method' => 'post'));
        $this->assertEqual($this->controller->redirectUrl, '/home');
        $this->assertPattern('/outside the app/', $this->controller->Session->read('Message.flash.message'));
    }

    function testAbsentRefererIsBlocked() {
        $_SERVER['HTTP_REFERER'] = '';
        $this->testAction('/departments/index', array('method' => 'post'));
        $this->assertEqual($this->controller->redirectUrl, '/home');
        $this->assertPattern('/outside the app/', $this->controller->Session->read('Message.flash.message'));
    }

    function testCrossOriginRefererAllowedWhenNotEnforced() {
        putenv('IPEER_ENFORCE_REFERRER');
        $_SERVER['HTTP_REFERER'] = 'http://other.com/url';
        $this->testAction('/departments/index', array('method' => 'post'));
        $this->assertNull($this->controller->redirectUrl);
    }

    function testSameOriginRefererIsAllowed() {
        $_SERVER['HTTP_REFERER'] = 'http://' . $_SERVER['HTTP_HOST'] . '/some/page';
        $this->testAction('/departments/index', array('method' => 'post'));
        $this->assertNull($this->controller->redirectUrl);
    }

    function testSameHostDifferentPortIsAllowed() {
        // Port is intentionally ignored in the Referer check — only the hostname
        // is compared. A same-host Referer with an explicit port must pass.
        $_SERVER['HTTP_REFERER'] = 'http://' . $_SERVER['HTTP_HOST'] . ':8080/some/page';
        $this->testAction('/departments/index', array('method' => 'post'));
        $this->assertNull($this->controller->redirectUrl);
    }
}
