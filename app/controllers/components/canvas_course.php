<?php
App::import('Model', 'User');
App::import('Component', 'CanvasApi');
App::import('Component', 'CanvasCourseUser');
App::import('Component', 'CanvasCourseGroup');

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
     * @param mixed $args key -> value mappings to initialize the instance
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
     * @param string $enrollment_type Get courses the user enrolled as. Default as 'teacher'.
     *
     * @access public
     * @return array Array of CanvasCourseComponent with id as key
     */
    static public function getCanvasCoursesByIPeerUser($_controller, $user_id, $force_auth=true, $enrollment_type=CanvasCourseUserComponent::ENROLLMENT_QUERY_TEACHER)
    {
        $api = new CanvasApiComponent($user_id);
        $courses_json = $api->getCanvasData($_controller, Router::url(null, true), $force_auth, '/courses', array('enrollment_type' => $enrollment_type));

        $courses = array();
        if (!empty($courses_json)) {
            foreach ($courses_json as $course) {
                $course_obj = new CanvasCourseComponent($course);
                $courses[$course_obj->id] = $course_obj;
            }
        }

        return $courses;
    }

    /**
     * Retrieves a course's users
     *
     * @param mixed $user_id
     * @param mixed $roles array of roles to retrieve. by default, retrieves teachers, TAs, and students
     *
     * @access public
     * @return array Array of CanvasCourseUserComponent.  Key is the value used to map Canvas users to iPeer username
     */
    public function getCanvasCourseUsers($_controller, $user_id, $roles=array(
        CanvasCourseUserComponent::ENROLLMENT_QUERY_STUDENT,
        CanvasCourseUserComponent::ENROLLMENT_QUERY_TEACHER,
        CanvasCourseUserComponent::ENROLLEMNT_QUERY_TA), 
        $active_only=false, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/users';
        $params['enrollment_type[]'] = $roles;      // note the square brackets used in key
        $params['include[]'] = array('enrollments', 'email');
        
        //$courseUsers_json = $api->getCanvasData($uri, $params);
        $courseUsers_json = $api->getCanvasData($_controller, Router::url(null, true), $force_auth, $uri, $params);

        $courseUsers = array();
        if (!empty($courseUsers_json)) {
            foreach ($courseUsers_json as $courseuser) {
                $courseuser_obj = new CanvasCourseUserComponent($courseuser);
                $key = $courseuser_obj->canvas_user_key;    // key used to map canvas user to iPeer username
                if (!empty($courseuser_obj->$key)) {
                    $courseUsers[$courseuser_obj->$key] = $courseuser_obj;
                }
            }
        }

        return $courseUsers;
    }
    
    /**
     * Retrieves a course's groups
     *
     * @param mixed $user_id
     *
     * @access public
     * @return array Array of CanvasCourseGroupComponent
     */
    public function getCanvasCourseGroups($_controller, $user_id, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/groups';
        
        $courseGroups_array = $api->getCanvasData($_controller, Router::url(null, true), $force_auth, $uri);

        $courseUsers = array();
        if (!empty($courseGroups_array)) {
            foreach ($courseGroups_array as $courseGroup_details) {
                $courseGroup = new CanvasCourseGroupComponent($courseGroup_details);
                if (!empty($courseGroup->id)) {
                    $courseUsers[$courseGroup->id] = $courseGroup;
                }
            }
        }

        return $courseUsers;
    }
}
