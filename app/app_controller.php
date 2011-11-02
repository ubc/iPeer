<?php
/*
 * Custom AppController for iPeer
 *
 * @author
 * @version     0.10.6.1865
 * @license		OPPL
 *
 */
ini_set('auto_detect_line_endings', true);

uses('sanitize');
App::import('Lib', 'toolkit');
App::import('Model', 'User');

class AppController extends Controller  {
  var $startpage = 'pages';
  var $components = array('Session', 'Output', 'sysContainer', 'userPersonalize', 'framework', 'Guard.Guard', 'Acl', 'AccessControl', 'Email');
  var $helpers = array('Session', 'Html', 'Js');
  var $access = array ();
  var $actionList = array ();

  function __construct() {
    parent::__construct();
  }

  function beforeFilter() {
    // backward compatible with original ipeer hash  method
    Security::setHash('md5');
    Configure::write('Security.salt', '');
    
    User::store($this->Auth->user());

    if($this->Auth->isAuthorized()) {
  //    $this->AccessControl->check('controllers/'.ucwords($this->params['controller']).'/'.$this->params['action']);

      $this->checkAccess();
//      $this->checkDatabaseVersion();

      // pass user variable to view
      // Replace with User::get() function, can be used anywhere
//      $user_array = $this->Auth->user();
//      $this->set('user', $user_array['User']);
    }

    parent::beforeFilter();
  }

  function checkDatabaseVersion()
  {
    $dbv = $this->sysContainer->getParamByParamCode('database.version', array('parameter_value' => 0));
    if('A' == $this->Auth->user('role') && Configure::read('DATABASE_VERSION') > $dbv['parameter_value']) {
      $flashMessage  = "<span class='notice'>Your database version is older than the current version. ";
      $flashMessage .= "Please do the <a href=" . $this->webroot ."upgrade" .">upgrade</a>.</span>";
      $this->Session->setFlash($flashMessage);
    }
  }

  function __setAccess()
	{
		$access = $this->sysContainer->getAccessFunctionList();
		if (!empty($access)){
			$this->set('access', $access);
		}
	}

	function __setActions()
	{
		$actionList = $this->sysContainer->getActionList();
		if (!empty($actionList)){
			$this->set('action', $actionList);
		}
	}

	function __setCourses()
	{
		$coursesList = $this->sysContainer->getMyCourseList();

		if (!empty($coursesList)){
			$this->set('coursesList', $coursesList);
		}
	}

	function extractModel($model,$array,$field)
	{
		$return = array();
		foreach ($array as $row)
      array_push($return,$row[$model][$field]);
		return $return;
	}

	function checkAccess()
	{
		//$this->rdAuth->loadFromSession();
		/*if($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession')) {
			$this->Auth->id = $this->Session->read('ipeerSession.id');
			$this->username = $this->Session->read('ipeerSession.username');
			$this->fullname = $this->Session->read('ipeerSession.fullname');
			$this->role = $this->Session->read('ipeerSession.role');
			$this->email = $this->Session->read('ipeerSession.email');
			$this->customIntegrateCWL = $this->Session->read('ipeerSession.customIntegrateCWL');
			$this->courseId = $this->Session->read('ipeerSession.courseId');
		}*/

    // Used when error messasges are displayed, just let cake display them.
    // No controller -> no security risk.
    if (empty($this->params)) {
      return true;
    }

		//set access function list
		$this->__setAccess();
		$this->__setActions();
		$this->__setCourses();

		// Enable for debug output
		//$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>";

		//Save the current URL in session
		$pass = (!empty($this->params['pass'])) ? join('/',$this->params['pass']) : null;

    $URL = $this->params['controller'].'/'.$this->params['action'].'/'.$pass;
		//$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>";
		//check if user not logined
		if ($this->params['controller']=='' || $this->params['controller']=="loginout" || $this->params['controller']=="install")
		{
			//$this->set('rdAuth',$this->rdAuth);

		}/* else {
      //Check whether the user is current login yet
      $role = $this->Auth->user('role');
      if (!isset($role)){
        $this->Session->write('URL', $URL);
        $this->Session->write('AccessErr', 'NO_LOGIN');
        $this->redirect(array('controller' => 'Users',
                              'action'     => 'login'));
        exit;
      }

      //check permission
      $functions = $this->sysContainer->getActionList();

      $url = $this->params['url']['url'];
      // Cut a trailing shash in url if it exists
      if ($url[strlen($url) - 1] == "/") {
        $url = substr($url, 0, (-1) );
      }

      $allowedExplicitly = false;
      $allowedByEntry = "";
      // First, check that this URL has been explicitly specified in Sys functions table.
      foreach ($functions as $func) {
        if ($func['url_link'] === $url) {
          $allowedExplicitly = true;
          $allowedByEntry = $url;
          break;
        }
      }

      // If not allower explicitly, check in allowed by controller
      $allowedByControllerEntry = false;

      if (!$allowedExplicitly) {
        foreach ($functions as $func) {
          if ($func['controller_name'] === $this->params['controller']) {
            $allowedByControllerEntry = true;
            $allowedByEntry = $this->params['controller'];
            break;
          }
        }
      }


      // Debug output: Display how this page was allowed.
      if ($allowedExplicitly){
        $this->set("allowedBy", "<span style='color:green'>Allowed Explicitly by <u>$allowedByEntry</u> entry in SysFunctions. </span>");
      } else if ($allowedByControllerEntry) {
        $this->set("allowedBy", "<span style='color:darkblue'>Allowed Implicitly by a controller <u>$allowedByEntry</u> entry in SysFunctions. </span>");
      }

      // Rdirect the user away if they have no permission to render this page.
      if (!$allowedExplicitly && !$allowedByControllerEntry) {
        $this->redirect('home/index');
        exit;
      }

      //render the authorized controller
      $this->set('rdAuth',$this->rdAuth);
      $this->set('sysContainer', $this->sysContainer);
      $this->set('Output', $this->Output);

      $this->Session->delete('URL');
    }*/
    return true;
  }

  function _sendEmail($content,$subject,$from,$to, $templateName = 'default', $cc = array(),$bcc= array()) {
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
}
?>
