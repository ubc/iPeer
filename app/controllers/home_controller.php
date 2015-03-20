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
        if (User::hasPermission('functions/coursemanager') || User::isInstructor()) {
            // Admins and profs
            $course_list = $this->Course->getAllAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'all', array('contain' => array('Event', 'Instructor')));
            $this->set('course_list', $this->_formatCourseList($course_list));
            if(!User::isStudentOrTutor()) {
                return;
            }
        }

        // Student and tutor
        $events = $this->Event->getEventsByUserId(User::get('id'));

        // mark events as late if past due date
        foreach ($events as &$type) {
            foreach ($type as &$event) {
                if ($event['Event']['due_in'] > 0) {
                    $event['late'] = false;
                    continue;
                }
                $event['late'] = true;
            }
        }

        // determine the proper penalty to be applied to a late eval
        foreach ($events['Evaluations'] as &$event) {
            if (!$event['late'] || empty($event['Penalty'])) {
                continue;
            }
            // convert seconds to days
            $daysLate = abs($event['Event']['due_in']) / 86400;
            $pctPenalty = 0;
            foreach ($event['Penalty'] as $penalty) {
                $pctPenalty = $penalty['percent_penalty'];
                if ($penalty['days_late'] > $daysLate) {
                    break;
                }
            }
            $event['percent_penalty'] = $pctPenalty;
        }

        // format the 'due in' time interval for display
        foreach ($events as &$types) {
            foreach ($types as &$event) {
                $event['Event']['due_in'] = $this->_formatDueIn(
                    abs($event['Event']['due_in']));
            }
        }

        // remove non-current events and split into upcoming/submitted/expired
        $evals = $this->_splitSubmittedEvents($events['Evaluations']);
        $surveys = $this->_splitSubmittedEvents($events['Surveys']);

        // calculate summary statistics
        $numOverdue = 0;
        $numDue = 0;
        $numDue = sizeof($evals['upcoming']) + sizeof($surveys['upcoming']);
        // only evals can have overdue events right now
        foreach ($evals['upcoming'] as $e) {
            $e['late'] ? $numOverdue++ : '';
        }


        $this->set('evals', $evals);
        $this->set('surveys', $surveys);
        $this->set('numOverdue', $numOverdue);
        $this->set('numDue', $numDue);
        
        if(!User::isInstructor()) {
            $this->render('studentIndex');
        } else {
            $this->render('combined');
        }
    }

    /**
     * formatDueIn
     *
     * Take the due interval, which is in seconds, and format
     * it something that's easier for users to read.
     *
     * @param mixed $seconds seconds
     *
     * @access private
     * @return void
     */
    private function _formatDueIn($seconds)
    {
        $ret = "";
        if ($seconds > 86400) {
            $ret = round($seconds / 86400, 1) . __(' days', true);
        }
        elseif ($seconds < 3600) {
            $minutes = (int) ($seconds / 60);
            $seconds = $seconds % 60;
            $ret = $minutes . __(' minutes ', true) . $seconds
                . __(' seconds', true);
        }
        else {
            $hours = (int) ($seconds / 3600);
            $minutes = (int) ($seconds % 3600 / 60);
            $ret = $hours . __(' hours ', true) . $minutes .
                __(' minutes', true);
        }
        return $ret;
    }

    /**
     * Helper to filter events into 3 different categories and to
     * discard inactive events.
     *
     * The 3 categories are: Upcoming, Submitted, Expired
     *
     * - Upcoming are events that the user can still make submissions for.
     * - Submitted are events that the user has already made a submission.
     * - Expired are events that the user hasn't made and can no longer make
     * submissions, but they can still view results from their peers.
     *
     * An evaluation is considered inactive once past its result release
     * period. A survey is considered inactive once past its release period.
     *
     * @param array $events - list of events info returned from the event model,
     *  each event MUST have an 'EvaluationSubmission' array or this won't work
     *
     * @return Discard inactive events and then split the remaining events
     * into upcoming, submitted, and expired.
     * */
    private function _splitSubmittedEvents($events)
    {
        $submitted = $upcoming = $expired = array();
        foreach ($events as $event) {
            if (empty($event['EvaluationSubmission']) &&
                $event['Event']['is_released']
            ) { // can only take surveys during the release period
                $upcoming[] = $event;
            }
            else if (!empty($event['EvaluationSubmission']) &&
                strtotime('NOW') <
                strtotime($event['Event']['result_release_date_end'])
            ) { // has submission and can or will be able to view results soon
                // note that we're not using is_released or is_result_released
                // because of an edge case where if there is a period of time
                // between the release and result release period, the evaluation
                // will disappear from view
                $submitted[] = $event;
            }
            else if (!empty($event['EvaluationSubmission']) &&
                $event['Event']['is_released']
            ) {
                // special case for surveys, which doesn't have
                // result_release_date_end
                $submitted[] = $event;
            }
            else if (empty($event['EvaluationSubmission']) &&
                strtotime('NOW') <
                strtotime($event['Event']['result_release_date_end']) &&
                strtotime('NOW') >
                strtotime($event['Event']['release_date_end'])
            ) { // student did not do the survey within the allowed time
                // but we should still let them view results
                $expired[] = $event;
            }
        }
        return array('upcoming' => $upcoming,
            'submitted' => $submitted,
            'expired' => $expired
        );
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
