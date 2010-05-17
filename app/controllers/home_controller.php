<?php
/* SVN FILE: $Id: home_controller.php,v 1.14 2006/09/08 00:22:00 davychiu Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.14 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/09/08 00:22:00 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Users
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class HomeController extends AppController
{
/**
 * This controller does not use a model
 *
 * @var $uses
 */
  var $uses =  array('User', 'UserEnrol', 'UserCourse', 'Event', 'GroupEvent', 'Group', 'EvaluationSubmission', 'Course');
	var $page;
	var $Sanitize;
	var $functionCode = 'HOME';

	function __construct()
	{
		$this->Sanitize = new Sanitize;
 		$this->pageTitle = 'Home';
		parent::__construct();
	}


    function sunwukong($student_no)
    {
        $this->autoLayout=false;
        $this->autoRender=false;

        $user=$this->User->findByUsername($student_no);

        //$a=print_r($user,true);
        //echo "<pre>$a</pre>";

	 ///echo "<hr>";

	 
	 echo "<hr>";

	$this->Session->write('ipeerSession.id', $user['User']['id']);
	$this->Session->write('ipeerSession.username',$user['User']['username']);
	$this->Session->write('ipeerSession.fullname', $user['User']['first_name'].' '.$user['User']['last_name']);			
	$this->Session->write('ipeerSession.role', $user['User']['role']);
	$this->Session->write('ipeerSession.email',$user['User']['email']);

        $b=print_r($this->rdAuth,true);
        echo "<pre>$b</pre>";
       $this->redirect('home');   
    }



	function index($msg='') {

		//Disable the autorender, base the role to render the custom home
		$this->autoRender = false;

    $role = $this->rdAuth->role;
    $this->set('message', $msg);
    if (isset ($role)) {
      //General Home Rendering for Admin and Instructor
      if ($role == $this->User->USER_TYPE_ADMIN || $role == $this->User->USER_TYPE_INSTRUCTOR)
      {
        //$courseList = $this->sysContainer->getMyCourseList();
          $courseList = $this->User->findById($this->rdAuth->id);
           //$courseList = $this->UserCourse->findByUser_id($this->rdAuth->id);
    	  $activeCourseDetail = $this->formatCourseList($courseList['UserCourse'], 'active_course');

    	  $inactiveCourseDetail=null;
        if ($this->rdAuth->role == $this->User->USER_TYPE_ADMIN)
        {
           $inactiveCourseList = $this->Course->getInactiveCourses();
           $inactiveCourseDetail = $this->formatCourseList($inactiveCourseList, 'inactive_course');
        }
        $this->set('activeCourseDetail', $activeCourseDetail);
        $this->set('inactiveCourseDetail', $inactiveCourseDetail);
        $this->render('index');

      }//Student Home Rendering
      else if ($role == $this->User->USER_TYPE_STUDENT) {

        $this->set('data', $this->preparePeerEvals());

        //Check if the student has a email in his/her profile
        if (!empty($this->rdAuth->email)) {
          $this->render('studentIndex');
        }else{
           $this->redirect('/users/editProfile');
        }
      }
    }

	}

	function preparePeerEvals()
	{
	  $curUserId = $this->rdAuth->id;
	  $eventAry = array();
	  $pos = 0;
	  //Get enrolled courses
	  $enrolledCourseIds = $this->UserEnrol->getEnrolledCourses($curUserId);
    foreach($enrolledCourseIds as $row): $userEnrol = $row['UserEnrol'];
       $courseId = $userEnrol['course_id'];
       //$courseDetail = $this->Course->find('id='.$courseId);

       //Get Events for this course that are due
       $events = $this->Event->findAll('release_date_begin < NOW() AND NOW() <= release_date_end AND course_id='.$courseId);
       foreach($events as $row):
        $event = $row['Event'];
          switch ($event['event_template_type_id']) {
            case 3:
              //Survey
              $survey = $this->getSurveyEvaluation($courseId,$event,$curUserId);
              if ($survey!=null) {
                $eventAry[$pos] = $survey;
      			    $pos++;
              }
              break;
            default:
              //Simple, Rubric and Mixed Evaluation
              $evaluation = $this->getEvaluation($curUserId, $event);
              if ($evaluation!=null) {
                $eventAry[$pos] = $evaluation;
      			    $pos++;
              }
              break;

          }
       endforeach;
    endforeach;

	  return $eventAry;
	}

	function getEvaluation($userId, $event=null)
	{
	  $result = null;
    $groupsEvents = $this->GroupEvent->getGroupEventByUserId($userId, $event['id']);

    foreach($groupsEvents as $row):
      $groupMember = $row['GroupMember'];
      $groupEvent = $row['GroupEvent'];
  		//get corresponding group
  		$group = $this->Group->find('id='.$groupEvent['group_id']);
  		// get corresponding evaluation submission that is not submitted
  		$isSubmitted = false;
  		$eventSubmit = $this->EvaluationSubmission->find('grp_event_id='.$groupEvent['id'].' AND submitter_id='.$userId);
			if ($eventSubmit['EvaluationSubmission']['submitted']) {
				$isSubmitted = true;
			}

			// get due date of event in days or number of days late
			$diff = $this->framework->getTimeDifference($event['due_date'], $this->framework->getTime());
			$isLate = ($diff < 0);
			$dueIn = abs(floor($diff));

  		// if eval submission is not submitted or doesn't exist, output
			if (!$isSubmitted) {
        $result['comingEvent']['Event'] = $event;
        $result['comingEvent']['Event']['is_late'] = $isLate;
        $result['comingEvent']['Event']['days_to_due'] = $dueIn;
        $result['comingEvent']['Event']['group_id'] = $groupEvent['group_id'];
        $result['comingEvent']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
			}
			else {
        $result['eventSubmitted']['Event'] = $event;
        $result['eventSubmitted']['Event']['is_late'] = $isLate;
        $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
        $result['eventSubmitted']['Event']['group_id'] = $groupEvent['group_id'];
        $result['eventSubmitted']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
			}

	  endforeach;
	  return $result;

	}

	function getSurveyEvaluation($courseId, $event = null, $userId=null) {
	  $result = null;
    $surveyEvents = $this->Event->getActiveSurveyEvents($courseId);

    foreach($surveyEvents as $row) {
  		// get corresponding evaluation submission that is not submitted
  		$isSubmitted = false;
  		$eventSubmit = $this->EvaluationSubmission->find('event_id='.$event['id'].' AND submitter_id='.$userId);

			if ($eventSubmit['EvaluationSubmission']['submitted']) {
				$isSubmitted = true;
			}

			// get due date of event in days or number of days late
			$diff = $this->framework->getTimeDifference($event['due_date'], $this->framework->getTime());
			$isLate = ($diff < 0);
			$dueIn = abs(floor($diff));

  		// if eval submission is not submitted or doesn't exist, output
			if (!$isSubmitted) {
        $result['comingEvent']['Event'] = $event;
        $result['comingEvent']['Event']['is_late'] = $isLate;
        $result['comingEvent']['Event']['days_to_due'] = $dueIn;
     //   $result['comingEvent']['Event']['group_id'] = $groupEvent['group_id'];
        $result['comingEvent']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
			}
			else {
        $result['eventSubmitted']['Event'] = $event;
        $result['comingEvent']['Event']['is_late'] = $isLate;
        $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
     //   $result['eventSubmitted']['Event']['group_id'] = $groupEvent['group_id'];
        $result['eventSubmitted']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
			}
    }
	  return $result;
	}

	function formatCourseList($courseList=null, $courseType='')
	{
     $pos = 0;
	   $result = array();

	   if ($courseList!=null) {

       foreach ($courseList as $course) {
         $result[$pos][$courseType]['Course'] = $course;
         if (isset($course['Course'])){
          $courseId = $course['Course']['id'];
         } else {
          $courseId =$course['course_id'];
        }
         $result[$pos][$courseType]['Course']['course'] = $this->sysContainer->getCourseName($courseId, 'A');
         $result[$pos][$courseType]['Course']['instructors'] = $this->UserCourse->getInstructors($courseId);

         $eventList = $this->Event->getCourseEvent($courseId);

         $result[$pos][$courseType]['Course']['events'] = $eventList;
         for ($i = 0; $i < count($eventList); $i++) {
           $result[$pos][$courseType]['Course']['events'][$i]['Event']['to_review_count'] = $this->GroupEvent->getToReviewGroupEventByEventId($eventList[$i]['Event']['id']);
           $completeCount = $this->EvaluationSubmission->numCountInEventCompleted($eventList[$i]['Event']['id']);
           $result[$pos][$courseType]['Course']['events'][$i]['Event']['completed_count'] = $completeCount[0][0]['count'];
           $totalSum = $this->GroupEvent->getMemberCountByEventId($eventList[$i]['Event']['id']);
           if ($result[$pos][$courseType]['Course']['events'][$i]['Event']['event_template_type_id'] == 3) {
             $count = $this->UserEnrol->getEnrolledStudentCount($courseId);
             //print_r($count);
             $result[$pos][$courseType]['Course']['events'][$i]['Event']['student_sum'] = $count[0]['total'];
           }
           else
             $result[$pos][$courseType]['Course']['events'][$i]['Event']['student_sum'] = $totalSum[0][0]['count'];
          }
         $pos++;
       }
      }
	   return $result;
	}
}

?>