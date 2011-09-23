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

define('IMPORT_USERNAME', 0);
define('IMPORT_FIRSTNAME', 1);
define('IMPORT_LASTNAME', 2);
define('IMPORT_STUDENT_NO', 3);
define('IMPORT_EMAIL', 4);
define('IMPORT_PASSWORD', 5);

App::import('Lib', 'neat_string');

class UsersController extends AppController
{
		
  var $name = 'Users';
  var $direction;
  var $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Pagination');
  var $NeatString;
  var $Sanitize;
  var $uses = array('User', 'UserEnrol', 'Personalize', 'Course', 'SysParameter', 'SysFunction','Role', 'Group');
  var $components = array('Session', 'AjaxList', 'RequestHandler', 'Email');
  
  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->NeatString = new NeatString;
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->set('title_for_layout', __('Users', true));
    parent::__construct();
  }

  function beforeFilter() {
    $this->Auth->autoRedirect = false;
    parent::beforeFilter();
  }
  
  function login() {
    // Get iPeer Admin's email address
    $admin_email = $this->sysContainer->getParamByParamCode('system.admin_email');
    $admin_email = $admin_email['parameter_value'];
    $this->set('admin_email', $admin_email);

    if($this->Auth->user()) {
      $user = $this->Auth->user();
      //sets up the system container for accessible functions
      $accessFunction = $this->SysFunction->getAllAccessibleFunction($this->Auth->user('role'));
      $accessController = $this->SysFunction->getTopAccessibleFunction($this->Auth->user('role'));
      $this->sysContainer->setAccessFunctionList($accessFunction);
      $this->sysContainer->setActionList($accessController);

      //setup my accessible courses
      $myCourses = $this->Course->findAccessibleCoursesList($user['User']);
      $this->sysContainer->setMyCourseList($myCourses);

      $this->redirect($this->Auth->redirect());
	exit;
    }
  }

  function logout() {
    $this->Session->destroy();
    $this->redirect($this->Auth->logout());
  }

  // =-=-=-=-=-== New list routines =-=-=-=-=-===-=-
  function setUpAjaxList () {
    // Set up the ajax list component

    // Get the course data
    $userCourseList = $this->sysContainer->getMyCourseList();
    $coursesList = array();
    // Add in the unassigned course entry:
    $coursesList{"!!!null"} = "-- Unassigned --";

    foreach ($userCourseList as $id => $course) {
      $coursesList{$id} = $course['course'];
    }

//    (isset($this->AjaxList->getState()->joinFilterSelections->course_id))?
//      $selected_course_id = $this->AjaxList->getState()->joinFilterSelections->course_id:
//      $selected_course_id = '0';
//
//    $groupsList = array($this->Group->find('list', array(
//        'fields' => array('group_name'),
//        'conditions' => array('course_id' => $selected_course_id)
//    )));
//    $groupsList = $groupsList['0'];

    // The columns to show
    $columns = array(
                     //    Model   columns       (Display Title) (Type Description)
                     array("User.role",       __("Role", true),         "6em",   "map",
                     array(  "A" => "Admin",  "I" => __("Instructor", true) , "S" => __("Student", true))),
                     array("User.id",         "",             "",      "hidden"),
                     array("User.username",   __("Username", true),     "10em",  "action", "View User"),
                     array("User.full_name",  __("Full Name", true),    "15em",  "string"),
                     array("User.email",      __("Email", true),        "auto",  "action", "Send Email"),
                     //array("UserEnrol.course_id", "Course ID", "number"),
                    );

    // The course to list for is the extra filter in this case
    $joinTables = 
      array(
            array(  // Define the GUI aspecs
                    "id"            => "Enrolment.id",
                    "description"   => __("for Course:", true),
                    // What are the choises and the default values?
                    "list"  => $coursesList,
                    "default" => $this->Session->read('ipeerSession.courseId'),
                    // What table do we join to get these
                    // commented out by compass. use association and default 
                    // join format
//                    "joinTable"     => "user_enrols",
//                    "joinModel"     => "UserEnrol",
//                    "foreignKey"    => "user_id",

                    // Any show/hide features based on maps
                    "dependsMap"    => "User.role",    // Look to this column
                    "dependsValues" => array("S")  // Display only when this column is one of these values
                 ),
//            array(  // Define the GUI aspecs
//                    "id"            => "group_id",
//                    "description"   => "for Group:",
//                    // What are the choises and the default values?
//                    "list"  => $groupsList,
//                    "default" => '',
//                    // What table do we join to get these
//                    "joinTable"     => "groups_members",
//                    "joinModel"     => "GroupsMember",
//                    "foreignKey"    => "user_id",
//                    "conditions" => array('course_id' => $selected_course_id),
//
//                    // Any show/hide features based on maps
//                    "dependsMap"    => "User.role",    // Look to this column
//                    "dependsValues" => array("S")  // Display only when this column is one of these values
//                 )
           );

    // Define Actions
    $deleteUserWarning = __("Delete this user. Irreversible. Are you sure?", true);
    $resetPassWarning = __("Resets user Password. Are you sure?", true);

    if ($this->Auth->user('role') != 'A') {
      $actionRestrictions = array(
                                  "User.role" => array (
                                                        "S" => true,
                                                        "!default" => false));
    } else {
      $actionRestrictions = "";
    }

    $actions = array(
                     //   parameters to cakePHP controller:,
                     //   display name, (warning shown), fixed parameters or Column ids
                     array(__("View User", true),  "", "", "", "view", "User.id"),
                     array(__("Send Email", true),  "", "", "emailer", "write", "User.id"),
                     array(__("Edit User", true),  "", $actionRestrictions, "", "edit", "User.id"),
                     array(__("Delete User", true),    $deleteUserWarning,   $actionRestrictions, "", "delete","User.id"),
                     array(__("Reset Password", true), $resetPassWarning,  $actionRestrictions, "", "resetPassword","User.id")
                    );

    $this->AjaxList->setUp($this->User, $columns, $actions, "User.id", "User.username",
                           $joinTables);
  }

  function ajaxList($pageForRedirect=null) {
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  // =-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-


  function index($message='') {
    // Set the top message
    $this->set('message', $message);

    // Set up the basic static ajax list variables
    $this->setUpAjaxList();

    //If User role isn't Admin, display student as default
    if($this->Auth->user('role')!='A'){
      $mapFilterSelections->{"User.role"} = "S";
      $this->AjaxList->setStateVariable("mapFilterSelections", $mapFilterSelections);
    }  

    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
    
    $this->set('can_add_user', $this->AccessControl->hasPermission('functions/user', 'create'));
    $this->set('can_import_user', $this->AccessControl->hasPermission('functions/user/import'));
    
    $fields = array('Enrolment');
    
    $tempVar=$this->User->getEnrolledStudents(1,$fields);      
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
        $joinFilterSelections->{'Enrolment.id'} = $course;
        $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);
        // but since that join filter depends on user role, we should set that too.
        $mapFilterSelections->{"User.role"} = "S";
        $this->AjaxList->setStateVariable("mapFilterSelections", $mapFilterSelections);
      }
    }
    // Redirect to user list after state modifications (or in case of error)
    $this->redirect("index");
  }

    /*function add($userType = null) {
      if(empty($userType)) {
        $userType = $this->data['User']['role'];
      }
      $this->AccessControl->check('functions/role/'.$userType, 'create');

      $course_id = $this->Session->read('ipeerSession.courseId');
      if (!empty($course_id))  {
        $this->set('title_for_layout', $this->sysContainer->getCourseName($this->Session->read('ipeerSession.courseId')).__(' > Students', true));
      }

      $this->set('role', $userType);
      $this->set('course_id', $course_id);

      //List Add Page
      if (empty($this->params['data'])) {

        //check and set user type
        if (empty($this->params['data']['User']['role'])) {
          $this->params['data']['User']['role'] = $userType;
        }

        if('student' == $userType) {
          // We need a coursesList, even if it's empty
          $coursesList = $this->sysContainer->getMyCourseList();
          $course_params = array('controller' => 'courses',
                                 'coursesList' => $coursesList,
                                 'courseId'=> $course_id,
                                 'defaultOpt' => (empty($course_id) ? '-1' : $course_id));
          $this->set('course_params', $course_params);
        }
        $this->set('isStudent', 'student' == $userType);
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
            $this->set('courseId', $this->Session->read('ipeerSession.courseId'));

            //Render to view page to display saved data
            $this->render('userSummary');
          }
          //Found error
          else {
            $this->set('data', $this->params['data']);

            //Validate the error why the User->save() method returned false
            $this->validateErrors($this->User);
            $this->set('errmsg', $this->User->errorMessage);
            $this->set('courseId', $this->Session->read('ipeerSession.courseId'));

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
              $this->set('courseId', $this->Session->read('ipeerSession.courseId'));

              //Render to view page to display saved data
              $this->render('userSummary');
            }
            else
            {
              $this->set('data', $this->params['data']);

              //Validate the error why the User->save() method returned false
              $this->validateErrors($this->UserEnrol);
              $this->set('errmsg', 'Data not saved.  The student is already enrolled in the course.  Please use the edit function to edit the student\'s details.');
              $this->set('courseId', $this->Session->read('ipeerSession.courseId'));
            }
          }
        }

      }
    }*/
  function __processForm() {
    if (!empty($this->data)) {
      $this->Output->filter($this->data);//always filter
	if(!empty($roleNum)) {
		$roleNum = $this->data['Role']['Role'];
	
		$role = $this->Role->getRoleByRoleNumber($roleNum[0]);
		$this->data['User']['role'] = $role;}
	    //Save Data
      if ($this->data = $this->User->save($this->data)) {
        $this->data['User']['id'] = $this->User->id;
        return true;
      }
      else{
        $validationErrors = $this->User->invalidFields();
        $errorMsg = '';
        foreach($validationErrors as $error){
          $errorMsg = $errorMsg."\n".$error;
        }
        $this->Session->setFlash(__('Failed to save.</br>', true).$errorMsg);
        
      }
    }

    return false;
  }

  // This function is needed since the $this->data looks different between
  //  initial page render, and postback page render. Needs a better implementation.
  function determineIfStudentFromThisData($data) {
    // On initial page render, the Role is a complete structure
    if (!empty($data['Role'])) {
      foreach ($data['Role'] as $role) {
        if (!empty($role['name']) && $role['name']=='student') {
          return true;
        }
      }
    }
    
    // But on a post back, it's a little funny...  Yeah, go figure :-)
    if (!empty($data['Role']['Role']) && is_array($data['Role']['Role'])) {  
      foreach ($data['Role']['Role'] as $value) {
        if ($value == 4) { // 4 means student
          return true;
        }
      }
    }

    return false;
  }

  function getSimpleEntrollmentLists($id) {
    $result = array();

    if ($id) {
      // This needs a custom query:
      //   The getSimpleEntrollmentLists() can be called twice in one page rendering.
      //    There's a problem with Cake PHP caching resutls (I could not turn this off)
//      $enrolled_courses = $this->UserEnrol->query(
//        "SELECT `course_id` from `user_enrols` WHERE user_id=$id",
//         /* No cache!! (undoc.) */ false );
      $enrolled_courses = $this->UserEnrol->find('all', array(
          'conditions' => array('UserEnrol.user_id' => $id),
          'fields' => array('UserEnrol.course_id')
      ));
    } else {
      // New Student = display a courses list.
      $enrolled_courses = array();
      $course_couha = 0;
      $all_courses = array();
    }

    // Get accessible courses
    $coursesList = $this->sysContainer->getMyCourseList();

    // List the entrolled courses
    $simpleEnrolledList = array();
    foreach ($enrolled_courses as $value) {
      if (!empty($coursesList[$value['UserEnrol']['course_id']])) {
        array_push($simpleEnrolledList, $value['UserEnrol']['course_id']);
      }
    }

    // List the avaliable courses
    $simpleCoursesList = array();
    foreach ($coursesList as $key => $value) {
      $simpleCoursesList[$key] = $value['course'];
    }

    // Pack up the data for the return
    $result['simpleEnrolledList'] = $simpleEnrolledList;
    $result['simpleCoursesList'] = $simpleCoursesList;

    return $result;
  }

  function setUpCourseEnrollmentLists($id, $thisCourse = null) {
    $data = $this->getSimpleEntrollmentLists($id);
    $this->set("simpleEnrolledList", $data['simpleEnrolledList']);
    $this->set("simpleCoursesList",  $data['simpleCoursesList']);
  }

  function processEnrollmentListsPostBack($params, $userId) {
    // Check if the course list was submitted at all.
    if (empty($params['form']['Courses'])) {
      // No Courses list? Then don't do anything. 
      return;
    }
    // Build up a list of checkboxed courses
    $checkedCourseList = array();
    foreach ($params['form'] as $key => $value) {
      if (strstr($key, "checkBoxList_")) {
        $aCourse = substr($key, 13);
        array_push($checkedCourseList, $aCourse);
      }
    }

    $data = $this->getSimpleEntrollmentLists($userId);
    $simpleEnrolledList = $data['simpleEnrolledList'];
    // Put students into newly selected courses
    foreach ($checkedCourseList as $key => $value) {
		if(!in_array($value, $simpleEnrolledList) && 
			is_numeric($userId) && 
			is_numeric($value)) {
		$this->User->registerEnrolment($userId, $value);
      }
    }

    // Take them out of the de-selected courses
    foreach ($simpleEnrolledList as $key => $value) {
		if (!in_array($value, $checkedCourseList) && 
			is_numeric($userId) && 
			is_numeric($value)) {
		$this->User->dropEnrolment($userId, $value);
      }
    }
  }

  function view($id) {
    $this->AccessControl->check('functions/user', 'read');

    if (!is_numeric($id) || !($this->data = $this->User->findUserByid($id))) {
      $this->Session->setFlash('Invalid user ID.');
      $this->redirect('index');
    }

    $roles = $this->User->getRoles($id);

    if(!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('ViewUser', $roles)) {
      $this->Session->setFlash(__('You do not have permission to view this user.', true));
      $this->redirect('index');
    }
      
    $isStudent = $this->determineIfStudentFromThisData($this->data);  
    if ($isStudent) {
      $this->setUpCourseEnrollmentLists($id);
    }
    
    $this->setUpCourseEnrollmentLists($id);
    $this->set('roles', $this->User->getRoles($id));
    $this->set('readonly', true);
    $this->set('isStudent', $isStudent);
    $this->render('add');
  }

  function add() {
   // $this->AccessControl->check('functions/user', 'create');

    $course_id = $this->Session->read('ipeerSession.courseId');
    if (!empty($course_id))  {
      $this->set('title_for_layout', $this->sysContainer->getCourseName($this->Session->read('ipeerSession.courseId')).' > Students');
    }

    $this->set('course_id', $course_id);

    if($this->__processForm()) {
      // Process the course changes list
      $this->processEnrollmentListsPostBack($this->params, $this->User->id);
      //Send email w/ params
      $this->set('addedUser', $this->params['data']['User']);
      if($this->params['data']['User']['send_email_notification']){
        if($this->_sendEmail('','User Created',$this->Auth->user('email'),$this->params['data']['User']['email'], 'addUser'))
          $this->Session->setFlash(__('Sent a notification email to the user', true));
        else
          $this->Session->setFlash(__('Failed to Send Email', true));
      }
      $this->set('data', $this->params['data']);
      $this->render('userSummary');      
    }

    $thisUser = $this->Auth->user();
    $thisUserRoles = $this->User->getRoles($thisUser['User']['id']);
    $addingStudent = in_array("instructor", $thisUserRoles); // If this is an instructor, they can only add students.
    $this->setUpCourseEnrollmentLists(null);
    $courseID = $this->set('courseId', $this->Session->read('ipeerSession.courseId'));

    $this->set('roles', $this->AccessControl->getEditableRoles());
    $this->set('isEdit', false);
    $this->set('isStudent', $addingStudent);
    $this->set('readonly', false);
  }

  function edit($id) {
    // Ensure that the id is valid
    if (!is_numeric($id)) {
      $this->cakeError('error404');
    }

    $this->AccessControl->check('functions/user/' . $this->User->getRoleById($id), 'update');
    if(empty($this->data)) {
      $this->User->id = $id;
      $this->data = $this->User->read();
    } else {
      $this->data['User']['id'] = $id;
      if($this->__processForm()) {
        // Process the course changes list
        $this->processEnrollmentListsPostBack($this->params, $id);
        // Set message for user.
        $this->Session->setFlash(__('Changes are saved.', true));
      }
      else{
        $this->User->id = $id;
        $this->data = $this->User->read();
      }
    }
    $isStudent = $this->determineIfStudentFromThisData($this->data);  
    if ($isStudent) {
      $this->setUpCourseEnrollmentLists($id);
    }
    
    $this->set('data', $this->data);
    $this->set('roles', $this->AccessControl->getEditableRoles());
    $this->set('isStudent', $isStudent);
    $this->set('readonly', false);
    $this->set('isEdit', true);
    $this->render('add');
  }

  /*function edit($id)
  {
      // If a form was submitted, user that id instead
      if (!empty($this->params['data'])) {
        $id = $this->params['data']['User']['id'];
      }

      // Ensure that the id is valid
      if (!is_numeric($id)) {
        $this->cakeError('error404');
      }

      $this->AccessControl->check('functions/role/' . $this->User->getRoleById($id), 'update');

      $enrolled_courses = $this->Course->findRegisteredCoursesList($id, $this->Auth->user('id'), $this->Auth->user('role'));
      $this->set('enrolled_courses', $enrolled_courses);
      $course_count = $this->Course->findNonRegisteredCoursesCount($id, $this->Auth->user('id'), $this->Auth->user('role'));
      $this->set('course_count', $course_count[0][0]['total']);
      $all_courses = $this->Course->findNonRegisteredCoursesList($id, $this->Auth->user('id'), $this->Auth->user('role'));
      $this->set('all_courses', $all_courses);


      // Get accessible courses
      $coursesList = $this->sysContainer->getMyCourseList();

      // List the entrolled courses
      $simpleEnrolledList = array();
      foreach ($enrolled_courses as $key => $value) {
        if (!empty($coursesList[$value['Course']['id']])) {
          array_push($simpleEnrolledList, $value['Course']['id']);
        }
      }

      $this->set("simpleEnrolledList", $simpleEnrolledList);

      // List the avaliable courses
      $simpleCoursesList = array();
      foreach ($coursesList as $key => $value) {
        $simpleCoursesList[$key] = $value['course'];
      }
      $this->set("simpleCoursesList", $simpleCoursesList);

      $this->set('user_id', $id);

      if (empty($this->params['data'])) {
        $this->params['data'] = $this->User->findUserById($id);
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

          // Build up a list of checkboxed courses
          $checkedCourseList = array();
          foreach ($this->params['form'] as $key => $value) {
            if (strstr($key, "checkBoxList_")) {
              $aCourse = substr($key, 13);
              array_push($checkedCourseList, $aCourse);
            }
          }

          // Put students into newly selected courses
          foreach ($checkedCourseList as $key => $value) {
            if(!isset($simpleEnrolledList[$value])) {
              $this->UserEnrol->insertCourses($data2save['User']['id'],array($value));
            }
          }

          // Take them out of the de-selected courses
          foreach ($simpleEnrolledList as $key => $value) {
            if (!isset($checkedCourseList[$value])) {
              $this->UserEnrol->removeStudentFromCourse($data2save['User']['id'], $value);
            }
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
    }*/

    function editProfile()
    {
       // No security checks here, since we're editing the logged-in user
      $id = $this->Auth->user('id');
      $this->set('viewPage', false);
      if(!empty($this->data)) {
        $this->data['User']['id'] = $id;
        
        if(!empty($this->data['User']['tmp_password'])){
          if(md5($this->data['User']['old_password']==$this->Auth->user('password'))){
            if($this->data['User']['tmp_password']==$this->data['User']['confirm_password']){
              $this->data['User']['password'] = md5($this->data['User']['tmp_password']);
            }
            else{
              $this->Session->setFlash(__("Confirm password is wrong", true));
              $this->redirect('editProfile/'.$id);
            }
          }
          else{
            $this->Session->setFlash(__("Old password is wrong", true));
            $this->redirect('editProfile/'.$id);
          }
        }
        else{
          unset($this->data['User']['tmp_password']);
        }
        
        if($this->__processForm()) {
          $this->__setSessionData($this->data['User']);
          if (!empty($this->data['User']['email'])) {
            $this->Session->setFlash(__("Your Profile Has Been Updated Successfully.", true)."<br /><br /> " .
            "<a href='../../home/' style='font-size:140%'>".__('Go to your iPeer Home page.', true)."</a><br /><br />");
          } else {
            $this->Session->setFlash(__("We saved your data, but you still need to enter an email address!", true));
          }
        }
      }
      $this->data = $this->User->read(null, $id);
      $this->Output->br2nl($this->data);    
      $this->set('has_title', $this->User->hasTitle($this->data['Role']));
      $this->set('is_student', $this->User->hasStudentNo($this->data['Role']));
      $this->set('data', $this->data);
      return;
    }

    function delete($id, $type = null)
    {
      $this->AccessControl->check('functions/user', 'delete');

      // Ensure that the id is valid
      if (!is_numeric($id)) {
        $this->cakeError('error404');
      }

      // check if current user has permission to delete this user
      // in case of the being deleted user has higher level role
      $roles = $this->User->getRoles($id);
      if(!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('DeleteUser', $roles)) {
        $this->Session->setFlash(__('You do not have permission to delete the user.', true));
      } else {
        if($this->User->delete($id)) {
          $this->Session->setFlash(__('Record is successfully deleted!', true));
        } else {
          $this->Session->setFlash(__('Delete failed!', true));
        }
      }

      $this->redirect('index');
    }

    function drop($id, $course_id) {
      // Ensure that the id is valid
      if (!is_numeric($id) && !is_numeric($course_id)) {
        $this->cakeError('error404');
      }

      // check if current user has permission to delete this user
      // in case of the being deleted user has higher level role
      $roles = $this->User->getRoles($id);
      if(!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('DropUser', $roles)) {
        $this->Session->setFlash(__('You do not have permission to drop the user.', true));
      } else {
        if($this->User->dropEnrolment($id, $course_id)) {
          $this->Session->setFlash(__('The user is dropped from this course!', true));
        } else {
          $this->Session->setFlash(__('Drop failed!', true));
        }
      }

      $this->redirect('index');
    }

    function checkDuplicateName() {
      if(!$this->RequestHandler->isAjax()) {
        $this->cakeError('error404');
      }
        $this->layout = 'ajax';
      $this->autoRender = false;

        $isUserEnrol = false;
        $sFound = $this->User->getByUsername($this->data['User']['username']);

        /*if(!empty($sFound)) {
             foreach($sFound['UserEnrol'] as $uEnrol) {
                if($uEnrol['course_id'] == $this->Session->read('ipeerSession.courseId'))
                    $isUserEnrol = true;
             }
        }

      $this->set('role', $role);
      $this->set('username', $this->params['form']['newuser']);
      $this->set('isEnrolled', $isUserEnrol);*/
      //$this->render('checkDuplicateName');
      return ($sFound) ? __('Username "', true).$this->data['User']['username'].__('" already exists.', true) : '';
    }

    function resetPassword($user_id, $render=true)
    {
      $this->AccessControl->check('functions/user/password_reset');

      // Read the user
      $user_data = $this->User->findUserByid($user_id, array('contain' => false));

      if (empty($user_data)) {
        $this->Session->setFlash(__('User Not Found!', true));
        $this->redirect("index");
      }

      $roles = $this->User->getRoles($user_id);
      if(!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('PasswordReset', $roles)) {
        $this->Session->setFlash(__('You do not have permission to drop the user.', true));
      }

      //General password
      $tmp_password = $this->NeatString->randomPassword(6);
      $user_data['User']['tmp_password'] = $tmp_password;
      $user_data['User']['password'] =  md5($tmp_password);
      $user_data['User']['id'] =  $user_id;

      //Save Data
      if ($user = $this->User->save($user_data, true, array('password'))) {
        $message = __("Password successfully reset. ", true);
        $this->User->set('id', $user_id);

        // send email to user
        $this->set('user_data', $user_data);
        if($this->_sendEmail('','Reset Password',$this->Auth->user('email'),$user_data['User']['email'], 'resetPassword')){
        //if($this->_sendEmail( $to, $from, $subject, $email_msg )) {
          $message .= __("Email has been sent. ", true);
        } else {
          if(!isset($to) || strlen($to) < 1) {
            $message .= __('No destination email address. ', true);
          }
          $message .= __("Email was <u>not</u> sent to the user.", true);
        }
        $this->Session->setFlash($message);
        $this->redirect('index');
      } else {
        //Get render page according to the user type
        $this->redirect('index');
      }
    }

    function import() {
        $this->autoRender = false;
        if(isset($this->params['form']['course_id']))
            $this->Session->write('ipeerSession.courseId', $this->params['form']['course_id']);
        $filename = isset($this->params['form']['file']['name'])? $this->params['form']['file']['name']:'';
        $tmpFile = isset($this->params['form']['file']['tmp_name'])? $this->params['form']['file']['tmp_name']: NULL;
        
        //$uploadDir = $this->sysContainer->getParamByParamCode('system.upload_dir');
        $uploadDir="../tmp/";
        $uploadFile = $uploadDir.$filename;
        //check for blank value
        if (trim($filename) == "") {
            $coursesList = $this->sysContainer->getMyCourseList();
            $this->set('coursesList', $coursesList);            
            $this->set('user_type', 'S');
            $this->set('import_again',"true");
            $this->render('import');
            $this->set('errmsg',__('A File is required for the import!', true));
            return false;
        }

        //Return true if valid, else error msg
        $validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
        if ($validUploads !== true) {
          $this->set('errmsg', $validUploads);
          $this->set('user_type', 'S');
          $this->set('import_again',"true");
          $this->render('import');
          return false;
        }

        // Get file into an array.
        $lines = file($uploadFile);
        // Delete the uploaded file
        unlink($uploadFile);

        //Mass create students
        $resultAry = $this->addUserByImport($this->params['data'], $lines);
        $this->set('data', $resultAry);
        
        $this->set('userRole', $this->params['data']['User/role']);
        $this->render('userSummary');
    }

    function addUserByImport($data, $lines)
    {
        // Make sure the present user is not a student
        //$this->rdAuth->noStudentsAllowed();
		if('S' == $this->Auth->user('role'))
		  return false;	  
        $result = array();
        $createdPos = $failedPos = 0;

        // Loop through our array
        for ($i = 0; $i < count($lines); $i++) {

            // Get rid of '"', it just  confuses iPeer in CSV Files
            $filteredLine = $lines[$i];
            $filteredLine = str_replace('"','', $filteredLine);

            // Split fields up on line by ','
            $line = @split(',', $filteredLine);

            // Set up the password lines
            if (isset($line[IMPORT_PASSWORD])) {
                $trimmedPassword = trim($line[IMPORT_PASSWORD]);
            } else {
                $trimmedPassword = $this->NeatString->randomPassword(6);
            }

            $data['User']['id'] = null;
            $data['User']['username']     = isset($line[IMPORT_USERNAME]) ? trim($line[IMPORT_USERNAME]) : "";
            $data['User']['first_name']   = isset($line[IMPORT_FIRSTNAME]) ? trim($line[IMPORT_FIRSTNAME]) : "";
            $data['User']['last_name']    = isset($line[IMPORT_LASTNAME]) ? trim($line[IMPORT_LASTNAME]) : "";
            $data['User']['student_no']   = isset($line[IMPORT_STUDENT_NO]) ? trim($line[IMPORT_STUDENT_NO]) : "";
            $data['User']['email']        = isset($line[IMPORT_EMAIL]) ? trim($line[IMPORT_EMAIL]) : "";
            $data['User']['tmp_password'] = $trimmedPassword;
            $data['User']['password']     = md5($trimmedPassword); // Will be hashed by the Users controller
            $data['User']['creator_id']   = $this->Auth->user('id');

            if ($this->User->save($data))
            {
                //New user, save it as usual
                $result['created_students'][$createdPos++] = $data;

                //Save enrol record
                if (isset($this->params['form']['course_id']) && $this->params['form']['course_id'] > 0)
                {
                    $userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
                    $userEnrol['UserEnrol']['user_id'] = $this->User->id;
                    $userEnrol['UserEnrol']['creator_id'] = $this->Auth->user('id');
                    $this->UserEnrol->save($userEnrol);
                    $this->UserEnrol->id = null;
                }

            } else {
                if (isset($this->params['form']['course_id']))
                {
                    $curUser = $this->User->find('username="'.$data['User']['username'].'"');
                    //Existing user, get this user with the course id
                    $enrolled = $this->UserEnrol->getEnrolledStudents($this->params['form']['course_id'], null, 'User.username="'.$data['User']['username'].'"');
                    //Current user does not registered to this course yet
                    if (empty($enrolled)) {
                        $userEnrol['UserEnrol']['course_id'] = $this->params['form']['course_id'];
                        $userEnrol['UserEnrol']['user_id'] = $curUser['User']['id'];
                        $userEnrol['UserEnrol']['creator_id'] = $this->Auth->user('id');
                        $this->UserEnrol->save($userEnrol);
                        $this->UserEnrol->id = null;
                        $result['created_students'][$createdPos++] = $data;
                    } else {
                        //Current user already registered
                        $result['failed_students'][$failedPos] = $data;
                        $result['failed_students'][$failedPos++]['User']['error_message'] = __('This user has been already added to this course.', true);
                    }

                } else {
                    //Current user already registered
                    $result['failed_students'][$failedPos] = $data;
                    $result['failed_students'][$failedPos++]['User']['error_message'] = __('This user has been already added to the database.', true);
                }

            }
        }
        return $result;
    }


    // unused function
