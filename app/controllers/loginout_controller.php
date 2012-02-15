<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/22 17:31:26 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Users
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses('sanitize');
App::import('Lib', 'neat_string');
class LoginoutController extends AppController
{
	/**
	 * This controller does not use a model
	 *
	 * @var $uses
	 */
	var $uses =  array('User','SysFunction', 'SysParameter', 'Course');
	var $Sanitize;
	var $components = array('rdAuth','Output','sysContainer','userPersonalize');
	var $NeatString;

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->set('title_for_layout', __('Login', true));
		$this->NeatString = new NeatString;
		parent::__construct();
	}

	function login() {
		$this->autoRender = false;
		// Get iPeer Admin's email address
		$admin_email = $this->sysContainer->getParamByParamCode('system.admin_email');
		$admin_email = $admin_email['parameter_value'];
		$this->set('admin_email', $admin_email);

		//RENDER VIEW IF USER IS LOGGED IN
		if($this->Auth->user('id') && $this->Auth->user('role'))
		{
			$redirect = 'home/index/';
			$this->redirect($redirect);
			exit;
		}

		$redirect = $this->__renderLoginPage();
		//RENDER LOGIN FORM AND THEN HANDLE POST
		if (empty($this->params['data']))
		{
			//Check for CWL login auth
			$cwlErr = $this->Session->read('CWLErr');
			if ($cwlErr == 'STUDENT_INVALID_LOGIN'){
				$this->set('errmsg', __('Access Denied. <br>Student must login by using UBC CWL Authentication.<br>If you are experiencing any issues regarding iPeer, please contact the iPeer administrator at <a href="mailto:ipeer.support@ubc.ca">ipeer.support@ubc.ca</a>.', true));
				$this->Session->delete('CWLErr');
			} else if ($cwlErr!=''){
				$this->set('errmsg', __('Access Denied. <br>You have successfully logged in using your CWL account but you do not have access to the iPeer application.<br>If you are experiencing any issues regarding iPeer, please contact the iPeer administrator at <a href="mailto:ipeer.support@ubc.ca">ipeer.support@ubc.ca</a>.', true));
				$this->Session->delete('CWLErr');
			}

			$lastURL = $this->Session->read('URL');
			$accessErr = $this->Session->read('AccessErr');
			$message = $this->Session->read('Message');
			//$a=print_r($this->rdAuth->Session,true); echo "<pre>$a</pre>"; exit;
			if (empty($lastURL)){
				if ($accessErr=='INVALID_LOGIN'){
					$this->set('errmsg', __('Access Denied. <br>Invalid Username/Password Combination.', true));
					$this->Session->delete('URL');
					$this->Session->delete('AccessErr');
				}
			} else {
				if ($accessErr=='NO_LOGIN'){
					$this->set('errmsg', __('please log in to see: ', true).$lastURL);
				}
				if ($accessErr=='NO_PERMISSION'){
					$this->set('errmsg', __('Permission Denied to see: ', true).$lastURL);
				}
				if ($accessErr=='INVALID_USER'){
					$this->set('errmsg', __('Access Denied. <br>UBC CWL Authentication is only valid for students. <br>If you are experiencing any issues regarding the iPeer, please contact your administrator for details.', true));
				}
				$this->Session->delete('URL');
				$this->Session->delete('AccessErr');
			}

			$this->render($redirect);
		}

	}

	function loginByDefault() {
		$this->autoRender = false;


		// Get iPeer Admin's email address
		$admin_email = $this->sysContainer->getParamByParamCode('system.admin_email');
		$admin_email = $admin_email['parameter_value'];
		$this->set('admin_email', $admin_email);

		//RENDER VIEW IF USER IS LOGGED IN
		if($this->Auth->user('id') && $this->Auth->user('role')) {
			$redirect = 'home/index/';
			$this->redirect($redirect);
			exit;
		}


		//$a=print_r($this->params,true); echo "<pre>$a</pre>"; exit;
		//RENDER LOGIN FORM AND THEN HANDLE POST
		if (empty($this->params['data'])) {
			$redirect = 'login';
			$lastURL = $this->Session->read('URL');
			$accessErr = $this->Session->read('AccessErr');
			$message = $this->Session->read('Message');

			if (empty($lastURL)) {
				//$this->set('errmsg', 'Session Expired.  Please log in again.');
			} else {
				if ($accessErr=='NO_LOGIN'){
					$this->set('errmsg', __('Please Login to see ', true).$lastURL);
					$this->Session->delete('URL');
				}
				if ($accessErr=='NO_PERMISSION'){
					$this->set('errmsg', __('Permission Denied to see: ', true).$lastURL);
					$this->Session->delete('URL');
				}
			}

			$this->render($redirect);
		} else {
			$this->Output->filter($this->params['data']);//always filter
			$this->Sanitize->clean($this->params['data']);
			$this->User->recursive = 0;
			$this->params['data']['User']['username'] = $this->Sanitize->paranoid($this->params['data']['User']['username'],array('.','_','-'));
			$this->params['data']['User']['password'] = md5(trim($this->params['data']['User']['password']));
			$this->params['data'] = $this->User->findUser($this->params['data']['User']['username'], $this->params['data']['User']['password']);
			//Check for CWL integration: Student cannot login generally
			if (isset($this->rdAuth->customIntegrateCWL) && $this->rdAuth->customIntegrateCWL) {

				/*

				Code below:

				&& !($this->params['data']['User']['id'] >=4553 && $this->params['data']['User']['id'] <=4583 )

				is a hard coding for Political Science 101-051 upon the request of Rosalind Warner

				*/
				if ($this->params['data']['User']['role'] == "S" && $this->params['data']['User']['username'] != '00000000') {
					$this->params['data']['User']['password'] = '';
					$this->Session->write('CWLErr', 'STUDENT_INVALID_LOGIN');
					$redirect = 'loginout/login';
					$this->redirect($redirect);
					exit;
				}
			}

			if ($this->params['data']['User']['id']) {
				//sets up the session vars
				$this->rdAuth->setFromData($this->params['data']['User']);


				//sets up the system container for accessible functions
				$accessFunction = $this->SysFunction->getAllAccessibleFunction($this->params['data']['User']['role']);
				$accessController = $this->SysFunction->getTopAccessibleFunction($this->params['data']['User']['role']);
				$this->sysContainer->setAccessFunctionList($accessFunction);
				$this->sysContainer->setActionList($accessController);

				//setup my accessible courses
				$myCourses = $this->Course->findAccessibleCoursesList($this->params['data']['User']);
				$this->sysContainer->setMyCourseList($myCourses);

				//clear up the data parameters
				$this->params['data'] = array();
				$redirect = '/home/index/';
				$this->redirect($redirect);
				exit;

			} else {
				$this->params['data']['User']['password'] = '';
				$this->Session->write('AccessErr', 'INVALID_LOGIN');

				$redirect = '/loginout/login';
				$this->redirect($redirect);
				exit;
			}
		}
	}

	function loginByCWL() {
    require_once('XML/RPC.php');
    global $CWL;
		$this->autoRender = false;

		$ticket = $_GET['ticket'];
		if ($ticket != null) {
			// now get some info about the session

			// the parameters passed to the RPC interface.  the ticket is the
			// first argument for all functions
			$params = array( new XML_RPC_Value($ticket, 'string') );

			// note that the function name is prepended with the string 'session.'
			$msg = new XML_RPC_Message("session.".$CWL['FunctionName'], $params);

			$cli = new XML_RPC_Client($CWL['RPCPath'], $CWL['RPCURL']);
			$cli->setCredentials($CWL['applicationID'], $CWL['applicationPassword']);
			//print_r ($cli);
			//$cli->setDebug(1);

			$resp = $cli->send($msg);
			if (!$resp)
			{
				echo __('Communication error: ', true) . $cli->errstr;
				exit;
			}

			// print the raw response data

			//echo "<b>Raw Response:</b><br /><pre>";
			//print_r($resp);
			//echo "</pre>";

			if (!$resp->faultCode())
			{
				// an encoded response value
				$val = $resp->value();

				// the actual data we requested
				$data = XML_RPC_decode($val);

				//echo "<b>Response Data:</b><br /><pre>";
				//print_r($data);
				//echo "</pre>";
				if (!empty($data['student_number'])||!empty($data['guest_id'])) {
					$studentNumber = empty($data['student_number'])? $data['guest_id']:$data['student_number'];

					//Check is this CWL login student able to use iPeer
					$this->params['data'] = $this->User->find("username = '".trim($studentNumber)."'");

					if ($this->params['data']['User']['id'])
					{
						//sets up the session vars
						$this->rdAuth->setFromData($this->params['data']['User']);

						//sets up the system container for accessible functions
						$accessFunction = $this->SysFunction->getAllAccessibleFunction($this->params['data']['User']['role']);
						$accessController = $this->SysFunction->getTopAccessibleFunction($this->params['data']['User']['role']);
						$this->sysContainer->setAccessFunctionList($accessFunction);
						$this->sysContainer->setActionList($accessController);

						//setup my accessible courses
						$myCourses = $this->Course->findAccessibleCoursesList($this->params['data']['User']);
						$this->sysContainer->setMyCourseList($myCourses);

						//clear up the data parameters
						$this->params['data'] = array();
						$redirect = '/home/index/';
						$this->redirect($redirect);
					}
					else
					{
						$this->Session->write('CWLErr', 'NO_PERMISSION');
						$redirect = 'loginout/login';
						$this->redirect($redirect);
					}
				} else {
					$this->Session->write('CWLErr', 'INVALID_USER');
					$redirect = 'loginout/login';
					$this->redirect($redirect);
				}

			}
			else
			{
				// error
				echo __('<b>Fault Code:</b> ', true) . $resp->faultCode() . "<br />\n";
				echo __('<b>Fault Reason:</b> ', true) . $resp->faultString() . "<br />\n";
			}

		}

	}

	/**
	 * Clean out the entire session
	 */
	function clearSession() {
        foreach ($this->Session->read() as $key => $value) {
            $this->Session->delete($key);
        }
	}

	/**
	 * Function to logout the user.
	 *
	 */
	function logout()
	{
		$this->autoRender = false;
		$this->rdAuth->logout();
		$this->clearSession();
		$redirect = 'loginout/login';
		$this->redirect($redirect);
	}

	function __renderLoginPage()
	{
		$redirect = 'login';
		//sets up the system parameters to system contrainer
		$allParameters = $this->SysParameter->find('all', array('id', 'parameter_code', 'parameter_value', 'parameter_type'));
		$this->sysContainer->setParamList($allParameters);

		$sysParameter = $this->sysContainer->getParamByParamCode('custom.login_control');
		//Check whether we are using the general ipeer login page
		if (isset($sysParameter['parameter_value']) && $sysParameter['parameter_value'] != 'ipeer') {
      global $CWL;
      $this->set('CWL', $CWL);

			//Setup Custom parameter
			$this->Session->write('ipeerSession.customIntegrateCWL', 1);

			//There is a custom setup for login
			$paraLogin = $this->sysContainer->getParamByParamCode('custom.login_page_pathname');
			if (isset($paraLogin['parameter_value'])) {
				$redirect = $paraLogin['parameter_value'];
			}
		} else {
      //Setup Custom parameter
      $this->Session->write('ipeerSession.customIntegrateCWL', 0);
			$redirect = 'login';
		}
		return $redirect;

	}

	function forgot()
	{
		$this->autoRender = false;

		$email = isset($this->params['form']['email']) ? trim($this->params['form']['email']):null;
		$studentNo = isset($this->params['form']['student_no']) ? trim($this->params['form']['student_no']):null;
		$user_id = '';
		$errmsg = '';

		$this->set('student_no', $studentNo);
		$this->set('email', $email);


		if(isset($email) && isset($studentNo))
		{

			if($email == '') {
				$errmsg .= __('Email address is required for password reset. ', true);
			}
			if($studentNo == '') {
				$errmsg .= __('Student number is required for password reset. ', true);
			}
			if ($errmsg != '') {
				$this->set('errmsg', $errmsg);
				$this->render('forgot');
				return;
			}

			$user = $this->User->findUserByEmailAndStudentNo($email, $studentNo);

			// TODO: sanitize data
			// TODO: check if valid email

			// check if there is a user with that email
			if( empty($user)) {
				$errmsg .= __('There is no one with that email address in the system. ', true);
			}
			else {
				// generate random password
				$new_password = $this->NeatString->randomPassword(6);
				$user['User']['password'] =  $new_password;

				if ($errmsg != '') {
					$this->set('errmsg', $errmsg);
					$this->render('forgot');
					return;
				}
				// save new md5 sum to database
				if ($this->User->save($user)) {
					// set email parameters
					$email_msg_param = $this->sysContainer->getParamByParamCode('system.password_reset_mail');
					$email_msg = $email_msg_param['parameter_value'];
					$from_param = $this->sysContainer->getParamByParamCode('system.admin_email');
					$from = $from_param['parameter_value'];
					$subject_param = $this->sysContainer->getParamByParamCode('system.password_reset_emailsubject');
					$subject = $subject_param['parameter_value'];
					$to = $user['User']['email'];
					$fullname = $user['User']['first_name'] . " " . $user['User']['last_name'];
					$email_msg = @ereg_replace("<user>", $fullname, $email_msg);
					$email_msg = @ereg_replace("<newpassword>", $new_password, $email_msg);
					$email_msg = @ereg_replace("<br>", "\n", $email_msg);

					// send email to user
					$success = $this->_sendEmail( $to, $from, $subject, $email_msg );

					if($success) {
						$this->set('message', __('Password reset request sent.', true));
						$this->set('student_no', $studentNo);
						$this->set('email', $email);
						$this->redirect('loginout/login');
						return;
					}
					else {
						$errmsg .= __('There was a problem in sending email. Please contact your iPeer administrator. ', true);
					}
				}
				else {
					$errmsg .= __('There was a problem resetting your password. Please contact your iPeer administrator. ', true);
				}
			}
		}

		$this->set('errmsg', $errmsg);
		$this->render('forgot');
	}

	function _sendEmail($to='', $from='', $subject='', $body='' ) {
		$result = false;
		$role = $this->Auth->user('role');

		$headers = "Content-Transfer-Encoding: quoted-printable\n" .
               "From: $from\n" .
               "Return-Path: $from\n" .
               "CC:\n" .
               "BCC:\n";

		$result = mail($to, $subject, $body, $headers);
		return $result;
	}
}

?>
