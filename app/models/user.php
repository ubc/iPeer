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
 * @lastmodified $Date: 2006/07/07 00:02:41 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * User
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

class User extends AppModel
{
  //The model name
  var $name = 'User';
  var $displayField = 'full_name';
  var $unhashed_password = '';

  /* User Type - Admin, Instructor, TA, Student */
  var $USER_TYPE_ADMIN = 'A';
  var $USER_TYPE_INSTRUCTOR = 'I';
  var $USER_TYPE_TA = 'T';
  var $USER_TYPE_STUDENT = 'S';

  var $_schema = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'length' => 80, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'role' => array('type' => 'string', 'null' => false, 'default' => 'S', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'student_no' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'last_logout' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'last_accessed' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'updater_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

  var $hasMany =array('Submission' => array('className' => 'EvaluationSubmission',
                                            'foreignKey' => 'submitter_id',
                                            'dependent' => true));
/*  var $hasMany = array('UserCourse' => array('className'   => 'UserCourse',
                                             'conditions'  => 'UserCourse.record_status = "A"',
                                             'order'       => 'UserCourse.course_id ASC',
                                             'limit'       => '99999',
                                             'foreignKey'  => 'user_id',
                                             'dependent'   => true,
                                             'exclusive'   => false,
                                             'finderSql'   => ''),
                       'UserEnrol' => array('className'   => 'UserEnrol',
                                            'conditions'  => 'UserEnrol.record_status = "A"',
                                            'order'       => 'UserEnrol.course_id ASC',
                                            'limit'       => '99999',
                                            'foreignKey'  => 'user_id',
                                            'dependent'   => true,
                                            'exclusive'   => false,
                                            'finderSql'   => '')
                      );*/

  var $hasAndBelongsToMany = array('Course' =>
                                   array('className'    => 'Course',
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
                                   'Enrolment' =>
                                   array('className'    => 'Course',
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
                                   'Group' =>
                                   array('className'    => 'Group',
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
                                   'Role' =>
                                   array('className'    => 'Role',
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
                                        )
                                   );

  var $validate = array('username'  => array('character' => array('rule' => 'alphaNumeric',
                                                                  'required' => true,
                                                                  'message' => 'Alphabets and numbers only'),
                                             'minLength' => array('rule' => array('minLength', 6),
                                                              'message' => 'Usernames must be at least 6 characters long'),
                                             'unique' => array('rule' => 'isUnique',
                                                               'message' => 'Duplicate Username found. Please change the username.')),
                        'email'     => array('rule' => 'email',
                                             'required' => false,
                                             'allowEmpty' => true,
                                             'message' => 'Invalid email format'));

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['full_name'] = sprintf('CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias);
    $this->virtualFields['student_no_with_full_name'] = sprintf('CONCAT(%s.student_no, " - ", %s.first_name, " ", %s.last_name)', $this->alias, $this->alias, $this->alias);
  }
  
  //Overwriting Function - will be called before save operation
  function beforeSave() {
    if(!isset($this->data[$this->name]['id']) && empty($this->data[$this->name]['password'])) {
      $tmp_pw = NeatString::randomPassword(6);
      $this->data[$this->name]['password'] = md5($tmp_pw);
      $this->data[$this->name]['tmp_password'] = $tmp_pw;
    }

    // clear password to avoid updating to a empty one
    if(empty($this->data[$this->name]['password'])) {
      unset($this->data[$this->name]['password']);
    }
    return parent::beforeSave();
  }

 function find($conditions = array(), $fields = array(), $order = null, $recursive = null) {
    if(!isset($fields)) {
    } elseif ($fields === false) {
      $fields = array('Creator', 'Updater');
    } else {
      $fields = array_merge((array)$fields, array('Creator', 'Updater'));
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
   * 
   * Find user by username and password
   * @param $username user's username
   * @param $password user's password
   * @return array with user data
   */
  
  function findUser ($username, $password) {
    return $this->find('first', array('conditions' => array('username' => $username,
                                                            'password' => $password)));
  }

  /**
   * 
   * Find user by student number
   * @param $studentNo student number
   * @return array with user data
   */
  function findUserByStudentNo ($studentNo = null) {
    if(!empty($studentNo)){
    return $this->find('first', array('conditions' => array('student_no' => $studentNo,
                                                            )));}
  }

  /**
   * 
   * Get user by user id
   * @param $id user id
   * @param $params search parameters
   * @return array with user data
   */
  function findUserByid ($id, $params = array()) {
    return $this->find('first', array_merge(array('conditions' => array($this->name.'.id' => $id,
                                                            )),
                                            $params));
  }
  
  function findUserByidWithFields($id , $fields = array()){
  	$result = $this->find('first', array('conditions' => array('User.id' => $id), 
  										 'fields' => $fields));
  	return $result['User'];
  }

  /**
   * 
   * Get user by username
   * @param $username user's username
   * @return array with user data
   */
  function getByUsername($username) {
    return $this->find('first', array('conditions' => array('username' => $username,
                                                            )));
  }

  function getByUsernames($usernames) {
    return $this->find('all', array('conditions' => array('username' => $usernames,
                                                         )));
  }

  /**
   * 
   * Get user id by student no
   * @param $studentNo student number
   * @return user id
   */
  function getUserIdByStudentNo($studentNo) {
    if(!empty($studentNo)){
    $tmp = $this->findUserByStudentNo($studentNo);
    return $tmp['User']['id']; }
  }

  /**
   * 
   * Get student enrolled in a course
   * @param $course_id course id
   * @param $fields fields to return
   * @param $conditions conditions of search
   * @return students enrolled in a course
   */
  function getEnrolledStudents($course_id, $fields = array(), $conditions=null) {
    return $this->find('all', array('conditions' => array('Enrolment.id' => $course_id),
                                    'fields' => 'User.*',
                                    'order' => 'User.student_no'));
  }

  /**
   * 
   * Get list of student enrolled students
   * @param $course_id course id
   * @return list of enrolled students
   */
  
  function getEnrolledStudentsForList($course_id) {
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
   * 
   * Get user by email
   * @param $email user's email
   * @return array with user data
   */

  function getUserByEmail($email='') {
    //return $this->find( "email='" . $email );
      return $this->find('first', array(
          'conditions' => array('email' => $email)
      ));
  }

  /**
   * 
   * Get user by email and student number
   * @param $email user's email
   * @param $studentNo user's student number
   * @return array with user data
   */
  function findUserByEmailAndStudentNo($email='', $studentNo='') {
    //return $this->find("email='" .$email . "' AND student_no='" . $studentNo . "'");
      return $this->find('first', array(
          'conditions' => array('email' => $email, 'student_no'=> $studentNo)
      ));
  }

  /**
   * canRemoveCourse check if user has permission to remove the course from a
   * student
   *
   * @param mixed $user the user array returned by findUserByxxxx
   * @param mixed $course_id target course id
   * @access public
   * @return boolean whether or not user can remove the course from the student
   */
  function canRemoveCourse($user, $course_id)
  {

    if(!isset($user['User']) || !is_array($user['User'])) return false;
    if('A' == $user['User']['role']) return true;
    if('SA' == $user['User']['role']) return true;
    if('S' == $user['User']['role']) return false;
    if(!isset($user['Course']) || !is_array($user['Course'])) return false;
    
    foreach($user['Course'] as $c)
    {
      if($c['id'] == $course_id) return true;
    }
    return false;
  }
  
  /**
   * 
   * Get full name of a role by letter abbreviation
   * @param $role abbreviation
   * @return full name of a role
   */
  
  function getRoleText($role)
  {
    if(!empty($role)){
  	$ROLE_TEXT = array('A'  => 'Administrator',
                       'I'  => 'Instructor',
                       'T'  => 'TA',
                       'S'  => 'Student');
    if(isset($ROLE_TEXT[$role]))
    {
      $text = $ROLE_TEXT[$role];
    }else{
      $text = 'Unknown';
    }
    return $text;}
    else return null;
  }
  /**
   * 
   * Hash password
   * @param $data array containing password
   * @return hashed password
   */
  function hashPasswords($data) {
    if (isset($data['User']['password'])) {
      $data['User']['password'] = md5($data['User']['password']);
      return $data;
    }
    return $data;
  }

  /**
   * 
   * Get user's role by id
   * @param $id user id
   * @return role
   */
  function getRoleById($id) {
    
    $user = $this->find('first', array('conditions' => array('id'=>$id)));
    return (!empty($user['Role'][0]['name'])) ? $user['Role'][0]['name'] : null;
  }
  
  /**
   * 
   * Get user's roles
   * @param $id user id
   * @return array of user's roles
   */

  function getRoles($id) {
    $user = $this->read(null, $id);
    return $this->getRolesByRole($user['Role']);
  }

  /**
   * 
   * Get role names by role ids
   * @param $roles array of role ids
   * @return role names
   */
  function getRolesByRole($roles) {
    $ret = array();
    if(!empty($roles)){
    foreach($roles as $role) {
      $ret[$role['id']] = $role['name'];
    }}
    return $ret;
  }

  /**
   * 
   * Return true if roles is any other then student
   * @param $roles roles
   * @return true if role isnt a student
   */
  function hasTitle($roles) {
    $hasTitle = false;
    foreach($roles as $key => $role) {
      if(is_array($role)) {
        if('student' != $role['name']) {
          $hasTitle = true;
        }
      } else {
        if('student' != $role) {
          $hasTitle = true;
        }
      }
    }
    return $hasTitle;
  }

  /**
   * 
   * Return true if role is a student
   * @param $roles roles
   * @return true if role is a student
   */
  function hasStudentNo($roles) {
    $hasStudentNo = false;
    foreach($roles as $key => $role) {
      if(is_array($role)) {
        if(isset($role['name']) && ('student' == $role['name'])) {
          $hasStudentNo = true;
        }
      } else {
        if('student' == $role) {
          $hasStudentNo = true;
        }
      }
    }
    return $hasStudentNo;
  }
  
  /**
   * Remove enrolled user from course. For student enrolment in a course.
   *
   * @param $id user id
   * @param $course_id course id
   * @return False on failure, true otherwise.
   */
  function dropEnrolment($id, $course_id) {
    return $this->habtmDelete('Enrolment', $id, $course_id);
  }

  /**
   * Enroll user in a course. For student enrolment in a course.
   *
   * @param $id user id
   * @param $course_id course id
   * @return False on failure, true otherwise.
   */
  function registerEnrolment($id, $course_id) {
    return $this->habtmAdd('Enrolment', $id, $course_id);
  }
   
  /**
   * Remove a user's role.
   *
   * @param $id user id
   * @param $role_id role id
   * @return False on failure, true otherwise.
   */
  function dropRole($id, $role_id) {
    return $this->habtmDelete('Role', $id, $role_id);
  }

  /**
   * Assign user a role.
   *
   * @param $id user id
   * @param $role_id role id
   * @return False on failure, true otherwise.
   */
  function registerRole($id, $role_id) {
    return $this->habtmAdd('Role', $id, $role_id);
  }

  /**
   * 
   * Get list of instructors
   * @param $type type of search: all, first, list
   * @param $params search parameters
   * @return list of instructors
   */
  function getInstructors($type, $params) {
    $defaults = array('order' => $this->alias.'.first_name');
    $params = array_merge($defaults, $params);

    if(array_key_exists('excludes', $params) && !empty($params['excludes'])) {
      $ids = array();
      if(!is_numeric($params['excludes'][0])) {
        // if the instructors are in full data array format, extract from id attributes
        foreach($params['excludes'] as $i) {
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

    if('list' == $type) {
      // list doesn't auto join tables as the recursive is set to -1. 
      $params['recursive'] = 0;
    } 

    return $this->find($type, $params);
  }

  /**
   * 
   * Get students not in group	
   * @param $group_id group id
   * @param $type type of search
   * @return search results
   */
  function getStudentsNotInGroup($group_id, $type = 'all') {
  	

  if($group_id = null) {return false;}
  	
    $groups_member = Classregistry::init('GroupsMember');
    $dbo = $groups_member->getDataSource();
    $subQuery = $dbo->buildStatement(array('fields' => array('GroupsMember.user_id'),
                                           'table' => $dbo->fullTableName($groups_member),
                                           'alias' => 'GroupsMember',
                                           'limit' => null,
                                           'offset' => null,
                                           'joins' => array(),
                                           'conditions' => array('group_id' => $group_id),
                                           'order' => null,
                                           'group' => null),
                                     $groups_member);
    $subQuery = ' `'.$this->alias.'`.`id` NOT IN (' . $subQuery . ') ';
    $subQueryExpression = $dbo->expression($subQuery);
    $this->displayField = 'student_no_with_full_name';
    return $this->find($type, array('conditions' => array('GM.id' => $group_id, $this->alias.'.role' => 'S',
                                                          $subQueryExpression),
                                    'joins' => array(array('table' => 'user_enrols',
                                                           'alias' => 'UserEnrol',
                                                           'type'  => 'LEFT',
                                                           'conditions' => array($this->alias.'.id = UserEnrol.user_id')),
                                                     array('table' => 'groups',
                                                           'alias' => 'GM',
                                                           'type'  => 'LEFT',
                                                           'conditions' => array('UserEnrol.course_id = GM.course_id'))
                                                    ),
                                    'order' => array($this->alias.'.student_no'),
                                    'recursive' => 1));
 	                            
  }
  
  /**
   * 
   * Get members in a group
   * @param $group_id group id
   * @param $type type of search
   * @return search result
   */
  function getMembersByGroupId($group_id, $type = 'all') {

    $groups_member = Classregistry::init('GroupsMember');
    $dbo = $groups_member->getDataSource();
    $subQuery = $dbo->buildStatement(array('fields' => array('GroupsMember.user_id'),
                                           'table' => $dbo->fullTableName($groups_member),
                                           'alias' => 'GroupsMember',
                                           'limit' => null,
                                           'offset' => null,
                                           'joins' => array(),
                                           'conditions' => array('group_id' => $group_id),
                                           'order' => null,
                                           'group' => null),
                                     $groups_member);
    $subQuery = ' `'.$this->alias.'`.`id` IN (' . $subQuery . ') ';
    $subQueryExpression = $dbo->expression($subQuery);
    $this->displayField = 'student_no_with_full_name';
    return $this->find($type, array('conditions' => array('GM.id' => $group_id, $this->alias.'.role' => 'S',
                                                          $subQueryExpression),
                                    'joins' => array(array('table' => 'user_enrols',
                                                           'alias' => 'UserEnrol',
                                                           'type'  => 'LEFT',
                                                           'conditions' => array($this->alias.'.id = UserEnrol.user_id')),
                                                     array('table' => 'groups',
                                                           'alias' => 'GM',
                                                           'type'  => 'LEFT',
                                                           'conditions' => array('UserEnrol.course_id = GM.course_id'))
                                                    ),
                                    'order' => array($this->alias.'.student_no'),
                                    'recursive' => 1));
  }
  
  /**
   * 
   * Get current logged in user
   * @return $user logged in user
   */
  function getCurrentLoggedInUser(){
    App::import('Component', 'Session');
    $Session = new SessionComponent();
    $user = $Session->read('Auth.User');
    return $user;
  }

  /**
   * addUserByArray add users with an array
   * 
   * @param mixed $userList array list of users
   * @access public
   * @return array result
   */
  function addUserByArray($userList, $updateExisting = false) {
    $data = array();

    foreach($userList as $line => $u) {
      $tmp = array();

      if(count($u) > IMPORT_PASSWORD + 1) {
        $this->errorMessage[] = array('addUser' => sprintf(__('Invalid column number on line %d', true), $line));
        continue;
      }

      if(!isset($u[IMPORT_USERNAME]) || trim($u[IMPORT_USERNAME]) == '') {
        $this->errorMessage[] = array('addUser' => sprintf(__('Username can not be empty. line %d', true), $line));
        continue;
      }

      // handle password
      if (isset($u[IMPORT_PASSWORD])) {
        $u[IMPORT_PASSWORD] = trim($u[IMPORT_PASSWORD]);
      } 
      if($u[IMPORT_PASSWORD]) {
        App::import('Lib', 'neat_string');
        $u[IMPORT_PASSWORD] = NeatString::randomPassword(6);
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

    if(!count($data)) {
      $this->errorMessage[] = array('addUser' => __('No valid user to add', true));
      return false;
    }

    // remove the existings
    $existings = $this->getByUsernames(Set::extract('/username', array_values($data)));
    foreach($existings as $e) {
      unset($data[$e['User']['username']]);
    }

    if(!empty($data) && !($this->saveAll(array_values($data)))) {
      $this->errorMessage = array_merge($this->errorMessage, $this->validationErrors);
      return false;
    }

    if($updateExisting) {
      foreach($existings as $key => $e) {
        $new = $data[$e['User']['username']];
        $tmp['username'] = $e['User']['username'];
        // update updatable column and changed field
        if($e['User']['first_name'] != $new['first_name']) {
          $tmp['first_name'] = $new['first_name'];
        }
        if($e['User']['last_name'] != $new['last_name']) {
          $tmp['last_name'] = $new['last_name'];
        }
        if($e['User']['email'] != $new['email']) {
          $tmp['email'] = $new['email'];
        }
        if($e['User']['student_no'] != $new['student_no']) {
          $tmp['student_no'] = $new['student_no'];
        }
        // ignore the password if not exists in import source
        if(!$new['generated_password']) {
          $tmp['password'] = $new['password'];
        }
        // don't need creator_id either
        unset($tmp['creator_id']);

        $existings[$key]['User'] = $tmp;
      }
      if(!$this->saveAll($existings)) {
        return false;
      }
    }

    return array('created_students' => $data, 'updated_students' => $existings);

    /*  if ($this->User->save($data))
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
    return $result;*/
  }

  /*********************************
   * Static functions
   * *******************************/
  function getInstance($user=null) {
    static $instance = array();

    if ($user) {
      $instance[0] =& $user;
    }

    if (!$instance) {
      return null;
    }

    return $instance[0];
  }

  function store($user) {
    if (empty($user)) {
      return false;
    }

    User::getInstance($user);
  }

  function get($path) {
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

  function isLoggedIn() {
    return self::getInstance() !== null;
  }

  function getMyCourses() {
    $model = Classregistry::init('Course');
    return $model->getCourseByInstructor(self::get('id'));
  }

  function getMyCourseList() {
    $model = Classregistry::init('Course');
    return $model->getCourseListByInstructor(self::get('id'));
  }
}
?>
