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
require_once('XML/RPC.php');
uses('sanitize', 'neat_string');
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
		$this->pageTitle = 'Login';
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
		if($this->rdAuth->id && $this->rdAuth->role)
		{
			$redirect = 'home/index/';
			$this->redirect($redirect);
		}

		$redirect = $this->__renderLoginPage();
		//RENDER LOGIN FORM AND THEN HANDLE POST
		if (empty($this->params['data']))
		{
			//Check for CWL login auth
			$cwlErr = $this->Session->read('CWLErr');
			if ($cwlErr == 'STUDENT_INVALID_LOGIN'){
				$this->set('errmsg', 'Access Denied. <br>Student must login by using UBC CWL Authentication.<br>If you are experiencing any issues regarding iPeer, please contact the iPeer administrator at <a href="mailto:ipeer@apsc.ubc.ca">ipeer@apsc.ubc.ca</a>.');
				$this->Session->del('CWLErr');
			} else if ($cwlErr!=''){
				$this->set('errmsg', 'Access Denied. <br>You have successfully logged in using your CWL account but you do not have access to the iPeer application.<br>If you are experiencing any issues regarding iPeer, please contact the iPeer administrator at <a href="mailto:ipeer@apsc.ubc.ca">ipeer@apsc.ubc.ca</a>.');
				$this->Session->del('CWLErr');
			}

			$lastURL = $this->Session->read('URL');
			$accessErr = $this->Session->read('AccessErr');
			$message = $this->Session->read('Message');
			//$a=print_r($this->rdAuth->Session,true); echo "<pre>$a</pre>"; exit;
			if (empty($lastURL)){

				if ($accessErr=='INVALID_LOGIN'){
					$this->set('errmsg', 'Access Denied. <br>Invalid Username/Password Combination.');
					$this->Session->del('URL');
					$this->Session->del('AccessErr');
				}


			} else {


				if ($accessErr=='NO_LOGIN'){
					$this->set('errmsg', 'please log in to see: '.$lastURL);
				}
				if ($accessErr=='NO_PERMISSION'){
					$this->set('errmsg', 'Permission Denied to see: '.$lastURL);
				}
				if ($accessErr=='INVALID_USER'){
					$this->set('errmsg', 'Access Denied. <br>UBC CWL Authentication is only valid for students. <br>If you are experiencing any issues regarding the iPeer, please contact your administrator for details.');
				}
				$this->Session->del('URL');
				$this->Session->del('AccessErr');
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
		if($this->rdAuth->id && $this->rdAuth->role)
		{
			$redirect = 'home/index/';
			$this->redirect($redirect);
		}

		//$a=print_r($this->params,true); echo "<pre>$a</pre>"; exit;
		//RENDER LOGIN FORM AND THEN HANDLE POST
		if (empty($this->params['data']))
		{
			$redirect = 'login';
			$lastURL = $this->Session->read('URL');
			$accessErr = $this->Session->read('AccessErr');
			$message = $this->Session->read('Message');

			if (empty($lastURL)){
				//$this->set('errmsg', 'Session Expired.  Please log in again.');
			} else {
				if ($accessErr=='NO_LOGIN'){
					$this->set('errmsg', 'Please Login to see '.$lastURL);
					$this->Session->del('URL');
				}
				if ($accessErr=='NO_PERMISSION'){
					$this->set('errmsg', 'Permission Denied to see: '.$lastURL);
					$this->Session->del('URL');
				}
			}

			$this->render($redirect);
		}
		else
		{
			$this->Output->filter($this->params['data']);//always filter
			$this->Sanitize->cleanArray($this->params['data']);
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
				if ($this->params['data']['User']['role'] == "S" && !($this->params['data']['User']['id'] >=4553 && $this->params['data']['User']['id'] <=4583 )) {
					$this->params['data']['User']['password'] = '';
					$this->Session->write('CWLErr', 'STUDENT_INVALID_LOGIN');
					$redirect = 'loginout/login';
					$this->redirect($redirect);
				}
			}

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
				print 333;
				$this->params['data']['User']['password'] = '';
				$this->Session->write('AccessErr', 'INVALID_LOGIN');
				$redirect = '/loginout/login';
				$this->redirect($redirect);
			}

		}
	}

	function loginByCWL() {
		$this->autoRender = false;
		/**
		 * the URL of the CWL login page
		 */
		$CWLLoginURL = 'https://www.auth.cwl.ubc.ca/auth/login';

		// CWL XML-RPC interface URLs: https://www.auth.verf.cwl.ubc.ca/auth/rpc (for verification)
		//                             https://www.auth.cwl.ubc.ca/auth/rpc
		$CWLRPCURL = "https://www.auth.cwl.ubc.ca";
		$CWLRPCPath = "/auth/rpc";

		/**
		 * the name of the function being called through XML-RPC. this is
		 * prepended with 'session.' later
		 */
		//$CWLFunctionName = 'getLoginName';
		$CWLFunctionName = 'getIdentities';

		/**
		 * the application's ID/name and password as given by the CWL team
		 */
		$applicationID = 'cis_ipeer_psa';
		$applicationPassword = 'p33k4b00';

		$ticket = $_GET['ticket'];
		if ($ticket != null) {
			// now get some info about the session

			// the parameters passed to the RPC interface.  the ticket is the
			// first argument for all functions
			$params = array( new XML_RPC_Value($ticket, 'string') );

			// note that the function name is prepended with the string 'session.'
			$msg = new XML_RPC_Message("session.$CWLFunctionName", $params);

			$cli = new XML_RPC_Client($CWLRPCPath, $CWLRPCURL);
			$cli->setCredentials($applicationID, $applicationPassword);
			//print_r ($cli);
			//$cli->setDebug(1);

			$resp = $cli->send($msg);
			if (!$resp)
			{
				echo 'Communication error: ' . $cli->errstr;
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
					$this->params['data'] = $this->User->findByUsername(trim($studentNumber));

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
				echo '<b>Fault Code:</b> ' . $resp->faultCode() . "<br />\n";
				echo '<b>Fault Reason:</b> ' . $resp->faultString() . "<br />\n";
			}

		}

	}

	/**
	 * Cleans up any Session variable that need to be cleaned on logout
	 */
	function clearSession() {
		$this->Session->del('URL');
		$this->Session->del('AccessErr');
		$this->Session->del('Message');
		$this->Session->del('CWLErr');
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
		$allParameters = $this->SysParameter->findAll(null, array('id', 'parameter_code', 'parameter_value', 'parameter_type'));
		$this->sysContainer->setParamList($allParameters);

		$sysParameter = $this->sysContainer->getParamByParamCode('custom.login_control');
		//Check whether we are using the general ipeer login page
		if (isset($sysParameter['parameter_value']) && $sysParameter['parameter_value'] != 'ipeer') {

			//Setup Custom parameter
			$this->Session->write('ipeerSession.customIntegrateCWL', 1);

			//There is a custom setup for login
			$paraLogin = $this->sysContainer->getParamByParamCode('custom.login_page_pathname');
			if (isset($paraLogin['parameter_value'])) {
				$redirect = $paraLogin['parameter_value'];
			}
		} else {
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
				$errmsg .= 'Email address is required for password reset. ';
			}
			if($studentNo == '') {
				$errmsg .= 'Student number is required for password reset. ';
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
				$errmsg .= 'There is no one with that email address in the system. ';
			}
			else {
				// generate random password
				$new_password = $this->NeatString->randomPassword(6);
				$user['User']['password'] =  md5($new_password);

				if ($errmsg != '') {
					$this->set('errmsg', $errmsg);
					$this->render('forgot');
					return;
				}
				// save new md5 sum to database
				if ($this->User->save($user)) {
					// set email parameters
					$email_msg_param = $this->sysContainer->getParamByParamCode('system.password_reset_email');
					$email_msg = $email_msg_param['parameter_value'];
					$from_param = $this->sysContainer->getParamByParamCode('system.admin_email');
					$from = $from_param['parameter_value'];
					$subject_param = $this->sysContainer->getParamByParamCode('system.password_reset_emailsubject');
					$subject = $subject_param['parameter_value'];
					$to = $user['User']['email'];
					$fullname = $user['User']['first_name'] . " " . $user['User']['last_name'];
					$email_msg = ereg_replace("<user>", $fullname, $email_msg);
					$email_msg = ereg_replace("<newpassword>", $new_password, $email_msg);
					$email_msg = ereg_replace("<br>", "\n", $email_msg);

					// send email to user
					$success = $this->_sendEmail( $to, $from, $subject, $email_msg );

					if($success) {
						$this->set('message', 'Password reset request sent.');
						$this->set('student_no', $studentNo);
						$this->set('email', $email);
						$this->render('login');
						return;
					}
					else {
						$errmsg .= 'There was a problem in sending email. Please contact your iPeer administrator. ';
					}
				}
				else {
					$errmsg .= 'There was a problem resetting your password. Please contact your iPeer administrator. ';
				}
			}
		}

		$this->set('errmsg', $errmsg);
		$this->render('forgot');
	}

	function _sendEmail($to='', $from='', $subject='', $body='' ) {
		$result = false;
		$role = $this->rdAuth->role;

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
