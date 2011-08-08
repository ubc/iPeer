<?php
class UserT extends AppModel {
	var $name = 'Usert';


	function getByUsername($id) {
		return $this->find('first', array('conditions' => array('id' => $id,
		)));
	}

	/*
	 * Code changes:
	 * swapped the boolean values between in the two return statements.
	 */
	function hasDuplicateUsername($username) {
		if ($this->find('first', array('conditions' => array('username' => $username)))) {
			$this->errorMessage='Duplicate Username found. Please change the username of this user.';
			/*if ($this->data[$this->name]['role'] == 'S') {
			 $this->errorMessage.='<br>If you want to enrol this student to one or more courses, use the enrol function on User Listing page.';
			 }*/
			return true;
		}
		return false;
	}

	function findUser($username, $password) {
		return $this->find('first', array('conditions' => array('username' => $username,
                                                            'password' => $password)));
	}


	function findUserByStudentNo ($studentNum) {
		return $this->find('first', array('conditions' => array('studentNo' => $studentNum,
		)));
	}

	/*
	 * Code changed, compared with original source code.
	 */
	function getUserByEmail($emailAddress) {
		return $this->find( 'first', array('conditions' =>array('email'=>$emailAddress)));
	}
	
  	function getUserIdByStudentNo($studentNo) {
    $tmp = $this->findUserByStudentNo($studentNo);
    return $tmp['User']['id'];
 	}

  function findUserByid ($id, $params = array()) {
    return $this->find('first', array_merge(array('conditions' => array($this->name.'.id' => $id,
                                                            )),
                                            $params));
  }
 	
  function getEnrolledStudents($course_id, $fields = array(), $conditions=null) {
    return $this->find('all', array('conditions' => array('Enrolment.id' => $course_id),
                                    'fields' => 'User.*',
                                    'order' => 'User.student_no'));
  }
  
 
  function weeWa($stuff)
	{
		$this->log($stuff);
	}
	
	

}
?>
