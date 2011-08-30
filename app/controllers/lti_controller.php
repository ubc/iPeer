<?php
class LtiController extends AppController
{
	var $name = "Lti";
	var $uses = array('User','Course','Role');
	var $components = array('LtiVerifier','LtiRequester');

	function beforeFilter() {
		$this->Auth->allow('index');
	}

	public function index()
	{
		// First verify that this is a legit LTI request
		debug($this->params['form']);
		$ret = $this->LtiVerifier->checkParams($this->params['form']);
		if ($ret)
		{ // param check failed
			$this->set('invalidlti',$ret);
			return;
		}

		// Request the class roster
		$roster = $this->LtiRequester->requestRoster($this->params['form']);
		if (!is_array($roster))
		{
			$this->set('invalidlti',$roster);
			return;
		}
		debug($roster);

		// Get course information
		$ret = $this->LtiVerifier->getCourseInfo($this->params['form']);
		if (!$ret)
		{ // failed to get course info
			$this->set('invalidlti', "Missing course info in LTI request");	
			return;
		}
		debug($ret);

		// APP SPECIFIC CODE STARTS HERE
		// Check whether the course already exists
		$course = $this->Course->find('first',
			array('conditions' => array('Course.course' => $ret['course']),)
		);
		debug($course);
		if (empty($course))
		{ // Non-existing course, create course

			// Create course
			$this->data = array();
			$this->data['course'] = $ret['course'];
			$this->data['title'] = $ret['title'];
			$this->data['record_status'] = Course::STATUS_ACTIVE;
			$this->data = $this->Course->save($this->data);
			if (!$this->data)
			{
				$this->set('invalidlti', "Unable to add course");
				return;
			}

			// Create users
			foreach($roster as $person)
			{
				$first = $person['person_name_given'];
				$last = $person['person_name_family'];
				$email = $person['person_contact_email_primary'];
				$lti_id = $person['user_id'];

				$instructor = stripos($person['roles'], 'Instructor');

				$cdata = array();
				$cdata['User']['username'] = $first . $last;
				$cdata['User']['first_name'] = $first;
				$cdata['User']['last_name'] = $last;
				$cdata['User']['email'] = $email;
				$cdata['User']['send_email_notification'] = false;
				$cdata['User']['lti_id'] = $lti_id;
				$cdata['User']['role'] = $this->User->USER_TYPE_STUDENT;

				# note that !== is required, we MUST compare type since
				# stripos() may return 0 for a valid match
				if ($instructor !== FALSE)
				{ # this guy is a prof
					$cdata['User']['role'] = $this->User->USER_TYPE_INSTRUCTOR;
				}

				$this->User->create();
				if ($this->User->save($cdata))
				{
					debug("User saved");
					if ($instructor === FALSE)
					{
						$student_role_id = $this->Role->field(
							'id', 
							array('Role.name =' => 'student',)
						);
						
						$this->User->registerRole(
							$this->User->id, 
							$student_role_id
						);

						$this->User->registerEnrolment(
							$this->User->id,
							$this->Course->id
						);
					}
					else
					{
						$instructor_role_id = $this->Role->field(
							'id', 
							array('name' => 'instructor')
						);
						$this->User->registerRole(
							$this->User->id, 
							$instructor_role_id
						);
						$this->Course->addInstructor(
							$this->Course->id, 
							$this->User->id
						);
					}
				}
				else
				{
					debug("User cannot be saved");
				}
			}
		}
		else
		{ // Existing course, update
		}
		// END APP SPECIFIC CODE


		// Populate course according to the class roster

		// Let's try logging in:
		$ret = $this->LtiVerifier->login($this->params['form'], $this->User);
		if ($ret)
		{
			$this->set('invalidlti', $ret);
			return;
		}
		debug($this->Auth->user());

	}

}
?>
