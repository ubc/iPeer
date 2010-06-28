<?php
class UserEnrol extends AppModel
{
  var $name = 'UserEnrol';

  function getEnrolledCourses($userId=null)
  {
    $result = $this->findAll('user_id='.$userId, 'DISTINCT course_id');

    return $result;
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

  function insertCourses($user_id=null, $data=null) {
    $courseIDs = '';
    for( $i=1; $i<=$data['data']['Course']['count']; $i++ ){
      $pos = 0;
      if (!empty($data['course_id'.$i]) && $data['course_id'.$i] > 0) {
          //$pos = strpos($instructorIDs, $data['course_id'.$i]);
          $pos = strpos($courseIDs, $data['course_id'.$i]);
          if (!(FALSE !== $pos)) {
              $newCourse = array();
              $newCourse['UserEnrol']['course_id'] = $data['course_id'.$i];
              $newCourse['UserEnrol']['user_id'] = $user_id;
              $newCourse['UserEnrol']['record_status'] = 'A';
              $this->save($newCourse);
              $this->id = null;
              $courseIDs .= $data['course_id'.$i].';';
          }
        }
    }
  }

}

?>
