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
        'UserCourse', 'UserTutor', 'Faculty', 'UserFaculty', 'EmailTemplate', 'EvaluationMixevalDetail',
        'EvaluationRubricDetail', 'EventTemplateType', 'Event', 'Faculty', 'GroupEvent',
        'Mixeval', 'Rubric', 'SimpleEvaluation', 'SysParameter', 'Survey', 'EvaluationMixeval',
        'EvaluationSimple', 'EvaluationRubric', 'GroupsMembers', 'SurveyGroupMember', 'SurveyInput',
        'EvaluationSubmission', 'EmailSchedule', 'RolesUser'
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
        $this->FileUpload->uploadDir(TMP);
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
        $this->FileUpload->attr('forceWebroot', false);
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
        $resetPassWarning = __("Resets user's password. Are you sure?", true);
        $resetPassWOEmail = __("Resets user's password without sending a email. The user will lose access to the system. Are you sure?", true);

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

        $extraFilters = array('User.record_status' => 'A');
        if (User::hasPermission('controllers/departments') && !User::hasPermission('functions/superadmin')) {
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
                	// in the case of admins that are not in any faculties
                	// empty facultyIds array will cause sql error
                	if (empty($facultyIds)) {
                		continue;
                	}
                    $conditions[] = 'Role.id = '.$id.' AND Faculty.id IN ('.join(',', $facultyIds).')';
                } else {
                    $conditions[] = 'Role.id = '.$id;
                }
            }
            $result = $this->User->query($query.join(' OR ', $conditions));
            $userIds = Set::extract($result, '/User/id');
            $extraFilters['User.id'] = $userIds;
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

        // add the functionality of resetting a user's password without sending the user a email
        if(User::hasPermission('controllers/users/resetpasswordwithoutemail')) {
            $actions[] = array(__("Reset Password Without Email", true), $resetPassWOEmail, "", "", "resetPasswordWithoutEmail", "User.id");
        }

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
        $this->set('can_merge_users', User::hasPermission('controllers/users/merge'));
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
            $coursesId[$course['id']] = 3; // insructor = 3
        }
        foreach ($user['Enrolment'] as $course) {
            $coursesId[$course['id']] = 5; // student = 5
        }
        foreach ($user['Tutor'] as $course) {
            $coursesId[$course['id']] = 4; // tutor = 4
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
        // get the courses that this user is allowed access to
        $coursesOptions = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        asort($coursesOptions);
        $this->set('coursesOptions', $coursesOptions);

        $this->set('courseLevelRoles', array( 0 => 'none', 3 => 'instructor', 4 => 'tutor', 5 => 'student'));
        
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
            $this->data['User'] = array_map('trim', $this->data['User']);
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
        $enrolled = Set::extract('/Tutor/id', $user) + Set::extract('/Enrolment/id', $user);

        if ($userRole <= $roleId || !in_array($userRole, array(4, 5))) {
            $this->Session->setFlash(__('Error: You do not have permission to enrol this user.', true));
            $this->redirect('/courses/home/'.$courseId);
            return;
        } else if (in_array($courseId, $enrolled)) {
            $this->Session->setFlash(__('Error: The student is already enrolled.', true));
            $this->redirect('/courses/home/'.$courseId);
            return;
        }

        // make the existing user active
        if ($user['User']['record_status'] == "I" && !$this->User->readdUser($user['User']['id'])) {
            $this->Session->setFlash(__('Error: Unable to enrol the user.', true));
            $this->redirect('/courses/home/'.$courseId);
        }

        // enrol students
        if ($userRole == 5) {
            $save = $this->User->addStudent($user['User']['id'], $courseId);
        // enrol tutors
        } else {
            $save = $this->User->addTutor($user['User']['id'], $courseId);
        }

        if (!empty($save)) {
            $this->Session->setFlash(__('User is successfully enrolled.', true), 'good');
        } else {
            $this->Session->setFlash(__('Error: Unable to enrol the user.', true));
        }
        $this->redirect('/courses/home/'.$courseId);
    }

    /**
     * Readd users that were previously soft-deleted
     *
     * @param mixed $username username
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    public function readd($username, $courseId = null)
    {
        $roleId = $this->User->getRoleId($this->Auth->user('id'));
        $user = $this->User->getByUsername($username);
        $userRole = $user['Role']['0']['RolesUser']['role_id'];
        $url = '/users';
        $url .= $courseId ? '/goToClassList/'.$courseId : '';

        if ($userRole <= $roleId || !in_array($userRole, array(4, 5))) {
            $this->Session->setFlash(__('Error: You do not have permission to readd this user.', true));
            $this->redirect($url);
            return;
        }

        if ($this->User->readdUser($user['User']['id'])) {
            $this->Session->setFlash(__('User is successfully readded.', true), 'good');
        } else {
            $this->Session->setFlash(__('Error: Unable to readd the user.', true));
        }
        $this->redirect($url);
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
        $tutorCourses = $this->User->getTutorCourses($userId);
        $instructors = $this->User->getInstructorCourses($userId);
        $role = $this->User->getRoleName($userId);

        if (!$this->User->findById($userId)) {
            $this->Session->setFlash(__('Error: This user does not exist.', true));
            $this->redirect($this->referer());
        }
        
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(__('Error: You do not have permission to edit users.', true));
            $this->redirect('/home');
        }
        
        if (!User::hasPermission('functions/user/'.$role, 'update')) {
            $this->Session->setFlash(__('Error: You do not have permission to edit this user.', true));
            if (is_null($courseId)) {
                $this->redirect('index');
            } else {
                $this->redirect('goToClassList/'.$courseId);
            }
        }
        
        // save the data which involves:
        if ($this->data) {
            $this->data['User'] = array_map('trim', $this->data['User']);

            // add list of courses the user is enrolled in but the logged
            // in user does not have access to so that the user would not
            // be unenrolled from the course when their profile is edited.
            $hiddenCourses = $this->_notUnenrolCourses($this->Auth->user('id'), $userId);
            
            // REMOVE OLD STUDENT STATUSES
            // unenrol student from course, group, surveygroup
            // only students will go in because only they have records in Enrolment
            foreach ($enrolCourses as $course) {
                if (!in_array($course, $hiddenCourses) && $this->data['CourseEnroll'][$course] != 5) {
                    $this->User->removeStudent($userId, $course);
                }
            }
            
            // REMOVE OLD TUTOR STATUSES
            // unenrol tutor from course, group
            foreach ($tutorCourses as $course) {
                if (!in_array($course, $hiddenCourses) && $this->data['CourseEnroll'][$course] != 4) {
                    $this->User->removeTutor($userId, $course);
                }
            }
            
            // REMOVE OLD INSTRUCTOR STATUSES
            // unenrol instructor from course
            foreach ($instructors as $course) {
                if (!in_array($course, $hiddenCourses) && $this->data['CourseEnroll'][$course] != 3) {
                    $this->User->removeInstructor($userId, $course);
                }
            }
            
            $newTutorCourses = array();
            $newInstructorCourses = array();
            $newStudentCourses = array();
            
            // ADD NEW (possibly existing) STATUSES
            foreach($this->data['CourseEnroll'] as $currCourseId => $currRoleId) {
                if(!is_numeric($currCourseId)) {
                    continue;
                }
                switch($currRoleId) {
                    case 3:
                        $newInstructorCourses[] = $currCourseId;
                        break;
                    case 4:
                        $newTutorCourses[] = $currCourseId;
                        break;
                    case 5:
                        $newStudentCourses[] = $currCourseId;
                        break;
                    default:
                        // nothing
                }
            }
            
            unset($this->data['CourseEnroll']); //unset to avoid confusing CakePHP model insertion

            // combine the query data
            $this->data = array_merge($this->data, 
                                      $this->_convertCourseEnrolment($newInstructorCourses,3),
                                      $this->_convertCourseEnrolment($newTutorCourses,4),
                                      $this->_convertCourseEnrolment($newStudentCourses,5)
                                     );
            
            // upgrade to instructor, but don't downgrade 
            if(!empty($newInstructorCourses) && $this->data['Role']['RolesUser']['role_id'] > 3) {
                $this->data['Role']['RolesUser']['role_id'] = 3;
            }
            
            // Now we actually attempt to save the data
            if ($this->User->save($this->data)) {
                // Success!
                $this->Session->setFlash(__('User successfully updated!', true), 'good');
                // course id given - assume an instructor
                if (!is_null($courseId)) {
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

            if (!empty($this->data['User']['temp_password'])) {
                $user = $this->User->findUserByidWithFields($id, array('password'));
                if (md5($this->data['User']['old_password'])==$user['password']) {
                    if ($this->data['User']['temp_password']==$this->data['User']['confirm_password']) {
                        $this->data['User']['password'] = md5($this->data['User']['temp_password']);
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
                $this->Session->setFlash((__("Your Profile Has Been Updated Successfully.", true)), 'good');
            }
        }
        if (in_array($this->User->getRoleName($id), array("student", "tutor"))) {
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

        // soft delete user
        if (is_null($courseId)) {
            $delete = array('User' => array(
                'id' => $id,
                'record_status' => 'I',
            ));
            $cleanUpModels = array(
                'SurveyGroupMember',
                'GroupsMembers',
                'UserEnrol',
                'UserCourse',
                'UserTutor',
            );
            if ($this->User->save($delete)) {
                $this->Session->setFlash(__('Record is successfully deleted!', true), 'good');
                // unenrol/remove them from courses/groups if they are soft-deleted
                foreach ($cleanUpModels as $model) {
                    $condition = array($model.'.user_id' => $id);
                    $this->$model->deleteAll($condition);
                }
            } else {
                $this->Session->setFlash(__('Error: Delete failed!', true));
            }
        // unenrol user from course
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
        $urlSuffix = $courseId ? '/'.$courseId : '';

        $sFound = $this->User->getByUsername($this->data['User']['username']);

        $message = __('Username "', true).$this->data['User']['username'].__('" already exists.', true);
        if (!is_null($courseId)) {
            $message .= '<br> To enrol, click '.
                '<a href="/users/enrol/'.$this->data['User']['username'].'/'.$courseId.'"> here</a>';
        } else if ($sFound && $sFound['User']['record_status'] == 'I') {
            $message .= '<br> To readd the user, click '.
                '<a href="/users/readd/'.$this->data['User']['username'].$urlSuffix.'"> here</a>';
        }

        return ($sFound) ? $message : '';
    }


    /**
     * resetPassword
     *
     * @param mixed $userId  user id
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function resetPassword($userId, $courseId = null)
    {
        // checks the user's permissions
        $user_data = $this->_checkResetPasswordPermission($userId, $courseId);

        //Save Data
        if ($tmp_password = $this->User->savePassword($userId)) {
            $message = sprintf(__("Password successfully reset. The new password is %s.", true).'<br />', $tmp_password);
            $user_data['User']['tmp_password'] = $tmp_password;
            $this->User->set('id', $userId);

            // send email to user
            $this->set('user_data', $user_data);
            if (!empty($user_data['User']['email'])) {
                if ($this->_sendEmail('', 'iPeer Password Reset', null, $user_data['User']['email'], 'resetPassword')) {
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
     * resetPasswordWithoutEmail
     *
     * @param mixed $userId
     * @param mixed $courseId
     *
     * @return boolean: true for success and false for fail
     */
    public function resetPasswordWithoutEmail($userId, $courseId = null)
    {
        // checks the user's permissions
        $this->_checkResetPasswordPermission($userId, $courseId);

        // generate and save new password
        if ($tmp_password = $this->User->savePassword($userId)) {
            $message = sprintf(__("Password successfully reset. The new password is %s.", true).'<br />', $tmp_password);
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
                $this->Session->setFlash("Error: Unable to import users.");
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
     * merge
     *
     * @access public
     * @return void
     */
    function merge()
    {
        $searchValue = array(
            'full_name' => __('Full Name', true),
            'username' => __('Username', true),
            'student_no' => __('Student No.', true)
        );
        $this->set('title_for_layout', __('Merge Users', true));
        $this->set('searchValue', $searchValue);
        $this->set('secondaryAccounts', array());
        $this->set('primaryAccounts', array());
        if($this->data) {
            $primaryAccount = $this->data['User']['primaryAccount'];
            $secondaryAccount = $this->data['User']['secondaryAccount'];
            $primaryRole = $this->User->getRoleId($primaryAccount);
            $secondaryRole = $this->User->getRoleId($secondaryAccount);

            // secondary account cannot be currently logged in user
            if (User::get('id') == $secondaryAccount) {
                $this->Session->setFlash(__('Error: The secondary account is the currently logged in user.', true));
                return;
            }

            if ($primaryRole != $secondaryRole) {
                $this->Session->setFlash(__('Error: The users do not have the same role.', true));
                return;
            }

            if ($primaryAccount == $secondaryAccount) {
                $this->Session->setFlash(__('Error: No merger needed. The primary and secondary accounts are the same.', true));
                return;
            }

            //update transactions
            $updated = true;
            $this->User->begin();
            // tables that only need creator_id and updater_id updated
            $updated = $updated && $this->_updateCreatorUpdaterId($updated, $primaryAccount, $secondaryAccount);
            // user_course, user_enrol, user_tutor
            $updated = $updated && $this->_updateUserCourse($updated, $primaryAccount, $secondaryAccount);
            // the three evaluation types eg. evaluation_simple
            $updated = $updated && $this->_updateEvaluations($updated, $primaryAccount, $secondaryAccount);
            // tables that only need their user_id field updated
            $updated = $updated && $this->_updateUserId($updated, $primaryAccount, $secondaryAccount);
            // evaluation_submissions && email_schedules
            $updated = $updated && $this->_updateTablesWithUserId($updated, $primaryAccount, $secondaryAccount);
            $updated = $updated && $this->User->delete($secondaryAccount); // delete secondaryAccount

            if ($updated) {
                $this->Session->setFlash(__('The two accounts have successfully merged.', true), 'good');
                $this->User->commit();
            } else {
                $this->Session->setFlash(__('Error: The two accounts could not be merged.', true));
                $this->User->rollback();
            }
        }
    }

    /**
     * ajax_merge_options
     *
     * @access public
     * @return void
     */
    function ajax_merge() {
        if (!$this->RequestHandler->isAjax()) {
            $this->cakeError('error404');
        }
        $options = array();
        switch($_GET['action']) {
            case 'account':
                if ($_GET['value'] == '') {
                    $options = array();
                } else {
                    $options = $this->User->find('all', array(
                        'conditions' => array(
                            'Role.id' => array_keys($this->AccessControl->getViewableRoles()),
                            'User.'.$_GET['field'].' LIKE' => "%".$_GET['value']."%",
                    )));
                    $options = Set::combine($options, '{n}.User.id', '{n}.User.'.$_GET['field']);
                }
                break;
            case 'data':
                $user = $this->User->findById($_GET['userId']);
                // initialize user's data with blank fields
                $options = array('Username', 'LastName', 'FirstName', 'Role', 'Title', 'Email',
                    'Creator', 'CreateDate', 'Updater', 'UpdateDate');
                $options = array_combine($options, array_fill(0, 10, ''));
                if (isset($user)) {
                    $options['Username'] = $user['User']['username'];
                    $options['LastName'] = $user['User']['last_name'];
                    $options['FirstName'] = $user['User']['first_name'];
                    $options['Role'] = ucwords($this->Role->getRoleName($user['Role']['0']['id']));
                    $options['Title'] = $user['User']['title'];
                    $options['Email'] = $user['User']['email'];
                    $options['Creator'] = $user['User']['creator'];
                    $options['CreateDate'] = $user['User']['created'];
                    $options['Updater'] = $user['User']['updater'];
                    $options['UpdateDate'] = $user['User']['modified'];
                }
        }


        asort($options);
        $this->set('options', $options);
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
     * _checkResetPasswordPermission
     *
     * @param mixed $userId
     * @param mixed $courseId
     *
     * @access private
     * @return array of user data
     */
    private function _checkResetPasswordPermission($userId, $courseId)
    {
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('Error: You do not have permission to reset passwords', true);
            $this->redirect('/home');
        }

        // Read the user
        $userData = $this->User->findById($userId);
        if (empty($userData)) {
            $this->Session->setFlash(__('User Not Found!', true));
            $this->redirect("index");
        }

        $role = $this->User->getRoleName($userId);
        if (!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash('Error: You do not have permission to reset the password for this user.', true);
            if (is_null($courseId)) {
                $this->redirect('index');
            } else {
                $this->redirect('/users/goToClassList/'.$courseId);
            }
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

            if (!in_array($userId, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to reset the password for this user', true));
                if (is_null($courseId)) {
                    $this->redirect('index');
                } else {
                    $this->redirect('/users/goToClassList/'.$courseId);
                }
            }
        }

        return $userData;
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

    /**
     * _updateCreatorUpdaterId
     *
     * @param mixed $updated   updated
     * @param mixed $primary   primary account
     * @param mixed $secondary secondary account
     *
     * @access private
     * @return void
     */
    private function _updateCreatorUpdaterId($updated, $primary, $secondary)
    {
        $models = array('Course', 'Department', 'EmailTemplate', 'EvaluationMixevalDetail',
            'EvaluationRubricDetail', 'Event', 'EventTemplateType', 'Group',
            'GroupEvent', 'Mixeval', 'Rubric', 'SimpleEvaluation', 'SysParameter', 'Survey');
        foreach ($models as $model) {
            $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $model)).'s';
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET creator_id='.$primary.' WHERE creator_id='.$secondary.';');
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET updater_id='.$primary.' WHERE updater_id='.$secondary.';');
        }
        $updated = $updated && $this->Faculty->query('UPDATE faculties SET creator_id='.$primary.' WHERE creator_id='.$secondary.';');
        $updated = $updated && $this->Faculty->query('UPDATE faculties SET updater_id='.$primary.' WHERE updater_id='.$secondary.';');

        return $updated;
    }

    /**
     * _updateUserCourse
     *
     * @param mixed $updated   updated
     * @param mixed $primary   primary account
     * @param mixed $secondary secondary account
     *
     * @access private
     * @return void
     */
    private function _updateUserCourse($updated, $primary, $secondary)
    {
        $functionNames = array(
            'UserTutor' => 'removeTutor',
            'UserEnrol' => 'unenrolStudent',
            'UserCourse' => 'removeInstructor'
        );
        $models = array_keys($functionNames);
        foreach ($models as $model) {
            $primaryTutor = Set::extract('/'.$model.'/course_id', $this->$model->findAllByUserId($primary));
            $secondaryTutor = Set::extract('/'.$model.'/course_id', $this->$model->findAllByUserId($secondary));
            $conflict = array_intersect($primaryTutor, $secondaryTutor);
            if ($conflict) {
                $updated = $updated && $this->User->$functionNames[$model]($secondary, $conflict);
            }
            $conflict = implode(',', $conflict);
            $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $model)).'s';
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET creator_id='.$primary.' WHERE creator_id='.$secondary.';');
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET updater_id='.$primary.' WHERE updater_id='.$secondary.';');
            $change = 'UPDATE '.$name.' SET user_id='.$primary.' WHERE user_id='.$secondary;
            $change .= ($conflict) ? ' AND course_id NOT IN ('.$conflict.');' : ';';
            $updated = $updated && $this->$model->query($change);
        }

        return $updated;
    }

    /**
     * _updateEvaluations
     *
     * @param mixed $updated   updated
     * @param mixed $primary   primary account
     * @param mixed $secondary secondary account
     *
     * @access private
     * @return void
     */
    private function _updateEvaluations($updated, $primary, $secondary)
    {
        $models = array('EvaluationSimple', 'EvaluationMixeval', 'EvaluationRubric');
        foreach ($models as $model) {
            $primaryEval = Set::extract('/'.$model.'/grp_event_id', $this->$model->findAllByEvaluator($primary));
            $secondaryEval = Set::extract('/'.$model.'/grp_event_id', $this->$model->findAllByEvaluator($secondary));
            $conflict = array_intersect($primaryEval, $secondaryEval);
            if ($conflict) {
                $updated = $updated && $this->$model->deleteAll(
                    array('evaluator' => $secondary, 'grp_event_id' => $conflict));
            }
            $conflict = implode(',', $conflict);
            $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $model)).'s';
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET creator_id='.$primary.' WHERE creator_id='.$secondary.';');
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET updater_id='.$primary.' WHERE updater_id='.$secondary.';');
            $updated = $updated && $this->$model->query('UPDATE '.$name.' SET evaluatee='.$primary.' WHERE evaluatee='.$secondary.';');
            $change = 'UPDATE '.$name.' SET evaluator='.$primary.' WHERE evaluator='.$secondary;
            $change .= ($conflict) ? ' AND grp_event_id NOT IN ('.$conflict.');' : ';';
            $updated = $updated && $this->$model->query($change);
        }

        return $updated;
    }

    /**
     * _updateUserId
     *
     * @param mixed $updated   updated
     * @param mixed $primary   primary account
     * @param mixed $secondary secondary account
     *
     * @access private
     * @return void
     */
    private function _updateUserId($updated, $primary, $secondary)
    {
        $models = array(
            array('GroupsMembers', 'groups_members', 'group_id'),
            array('SurveyGroupMember', 'survey_group_members', 'group_set_id'),
            array('SurveyInput', 'survey_inputs', 'event_id'),
            array('UserFaculty', 'user_faculties', 'faculty_id')
        );

        foreach ($models as $model) {
            $primaryUser = $this->$model[User::MERGE_MODEL]->findAllByUserId($primary);
            $primaryUser = Set::extract('/'.$model[User::MERGE_MODEL].'/'.$model[User::MERGE_FIELD], $primaryUser);
            $secondaryUser = $this->$model[User::MERGE_MODEL]->findAllByUserId($secondary);
            $secondaryUser = Set::extract('/'.$model[User::MERGE_MODEL].'/'.$model[User::MERGE_FIELD], $secondaryUser);
            $conflict = array_intersect($primaryUser, $secondaryUser);
            if ($conflict) {
                $updated = $updated && $this->$model[User::MERGE_MODEL]->deleteAll(
                    array('user_id' => $secondaryUser, $model[User::MERGE_FIELD] => $conflict));
            }
            $conflict = implode(',', $conflict);
            $change = 'UPDATE '.$model[User::MERGE_TABLE].' SET user_id='.$primary.' WHERE user_id='.$secondary;
            $change .= ($conflict) ? ' AND '.$model[User::MERGE_FIELD].' NOT IN ('.$conflict.');' : ';';
            $updated = $updated && $this->$model[User::MERGE_MODEL]->query($change);
        }

        //oauth_clients
        $updated = $updated && $this->OauthClient->query('UPDATE oauth_clients SET user_id='.$primary.' WHERE user_id='.$secondary.';');
        //oauth_tokens
        $updated = $updated && $this->OauthToken->query('UPDATE oauth_tokens SET user_id='.$primary.' WHERE user_id='.$secondary.';');

        return $updated;
    }

    /**
     * _updateTablesWithUserId
     *
     * @param mixed $updated   updated
     * @param mixed $primary   primary account
     * @param mixed $secondary secondary account
     *
     * @access private
     * @return void
     */
    private function _updateTablesWithUserId($updated, $primary, $secondary)
    {
        //evaluation_submissions
        //update creator_id and updater_id
        $updated = $updated && $this->EvaluationSubmission->query('UPDATE evaluation_submissions SET creator_id='.$primary.' WHERE creator_id='.$secondary.';');
        $updated = $updated && $this->EvaluationSubmission->query('UPDATE evaluation_submissions SET updater_id='.$primary.' WHERE updater_id='.$secondary.';');
        $primaryEval = $this->EvaluationSubmission->getGrpEventIdEvalSub($primary);
        $primarySurvey = $this->EvaluationSubmission->getEventIdSurveySub($primary);
        $secondaryEval = $this->EvaluationSubmission->getGrpEventIdEvalSub($secondary);
        $secondarySurvey = $this->EvaluationSubmission->getEventIdSurveySub($secondary);

        $evalConflict = array_intersect($primaryEval, $secondaryEval);  //grp_evnt_id
        $surveyConflict = array_intersect($primarySurvey, $secondarySurvey); //event_id
        //delete conflicted evaluation submissions by grp_event_id
        if ($evalConflict) {
            $updated = $updated && $this->EvaluationSubmission->deleteAll(
                array('EvaluationSubmission.submitter_id' => $secondary, 'EvaluationSubmission.grp_event_id' => $evalConflict));
        }
        //delete conflicted survey submissions by event_id
        if ($surveyConflict) {
            $updated = $updated && $this->EvaluationSubmission->deleteAll(
                array('EvaluationSubmission.submitter_id' => $secondary, 'EvaluationSubmission.event_id' => $surveyConflict));
        }
        $evalConflict = implode(',', $evalConflict);
        $surveyConflict = implode(',', $surveyConflict);

        $change = 'UPDATE evaluation_submissions SET submitter_id='.$primary.' WHERE submitter_id='.$secondary;
        $change .= ($evalConflict || $surveyConflict) ? ' AND (' : ';';
        //append grp_event_id if any evaluation submissions are conflicted
        $change .= ($evalConflict) ? 'grp_event_id NOT IN ('.$evalConflict.')' : '';
        $change .= ($evalConflict && $surveyConflict) ? ' OR ' : '';
        //append event_id if any survey submissions are conflicted
        $change .= ($surveyConflict) ? 'event_id NOT IN ('.$surveyConflict.')' : '';
        $change .= ($evalConflict || $surveyConflict) ? ');' : '';
        $updated = $updated && $this->EvaluationSubmission->query($change);

        //email_schedules
        $updated = $updated && $this->EmailSchedule->query("UPDATE email_schedules SET creator_id=".$primary." WHERE creator_id=".$secondary.";");
        $updated = $updated && $this->EmailSchedule->query("UPDATE email_schedules SET `from`=".$primary." WHERE `from`=".$secondary.";");
        // middle of the string eg. ;17;
        $updated = $updated && $this->EmailSchedule->query('UPDATE email_schedules SET `to`=REPLACE(`to`, ";'.$secondary.';", ";'.$primary.';");');
        // end of the string eg. ;17
        $updated = $updated && $this->EmailSchedule->query('UPDATE email_schedules SET `to`=REPLACE(`to`, ";'.$secondary.'", ";'.$primary.'");');
        // beginning of the string eg. 17;
        $updated = $updated && $this->EmailSchedule->query('UPDATE email_schedules SET `to`=REPLACE(`to`, "'.$secondary.';", "'.$primary.';");');
        // the whole string eg. 17
        $updated = $updated && $this->EmailSchedule->query('UPDATE email_schedules SET `to`=REPLACE(`to`, "'.$secondary.'", "'.$primary.'");');

        return $updated;
    }

    /**
     * helper function for users/edit, to not unenrol the user being edited from
     * courses the logged user don't have access to
     *
     * @param int $editor
     * @param int $userId
     *
     * @return array of courses to not unenrol
     */
    private function _notUnenrolCourses($editor, $userId)
    {
        $editor = $this->User->findById($editor);
        $user = $this->User->findById($userId);
        $editorCourses = array();
        $userCourses = array();

        // user's list of courses
        foreach ($user['Course'] as $course) {
            $userCourses[] = $course['id'];
        }
        foreach ($user['Enrolment'] as $course) {
            $userCourses[] = $course['id'];
        }
        foreach ($user['Tutor'] as $course) {
            $userCourses[] = $course['id'];
        }

        // get editor's list of courses
        $editorCourses = $this->Course->getAccessibleCourses(User::get('id'),
            User::getCourseFilterPermission(), 'list');
        
        return array_diff($userCourses, array_keys($editorCourses));
    }

    /**
     * formatDueIn
     *
     * Take the due interval, which is in seconds, and format
     * it something that's easier for users to read.
     *
     * @param mixed $seconds seconds
     *
     * @access private
     * @return void
     */
    private function _formatDueIn($seconds)
    {
        $ret = "";
        if ($seconds > 86400) {
            $ret = round($seconds / 86400, 1) . __(' days', true);
        } elseif ($seconds < 3600) {
            $minutes = (int) ($seconds / 60);
            $seconds = $seconds % 60;
            $ret = $minutes . __(' minutes ', true) . $seconds . __(' seconds', true);
        } else {
            $hours = (int) ($seconds / 3600);
            $minutes = (int) ($seconds % 3600 / 60);
            $ret = $hours . __(' hours ', true) . $minutes . __(' minutes', true);
        }

        return $ret;
    }

    /**
     * Helper to filter events into 3 different categories and to
     * discard inactive events.
     *
     * The 3 categories are: Upcoming, Submitted, Expired
     *
     * - Upcoming are events that the user can still make submissions for.
     * - Submitted are events that the user has already made a submission.
     * - Expired are events that the user hasn't made and can no longer make
     * submissions, but they can still view results from their peers.
     *
     * An evaluation is considered inactive once past its result release
     * period. A survey is considered inactive once past its release period.
     *
     * @param array $events - list of events info returned from the event model,
     *  each event MUST have an 'EvaluationSubmission' array or this won't work
     *
     * @return Discard inactive events and then split the remaining events
     * into upcoming, submitted, and expired.
     * */
    private function _splitSubmittedEvents($events)
    {
        $submitted = $upcoming = $expired = array();

        foreach ($events as $event) {
            if (empty($event['EvaluationSubmission']) && $event['Event']['is_released']) {
                // can only take surveys during the release period
                $upcoming[] = $event;
            } else if (!empty($event['EvaluationSubmission']) &&
                strtotime('NOW') < strtotime($event['Event']['result_release_date_end'])) {
                // has submission and can or will be able to view results soon
                // note that we're not using is_released or is_result_released
                // because of an edge case where if there is a period of time
                // between the release and result release period, the evaluation
                // will disappear from view
                $submitted[] = $event;
            } else if (!empty($event['EvaluationSubmission']) && $event['Event']['is_released']) {
                // special case for surveys, which doesn't have
                // result_release_date_end
                $submitted[] = $event;
            } else if (empty($event['EvaluationSubmission']) &&
                    strtotime('NOW') <
                    strtotime($event['Event']['result_release_date_end']) &&
                    strtotime('NOW') >
                    strtotime($event['Event']['release_date_end'])
            ) { // student did not do the survey within the allowed time
                // but we should still let them view results
                $expired[] = $event;
            }
        }

        return array('upcoming' => $upcoming, 'submitted' => $submitted, 'expired' => $expired);
    }

    /**
     * showEvents
     *
     * @param mixed $id - user id
     *
     * @access public
     * @return void
     */

    function showEvents($id)
    {
        $this->redirect('/');

        // check what type the logged in user is
        if(User::hasPermission('functions/superadmin')) {
            $extraId = null;
        } else if (User::hasPermission('controllers/departments')) {
            $extraId = User::getAccessibleCourses();
        } else {
            $extraId = User::get('id');
        }
        // find all the student's events the user is allowed to see
        $events = $this->Event->getEventsByUserId($id, null, $extraId);

        // mark events as late if past due date
        foreach ($events as &$type) {
            foreach ($type as &$event) {
                if ($event['Event']['due_in'] > 0) {
                    $event['late'] = false;
                    continue;
                }
                $event['late'] = true;
            }
        }

        // determine the proper penalty to be applied to a late eval
        foreach ($events['Evaluations'] as &$event) {
            if (!$event['late'] || empty($event['Penalty'])) {
                continue;
            }
            // convert seconds to days
            $daysLate = abs($event['Event']['due_in']) / 86400;
            $pctPenalty = 0;
            foreach ($event['Penalty'] as $penalty) {
                $pctPenalty = $penalty['percent_penalty'];
                if ($penalty['days_late'] > $daysLate) {
                    break;
                }
            }
            $event['percent_penalty'] = $pctPenalty;
        }

        // format the 'due in' time interval for display
        foreach ($events as &$types) {
            foreach ($types as &$event) {
                $event['Event']['due_in'] = $this->_formatDueIn(
                    abs($event['Event']['due_in']));
            }
        }

        // remove non-current events and split into upcoming/submitted/expired
        $evals = $this->_splitSubmittedEvents($events['Evaluations']);
        $surveys = $this->_splitSubmittedEvents($events['Surveys']);

        // calculate summary statistics
        $numOverdue = 0;
        $numDue = 0;
        $numDue = sizeof($evals['upcoming']) + sizeof($surveys['upcoming']);
        // only evals can have overdue events right now
        foreach ($evals['upcoming'] as $e) {
            $e['late'] ? $numOverdue++ : '';
        }

        $this->set('studentId', $id);
        $this->set('evals', $evals);

        $this->set('surveys', $surveys);
        $this->set('numOverdue', $numOverdue);

        $this->set('numDue', $numDue);
        $this->render('student_events');
    }
}
