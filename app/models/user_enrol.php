<?php
class UserEnrol extends AppModel
{
  var $name = 'UserEnrol';
  var $actsAs = array('Traceable');

  var $belongsTo = array('User');
    
/**
 * 
 * Remove student from course
 * @param unknown_type $user_id user id
 * @param unknown_type $course_id course id
 */

  function removeStudentFromCourse($user_id=null, $course_id=null) {
    //$course_to_remove = $this->find(array('course_id=' . $course_id, 'user_id=' . $user_id));
    $course_to_remove = $this->find('first', array(
            'conditions' => array('course_id' => $course_id, 'user_id' => $user_id)
        ));
    return $this->delete($course_to_remove['UserEnrol']['id']);
  }
  
  /**
   * 
   * Returns true if the username is enrolled in the course, and false if not.	
   * @param unknown_type $username username		
   * @param unknown_type $courseId course id
   * @return true if user is enrolled  in a course
   */ 
  function isEnrolledInByUsername($username, $courseId) { 
    return $this->find('count', array(
        'conditions' => array('UserEnrol.course_id' => $courseId, 'User.username' => $username)
    ));
  }

  /**
   * 
   * Enroll user in courses
   * @param unknown_type $user_id user id
   * @param unknown_type $course_ids course id
   */
  function insertCourses ($user_id, $course_ids) {

    if(!is_array($course_ids) || empty($course_ids) || $user_id <= 0) return;

    $course_ids = array_unique($course_ids);

    foreach($course_ids as $id)
    {
      $c = array();
      $c['UserEnrol']['course_id']  = $id;
      $c['UserEnrol']['user_id']    = $user_id;
      $c['UserEnrol']['record_status'] = 'A';
      $this->save($c);
      $this->id = null;
    }
  }
  
  /**
   * 
   * Get courses a user is enrolled in
   * @param unknown_type $userId user id
   * @return list of course ids
   */
function getEnrolledCourses($userId=''){
  	return $this->find('all',array('conditions'=>array('user_id'=>$userId), 'fields'=>array('course_id')));
  }

  /**
   * 
   * Get list of users enrolled in a course
   * @param unknown_type $course_id
   * @return list of user ids
   */
  function getUserListByCourse($course_id){
    $this->displayField = 'user_id';
    return $this->find('list', array(
        'conditions' => array('course_id' => $course_id)
    ));
  }
}

?>