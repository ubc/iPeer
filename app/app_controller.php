<?php
/**
 * Custom AppController for iPeer
 *
 * @author  Pan Luo <pan.luo@ubc.ca>
 * @license MIT http://www.opensource.org/licenses/MIT
 * @version GIT:
 *
 */
ini_set('auto_detect_line_endings', true);

uses('sanitize');
App::import('Lib', 'toolkit');
App::import('Lib', 'breadcrumb');
App::import('Lib', 'caliper');
require_once APP . 'libs/request_context.php';
use caliper\CaliperHooks;

/**
 * AppController the base controller
 *
 * @uses Controller
 * @package App
 */
class AppController extends Controller
{
    public $startpage  = 'pages';
    public $uses       = array('User', 'SysParameter', 'OauthToken');
    public $components = array('Session', 'Output',
        'userPersonalize', 'framework', 'Guard.Guard', 'Acl',
        'AccessControl', 'TemplateEmail');
    public $helpers    = array('Session', 'Html', 'Js', 'Vocabulary');
    public $access     = array ();
    public $actionList = array ();
    public $breadcrumb;
    public $validTZ;
    public $emailInterfaceEnabled = true;

    /**
     * if this request has session transfer data
     */
    public $isSessionTransfer = false;

    /**
     * session transfer data
     */
    public $sessionTransferData = array();


    /**
     * __construct constructor function
     *
     * @access protected
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->validTZ = array_flip(DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC));
    }

    /**
     * beforeFilter function called before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
        $timezone = $this->SysParameter->findByParameterCode('system.timezone');
        // default to UTC if no timezone is set
        if (!(empty($timezone) || empty($timezone['SysParameter']['parameter_value']))) {
            $timezone = $timezone['SysParameter']['parameter_value'];
            // check that the timezone is valid
            if (isset($this->validTZ[$timezone])) {
                date_default_timezone_set($timezone);
            } else {
                $this->Session->setFlash(__('An invalid timezone is provided, please edit "system.timezone"', true));
            }
        }

        static $supportEmailApplied = false;
        if (!$supportEmailApplied) {
            $supportEmail = getenv('IPEER_SUPPORT_EMAIL');
            if (!empty($supportEmail)) {
                $currentSupport = $this->SysParameter->get('display.contact_info');
                if ($currentSupport !== $supportEmail) {
                    $this->SysParameter->setValue('display.contact_info', $supportEmail);
                    $this->SysParameter->reload();
                }
            }
            $supportEmailApplied = true;
        }

        $ipeerSalt = getenv('IPEER_SECURITY_SALT');
        if ($ipeerSalt) {
            Security::setHash('sha256');
            Configure::write('Security.salt', $ipeerSalt);
        } else {
            // backward compatible with original ipeer hash method
            Security::setHash('md5');
            Configure::write('Security.salt', '');
        }

        $this->Auth->autoRedirect = false;



        $locale = $this->SysParameter->findByParameterCode('display.locale');
        // default to eng if no locale is set
        if (!(empty($locale) || empty($locale['SysParameter']['parameter_value']))) {
            $locale = $locale['SysParameter']['parameter_value'];

            // TODO: check that the locale is valid
            Configure::write('Config.language', $locale);
        } else {
            Configure::write('Config.language', 'eng');
        }

        // if we have a session transfered to us
        if ($this->_hasSessionTransferData()) {
            $this->log('Using session transfer data for login', 'info');
            if ($this->_authenticateWithSessionTransferData()) {
                if (method_exists($this, '_afterLogin')) {
                    $this->_afterLogin(false);
                }
            } else {
                $this->Session->setFlash($this->Auth->loginError, $this->Auth->flashElement, array(), 'auth');
            }
        }

        // store user in the singleton for global access
        User::store($this->Auth->user());

        RequestContext::resolveClientIp();

        if ($this->Auth->user()) {
            RequestContext::setUserId($this->Auth->user('id'));
            RequestContext::setUsername($this->Auth->user('username'));
        }

        self::logControllerAction($this);

        if (!$this->_checkCsrfReferer()) {
            return;
        }

        $this->breadcrumb = Breadcrumb::create();

        if ($this->Auth->isAuthorized()) {

            // Check if user has permission for this action
            
            // check if the user has permission to access the controller/action
            $permission = array_filter(array('controllers', ucwords($this->params['plugin']), ucwords($this->params['controller']), $this->params['action']));
            if (!User::hasPermission(join('/', $permission))) {
                $supportEmail = getenv('IPEER_SUPPORT_EMAIL');
                if (empty($supportEmail)) {
                    $supportEmail = $this->SysParameter->get('display.contact_info');
                }
                if (empty($supportEmail)) {
                    $supportEmail = 'support@your-domain.ca';
                }
                $this->Session->setFlash('Access to this page is limited to authorized users. Please contact the course shell owner if you believe you should have access, or reach out to ' . $supportEmail);
                $this->redirect('/home');
                return;
            }

            $this->_checkSystemVersion();
        }

        // check if email interface is globally disabled
        $this->emailInterfaceEnabled = !in_array(
            $this->SysParameter->get('email.disableEmailInterface', 'false'),
            array('1', 'true', 'yes')
        );
        $this->set('emailInterfaceEnabled', $this->emailInterfaceEnabled);

        // for setting up google analytics
        $trackingId = $this->SysParameter->findByParameterCode('google_analytics.tracking_id');
        $domain = $this->SysParameter->findByParameterCode('google_analytics.domain');
        $customLogo = $this->SysParameter->findByParameterCode('banner.custom_logo');
        $this->set('trackingId', $trackingId);
        $this->set('domain', $domain);
        $this->set('customLogo', $customLogo);

        $maintenanceNotice = $this->SysParameter->get('display.maintenance_notice', '');
        $this->set('maintenanceNotice', $maintenanceNotice);

        parent::beforeFilter();
    }

    /**
     * Called after the controller action is run, but before the view is rendered.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    function beforeRender() {
        CaliperHooks::app_controller_before_render($this);

        $commitHash = getenv('IPEER_COMMIT_HASH');
        if (!empty($commitHash) && User::hasPermission('functions/superadmin')) {
            $this->set('ipeerCommitHash', htmlspecialchars($commitHash));
        }

        parent::beforeRender();
    }

    /**
     * checkDatabaseVersion
     *
     * @access public
     * @return void
     */
    public function _checkSystemVersion()
    {
        $sysv = $this->SysParameter->get('system.version');

        if (User::hasPermission('controllers/upgrade') && version_compare(IPEER_VERSION, $sysv) > 0) {
            $flashMessage = "Your system version is older than the current version. ";
            $flashMessage .= "Please do the <a href=" . $this->webroot ."upgrade" .">upgrade</a>.";
            $this->Session->setFlash($flashMessage);
        }
    }


