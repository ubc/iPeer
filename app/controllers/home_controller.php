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
            //General Home Rendering for others
            $course_list = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'all', array('contain' => array('Event', 'Instructor')));
            $this->set('course_list', $this->_formatCourseList($course_list));
        } else {
            // student and tutor
            $events = $this->Event->getEventsByUserId(User::get('id'));
            $submitted = $upcoming = array();
            foreach ($events as $event) {
                if (isset($event['EvaluationSubmission']['id'])) {
                    $submitted[] = $event;
                } else {
                    $upcoming[] = $event;
                }
            }
            $this->set('submitted', $submitted);
            $this->set('upcoming', $upcoming);
            $this->render('studentIndex');
        }
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
