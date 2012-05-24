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
        if ($this->Auth->isAuthorized()) {
            // after login stuff
            $this->AccessControl->getPermissions();
            $this->User->loadRoles(User::get('id'));
            //TODO logging!
        }
        $this->redirect($this->Auth->redirect());
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
    function _setUpAjaxList ()
    {
        // The columns to be shown in the table
        $columns = array(
            // Model, Columns, (Display Title), (Type Description)
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

        if (!User::hasRole('superadmin') && !User::hasRole('admin')) {
            $actionRestrictions = array(
                "User.role" => array (
                    "S" => true,
                    "!default" => false));
        } else {
            $actionRestrictions = "";
        }

        $joinTables =  array(
            array (
                // GUI aspects
                "id" => "Role.id",
                "description" => __("Show role:", true),
                // The choise and default values
                "list" => $this->AccessControl->getEditableRoles(),
                "default" => 0,
            )
        );

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
            "User.id", "User.username", $joinTables);
    }


    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Make sure the present user is not a student
        //$this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->_setUpAjaxList();
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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to view users.');
            $this->redirect('/home');
        }

        // Set the top message
        $this->set('message', $message);

        // Set up the basic static ajax list variables
        $this->_setUpAjaxList();

        //If User role isn't Admin, display student as default
        if (!User::hasRole('superadmin') && !User::hasRole('admin')) {
            $mapFilterSelections->{"User.role"} = "S";
            $this->AjaxList->setStateVariable("mapFilterSelections", $mapFilterSelections);
        }

        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());

        $this->set('can_add_user', User::hasPermission('functions/user', 'create'));
        $this->set('can_import_user', User::hasPermission('functions/user/import'));
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

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(
                'You do not have permission to view users.', true);
            $this->redirect('/home');
        }

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
     * _setUpCourseEnrollmentLists
     *
     * @param mixed $id - user id
     *
     * @access public
     * @return void
     */
    function _setUpCourseEnrollmentLists($id)
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
                is_numeric($value)
            ) {
                $this->User->registerEnrolment($userId, $value);
            }
        }

        // Take them out of the de-selected courses
        foreach ($simpleEnrolledList as $key => $value) {
            if (!in_array($value, $checkedCourseList) &&
                is_numeric($userId) &&
                is_numeric($value)
            ) {
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
        if (!is_numeric($id)) {
            $this->Session->setFlash('Invalid user ID.');
            $this->redirect('index');
        }

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(
                'You do not have permission to view users.', true);
            $this->redirect('/home');
        }

        $role = $this->User->getRoleName($id);
        if (!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash('You do not have permission to view this user.', true);
            $this->redirect('index');
        }

        if(!($this->data = $this->User->findUserByid($id))) {
            $this->Session->setFlash('Invalid user ID.');
            $this->redirect('index');
        }

        $this->set('title_for_layout', __('View User', true));
        $this->set('user', $this->data);
    }

    /**
     * Set the variables needed to preselect values in the select element on
     * the add user form.
     *
     * @param int $courseId - automatically enroll the user in this course.
     *
     * @return void
     * */
    private function _initAddFormEnv($courseId) {
        $this->_initFormEnv();
        $roleDefault = $this->Role->getDefaultId();
        $this->set('roleDefault', $roleDefault);
        $this->set('coursesSelected', $courseId);
    }

    /**
     * Set the variables needed to preselect values in the select element on
     * the edit user form.
     *
     * @param int $userId - user being edited
     *
     * @return void
     * */
    private function _initEditFormEnv($userId) {
        $this->_initFormEnv();

        // set this user's role
        $user = $this->User->findById($userId);
        $this->set('roleDefault', $user['Role'][0]['id']);
        // set the courses that this user is already in
        $coursesId = array();
        foreach ($user['Course'] as $course) {
            $coursesId[] = $course['id'];
        }
        foreach ($user['Enrolment'] as $course) {
            $coursesId[] = $course['id'];
        }
        $this->set('coursesSelected', $coursesId);
    }

    /**
     * Set the variables needed to fill in form elements on the add and edit
     * user forms.
     *
     * @return void
     * */
    private function _initFormEnv() {
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
            $coursesOptions[$course['id']] =
                $course['course'].' - '.$course['title'];
        }
        $this->set('coursesOptions', $coursesOptions);

        $this->set('roleOptions', $this->AccessControl->getEditableRoles());
        $this->set('faculties', $this->User->Faculty->find('list', 
            array('order' => 'Faculty.name ASC')));
    }

    /**
     * Convert course selection from Form submit data into a format
     * that CakePHP will understand to properly enrol users in the right
     * courses.
     *
     * - Course-Instructor relations are stored by the UserCourses table.
     *
     * - Course-Student relations are stored by the UserEnrols table.
     * The relations as defined for some reason puts these related tables
     * deep in the array.
     *
     * @param array $courses - the form data from the course selection
     * element
     * @param int $wantedRole - the role id of the role of the user being
     * created
     *
     * @return the arrays to be added to $this->data
     * */
    private function _convertCourseEnrolment($courses, $wantedRole) {
        $ret = array();
        $roleDefault = $this->Role->getDefaultId();
        $ownRole = $this->User->getRoleId($this->Auth->user('id'));
        foreach ($courses as $id) {
            if ($wantedRole < $ownRole) {
                // trying to create a user with higher access than yourself
                $this->Session->setFlash('Invalid role permission');
                return;
            }
            if ($wantedRole == $roleDefault) {
                // we should add this user as a student
                $ret['Enrolment'][]['UserEnrol']['course_id'] = $id;
            }
            else {
                // we should add this user as an instructor
                $ret['Course'][]['UserCourse']['course_id'] = $id;
            }
        }

        return $ret;
    }

    /**
     * Add a user to iPeer.
     *
     * Note that enrolment as admins or superadmins is not working right now.
     *
     * @param int $courseId - will automatically enroll the user in this course.
     *
     * @access public
     * @return void
     */
    public function add($courseId = null) {
        $this->set('title_for_layout', 'Add User');

        if(!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to add users', true);
            $this->redirect('/home');
        }

        // set up the course and role variables to fill the form elements
        $this->_initAddFormEnv($courseId);

        // save the data which involves:
        if ($this->data) {
            // create the enrolment entry depending on if instructor or student
            // and also convert it into a CakePHP dark magic friendly format
            $enrolments = $this->_convertCourseEnrolment(
                $this->data['Courses']['id'],
                $this->data['Role']['RolesUser']['role_id']
            );
            $this->data = array_merge($this->data, $enrolments);

            // Now we add in the password
            $password = $this->PasswordGenerator->generate();
            $this->data['User']['password'] = $this->Auth->password($password);

            // Now we actually attempt to save the data
            if ($ret = $this->User->save($this->data)) {
                // Success!
                $message = "User sucessfully created!
                    <br />Password: <b>$password</b> <br />";
                $message .=
                    $this->_sendAddUserEmail($ret, $password, $enrolments);
                $this->Session->setFlash($message, 'good');
            }
            else {
                // Failed
                $this->Session->setFlash("Unable to create user.");
            }
        }
    }


    /**
     * Given a user id, edit the information for that user
     *
     * @param int $userId - the user being edited
     *
     * @access public
     * @return void
     */
    public function edit($userId) {
        $this->set('title_for_layout', 'Edit User');

        $role = $this->User->getRoleName($userId);

        if(!User::hasPermission('functions/user')) {
            $this->Session->setFlash(
                'You do not have permission to edit users.', true);
            $this->redirect('/home');
        }

        if(!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash(
                'You do not have permission to edit this user.', true);
            $this->redirect('index');
        }

        // stop if don't have required params
        if (!$userId && empty($this->data)) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect($this->referer());
        }

        // save the data which involves:
        if ($this->data) {
            // create the enrolment entry depending on if instructor or student
            // and also convert it into a CakePHP dark magic friendly format
            $enrolments = $this->_convertCourseEnrolment(
                $this->data['Courses']['id'],
                $this->data['Role']['RolesUser']['role_id']
            );
            $this->data = array_merge($this->data, $enrolments);

            // Now we actually attempt to save the data
            if ($this->User->save($this->data)) {
                // Success!
                $message = "User sucessfully updated! <br />";
                $this->Session->setFlash($message, 'good');
            }
            else {
                // Failed
                $this->Session->setFlash("Unable to update user.");
            }
            // set up the course and role variables to fill the form elements
            // only load this after save, or we won't get the correct values
            $this->_initEditFormEnv($userId);
            return;
        }

        // set up the course and role variables to fill the form elements
        $this->_initEditFormEnv($userId);

        // not saving, need to load data for forms to fill in
        $this->data = $this->User->read(null, $userId);
    }

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
                    $this->Session->setFlash((__("Your Profile Has Been Updated Successfully.", true)."<br /><br />" .
                        "<a href='../../home/'>".__('Go to your iPeer Home page.', true)."</a><br />"), 'good');
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
     * @param mixed $id - user id to delete
     *
     * @access public
     * @return void
     */
    function delete($id)
    {
        $role = $this->User->getRoleName($id);

        if(!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to delete users');
            $this->redirect('/home');
        }

        // check if current user has permission to delete this user
        // in case of the being deleted user has higher level role
        if(!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash('You do not have permission to delete this user');
            $this->redirect('index');
        }

        // Ensure that the id is valid
        if (!is_numeric($id)) {
            $this->cakeError('error404');
        }

        if ($this->User->delete($id)) {
            $this->Session->setFlash(__('Record is successfully deleted!', true), 'good');
        } else {
            $this->Session->setFlash(__('Delete failed!', true));
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

        $sFound = $this->User->getByUsername($this->data['User']['username']);

        return ($sFound) ? __('Username "', true).$this->data['User']['username'].__('" already exists.', true) : '';
    }


    /**
     * resetPassword
     *
     * @param mixed $user_id user id
     *
     * @access public
     * @return void
     */
    function resetPassword($user_id)
    {
        $role = $this->User->getRoleName($user_id);

        if(!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to reset passwords', true);
            $this->redirect('/home');
        }

        if(!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash('You do not have permission to reset the password for this user.', true);
            $this->redirect('index');
        }

        // Read the user
        $user_data = $this->User->findUserByid($user_id, array('contain' => false));

        if (empty($user_data)) {
            $this->Session->setFlash(__('User Not Found!', true));
            $this->redirect("index");
        }

        //General password
        $tmp_password = $this->PasswordGenerator->generate();
        $user_data['User']['tmp_password'] = $tmp_password;
        $user_data['User']['password'] =  md5($tmp_password);
        $user_data['User']['id'] =  $user_id;

        //Save Data
        if ($this->User->save($user_data, true, array('password'))) {
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
                $message .= __("Email was <u>not</u> sent to the user. ", true) . $this->Email->smtpError;
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

        $this->Course->enrolStudents($this->User->insertedIds, $this->data['User']['course_id']);

        $this->FileUpload->removeFile($uploadFile);

        $this->set('data', $result);

        $this->render('userSummary');
    }


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
     * @param string $user - the return value from $this->User->save,
     * which is basically $this->data, contains all the form values
     * @param string $password - the password to the username
     * @param string $enrolments - the courses that the user is enrolled in
     *
     * @return Status message indicating success or error, empty string
     * if no email notification
     * */
    private function _sendAddUserEmail($user, $password, $enrolments) {
        if (!($user['User']['send_email_notification'] &&
            $user['User']['email'])
        ) {
            return "";
        }
        // get username and address
        $from = $this->Auth->user('email');
        $to = $user['User']['email'];
        $username = $user['User']['username'];
        $name = $user['User']['first_name'].' '.$user['User']['last_name'];

        // this means only students will get a list of courses they're
        // enroled in, since instructors are stored in another array
        foreach ($enrolments['Enrolment'] as $course) {
            $cid = $course['UserEnrol']['course_id'];
            $courses[] = $this->Course->field('course', array('id' => $cid)) .
                ' - ' . $this->Course->field('title', array('id' => $cid));
        }

        // prep variables used by the email template layout for addUser
        $this->set('name', $name);
        $this->set('username', $username);
        $this->set('password', $password);
        $this->set('courses', $courses);
        $this->set('siteurl',
            $this->sysContainer->getParamByParamCode('system.absolute_url'));

        // call send mail
        $subject = "iPeer Account Creation";
        $template = "addUser";

        if ($this->_sendEmail("", $subject, $from, $to, $template)) {
            return "Email notification sent.";
        }
        return "<div class='red'>User created but unable to send email
            notification: ". $this->Email->smtpError ."</div>";
    }

}