    /**
     * extractModel extract the model
     *
     * @param mixed $model model name
     * @param mixed $array model of array
     * @param mixed $field field
     *
     * @access public
     * @return void
     */
    public function _extractModel($model,$array,$field)
    {
        $return = array();
        foreach ($array as $row) {
            array_push($return, $row[$model][$field]);
        }

        return $return;
    }

    /**
     * _sendEmail send email wrapper
     *
     * @param mixed $content      email body
     * @param mixed $subject      email subject
     * @param mixed $from         sender address
     * @param mixed $toAddress    receiver address
     * @param bool  $templateName email template name
     * @param bool  $ccAddress    cc field
     * @param bool  $bcc          bcc field
     *
     * @access protected
     * @return void
     */
    protected function _sendEmail($content, $subject, $from, $toAddress, $templateName = 'default', $ccAddress = array(), $bcc= array())
    {
        $this->Email->reset();

        $smtpHost = $this->SysParameter->get('email.host');
        if (!empty($smtpHost)) {
            $smtp['port'] = $this->SysParameter->get('email.port');
            $smtp['host'] = $this->SysParameter->get('email.host');
            $smtp['username'] = $this->SysParameter->get('email.username');
            $smtp['password'] = $this->SysParameter->get('email.password');
            $smtp['timeout'] = 30;
            $this->Email->delivery = 'smtp';
            $this->Email->smtpOptions = $smtp;
        } else {
            $this->Email->delivery = 'mail';
        }

        $this->Email->to = $toAddress;
        $this->Email->cc = $ccAddress;
        $this->Email->bcc = $bcc;
        $this->Email->subject = $subject;
        $this->Email->from = ($from == null ? $this->SysParameter->get('display.contact_info') : $from);
        $this->Email->template = $templateName;
        $this->Email->sendAs = 'both';
        //$this->Email->delivery = 'debug';

        return $this->Email->send($content);
    }

    /**
     * beforeLogin callback, called every time in auth compoment if user is not
     * logged in yet
     *
     * @access public
     * @return void
     */
    public function _beforeLogin()
    {
        $this->set('loginHeader', $this->SysParameter->get('display.login.header'));
        $this->set('loginFooter', $this->SysParameter->get('display.login.footer'));
    }

