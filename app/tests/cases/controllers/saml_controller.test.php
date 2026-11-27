<?php
App::import('Controller', 'Saml');
App::import('Lib', 'ExtendedAuthTestCase');
require_once ROOT . DS . 'app' . DS . 'libs' . DS . 'IPeerSamlResponse.php';
require_once ROOT . DS . 'app' . DS . 'tests' . DS . 'cases' . DS . 'libs' . DS . 'saml_test_kit.php';

// In the app, redirect() ends the request, so no code after it runs. The test
// controller does the same: redirect() saves the URL in $redirectUrl, then
// throws SamlTestRedirect to stop the action. post() catches it. It is an
// Error, not an Exception, so the app's catch (Exception) blocks do not catch it.
class SamlTestRedirect extends Error {
}

class TestSamlController extends SamlController {
    public $redirectUrl = null;
    public $headers = [];

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
    ['isAuthorized', 'render', '_stop']);

/**
 * The SAML login endpoint (acs): what each kind of SAMLResponse does to the
 * session, and where the browser goes next. SamlTestKit makes the responses
 * and the SAML_SETTINGS that accept them.
 *
 * The requests go to the default route /saml/acs. This test does not check
 * the route that the IdP posts to (/__samlwrapper/api/saml/auth in
 * routes.php).
 *
 * @package app.tests.cases.controllers
 */
class SamlControllerTestCase extends ExtendedAuthTestCase {
    public $fixtures = [
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
        'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval',
    ];

    private $samlTestKit;
    // SAML_SETTINGS, IPEER_SECRET_KEY and REQUEST_METHOD before each test, put back after it
    private $environmentBefore = [];
    // Whether testAction() logs in the default user before the request
    private $loggedIn = false;

    function startCase()
    {
        echo "Start SAML controller test.\n";
        $this->defaultLogin = [
            'User' => [
                'username' => 'root',
                'password' => md5('ipeeripeer'),
            ],
        ];
    }

    function endCase() {
    }

    function startTest($method) {
        echo $method.TEST_LB;
        $this->samlTestKit = new SamlTestKit();
        $this->environmentBefore = [
            'SAML_SETTINGS'    => getenv('SAML_SETTINGS'),
            'IPEER_SECRET_KEY' => getenv('IPEER_SECRET_KEY'),
            'REQUEST_METHOD'   => $_SERVER['REQUEST_METHOD'] ?? null,
        ];
        putenv('SAML_SETTINGS=' . json_encode($this->samlTestKit->settings));
        // The response decrypts with the kit's key, not one from the environment.
        putenv('IPEER_SECRET_KEY');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->loggedIn = false;
        $this->controller = new MockSamlController();
    }

    public function endTest($method)
    {
        foreach (['SAML_SETTINGS', 'IPEER_SECRET_KEY'] as $name) {
            putenv($this->environmentBefore[$name] === false ? $name : "$name={$this->environmentBefore[$name]}");
        }
        if ($this->environmentBefore['REQUEST_METHOD'] === null) {
            unset($_SERVER['REQUEST_METHOD']);
        } else {
            $_SERVER['REQUEST_METHOD'] = $this->environmentBefore['REQUEST_METHOD'];
        }
        unset($_POST['SAMLResponse']);
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

    // afterLogin() writes the roles of the User singleton to the session,
    // which can still hold the user of an earlier test.
    function afterLogin($controller)
    {
        if ($this->loggedIn) {
            parent::afterLogin($controller);
        }
    }

    // Posts a SAMLResponse to acs and returns the URL it redirected to, if any.
    private function post(string $samlResponse)
    {
        $this->controller->redirectUrl = null;
        $this->controller->headers = [];
        $_POST['SAMLResponse'] = $samlResponse;
        try {
            $this->testAction('/saml/acs');
        } catch (SamlTestRedirect $e) {
        }
        return $this->controller->redirectUrl;
    }

    // Shows that the response fails validation for the given reason, so a
    // test of the controller cannot pass because of another failure.
    private function expectInvalid(string $samlResponse, string $reason)
    {
        try {
            (new IPeerSamlResponse($this->samlTestKit->settings, $samlResponse))->validate();
            $this->fail("The response passed validation; expected: $reason");
        } catch (Exception $e) {
            $this->assertPattern($reason, $e->getMessage());
        }
    }

    function testValidResponseLogsTheUserIn()
    {
        $url = $this->post($this->samlTestKit->buildResponse(['username' => 'root']));
        $this->assertEqual($this->controller->Auth->user('username'), 'root');
        $this->assertNotNull($url);
        $this->assertNoPattern('/login/', (string) $url);
    }

    function testUserWithoutAnAccountGetsTheNotice()
    {
        $url = $this->post($this->samlTestKit->buildResponse(['username' => 'no_such_user']));
        $this->assertEqual($url, '/login?notice=no_account');
        $this->assertNull($this->controller->Auth->user());
    }

    function testInvalidResponseDoesNotLogIn()
    {
        $response = $this->samlTestKit->buildResponse(['username' => 'root', 'tamperAfterSign' => true]);
        $this->expectInvalid($response, '/Reference validation failed/');
        $this->assertEqual($this->post($response), '/login');
        $this->assertNull($this->controller->Auth->user());
    }

    function testResponseForAnotherServiceDoesNotLogIn()
    {
        $response = $this->samlTestKit->buildResponse(['username' => 'root', 'audience' => 'https://other-sp.example.com']);
        $this->expectInvalid($response, '/Audience mismatch/');
        $this->assertEqual($this->post($response), '/login');
        $this->assertNull($this->controller->Auth->user());
    }

    function testGetIsRefused()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $url = $this->post($this->samlTestKit->buildResponse(['username' => 'root']));
        $this->assertEqual($this->controller->headers,
            ['HTTP/1.1 405 Method Not Allowed', 'Allow: POST']);
        $this->assertNull($url);
        $this->assertNull($this->controller->Auth->user());
    }

    function testLoggedInUserGoesHome()
    {
        $this->loggedIn = true;
        $url = $this->post($this->samlTestKit->buildResponse(['username' => 'no_such_user']));
        $this->assertEqual($url, '/');
        $this->assertEqual($this->controller->Auth->user('username'), 'root');
    }
}
