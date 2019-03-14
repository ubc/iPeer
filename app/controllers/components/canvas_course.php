<?php
App::import('Model', 'User');
App::import('Component', 'CanvasApi');
App::import('Component', 'CanvasCourseUser');
App::import('Component', 'CanvasCourseGroup');
App::import('Component', 'CanvasCourseGradeColumn');

/**
 * CanvasCourseComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseComponent extends CakeObject
{
    public $id = null;
    public $name = null;
    public $course_code = null;
    public $term = null;

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
            if ($key == 'id' || $key == 'name' || $key == 'course_code' || $key == 'term') {
                $this->$key = $val;
            }
        }
    }

    /*********************************************************************************************/
    /* Static Functions
    /*********************************************************************************************/

    /**
     * Retreives a single Canvas course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string  $course_id
     *
     * @access public
     * @return object CanvasCourseComponent
     */
    static public function getById($_controller, $user_id, $course_id, $force_auth=true)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $course_id;

        $params = array(
            'include[]' => 'permissions'
        );

        $course_obj = $api->getCanvasData($_controller, $force_auth, $uri, $params);

        return new CanvasCourseComponent($course_obj);
    }

    /**
     * Retreives user's Canvas courses
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string  $enrollment_type Get courses the user enrolled as. Default as 'teacher'.
     *
     * @access public
     * @return array Array of CanvasCourseComponent with id as key
     */
    static public function getAllByIPeerUser($_controller, $user_id, $force_auth=true, $enrollment_type=CanvasCourseUserComponent::ENROLLMENT_QUERY_TEACHER)
    {
        $api = new CanvasApiComponent($user_id);
        $params = array(
            'include[]' => 'term',
            'enrollment_type' => $enrollment_type,
        );

        $courses_json = $api->getCanvasData($_controller, $force_auth, '/courses', $params);

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
     * Get the progress of an asynchronous Canvas API operation
     * Returns a progress object containing:
     * - completion (an integer out of 100)
     * - workflow_state (queued, running, completed, or failed)
     * - message (e.g. "17 courses processed"))
     * See https://canvas.instructure.com/doc/api/progress.html#Progress for full list
     *
     * @param object $_controller
     * @param integer $user_id
     * @param integer $progress_id
     * @param boolean $force_auth
     *
     * @access public
     * @return object progress object
     */
    static public function getProgress($_controller, $user_id, $progress_id, $force_auth=true) {

        $api = new CanvasApiComponent($user_id);
        $uri = '/progress/' . $progress_id;

        $progressObj = $api->getCanvasData($_controller, $force_auth, $uri);

        return $progressObj;
    }

    static public function getCourseUrl($user_id, $canvas_course_id)
    {
        $api = new CanvasApiComponent($user_id);
        return $api->getBaseUrl(true) . '/courses/' . $canvas_course_id;
    }

    /*********************************************************************************************/
    /* Users
    /*********************************************************************************************/

    /**
     * Retrieves a course's users
     *
     * @param object $_controller
     * @param integer $user_id
     * @param mixed $roles array of roles to retrieve. by default, retrieves teachers, TAs, and students
     *
     * @access public
     * @return array Array of CanvasCourseUserComponent.  Key is the value used to map Canvas users to iPeer username
     */
    public function getUsers($_controller, $user_id, $roles=array(
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
        $courseUsers_json = $api->getCanvasData($_controller, $force_auth, $uri, $params);

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

    /*********************************************************************************************/
    /* Groups
    /*********************************************************************************************/

    /**
     * Retrieves a course's groups
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     *
     * @access public
     * @return array Array of CanvasCourseGroupComponent
     */
    public function getGroups($_controller, $user_id, $force_auth=false, $group_category_id=null)
    {
        $api = new CanvasApiComponent($user_id);
        if (empty($group_category_id)) {
            $uri = '/courses/' . $this->id . '/groups';
        }
        else {
            $uri = '/group_categories/' . $group_category_id . '/groups';
        }

        $courseGroups_array = $api->getCanvasData($_controller, $force_auth, $uri);

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

    /**
     * Creates a new group in this course
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string $group_name
     * @param integer $group_category_id
     *
     * @access public
     * @return object of type CanvasCourseGroupComponent
     */
    public function createGroup($_controller, $user_id, $force_auth=false, $group_name='', $group_category_id=null)
    {
        $api = new CanvasApiComponent($user_id);
        if ($group_category_id) {
            $uri = '/group_categories/' . $group_category_id . '/groups';
        }
        else {
            $uri = '/groups';
        }

        $params = array(
            'name' => $group_name,
            'join_level' => 'parent_context_auto_join'
        );

        $courseGroup_obj = $api->postCanvasData($_controller, $force_auth, $uri, $params);

        return new CanvasCourseGroupComponent($courseGroup_obj);
    }

    /**
     * Deletes a group in this course
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string $group_id
     *
     * @access public
     * @return object returned object
     */
    public function deleteGroup($_controller, $user_id, $force_auth, $group_id)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/groups/' . $group_id;

        $retObj = $api->deleteCanvasData($_controller, $force_auth, $uri);

        return $retObj;
    }

    /*********************************************************************************************/
    /* Group Categories ("Group sets")
    /*********************************************************************************************/

    /**
     * Retrieves a course's group categories (also called Group sets)
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param mixed $names_only set to false to return the full group category objects, rather than just the names
     *
     * @access public
     * @return array Array of stdClass Objects (each representing one group category)
     */
    public function getGroupCategories($_controller, $user_id, $force_auth=false, $names_only=true)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/group_categories';

        $courseGroupCategories_array = $api->getCanvasData($_controller, $force_auth, $uri);

        $courseGroupCategories = array();

        if (!empty($courseGroupCategories_array)) {
            foreach ($courseGroupCategories_array as $cat) {
                $courseGroupCategories[$cat->id] = ($names_only) ? $cat->name : $cat;
            }
        }

        return $courseGroupCategories;
    }

    /**
     * Creates a new group category in this course
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string $group_category_name
     *
     * @access public
     * @return array containing the id and name of this group category
     */
    public function createGroupCategory($_controller, $user_id, $force_auth=false, $group_category_name=null)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/group_categories';

        // if no groupset name is passed in, create a sequential groupset name e.g. "Groupset 4"
        if (!$group_category_name) {
            $groupCategories = $this->getGroupCategories($_controller, $user_id, $force_auth);
            $groupCategoryNum = count($groupCategories) + 1;
            while (in_array("Group set " . $groupCategoryNum, $groupCategories)) {
                $groupCategoryNum++;
            }
            $group_category_name = "Groupset " . $groupCategoryNum;
        }

        $params = array(
            'name' => $group_category_name
        );

        $courseGroupCategory_obj = $api->postCanvasData($_controller, $force_auth, $uri, $params);

        $courseGroupCategory = array();
        foreach ($courseGroupCategory_obj as $key => $val) {
            if ($key == 'id' || $key == 'name') {
                $courseGroupCategory[$key] = $val;
            }
        }

        return $courseGroupCategory;
    }

    /*********************************************************************************************/
    /* Custom Grade Columns
    /*********************************************************************************************/

    /**
     * Retrieves a course's custom gradebook columns
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $include_hidden
     * @param boolean $force_auth
     *
     * @access public
     * @return array Array of CanvasCourseGradeColumnComponent
     */
    public function getGradeColumns($_controller, $user_id, $include_hidden=false, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/custom_gradebook_columns';
        $params['include_hidden'] = $include_hidden;

        $courseGradeColumns_json = $api->getCanvasData($_controller, $force_auth, $uri, $params);

        $courseGradeColumns = array();
        if (!empty($courseGradeColumns_json)) {
            foreach ($courseGradeColumns_json as $courseGradeColumn) {
                $courseGradeColumn_obj = new CanvasCourseGradeColumnComponent($this->id, $courseGradeColumn);
                if (!empty($courseGradeColumn_obj->id)) {
                    $courseGradeColumns[$courseGradeColumn_obj->id] = $courseGradeColumn_obj;
                }
            }
        }

        return $courseGradeColumns;
    }

    /**
     * Creates a new custom grade column in this course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param string  $title the name of this column
     * @param integer $position position of this column in the gradebook
     * @param boolean $hidden defaults to false
     * @param boolean $force_auth
     *
     * @access public
     * @return object of type CanvasCourseGradeColumnComponent
     */
    public function createGradeColumn($_controller, $user_id, $title, $position, $hidden=false, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/custom_gradebook_columns';

        $params = array(
            'column[title]' => $title,
            'column[position]' => ($position + 0),
            'column[hidden]' => $hidden
        );

        $courseGradeColumn_obj = $api->postCanvasData($_controller, $force_auth, $uri, $params);

        return new CanvasCourseGradeColumnComponent($this->id, $courseGradeColumn_obj);
    }

    /**
     * Deletes a custom grade column in this course
     *
     * @param object $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param string $grade_column_id
     *
     * @access public
     * @return object returned object
     */
    public function deleteGradeColumn($_controller, $user_id, $force_auth, $grade_column_id)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/custom_gradebook_columns/' . $grade_column_id;

        $retObj = $api->deleteCanvasData($_controller, $force_auth, $uri);

        return $retObj;
    }

    /*********************************************************************************************/
    /* Assignments
    /*********************************************************************************************/

    /**
     * Retrieves a single assignment for this course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param integer $assignment_id
     *
     * @access public
     * @return object of type CanvasCourseAssignmentComponent
     */
    public function getAssignment($_controller, $user_id, $assignment_id, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/assignments/' . $assignment_id;

        $courseAssignment_obj = $api->getCanvasData($_controller, $force_auth, $uri);

        if (!empty($courseAssignment_obj)) {
            return new CanvasCourseAssignmentComponent($this->id, $courseAssignment_obj);
        }
        else {
            return false;
        }
    }

    /**
     * Creates a new assignment in this course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     * @param array   $args
     *
     * @access public
     * @return mixed object of type CanvasCourseAssignmentComponent if successful. false otherwise
     */
    public function createAssignment($_controller, $user_id, $args, $assignment_group_name, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/assignments';

        $defaults = array(
            'submission_types' => array('none'),
            'published' => true,
            'grading_type' => 'points'
        );

        // place into assignment group named $assignment_group_name
        $assignmentGroups = $this->getAssignmentGroups($_controller, $user_id, $force_auth);
        foreach ($assignmentGroups as $assignmentGroup) {
            if ($assignmentGroup->name == $assignment_group_name) {
                $defaults['assignment_group_id'] = $assignmentGroup->id;
                break;
            }
        }
        if (empty($defaults['assignment_group_id'])) {
            $defaults['assignment_group_id'] = $this->createAssignmentGroup($_controller, $user_id, $assignment_group_name, $force_auth);
        }

        $the_assignment = array();
        foreach ($defaults as $key => $val) {
            $the_assignment[$key] = $val;
        }
        foreach ($args as $key => $val) {
            $the_assignment[$key] = $val;
        }
        $params['assignment'] = $the_assignment;

        $assignment_obj = $api->postCanvasData($_controller, $force_auth, $uri, $params);
        if (empty($assignment_obj)) {
            return false;
        }
        return new CanvasCourseAssignmentComponent($this->id, $assignment_obj);
    }

    /*********************************************************************************************/
    /* Assignment Groups
    /*********************************************************************************************/

    /**
     * Retrieves assignment groups for this course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param boolean $force_auth
     *
     * @access public
     * @return array  Array of stdClass Objects (each representing one assignment group)
     */
    public function getAssignmentGroups($_controller, $user_id, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/assignment_groups';

        return $api->getCanvasData($_controller, $force_auth, $uri);
    }

    /**
     * Create a new assignment group in this course
     *
     * @param object  $_controller
     * @param integer $user_id
     * @param string  $name
     * @param boolean $force_auth
     *
     * @access public
     * @return integer assignment group id
     */
    public function createAssignmentGroup($_controller, $user_id, $name, $force_auth=false)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/courses/' . $this->id . '/assignment_groups';

        $params['name'] = $name;

        $assignmentGroup_obj = $api->postCanvasData($_controller, $force_auth, $uri, $params);

        return $assignmentGroup_obj->id;
    }
}
