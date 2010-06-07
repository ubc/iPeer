<?php
/* SVN FILE: $Id: users_controller.php,v 1.35 2006/08/22 17:31:26 davychiu Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.35 $
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
uses('neat_string');
class UsersController extends AppController
{
	var $name = 'Users';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
	var $uses = array('User', 'UserEnrol','Personalize', 'Course','SysParameter');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->NeatString = new NeatString;
		$this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
		$this->pageTitle = 'Users';
		parent::__construct();
	}

    // =-=-=-==-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=
    // User privilege level functions

    // Get the privilege level for a user ID or a user type.
    function getPrivilegeLevel($user=null) {
        // For no parameters passed, get the privilege level of this user.
        if (empty($user)) {
            return $this->getPrivilegeLevel($this->rdAuth->role);
        } else if (is_numeric($user)) {
        // If $user is a numberic user ID, look it up in the database.
            $data = $this->User->findById($user);
            return $data ? $this->getPrivilegeLevel($data['User']['role']) : 0;
        } else {
            // Or if this is a string, look up the user type, and return privilege
            switch (strtolower($user)) {
                case "s" : return 200;
                case "i" : return 600;
                case "a" : return 1200;
                default : return 0;
            }
        }
    }

    function studentPrivilegeLevel() {
        return $this->getPrivilegeLevel('s');
    }

    function priviligeError() {
        $this->redirect('home/index');
        exit;
    }

	function index() {

        // Make sure this is not a student
        if ($this->getPrivilegeLevel() <= $this->studentPrivilegeLevel()) {
           priviligeError();
        }

		if (!empty($this->rdAuth->courseId))
		{
			$this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Students';
			$courseId = $this->rdAuth->courseId;
		} else {
			$courseId = -1;
		}
		//Setup User Type Display Option
		isset($this->params['form']['display_user_type'])? $displayUserType = $this->params['form']['display_user_type'] : $displayUserType = 'S';


		$this->set('displayUserType', $displayUserType);

		$courseList = $this->sysContainer->getMyCourseList();
		$this->set('courseList', $courseList);

		$queryAttributes = $this->getQueryAttribute($displayUserType, $courseId);
		$fields = $queryAttributes['fields'];
		$condition = $queryAttributes['condition'];
		$joinTable = $queryAttributes['joinTable'];

		$paging['style'] = 'ajax';
		$paging['link'] = '/users/search/?show='.$this->show.'&display_user_type='.$displayUserType.'&course_id='.$courseId.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

		$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
		$this->userPersonalize->setPersonalizeList($personalizeData);
		if ($personalizeData && $this->userPersonalize->inPersonalizeList('User.ListMenu.Limit.Show')) {
			$this->show = $this->userPersonalize->getPersonalizeValue('User.ListMenu.Limit.Show');
			$this->set('userPersonalize', $this->userPersonalize);
		} else {
			$this->show = '10';
			$this->update($attributeCode = 'User.ListMenu.Limit.Show',$attributeValue = $this->show);
		}

		$data = $this->User->findAll($condition, $fields, $this->order, $this->show, $this->page, null, $joinTable);
		$paging['count'] = $this->User->findCount($condition, 0, $joinTable);
		$paging['show'] = array('10','25','50','all');
		$paging['page'] = $this->page;
		$paging['limit'] = $this->show;
		$paging['direction'] = $this->direction;

		$this->set('paging',$paging);
		$this->set('data',$data);
	}

	function view($id) {
        // Make sure this is not a student
        if ($this->getPrivilegeLevel() <= $this->studentPrivilegeLevel()) {
           priviligeError();
        }

        if (is_numeric($id)) {
            // Make sure that the privileges of the asking user is at least as high
            //  as the priviliges of the user being viewed

            if ($this->getPrivilegeLevel() >= $this->getPrivilegeLevel($id)) {
                $this->set('data', $this->User->read());
            } else {
                $this->priviligeError();
            }
        }
	}

	function add($userType='') {

		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$this->autoRender = false;
		if (!empty($this->rdAuth->courseId))
		{
			$this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Students';
		}

		//List Add Page
		if (empty($this->params['data'])) {

			//check and set user type
			if (empty($this->params['data']['User']['role'])) {
				$this->params['data']['User']['role'] = $userType;
			}

			$courseList = $this->sysContainer->getMyCourseList();
			$this->set('courseList', $courseList);

			//Get render page according to the user type
			$renderPage = $this->__getRenderPage($userType);
			$this->render($renderPage);
		}
		else {
			//General password
			$password = $this->NeatString->randomPassword(6);//custom method in the User model
			$this->params['data']['User']['password'] =  md5($password);
			if (empty($this->params['data']['User']['username'])) $this->params['data']['User']['username'] = $this->params['form']['newuser'];

			if ($this->params['data']['User']['role'] == 'S')
			{
				$this->params['data']['User']['student_no'] = $this->params['data']['User']['username'];
			}

			$this->Output->filter($this->params['data']);//always filter

			//Save Data
			if ($this->User->save($this->params['data'])) {

				//Save enrol record
				if (isset($this->params['form']['course_id']) && $this->params['form']['course_id'] > 0)
				{
					$userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
					$userEnrol['UserEnrol']['user_id'] = $this->User->id;

					$this->UserEnrol->save($userEnrol);
				}

				$this->set('tmpPassword',$password);
				$this->set('data', $this->User->read());
				$this->set('userRole', $this->params['data']['User']['role']);
				$this->set('courseId', $this->rdAuth->courseId);

				//Render to view page to display saved data
				$this->render('userSummary');
			}
			//Found error
			else {
				$this->set('data', $this->params['data']);

				//Validate the error why the User->save() method returned false
				$this->validateErrors($this->User);
				$this->set('errmsg', $this->User->errorMessage);
				$this->set('courseId', $this->rdAuth->courseId);

				//Get render page according to the user type
				$renderPage = $this->__getRenderPage($this->params['data']['User']['role']);
				$this->render($renderPage);

			}//end if
		}
	}


	//private helper function
	function __getRenderPage ($userType='') {
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$renderPage = '';

		//render user add page based on user type
		switch ($userType) {
			case $this->User->USER_TYPE_ADMIN:
				if (!empty($this->rdAuth->courseId)) {
					$this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Admins';
				}
				$renderPage = 'addAdmin';
				break;

			case $this->User->USER_TYPE_INSTRUCTOR: {
				if (!empty($this->rdAuth->courseId)) {
					$this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Instructors';
				}
				$renderPage = 'addInstructor';
		  		break;
			}

			case $this->User->USER_TYPE_STUDENT:
				$renderPagqe = 'addStudent';
		  break;

			case $this->User->USER_TYPE_TA:
		  //Implement Later
		  break;
		}
		return $renderPage;
	}

	function edit($id=null)
	{
		//Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);

		$enrolled_courses = $this->Course->findRegisteredCoursesList($id);
		$course_count = $this->Course->findNonRegisteredCoursesCount($id);
		$all_courses = $this->Course->findNonRegisteredCoursesList($id);

		$this->set('all_courses', $all_courses);
		$this->set('enrolled_courses', $enrolled_courses);
		$this->set('course_count', $course_count[0][0]['total']);
		$this->set('user_id', $id);

		if (empty($this->params['data']))
		{
			$this->User->setId($id);
			$this->params['data'] = $this->User->read();
			$this->render();
		}
		else
		{
			$this->Output->filter($this->params['data']);//always filter

			if ( $this->User->save($this->params['data']))
			{

				$this->UserEnrol->insertCourses($this->User->id, $this->params['form']);

				//Render to view page to display saved data
				//TODO: Display list of users after import
				$this->set('data', $this->User->read());
				$this->set('userRole', $this->params['data']['User']['role']);
				$this->render('userSummary');
			}
			else
			{
				$this->Output->br2nl($this->params['data']);
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}

	function editProfile()
	{
		$id = $this->rdAuth->id;
		//Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);

		if (empty($this->params['data']))
		{
			$this->User->setId($id);
			$this->params['data'] = $this->User->read();
			$this->set('viewPage', 'false');
			$this->render();
		}
		else
		{
			if (!empty($this->params['data']['User']['password']))
			{
				//Update password
				$password = $this->params['data']['User']['password'];//custom method in the User model
				$this->params['data']['User']['password'] =  md5($password);
			} else {
				unset($this->params['data']['User']['password']);
			}
			$this->Output->filter($this->params['data']);//always filter

			if ( $this->User->save($this->params['data']))
			{
				//Render to view page to display saved data
				//TODO: Display list of users after import
				$user = $this->User->read();
				$this->set('data', $user);
				$this->set('viewPage', 'true');
				$this->set('message', 'Your Profile Updated Successfully.');
				//Setup Custom parameter
				$this->rdAuth->setFromData($user['User']);
				//$this->render('editProfile');
			}
			else
			{
				$this->Output->br2nl($this->params['data']);
				$this->set('data', $this->params['data']);
				$this->set('viewPage', 'false');
				$this->render();
			}
		}
	}

	function delete($id = null)
	{
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		isset($this->params['form']['display_user_type'])? $displayUserType = $this->params['form']['display_user_type'] : $displayUserType = 'S';
		$this->set('displayUserType', $displayUserType);
		if (isset($this->params['form']['id']))
		{
			$id = intval(substr($this->params['form']['id'], 5));
		}   //end if
		if ($this->User->del($id)) {
			$this->set('message', 'Record deletion successful.');
			$this->index();
			$this->render('index');
		}
		else {
			$this->set('message', 'Record deletion failed.');
			$this->index();
			$this->render('index');
		}
	}

	function search()
	{
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$this->layout = 'ajax';
		if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
			$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
			if ($personalizeData) {
				$this->userPersonalize->setPersonalizeList($personalizeData);
				$this->show = $this->userPersonalize->getPersonalizeValue('User.ListMenu.Limit.Show');
				$this->set('userPersonalize', $this->userPersonalize);
			}
		}
		$condition = "";
		isset($this->params['form']['display_user_type'])? $displayUserType = $this->params['form']['display_user_type'] : $displayUserType = $_GET['display_user_type'];

		//$courseId = $this->rdAuth->courseId;
		isset( $this->params['form']['course_id'] )? $courseId = $this->params['form']['course_id'] : $courseId = $_GET['course_id'];
		$queryAttributes = $this->getQueryAttribute($displayUserType, $courseId);
		$fields = $queryAttributes['fields'];
		$condition = $queryAttributes['condition'];
		$joinTable = $queryAttributes['joinTable'];
		if (!empty($this->params['form']['livesearch']) && !empty($this->params['form']['select']))
		{
			$pagination->loadingId = 'loading';
			//parse the parameters
			$searchField=$this->params['form']['select'];
			$searchValue=trim($this->params['form']['livesearch']);
			if (!empty($displayUserType) )
			{
				$condition .= " AND ";
			}
			$condition .= $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
		}

		$this->update($attributeCode = 'User.ListMenu.Limit.Show',$attributeValue = $this->show);
		$this->set('conditions',$condition);
		$this->set('fields',$fields);
		$this->set('joinTable',$joinTable);
		$this->set('displayUserType',$displayUserType);
		$this->set('courseId',$courseId);

	}

	function checkDuplicateName($role='')
	{
		$this->layout = 'ajax';
		$this->set('role', $role);
		$this->render('checkDuplicateName');

	}

	function resetPassword($userId='', $render=true)
	{
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		//General password
		$password = $this->NeatString->randomPassword(6);//custom method in the User model
		$this->params['data']['User']['password'] =  md5($password);
		$this->params['data']['User']['id'] =  $userId;
		//Save Data
		if ($this->User->save($this->params['data'])) {
			$message = "Password successfully reset. ";
			$this->User->setId($userId);
			$user = $this->User->read();

			// set email parameters
			$email_msg_param = $this->sysContainer->getParamByParamCode('system.password_reset_mail');
			$email_msg = $email_msg_param['parameter_value'];
			$from_param = $this->sysContainer->getParamByParamCode('system.admin_email');
			$from = $from_param['parameter_value'];
			$subject_param = $this->sysContainer->getParamByParamCode('system.password_reset_emailsubject');
			$subject = $subject_param['parameter_value'];
			$to = $user['User']['email'];
			$fullname = $user['User']['first_name'] . " " . $user['User']['last_name'];
			$email_msg = ereg_replace("<user>", $fullname, $email_msg);
			$email_msg = ereg_replace("<newpassword>", $password, $email_msg);

			// send email to user
			$success = $this->sendEmail( $to, $from, $subject, $email_msg );

			if ( $success ) {
				$message .= "Email has been sent. ";
				$this->set('message', $message);
			}
			else {
				if(!isset($to) || strlen($to) < 1) {
					$message .= 'No destination email address. ';
				}
				$message .= "Email didn't get sent.";
				$this->set('message', $message);
			}

			//Render to view page to display saved data
			//TODO: Allow to enter email and forward the password reset message to the user
			$this->set('tmpPassword',$password);
			$this->set('userRole', $user['User']['role']);
			$this->set('data', $user);
			$this->render('userSummary');

		}
		//Found error
		else {
			$this->set('data', $this->params['data']);

			//Validate the error why the User->save() method returned false
			$this->validateErrors($this->User);
			$this->set('errmsg', $this->User->errorMessage);

			//Get render page according to the user type
			$renderPage = $this->__getRenderPage($this->params['data']['User']['role']);
			$this->render($renderPage);

		}//end if

	}

	function import() {
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$this->autoRender = false;
		$this->rdAuth->courseId = $this->params['form']['course_id'];
		$filename = $this->params['form']['file']['name'];
		$tmpFile = $this->params['form']['file']['tmp_name'];

		//$uploadDir = $this->sysContainer->getParamByParamCode('system.upload_dir');
		$uploadDir="/var/www/ipeer.apsc.ubc.ca/htdocs/prod/app/uploads/";
		$uploadFile = $uploadDir.$filename;
		//check for blank value
		if (trim($filename) == "") {
			$courseList = $this->sysContainer->getMyCourseList();
			$this->set('courseList', $courseList);
			$this->set('errmsg','File required.');
			$this->render('add_student');
			return false;
		}

		//Return true if valid, else error msg
		$validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
		if ($validUploads) {
			// Get file into an array.
			$lines = file($uploadFile);
			// Delete the uploaded file
			unlink($uploadFile);

			//Mess create students
			$resultAry = $this->addUserByImport($this->params['data'], $lines);
			$this->set('data', $resultAry);
			$this->set('userRole', $this->params['data']['User']['role']);

			$this->render('userSummary');
		}
		else {
			$this->set('errmsg', $$validUploads);
			$this->render('add_student');
		}
	}

	function addUserByImport($data=null, $lines=null)
	{
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$result = array();
		$createdPos = $failedPos = 0;

		// Loop through our array
		for ($i = 0; $i < count($lines); $i++) {
			// Split fields up on line by '
			$line = split(',', $lines[$i]);

			$data['User']['id'] = null;
			$data['User']['username'] = trim($line[0]);
			$data['User']['tmp_password'] = trim($line[1]);
			$data['User']['password'] = md5(trim($line[1]));
			$data['User']['student_no'] = trim($line[2]);
			$data['User']['email'] = trim($line[3]);
			$data['User']['first_name'] = trim($line[4]);
			$data['User']['last_name'] = trim($line[5]);
			$data['User']['creator_id'] = $this->rdAuth->id;
			if ($this->User->save($data))
			{
				//New user, save it as usual
				$result['created_students'][$createdPos++] = $data;

				//Save enrol record
				if (isset($this->params['form']['course_id']) && $this->params['form']['course_id'] > 0)
				{
					$userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
					$userEnrol['UserEnrol']['user_id'] = $this->User->id;
					$userEnrol['UserEnrol']['creator_id'] = $this->rdAuth->id;
					$this->UserEnrol->save($userEnrol);
					$this->UserEnrol->id = null;
				}

			}
			else{
				if (isset($this->params['form']['course_id']))
				{
					$curUser = $this->User->find('username="'.$data['User']['username'].'"');
					//Existing user, get this user with the course id
					$enrolled = $this->UserEnrol->getEnrolledStudents($this->params['form']['course_id'], null, 'User.username="'.$data['User']['username'].'"');
					//Current user does not registered to this course yet
					if (empty($enrolled)) {
						$userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
						$userEnrol['UserEnrol']['user_id'] = $curUser['User']['id'];
						$userEnrol['UserEnrol']['creator_id'] = $this->rdAuth->id;
						$this->UserEnrol->save($userEnrol);
						$this->UserEnrol->id = null;
						$result['created_students'][$createdPos++] = $data;


					} else {
						//Current user already registered
						$result['failed_students'][$failedPos] = $data;
						$result['failed_students'][$failedPos++]['User']['error_message'] = 'This user has been already added to this course.';
					}

				} else {
					//Current user already registered
					$result['failed_students'][$failedPos] = $data;
					$result['failed_students'][$failedPos++]['User']['error_message'] = 'This user has been already added to the database.';
				}

			}
		}
		return $result;
	}


	function getQueryAttribute($displayUserType = null, $courseId = null)
	{
		$attributes = array('fields'=>'', 'condition'=>'', 'joinTable'=>array());
		$attributes['fields'] = 'User.id, User.username, User.role, User.first_name, User.last_name, User.email, User.created, User.creator_id, User.modified, User.updater_id';
		$joinTable = array();

		//if (isset($this->rdAuth->courseId)) {
		if (!empty($displayUserType))
		{
			$attributes['condition'] .= 'User.role = "'.$displayUserType.'"';
		}
		if ($displayUserType == 'S') {
			if ($courseId == -1)
			{//Get unassigned student
				$joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id=UserEnrol.user_id');
				$attributes['condition']  .= ' AND UserEnrol.user_id IS NULL';
			}
			else {
				$attributes['condition']  .= ' AND User.id = UserEnrol.user_id';
				//if ($courseId != -1) {
				$attributes['condition'] .= ' AND UserEnrol.course_id = '.$courseId;
				//}
				$joinTable = array(', user_enrols as UserEnrol');
			}
		}
		//}
		$attributes['joinTable']=$joinTable;

		return $attributes;
	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}

	function sendEmail($to='', $from='', $subject='', $body='' ) {
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
            exit();
		}
		// check if the user is an admin - reject otherwise
		$result = false;
		$role = $this->rdAuth->role;

		// TODO: Validation...
		if ( ($role == $this->User->USER_TYPE_ADMIN) || ($role == $this->User->USER_TYPE_INSTRUCTOR) ) {
			$headers = "Content-Transfer-Encoding: quoted-printable\n" .
  	             "From: $from\n" .
  	             "Return-Path: $from\n" .
  	             "CC:\n" .
  	             "BCC:\n";

			$result = @mail($to, $subject, $body, $headers);
			return $result;
		}
		else {
			return $result;
		}
	}

	function removeFromCourse($course_id='', $user_id='') {

		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$this->autoRender = false;
		if($course_id != '' && $user_id != '') {
			$return = $this->UserEnrol->removeStudentFromCourse($user_id, $course_id);
		}
		else {

		}

		$this->redirect('users/edit/'.$user_id);
	}

	function adddelcourse($user_id='')
	{
		if ($this->rdAuth->role == 'S') {
			$this->redirect('home/index');
			exit();
		}
		$this->set('user_id', $user_id);
		$this->layout = 'ajax';
	}

	function nonRegisteredCourses($userId=null) {
		return $this->Course->findNonRegisteredCoursesList($userId);
	}

}

?>