/*    function getQueryAttribute($displayUserType = null, $courseId = null, $is_count = false)
    {
        $attributes = array('fields'    => 'User.id, User.username, User.role, User.first_name, User.last_name, User.email, User.created, User.creator_id, User.modified, User.updater_id',
                            'condition' => array(),
                            'joinTable' => array());
        $joinTable = array();

        //if (isset($this->Session->read('ipeerSession.courseId'))) {
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
    }*/

    function update($attributeCode='',$attributeValue='') {
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->data = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
    }
    
    function nonRegisteredCourses($user_id, $requester = null, $requester_role = null) {
        return $this->Course->findNonRegisteredCoursesList($user_id, $requester, $requester_role);
    }


    /**
     * Loads the rdAuth data from the Session.
     */
    function __loadFromSession() {
        if($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession')) {
            $this->id = $this->Session->read('ipeerSession.id');
            $this->username = $this->Session->read('ipeerSession.username');
            $this->fullname = $this->Session->read('ipeerSession.fullname');
            $this->role = $this->Session->read('ipeerSession.role');
            $this->email = $this->Session->read('ipeerSession.email');
            $this->customIntegrateCWL = $this->Session->read('ipeerSession.customIntegrateCWL');
            $this->courseId = $this->Session->read('ipeerSession.courseId');
        } else {
            return $this->Session->error();
        }
    }

    /**
     * Updates the user session from the user data passed, and loads it into this rdAuth object.
     * @param unknown_type userData
     */
    function __setSessionData($userData) {
        $this->Session->write('ipeerSession.id', $userData['id']);
        $this->Session->write('ipeerSession.username', $userData['username']);
        $this->Session->write('ipeerSession.fullname', $userData['last_name'].' '.$userData['first_name']);
        //$this->Session->write('ipeerSession.role', $userData['Role']);
        $this->Session->write('ipeerSession.email', $userData['email']);
        //return $this->__loadFromSession();
    }
}
?>
