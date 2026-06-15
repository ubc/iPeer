<?php
App::import('Controller', 'Users');
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Component', 'CanvasApi');

Mock::generatePartial('UsersController',
    'MockUsersController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header', '_createCanvasApi'));

/**
 * Stub subclass used by canvasOauthCallback tests to prevent real HTTP calls
 * to the Canvas token endpoint. Set $fakeToken before calling handleOauthCallback.
 */
class TestCanvasApiComponent extends CanvasApiComponent
{
    public $fakeToken = null;

    public function getApiTokenUsingCode($code)
    {
        return $this->fakeToken;
    }
}

class UsersControllerTestCase extends ExtendedAuthTestCase
{
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
        'app.mixeval_question_desc', 'app.mixeval', 'app.mixeval_question_type',
        'app.oauth_client', 'app.user_oauths', 'app.email_schedule',
        'app.email_template',
    );

    public function startCase()
    {
        echo "Start Users controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer'),
            )
        );
    }

    public function endCase()
    {
    }

    public function startTest($method)
    {
        echo $method . TEST_LB;
        $this->controller = new MockUsersController();
    }

    public function endTest($method)
    {
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

    /**
     * Happy path: merging two students with no shared courses or submissions
     * results in the secondary account being deleted and a success flash message.
     *
     * Uses user id=9 (course 2 only) as primary and user id=13 (course 1 only) as secondary —
     * both are role 5 (student) with no overlapping enrollments, groups, or evaluations.
     */
    public function testMergeHappyPath()
    {
        $userModel = ClassRegistry::init('User');

        $this->testAction('/users/merge', array(
            'fixturize' => true,
            'method' => 'post',
            'data' => array(
                'User' => array(
                    'primaryAccount' => '9',
                    'secondaryAccount' => '13',
                )
            )
        ));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The two accounts have successfully merged.');

        $secondary = $userModel->find('first', array('conditions' => array('User.id' => 13)));
        $this->assertFalse($secondary, 'Secondary account should have been deleted after merge.');
    }

    /**
     * Single-conflict path: users 5 and 6 are both enrolled in course 1.
     * Verifies that a single shared enrollment (which previously produced
     * invalid SQL via the CakePHP 1.3 'field NOT' => array(one_value) bug)
     * is handled correctly and the merge still succeeds.
     */
    public function testMergeSingleConflictEnrollment()
    {
        $userModel = ClassRegistry::init('User');

        $this->testAction('/users/merge', array(
            'fixturize' => true,
            'method' => 'post',
            'data' => array(
                'User' => array(
                    'primaryAccount' => '5',
                    'secondaryAccount' => '6',
                )
            )
        ));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The two accounts have successfully merged.');

        $secondary = $userModel->find('first', array('conditions' => array('User.id' => 6)));
        $this->assertFalse($secondary, 'Secondary account should have been deleted after merge.');
    }

    // ---------------------------------------------------------------------------
    // canvasOauthCallback tests
    //
    // These call the action directly (not via testAction) so we can control auth
    // state and avoid the full request lifecycle. _exchangeCanvasCode is mocked
    // to prevent real HTTP calls to Canvas.
    // ---------------------------------------------------------------------------

    /**
     * Initialises the controller for direct canvasOauthCallback calls.
     * Sets params to reflect the callback action so Auth->allow() is respected
     * during Component::startup(). Pass false to skip login (unauthenticated tests).
     */
    private function _setupCallbackController($loggedIn = true)
    {
        $this->controller->__construct();
        $this->controller->constructClasses();
        $this->controller->Session->delete('Message');
        $this->controller->params = array(
            'controller' => 'users',
            'action'     => 'canvasOauthCallback',
            'url'        => array('url' => '/users/canvasOauthCallback'),
            'named'      => array(),
            'pass'       => array(),
            'plugin'     => null,
        );
        $this->controller->action = '/users/canvasOauthCallback';
        $this->controller->Component->initialize($this->controller);
        if ($loggedIn) {
            $this->login($this->controller);
            $this->afterLogin($this->controller);
        }
        $this->controller->beforeFilter();
        $this->controller->Component->startup($this->controller);
    }

    /**
     * Unauthenticated visitor hits the callback URL (e.g. session expired between
     * OAuth initiation and Canvas redirect). Should see an explanatory flash and
     * be sent to the login page without attempting any token exchange.
     */
    public function testCanvasOauthCallbackSessionExpired()
    {
        $this->_setupCallbackController(false);

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/session expired/', $message['message']);
    }

    /**
     * User cancelled the Canvas authorisation dialog. Should see the cancellation
     * message, and both OAuth session keys should be cleaned up so the stale state
     * cannot interfere with a subsequent attempt.
     */
    public function testCanvasOauthCallbackAccessDenied()
    {
        $testApi = new TestCanvasApiComponent(1);
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        $this->controller->Session->write('oauth_canvas_state', 'st_abc');
        $this->controller->Session->write('canvas_oauth_return_url', '/courses/1');
        $this->controller->params['url']['error'] = 'access_denied';

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/cancelled/', $message['message']);
        $this->assertNull($this->controller->Session->read('oauth_canvas_state'),
            'oauth_canvas_state should be deleted on access_denied');
        $this->assertNull($this->controller->Session->read('canvas_oauth_return_url'),
            'canvas_oauth_return_url should be deleted on access_denied');
    }

    /**
     * Canvas redirected back with a state but no code — malformed callback.
     * Should show a generic authentication error and leave session state intact
     * (no token exchange attempted).
     */
    public function testCanvasOauthCallbackMissingCode()
    {
        $testApi = new TestCanvasApiComponent(1);
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        $this->controller->Session->write('oauth_canvas_state', 'st_abc');
        $this->controller->Session->write('canvas_oauth_return_url', '/courses/1');
        $this->controller->params['url']['state'] = 'st_abc';
        // no 'code' in params

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/authentication error/', $message['message']);
        $this->assertEqual('st_abc', $this->controller->Session->read('oauth_canvas_state'),
            'oauth_canvas_state should not be deleted when code is missing');
    }

    /**
     * State parameter returned by Canvas does not match what was stored in session.
     * This is the CSRF guard — the token exchange must not proceed.
     */
    public function testCanvasOauthCallbackStateMismatch()
    {
        $testApi = new TestCanvasApiComponent(1);
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        $this->controller->Session->write('oauth_canvas_state', 'st_abc');
        $this->controller->Session->write('canvas_oauth_return_url', '/courses/1');
        $this->controller->params['url']['code']  = 'canvas_code';
        $this->controller->params['url']['state'] = 'TAMPERED';

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/authentication error/', $message['message']);
        $this->assertEqual('st_abc', $this->controller->Session->read('oauth_canvas_state'),
            'oauth_canvas_state should not be deleted on state mismatch');
    }

    /**
     * No OAuth flow was ever initiated for this session (no state in session).
     * A request with a code but no matching state must be rejected — guards
     * against the null != null bypass that loose comparison allowed previously.
     */
    public function testCanvasOauthCallbackNullSessionState()
    {
        $testApi = new TestCanvasApiComponent(1);
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        // deliberately do NOT write oauth_canvas_state
        $this->controller->params['url']['code']  = 'canvas_code';
        $this->controller->params['url']['state'] = 'st_abc';

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/authentication error/', $message['message']);
    }

    /**
     * Happy path: valid code and matching state, Canvas returns an access token.
     * Session keys should be cleaned up and the user should see the success message.
     */
    public function testCanvasOauthCallbackSuccess()
    {
        $testApi = new TestCanvasApiComponent(1);
        $testApi->fakeToken = array('accessToken' => 'tok123');
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        $this->controller->Session->write('oauth_canvas_state', 'st_abc');
        $this->controller->Session->write('canvas_oauth_return_url', '/courses/1');
        $this->controller->params['url']['code']  = 'canvas_code';
        $this->controller->params['url']['state'] = 'st_abc';

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertPattern('/successfully connected/', $message['message']);
        $this->assertEqual('good', $message['element'],
            'Success flash should use the "good" element');
        $this->assertNull($this->controller->Session->read('oauth_canvas_state'),
            'oauth_canvas_state should be deleted after successful exchange');
        $this->assertNull($this->controller->Session->read('canvas_oauth_return_url'),
            'canvas_oauth_return_url should be deleted after successful exchange');
    }

    /**
     * Canvas token endpoint returned an error (e.g. code already used).
     * The specific error message from the API should be surfaced to the user.
     */
    public function testCanvasOauthCallbackTokenError()
    {
        $testApi = new TestCanvasApiComponent(1);
        $testApi->fakeToken = array('err' => 'Error: invalid_grant');
        $this->_setupCallbackController();
        $this->controller->setReturnValue('_createCanvasApi', $testApi);
        $this->controller->Session->write('oauth_canvas_state', 'st_abc');
        $this->controller->Session->write('canvas_oauth_return_url', '/courses/1');
        $this->controller->params['url']['code']  = 'canvas_code';
        $this->controller->params['url']['state'] = 'st_abc';

        $this->controller->canvasOauthCallback();

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual('Error: invalid_grant', $message['message']);
    }

}
