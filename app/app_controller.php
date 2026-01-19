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

        $this->Auth->autoRedirect = false;
        // backward compatible with original ipeer hash  method
        Security::setHash('md5');
        Configure::write('Security.salt', '');

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

        // for setting up google analytics
        $trackingId = $this->SysParameter->findByParameterCode('google_analytics.tracking_id');
        $domain = $this->SysParameter->findByParameterCode('google_analytics.domain');
        $customLogo = $this->SysParameter->findByParameterCode('banner.custom_logo');
        $this->set('trackingId', $trackingId);
        $this->set('domain', $domain);
        $this->set('customLogo', $customLogo);

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
                $this->Session->setFlash(__('Your account is currently inactive.', true));
                $this->redirect('/');
                return;
            }
            // after login stuff
            $this->User->loadRoles(User::get('id'));
            
            $isStudent = User::get('role_id') == $this->User->USER_TYPE_STUDENT;

            // Only check course enrollment for students 
            if ( $isStudent ) {
                $enrolledCourses = $this->User->getEnrolledCourses(User::get('id'));
                $tutorCourses = $this->User->getTutorCourses(User::get('id'));
                
                if (empty($enrolledCourses) && empty($tutorCourses)) {
                    // Show message and logout to SAML
                    $this->Auth->logout();
                    $message = __('You do not have any course enrollment in iPeer. Please contact your instructor.', true);
                    $samlLogoutUrl = Configure::read('SAML_LOGOUT_URL');
                    $logoutUrl = !empty($samlLogoutUrl) ? $samlLogoutUrl : '/logout';
                    
                    echo "<script>alert(" . json_encode($message) . "); window.location.href=" . json_encode($logoutUrl) . ";</script>";
                    exit;
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
            $this->log('Invalid username '.$this->sessionTransferData['username'].' from session transfer.', 'debug');
            return false;
        }

        $this->log('User '.$this->sessionTransferData['username'].' is logged in with session transfer.', 'debug');

        return true;
    }
}
