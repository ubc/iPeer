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


	function index() {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

        if (!is_numeric($id)) {
            $this->rdAuth->privilegeError();
        }
            // Make sure that the privileges of the asking user is at least as high
            //  as the privileges of the user being viewed.
        if ($this->rdAuth->getPrivilegeLevel() < $this->rdAuth->getPrivilegeLevel($id)) {
            $this->rdAuth->privilegeError();
        }

        $this->set("userId", $id);
        // All okay, proceed to render.
	}

	function add($userType='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

        //check and set user type
        if (!empty($this->params['data'])) {
             $userType = $this->params['data']['User']['role'];
        }

        // Check that user type is valid : get from parameter, or the submited form.
        $userTypeLow = !empty($this->params['data']) ? $this->params['data']['User']['role'] : $userType;
        $userTypeLow = strtolower($userTypeLow);
        if ($userTypeLow != 's' && $userTypeLow != 'i' && $userTypeLow != 'a') {
            // Bad user type
            $this->rdAuth->privilegeError();
        }

        $this->set('user_type', $userType);

        // We should be of equal or higher privileges to be able to create this user
        if ($this->rdAuth->getPrivilegeLevel() >= $this->rdAuth->getPrivilegeLevel($userType)) {

            if (!empty($this->rdAuth->courseId))  {
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

            } else {
                //General password
                $this->params['data']['User']['password'] = $this->NeatString->randomPassword(6);
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

                    $this->set('tmpPassword', $this->params['data']['User']['password']);
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

                }//end if
            }
        } else {
            $this->rdAuth->privilegeError();
        }
	}

	function edit($id=null)
	{
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

        // If a form was submitted, user that id instead
        if (!empty($this->params['data'])) {
            $id = $this->params['data']['User']['id'];
        }

        // Ensure that the id is valid
        if (is_numeric($id)) {

            // We should be of equal or higher privileges to be able to create this user
            if ($this->rdAuth->getPrivilegeLevel() >= $this->rdAuth->getPrivilegeLevel($id)) {

                $enrolled_courses = $this->Course->findRegisteredCoursesList($id);
                $course_count = $this->Course->findNonRegisteredCoursesCount($id);
                $all_courses = $this->Course->findNonRegisteredCoursesList($id);

                $this->set('all_courses', $all_courses);
                $this->set('enrolled_courses', $enrolled_courses);
                $this->set('course_count', $course_count[0][0]['total']);
                $this->set('user_id', $id);

                if (empty($this->params['data'])) {
                    $this->User->setId($id);
                    $this->params['data'] = $this->User->read();
                    $this->render();
                } else {
                    $this->Output->filter($this->params['data']);//always filter

                    // Prevent User role changes (also stops privilege escalation)
                    if (!empty($this->params['data']['User']['role'])) {
                        unset ($this->params['data']['User']['role']);
                    }

                    if ($this->User->save($this->params['data'])) {

                        $this->UserEnrol->insertCourses($this->User->id, $this->params['form']);

                        //Render to view page to display saved data
                        //TODO: Display list of users after import
                        $data = $this->User->read();
                        $this->set('data', $data);
                        $this->set('userRole', $data['User']['role']);
                        $this->render('userSummary');
                    } else {
                        $this->Output->br2nl($this->params['data']);
                        $this->set('data', $this->params['data']);
                        $this->render();
                    }
                }
            } else {
                //User is under-privileged
                $this->rdAuth->privilegeError();
            }
        } else {
            // Bad ID format
            $this->rdAuth->privilegeError();
        }
	}

	function editProfile()
	{
        // No security checks here, since we're editing the logged-in user
		$id = $this->rdAuth->id;


		if (empty($this->params['data']))
		{
			$this->User->setId($id);
			$this->params['data'] = $this->User->read();
			$this->set('viewPage', 'false');
			$this->render();
		} else {
			if (empty($this->params['data']['User']['password'])) {
				unset($this->params['data']['User']['password']);
			}
			$this->Output->filter($this->params['data']);//always filter

            // Prevent User role changes (also stops privilege escalation)
            if (!empty($this->params['data']['User']['role'])) {
                unset ($this->params['data']['User']['role']);
            }

			if ( $this->User->save($this->params['data']))
			{
				//Render to view page to display saved data
				//TODO: Display list of users after import
				$user = $this->User->read();
				$this->params['data'] = $user;
				$this->set('viewPage', 'true');
				$this->set('message', 'Your Profile Updated Successfully.');
				//Setup Custom parameter
				$this->rdAuth->setFromData($user['User']);
			} else {
				$this->Output->br2nl($this->params['data']);
				$this->set('data', $this->params['data']);
				$this->set('viewPage', 'false');
				$this->render();
			}
		}
	}

	function delete($id = null)
	{
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Ensure that the id is valid
        if (is_numeric($id)) {

            // We should be of equal or higher privileges to be able to create this user
            if ($this->rdAuth->getPrivilegeLevel() >= $this->rdAuth->getPrivilegeLevel($id)) {

                $displayUserType = isset($this->params['form']['display_user_type']) ?
                    $this->params['form']['display_user_type'] : 'S';

                $this->set('displayUserType', $displayUserType);
                if (isset($this->params['form']['id']))
                {
                    $id = intval(substr($this->params['form']['id'], 5));
                }

                if ($this->User->del($id)) {
                    $this->set('message', 'Record deletion successful.');
                    $this->index();
                    $this->render('index');
                } else {
                    $this->set('message', 'Record deletion failed.');
                    $this->index();
                    $this->render('index');
                }
            } else {
                $this->rdAuth->privilegeError();
            }
        } else {
            $this->rdAuth->privilegeError();
        }
	}

	function search()
	{
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

		//General password
		$this->params['data']['User']['password'] =  $this->NeatString->randomPassword(6);
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
			$email_msg = ereg_replace("<newpassword>", $this->params['data']['User']['password'], $email_msg);

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
			$this->set('tmpPassword',$this->params['data']['User']['password']);
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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

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

			} else{
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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();


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

        // Make sure the present user is not a student
        if ($this->rdAuth->getPrivilegeLevel() <= $this->rdAuth->studentPrivilegeLevel()) {
            $this->rdAuth->privilegeError();
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
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

		$this->set('user_id', $user_id);
		$this->layout = 'ajax';
	}

	function nonRegisteredCourses($userId=null) {
		return $this->Course->findNonRegisteredCoursesList($userId);
	}

}

?>
