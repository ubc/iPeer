<?php
/**
 * HomeController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class HomeController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array( 'Group', 'GroupEvent',
        'User', 'UserCourse', 'Event', 'EvaluationSubmission',
        'Course', 'Role', 'UserEnrol', 'Rubric', 'Penalty');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->set('title_for_layout', __('Home', true));
        parent::__construct();
    }

    /**
     * index
     *
     *
     * @access public
     * @return void
     */
    function index()
    {
        //General Home Rendering for Admin
        if (User::hasPermission('functions/superadmin')) {
            $course_list = $this->Course->find('all', array('contain' => array('Instructor', 'Event')));
        // admins
        } else if (User::hasPermission('controllers/departments')) {
            $course_list = User::getMyDepartmentsCourseList('all');
        // students & tutors
        } else if (User::hasPermission('functions/onlytakeeval')) {
            $this->redirect('studentIndex');
            return;
        // instructors
        } else {
            $course_list = $this->Course->getCourseByInstructor($this->Auth->user('id'), 'all', array('Event'));
        }
        $this->set('course_list', $this->_formatCourseList($course_list));
    }

    /**
     * studentIndex
     *
     *
     * @access public
     * @return void
     */
     function studentIndex() {
        $this->set('data', $this->_preparePeerEvals());
        $this->set('userId', $this->Auth->user('id'));
     }


    /**
     * preparePeerEvals
     *
     *
     * @access public
     * @return void
     */
    function _preparePeerEvals()
    {
        $curUserId = $this->Auth->user('id');
        $eventAry = array();
        $pos = 0;
        $user = $this->User->findById($this->Auth->user('id'));
        $roleId = $user['Role'][0]['id'];
        if ($roleId == 5) {
            //Get enrolled courses
            $enrol = $user['Enrolment'];
            foreach ($enrol as $enrolledCourse) {
                $courseId = $enrolledCourse['UserEnrol']['course_id'];
                //$courseDetail = $this->Course->find('id='.$courseId);
                //Get Events for this course that are due
                $evals = $this->Event->find(
                    'all',
                    array(
                        'conditions' => array(
                            'release_date_begin < NOW()',
                            'NOW() <= result_release_date_end',
                            'course_id' => $courseId,
                            'NOT' => array('event_template_type_id' => 3)
                )));
                $surveys = $this->Event->find(
                    'all',
                    array(
                        'conditions' => array(
                            'course_id' => $courseId,
                            'event_template_type_id' => 3
                )));
                $events = array_merge($evals, $surveys);
                foreach ($events as $row) {
                    $event = $row['Event'];
                    switch ($event['event_template_type_id']) {
                    case 3:
                        //Survey
                        $survey = $this->_getSurveyEvaluation($courseId, $event, $curUserId);
                        if ($survey!=null) {
                            $eventAry[$pos] = $survey;
                            $pos++;
                        }
                        break;
                    default:
                        //Simple, Rubric and Mixed Evaluation
                        $evaluation = $this->_getEvaluation($curUserId, $event);
                        if ($evaluation!=null) {
                            $eventAry[$pos] = $evaluation;
                            $pos++;
                        }
                        break;
                    }
                }
            }
        } else if ($roleId == 4) {
            //Get assigned courses
            $courses = $user['Tutor'];
            foreach ($courses as $assignedCourse) {
                $courseId = $assignedCourse['id'];
                // will never return surveys because it doesn't have result release date
                $events = $this->Event->find('all', array('conditions' => array('release_date_begin < NOW()', 'NOW() <= result_release_date_end', 'course_id' => $courseId)));
                foreach ($events as $row) {
                    $event = $row['Event'];
                    switch ($event['event_template_type_id']) {
                    case 3:
                        //Survey
                        $survey = $this->_getSurveyEvaluation($courseId, $event, $curUserId);
                        if ($survey!=null) {
                            $eventAry[$pos] = $survey;
                            $pos++;
                        }
                        break;
                    default:
                        //Simple, Rubric and Mixed Evaluation
                        $evaluation = $this->_getEvaluation($curUserId, $event);
                        if ($evaluation!=null) {
                            $eventAry[$pos] = $evaluation;
                            $pos++;
                        }
                        break;
                    }
                }
            }
        }
        return $eventAry;
    }


    /**
     * _getEvaluation
     *
     * @param mixed $userId user id
     * @param bool  $event  event
     *
     * @access public
     * @return void
     */
    function _getEvaluation($userId, $event=null)
    {
        $result = null;
        $groupsEvents = $this->GroupEvent->getGroupEventByUserId($userId, $event['id']);
        foreach ($groupsEvents as $row) {
            $groupEvent = $row['GroupEvent'];
            $group = $this->Group->findGroupByid($groupEvent['group_id']);

            // get corresponding evaluation submission that is not submitted
            $isSubmitted = false;
            $eventSubmit = $this->EvaluationSubmission->find('first', array( 'conditions'=>array( 'grp_event_id'=>$groupEvent['id'], 'submitter_id'=>$userId)));
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
                $result['comingEvent']['Event']['group_name'] = $group['Group']['group_name'];
                $result['comingEvent']['Event']['course'] = $this->Course->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);

                if ($isLate) {
                    $penalty = $this->Penalty->find('first', array(
                        'conditions' => array('event_id' => $event['id'], 'OR' => array(
                            array('days_late' => $dueIn), array('days_late <' => 0))),
                        'order' => array('days_late DESC')));
                    $result['comingEvent']['Event']['penalty'] = $penalty['Penalty']['percent_penalty'];
                }

            } else {
                $result['eventSubmitted']['Event'] = $event;
                $result['eventSubmitted']['Event']['is_late'] = $isLate;
                $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
                $result['eventSubmitted']['Event']['group_id'] = $groupEvent['group_id'];
                $result['eventSubmitted']['Event']['group_name'] = $group['Group']['group_name'];
                $result['eventSubmitted']['Event']['course'] = $this->Course->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
            }
        }
        return $result;
    }


    /**
     * _getSurveyEvaluation
     *
     * @param mixed $courseId course id
     * @param bool  $event    event
     * @param bool  $userId   user id
     *
     * @access public
     * @return void
     */
    function _getSurveyEvaluation($courseId, $event = null, $userId=null)
    {
        $result = null;
        $surveyEvents = $this->Event->getActiveSurveyEvents($courseId);

        foreach ($surveyEvents as $row) {
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
                $result['comingEvent']['Event']['group_name'] = '-';
                $result['comingEvent']['Event']['course'] = $this->Course->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
            } else {
                $result['eventSubmitted']['Event'] = $event;
                $result['comingEvent']['Event']['is_late'] = $isLate;
                $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
                $result['eventSubmitted']['Event']['group_name'] = '-';
                $result['eventSubmitted']['Event']['course'] = $this->Course->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
            }
        }
        return $result;
    }


    /**
     * _formatCourseList
     *
     * @param mixed $course_list
     *
     * @access public
     * @return void
     */
    function _formatCourseList($course_list)
    {
        $result = array();

        foreach ($course_list as $row) {
            for ($i = 0; $i < count($row['Event']); $i++) {
                if ($row['Event'][$i]['event_template_type_id'] == 3) {
                    $row['Event'][$i]['student_count'] = $row['Course']['student_count'];
                }
            }
            $result[$row['Course']['record_status']][] = $row;
        }
        return $result;
    }
}
