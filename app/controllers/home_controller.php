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
        if (User::hasPermission('functions/coursemanager')) {
            // Admins and profs
            $course_list = $this->Course->getAllAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'all', array('contain' => array('Event', 'Instructor')));
            $this->set('course_list', $this->_formatCourseList($course_list));
            return;
        }

        // Student and tutor
        
        // TODO
        // make sure that submitted surveys CAN be viewed
        // make a summary section that tells students if there are any urgently
        //  due assignments
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

        $evals = $this->_splitSubmittedEvents($events['Evaluations']);
        $surveys = $this->_splitSubmittedEvents($events['Surveys']);
        $this->set('evals', $evals);
        $this->set('surveys', $surveys);
        $this->render('studentIndex');
    }

    /**
     * Take the due interval, which is in seconds, and format
     * it something that's easier for users to read.
     * */
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
     * Helper for index to split an array of survey  or 
     * evaluation events into events that has submissions
     * and events that don't have any submissions.
     *
     * @param array $events - list of events info returned from the event model,
     *  each event MUST have an 'EvaluationSubmission' array or this won't work
     *
     * @return Split the events array into 'upcoming' or 'submitted' categories
     * */
    private function _splitSubmittedEvents($events) 
    {
        $submitted = $upcoming = array();
        foreach ($events as $event) {
            if (empty($event['EvaluationSubmission']) || 
                $event['Event']['is_ended']
            ){
                $upcoming[] = $event;
            } else {
                $submitted[] = $event;
            }
        }
        return array('upcoming' => $upcoming, 'submitted' => $submitted);
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
