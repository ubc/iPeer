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
 
  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

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
                                         'conditions'   =>  /*'Enrol.role = "S"'*/'',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
                                         'dependent'    => false,
                                         )
                                  );

  /* Record Status - Active, Inactive */
  const STATUS_ACTIVE = 'A';
  const STATUS_INACTIVE = 'I';
  var $STATUS = array(
	  self::STATUS_ACTIVE => 'Active',
	  self::STATUS_INACTIVE => 'Inactive' 
  );


  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM user_enrols as enrol WHERE enrol.course_id = %s.id', $this->alias);
  }

  /**
   * 
   * Get instructors
   * @param unknown_type $type search type
   * @param unknown_type $params search parameters
   * @return List of instructors
   */
  
  function getAllInstructors($type, $params = array()){
    return ClassRegistry::init('User')->getInstructors($type, $params);
  }
 
  /**
   * 
   * Delete instructor from a course
   * @param unknown_type $course_id course id
   * @param unknown_type $user_id user id
   */
  function deleteInstructor($course_id, $user_id){
    return $this->habtmDelete('Instructor', $course_id, $user_id);
  }

  /**
   * 
   * Add instructor to a course
   * @param unknown_type $course_id course id
   * @param unknown_type $user_id user id
   */
  function addInstructor($course_id, $user_id){
    $user = ClassRegistry::init('User')->findUserByid($user_id);
    if($user['User']['role'] != 'S') { 
      return $this->habtmAdd('Instructor', $course_id, $user_id);
    }
  } 
  
  /**
   * 
   * Get inactive courses
   * @return list of inactive courses
   */
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
  
  /**
   * 
   * Find all accessible courses id
   * @param unknown_type $user user data
   * @return list of accessible courses
   */  

  function findAccessibleCoursesList($user=null){
    $userId=$user['id'];
    $userRole=$user['role'];
    return $this->findAccessibleCoursesListByUserIdRole($userId, $userRole);
  }

  /**
   * 
   * Find all accessible courses id
   * @param unknown_type $userId user id
   * @param unknown_type $userRole user role
   * @param unknown_type $condition search conditions
   * @return list of course ids
   */

  function findAccessibleCoursesListByUserIdRole($userId=null, $userRole='', $condition=null){

  	
  	switch($userRole){
   case 'S':
        //$course =  $this->query('SELECT * FROM courses WHERE id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.') ORDER BY course');
        $conditionsSubQuery['UserEnrol.user_id'] = $userId;
        $dbo = $this->UserEnrol->getDataSource();
        $subQuery = $dbo->buildStatement(
        array(
            'fields' => array('DISTINCT UserEnrol.course_id'),
            'table' => $dbo->fullTableName($this->UserEnrol),
            'alias' => 'UserEnrol',
            'conditions' => $conditionsSubQuery,
            'order' => null,
            'limit' => null,
            'group' => null
          ),
          $this->UserEnrol
        );
        $subQuery = 'Course.id IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);
        $conditions[] = $subQueryExpression;
        $this->recursive = 0;
        $course = $this->find('all', compact('conditions'));
        return $course;
      break;
      
    case 'I':
        //$course =  $this->query('SELECT * FROM courses WHERE id IN ( SELECT DISTINCT course_id FROM user_enrols WHERE user_id = '.$userId.') ORDER BY course');
        $conditionsSubQuery['UserCourse.user_id'] = $userId;
        $dbo = $this->UserCourse->getDataSource();
        $subQuery = $dbo->buildStatement(
        array(
            'fields' => array('DISTINCT UserCourse.course_id'),
            'table' => $dbo->fullTableName($this->UserCourse),
            'alias' => 'UserCourse',
            'conditions' => $conditionsSubQuery,
            'order' => null,
            'limit' => null,
            'group' => null
          ),
          $this->UserCourse
        );
        $subQuery = 'Course.id IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);
        $conditions[] = $subQueryExpression;
        $this->recursive = 0;
        $course = $this->find('all', compact('conditions'));
        return $course;
      break;
  	      case 'A':
        return $this->find('all', array(
            'conditions' => $condition,
            'order' => 'Course.course'
        ));
        break;

      default:
        $conditionsSubQuery['UserEnrol.user_id'] = $userId;
        $dbo = $this->UserEnrol->getDataSource();
        $subQuery = $dbo->buildStatement(
        array(
            'fields' => array('DISTINCT UserEnrol.course_id'),
            'table' => $dbo->fullTableName($this->UserEnrol),
            'alias' => 'UserEnrol',
            'conditions' => $conditionsSubQuery,
            'order' => null,
            'limit' => null,
            'group' => null
          ),
          $this->UserEnrol
        );
        $subQuery = 'Course.id IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);
        $conditions[] = $condition + $subQueryExpression;
        $conditions['Course.record_status'] = 'A';
        $this->recursive = 0;
        $course = $this->find('all', compact('conditions'));
        return $course;
    }
  }

  /**
   * 
   * Find the record count of all accessible courses
   * @param unknown_type $userId user id
   * @param unknown_type $userRole user role
   * @param unknown_type $condition search conditions
   */

  function findAccessibleCoursesCount($userId=null, $userRole=null, $condition=null){
  	
    switch($userRole){
    	case 'S':
        $course = $this->UserEnrol->find('count', array(
          'conditions' => array('UserEnrol.user_id' => $userId),
          'fields' => 'DISTINCT UserEnrol.course_id'
        ));
        return $course;
        break;
        	
    	case 'I':
        $course = $this->UserCourse->find('count', array(
          'conditions' => array('UserCourse.user_id' => $userId),
          'fields' => 'DISTINCT UserCourse.course_id'
        ));
        return $course;
        break;	
        	
      case 'A':
        return $this->find('count', array(
          'conditions' => $condition
        ));
      break;
    	
    	 default:
        $conditionsSubQuery['UserEnrol.user_id'] = $userId;
        $dbo = $this->UserEnrol->getDataSource();
        $subQuery = $dbo->buildStatement(
        array(
            'fields' => array('DISTINCT UserEnrol.course_id'),
            'table' => $dbo->fullTableName($this->UserEnrol),
            'alias' => 'UserEnrol',
            'conditions' => $conditionsSubQuery,
            'order' => null,
            'limit' => null,
            'group' => null
          ),
          $this->UserEnrol
        );
        $subQuery = 'Course.id IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);
        $conditions[] = $condition + $subQueryExpression;
        $conditions['Course.record_status'] = 'A';
        $this->recursive = 0;
        $course = $this->find('count', compact('conditions'));
        return $course;
      break;		
    }
    
  }

  /**
   * Verifies that a user matches with his/her userRole.
   * @param $userName username	
   * @param $userRole user role
   * @return true if the role is verified
   */

  function verifyUserRole($userName=null, $userRole=null){
  
  	$this->User =& ClassRegistry::init('User');
  	$user = $this->User->getByUsername($userName);
  	$role = $user['User']['role'];

  	if($role == $userRole)return 1;
  	return 0;
  }
  

