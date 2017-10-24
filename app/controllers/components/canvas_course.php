<?php
App::import('Model', 'User');
App::import('Component', 'CanvasApi');
/**
 * CanvasCourseComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseComponent extends Object
{
    public $id = null;
    public $name = null;
    public $course_code = null;

    /**
     * __construct
     *
     * @param array $args
     *
     * @access public
     * @return void
     */
    public function __construct($args = array())
    {
        parent::__construct();
        foreach($args as $key => $val) {
            if ($key == 'id' || $key == 'name' || $key == 'course_code') {
                $this->$key = $val;
            }
        }
    }

    /**
     * Retreives user's Canvas courses
     *
     * @param mixed $user_id
     *
     * @access public
     * @return array Array of CanvasCourseComponent with id as key
     */
    static public function getCanvasCoursesByIPeerUser($user_id)
    {
        $api = new CanvasApiComponent($user_id);
        $courses_json = $api->getCanvasData('/courses', array('enrollment_type' => 'teacher'));

        $courses = array();
        foreach ($courses_json as $course) {
            $course_obj = new CanvasCourseComponent($course);
            $courses[$course_obj->id] = $course_obj;
        }

        return $courses;
    }
}
