<?php
App::import('Component', 'CanvasApi');

/**
 * CanvasCourseGradeColumnComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Aidin Niavarani <aidin.niavarani@ubc.ca>
 * @copyright 2018 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseGradeColumnComponent extends CakeObject
{
    public $course_id = null;
    public $id = null;
    public $title = null;
    public $position = null;
    public $hidden = false;

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
            if ($key == 'id' || $key == 'title' || $key == 'position' || $key == 'hidden') {
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
     * @return void
     */
    public function getGrades($_controller, $user_id, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->course_id . '/custom_gradebook_columns/' . $this->id . '/data';

        $gradesArray = $api->getCanvasData($_controller, $force_auth, $uri);

        $grades = array();
        if (!empty($gradesArray)) {
            foreach ($gradesArray as $userGrade) {
                $grades[$userGrade->user_id] = $userGrade->content;
            }
        }

        return $grades;
    }
}
