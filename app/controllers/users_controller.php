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
    public $uses = array('User', 'UserEnrol', 'Personalize', 'Course',
        'SysParameter', 'Role', 'Group', 'UserFaculty',
        'Department', 'CourseDepartment', 'OauthClient', 'OauthToken',
        'UserCourse', 'UserTutor', 'Faculty', 'UserFaculty'
    );
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

        $allowTypes = array(
            'text/plain', 'text/csv', 'application/csv',
            'application/csv.ms-excel', 'application/octet-stream',
            'text/comma-separated-values', 'text/anytext');
        $this->FileUpload->allowedTypes(array(
            'txt' => null,
            'csv' => null,
        ));
        $this->FileUpload->uploadDir('../tmp');
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
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
        );

        if (User::hasPermission('functions/viewemailaddresses')) {
            $email = array(
                "User.email",
                __("Email", true),
                "auto",
                "action",
                "Send Email"
            );

            array_push($columns, $email);
        }

        // define action warnings
        $deleteUserWarning = __("Delete this user. Irreversible. Are you sure?", true);
        $resetPassWarning = __("Resets user Password. Are you sure?", true);

        $actionRestrictions = "";

        $viewableRoles = $this->AccessControl->getViewableRoles();
        $joinTables =  array(
            array (
                // GUI aspects
                "id" => "Role.id",
                "description" => __("Show role:", true),
                // The choise and default values
                "list" => $viewableRoles,
                "default" => 0,
            ),
        );

        $extraFilters = array();
        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = array();
        } elseif (User::hasPermission('controllers/departments')) {
            // faculty admins, filter out the admins and instructors from other department/faculty
            // stupid cakephp doesn't support double habtm query. So using raw query
            $conditions = array();
            $faculties = $this->UserFaculty->find('all', array(
                'conditions' => array('user_id' => User::get('id')),
                'fields' => array('faculty_id'),
            ));
            $facultyIds = Set::extract($faculties, '/UserFaculty/faculty_id');
            $query = "SELECT User.id FROM `users` AS `User` LEFT JOIN `user_faculties` AS `UserFaculty` ON (`UserFaculty`.`user_id` = `User`.`id`) LEFT JOIN `faculties` AS `Faculty` ON (`Faculty`.`id` = `UserFaculty`.`faculty_id`) INNER JOIN `roles_users` AS `RolesUser` ON (`RolesUser`.`user_id` = `User`.`id`) INNER JOIN `roles` AS `Role` ON (`Role`.`id` = `RolesUser`.`role_id`) WHERE ";
            foreach ($viewableRoles as $id => $role) {
                if ($role == 'admin' || $role == 'instructor') {
                    $conditions[] = 'Role.id = '.$id.' AND Faculty.id IN ('.join(',', $facultyIds).')';
                } else {
                    $conditions[] = 'Role.id = '.$id;
                }
            }
            $result = $this->User->query($query.join(' OR ', $conditions));
            $userIds = Set::extract($result, '/User/id');
            $extraFilters = array('User.id' => $userIds);
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
            "User.id", "User.username", $joinTables, $extraFilters);
    }


    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
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
        // to filter out students and tutors
        if (!User::hasPermission('functions/user/index')) {
            $this->Session->setFlash('You do not have permission to view users.');
            $this->redirect('/home');
        }

        // Set the top message
        $this->set('message', $message);

        // Set up the basic static ajax list variables
        $this->_setUpAjaxList();

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
     * @param mixed $courseId the course id to list users for
     *
     * @access public
     * @return void
     */
    function goToClassList($courseId)
    {
        // check whether the course exists
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getCourseWithEnrolmentById($courseId);

        $this->set('classList', $course['Enrol']);
        $this->set('courseId', $courseId);

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Students', true)));
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
            //Save Data
            if ($this->data = $this->User->save($this->data)) {
                $this->data['User']['id'] = $this->User->id;
            } else {
                $validationErrors = $this->User->invalidFields();
                $errorMsg = '';
                foreach ($validationErrors as $error) {
                    $errorMsg = $errorMsg."\n".$error;
                }
                $this->Session->setFlash(__('Failed to save.</br>', true).$errorMsg);
                return false;
            }

            if (isset($this->data['OauthClient'])) {
                if (!($this->OauthClient->saveAll($this->data['OauthClient']))) {
                    $this->Session->setFlash(__('Failed to save.</br>', true));
                    return false;
                }
            }

            if (isset($this->data['OauthToken'])) {
                if (!($this->OauthToken->saveAll($this->data['OauthToken']))) {
                    $this->Session->setFlash(__('Failed to save.</br>', true));
                    return false;
                }
            }
        }

        return true;
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
                if ($value == 5) {
                    // 5 means student
                    return true;
                }
            }
        }

        return false;
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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(__('Error: You do not have permission to view users', true));
            $this->redirect('/home');
        }

        if (!($this->data = $this->User->findById($id))) {
            $this->Session->setFlash(__('Error: This user does not exist', true));
            $this->redirect($this->referer());
        }

        $role = $this->User->getRoleName($id);
        if (!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash(__('Error: You do not have permission to view this user', true));
            $this->redirect($this->referer());
        }

        //super admins and faculty admins can view all users
        if (!User::hasPermission('controllers/departments')) {
            // instructors
            $courses = User::getMyCourseList();
            $models = array('UserCourse', 'UserTutor', 'UserEnrol');
            $accessibleUsers = array();

            // generate a list of instructors, tutors, and students the user has access to
            foreach ($models as $model) {
                $users = $this->$model->find('list', array(
                    'conditions' => array('course_id' => array_keys($courses)),
                    'fields' => array('user_id')
                ));
                $accessibleUsers = array_merge($accessibleUsers, $users);
            }

            if (!in_array($id, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to view this user', true));
                $this->redirect($this->referer());
            }
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
    private function _initFormEnv()
    {
        $user = $this->User->find(
            'first',
            array('conditions' => array('id' => $this->Auth->user('id')))
        );
        // get the courses that this user is allowed access to
        $coursesOptions = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        asort($coursesOptions);
        /*if (User::hasPermission('functions/user/superadmin')) {
            // superadmins should have access to all courses regardless
            $coursesOptions = $this->Course->find('list');
        } else if (User::hasPermission('functions/user/admin')) {
            // admins should have access only in their faculty
            // get user's faculties
            $uf = $this->UserFaculty->findAllByUserId($this->Auth->user('id'));
            // based on the faculties, get the user's departments
            $ret = $this->Department->getByUserFaculties($uf);
            // based on the departments, get the user's allowed courses
            foreach ($ret as $department) {
                $courses = $this->CourseDepartment->findAllByDepartmentId(
                    $department['Department']['id']);
                foreach ($courses as $course) {
                    $cid = $course['CourseDepartment']['course_id'];
                    $coursesOptions[$cid] =
                        $this->Course->field('full_name', array('id' => $cid));
                }
            }
        } else if (User::hasPermission('functions/user/instructor')) {
            // instructors can only access courses they teach
            $courses = $user['Course'];
            foreach ($courses as $course) {
                $coursesOptions[$course['id']] = $course['full_name'];
            }
        }*/
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
        $ret = array('Enrolment' => array());
        $roleDefault = $this->Role->getDefaultId();
        $tutorRole = $this->Role->field('id', array('name'=>'tutor'));
        $ownRole = $this->User->getRoleId($this->Auth->user('id'));
        if (!isset($courses)) {
            return $ret;
        }
        foreach ($courses as $id) {
            if ($wantedRole < $ownRole) {
                // trying to create a user with higher access than yourself
                $this->Session->setFlash('Invalid role permission');
                return;
            }
            if ($wantedRole == $roleDefault) {
                // we should add this user as a student
                $ret['Enrolment'][]['UserEnrol']['course_id'] = $id;
            } else if ($wantedRole == $tutorRole) {
                // we should add this user as a tutor
                $ret['Tutor'][]['UserTutor']['course_id'] = $id;
            } else {
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
    public function add($courseId = null)
    {
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('Error: You do not have permission to add users', true);
            $this->redirect('/home');
        }

        // set up the course and role variables to fill the form elements
        $this->_initAddFormEnv($courseId);
        $this->set('courseId', $courseId);

        // save the data which involves:
        if ($this->data) {
            $submit = $this->data['Form']['save'];
            unset($this->data['Form']);
            // create the enrolment entry depending on if instructor or student
            // and also convert it into a CakePHP dark magic friendly format
            if (!empty($this->data['Courses']['id'])) {
                $enrolments = $this->_convertCourseEnrolment(
                    $this->data['Courses']['id'],
                    $this->data['Role']['RolesUser']['role_id']
                );
            } else {
                $enrolments = array('Enrolment' => array());
            }

            $this->data = array_merge($this->data, $enrolments);

            // Now we add in the password
            $password = $this->PasswordGenerator->generate();
            $this->data['User']['password'] = $this->Auth->password($password);

            // Now we actually attempt to save the data
            if ($ret = $this->User->save($this->data)) {
                // if the user added is a super admin
                if ($ret['Role']['RolesUser']['role_id'] == '1') {
                    $userId = $this->User->getLastInsertID();
                    $faculties = $this->Faculty->find('list', array('fields' => array('Faculty.id')));
                    $userfac = array();
                    foreach ($faculties as $faculty) {
                        $userfac[] = array('user_id' => $userId, 'faculty_id' => $faculty);
                    }
                    $this->UserFaculty->saveAll($userfac);
                }
                // Success!
                $message = "User successfully created!
                    <br>Password: <b>$password</b><br>";
                $message .=
                    $this->_sendAddUserEmail($ret, $password, $enrolments);
                $this->Session->setFlash($message, 'good');

                // save and "leave"
                if ($submit == 'Save') {
                    // no $courseId specified - assumes not instructor - redirect to index
                    if (is_null($courseId)) {
                        $this->redirect('index');
                    // redirect to goToClassList
                    } else {
                        $this->redirect('/users/goToClassList/'.$courseId);
                    }
                // save and add another user
                } else {
                    $this->redirect('/'.$this->params['url']['url']);
                }


                $this->redirect('/users/add');
            } else {
                // Failed
                $this->Session->setFlash("Error: Unable to create user.");
            }
        }

        // Check whether the course exists
        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (!$course) {
                $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
                $this->redirect('/courses');
            }
            $this->breadcrumb->push(array('course' => $course['Course']));
        }
        $this->set('breadcrumb', $this->breadcrumb->push(__('Add User', true)));
    }

    /**
     * Enrol individual students via Users add
     *
     * @param mixed $username username
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    public function enrol($username, $courseId)
    {
        $roleId = $this->User->getRoleId($this->Auth->user('id'));
        $user = $this->User->getByUsername($username);
        $userRole = $user['Role']['0']['RolesUser']['role_id'];
        
        if ($userRole <= $roleId || !in_array($userRole, array(4,5))) {
            $this->Session->setFlash(__('Error: You do not have permission to enrol this user.', true));
            $this->redirect('/courses/home/'.$courseId);
            return;
        }
        
        // enrol students
        if ($userRole == 5) {
            $save = $this->User->addStudent($user['User']['id'], $courseId);
        // enrol tutors
        } else {
            $save = $this->User->addTutor($user['User']['id'], $courseId);
        }
        
        if (!empty($save)) {
            $this->Session->setFlash(__('Student is successfully enrolled.', true), 'good');
        } else {
            $this->Session->setFlash(__('Error: Unable to enrol student.', true));
        }
        $this->redirect('/courses/home/'.$courseId);
    }
     

    /**
     * Given a user id, edit the information for that user
     *
     * @param int $userId   - the user being edited
     * @param int $courseId - the course the user is accessed from
     *
     * @access public
     * @return void
     */
    public function edit($userId = null, $courseId = null) {
        $this->set('title_for_layout', 'Edit User');

        $enrolCourses = $this->User->getEnrolledCourses($userId);

        $role = $this->User->getRoleName($userId);

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(__('Error: You do not have permission to edit users.', true));
            $this->redirect('/home');
        }

        // save the data which involves:
        if ($this->data) {
            // unenrol student from course, group, surveygroup
            // only students will go in because only they have records in Enrolment
            foreach ($enrolCourses as $course) {
                if (!in_array($course, $this->data['Courses']['id'])) {
                    $this->User->removeStudent($userId, $course);
                }
            }

            // create the enrolment entry depending on if instructor or student
            // and also convert it into a CakePHP dark magic friendly format
            if (!empty($this->data['Courses']['id'])) {
                $enrolments = $this->_convertCourseEnrolment(
                    $this->data['Courses']['id'],
                    $this->data['Role']['RolesUser']['role_id']
                );
            } else {
                $enrolments = array('Enrolment' => array());
            }
            $this->data = array_merge($this->data, $enrolments);

            // Now we actually attempt to save the data
            if ($this->User->save($this->data)) {
                // Success!
                $this->Session->setFlash(__('User successfully updated!', true), 'good');
                // no course id given - assume not an instructor
                if (is_null($courseId)) {
                    $this->redirect('index');
                } else {
                    $this->redirect('goToClassList/'.$courseId);
                }
            } else {
                // Failed
                $this->Session->setFlash(__('Error: Unable to update user.', true));
            }
            // set up the course and role variables to fill the form elements
            // only load this after save, or we won't get the correct values
            $this->_initEditFormEnv($userId);
            return;
        }

        if (!($this->data = $this->User->findById($userId))) {
            $this->Session->setFlash(__('Error: This user does not exist.', true));
            $this->redirect($this->referer());
        }

        if (!User::hasPermission('functions/user/'.$role, 'update')) {
            $this->Session->setFlash(__('Error: You do not have permission to edit this user.', true));
            if (is_null($courseId)) {
                $this->redirect('index');
            } else {
                $this->redirect('goToClassList/'.$courseId);
            }
        }

        // set up the course and role variables to fill the form elements
        $this->_initEditFormEnv($userId);

        // not saving, need to load data for forms to fill in
        $this->data = $this->User->read(null, $userId);

        // super admins and faculty admins can edit all users
        // instructors can only edit students and tutors in their course(s)
        if (!User::hasPermission('controllers/departments')) {
            // instructors
            $courses = User::getMyCourseList();
            $models = array('UserTutor', 'UserEnrol');
            $accessibleUsers = array();

            foreach ($models as $model) {
                $users = $this->$model->find('list', array(
                    'conditions' => array('course_id' => array_keys($courses)),
                    'fields' => array('user_id')
                ));
                $accessibleUsers = array_merge($accessibleUsers, $users);
            }

            if (!in_array($userId, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this user', true));
                if (is_null($courseId)) {
                    $this->redirect('index');
                } else {
                    $this->redirect('goToClassList/'.$courseId);
                }
            }
        }
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
                $user = $this->User->findUserByidWithFields($id, array('password'));
                if (md5($this->data['User']['old_password'])==$user['password']) {
                    if ($this->data['User']['tmp_password']==$this->data['User']['confirm_password']) {
                        $this->data['User']['password'] = md5($this->data['User']['tmp_password']);
                    } else {
                        $this->Session->setFlash(__("New passwords do not match", true));
                        $this->redirect('editProfile/'.$id);
                    }
                } else {
                    $this->Session->setFlash(__("Old password is incorrect", true));
                    $this->redirect('editProfile/'.$id);
                }
            } else {
                unset($this->data['User']['temp_password']);
            }

            if ($this->__processForm()) {
                $this->__setSessionData($this->data['User']);
                $this->Session->setFlash((__("Your Profile Has Been Updated Successfully.", true)."<br /><br />" .
                    "<a href='../../home/'>".__('Go to your iPeer Home page.', true)."</a><br />"), 'good');
            }
        }
        if ($this->User->getRoleName($id) == "student") {
            $isStudent = true;
        } else {
            $isStudent = false;
        }
        $oAuthClient = $this->OauthClient->find('all', array('conditions' => array('OauthClient.user_id' => $id)));
        $oAuthToken = $this->OauthToken->find('all', array('conditions' => array('OauthToken.user_id' => $id)));

        $enabled = array('0' => 'Disabled', '1' => 'Enabled');
        $this->data = $this->User->read(null, $id);
        $this->Output->br2nl($this->data);
        $this->set('clients', $oAuthClient);
        $this->set('tokens', $oAuthToken);
        $this->set('enabled', $enabled);
        $this->set('is_student', $isStudent);
        $this->set('data', $this->data);
        $this->set('title_for_layout', __('Edit Profile', true));
        return;
    }


    /**
     * delete
     *
     * @param mixed $id       - user id to delete
     * @param mixed $courseId - the course the user is accessed from
     *
     * @access public
     * @return void
     */
    function delete($id, $courseId = null)
    {
        $role = $this->User->getRoleName($id);

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('Error: You do not have permission to delete users');
            $this->redirect('/home');
        }

        // check if current user has permission to delete this user
        // in case of the being deleted user has higher level role
        if (!User::hasPermission('functions/user/'.$role, 'delete')) {
            $this->Session->setFlash('Error: You do not have permission to delete this user');
            if (is_null($courseId)) {
                $this->redirect('index');
            } else {
                $this->redirect('/users/goToClassList/'.$courseId);
            }
        }

        // super admins and faculty admins can delete all users
        // instructors can only delete students and tutors in their course(s)
        if (!User::hasPermission('controllers/departments')) {
            // instructors
            $courses = User::getMyCourseList();
            $models = array('UserTutor', 'UserEnrol');
            $accessibleUsers = array();

            foreach ($models as $model) {
                $users = $this->$model->find('list', array(
                    'conditions' => array('course_id' => array_keys($courses)),
                    'fields' => array('user_id')
                ));
                $accessibleUsers = array_merge($accessibleUsers, $users);
            }

            if (!in_array($id, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to delete this user', true));
                if (is_null($courseId)) {
                    $this->redirect('index');
                } else {
                    $this->redirect('/users/goToClassList/'.$courseId);
                }
            }
        }

        // Ensure that the id is valid
        if (!is_numeric($id)) {
            $this->cakeError('error404');
        }

        if (is_null($courseId)) {
            if ($this->User->delete($id)) {
                $this->Session->setFlash(__('Record is successfully deleted!', true), 'good');
            } else {
                $this->Session->setFlash(__('Error: Delete failed!', true));
            }
        } else {
            if ($this->User->removeStudent($id, $courseId)) {
                $this->Session->setFlash(__('Student is successfully unenrolled!', true), 'good');
            } else {
                $this->Session->setFlash(__('Error: Unenrol failed!', true));
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * checkDuplicateName
     *
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function checkDuplicateName($courseId = null)
    {
        if (!$this->RequestHandler->isAjax()) {
            $this->cakeError('error404');
        }
        $this->layout = 'ajax';
        $this->autoRender = false;
        
        $message = __('Username "', true).$this->data['User']['username'].__('" already exists.', true);
        if (!is_null($courseId)) {
            $message = $message.'<br> To enrol, click '.
                '<a href="/users/enrol/'.$this->data['User']['username'].'/'.$courseId.'"> here</a>';
        }

        $sFound = $this->User->getByUsername($this->data['User']['username']);

        return ($sFound) ? $message : '';
    }


    /**
     * resetPassword
     *
     * @param mixed $user_id  user id
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function resetPassword($user_id, $courseId = null)
    {
        $role = $this->User->getRoleName($user_id);

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('Error: You do not have permission to reset passwords', true);
            $this->redirect('/home');
        }

        if (!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash('Error: You do not have permission to reset the password for this user.', true);
            if (is_null($courseId)) {
                $this->redirect('index');
            } else {
                $this->redirect('/users/goToClassList/'.$courseId);
            }
        }

        // Read the user
        $user_data = $this->User->find('first', array(
            'conditions' => array('id' => $user_id),
            'contain' => false
        ));

        if (empty($user_data)) {
            $this->Session->setFlash(__('User Not Found!', true));
            $this->redirect("index");
        }

        // super admins and faculty admins can reset passwords for all users
        // instructors can only reset passwords for students and tutors in their course(s)
        if (!User::hasPermission('controllers/departments')) {
            // instructors
            $courses = User::getMyCourseList();
            $models = array('UserTutor', 'UserEnrol');
            $accessibleUsers = array();

            foreach ($models as $model) {
                $users = $this->$model->find('list', array(
                    'conditions' => array('course_id' => array_keys($courses)),
                    'fields' => array('user_id')
                ));
                $accessibleUsers = array_merge($accessibleUsers, $users);
            }

            if (!in_array($user_id, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to reset the password for this user', true));
                if (is_null($courseId)) {
                    $this->redirect('index');
                } else {
                    $this->redirect('/users/goToClassList/'.$courseId);
                }
            }
        }

        //General password
        $tmp_password = $this->PasswordGenerator->generate();
        $user_data['User']['tmp_password'] = $tmp_password;
        $user_data['User']['password'] =  md5($tmp_password);
        $user_data['User']['id'] =  $user_id;

        //Save Data
        if ($this->User->save($user_data, true, array('password'))) {
            $message = sprintf(__("Password successfully reset. The new password is %s.", true).'<br />', $tmp_password);
            $this->User->set('id', $user_id);

            // send email to user
            $this->set('user_data', $user_data);
            if (!empty($user_data['User']['email'])) {
                if ($this->_sendEmail('', 'Reset Password', null, $user_data['User']['email'], 'resetPassword')) {
                    $message .= __("Email has been sent. ", true);
                } else {
                    $message .= __("Email was <u>not</u> sent to the user. ", true) . $this->Email->smtpError;
                }
            } else {
                $message .= __('No email has been sent. User does not have email address.', true);
            }
            $this->Session->setFlash($message, 'good');
            $this->redirect($this->referer());
        } else {
            //Get render page according to the user type
            $this->redirect($this->referer());
        }
    }

    /**
     * import
     *
     * @param int $courseId The id the course to import users into
     *
     * @return void
     */
    public function import($courseId = null)
    {
        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (empty($course)) {
                $this->Session->setFlash(__('Error: That course does not exist.', true));
                $this->redirect('/courses');
            }
            $this->breadcrumb->push(array('course' => $course['Course']));
        }

        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $this->set('courses', $courses);

        // make sure we know the course we're importing users into
        if ($courseId == null && !empty($this->data)) {
            $courseId = $this->data['Course']['Course'];
        }
        $this->set('courseId', $courseId);

        $this->set('breadcrumb', $this->breadcrumb->push(__('Import Students From Text (.txt) or CSV File (.csv)', true)));


        if (!empty($this->data)) {
            // check that file upload worked
            if ($this->FileUpload->success) {
                $uploadFile = $this->FileUpload->uploadDir.DS.$this->FileUpload->finalFile;
            } else {
                $this->Session->setFlash($this->FileUpload->showErrors());
                return;
            }

            $data = Toolkit::parseCSV($uploadFile);
            $usernames = array();
            // generation password for users who weren't given one
            foreach ($data as &$user) {
                if (empty($user[User::IMPORT_PASSWORD])) {
                    $user[User::GENERATED_PASSWORD] = $this->PasswordGenerator->generate();
                } else {
                    $user[User::GENERATED_PASSWORD] = '';
                }
                $usernames[] = $user[User::IMPORT_USERNAME];
            }

            if ($this->data['User']['update_class']) {
                $this->User->removeOldStudents($usernames, $courseId);
            }

            // add the users to the database
            $result = $this->User->addUserByArray($data, true);

            if (!$result) {
                $this->Session->setFlash("Unable to import users. Do they already exist?");
                return;
            }

            $insertedIds = array();
            foreach ($this->User->insertedIds as $new) {
                $insertedIds[] = $new;
            }
            foreach ($result['updated_students'] as $old) {
                $insertedIds[] = $old['User']['id'];
            }

            // enrol the users in the selectec course
            $this->Course->enrolStudents($insertedIds,
                $this->data['Course']['Course']);
            $this->FileUpload->removeFile($uploadFile);
            $this->set('data', $result);
            $this->render('userSummary');
        }
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
     * __loadFromSession
     * Loads data from the Session.
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
     * Updates the user session from the user data passed.
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
        $this->Session->write('ipeerSession.fullname', $userData['first_name'].' '.$userData['last_name']);
        $this->Session->write('ipeerSession.email', $userData['email']);
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
        // enrolled in, since instructors are stored in another array
        $courses = array();
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
            str_replace($this->here, '', Router::url($this->here, true)));

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