/**
 * 
 * Generates SQL for querrying courses, only Instructors and admin can access this function.
 * @param unknown_type $userId user id
 * @param unknown_type $enrolled if enrolled
 * @param unknown_type $getCount if count is needed
 * @param unknown_type $requester requester id
 * @param unknown_type $requester_role requester role
 */
  function generateRegisterCourse($userId, $enrolled = true, $getCount = false,  $requester = null, $requester_role = null)
  {
    //verify that all necessary inputs are not null && requester's role indeed matches with $requester_role
    $isUserRoleMatch = $this->verifyUserRole($requester, $requester_role);
    if($userId==null || $requester==null || $requester_role==null || $isUserRoleMatch==0) return array();
    $type = $getCount ? 'count': 'all';
    $enrolled = $enrolled ? 'IN ' : 'NOT IN ';
    
    $conditionsSubQuery['UserCourse.user_id'] = $userId;
    $dbo = $this->UserEnrol->getDataSource();
    $subQuery = $dbo->buildStatement(
    array(
        'fields' => array('UserCourse.course_id'),
        'table' => $dbo->fullTableName($this->UserCourse),
        'alias' => 'UserCourse',
        'conditions' => $conditionsSubQuery,
        'order' => null,
        'limit' => null,
        'group' => null
      ),
      $this->UserCourse
    );
    $subQuery = 'Course.id '. $enrolled .'(' . $subQuery . ') ';
    $subQueryExpression = $dbo->expression($subQuery);
    $conditions[] = $subQueryExpression;
    $this->recursive = 0;
    //requester_role is 'Admin' or 'Instructor'
    if($requester_role=='A' || $requester_role=='I')
      return $this->find($type, compact('conditions'));
    else
      return array();
  }

  /**
   * 
   * Courses registered for a user
   * @param unknown_type $user_id user id
   * @param unknown_type $requester requester id
   * @param unknown_type $requester_role requester role
   * @return list of courses
   */
  function findRegisteredCoursesList($user_id, $requester = null, $requester_role = null){
	return $this->generateRegisterCourse($user_id, true, false, $requester, $requester_role);
  } 

  /**
   * 
   * List of courses not registered for a user
   * @param unknown_type $user_id user id
   * @param unknown_type $requester requester id
   * @param unknown_type $requester_role requester role
   * @return list of courses
   */
  function findNonRegisteredCoursesList($user_id, $requester = null, $requester_role = null) {
    return $this->generateRegisterCourse($user_id, false, false, $requester, $requester_role);
  }
   /**
   * 
   * Count of courses not registered for a user
   * @param unknown_type $user_id user id
   * @param unknown_type $requester requester id
   * @param unknown_type $requester_role requester role
   * @return list of courses
   */
  
  function findNonRegisteredCoursesCount($user_id, $requester = null, $requester_role = null){
    return $this->generateRegisterCourse($user_id, false, true, $requester, $requester_role);
  }
  
  /**
   * 
   * Get cource name by id
   * @param unknown_type $id course id
   * @return course name
   */
  function getCourseName($id) {
    $tmp = $this->read(null, $id);
    return $tmp['Course']['course'];
  }

  /**
   * Delete course and all related items
   * @param $id course id
   */
  function deleteAll($id=null) {
    //delete self
   if($this->delete($id)){
      //delete user course,user enrol handled by hasMany
      $events = $this->Event->find('all', array('conditions' => array('course_id' => $id)));
      foreach ($events as $event)
        $this->Event->deleteAll($event['Event']['id']);
    }
  }

  /**
   * 
   * Count students enrolled in a course
   * @param unknown_type $course_id course id
   * @return  count of enrolled students
   */
  function getEnrolledStudentCount($course_id) {
    return $this->Instructor->find('count', array('conditions' => array('Enrolment.id' => $course_id)));
  }
  
  /**
   * 
   * Get course data by course name
   * @param unknown_type $course course name
   * @param unknown_type $params search params
   * @return course data
   */
  function getCourseByCourse($course, $params =null) {
    return $this->find('all', array_merge(array('conditions' => array('course' => $course)), $params));
  }

  /**
   * 
   * Get course data by instructor id
   * @param unknown_type $course instructor id
   * @param unknown_type $params search params
   * @return course data
   */
  function getCourseByInstructor($instructorId, $type = 'all', $recursive = 1) {
    $fields = array('Course.*');
    if($type == 'list') {
      $fields = array('Course.course'); 
      $recursive = 0;
    }
    return $this->find($type, array('conditions' => array('Instructor.id' => $instructorId),
                                    'fields' => $fields,
                                    'recursive' => $recursive));
  }

  /**
   * 
   * Get course data by instructor id
   * @param unknown_type $course instructor id
   * @param unknown_type $params search params
   * @return course data
   */
  function getCourseListByInstructor($instructorId) {
    return $this->getCourseByInstructor($instructorId, 'list');
  }

  /**
   * enrolStudents enrol student to a course
   * 
   * @param mixed $ids id array of the students
   * @param mixed $courseId the course the students are enrolled into. If null, 
   * read the current id in the course object
   * @access public
   * @return boolean true for success, false for failed.
   */
  function enrolStudents($ids, $courseId = null) {
    if(null == $courseId) {
      $courseId = $this->id;
    }

    if(null == $courseId) {
      return false;
    }

    return $this->habtmAdd('Enrol', $courseId, $ids);
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

	function getCourseList(){
          $this->displayField = 'course';
          return $this->find('list', array(
              'conditions' => array()
          ));
        }
        
	function getCourseById($courseId){
		return $this->find('first', array('conditions' => array('Course.id' => $courseId)));
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
