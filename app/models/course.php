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

 
  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

  var $hasMany = array(
                  'Group' =>
                     array('className'   => 'Group',
                           'conditions'  => 'Group.record_status = "A"',
                           'order'       => 'Group.created DESC',
                           'foreignKey'  => 'course_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     ),
                  'Event' =>
                     array('className'   => 'Event',
                           'conditions'  => 'Event.record_status = "A"',
                           'order'       => 'Event.created DESC',
                           'foreignKey'  => 'course_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     ),
                  'Survey' =>
                     array('className'   => 'Survey',
                           'conditions'  => '',
                           'order'       => '',
                           'foreignKey'  => 'course_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                     )
              );

  var $hasAndBelongsToMany = array('Instructor' =>
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
                                         'dependent'    => false,
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
                                         'dependent'    => false,
                                         )
                                  );

  var $STATUS = array('I' => 'Inactive', 'A' => 'Active');


  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM user_enrols as enrol WHERE enrol.course_id = %s.id', $this->alias);
  }

  function getAllInstructors($type, $params = array()){
    return ClassRegistry::init('User')->getInstructors($type, $params);
  }
 
  function deleteInstructor($course_id, $user_id){
    return $this->habtmDelete('Instructor', $course_id, $user_id);
  }

  function addInstructor($course_id, $user_id){
    return $this->habtmAdd('Instructor', $course_id, $user_id);
  } 

  function getInactiveCourses(){
    return $this->find('all', array('conditions' => array('record_status' => 'I')));
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
      $this->errorMessage = "Please enter a title for this " . $this->name . ".";
      return false;
    }

    // Add an http or https to address
    if (!empty($this->data[$this->name]['homepage']) &&
        stripos($this->data[$this->name]['homepage'], "http") !== 0) {
      $this->data[$this->name]['homepage'] = "http://" . $this->data[$this->name]['homepage'];
    }

    // Remove any single quotes in the name, so that custom SQL queries are not confused.
    $this->data[$this->name]['title'] = str_replace("'", "", $this->data[$this->name]['title']);

    $allowSave = true;
    if (empty($this->data[$this->name]['course'])) { //temp ! to escape ajax bug
      $this->errorMessage='Course name is required.'; //check empty name
      $allowSave = false;
    } else {
      $allowSave = $this->__checkDuplicateCourse();//check the duplicate course
    }

    return $allowSave && parent::beforeSave();
  }

  //Validation check on duplication of course
  function __checkDuplicateCourse() {
    $duplicate = false;
    $field = 'course';
    $value = $this->data[$this->name]['course'];
    if ($result = $this->find('first', array('conditions' => array($field => $value)))){
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

  

#### Function modified by Tony (March 11/2011)
  // Find all accessible courses id
  function findAccessibleCoursesListByUserIdRole($userId=null, $userRole='', $condition=null){

/*
    if ($userRole == 'S') {
      $course =  $this->query('SELECT * FROM courses WHERE id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.') ORDER BY course');
      return $course;
    } else  if ($userRole == 'A') {
        if ($condition !=null) {
          return $this->query('SELECT * FROM courses WHERE '.$condition.' ORDER BY course');
        } else {
          return $this->query('SELECT * FROM courses ORDER BY course');
        }
      } else {
        if ($condition !=null) {
            $course =  $this->query('SELECT * FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        } else {
            $course =  $this->query('SELECT * FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        }
		return $course;
    }

    return false;
*/
  	
  	switch($userRole){
  		case 'S':
  			$course =  $this->query('SELECT * FROM courses WHERE id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.') ORDER BY course');
      		return $course;
      		break;
      		
  		case 'A':
  			if ($condition !=null){
          		return $this->query('SELECT * FROM courses WHERE '.$condition.' ORDER BY course');
          		break;
        }
        	else{
          		return $this->query('SELECT * FROM courses ORDER BY course');
          		break;
        }

  		default:
  			if($condition !=null){
            	$course =  $this->query('SELECT * FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        	}else{
           		$course =  $this->query('SELECT * FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' ) ORDER BY course');
        	}
				return $course;
    
  	}
  }

  // Find the record count of all accessible courses
  function findAccessibleCoursesCount($userId=null, $userRole=null, $condition=null){
  	
/*
    if ($userRole == 'S') {
        $course =  $this->query('SELECT COUNT(DISTINCT course_id) as total FROM user_enrols WHERE user_id = '.$userId);
        return $course;
    } else if ($userRole == 'A') {
        if ($condition !=null) {
          return $this->query('SELECT COUNT(*) AS total FROM courses WHERE '.$condition);
        } else {
          return $this->query('SELECT COUNT(*) AS total FROM courses');
        }
    } else {
        if ($condition !=null) {
          $course =  $this->query('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        } else {
          $course =  $this->query('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
        }
        return $course;
    }
*/

    switch($userRole){
    	case 'S':
    		$course =  $this->query('SELECT COUNT(DISTINCT course_id) as total FROM user_enrols WHERE user_id = '.$userId);
        	return $course;
        	break;
        	
    	case 'A':
    		//Check that admin is exists
    		$sql = 'SELECT COUNT( * ) as count
					FROM users U
					WHERE U.id = '.$userId.'';
    	$adminCount = $this->query($sql);
    	if($adminCount[0][0]['count']>=1){	
    		if ($condition !=null) return $this->query('SELECT COUNT(*) AS total FROM courses WHERE '.$condition);
    		else return $this->query('SELECT COUNT(*) AS total FROM courses');
    		break;
    	}
    	else return null;
    	break;
    	
    	default:
    		if ($condition !=null) return $this->query('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND '.$condition.' AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
    		else return $this->query('SELECT COUNT(*) as total FROM courses WHERE record_status = "A" AND id IN ( SELECT DISTINCT course_id FROM user_courses WHERE user_id = '.$userId.' )');
			break;    		
    }
    
  }

//Verifies that a user matches with his/her userRole.
  function verifyUserRole($userName=null, $userRole=null){
  
  	$this->User =& ClassRegistry::init('User');
  	$user = $this->User->getByUsername($userName);
  	$role = $user['User']['role'];

  	if($role == $userRole)return 1;
  	return 0;
  }
  
#### Function was modified by Tony (March 14/2011)
  // Generates SQL for querrying courses, only Instructors and admin can access this function.
  function generateRegisterCourseSQL($userId, $enrolled = true, $getCount = false,  $requester = null, $requester_role = null)
  {
  	//verify that all necessary inputs are not null && requester's role indeed matches with $requester_role
  	$isUserRoleMatch = $this->verifyUserRole($requester, $requester_role);
	if($userId==null || $requester==null || $requester_role==null || $isUserRoleMatch==0) return array(); 	
  	
  	$enrolled = $enrolled ? 'IN' : 'NOT IN';
  	$getCount = $getCount ? 'count(*) as total' : '*';
  	
  	//requester_role is 'Admin' or 'Instructor'
  	if($requester_role=='A' || $requester_role=='I'){
  		$sql = 'SELECT '.$getCount.' 
  				FROM courses C 
  				WHERE C.id '.$enrolled.' ( SELECT U.course_id 
  								FROM user_courses U 
  								WHERE U.user_id ='.$userId.')';
  		
  		return $sql;
  		}
  	//requester_role is 'Student' or invalid
  	else return array();
  	
  							
  	/*
    $getCount = $getCount ? 'count(*) as total' : '*';
    $enrolled = $enrolled ? 'IN' : 'NOT IN';

    $sql = 'SELECT '.$getCount.' FROM courses As Course ';
    $condition = 'Course.id '.$enrolled.' (SELECT course_id FROM user_enrols WHERE user_id = ' . $userId . ')';

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
    */
  }

  function findRegisteredCoursesList($user_id, $requester = null, $requester_role = null){
	return $this->query($this->generateRegisterCourseSQL($user_id, true, false, $requester, $requester_role));
  } 

  function findNonRegisteredCoursesList($user_id, $requester = null, $requester_role = null) {
    return $this->query($this->generateRegisterCourseSQL($user_id, false, false, $requester, $requester_role));
  }

  function findNonRegisteredCoursesCount($user_id, $requester = null, $requester_role = null){
    return $this->query($this->generateRegisterCourseSQL($user_id, false, true, $requester, $requester_role));
  }
  
  function getCourseName($id) {
    $tmp = $this->read(null, $id);
    return $tmp['Course']['course'];
  }

  function deleteAll($id=null) {
    //delete self
    if ($this->del($id)) {
      //delete user course,user enrol handled by hasMany
      $events = $this->Events->find('all', array('conditions' => array('course_id' => $id)));
      foreach ($events as $event)
        $this->Event->deleteAll($event['Event']['id']);
    }
  }

  function getEnrolledStudentCount($course_id) {
    return $this->Instructor->find('count', array('conditions' => array('Enrolment.id' => $course_id)));
  }
  
  function getCourseByCourse($course, $params) {
    return $this->find('all', array_merge(array('conditions' => array('course' => $course)), $params));
  }

  function getCourseByInstructor($instructor_id) {
    return $this->find('all', array('conditions' => array('Instructor.id' => $instructor_id),
                                    'fields' => array('Course.*')));
  }

##########################################################################################################     
##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
##########################################################################################################        
	
  	function createUserHelper( $id ='' , $username='' , $role='' ){
	
		$query = "INSERT INTO users VALUES ('$id','$role' ,'$username' , 'password', NULL , NULL , NULL , NULL , NULL , NULL , NULL , NULL , 'A', '0', '0000-00-00 00:00:00', NULL , NULL )";		
		$this->query($query);
	}
	
	function enrollUserHelper( $id=null, $user_id=null, $course_id=null, $role=''){
		
		if($role=='S'){
		$query = "INSERT INTO user_enrols VALUES ('$id', '$course_id', '$user_id','A', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->query($query);
		}
		
		if($role=='I'){
		$query = "INSERT INTO user_courses VALUES('$id' , '$user_id', '$course_id', 'O', 'A', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->query($query);
		}
	}
	
	function createCoursesHelper($id=null, $course=null, $title=null){
		
		$sql = "INSERT INTO courses VALUES ( '$id', '$course', '$title', NULL , 'off', NULL , 'A', '0', '0000-00-00 00:00:00', NULL , NULL , '0' ) "; 
		$this->query($sql);		
		
	}
	
	function createInactiveCoursesHelper($id=null, $course=null, $title=null){
		
		$sql = "INSERT INTO courses VALUES ( '$id', '$course', '$title', NULL , 'off', NULL , 'I', '0', '0000-00-00 00:00:00', NULL , NULL , '0' ) "; 
		$this->query($sql);		
		
	}
	
### Helper functions for testing purposes ###	
	function deleteAllTuples($table){	
		$sql = "DELETE FROM $table";
		$this->query($sql);
	}
  
  	function printHelp($temp){
  	$this->log($temp);
  	}
}
?>
