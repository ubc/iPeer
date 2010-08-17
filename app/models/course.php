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

  var $hasAndBelongsToMany = array('User' =>
                                   array('className'    =>  'User',
                                         'joinTable'    =>  'user_courses',
                                         'foreignKey'   =>  'course_id',
                                         'associationForeignKey'    =>  'user_id',
                                         'conditions'   =>  '',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
                                         ),
                                   'Enrol' =>
                                   array('className'    =>  'User',
                                         'joinTable'    =>  'user_enrols',
                                         'foreignKey'   =>  'course_id',
                                         'associationForeignKey'    =>  'user_id',
                                         'conditions'   =>  'Enrol.role = "S"',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
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
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['title'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

      // Remove any single quotes in the name, so that custom SQL queries are not confused.
      $this->data[$this->name]['title'] =
        str_replace("'", "", $this->data[$this->name]['title']);

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
    } else  if ($userRole == 'A') {
        if ($condition !=null) {
          return $this->findBySql('SELECT * FROM courses WHERE '.$condition.' ORDER BY course');
        } else {
          return $this->findBySql('SELECT * FROM courses ORDER BY course');
        }
      } else {
        if ($condition !=null) {
            $course =  $this->findBySql('SELECT * FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        } else {
            $course =  $this->findBySql('SELECT * FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        }

        return $course;
    }

    return false;

  }

  // Find the record count of all accessible courses
  function findAccessibleCoursesCount($userId=null, $userRole=null, $condition=null){

    if ($userRole == 'S') {
        $course =  $this->findBySql('SELECT COUNT(DISTINCT course_id) as total FROM user_enrols WHERE user_id = '.$userId);
        return $course;
    } else if ($userRole == 'A') {
        if ($condition !=null) {
          return $this->findBySql('SELECT COUNT(*) AS total FROM courses WHERE '.$condition);
        } else {
          return $this->findBySql('SELECT COUNT(*) AS total FROM courses');
        }
    } else {
        if ($condition !=null) {
          $course =  $this->findBySql('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        } else {
          $course =  $this->findBySql('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        }
        return $course;
    }

    return false;
  }

  // Find all accessible courses id
  function generateRegisterCourseSQL($userId, $register = true, $count = false,  $requester = null, $requester_role = null)
  {
    $count = $count ? 'count(*) as total' : '*';
    $register = $register ? 'IN' : 'NOT IN';

    $sql = 'SELECT '.$count.' FROM courses As Course ';
    $condition = 'Course.id '.$register.' (SELECT course_id FROM user_enrols WHERE user_id = ' . $userId . ')';

    if(null == $requester_role && null == $requester)
    {
      return array();
    }
    elseif(null == $requester_role && null != $requester)
    {
      $user = new User();
      $user->read(null, $requester);
      $requester_role = $user['role'];
    }

    if('A' != $requester_role)
    {
      $sql .= ' LEFT JOIN user_courses as UserCourse ON Course.id = UserCourse.course_id ';
      $condition .= ' AND UserCourse.user_id = '.$requester;
    }

    $sql .= ' WHERE '.$condition;

    return $sql;
  }

  function findRegisteredCoursesList($user_id, $requester = null, $requester_role = null){
    return $this->findBySql($this->generateRegisterCourseSQL($user_id, true, false, $requester, $requester_role));
  }

  function findNonRegisteredCoursesList($user_id, $requester = null, $requester_role = null) {
    return $this->findBySql($this->generateRegisterCourseSQL($user_id, false, false, $requester, $requester_role));
  }

  function findNonRegisteredCoursesCount($user_id, $requester = null, $requester_role = null){
    return $this->findBySql($this->generateRegisterCourseSQL($user_id, false, true, $requester, $requester_role));
  }

  function getCourseName($id=null) {
    $tmp = $this->find('id='.$id);
    return $tmp['Course']['course'];
  }

  function deleteAll($id=null) {
    //delete self
    if ($this->del($id)) {
      //delete user course,user enrol handled by hasMany
      $events = $this->Events->findAll('course_id = '.$id);
      foreach ($events as $event)
        $this->Event->deleteAll($event['Event']['id']);
      //
    }
  }

  function getEnrolledStudentCount($course_id) {
    $course = $this->find('id = '.$course_id);
    if(null == $course) return;
    return count($course['Enrol']);
  }
}
?>
