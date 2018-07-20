<?php
App::import('Component', 'CanvasApi');

/**
 * CanvasCourseAssignmentComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Aidin Niavarani <aidin.niavarani@ubc.ca>
 * @copyright 2018 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseAssignmentComponent extends Object
{
    private $attributes = array('id', 'integration_id', 'course_id', 'published', 'name', 'description', 'due_at',
                                'lock_at', 'unlock_at', 'grade_group_students_individually', 'group_category_id',
                                'submission_types', 'points_possible', 'grading_type', 'percent', 'letter_grade',
                                'gpa_scale', 'use_rubric_for_grading', 'rubric_settings', 'rubric');

    public $id = null;
    public $integration_id = null; // could use this to store the iPeer eval ID (if needed)
    public $course_id = null;
    public $published = true;
    public $muted = null;
    public $name = null;
    public $description = null;
    public $submission_types = array();
    public $group_category_id = null;

    // dates
    public $unlock_at = null;
    public $lock_at = null;
    public $due_at = null;

    // grading
    public $grade_group_students_individually = false;
    public $grading_type = null; // possible values: 'points', 'pass_fail', 'percent', 'letter_grade', or 'gpa_scale'
    public $points_possible = null;
    public $use_rubric_for_grading = false;
    public $rubric_settings = null;
    public $rubric = null;

    /**
     * __construct
     *
     * @param mixed $args key -> value mappings to initialize the instance
     *
     * @access public
     * @return void
     */
    public function __construct($course_id, $args = array())
    {
        parent::__construct();

        $this->course_id = $course_id;

        foreach ($args as $key => $val) {
            if (in_array($key, $this->attributes)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Get all grade entries in this column
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     *
     * @access public
     * @return array
     */
    public function getGrades($_controller, $user_id, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->course_id . '/assignments/' . $this->id . '/submissions';

        $params = array(
            'include[]' => 'group'
        );

        $submissions_obj = $api->getCanvasData($_controller, $force_auth, $uri, $params);

        $grades = array();
        foreach ($submissions_obj as $submission_obj) {
            $grades[$submission_obj->user_id] = array(
                'grade' => $submission_obj->grade,
                'score' => $submission_obj->score,
                'workflow_state' => $submission_obj->workflow_state
            );
        }

        return $grades;
    }

    /**
     * Adds a grade for the specified user in Canvas
     * Note: This operation is done asynchronously in Canvas, and we can check the progress in Canvas
     *
     * @param object  $_controller
     * @param integer $user_id  this is the user id of the person performing this change (i.e. current user)
     * @param integer $grades an associative array mapping user_ids and grades
     * @param boolean $force_auth
     *
     * @access public
     * @return object a progress object, or false (if nothing to do / validation failed)
     *
     * Progress object: https://canvas.instructure.com/doc/api/progress.html#Progress
     */
    public function grade($_controller, $user_id, $grades, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->course_id . '/assignments/' . $this->id . '/submissions/update_grades';

        $params = array();
        $grade_data = array();
        foreach ($grades as $student_id => $grade) {
            $student_id += 0;
            if ($student_id) {
                $grade_data[$student_id]['posted_grade'] = $grade;
            }
        }

        if (count($grade_data)) {
            $params['grade_data'] = $grade_data;
            return $api->postCanvasData($_controller, $force_auth, $uri, $params);
        }

        return false;
    }
}