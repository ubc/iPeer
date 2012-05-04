<?php
App::import('Lib', 'neat_string');

/**
 * UsersController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UsersController extends AppController
{
    public $name = 'Users';
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'FileUpload.FileUpload');
    public $NeatString;
    public $uses = array('User', 'UserEnrol', 'Personalize', 'Course', 'SysParameter', 'SysFunction', 'Role', 'Group');
    public $components = array('Session', 'AjaxList', 'RequestHandler', 
      'Email', 'FileUpload.FileUpload', 'PasswordGenerator');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->NeatString = new NeatString;
        $this->set('title_for_layout', __('Users', true));
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        $this->Auth->autoRedirect = false;
        parent::beforeFilter();

        $this->FileUpload->allowedTypes(array(
            'txt' => array('text/plain'),
            'csv' => array('text/csv')));
        $this->FileUpload->uploadDir('../tmp');
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
    }


    /**
     * login
     *
     * @access public
     * @return void
     */
    function login()
    {
        // Get iPeer Admin's email address
        $admin_email = $this->sysContainer->getParamByParamCode('system.admin_email');
        $admin_email = $admin_email['parameter_value'];
        $this->set('admin_email', $admin_email);

        if ($this->Auth->user()) {
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

    /**
     * logout
     *
     * @access public
     * @return void
     */
    function logout()
    {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }


    /**
     * Setup the ajax list component. The ajax list component is used to
     * display many iPeer tables since it allows easy sorting and filtering
     * by columns. 
     *
     * Note that there was an attempt at filtering users by course enrolment
     * but the code for that does not work even in the master branch, so it 
     * looks like either the course filtering was broken somewhere along the
     * way or it was never completed.
     * 
     * @access public
     * @return void
     */
    function setUpAjaxList ()
    {
        // The columns to be shown in the table
        $columns = array(
          // Model, Columns, (Display Title), (Type Description)
          array(
            "User.role",
            __("Role", true),
            "6em",
            "map",
            array(
              "A" => "Admin",
              "I" => __("Instructor", true),
              "S" => __("Student", true)
            )
          ),
          array(
            "User.id",
            "",
            "",
            "hidden"
          ),
          array(
            "User.username",
            __("Username", true),
            "10em",
            "action",
            "View User"
          ),
          array(
            "User.full_name",
            __("Full Name", true),
            "15em",
            "string"
          ),
          array(
            "User.email",
            __("Email", true),
            "auto",
            "action",
            "Send Email"
          ),
        );

        // define action warnings
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

        // define right click menu actions
        $actions = array(
            //   display name, (warning shown), fixed parameters or Column ids
            array(__("View User", true),  "", "", "", "view", "User.id"),
            array(__("Send Email", true),  "", "", "emailer", "write", 'U', "User.id"),
            array(__("Edit User", true),  "", $actionRestrictions, "", "edit", "User.id"),
            array(__("Delete User", true),    $deleteUserWarning,   $actionRestrictions, "", "delete", "User.id"),
            array(__("Reset Password", true), $resetPassWarning,  $actionRestrictions, "", "resetPassword", "User.id")
        );

        $this->AjaxList->setUp($this->User, $columns, $actions, 
          "User.id", "User.username", array());
    }


    /**
     * ajaxList
     *
     * @param bool $pageForRedirect
     *
     * @access public
     * @return void
     */
    function ajaxList($pageForRedirect=null)
    {
        // Make sure the present user is not a student
        //$this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

    /**
     * Lists all users.
     *
     * Note that this is using the prototype based ajaxList. It should be
     * converted to the newer jquery based DataTable when possible. The only
     * problem with the DataTable migration is speed. The way DataTable works
     * is to convert an existing table with all the data into a paginated
     * table. If there are a lot of users, then this might be a bit slow.
     * E.g.: current production has about 10 000 users. But we might expect
     * some 60 000 users later. While the loading speed was fine for 10 000,
     * I have doubts about handling 60 000 users. The ajaxList code retrieves
     * users on demand, so does not have this problem.
     *
     * @param string $message
     *
     * @access public
     * @return void
     */
    function index($message='')
    {
        // Set the top message
        $this->set('message', $message);

        // Set up the basic static ajax list variables
        $this->setUpAjaxList();

        //If User role isn't Admin, display student as default
        if ($this->Auth->user('role')!='A') {
            $mapFilterSelections->{"User.role"} = "S";
            $this->AjaxList->setStateVariable("mapFilterSelections", $mapFilterSelections);
        }

        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());

        $this->set('can_add_user', $this->AccessControl->hasPermission('functions/user', 'create'));
        $this->set('can_import_user', $this->AccessControl->hasPermission('functions/user/import'));
        $fields = array('Enrolment');

        $tempVar=$this->User->getEnrolledStudents(1, $fields);
    }

  /**
   * Display a list of users enrolled in a given course. 
   *
   * Note that this uses a different listing method than the index. The index
   * uses the old prototype based ajaxList, this one uses a newer jquery
   * based DataTable. The switch was done mostly because the ajaxList code
   * to filter based on class wasn't working and a long look at the ajaxList
   * code base produced only puzzlement. As we want to move off of prototype
   * and to jquery anyways, it was easier to just rewrite this part.
   *
   * @param mixed $course - the course id to list users for
   *
   * @access public
   * @return void
   */
  function goToClassList($course) {
    $classStudents = array(); // holds all the students enrolled in this course
    $classInstructors = array(); // holds instructors for this course
    $classList = array(); // holds all users in this course for display in view

    // get the instructors
    $classInstructors = $this->User->find(
      'all',
      array(
        'conditions' => array('Course.id' => $course),
      )
    );
    // get the students
    $classStudents = $this->User->find(
      'all',
      array(
        'conditions' => array('Enrolment.id' => $course),
      )
    );

    // put only the data needed for display into classList
    // TODO role based data retrival restrictions
    foreach ($classInstructors as $user) {
      $tmp = array();
      $tmp['id'] = $user['User']['id'];
      $tmp['Role'] = 'Instructor';
      $tmp['Username'] = $user['User']['username'];
      $tmp['Full Name'] = $user['User']['first_name'] .' '. 
        $user['User']['last_name'];
      $tmp['Email'] = $user['User']['email'];
      $classList[] = $tmp;
    }
    foreach ($classStudents as $user) {
      $tmp = array();
      $tmp['id'] = $user['User']['id'];
      $tmp['Role'] = 'Student';
      $tmp['Username'] = $user['User']['username'];
      $tmp['Full Name'] = $user['User']['first_name'] .' '. 
        $user['User']['last_name'];
      $tmp['Email'] = $user['User']['email'];
      $classList[] = $tmp;
    }
    $this->set('classList', $classList);
  }

    /**
     * __processForm
     *
     * @access protected
     * @return void
     */
    function __processForm()
    {
        if (!empty($this->data)) {
            $this->Output->filter($this->data);//always filter
            if (!empty($roleNum)) {
                $roleNum = $this->data['Role']['Role'];

                $role = $this->Role->getRoleByRoleNumber($roleNum[0]);
                $this->data['User']['role'] = $role;
            }
            //Save Data
            if ($this->data = $this->User->save($this->data)) {
                $this->data['User']['id'] = $this->User->id;
                return true;
            } else {
                $validationErrors = $this->User->invalidFields();
                $errorMsg = '';
                foreach ($validationErrors as $error) {
                    $errorMsg = $errorMsg."\n".$error;
                }
                $this->Session->setFlash(__('Failed to save.</br>', true).$errorMsg);

            }
        }

        return false;
    }


    /**
     * determineIfStudentFromThisData
     * This function is needed since the $this->data looks different between
     * initial page render, and postback page render. Needs a better implementation.
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function determineIfStudentFromThisData($data)
    {
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
                if ($value == 4) {
                    // 4 means student
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * getSimpleEntrollmentLists
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function getSimpleEntrollmentLists($id)
    {
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


    /**
     * setUpCourseEnrollmentLists
     *
     * @param mixed $id         id
     * @param bool  $thisCourse this course
     *
     * @access public
     * @return void
     */
    function setUpCourseEnrollmentLists($id, $thisCourse = null)
    {
        $data = $this->getSimpleEntrollmentLists($id);
        $this->set("simpleEnrolledList", $data['simpleEnrolledList']);
        $this->set("simpleCoursesList", $data['simpleCoursesList']);
    }


    /**
     * processEnrollmentListsPostBack
     *
     * @param mixed $params params
     * @param mixed $userId user id
     *
     * @access public
     * @return void
     */
    function processEnrollmentListsPostBack($params, $userId)
    {
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
            if (!in_array($value, $simpleEnrolledList) &&
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


    /**
     * view
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function view($id)
    {
        $this->AccessControl->check('functions/user', 'read');

        if (!is_numeric($id) || !($this->data = $this->User->findUserByid($id))) {
            $this->Session->setFlash('Invalid user ID.');
            $this->redirect('index');
        }

        $roles = $this->User->getRoles($id);
        
        if (!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('ViewUser', $roles)) {
            $this->Session->setFlash(__('You do not have permission to view this user.', true));
            $this->redirect('index');
        }

        $this->set('title_for_layout', __('View User', true));
        $this->set('user', $this->data);
    }


  /**
   * Add a user to iPeer.
   *
   * Note that enrolment as admins or superadmins is not working right now.
   * 
   * @param course_id - will automatically enroll the user in this course.
   * @access public
   * @return void
   */
  public function add($course_id = null) {
    $this->set('title_for_layout', 'Add User');

    // get the courses that this user is instructor in
    // TODO need to implement separate behaviours for admins and superadmins
    // superadmins should have access to all courses regardless
    // admins should have access only to their department
    $user = $this->User->find(
      'first', 
      array('conditions' => array('id' => $this->Auth->user('id')))
    );
    $courses = $user['Course'];
    $coursesOptions = array();
    foreach ($courses as $course) {
      $coursesOptions[$course['id']] = $course['course'].' - '.$course['title'];
    }
    $this->set('coursesOptions', $coursesOptions);
    $this->set('coursesSelected', $course_id);

    // get the roles assignable to the new user 
    // TODO Update for admin/superadmin cases once access control done
    // basically, superadmin is 1, and has the highest access
    // student is 4, and has the lowest access.
    // so the roles you are allowed to assign are any number
    // greater than your role id.
    $roles = $this->Role->find('list');
    $highestRole = 99999999;
    $roleDefault = null;
    foreach($user['Role'] as $role) {
      if ($role['id'] < $highestRole) {
        $highestRole = $role['id'];
      }
    }
    foreach($roles as $key => $val) {
      if ($val == 'student') {
        $roleDefault = $key;
      }
      if ($key <= $highestRole) {
        unset($roles[$key]);
      }
    }
    $this->set('roleOptions', $roles);
    $this->set('roleDefault', $roleDefault);

    // validate the form data
    if ($this->data) {
      $this->User->set($this->data);
      if (!$this->User->validates()) {
        $this->Session->setFlash('Unable to validate data.');
        $errors = $this->User->invalidFields();
        // note we're counting on automagic to persist form data so the user
        // don't have to fill everything back in
        return;
      }
    }

    // save the data which involves:
    // create the user entry
    // create the rolesusers entry
    // create the enrolment entry depending on if instructor or student
    if ($this->data) {
      // To properly enrol the user in the course, we're going to use some
      // cakephp dark magic by massaging the data in such a way that we
      // get cakephp to save to the right related models.
      // * Course-Instructor relations are stored by the UserCourses table.
      // * Course-Student relations are stored by the UserEnrols table.
      // The relations as defined for some reason puts these related tables
      // deep in the array.
      $coursesEnrolled = array();
      foreach ($this->data['Courses']['id'] as $id) {
        $wantedRole = $this->data['Role']['RolesUser']['role_id'];
        if ($wantedRole < $highestRole) {
          // trying to create a user with higher access than yourself
          $this->Session->setFlash('Invalid role permission');
          return;
        }
        if ($wantedRole == $roleDefault) {
          // we should add this user as a student
          $this->data['Enrolment'][]['UserEnrol']['course_id'] = 
            $id;
        }
        else {
          // we should add this user as an instructor
          $this->data['Course'][]['UserCourse']['course_id'] = 
            $id;
        }
        // For email, store a mapping of enrolled course id to name
        $coursesEnrolled[$id] = $coursesOptions[$id];
      }
      // Remove the unneeded intermediate data
      unset($this->data['Courses']);

      // Now we add in the password
      $password = $this->PasswordGenerator->generate();
      $this->data['User']['password'] = $this->Auth->password($password);

      // Now we actually attempt to save the data
      if ($this->User->save($this->data)) {
        // Success!
        $message = "User sucessfully created! 
          <br />Password: <b>$password</b> <br />";
        if ($this->data['User']['send_email_notification'] &&
          $this->data['User']['email']
        ) {
          if ($this->sendAddUserEmail(
            $this->Auth->user('email'), 
            $this->data['User']['email'], 
            $this->data['User']['username'],
            $password,
            $this->data['User']['first_name'].' '.$this->data['User']['last_name'],
            $coursesEnrolled
          )) {
            # email notification successful
            $message .= "Email notification sent.";
          }
          else {
            # email notification failed
            $message .= "<div class='red'>User created but unable to send
              email notification.</div>";
          }
        }
        $this->Session->setFlash($message, 'good');
        return;
      }
      else {
        // Failed
        $this->Session->setFlash("Error while trying to save, try again.");
        return;
      }
    }
  }


    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function edit($id)
    {
        // Ensure that the id is valid
        if (!is_numeric($id)) {
            $this->cakeError('error404');
        }

        $this->AccessControl->check('functions/user/' . $this->User->getRoleById($id), 'update');
        if (empty($this->data)) {
            $this->User->id = $id;
            $this->data = $this->User->read();
        } else {
            $this->data['User']['id'] = $id;
            if ($this->__processForm()) {
                // Process the course changes list
                $this->processEnrollmentListsPostBack($this->params, $id);
                // Set message for user.
                $this->Session->setFlash(__('Changes are saved.', true));
            } else {
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
          if (!empty($this->params['form']['course_ids'])) {
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
            if (!isset($simpleEnrolledList[$value])) {
              $this->UserEnrol->insertCourses($data2save['User']['id'], array($value));
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

    /**
     * editProfile
     *
     * @access public
     * @return void
     */
    function editProfile()
    {
        // No security checks here, since we're editing the logged-in user
        $id = $this->Auth->user('id');
        $this->set('viewPage', false);
        if (!empty($this->data)) {
            $this->data['User']['id'] = $id;

            if (!empty($this->data['User']['tmp_password'])) {
                if (md5($this->data['User']['old_password']==$this->Auth->user('password'))) {
                    if ($this->data['User']['tmp_password']==$this->data['User']['confirm_password']) {
                        $this->data['User']['password'] = md5($this->data['User']['tmp_password']);
                    } else {
                        $this->Session->setFlash(__("Confirm password is wrong", true));
                        $this->redirect('editProfile/'.$id);
                    }
                } else {
                    $this->Session->setFlash(__("Old password is wrong", true));
                    $this->redirect('editProfile/'.$id);
                }
            } else {
                unset($this->data['User']['tmp_password']);
            }

            if ($this->__processForm()) {
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


    /**
     * delete
     *
     * @param mixed $id   id
     * @param bool  $type type
     *
     * @access public
     * @return void
     */
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
        if (!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('DeleteUser', $roles)) {
            $this->Session->setFlash(__('You do not have permission to delete the user.', true));
        } else {
            if ($this->User->delete($id)) {
                $this->Session->setFlash(__('Record is successfully deleted!', true));
            } else {
                $this->Session->setFlash(__('Delete failed!', true));
            }
        }

        $this->redirect($this->referer());
    }


    /**
     * drop
     *
     * @param mixed $id        id
     * @param mixed $course_id course id
     *
     * @access public
     * @return void
     */
    function drop($id, $course_id)
    {
        // Ensure that the id is valid
        if (!is_numeric($id) && !is_numeric($course_id)) {
            $this->cakeError('error404');
        }

        // check if current user has permission to delete this user
        // in case of the being deleted user has higher level role
        $roles = $this->User->getRoles($id);
        if (!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('DropUser', $roles)) {
            $this->Session->setFlash(__('You do not have permission to drop the user.', true));
        } else {
            if ($this->User->dropEnrolment($id, $course_id)) {
                $this->Session->setFlash(__('The user is dropped from this course!', true));
            } else {
                $this->Session->setFlash(__('Drop failed!', true));
            }
        }

        $this->redirect('index');
    }


    /**
     * checkDuplicateName
     *
     * @access public
     * @return void
     */
    function checkDuplicateName()
    {
        if (!$this->RequestHandler->isAjax()) {
            $this->cakeError('error404');
        }
        $this->layout = 'ajax';
        $this->autoRender = false;

        $isUserEnrol = false;
        $sFound = $this->User->getByUsername($this->data['User']['username']);

        /*if (!empty($sFound)) {
             foreach ($sFound['UserEnrol'] as $uEnrol) {
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


    /**
     * resetPassword
     *
     * @param mixed $user_id user id
     * @param bool  $render  render
     *
     * @access public
     * @return void
     */
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
        if (!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('PasswordReset', $roles)) {
            $this->Session->setFlash(__('You do not have permission to drop the user.', true));
        }

        //General password
        $tmp_password = $this->PasswordGenerator->generate();
        $user_data['User']['tmp_password'] = $tmp_password;
        $user_data['User']['password'] =  md5($tmp_password);
        $user_data['User']['id'] =  $user_id;

        //Save Data
        if ($user = $this->User->save($user_data, true, array('password'))) {
            $message = __("Password successfully reset. ", true);
            $this->User->set('id', $user_id);

            // send email to user
            $this->set('user_data', $user_data);
            if ($this->_sendEmail('', 'Reset Password', $this->Auth->user('email'), $user_data['User']['email'], 'resetPassword')) {
                //if ($this->_sendEmail( $to, $from, $subject, $email_msg )) {
                $message .= __("Email has been sent. ", true);
            } else {
                if (!isset($user_data['User']['email']) || strlen($user_data['User']['email']) < 1) {
                    $message .= __('No destination email address. ', true);
                }
                $message .= __("Email was <u>not</u> sent to the user.", true) . $this->Email->smtpError;
            }
            $this->Session->setFlash($message);
            $this->redirect($this->referer());
        } else {
            //Get render page according to the user type
            $this->redirect($this->referer());
        }
    }

    /**
     * import
     *
     * @access public
     * @return void
     */
    function import()
    {
        $this->set('title_for_layout', __('Import Students From Text (.txt) or CSV File (.csv)', true));
        if (isset($this->params['form']['course_id'])) {
            $this->Session->write('ipeerSession.courseId', $this->params['form']['course_id']);
        }

        $courseList = User::getMyCourseList();
        $this->set('courseList', $courseList);
        $this->set('courseParams', array('courseList' => $courseList, 'id_prefix' => 'import'));
        $this->set('user_type', 'S');
    }


    /**
     * importPreview
     * TODO: add import preview
     *
     *
     * @access public
     * @return void
     */
    function importPreview()
    {
    }


    /**
     * importFile
     *
     * @access public
     * @return void
     */
    function importFile()
    {
        $this->autoRender = false;

        if (!empty($this->data)) {
            if ($this->FileUpload->success) {
                $uploadFile = $this->FileUpload->uploadDir.DS.$this->FileUpload->finalFile;
            } else {
                $this->Session->setFlash($this->FileUpload->showErrors());
                $this->redirect('import');
            }
        }

        $data = Toolkit::parseCSV($uploadFile);
        $result = $this->User->addUserByArray($data, true);

        if (!$result) {
            $this->Session->setFlash($this->User->showErrors());
            $this->redirect('import');
        }

        $enrolResult = $this->Course->enrolStudents($this->User->insertedIds, $this->data['User']['course_id']);

        $this->FileUpload->removeFile($uploadFile);

        $this->set('data', $result);

        //$this->set('userRole', $this->params['data']['User/role']);
        $this->render('userSummary');
    }


    // unused function
/*    function getQueryAttribute($displayUserType = null, $courseId = null, $is_count = false)
    {
        $attributes = array('fields'    => 'User.id, User.username, User.role, User.first_name, User.last_name, User.email, User.created, User.creator_id, User.modified, User.updater_id',
                            'condition' => array(),
                            'joinTable' => array());
        $joinTable = array();

        //if (isset($this->Session->read('ipeerSession.courseId'))) {
        if (!empty($displayUserType)) {
            $attributes['condition'][] = 'User.role = "'.$displayUserType.'"';
        }


        if ('S' == $displayUserType) {
          $attributes['fields'] .= ', COUNT(UserEnrol.id) as enrol_count';

          if ($courseId == -1) {
            //Get unassigned student
            $attributes['condition'][] = 'UserEnrol.user_id IS NULL';
            $joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id = UserEnrol.user_id');
          } else if (is_numeric($courseId)) {
            $attributes['condition'][] = 'UserEnrol.course_id = ' . $courseId;
            if ($is_count) {
              $attributes['condition'][] = 'User.id = UserEnrol.user_id';
              $joinTable = array(', user_enrols as UserEnrol');
            } else {
              $joinTable = array(' LEFT JOIN user_enrols as UserEnrol ON User.id = UserEnrol.user_id');
            }
          } else {
            if (!$is_count) {
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

    /**
     * update
     *
     * @param string $attributeCode  attribute code
     * @param string $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function update($attributeCode='', $attributeValue='')
    {
        if ($attributeCode != '' && $attributeValue != '') {
            //check for empty params
            $this->data = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
        }
    }


    /**
     * nonRegisteredCourses
     *
     * @param mixed $user_id        user id
     * @param bool  $requester      requester
     * @param bool  $requester_role requester role
     *
     * @access public
     * @return void
     */
    function nonRegisteredCourses($user_id, $requester = null, $requester_role = null)
    {
        return $this->Course->findNonRegisteredCoursesList($user_id, $requester, $requester_role);
    }



    /**
     * __loadFromSession
     * Loads the rdAuth data from the Session.
     *
     *
     * @access protected
     * @return void
     */
    function __loadFromSession()
    {
        if ($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession')) {
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
     * __setSessionData
     * Updates the user session from the user data passed, and loads it into this rdAuth object.
     *
     * @param mixed $userData
     *
     * @access protected
     * @return void
     */
    function __setSessionData($userData)
    {
        $this->Session->write('ipeerSession.id', $userData['id']);
        $this->Session->write('ipeerSession.username', $userData['username']);
        $this->Session->write('ipeerSession.fullname', $userData['last_name'].' '.$userData['first_name']);
        //$this->Session->write('ipeerSession.role', $userData['Role']);
        $this->Session->write('ipeerSession.email', $userData['email']);
        //return $this->__loadFromSession();
    }


  /**
   * Helper function to send an email notification about to a user about
   * being added to iPeer.
   *
   * @param $from - who the email is from
   * @param $to - where the email is going to
   * @param $username - the username given to the user
   * @param $password - the password to the username
   * @param $name - first name + last name
   * @param $courses - the courses that the user is enrolled in
   *
   * @return true if successful, false otherwise
   * */
  private function sendAddUserEmail($from, $to, $username, 
    $password, $name, $courses
  ) {
    // prep variables used by the email template layout for addUser
    $this->set('name', $name);
    $this->set('username', $username);
    $this->set('password', $password);
    $this->set('courses', $courses);
    $this->set('siteurl', $this->sysContainer->getParamByParamCode('system.absolute_url'));

    // call send mail 
    $subject = "iPeer Account Creation";
    $template = "addUser";

    return $this->_sendEmail("", $subject, $from, $to, $template);
  }

}
