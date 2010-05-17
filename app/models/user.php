<?php
/* SVN FILE: $Id: user.php,v 1.15 2006/07/07 00:02:41 rrsantos Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.15 $
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

class User extends AppModel
{
  //The model name
  var $name = 'User';

  /* User Type - Admin, Instructor, TA, Student */
  var $USER_TYPE_ADMIN = 'A';
  var $USER_TYPE_INSTRUCTOR = 'I';
  var $USER_TYPE_TA = 'T';
  var $USER_TYPE_STUDENT = 'S';

  var $hasMany = array(
                 'UserCourse' =>
                     array('className'   => 'UserCourse',
                           'conditions'  => 'UserCourse.record_status = "A"',
                           'order'       => 'UserCourse.course_id ASC',
                           'limit'       => '99999',
                           'foreignKey'  => 'user_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     ),
                 'UserEnrol' =>
                     array('className'   => 'UserEnrol',
                           'conditions'  => 'UserEnrol.record_status = "A"',
                           'order'       => 'UserEnrol.course_id ASC',
                           'limit'       => '99999',
                           'foreignKey'  => 'user_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     )
              );

	//Overwriting Function - will be called before save operation
	function beforeSave(){
	  $allowSave = true;
	  if (empty($this->data[$this->name]['id'])) {
      //check the duplicate username
      $allowSave = $this->__checkDuplicateUsername();
    }
    return $allowSave;
	}

  //Validation check on duplication of username
	function __checkDuplicateUsername() {
	  $duplicate = false;
    $field = 'username';
    $value = $this->data[$this->name]['username'];
    if ($result = $this->find($field . ' = "' . $value.'"', $field.', id')){
		  if ($this->data[$this->name]['id'] == $result[$this->name]['id']) {
		    $duplicate = false;
		  } else {
  		  $duplicate = true;
  		}
     }

    if ($duplicate == true) {

      $this->errorMessage='Duplicate Username found. Please change the username of this user.';

      if ($this->data[$this->name]['role'] == 'S') {
        $this->errorMessage.='<br>If you want to enrol this student to one or more courses, use the enrol function on User Listing page.';
      }
      return false;
    }
    else {
      return true;
    }
	}

  function findUser ($username='', $password='') {
 		return $this->find("username = '".$username."' AND password = '".$password."'");
  }

  function findUserByStudentNo ($studentNo='') {
 		return $this->find("student_no = '".$studentNo."' ");
  }

  function findUserByid ($id='') {
 		return $this->find("id = '".$id."' ");
  }

  function getUserIdByStudentNo($studentNo=null) {
    $tmp = $this->find('student_no='.$studentNo);
    return $tmp['User']['id'];
  }


  function getEnrolledStudents($courseId=null,$fields=null,$conditions=null) {
    $condition = 'UserEnrol.course_id='.$courseId;
    if ($conditions != null) {
      $condition = $condition.' AND ('.$conditions.')';
    }
    $fields = 'User.id, User.role, User.username, User.first_name, User.last_name, User.student_no, User.title, User.email';
    $joinTable = array(' RIGHT JOIN user_enrols as UserEnrol ON User.id=UserEnrol.user_id');
    return $this->findAll($condition, $fields, 'User.last_name ASC', null, null, null, $joinTable );
  }
  
  function getUserByEmail($email='') {
  	return $this->find( "email='" . $email );
  }
  
  function findUserByEmailAndStudentNo($email='', $studentNo='') {
  	return $this->find("email='" .$email . "' AND student_no='" . $studentNo . "'");
  }
}

?>