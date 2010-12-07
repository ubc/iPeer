<?php
class UserCourse extends AppModel
{
  var $name = 'UserCourse';


  // saves all the instructors associated with a course to the user_courses table
  function insertInstructors($course_id=null, $data=null){
    $instructorIDs = '';
  	for( $i=1; $i<=$data['count']; $i++ ){
  	  $pos = 0;
  	  if (!empty($data['instructor_id'.$i]) && $data['instructor_id'.$i] > 0) {
  	      //$pos = strpos($instructorIDs, $data['instructor_id'.$i]);
  	      $pos = strpos($instructorIDs, $data['instructor_id'.$i]);
  	      if (!(FALSE !== $pos)) {
              $newInstructor = array();
              $newInstructor['UserCourse']['user_id'] = $data['instructor_id'.$i];
              $newInstructor['UserCourse']['course_id'] = $course_id;
              $newInstructor['UserCourse']['access_right'] = 'A';
              $newInstructor['UserCourse']['record_status'] = 'A';
              $this->save($newInstructor);
              $this->id = null;
              $instructorIDs .= $data['instructor_id'.$i].';';
          }
      	}
  	}
  }

  // returns all the instructor names and ids for display on the view page
  function getInstructors($id=null){
  	$result = $this->query('SELECT User.id, User.first_name, User.last_name, User.email FROM user_courses JOIN users as User ON User.id=user_courses.user_id AND User.id <> 1 WHERE course_id='.$id);

  	return $result;
  }

  // function called for every newly added course to place root account as admin
  function insertAdmin($id=null){
    $tmp = array( 'user_id'=>1, 'course_id'=>$id, 'access_right'=>'A', 'record_status'=>'A' );
  	$this->save($tmp);
  }


}

?>
