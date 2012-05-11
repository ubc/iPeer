<?php
define('IMPORT_USERNAME', 0);
define('IMPORT_FIRSTNAME', 1);
define('IMPORT_LASTNAME', 2);
define('IMPORT_STUDENT_NO', 3);
define('IMPORT_EMAIL', 4);
define('IMPORT_PASSWORD', 5);

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
    protected $unhashed_password = '';

    /* User Type - Admin, Instructor, TA, Student */
    public $USER_TYPE_ADMIN = 'A';
    public $USER_TYPE_INSTRUCTOR = 'I';
    public $USER_TYPE_TA = 'T';
    public $USER_TYPE_STUDENT = 'S';

    public $_schema = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'username' => array('type' => 'string', 'null' => false, 'length' => 80, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'password' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'role' => array('type' => 'string', 'null' => false, 'default' => 'S', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'student_no' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'last_logout' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'last_accessed' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

    public $hasMany = array(
        'Submission' => array(
            'className' => 'EvaluationSubmission',
            'foreignKey' => 'submitter_id',
            'dependent' => true,
        )
    );

    public $hasAndBelongsToMany = array(
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
                'rule' => 'alphaNumeric',
                'message' => 'Usernames may only have letters and numbers.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Usernames must be at least 6 characters.'
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
        'first_name' => array(
            'rule' => 'notEmpty',
            'message' => "First name cannot be empty, it is used as the display name."
        ),
        'role'     => array(
            'rule' => 'notEmpty',
            'message' => 'Role field may not be left blank.'
        ),
        'send_email_notification' => array(
            'rule' => array('requiredWith', 'email'),
            'message' => 'Email notification requires an email address.'
        )
    );


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
        $this->virtualFields['full_name'] = sprintf('CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias);
        $this->virtualFields['student_no_with_full_name'] = sprintf('CONCAT(%s.student_no, " - ", %s.first_name, " ", %s.last_name)', $this->alias, $this->alias, $this->alias);
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


    //Validation check on duplication of username
/*  function hasDuplicateUsername($username) {
    if ($this->find('first', array('conditions' => array('username' => $username)))) {
      $this->errorMessage[] = array('Username' => __('Duplicate Username found. Please change the username of this user.'));
      /*if ($this->data[$this->name]['role'] == 'S') {
        $this->errorMessage.='<br>If you want to enrol this student to one or more courses, use the enrol function on User Listing page.';
        }
      return false;
    }

    return true;
}*/

    /**
     * findUser Find user by username and password
     *
     * @param mixed $username user's username
     * @param mixed $password user's password
     *
     * @access public
     * @return array with user data
     * */
    public function findUser ($username, $password)
    {
        return $this->find('first', array('conditions' => array('username' => $username,
            'password' => $password)));
    }


    /**
     * Find user by student number
     *
     * @param mixed $studentNo student number
     *
     * @access public
     * @return array with user data
     * */
    function findUserByStudentNo ($studentNo = null)
    {
        if (!empty($studentNo)) {
            return $this->find('first',
                array('conditions' => array('student_no' => $studentNo,)));
        }
    }

    /**
     * findUserByid Get user by user id
     *
     * @param mixed $id     user id
     * @param bool  $params search parameters
     *
     * @access public
     * @return array with user data
     * */
    function findUserByid ($id, $params = array())
    {
        if (null == $id) {
            return null;
        }

        return $this->find(
            'first',
            array_merge(array('conditions' => array($this->name.'.id' => $id,)), $params)
        );
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
     * Get user id by student no
     *
     * @param string $studentNo student number
     *
     * @access public
     * @return user id
     * */
    function getUserIdByStudentNo($studentNo)
    {
        if (!empty($studentNo)) {
            $tmp = $this->findUserByStudentNo($studentNo);

            return $tmp['User']['id'];
        }

        return null;
    }

    /**
     * Get student enrolled in a course
     *
     * @param int   $course_id  course id
     * @param mixed $fields     fields to return
     * @param mixed $conditions conditions of search
     *
     * @access public
     * @return students enrolled in a course
     * */
    function getEnrolledStudents($course_id, $fields = array(), $conditions = null)
    {
        return $this->find('all', array('conditions' => array('Enrolment.id' => $course_id),
            'fields' => 'User.*',
            'order' => 'User.student_no'));
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
        return $this->find('list', array('conditions' => array('UserEnrol.course_id' => $course_id, 'User.role' => 'S'),
            'joins' => array(array('table' => 'user_enrols',
            'alias' => 'UserEnrol',
            'type'  => 'LEFT',
            'conditions' => array('User.id = UserEnrol.user_id'))
        ),
        'order' => 'User.student_no'));
    }

    /**
     * Get user by email
     *
     * @param string $email user's email
     *
     * @access public
     * @return array with user data
     * */
    function getUserByEmail($email = '')
    {
        //return $this->find( "email='" . $email );
        return $this->find('first', array(
            'conditions' => array('email' => $email)
        ));
    }

    /**
     * Get user by email and student number
     *
     * @param string $email     user's email
     * @param string $studentNo user's student number
     *
     * @access public
     * @return array with user data
     * */
    function findUserByEmailAndStudentNo($email = '', $studentNo = '')
    {
        //return $this->find("email='" .$email . "' AND student_no='" . $studentNo . "'");
        return $this->find('first', array(
            'conditions' => array('email' => $email, 'student_no'=> $studentNo)
        ));
    }

    /**
     * canRemoveCourse check if user has permission to remove the course from a
     * student
     *
     * @param mixed $user      the user array returned by findUserByxxxx
     * @param mixed $course_id target course id
     *
     * @access public
     * @return boolean whether or not user can remove the course from the student
     * */
    function canRemoveCourse($user, $course_id)
    {

        if (!isset($user['User']) || !is_array($user['User'])) {
            return false;
        }
        if ('A' == $user['User']['role']) {
            return true;
        }
        if ('SA' == $user['User']['role']) {
            return true;
        }
        if ('S' == $user['User']['role']) {
            return false;
        }
        if (!isset($user['Course']) || !is_array($user['Course'])) {
            return false;
        }

        foreach ($user['Course'] as $c) {
            if ($c['id'] == $course_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get full name of a role by letter abbreviation
     *
     * @param string $role abbreviation
     *
     * @access public
     * @return full name of a role
     * */
    function getRoleText($role)
    {
        if (!empty($role)) {
            $ROLE_TEXT = array('A'  => 'Administrator',
                'I'  => 'Instructor',
                'T'  => 'TA',
                'S'  => 'Student');
            if (isset($ROLE_TEXT[$role])) {
                $text = $ROLE_TEXT[$role];
            } else {
                $text = 'Unknown';
            }

            return $text;
        }

        return null;
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
     * Get user's role by id
     *
     * @param int $id user id
     *
     * @access public
     * @return role
     * */
    function getRoleById($id)
    {
        if (null == $id) {
            return null;
        }

        $user = $this->find('first', array(
            'conditions' => array('id' => $id),
            'contain'    => array('Role'),
        ));

        return (!empty($user['Role'][0]['name'])) ? $user['Role'][0]['name'] : null;
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

        return $this->getRolesByRole($user['Role']);
    }

    /**
     * Get role names by role ids
     *
     * @param array $roles array of role ids
     *
     * @access public
     * @return role names
     * */
    function getRolesByRole($roles)
    {
        $ret = array();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $ret[$role['id']] = $role['name'];
            }
        }

        return $ret;
    }

    /**
     * Return true if roles is any other then student
     *
     * @param array $roles roles
     *
     * @access public
     * @return true if role isnt a student
     * */
    function hasTitle($roles)
    {
        $hasTitle = false;
        foreach ($roles as $key => $role) {
            if (is_array($role)) {
                if ('student' != $role['name']) {
                    $hasTitle = true;
                }
            } else {
                if ('student' != $role) {
                    $hasTitle = true;
                }
            }
        }

        return $hasTitle;
    }

    /**
     * Return true if role is a student
     *
     * @param array $roles roles
     *
     * @access public
     * @return true if role is a student
     * */
    function hasStudentNo($roles)
    {
        $hasStudentNo = false;
        foreach ($roles as $key => $role) {
            if (is_array($role)) {
                if (isset($role['name']) && ('student' == $role['name'])) {
                    $hasStudentNo = true;
                }
            } else {
                if ('student' == $role) {
                    $hasStudentNo = true;
                }
            }
        }

        return $hasStudentNo;
    }

    /**
     * Remove enrolled user from course. For student enrolment in a course.
     *
     * @param int $id        user id
     * @param int $course_id course id
     *
     * @access public
     * @return False on failure, true otherwise.
     * */
    function dropEnrolment($id, $course_id)
    {
        return $this->habtmDelete('Enrolment', $id, $course_id);
    }

    /**
     * Enroll user in a course. For student enrolment in a course.
     *
     * @param int $id        user id
     * @param int $course_id course id
     *
     * @access public
     * @return False on failure, true otherwise.
     * */
    function registerEnrolment($id, $course_id)
    {
        return $this->habtmAdd('Enrolment', $id, $course_id);
    }

    /**
     * Remove a user's role.
     *
     * @param int $id      user id
     * @param int $role_id role id
     *
     * @access public
     * @return False on failure, true otherwise.
     * */
    function dropRole($id, $role_id)
    {
        return $this->habtmDelete('Role', $id, $role_id);
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
    function getInstructors($type, $params)
    {
        $defaults = array('order' => $this->alias.'.first_name');
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

        $ret = array();

        if ('list' == $type) {
            // list doesn't auto join tables as the recursive is set to -1.
            $params['recursive'] = 0;
        }

        return $this->find($type, $params);
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

            if (count($u) > IMPORT_PASSWORD + 1) {
                $this->errorMessage[] = array('addUser' => sprintf(__('Invalid column number on line %d', true), $line));
                continue;
            }

            if (!isset($u[IMPORT_USERNAME]) || trim($u[IMPORT_USERNAME]) == '') {
                $this->errorMessage[] = array('addUser' => sprintf(__('Username can not be empty. line %d', true), $line));
                continue;
            }

            // handle password
            if (isset($u[IMPORT_PASSWORD])) {
                $u[IMPORT_PASSWORD] = trim($u[IMPORT_PASSWORD]);
            }
            if ($u[IMPORT_PASSWORD]) {
                App::import('Lib', 'neat_string');
                $u[IMPORT_PASSWORD] = PasswordGenerator::generate();
                $tmp['generated_password'] = true;
            }

            $tmp['username']     = $u[IMPORT_USERNAME];
            $tmp['first_name']   = isset($u[IMPORT_FIRSTNAME]) ? trim($u[IMPORT_FIRSTNAME]) : "";
            $tmp['last_name']    = isset($u[IMPORT_LASTNAME]) ? trim($u[IMPORT_LASTNAME]) : "";
            $tmp['student_no']   = isset($u[IMPORT_STUDENT_NO]) ? trim($u[IMPORT_STUDENT_NO]) : "";
            $tmp['email']        = isset($u[IMPORT_EMAIL]) ? trim($u[IMPORT_EMAIL]) : "";
            $tmp['tmp_password'] = $u[IMPORT_PASSWORD];
            $tmp['password']     = md5($u[IMPORT_PASSWORD]); // Will be hashed by the Users controller
            $tmp['creator_id']   = User::get('id');
            $data[$u[IMPORT_USERNAME]] = $tmp;
        }

        if (!count($data)) {
            $this->errorMessage[] = array('addUser' => __('No valid user to add', true));
            return false;
        }

        // remove the existings
        $existings = $this->getByUsernames(Set::extract('/username', array_values($data)));
        foreach ($existings as $key => $e) {
            if ($updateExisting) {
                $new = $data[$e['User']['username']];
                $tmp['username'] = $e['User']['username'];
                // update updatable column and changed field
                if ($e['User']['first_name'] != $new['first_name']) {
                    $tmp['first_name'] = $new['first_name'];
                }
                if ($e['User']['last_name'] != $new['last_name']) {
                    $tmp['last_name'] = $new['last_name'];
                }
                if ($e['User']['email'] != $new['email']) {
                    $tmp['email'] = $new['email'];
                }
                if ($e['User']['student_no'] != $new['student_no']) {
                    $tmp['student_no'] = $new['student_no'];
                }
                // ignore the password if not exists in import source
                if (!$new['generated_password']) {
                    $tmp['password'] = $new['password'];
                }
                // don't need creator_id either
                unset($tmp['creator_id']);

                $existings[$key]['User'] = $tmp;
            }
            // remove the existings from the data array
            unset($data[$e['User']['username']]);
        }

        if (!empty($data) && !($this->saveAll(array_values($data)))) {
            $this->errorMessage = array_merge($this->errorMessage, $this->validationErrors);
            return false;
        }

        if ($updateExisting) {
            if (!$this->saveAll($existings, array('validate' => false))) {
                return false;
            }
        }

        return array('created_students' => $data, 'updated_students' => Set::classicExtract($existings, '{n}.User'));
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

        return $roles;
    }


    /*********************************
     * Static functions
     * *******************************/

    /**
     * getInstance
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

        return $model->getCourseListByInstructor(self::get('id'));
    }


    /**
     * Given a user id and a role string, check if that user has that
     * role. E.g.: isRole(1, 'superadmin')
     *
     * @param int    $id   - the user id
     * @param string $role - the name of the role to check for
     *
     * @return true if the user $id has $role, false otherwise
     * */
    public function isRole($id, $role)
    {
        $data = $this->findById($id);
        foreach ($data['Role'] as $r) {
            if ($r['name'] == $role) {
                return true;
            }
        }
        return false;
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
        App::import('Component', 'Session');
        $Session = new SessionComponent();
        if (!($permission = $Session->read('ipeerSession.Permissions'))) {
            return false;
        }

        if (!Toolkit::isStartWith($aco, 'functions')) {
            // controller branch for acl tree, looking for permission directly
            return in_array($aco, $permission);
        } else {
            while (!empty($aco)) {
                if (in_array($aco, $permission)) {
                    return true;
                }

                // trace to a higher level of aco to see if we have permission there
                $aco = explode('/', $aco);
                array_pop($aco);
                $aco = implode('/', $aco);
            }
            return false;
        }

        return false;
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
        foreach ($check as $key => $val) {
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
