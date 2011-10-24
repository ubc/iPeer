<?php
class LtiController extends AppController {
  var $name = "Lti";
  var $uses = array('User', 'Course', 'Role');
  var $components = array('LtiVerifier', 'LtiRequester');

  function beforeFilter() {
    $this->Auth->allow('index');
  }

  public function index() {
    // First verify that this is a legit LTI request
    $ret = $this->LtiVerifier->checkParams($this->params['form']);
    if ($ret) { // param check failed
      $this->set('invalidlti', $ret);
      return;
    }

    // Request the class roster
    $roster = $this->LtiRequester->requestRoster($this->params['form']);
    if (!is_array($roster)) {
      $this->set('invalidlti', $roster);
      return;
    }

    // Get course information
    $ret = $this->LtiVerifier->getCourseInfo($this->params['form']);
    if (!$ret) { // failed to get course info
      $this->set('invalidlti', "Missing course info in LTI request");
      return;
    }

    // APP SPECIFIC CODE STARTS HERE
    // Check whether the course already exists
    $course = $this->Course->find(
      'first',
      array('conditions' => array('Course.course' => $ret['course']),)
    );
    if (empty($course)) { 
      // Non-existing course, create course
      $this->data = array();
      $this->data['course'] = $ret['course'];
      $this->data['title'] = $ret['title'];
      $this->data['record_status'] = Course::STATUS_ACTIVE;
      $this->data = $this->Course->save($this->data);
      if (!$this->data) {
        $this->set('invalidlti', "Unable to add course");
        return;
      }

      // Create users, if needed, and enrol them to the course
      foreach ($roster as $person) {
        $this->addUser($person, $this->Course->id);
      }

    }
    else { 
      // Existing course, update course
      // Get current roster in iPeer
      $courseid = $course['Course']['id'];
      $ipeerroster = $this->User->getEnrolledStudents($courseid);
      // Compare with roster from LTI
      # Remove users that are on both lists
      foreach ($roster as $ltikey => $ltiuser) {
        foreach ($ipeerroster as $ipeerkey => $ipeeruser) {
          if ($ltiuser['user_id'] == $ipeeruser['User']['lti_id']) {
            unset($roster[$ltikey]);
            unset($ipeerroster[$ipeerkey]);
            continue;
          }
        }
      }
      # Remaining users in ipeerroster needs to be dropped
      foreach ($ipeerroster as $ipeeruser) {
        $this->User->dropEnrolment($ipeeruser['User']['id'], $courseid);
      }
      # Remaining users in roster needs to be added
      foreach ($roster as $person) {
        if (!$this->isLTIInstructor($person)) {
          $this->addUser($person, $courseid);
        }
      }
    }
    // END APP SPECIFIC CODE

    // Let's try logging in:
    $ret = $this->LtiVerifier->login($this->params['form'], $this->User);
    if ($ret) {
      $this->set('invalidlti', $ret);
      return;
    }

    // APP SPECIFIC CODE BELOW
    $this->redirect('/home');

  }

  private function addUser($info, $courseid) {
    $first = $info['person_name_given'];
    $last = $info['person_name_family'];
    $email = $info['person_contact_email_primary'];
    $lti_id = $info['user_id'];
    $instructor = $this->isLTIInstructor($info);

    // Prepare user data
    $cdata = array();
    $cdata['User']['username'] = $first . $last;
    $cdata['User']['first_name'] = $first;
    $cdata['User']['last_name'] = $last;
    $cdata['User']['email'] = $email;
    $cdata['User']['send_email_notification'] = false;
    $cdata['User']['lti_id'] = $lti_id;
    // TODO USER_TYPE_STUDENT needs to change to const instead of var later
    $cdata['User']['role'] = $this->User->USER_TYPE_STUDENT;
    $cdata['User']['created'] = date('Y-m-d H:i:s');
    if ($instructor !== FALSE) { # this guy is a prof
      $cdata['User']['role'] = $this->User->USER_TYPE_INSTRUCTOR;
    }

    // Check if user already exists
    $user = $this->User->getByUsername($cdata['User']['username']);
    if (!empty($user)) { # pre-existing user, just need to add them to course
      $this->addUserToCourse($user['User']['id'], $courseid, $instructor);
      # user might not have an lti_id, so save one
      $user['User']['lti_id'] = $lti_id;
      $this->User->save($user);
      return false;
    }

    // Need to create a new user
    $this->User->create();
    if ($this->User->save($cdata)) {
      // User enrolment
      $this->addUserToCourse($this->User->id, $courseid, $instructor);
      return false;
    } else {
      return $cdata['User']['username'];
    }
  }

  private function addUserToCourse($userid, $courseid, $instructor) {
    // TODO use Role methods instead of this if possible
    $student_role_id = $this->Role->field('id', array('name =' => 'student'));
    $instructor_role_id = $this->Role->field(
      'id', array('name' => 'instructor'));

    if ($instructor === false) {
      $this->User->registerRole($userid, $student_role_id);
      $this->User->registerEnrolment($userid, $courseid);
    }
    else {
      $this->User->registerRole($userid, $instructor_role_id);
      $this->Course->addInstructor($courseid, $userid);
    }
  }

  private function isLTIInstructor($info) {
    $instructor = stripos($info['roles'], 'Instructor');
    # note that !== is required, we MUST compare type since
    # stripos() may return 0 for a valid match
    if ($instructor === false) {
      return false;
    }
    return true;
  }

}
?>