    /**
     * afterLogin callback, called when logging in successfully
     *
     * @param bool $isRedirect whether redirecting
     *
     * @access public
     * @return void
     */
    public function _afterLogin($isRedirect = true)
    {
        if ($this->Auth->isAuthorized()) {
            User::getInstance($this->Auth->user());
            // deny access for inactive users
            if (User::get('record_status') == 'I') {
                $this->Auth->logout();
                $this->redirect('/login?notice=inactive');
                return;
            }

            // Clear any auth error flash message from the login redirect
            $this->Session->delete('Message.auth');

            // after login stuff
            $roles = $this->User->loadRoles(User::get('id'));

            $isStudent = array_key_exists($this->User->USER_TYPE_STUDENT, $roles);

            // Only check course enrollment for students 
            if ( $isStudent ) {
                $enrolledCourses = $this->User->getEnrolledCourses(User::get('id'));
                $tutorCourses = $this->User->getTutorCourses(User::get('id'));
                
                if (empty($enrolledCourses) && empty($tutorCourses)) {
                    $this->Auth->logout();
                    $this->redirect('/login?notice=no_enrollment');
                    return;
                }
            }

            $this->AccessControl->loadPermissions();
            $this->SysParameter->reload();
            //TODO logging!

            CaliperHooks::app_controller_after_login($this);
        }

        if (!$isRedirect) {
            return;
        }

        $redirect = $this->Auth->redirect();
        if (isset($this->params['url']['redirect'])) {
            $redirect = $this->params['url']['redirect'];
        }

        $this->log('redirecting to '.$redirect, 'debug');
        $this->redirect($redirect);
    }

    /**
     * afterLogout callback, clean up after logout
     *
     * @access public
     * @return void
     */
    function _afterLogout()
    {
        CaliperHooks::app_controller_after_logout($this);
        $this->Session->destroy();
    }

    /**
     * hasSessionTransferData
     *
     * @access public
     * @return boolean
     */
    function _hasSessionTransferData()
    {
        $params = $this->params['url'];
        if (isset($params['username']) && isset($params['timestamp']) && isset($params['token']) && isset($params['signature'])) {
            $this->isSessionTransfer = true;
            $this->sessionTransferData = $params;

            return true;
        }

        return false;
    }

    /**
     * authenticateWithSessionTransferData
     *
     * @access public
     * @return boolean
     */
    function _authenticateWithSessionTransferData()
    {
        // valid signature first
        $message = $this->sessionTransferData['username'].$this->sessionTransferData['timestamp'].$this->sessionTransferData['token'];
        $secret = $this->OauthToken->getTokenSecret($this->sessionTransferData['token']);
        $signature = base64_encode(hash_hmac('sha1', $message, $secret, true));
        if ($signature != $this->sessionTransferData['signature']) {
            $this->log('Invalid signature! Expect '.$signature.', Got '.$this->sessionTransferData['signature']);

            return false;
        }

        // find the userId by username and use it to login
        $userId = $this->User->field('id', array('username' => $this->sessionTransferData['username']));
        if (!$this->Auth->login($userId)) {
            $this->log('Invalid username '.$this->sessionTransferData['username'].' from session transfer.', 'info');
            return false;
        }

        $this->log('User '.$this->sessionTransferData['username'].' is logged in with session transfer.', 'info');

        return true;
    }

    public static function logControllerAction($controller) {
        $params = implode(', ', $controller->params['pass']);
        $rawMethod = env('REQUEST_METHOD') ?? '';
        $overrideMethod = $controller->params['form']['_method'] ?? null;
        $methodInfo = $overrideMethod !== null
            ? $rawMethod . ' (_method=' . $overrideMethod . ')'
            : $rawMethod;
        $controller->log(
            "Accessing " . $methodInfo . ' ' . $controller->params['controller'] . '::' . $controller->params['action'] . '(' . $params . ')',
            'info'
        );
    }

