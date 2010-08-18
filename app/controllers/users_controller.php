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
    var $components = array('Session','AjaxList');

    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->NeatString = new NeatString;
        $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') $this->show = 99999999;
        $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy . ' ' . strtoupper($this->direction);
        $this->pageTitle = 'Users';
        parent::__construct();
    }

    // =-=-=-=-=-== New list routines =-=-=-=-=-===-=-
    function setUpAjaxList () {
        // Set up the ajax list component

        // Get the course data
        $userCourseList = $this->sysContainer->getMyCourseList();
        $courseList = array();

        // Add in the unassigned course entry:
        $courseList{"!!!null"} = "-- Unassigned --";

        foreach ($userCourseList as $id => $course) {
            $courseList{$id} = $course['course'];
        }


        // The columns to show
        $columns = array(
            //    Model   columns       (Display Title) (Type Description)
            array("User.id",         "ID",           "4em",    "number"),
            array("User.username",   "Username",     "10em",    "string"),
            array("User.role",       "Role",         "6em",    "map",
                array(  "A" => "Admin",  "I" => "Instructor", "S" => "Student")),
            array("User.first_name", "First Name",   "13em",    "string"),
            array("User.last_name",  "Last Name",    "13em",    "string"),
            array("User.email",      "Email",        "auto",    "string")//,
            //array("UserEnrol.course_id", "Course ID", "number")
        );

        // The course to list for is the extra filter in this case
        $joinTables =
            array(
                array(  // Define the GUI aspecs
                    "id"            => "course_id",
                    "description"   => "for Course:",
                    // What are the choises and the default values?
                    "list"  => $courseList,
                    "default" => $this->rdAuth->courseId,
                    // What table to we join to get these
                    "joinTable"     => "user_enrols",
                    "joinModel"     => "UserEnrol",
                    "foreignKey"    => "user_id",

                    // Any show/hide features based on maps
                    "dependsMap"    => "User.role",    // Look to this column
                    "dependsValues" => array("S")  // Display only when this column is one of these values
                )
            );

        $extraFilters = array();

        // Define Actions
        $deleteUserWarning = "Delete this user. Irreversible. Are you sure?";
        $resetPassWarning = "Resets user Password. Are you sure?";
        $actions = array(
            //   parameters to cakePHP controller:,
            //   display name, (warning shown), fixed parameters or Column ids
            array("View User",  "", "", "view", "User.id"),
            array("Edit User",  "", "", "edit", "User.id"),
            array("Delete User",  $deleteUserWarning,  "", "delete",       "User.id"),
            array("Reset Password", $resetPassWarning, "", "resetPassword","User.id")
        );

        $this->AjaxList->setUp($this->User, $columns, $actions, "User.id", "User.username",
            $joinTables, $extraFilters);
    }

    function ajaxList($pageForRedirect=null) {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

    // =-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-


    function index($message='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

        // Set the top message
        $this->set('message', $message);

        // Set up the basic static ajax list variables
        $this->setUpAjaxList();

        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    // Show a class list
    function goToClassList($course) {
        if (is_numeric($course)) {
            $courses = $this->sysContainer->getMyCourseList();
            if (!empty($courses[$course])) {
                // We need to change the session state to point to this
                // course:
                // Initialize a basic non-funcional AjaxList
                $this->AjaxList->quickSetUp();
                // Clear the state first, we don't want any previous searches/selections.
                $this->AjaxList->clearState();
                // Set and update session state Variable
                $joinFilterSelections->course_id = $course;
                $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);
                // but since that join filter depends on user role, we should set that too.
                $mapFilterSelections->{"User.role"} = "S";
                $this->AjaxList->setStateVariable("mapFilterSelections", $mapFilterSelections);
            }
        }
        // Redirect to user list after state modifications (or in case of error)
        $this->redirect("/users/index");
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
        if ($this->rdAuth->getPrivilegeLevel() >= $this->rdAuth->getPrivilegeLevel($userType    )) {

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

            }  else  {
                $sFound = $this->User->findUserByStudentNo($this->params['data']['User']['username']);
                $duplicate = $sFound ? true : false; // Convert to boolean

                if(!$duplicate)
                {
                       //Generate password
                       if(!$duplicate)
                        $this->params['data']['User']['password'] = $this->NeatString->randomPassword(6);

                    if (empty($this->params['data']['User']['username']))
                        $this->params['data']['User']['username'] = $this->params['form']['newuser'];

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
                } else {
                    $sFound['User']['first_name'] = $this->data['User']['first_name'];
                    $sFound['User']['last_name'] = $this->data['User']['last_name'];
                    $sFound['User']['email'] = $this->data['User']['email'];

                    //Save enrol record
                    if (isset($this->params['form']['course_id']) && $this->params['form']['course_id'] > 0)
                    {
                        $userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
                        $userEnrol['UserEnrol']['user_id'] = $sFound['User']['id'];

                        if($this->UserEnrol->save($userEnrol) && $this->User->save($sFound['User']))
                        {
                            $this->set('tmpPassword', '<Hidden>');
                            $this->set('data', $sFound);
                            $this->set('userRole', $sFound['User']['role']);
                            $this->set('courseId', $this->rdAuth->courseId);

                            //Render to view page to display saved data
                            $this->render('userSummary');
                        }
                        else
                        {
                            $this->set('data', $this->params['data']);

                            //Validate the error why the User->save() method returned false
                            $this->validateErrors($this->UserEnrol);
                            $this->set('errmsg', 'Data not saved.  The student is already enrolled in the course.  Please use the edit function to edit the student\'s details.');
                            $this->set('courseId', $this->rdAuth->courseId);
                        }
                    }
                }

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

                $enrolled_courses = $this->Course->findRegisteredCoursesList($id, $this->rdAuth->id, $this->rdAuth->role);
                $course_count = $this->Course->findNonRegisteredCoursesCount($id, $this->rdAuth->id, $this->rdAuth->role);
                $all_courses = $this->Course->findNonRegisteredCoursesList($id, $this->rdAuth->id, $this->rdAuth->role);

                $this->set('all_courses', $all_courses);
                $this->set('enrolled_courses', $enrolled_courses);
                $this->set('course_count', $course_count[0][0]['total']);
                $this->set('user_id', $id);
                $this->set('user', $this->rdAuth->User->findUserById($this->rdAuth->id));

                if (empty($this->params['data'])) {
                    $this->User->setId($id);
                    $this->params['data'] = $this->User->read();
                    $this->render();
                } else {
                    $this->Output->filter($this->params['data']);//always filter


                    if ($this->params['data']['User']['role'] == 'S') {
                        // For existing students
                        $data2save = $this->User->findUserByStudentNo($this->params['data']['User']['student_no']);
                        $data2save['User']['first_name'] = $this->data['User']['first_name'];
                        $data2save['User']['last_name'] = $this->data['User']['last_name'];
                        $data2save['User']['email'] = $this->data['User']['email'];
                    } else {

                        // For other users
                        $data2save = $this->params['data'];
                    }

                    // Prevent User role changes (also stops privilege escalation)
                    if (!empty($data2save['User']['role'])) {
                        unset ($data2save['User']['role']);
                    }

                    if ($this->User->save($data2save['User'])) {
 						if(!empty($this->params['form']['course_ids'])) {
                        	$this->UserEnrol->insertCourses($this->User->id, $this->params['form']['course_ids']);
                        }

                        //Render to view page to display saved data
                        //TODO: Display list of users after import
                        // $data = $this->User->read();
                        // $this->set('data', $data);
                        // $this->set('userRole', $data['User']['role']);
                        // $this->render('userSummary');
                        $this->redirect("/users/index/The user was edited.");
                    } else {
                    	$this->Output->br2nl($this->params['data']);
                    	$this->set('errmsg', $this->User->errorMessage);
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
                $saveRole = $this->params['data']['User']['role'];
                unset ($this->params['data']['User']['role']);
            }

            if ( $this->User->save($this->params['data']))
            {
                //Render to view page to display saved data
                //TODO: Display list of users after import
                $user = $this->User->read();
                $this->params['data'] = $user;
                $this->set('viewPage', 'false');

                if (!empty($user['User']['email'])) {
                    $message =  "Your Profile Has Been Updated Successfully." . "<br > <br />";
                    $message .= "<a href='..' style='font-size:140%'>Go to your iPeer Home page.</a><br /><br />";
                } else {
                    $message = "We saved your data, but you still need to enter an email address!";
                }

                $this->params['data']['User']['role'] = isset($saveRole) ? $saveRole : '';

                $this->set('data', $this->params['data']);
                $this->set('message', $message);

                //Setup Custom parameter
                $this->rdAuth->setFromData($user['User']);
            } else {
                $this->params['data']['User']['role'] = isset($saveRole) ? $saveRole : '';
                $this->Output->br2nl($this->params['data']);
                $this->set('data', $this->params['data']);
                $this->set('viewPage', 'false');
                $this->set('message', $this->User->errorMessage);
                $this->render();
            }
        }
    }

    function delete($id = null, $type = null)
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

                $delStatus = false;

                if(!is_null($type))
                	$delStatus = $this->UserEnrol->del($id);
                else
                	$delStatus = $this->User->del($id);

                if($delStatus) {

                    $this->redirect('users/index/Record deletion successful.');
                } else {
                    $this->redirect('users/index/Record deletion failed.');
                }
            } else {
                $this->rdAuth->privilegeError();
            }
        } else {
            $this->rdAuth->privilegeError();
        }
    }

    function checkDuplicateName($role='')
    {
    	$isUserEnrol = false;
	   	$sFound = $this->User->findUserByStudentNo($this->params['form']['newuser']);

    	if(!empty($sFound))
    	{
	    	 foreach($sFound['UserEnrol'] as $uEnrol)
	    	 {
	    	 	if($uEnrol['course_id'] == $this->rdAuth->courseId)
	    	 		$isUserEnrol = true;
	    	 }
    	}

    	$this->layout = 'ajax';
        $this->set('role', $role);
        $this->set('isEnrolled', $isUserEnrol);
        $this->render('checkDuplicateName');

    }

    function resetPassword($userId='', $render=true)
    {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();

        // Read the user
        $userData = $this->User->findById($userId);
        if (empty($userData)) {
            $this->redirect("/users/index/User Not Found");
        }

        //General password
        $userData['User']['password'] =  $this->NeatString->randomPassword(6);
        $userData['User']['id'] =  $userId;

        //Save Data
        if ($this->User->save($userData)) {
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
            $email_msg = ereg_replace("<newpassword>", $userData['User']['password'], $email_msg);

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
                $message .= "Email was <u>not</u> sent to the user.";
                $this->set('message', $message);
            }

            //Render to view page to display saved data
            //TODO: Allow to enter email and forward the password reset message to the user
            $this->set('tmpPassword',$userData['User']['password']);
            $this->set('userRole', $userData['User']['role']);
            $this->set('data', $user);
            $this->render('userSummary');

        } else {         //Found error
            $this->set('data', $this->params['data']);

            //Validate the error why the User->save() method returned false
            $this->validateErrors($this->User);

            //Get render page according to the user type
            $this->redirect("/users/index/" . $this->User->errorMessage);

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
        $uploadDir="../tmp/";
        $uploadFile = $uploadDir.$filename;
        //check for blank value
        if (trim($filename) == "") {
            $courseList = $this->sysContainer->getMyCourseList();
            $this->set('courseList', $courseList);
            $this->set('errmsg','A File is required for the import!');
            $this->set('user_type', 'S');
            $this->set('import_again',"true");
            $this->render('add');
            return false;
        }

        //Return true if valid, else error msg
        $validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
        if ($validUploads) {
            // Get file into an array.
            $lines = file($uploadFile);
            // Delete the uploaded file
            unlink($uploadFile);

            //Mass create students
            $resultAry = $this->addUserByImport($this->params['data'], $lines);
            $this->set('data', $resultAry);
            $this->set('userRole', $this->params['data']['User']['role']);

            $this->render('userSummary');
        }
        else {
            $this->set('errmsg', $$validUploads);
            $this->set('user_type', 'S');
            $this->set('import_again',"true");
            $this->render('add');
            return false;
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

            // Set up the password lines
            if (isset($line[1])) {
                $trimmedPassword = trim($line[1]);
            } else {
                $trimmedPassword = "";
            }

            $data['User']['id'] = null;
            $data['User']['username']     = isset($line[0]) ? trim($line[0]) : "";
            $data['User']['tmp_password'] = $trimmedPassword;
            $data['User']['password']     = $trimmedPassword; // Will be hashed by the Users controller
            $data['User']['student_no']   = isset($line[2]) ? trim($line[2]) : "";
            $data['User']['email']        = isset($line[3]) ? trim($line[3]) : "";
            $data['User']['first_name']   = isset($line[4]) ? trim($line[4]) : "";
            $data['User']['last_name']    = isset($line[5]) ? trim($line[5]) : "";
            $data['User']['creator_id']   = $this->rdAuth->id;
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


    function getQueryAttribute($displayUserType = null, $courseId = null, $is_count = false)
    {
        $attributes = array('fields'    => 'User.id, User.username, User.role, User.first_name, User.last_name, User.email, User.created, User.creator_id, User.modified, User.updater_id',
                            'condition' => array(),
                            'joinTable' => array());
        $joinTable = array();

        //if (isset($this->rdAuth->courseId)) {
        if (!empty($displayUserType))
        {
            $attributes['condition'][] = 'User.role = "'.$displayUserType.'"';
        }


        if ('S' == $displayUserType) {
          $attributes['fields'] .= ', COUNT(UserEnrol.id) as enrol_count';

          if ($courseId == -1)
          {
            //Get unassigned student
            $attributes['condition'][] = 'UserEnrol.user_id IS NULL';
            $joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id = UserEnrol.user_id');
          } else if (is_numeric($courseId)) {
            $attributes['condition'][] = 'UserEnrol.course_id = ' . $courseId;
            if($is_count)
            {
              $attributes['condition'][] = 'User.id = UserEnrol.user_id';
              $joinTable = array(', user_enrols as UserEnrol');
            }
            else
            {
              $joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id = UserEnrol.user_id');
            }
          } else {
            if(!$is_count)
            {
              $joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id = UserEnrol.user_id');
            }
          }
        }
        //}

        // hack for stupid CakePHP 1.1, no group by
        $attributes['condition'] = implode(' AND ', $attributes['condition']) . ((!$is_count && 'S' == $displayUserType) ? ' GROUP BY User.id' : '');

        $attributes['joinTable'] = $joinTable;
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

    if($course_id == '' || $user_id == '') {
      $this->Session->setFlash('Invalid course id or user id!');
    }else{

      $this->autoRender = false;

      if(User::canRemoveCourse($this->rdAuth->User->findUserById($this->rdAuth->id), $course_id))
      {
        $return = $this->UserEnrol->removeStudentFromCourse($user_id, $course_id);
      }else{
        $this->Session->setFlash('You do not have permssion to remove this course.');
      }
    }

        $this->redirect('users/edit/'.$user_id);
    }

    function adddelcourse($user_id)
    {
      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();

      $this->set('courses', $this->nonRegisteredCourses($user_id, $this->rdAuth->id, $this->rdAuth->role));

      $this->layout = 'ajax';
    }

    function nonRegisteredCourses($user_id, $requester = null, $requester_role = null) {
        return $this->Course->findNonRegisteredCoursesList($user_id, $requester, $requester_role);
    }

}
?>
