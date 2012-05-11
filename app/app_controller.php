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
App::import('Model', 'User');

/**
 * AppController the base controller
 *
 * @uses Controller
 * @package App
 */
class AppController extends Controller
{
    public $startpage  = 'pages';
    public $uses       = array('User', 'SysParameter');
    public $components = array('Session', 'Output', 'sysContainer',
        'userPersonalize', 'framework', 'Guard.Guard', 'Acl',
        'AccessControl', 'Email');
    public $helpers    = array('Session', 'Html', 'Js');
    public $access     = array ();
    public $actionList = array ();

    /* protected __construct() {{{ */
    /**
     * __construct constructor function
     *
     * @access protected
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /* }}} */

    /* public beforeFilter() {{{ */
    /**
     * beforeFilter function called before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->autoRedirect = false;
        // backward compatible with original ipeer hash  method
        Security::setHash('md5');
        Configure::write('Security.salt', '');

        // set default language for now
        Configure::write('Config.language', 'eng');

        // store user in the singleton for global access
        User::store($this->Auth->user());

        if ($this->Auth->isAuthorized()) {
            // check if the user has permission to access the controller/action
            User::hasPermission('controllers/'.ucwords($this->params['controller']).'/'.$this->params['action']);

            $this->_checkDatabaseVersion();
        }

        parent::beforeFilter();
    }
    /* }}} */

    /* public checkDatabaseVersion() {{{ */
    /**
     * checkDatabaseVersion
     *
     * @access public
     * @return void
     */
    public function _checkDatabaseVersion()
    {
        $dbv = $this->SysParameter->getDatabaseVersion();

        if (User::hasPermission('controllers/upgrade') &&
            Configure::read('DATABASE_VERSION') > $dbv) {
            $flashMessage  = "<span class='notice'>Your database version is older than the current version. ";
            $flashMessage .= "Please do the <a href=" . $this->webroot ."upgrade" .">upgrade</a>.</span>";
            $this->Session->setFlash($flashMessage);
        }
    }
    /* }}} */

    /* }}} */

    /* public extractModel($model,$array,$field) {{{ */
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
    public function extractModel($model,$array,$field)
    {
        $return = array();
        foreach ($array as $row) {
            array_push($return, $row[$model][$field]);
        }

        return $return;
    }
    /* }}} */

    /* protected _sendEmail($content,$subject,$from,$to, $templateName = 'default', $cc = array(),$bcc= array()) {{{ */
    /**
     * _sendEmail send email wrapper
     *
     * @param mixed $content      email body
     * @param mixed $subject      email subject
     * @param mixed $from         sender address
     * @param mixed $to           receiver address
     * @param bool  $templateName email template name
     * @param bool  $cc           cc field
     * @param bool  $bcc          bcc field
     *
     * @access protected
     * @return void
     */
    protected function _sendEmail($content,$subject,$from,$to, $templateName = 'default', $cc = array(),$bcc= array())
    {
        $smtp['port'] = $this->sysContainer->getParamByParamCode('email.port');
        $smtp['host'] = $this->sysContainer->getParamByParamCode('email.host');
        $smtp['username'] = $this->sysContainer->getParamByParamCode('email.username');
        $smtp['password'] = $this->sysContainer->getParamByParamCode('email.password');

        $this->Email->reset();

        $this->Email->smtpOptions = array(
            'port'=>$smtp['port']['parameter_value'],
            'timeout'=>'30',
            'host' => $smtp['host']['parameter_value'],
            'username'=>$smtp['username']['parameter_value'],
            'password'=>$smtp['password']['parameter_value'],
        );

        $this->Email->delivery = 'smtp';
        $this->Email->to = $to;
        $this->Email->cc = $cc;
        $this->Email->bcc = $bcc;
        $this->Email->subject = $subject;
        $this->Email->from = $from;
        $this->Email->template = $templateName;
        $this->Email->sendAs = 'both';

        return $this->Email->send($content);
    }
    /* }}} */
}
