<?php
class UserEnrol extends AppModel
{
  var $name = 'UserEnrol';
  var $belongsTo = array("User", "Course");

  function getEnrolledCourses($userId=null)
  {
    $result = $this->findAll('user_id='.$userId, 'DISTINCT course_id');

    return $result;
  }

    function isEnrolledInById($userId, $courseId) {
        return $this->findCount("user_id=$user_id AND course_id=$courseId") > 0;
    }

    function isEnrolledInByUsername($userName, $courseId) {
        return $this->findCount("User.username='$userName' AND course_id=$courseId") > 0;
    }


  function getEnrolledStudentCount($courseId=null) {
    $conditions = array('course_id' => $courseId,
                        );
    return $this->find($conditions, 'COUNT(DISTINCT user_id) as total');
  }

  function removeStudentFromCourse($user_id=null, $course_id=null) {
    $course_to_remove = $this->find(array('course_id=' . $course_id, 'user_id=' . $user_id));
    return $this->delete($course_to_remove['UserEnrol']['id']);
  }

  function insertCourses($user_id, $course_ids) {
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

