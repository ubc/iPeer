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

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

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
    $allowSave = true;

    if(!isset($this->data[$this->name]['id']) && empty($this->data[$this->name]['password'])) {
      $tmp_pw = NeatString::randomPassword(6);
      $this->data[$this->name]['password'] = md5($tmp_pw);
      $this->data[$this->name]['tmp_password'] = $tmp_pw;
    }

    // clear password to avoid updating to a empty one
    if(empty($this->data[$this->name]['password'])) {
      unset($this->data[$this->name]['password']);
    }
    return $allowSave && parent::beforeSave();
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
  function hasDuplicateUsername($username) {
    if ($this->find('first', array('conditions' => array('username' => $username)))) {
      $this->errorMessage='Duplicate Username found. Please change the username of this user.';
      /*if ($this->data[$this->name]['role'] == 'S') {
        $this->errorMessage.='<br>If you want to enrol this student to one or more courses, use the enrol function on User Listing page.';
        }*/
      return false;
    }

    return true;
  }

  function findUser ($username, $password) {
    return $this->find('first', array('conditions' => array('username' => $username,
                                                            'password' => $password)));
  }

  function findUserByStudentNo ($studentNo) {
    return $this->find('first', array('conditions' => array('student_no' => $studentNo,
                                                            )));
  }

  function findUserByid ($id, $params = array()) {
    return $this->find('first', array_merge(array('conditions' => array($this->name.'.id' => $id,
                                                            )),
                                            $params));
  }

  function getByUsername($username) {
    return $this->find('first', array('conditions' => array('username' => $username,
                                                            )));
  }

  function getUserIdByStudentNo($studentNo) {
    $tmp = $this->findUserByStudentNo($studentNo);
    return $tmp['User']['id'];
  }

  function getEnrolledStudents($course_id, $fields = array(), $conditions=null) {
    return $this->find('all', array('conditions' => array('Enrolment.id' => $course_id),
                                    'fields' => 'User.*',
                                    'order' => 'User.student_no'));
  }
  
  function printHelp($temp){
  	$this->log($temp);
  }

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


  function getUserByEmail($email='') {
    //return $this->find( "email='" . $email );
      return $this->find('first', array(
          'conditions' => array('email' => $email)
      ));

  }

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
    if('S' == $user['User']['role']) return false;
    if(!isset($user['UserCourse']) || !is_array($user['UserCourse'])) return false;
    
    foreach($user['UserCourse'] as $c)
    {
      if($c['course_id'] == $course_id) return true;
    }
    return false;
  }
  
  function getRoleText($role)
  {
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
    return $text;
  }

  function hashPasswords($data) {
    if (isset($data['User']['password'])) {
      $data['User']['password'] = md5($data['User']['password']);
      return $data;
    }
    return $data;
  }

  function getRoleById($id) {
    $user = $this->find('first', array('conditions' => array('id'=>$id)));
    return $user['Role'][0]['name'];
  }

  function getRoles($id) {
    $user = $this->read(null, $id);
    return $this->getRolesByRole($user['Role']);
  }

  function getRolesByRole($roles) {
    $ret = array();
    foreach($roles as $role) {
      $ret[$role['id']] = $role['name'];
    }
    return $ret;
  }

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

  function dropCourses($id, $course_id) {
    $this->habtmDelete('Course', $id, $course_id);
  }

  function registerCourses($id, $course_id) {
    $this->habtmAdd('Course', $id, $course_id);
  }

  function dropEnrolment($id, $course_id) {
    $this->habtmDelete('Enrolment', $id, $course_id);
  }

  function registerEnrolment($id, $course_id) {
    $this->habtmAdd('Enrolment', $id, $course_id);
  }
   
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
      // list doesn't auto join tables. Have to do it manually
      $temp = $this->find('all', $params);
      foreach($temp as $t) {
        $ret[$t['User']['id']] = $t['User'][$this->displayField];
      }
    } else {
      $ret = $this->find($type, $params);
    }

    return $ret;
  }

  function getStudentsNotInGroup($group_id, $type = 'all') {
  	

  
  	
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

  function getMembersByGroupId($group_id, $type = 'all') {
//    $ret = array();
//    $this->displayField = 'student_no_with_full_name';
//    if('list' != $type) {
//      $ret = $this->find($type, array('conditions' => array('Group.id' => $group_id)));
//    } else {
//      $data = $this->find('all', array('conditions' => array('Group.id' => $group_id)));
//      foreach($data as $d) {
//        $ret[$d[$this->alias]['id']] = $d[$this->alias][$this->displayField];
//      }
//    }
//    return $ret;
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
  
function getCurrentLoggedInUser(){

      App::import('Component', 'Session');
    $Session = new SessionComponent();
    $user = $Session->read('Auth.User');
    return $user;
  
  	
  }
}
?>
