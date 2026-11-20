<?php
App::import('Controller', 'Saml');
App::import('Lib', 'ExtendedAuthTestCase');
require_once ROOT . DS . 'app' . DS . 'tests' . DS . 'cases' . DS . 'libs' . DS . 'saml_test_kit.php';

// In the app, redirect() ends the request. The test controller stops at the
// first redirect in the same way, and keeps its URL.
class SamlTestRedirect extends Exception {
}

class TestSamlController extends SamlController {
    public $redirectUrl = null;
    public $headers = array();

    function header($status) {
        $this->headers[] = $status;
    }

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
        throw new SamlTestRedirect();
    }
}

Mock::generatePartial('TestSamlController',
    'MockSamlController',
    array('isAuthorized', 'render', '_stop'));

/**
 * The SAML login endpoint (acs): what each kind of SAMLResponse does to the
 * session, and where the browser goes next. SamlTestKit makes the responses
 * and the SAML_SETTINGS that accept them.
 *
 * @package app.tests.cases.controllers
 */
class SamlControllerTestCase extends ExtendedAuthTestCase {
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

    private $kit;
    private $requestMethod;
    // Whether testAction() logs in the default user before the request
    private $loggedIn = false;

    function startCase()
    {
        echo "Start SAML controller test.\n";
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
        $this->kit = new SamlTestKit();
        putenv('SAML_SETTINGS=' . json_encode($this->kit->settings));
        $this->requestMethod = $_SERVER['REQUEST_METHOD'] ?? null;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->loggedIn = false;
        $this->controller = new MockSamlController();
    }

    public function endTest($method)
    {
        putenv('SAML_SETTINGS');
        unset($_POST['SAMLResponse']);
        if ($this->requestMethod === null) {
            unset($_SERVER['REQUEST_METHOD']);
        } else {
            $_SERVER['REQUEST_METHOD'] = $this->requestMethod;
        }
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

    // A SAML login starts with nobody logged in, unless the test says so.
    function login($controller)
    {
        return $this->loggedIn ? parent::login($controller) : true;
    }

    private function postResponse(array $opts = array())
    {
        $_POST['SAMLResponse'] = $this->kit->buildResponse($opts);
        try {
            $this->testAction('/saml/acs');
        } catch (SamlTestRedirect $e) {
        }
        return $this->controller->redirectUrl;
    }

    function testValidResponseLogsTheUserIn()
    {
        $url = $this->postResponse(array('username' => 'root'));
        $this->assertEqual($this->controller->Auth->user('username'), 'root');
        $this->assertNotNull($url);
        $this->assertNoPattern('/login/', (string) $url);
    }

    function testUserWithoutAnAccountGetsTheNotice()
    {
        $url = $this->postResponse(array('username' => 'no_such_user'));
        $this->assertEqual($url, '/login?notice=no_account');
        $this->assertNull($this->controller->Auth->user());
    }

    function testInvalidResponseDoesNotLogIn()
    {
        $url = $this->postResponse(array('username' => 'root', 'tamperAfterSign' => true));
        $this->assertEqual($url, '/login');
        $this->assertNull($this->controller->Auth->user());
    }

    function testResponseForAnotherServiceDoesNotLogIn()
    {
        $url = $this->postResponse(array('username' => 'root', 'audience' => 'https://other-sp.example.com'));
        $this->assertEqual($url, '/login');
        $this->assertNull($this->controller->Auth->user());
    }

    function testGetIsRefused()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $url = $this->postResponse(array('username' => 'root'));
        $this->assertEqual($this->controller->headers,
            array('HTTP/1.1 405 Method Not Allowed', 'Allow: POST'));
        $this->assertNull($url);
        $this->assertNull($this->controller->Auth->user());
    }

    function testLoggedInUserGoesHome()
    {
        $this->loggedIn = true;
        $url = $this->postResponse(array('username' => 'no_such_user'));
        $this->assertEqual($url, '/');
        $this->assertEqual($this->controller->Auth->user('username'), 'root');
    }
}
