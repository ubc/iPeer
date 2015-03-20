<?php

App::import('Lib', 'neat_string');

/**
 * User Model
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class User extends AppModel
{
    //The model name
    public $name = 'User';
    public $displayField = 'full_name';
    public $insertedIds = array();
    protected $unhashed_password = '';

    /* User Type - Admin, Instructor, TA, Student */
    public $USER_TYPE_ADMIN = 2;
    public $USER_TYPE_INSTRUCTOR = 3;
    public $USER_TYPE_TA = 4;
    public $USER_TYPE_STUDENT = 5;

    const IMPORT_USERNAME = '0';
    const IMPORT_FIRSTNAME = '1';
    const IMPORT_LASTNAME = '2';
    const IMPORT_STUDENT_NO = '3';
    const IMPORT_EMAIL = '4';
    const IMPORT_PASSWORD = '5';
    const GENERATED_PASSWORD = '6';

    const MERGE_MODEL = '0';
    const MERGE_TABLE = '1';
    const MERGE_FIELD = '2';

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

    public $hasMany = array(
        'Submission' => array(
            'className' => 'EvaluationSubmission',
            'foreignKey' => 'submitter_id',
            'dependent' => true,
        ),
        'SurveyInput' => array(
            'className' => 'SurveyInput',
            'dependent' => true,
        ),
        'SurveyGroupMember' => array(
            'className' => 'SurveyGroupMember',
            'foreignKey' => 'user_id',
        ),
    );

    public $hasAndBelongsToMany = array(
        'Faculty' => array(
            'className'    => 'Faculty',
            'joinTable'    => 'user_faculties',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    =>  'faculty_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
        'Course' => array(
            'className'    => 'Course',
            'joinTable'    => 'user_courses',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    =>  'course_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => true,
        ),
        'Tutor' => array(
            'className'    => 'Course',
            'joinTable'    => 'user_tutors',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    =>  'course_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => true,
        ),
        'Enrolment' => array(
            'className'    => 'Course',
            'joinTable'    => 'user_enrols',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    =>  'course_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => true,
        ),
        'Group' => array(
            'className'    => 'Group',
            'joinTable'    => 'groups_members',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    =>  'group_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => true,
        ),
        'Role' => array(
            'className'    => 'Role',
            'joinTable'    => 'roles_users',
            'foreignKey'   => 'user_id',
            'associationForeignKey'    => 'role_id',
            'conditions'   => '',
            'order'        => '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => true,
        ),
    );

    public $validate = array(
        'username'  => array(
            'character' => array(
                'rule' => '/^[a-z0-9_.-]{1,}$/i',
                'message' => 'Usernames may only have letters, numbers, underscore and dot.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 1),
                'message' => 'Usernames must be at least 1 characters.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Duplicate Username found. Please select another.'
            )
        ),
        'email'     => array(
            'rule'       => array('validEmail'),
            'allowEmpty' => true,
            'message'    => 'Invalid email format.'
        ),
        'send_email_notification' => array(
            'rule' => array('requiredWith', 'email'),
            'message' => 'Email notification requires an email address.'
        ),
        'temp_password' => array(
            'rule' => array('minLength', 6),
            'message' => 'Passwords must have a minimum of 6 characters.'
        ),
    );


    public $virtualFields = array(
        'full_name' => 'IF(CONCAT(first_name, last_name)>"", CONCAT_WS(" ", first_name, last_name), username)',
        'student_no_with_full_name' => 'CONCAT_WS(" ", student_no,CONCAT_WS(" ", first_name, last_name))'
    );

    /** validate the faculty field for user form
     * if user is a faculty admin, or instructor,
     * faculty field must not be empty
     */
    public function beforeValidate() {
        /* array structure is different between add & edit and reset password &
        adding superadmin during installation */
        if (array_key_exists('Faculty', $this->data) && array_key_exists('Role', $this->data)) {
            (isset($this->data['Faculty']['Faculty'])) ? $faculty = $this->data['Faculty']['Faculty'] :
                $faculty = $this->data['Faculty'];
            (isset($this->data['Role']['RolesUser']['role_id'])) ? $role = $this->data['Role']['RolesUser']['role_id'] :
                $role = $this->data['Role']['0']['RolesUser']['role_id'];
            if (empty($faculty) && in_array($role, array(2,3))) {
                // make sure this model fails when saving without faculty
                $this->invalidate('Faculty');
                // make the error message appear in the right place
                $this->Faculty->invalidate('Faculty',
                    'Please select a faculty.');
            }
        }
    }

    /* public afterSave($created) {{{ */
    /**
     * afterSave callback for after the save function
     *
     * @param mixed $created if the record has been created
     *
     * @access public
     * @return void
     */
    function afterSave($created)
    {
        if ($created) {
            $this->insertedIds[] = $this->getInsertID();
        }

        return true;
    }


    /**
     * __construct
     *
     * @param bool $id    The id to start the model on.
     * @param bool $table The table to use for this model.
     * @param bool $ds    The connection name this model is connected to.
     *
     * @access public
     * @return void
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }

    /**
     * beforeSave Overwriting Function - will be called before save operation
     *
     * @access public
     * @return void
     */
    public function beforeSave()
    {
        return parent::beforeSave();
    }


    /**
     * find
     *
     * @param bool $conditions search conditions
     * @param bool $fields     fields
     * @param bool $order      result order
     * @param bool $recursive  recursive
     *
     * @access public
     * @return void
     */
    public function find($conditions = array(), $fields = array(), $order = null, $recursive = null)
    {
        if (!isset($fields)) {
        } elseif ($fields === false) {
            $fields = array('Creator', 'Updater');
        } else {
            $fields = array_merge((array) $fields, array('Creator', 'Updater'));
        }

        return parent::find($conditions, $fields, $order, $recursive);
    }

    /**
     * findUserByidWithFields
     *
     * @param mixed $id     user id
     * @param bool  $fields fields to search
     *
     * @access public
     * @return void
     * */
    function findUserByidWithFields($id , $fields = array())
    {
        $result = $this->find('first', array('conditions' => array('User.id' => $id),
            'fields' => $fields));

        return $result['User'];
    }


    /**
     * Get user by username
     *
     * @param mixed $username user's username
     *
     * @access public
     * @return array with user data
     * */
    function getByUsername($username)
    {
        return $this->find(
            'first',
            array('conditions' => array('username' => $username,))
        );
    }

    /**
     * getByUsernames
     *
     * @param mixed $usernames username array
     * @param mixed $contain   models to be included
     *
     * @access public
     * @return void
     * */
    function getByUsernames($usernames, $contain = false)
    {
        return $this->find(
            'all',
            array('conditions' => array('username' => $usernames,), 'contain' => $contain)
        );
    }

    /**
     * Get student enrolled in a course
     *
     * @param int $course_id course id
     *
     * @access public
     * @return students enrolled in a course
     * */
    function getEnrolledStudents($course_id)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array('Enrolment.id' => $course_id),
                'fields' => 'User.*',
                'order' => 'User.student_no'
            )
        );
    }

    /**
     * Get instructors enrolled in a course
     *
     * @param int $course_id course id
     *
     * @access public
     * @return instructors enrolled in a course
     * */
    function getInstructorsByCourse($course_id)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array('Course.id' => $course_id),
                'fields' => 'User.*',
            )
        );
    }

    /**
     * Get student enrolled in a course
     *
     * @param int $course_id course id
     *
     * @access public
     * @return students enrolled in a course
     * */
    function getTutorsByCourse($course_id)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array('Tutor.id' => $course_id),
                'fields' => 'User.*',
            )
        );
    }

    /**
     * Get list of student enrolled students
     *
     * @param int $course_id course id
     *
     * @access public
     * @return list of enrolled students
     * */
    function getEnrolledStudentsForList($course_id)
    {
        $this->displayField = 'student_no_with_full_name';
        return $this->find(
            'list',
            array(
                'conditions' => array(
                    'Enrolment.id' => $course_id,
                ),
                'recursive' => 1,
                'order' => 'User.student_no'
            )
        );
    }

    /**
     * Get list of course tutors
     *
     * @param int $course_id course id
     *
     * @access public
     * @return list of course tutors
     * */
    function getCourseTutorsForList($course_id)
    {
        $this->displayField = 'full_name';
        $temp = $this->find('list', array(
            'conditions' => array('UserTutor.course_id' => $course_id),
            'joins' => array(array('table' => 'user_tutors',
                'alias' => 'UserTutor',
                'type'  => 'LEFT',
                'conditions' => array('User.id = UserTutor.user_id'))
            ),
            'order' => 'User.last_name'));
        return $temp;
    }

    /**
     * Get list of users in the group
     *
     * @param int   $groupId    group id
     * @param mixed $excludeIds the member that are excluded from retrieving
     *
     * @access public
     * @return list of users
     * */
    public function getMembersByGroupId($groupId, $excludeIds = null)
    {
        $conditions = array('Group.id' => $groupId);
        if (!empty($excludeIds)) {
            $conditions[$this->alias.'.id <>'] = $excludeIds;
        }

        return $this->find('all', array(
            'fields' => array($this->alias.'.*'),
            'conditions' => $conditions,
            'contain' => array('Group', 'Role'),
        ));
    }

    /**
     * Hash password
     *
     * @param array $data array containing password
     *
     * @access public
     * @return hashed password
     * */
    function hashPasswords($data)
    {
        if (isset($data['User']['password'])) {
            $data['User']['password'] = md5($data['User']['password']);

            return $data;
        }

        return $data;
    }

    /**
     * Given a user id, get that user's role name
     *
     * @param int $id user id
     *
     * @return role name
     * */
    public function getRoleName($id) {
        $user = $this->findById($id);
        return $user['Role'][0]['name'];
    }

    /**
     * Given a user id, get that user's role id
     *
     * @param int $id user id
     *
     * @return role
     * */
    public function getRoleId($id) {
        $user = $this->findById($id);
        return $user['Role'][0]['id'];
    }

    /**
     * Get user's roles
     *
     * @param int $id user id
     *
     * @access public
     * @return array of user's roles
     * */
    function getRoles($id)
    {
        $user = $this->read(null, $id);

        $ret = array();
        foreach ($user['Role'] as $role) {
            $ret[$role['id']] = $role['name'];
        }

        return $ret;
    }

    /**
     * Assign user a role.
     *
     * @param int $id      user id
     * @param int $role_id role id
     *
     * @access public
     * @return False on failure, true otherwise.
     * */
    function registerRole($id, $role_id)
    {
        return $this->habtmAdd('Role', $id, $role_id);
    }

    /**
     * Get list of instructors
     *
     * @param string $type   type of search: all, first, list
     * @param array  $params search parameters
     *
     * @access public
     * @return list of instructors
     * */
    function getInstructors($type, $params = array())
    {
        $defaults = array('order' => $this->alias.'.last_name');
        $params = array_merge($defaults, $params);

        if (array_key_exists('excludes', $params) && !empty($params['excludes'])) {
            $ids = array();
            if (!is_numeric($params['excludes'][0])) {
                // if the instructors are in full data array format, extract from id attributes
                foreach ($params['excludes'] as $i) {
                    $ids[] = $i['id'];
                }
            } else {
                $ids = $params['excludes'];
            }
            $params['conditions']['NOT'] = array($this->alias.'.id' => $ids);
        }

        $params['contain'] = array('Role');
        $params['conditions']['Role.name'] = 'instructor';

        if ('list' == $type) {
            // list doesn't auto join tables as the recursive is set to -1.
            $params['recursive'] = 0;
        }

        return $this->find($type, $params);
    }

    /**
     * Get list of tutors
     *
     * @access public
     * @return void
     */
    function getTutors()
    {
        $tutorList = $this->find('all', array(
            'conditions' => array('Role.id' => $this->USER_TYPE_TA)));
        return Set::combine($tutorList, '{n}.User.id', '{n}.User.'.$this->displayField);
    }

    /**
     * getInstructorListByFaculty
     * get instructors within faculty
     *
     * @param mixed $facultyId
     *
     * @access public
     * @return void
     */
    function getInstructorListByFaculty($facultyId)
    {
        $users = $this->find('all', array(
            'conditions' => array('Role.id' => $this->USER_TYPE_INSTRUCTOR, 'Faculty.id' => $facultyId),
            'fields' => array($this->alias.'.id', $this->displayField),
            'contain' => array('Faculty', 'Role'),
            'order' => $this->alias.'.last_name',
        ));

        $userList = Set::combine($users, '{n}.User.id', '{n}.User.'.$this->displayField);

        return $userList;
    }

    /**
     * Get current logged in user
     *
     * @access public
     * @return $user logged in user
     * */
    function getCurrentLoggedInUser()
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        $user = $Session->read('Auth.User');
        return $user;
    }

    /**
     * addUserByArray add users with an array
     *
     * @param mixed $userList       array list of users
     * @param bool  $updateExisting if update existing user
     *
     * @access public
     * @return array result
     * */
    function addUserByArray($userList, $updateExisting = false)
    {
        $data = array();

        foreach ($userList as $line => $u) {
            $tmp = array();

            if (count($u) > User::IMPORT_PASSWORD + 2) {
                $this->errorMessage[] = array('addUser' => sprintf(__('Invalid column number on line %d', true), $line));
                continue;
            }

            if (!isset($u[User::IMPORT_USERNAME]) || trim($u[User::IMPORT_USERNAME]) == '') {
                $this->errorMessage[] = array('addUser' => sprintf(__('Username can not be empty. line %d', true), $line));
                continue;
            }

            // handle password
            if (isset($u[User::IMPORT_PASSWORD])) {
                $u[User::IMPORT_PASSWORD] = trim($u[User::IMPORT_PASSWORD]);
            }

            $tmp['username']     = $u[User::IMPORT_USERNAME];
            $tmp['first_name']   = isset($u[User::IMPORT_FIRSTNAME]) ? trim($u[User::IMPORT_FIRSTNAME]) : "";
            $tmp['last_name']    = isset($u[User::IMPORT_LASTNAME]) ? trim($u[User::IMPORT_LASTNAME]) : "";
            $tmp['student_no']   = isset($u[User::IMPORT_STUDENT_NO]) ? trim($u[User::IMPORT_STUDENT_NO]) : "";
            $tmp['email']        = isset($u[User::IMPORT_EMAIL]) ? trim($u[User::IMPORT_EMAIL]) : "";

            if (empty($u[User::IMPORT_PASSWORD])) {
                $tmp['import_password'] = "";
                $tmp['tmp_password'] = $u[User::GENERATED_PASSWORD];
            } else {
                $tmp['import_password'] = $u[User::IMPORT_PASSWORD];
                $tmp['tmp_password'] = "";
            }

            empty($u[User::IMPORT_PASSWORD]) ? $tmp['password'] = md5($u[User::GENERATED_PASSWORD]) :
                $tmp['password'] = md5($u[User::IMPORT_PASSWORD]); // Will be hashed by the Users controller

            $tmp['creator_id']   = User::get('id');
            $data[$u[User::IMPORT_USERNAME]]['User'] = $tmp;
            $data[$u[User::IMPORT_USERNAME]]['Role']['RolesUser']['role_id'] = '5';
        }

        if (!count($data)) {
            $this->errorMessage[] = array('addUser' => __('No valid user to add', true));
            return false;
        }

        // remove the existings
        $existings = $this->getByUsernames(Set::extract('/User/username', array_values($data)));

        foreach ($existings as $key => $e) {
            if ($updateExisting) {
                $new = $data[$e['User']['username']];
                $temp['id'] = $e['User']['id'];
                $temp['username'] = $e['User']['username'];
                // update updatable colun and changed field
                if ($e['User']['first_name'] != $new['User']['first_name']) {
                    $temp['first_name'] = $new['User']['first_name'];
                } else {
                    $temp['first_name'] = $e['User']['first_name'];
                }

                if ($e['User']['last_name'] != $new['User']['last_name']) {
                    $temp['last_name'] = $new['User']['last_name'];
                } else {
                    $temp['last_name'] = $e['User']['last_name'];
                }

                if ($e['User']['email'] != $new['User']['email']) {
                    $temp['email'] = $new['User']['email'];
                } else {
                    $temp['email'] = $e['User']['email'];
                }

                if ($e['User']['student_no'] != $new['User']['student_no']) {
                    $temp['student_no'] = $new['User']['student_no'];
                } else {
                    $temp['student_no'] = $e['User']['student_no'];
                }

                // ignore the password if not exists in import source
                if ($new['User']['import_password']) {
                    $temp['password'] = $new['User']['password'];
                } else {
                    $temp['password'] = $e['User']['password'];
                }
                // don't need creator_id either
                unset($temp['creator_id']);

                $existings[$key]['User'] = $temp;
            }
            
            //change inactive status to active
            $this->readdUser($e['User']['id']);
            
            // remove the existings from the data array
            unset($data[$e['User']['username']]);
        }

        if (!empty($data) && !($this->saveAll(array_values($data)))) {
            $this->errorMessage = array_merge($this->errorMessage, $this->validationErrors);
            return false;
        }

        if ($updateExisting && !empty($existings)) {
            if (!$this->saveAll($existings, array('validate' => false))) {
                return false;
            }
        }

        return array('created_students' => $data, 'updated_students' => $existings);
    }

    /**
     * loadRoles load the roles from database and store them in session
     *
     * @param mixed $id the user id to load the role
     *
     * @access public
     * @return array the role array for user $id ($role_id => $role_name)
     */
    public function loadRoles($id)
    {
        $data = $this->Role->find('all', array('conditions' => array('User.id' => $id), 'recursive' => 0));
        $roles = array_combine(Set::extract('/Role/id', $data), Set::extract('/Role/name', $data));

        App::import('Component', 'Session');
        $Session = new SessionComponent();
        $Session->write('ipeerSession.Roles', $roles);

        // set to true if e.g. user is an instructor/student in at least one course
        $Session->write('ipeerSession.IsInstructor', sizeof($this->getInstructorCourses($id)) > 0);
        $Session->write('ipeerSession.IsStudentOrTutor', 
                        (sizeof($this->getEnrolledCourses($id)) > 0) ||
                        (sizeof($this->getTutorCourses($id)) > 0) );
        
        return $roles;
    }

    /**
     * Add student to course
     *
     * Note that duplicates should be prevented by SQL constraints
     * but can't be tested in test cases cause the fixtures
     * doesn't duplicate that functionality.
     *
     * @param int $user_id the user being enrolled
     * @param int $course_id the course to be enrolled in
     *
     * @return true on success, false on failure
     */
    public function addStudent($user_id, $course_id)
    {
        $newEntry = array();
        $newEntry['UserEnrol']['course_id'] = $course_id;
        $newEntry['UserEnrol']['user_id'] = $user_id;
        $newEntry['UserEnrol']['record_status'] = 'A';
        $this->UserEnrol->create();
        return $this->UserEnrol->save($newEntry);
    }

    /**
     * Remove student from course
     *
     * @param int $user_id the user being dropped
     * @param int $course_id the course to be dropped from
     *
     * @return true on success, false on failure
     */
    public function removeStudent($user_id, $course_id)
    {
        $this->Event = ClassRegistry::init('Event');
        $id = $this->UserEnrol->field('id',
            array('user_id' => $user_id, 'course_id' => $course_id));
        // query all survey events of the course
        $surveys = $this->Event->find('list', array(
            'conditions' => array(
                'course_id' => $course_id,
                'event_template_type_id' => 3),
            'fields' => array('Event.id')));
        /* query any surveyGroupMember records created based on the above
        survey events for the user */
        $members = $this->SurveyGroupMember->find('all', array(
            'conditions' => array(
                'SurveyGroupMember.user_id' => $user_id,
                'SurveyGroupSet.survey_id' => $surveys)));
        // remove the records found
        foreach ($members as $member) {
            $this->SurveyGroupMember->delete($member['SurveyGroupMember']['id']);
        }
        $members = $this->Group->find('all', array('conditions' => array('Member.id' => $user_id, 'course_id' => $course_id)));
        foreach ($members as $member) {
            $this->GroupsMember->delete($member['GroupsMember']['id']);
        }
        return $this->UserEnrol->delete($id);
    }

    /**
     * unenrolStudent
     *
     * @param mixed $userId   user id
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function unenrolStudent($userId, $courseId)
    {
        $id = $this->UserEnrol->field('id',
            array('user_id' => $userId, 'course_id' => $courseId));
        return $this->UserEnrol->delete($id);
    }

    /**
     * Add instructor to course
     *
     * Note that duplicates should be prevented by SQL constraints
     * but can't be tested in test cases cause the fixtures
     * doesn't duplicate that functionality.
     *
     * @param int $user_id the user being enrolled
     * @param int $course_id the course to be enrolled in
     *
     * @return true on success, false on failure
     */
    public function addInstructor($user_id, $course_id)
    {
        $newEntry = array();
        $newEntry['UserCourse']['course_id'] = $course_id;
        $newEntry['UserCourse']['user_id'] = $user_id;
        $newEntry['UserCourse']['record_status'] = 'A';
        $this->UserCourse->create();
        return $this->UserCourse->save($newEntry);
    }

    /**
     * Remove instructor from course
     *
     * @param int $user_id the user being dropped
     * @param int $course_id the course to be dropped from
     *
     * @return true on success, false on failure
     */
    public function removeInstructor($user_id, $course_id)
    {
        $id = $this->UserCourse->field('id',
            array('user_id' => $user_id, 'course_id' => $course_id));
        return $this->UserCourse->delete($id);
    }

    /**
     * Add tutor to course
     *
     * Note that duplicates should be prevented by SQL constraints
     * but can't be tested in test cases cause the fixtures
     * doesn't duplicate that functionality.
     *
     * @param int $user_id the user being enrolled
     * @param int $course_id the course to be enrolled in
     *
     * @return true on success, false on failure
     */
    public function addTutor($user_id, $course_id)
    {
        $newEntry = array();
        $newEntry['UserTutor']['course_id'] = $course_id;
        $newEntry['UserTutor']['user_id'] = $user_id;
        $newEntry['UserTutor']['record_status'] = 'A';
        $this->UserTutor->create();
        return $this->UserTutor->save($newEntry);
    }

    /**
     * Remove tutor from course
     *
     * @param int $user_id the user being dropped
     * @param int $course_id the course to be dropped from
     *
     * @return true on success, false on failure
     */
    public function removeTutor($user_id, $course_id)
    {
        $id = $this->UserTutor->field('id',
            array('user_id' => $user_id, 'course_id' => $course_id));

        $members = $this->Group->find('all', array('conditions' => array('Member.id' => $user_id, 'course_id' => $course_id)));
        foreach ($members as $member) {
            $this->GroupsMember->delete($member['GroupsMember']['id']);
        }

        return $this->UserTutor->delete($id);
    }

    /**
     * getEmails
     *
     * @param mixed $id id
     *
     * @access public
     * @return void
     */
    public function getEmails($id)
    {
        return $this->find('list', array(
            'fields' => array('email'),
            'conditions' => array('id' => $id),
            'contain' => false,
        ));
    }

    /**
     * getWithSurveyResponsesByEvent
     * return currently enrolled member's responses for the event
     *
     * @param mixed $event
     *
     * @access public
     * @return void
     */
    public function getWithSurveyResponsesByEvent($event)
    {
        return $this->find('all', array(
            'fields' => array($this->alias.'.*'),
            'conditions' => array(
                'Enrolment.id' => $event['Event']['course_id'],
            ),
            'contain' => array(
                'Enrolment',
                'SurveyInput' => array(
                    'conditions' => array('event_id' => $event['Event']['id'])
                )
            ),
        ));
    }

    /**
     * removeOldStudents
     *
     * @param mixed $newList  new list of students
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    public function removeOldStudents($newList, $courseId) {
        //match usernames
        $oldList = $this->getEnrolledStudents($courseId);
        foreach ($oldList as $student) {
            if (!in_array($student['User']['username'], $newList)) {
                $this->removeStudent($student['User']['id'], $courseId);
            }
        }
    }

    /**
     * getFullNames
     * Get first and last names of user(s)
     *
     * @param mixed $userId user id
     *
     * @return list of usernames
     */
    function getFullNames($userId)
    {
        return $this->find('list', array(
            'conditions' => array('User.id' => $userId),
            'fields' => array('User.full_name')
        ));
    }

    /**
     * getUsers
     *
     * @param mixed $userIds user ids
     * @param mixed $models  models
     * @param mixed $fields  fields
     *
     * @return users
     */
    function getUsers($userIds, $models=array(), $fields=array()) {
        if (empty($userIds)) {
            return array();
        }
        return $this->find('all', array(
            'conditions' => array('User.id' => $userIds),
            'contain' => $models,
            'fields' => $fields,
        ));
    }

    /**
     * Get members in a group in event (not including tutors)
	 *
	 * @param mixed $groupId group id
	 * @param bool $selfEval check whether self evaluation is allowed or not
	 * @param mixed $userId  user id
	 *
	 * @return group members
	 */
    function getEventGroupMembersNoTutors($groupId, $selfEval, $userId)
    {
        $conditions['Group.id'] = $groupId;
        $conditions['Role.id'] = $this->USER_TYPE_STUDENT;
        if (!$selfEval) {
            $conditions['User.id !='] = $userId;
        }

        $members = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array('Role', 'Group')
        ));

        $groupMembers = array();
        foreach($members as $member) {
            $tmp = array();
            $tmp['User'] = $member['User'];
            $tmp['Role']['0'] = $member['Role'];
            $groupMembers[] = $tmp;
        }

        return $groupMembers;
    }

    /**
     * Get courses a user is enrolled in
     *
     * @param mixed $userId user id
     *
     * @return list of course ids
     */
    function getEnrolledCourses($userId='')
    {
        $user = $this->find('first', array(
            'conditions' => array('User.id' => $userId),
            'contain' => array('Enrolment')
        ));
        return Set::extract($user, '/Enrolment/id');
    }

    /**
     * Get courses a tutor is enrolled in
     *
     * @param mixed $userId user id
     *
     * @return list of course ids
     */
    function getTutorCourses($userId='')
    {
        $user = $this->find('first', array(
            'conditions' => array('User.id' => $userId),
            'contain' => array('Tutor')
        ));
        return Set::extract($user, '/Tutor/id');
    }

    /**
     * Get courses an instructor is enrolled in
     *
     * @param mixed $userId user id
     *
     * @return list of course ids
     */
    function getInstructorCourses($userId='')
    {
        $user = $this->find('first', array(
            'conditions' => array('User.id' => $userId),
            'contain' => array('Course')
        ));
        return Set::extract($user, '/Course/id');
    }
    
    /**
     * generates and saves new password
     *
     * @param mixed $user_id
     *
     * @return boolean
     */
    function savePassword($user_id) {
        App::import('Component', 'PasswordGenerator');
        $psGen = new PasswordGeneratorComponent();
        
        $tmp_password = $psGen->generate();
        $user_data['User']['tmp_password'] = $tmp_password;
        $user_data['User']['password'] =  md5($tmp_password);
        $user_data['User']['id'] =  $user_id;
        
        if($this->save($user_data, true, array('password'))) {
            return $tmp_password;
        }
        return false;
    }
    
    /**
     * unflips user's record status from inactive to active
     *
     * @param mixed $userId
     *
     * @return boolean
     */
    function readdUser($userId)
    {
        $userData['User']['record_status'] = 'A';
        $userData['User']['id'] = $userId;
        
        return $this->save($userData, true, array('record_status'));
    }

    /*********************************
     * Static functions
     * *******************************/

    /**
     * getInstance - used by debug
     *
     * @param object $user user object
     *
     * @access public
     * @return void
     * */
    function getInstance($user = null)
    {
        static $instance = array();

        if ($user) {
            $instance[0] =& $user;
        }

        if (!$instance) {
            return null;
        }

        return $instance[0];
    }


    /**
     * store store user object into session
     *
     * @param mixed $user user object
     *
     * @access public
     * @return void
     * */
    function store($user)
    {
        if (empty($user)) {
            return false;
        }
        User::getInstance($user);
    }


    /**
     * get get user information
     *
     * @param mixed $path user info represented by a path
     *
     * @access public
     * @return void
     * */
    function get($path)
    {
        $_user =& User::getInstance();
        $path = str_replace('.', '/', $path);
        if (strpos($path, 'User') !== 0) {
            $path = sprintf('User/%s', $path);
        }

        if (strpos($path, '/') !== 0) {
            $path = sprintf('/%s', $path);
        }

        $value = Set::extract($path, $_user);

        if (!$value) {
            return false;
        }

        return $value[0];
    }


    /**
     * isLoggedIn check if user is logged in
     *
     * @access public
     * @return bool if user is logged in
     * */
    function isLoggedIn()
    {
        return self::getInstance() !== null;
    }


    /**
     * getMyCourses return the courses that the current user teaches
     *
     * @access public
     * @return array list of courses
     * */
    function getMyCourses()
    {
        $model = Classregistry::init('Course');

        return $model->getCourseByInstructor(self::get('id'));
    }


    /**
     * getMyCourseList get the list of courses
     *
     * @access public
     * @return array list of courses
     * */
    function getMyCourseList()
    {
        $model = Classregistry::init('Course');

        return $model->getListByInstructor(self::get('id'));
    }

    /**
     * getMyDepartmentsCourseList get the list of courses
     *
     * @param mixed $findType
     *
     * @access public
     * @return array list of courses
     */
    function getMyDepartmentsCourseList($findType = 'list')
    {
        $this->UserFaculty = Classregistry::init('UserFaculty');
        $this->Department = Classregistry::init('Department');
        $this->Course = Classregistry::init('Course');

        $uf = $this->UserFaculty->findAllByUserId($this->Auth->user('id'));
        $d = $this->Department->getByUserFaculties($uf);
        $ret = $this->Course->getByDepartments($d, $findType);

        return $ret;
    }
    /**
     * getAccessibleCourses
     *
     * @access public
     * @return list of course ids
     */
    function getAccessibleCourses()
    {
        if (User::hasPermission('functions/user/admin')) {
            return array_keys(User::getMyDepartmentsCourseList('list'));
        } else {
            return array_keys(User::getMyCourseList());
        }
    }

    /**
     * getDroppedStudentsWithRole
     *
     * @param mixed $model
     * @param mixed $results
     * @param mixed $group
     *
     * @access public
     * @return array of dropped students
     */
    function getDroppedStudentsWithRole($model, $results, $group)
    {
        $evaluators = Set::extract('/'.$model.'/evaluator', $results);
        $evaluatees = Set::extract('/'.$model.'/evaluatee', $results);
        $groupMembers = Set::extract('/id', $group['Member']);
        $dropped = array_diff(array_unique(array_merge($evaluators, $evaluatees)), $groupMembers);
        $dropped = $this->find('all', array(
            'conditions' => array('User.id' => $dropped, 'Role.id' => array($this->USER_TYPE_STUDENT, $this->USER_TYPE_TA)),
            'contain' => array('Role', 'Group'),
        ));
        foreach ($dropped as $key => $drop){
            $dropped[$key] = $dropped[$key] + $drop['User'];
        }

        return $dropped;
    }

    /**
     * hasRole test if the user has a specific role
     *
     * @param mixed $role the role name to test
     *
     * @access public
     * @return boolean true if the user has $role, false otherwise
     */
    static function hasRole($role)
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        if (!($roles = $Session->read('ipeerSession.Roles'))) {
            return false;
        }

        return array_search($role, $roles) !== false;
    }

    /**
     * getRoleArray get all roles associated with current user
     *
     *
     * @static
     * @access public
     * @return array the role array
     */
    static function getRoleArray()
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        return $Session->read('ipeerSession.Roles');
    }


    /**
     * getPermissions return the stored permissions for current user
     *
     *
     * @static
     * @access public
     * @return void
     */
    static function getPermissions()
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        return $Session->read('ipeerSession.Permissions');
    }


    /**
     * hasPermission test if the user has permssion to aco and action
     *
     * @param mixed  $aco    aco
     * @param string $action action
     *
     * @access public
     * @return boolean
     */
    static function hasPermission($aco, $action = '*')
    {
        $aco = strtolower($aco);
        App::import('Component', 'Session');
        $Session = new SessionComponent();

        if (!($permission = $Session->read('ipeerSession.Permissions'))) {
            return false;
        }

        // no permission for aco
        if (!isset($permission[$aco])) {
            return false;
        }

        if ("*" == $action) {
            // no need to check action
            return true;
        }

        // check action
        return in_array($action, $permission[$aco]);
    }


    /**
     * isInstructor returns true if user is teaching at least once course
     *
     * @static
     * @access public
     * @return void
     */
    static function isInstructor()
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        $permission = $Session->read('ipeerSession.IsInstructor');

        if (!(isset($permission))) {
            return false;
        }

        return ($permission == true);
    }
    
    /**
     * isStudent returns true if user is a student in at least once course
     *
     * @static
     * @access public
     * @return void
     */
    static function isStudentOrTutor()
    {
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        $permission = $Session->read('ipeerSession.IsStudentOrTutor');

        if (!(isset($permission))) {
            return false;
        }

        return ($permission == true);
    }


    /**
     * getCourseFilterPermission return the permissions need by filtering the course
     *
     * @static
     * @access public
     * @return void
     */
    static function getCourseFilterPermission()
    {
        if (User::hasPermission('functions/superadmin')) {
            return Course::FILTER_PERMISSION_SUPERADMIN;
        } elseif (User::hasPermission('controllers/departments')) {
            return Course::FILTER_PERMISSION_FACULTY;
        } elseif (User::hasPermission('functions/coursemanager')) {
            return Course::FILTER_PERMISSION_OWNER;
        } else {
            return Course::FILTER_PERMISSION_ENROLLED;
        }
    }

    /**
     * Custom validation rule makes a field required if another field is
     * enabled.
     *
     * @param mixed $check the field that needs to be enabled
     * @param mixed $with  the field that needs to be filled if the previous param
     * was enabled
     *
     * @access public
     * @return boolean - true if the $with field is enabled and all the $check
     * fields are filled in too, false otherwise
     * */
    protected function requiredWith($check, $with)
    {
        foreach ($check as $val) {
            if ($val && empty($this->data[$this->name][$with])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Custom validation rule for emails. The built-in CakePHP email validation
     * improperly rejects valid email addresses. e.g.: won't let me use a
     * email like john@localhost.localdomain, probably due to it having a white
     * list of valid domains. This makes testing with email a pain though, so
     * we're not using that.
     *
     * Sadly, even the built in PHP email validation using filter_var fails
     * to follow all of the RFCs. But at least it's better than the built-in
     * CakePHP one.
     *
     * The built in PHP validation requires PHP >= 5.2.0, if it's not available,
     * we use a really simple fallback validation that simply checks if there's
     * text on both sides of the @ sign.
     *
     * @param mixed $check contains the parameters to validate
     *
     * @access public
     * @return boolean - true if the $with field is enabled and all the $check
     * fields are filled in too, false otherwise
     * */
    protected function validEmail($check)
    {
        $email = $check['email'];
        if (function_exists('filter_var')) {
            // filter_var() requires php >= 5.2.0
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
        } else {
            // really basic fallback validation
            if (preg_match('/.+@.+/', $email)) {
                return true;
            }
        }
        return false;
    }
}
