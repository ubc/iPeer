<?php
class UserEnrol extends AppModel
{
  var $name = 'UserEnrol';

  function getEnrolledCourses($userId=null)
  {
    //$result = $this->find('all','user_id='.$userId, 'DISTINCT course_id');

    return $this->find('all', array(
        'conditions' => array('user_id' => $userId),
        'fields' => array('DISTINCT course_id')
    ));
  }

  // Deprecated: replaced by virtual field student_count in course model
  /*function getEnrolledStudentCount($courseId=null) {
    $conditions = array('course_id' => $courseId,
                        );
    return $this->find('count', array('conditions' => $conditions));
  }*/

  function removeStudentFromCourse($user_id=null, $course_id=null) {
    //$course_to_remove = $this->find(array('course_id=' . $course_id, 'user_id=' . $user_id));
    $course_to_remove = $this->find('first', array(
            'conditions' => array('course_id' => $course_id, 'user_id' => $user_id)
        ));
    return $this->delete($course_to_remove['UserEnrol']['id']);
  }
  
  // Returns true if the username is enrolled in the course, and false if not.
  function isEnrolledInByUsername($username, $courseId) {
    $result = $this->query("select User.id from users join user_enrols" .
                            "on users.id=user_enrols.user_id " . 
                            "where username=$username and course_id=$courseId");
    return count($results) > 0;
  }

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

}

?>
