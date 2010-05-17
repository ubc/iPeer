<?php
/* SVN FILE: $Id: course.php,v 1.15 2006/08/31 21:03:09 davychiu Exp $ */

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
 * @lastmodified $Date: 2006/08/31 21:03:09 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Course
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Course extends AppModel
{
  var $name = 'Course';
  var $hasMany = array(
                  'UserCourse' =>
                     array('className'   => 'UserCourse',
                           'conditions'  => 'UserCourse.record_status = "A"',
                           'order'       => 'UserCourse.created DESC',
                           'limit'       => '99999',
                           'foreignKey'  => 'course_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     ),
                  'UserEnrol' =>
                     array('className'   => 'UserEnrol',
                           'conditions'  => 'UserEnrol.record_status = "A"',
                           'order'       => 'UserEnrol.created DESC',
                           'limit'       => '99999',
                           'foreignKey'  => 'course_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     ),
                  'Event' =>
                     array('className'   => 'Event',
                           'conditions'  => 'Event.record_status = "A"',
                           'order'       => 'Event.created DESC',
                           'limit'       => '99999',
                           'foreignKey'  => 'course_id',
                           'dependent'   => false,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     )
              );

	function findInstructors(){
		return $this->findBySql("SELECT * FROM users WHERE role='I'");
	}

	function findUniqueInstructors($course_id){
		return $this->findBySql("SELECT * FROM users WHERE role='I' AND id NOT IN (SELECT user_id AS id FROM user_courses WHERE course_id=$course_id)");
	}

	function findUsers(){
		return $this->findBySql("SELECT * FROM users");
	}

	function getInstructors($instructor_id=null){
		return $this->findBySql("SELECT first_name, last_name, id FROM users WHERE id=$instructor_id");
	}

	function deleteInstructor($user_id, $course_id){
		$this->query('DELETE FROM user_courses WHERE user_id='.$user_id.' AND course_id='.$course_id);
	}

	function getInactiveCourses(){
		return $this->findBySql("SELECT * FROM courses As Course WHERE record_status = 'I'");
	}

	function prepData($data=null){
	  if (empty($data['data']['Course']['record_status'])) {
  		$data['data']['Course']['record_status'] = $data['form']['record_status'];
  	}


		if( !empty($data['form']['self_enroll']))
			$data['data']['Course']['self_enroll'] = "on";
		else
			$data['data']['Course']['self_enroll'] = "off";

		for( $i=1; $i<=$data['data']['Course']['count']; $i++ ){
			$data['data']['Course']['instructor_id'.$i] = isset($data['form']['instructor_id'.$i])? $data['form']['instructor_id'.$i] : '';
		}

		return $data;
	}

	//Overwriting Function - will be called before save operation
	function beforeSave(){
	  $allowSave = true;
	  if (empty($this->data[$this->name]['course'])) { //temp ! to escape ajax bug
		  $this->errorMessage='Course name is required.'; //check empty name
			$allowSave = false;
    } else {
		  $allowSave = $this->__checkDuplicateCourse();//check the duplicate course
		}
	   return $allowSave;
	}

  //Validation check on duplication of course
	function __checkDuplicateCourse() {
		$duplicate = false;
		$field = 'course';
		$value = $this->data[$this->name]['course'];
		if ($result = $this->find($field . ' = "' . $value.'"', $field.', id')){
		  if ($this->data[$this->name]['id'] == $result[$this->name]['id']) {
		    $duplicate = false;
		  } else {
  		  $duplicate = true;
  		}
		 }

		if ($duplicate == true) {
		  $this->errorMessage='Duplicate Course found. Please change the course name.';
		  return false;
		}
		else {
		  return true;
		}
	}
  // Find all accessible courses id
  function findAccessibleCoursesList($user=null){
    $userId=$user['id'];
    $userRole=$user['role'];
    return $this->findAccessibleCoursesListByUserIdRole($userId, $userRole);
  }

  // Find all accessible courses id
  function findAccessibleCoursesListByUserIdRole($userId=null, $userRole=null, $condition=null){

    if ($userRole == 'S') {
      $course =  $this->findBySql('SELECT * FROM courses WHERE id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.') ORDER BY course');
      return $course;
    }
    else {
      if ($userId == 1) {
        if ($condition !=null) {
          return $this->findBySql('SELECT * FROM courses WHERE '.$condition.' ORDER BY course');
        } else {
          return $this->findBySql('SELECT * FROM courses ORDER BY course');
        }
      }
      else {
        if ($condition !=null) {
          $course =  $this->findBySql('SELECT * FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        } else {
          $course =  $this->findBySql('SELECT * FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        }
        return $course;
      }
    }
  }

  // Find the record count of all accessible courses
  function findAccessibleCoursesCount($userId=null, $userRole=null, $condition=null){

    if ($userRole == 'S') {
      $course =  $this->findBySql('SELECT COUNT(DISTINCT course_id) as total FROM user_enrols WHERE user_id = '.$userId);
      return $course;
    }
    else {
      if ($userId == 1) {
        if ($condition !=null) {
          return $this->findBySql('SELECT COUNT(*) AS total FROM courses WHERE '.$condition);
        } else {
          return $this->findBySql('SELECT COUNT(*) AS total FROM courses');
        }
      }
      else {
        if ($condition !=null) {
          $course =  $this->findBySql('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        }else {
          $course =  $this->findBySql('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        }
        return $course;
      }
    }
  }

  // Find all accessible courses id
  function findRegisteredCoursesList($userId=null){

    $course =  $this->findBySql('SELECT * FROM courses AS Course WHERE Course.id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.' )');
    return $course;
  }

  function findNonRegisteredCoursesList($userId=null) {
    $course = $this->findBySql('SELECT * FROM courses As Course WHERE Course.id NOT IN (SELECT DISTINCT course_id FROM user_enrols WHERE user_id = ' . $userId . ')');
    return $course;
  }

  function findNonRegisteredCoursesCount($userId=null){
  	$course = $this->findBySql('SELECT COUNT(*) AS total FROM courses As Course WHERE Course.id NOT IN (SELECT DISTINCT course_id FROM user_enrols WHERE user_id = ' . $userId . ')');
    return $course;
  }

  function getCourseName($id=null) {
    $tmp = $this->find('id='.$id);
    return $tmp['Course']['course'];
  }

  function deleteAll($id=null) {
    //delete self
    if ($this->del($id)) {
      //delete user course,user enrol handled by hasMany
      $events = $this->Events->findAllByCourseId($id);
      foreach ($events as $event)
        $this->Event->deleteAll($event['Event']['id']);
      //
    }
  }
}
?>