    /**
     * Checks the HTTP Referer header as a lightweight CSRF mitigation if
     * `IPEER_ENFORCE_REFERRER` is set to true.
     *
     * An absent Referer is treated the same as a cross-origin one (rejected).
     *
     * Covers POST, PUT, DELETE, and PATCH — including CakePHP's _method override
     * which rewrites REQUEST_METHOD from POST to the tunnelled method.
     *
     * Ideally we should use Cakephp's form helpers and generate CSRF tokens,
     * but this would require refactoring and testing a lot of forms. We would
     * also need to switch all the GET state-changing endpoints. At the time
     * of writing, we don't want to invest much more time in iPeer 3.x, so this
     * measure is a decent tradeoff.
     *
     * @return bool false if the request was rejected (caller should return immediately)
     */
    protected function _checkCsrfReferer() {
        // State-mutating endpoints (POST/PUT/DELETE/PATCH) that legitimately receive cross-origin requests
        $crossOriginAllowed = array(
            'saml/acs',
            'home/index',
            'guard/login',
        );

        // State-changing actions reachable via plain HTTP GET
        // These should be changed to POSTs if we decide to continue working on iPeer 3.x
        $getRequestsDisallowed = array(
            'users/enrol',
            'users/readd',
            'users/resetpassword',
            'users/resetpasswordwithoutemail',
            'departments/delete',
            'emailtemplates/delete',
            'faculties/delete',
            'groups/delete',
            'mixevals/delete',
            'rubrics/delete',
            'simpleevaluations/delete',
            'surveys/delete',
            'sysparameters/delete',
        );

        // Non-existent actions will result in a 404; skip CSRF checking for them.
        // Use the same predicate as the dispatcher ($this->methods excludes base
        // Controller methods, which are also not dispatchable as actions).
        $dispatchableMethods = array_flip($this->methods);
        if (!isset($dispatchableMethods[strtolower($this->params['action'])])) {
            return true;
        }

        $action = strtolower($this->params['controller'] . '/' . $this->params['action']);
        $method = strtoupper(env('REQUEST_METHOD'));

        $stateMutatingMethods = array('POST', 'PUT', 'DELETE', 'PATCH');
        if (in_array($method, $stateMutatingMethods) && !in_array($action, $crossOriginAllowed)) {
            return $this->_requireSameOriginReferer();
        }

        if ($method === 'GET' && in_array($action, $getRequestsDisallowed)) {
            return $this->_requireSameOriginReferer();
        }

        return true;
    }

    /**
     * @return array{ok: bool, receivedHost: string, expectedHost: string}
     */
    protected function _testSameOriginReferer() {
        $expectedHost = strtok(env('HTTP_HOST') ?? '', ':');
        $referer = env('HTTP_REFERER');
        if (empty($referer)) {
            return array('ok' => false, 'receivedHost' => '(none)', 'expectedHost' => $expectedHost);
        }
        $receivedHost = parse_url($referer, PHP_URL_HOST);
        return array('ok' => $receivedHost === $expectedHost, 'receivedHost' => $receivedHost, 'expectedHost' => $expectedHost);
    }

    protected function _requireSameOriginReferer() {
        $result = $this->_testSameOriginReferer();

        if ($result['ok']) {
            return true;
        }

        $action = $this->params['controller'] . '/' . $this->params['action'];

        $allowedDomainsEnv = getenv('IPEER_REFERRER_ALLOWED_DOMAINS');
        if (!empty($allowedDomainsEnv)) {
            $allowedDomains = array_map('trim', explode(',', $allowedDomainsEnv));
            if (in_array($result['receivedHost'], $allowedDomains, true)) {
                CakeLog::write(
                    'info',
                    'CSRF: cross-origin referer allowed via IPEER_REFERRER_ALLOWED_DOMAINS. ' .
                    'Action: ' . $action . ', ' .
                    'method: ' . env('REQUEST_METHOD') . ', ' .
                    'referer host: ' . $result['receivedHost']
                );
                return true;
            }
        }

        $enforce = (bool) getenv('IPEER_ENFORCE_REFERRER');
        CakeLog::write(
            'warning',
            'CSRF: referer mismatch' . ($enforce ? ' (blocked)' : ' (allowed, enforcement disabled)') .
            '. Action: ' . $action . ', ' .
            'method: ' . env('REQUEST_METHOD') . ', ' .
            'received host: ' . $result['receivedHost'] . ', expected: ' . $result['expectedHost'] . ', ' .
            'referer: ' . env('HTTP_REFERER')
        );
        if (!$enforce) {
            return true;
        }

        if (!headers_sent()) {
            header('X-iPeer-CSRF-Blocked: received=' . $result['receivedHost'] . ', expected=' . $result['expectedHost']);
        }
        $this->Session->setFlash(__(
            'iPeer could not complete your request. It looks like your request came from outside the app. ' .
            'This action must be done from within iPeer.',
            true
        ));
        $this->redirect('/home');
        return false;
    }
}
