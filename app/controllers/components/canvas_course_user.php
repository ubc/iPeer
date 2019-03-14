<?php
App::import('Model', 'SysParameter');
App::import('Model', 'User');
App::import('Component', 'CanvasApi');

/**
 * CanvasCourseUserComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseUserComponent extends CakeObject
{
    public $canvas_user_key = 'integration_id';
    
    const ENROLLMENT_QUERY_STUDENT = 'student';
    const ENROLLMENT_QUERY_TEACHER = 'teacher';
    const ENROLLEMNT_QUERY_TA = 'ta';
    
    const ENROLLMENT_TYPE_STUDENT = 'StudentEnrollment';
    const ENROLLMENT_TYPE_TEACHER = 'TeacherEnrollment';
    const ENROLLMENT_TYPE_TA = 'TaEnrollment';
    
    public $id = null;
    public $sis_user_id = null;
    public $name = null;
    public $login_id = null;
    public $enrollment_roles = array();

    /**
     * Finds the corresponding iPeer user with specific role
     */
    public static function getCorrespondingUser($currentUser, $canvas_course_users, $role)
    {
        $result = array();
        foreach ($canvas_course_users as $ccuser) {
            if (in_array($role, $ccuser->enrollment_roles)) {
                $iuser = $ccuser->getMatchingiPeerUser($currentUser);

                if ($iuser) {
                    $result[$iuser['id']] = $iuser;
                }
            }
        }
        return $result;
    }

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
        
        // Determine which key used to map Canvas user to iPeer username
        $SysParameter = ClassRegistry::init('SysParameter');
        $user_key = $SysParameter->get('system.canvas_user_key'); 
        if (!empty($user_key)) {
            $this->canvas_user_key = $user_key;
        }
        $user_key = $this->canvas_user_key;
        $this->$user_key = null;
        
        foreach ($args as $key => $val) {
            if ($key == 'id' || $key == 'name' || $key == $this->canvas_user_key || $key == 'login_id' || $key == 'email' || $key == 'sis_user_id') {
                $this->$key = $val;
            } else if ($key == 'sortable_name') {
                $nameParts = explode(', ', $val);
                $this->first_name = count($nameParts)>1 ? $nameParts[1] : $nameParts[0];
                $this->last_name = count($nameParts)>1 ? $nameParts[0] : '';
            } else if ($key == 'enrollments') {
                foreach ($val as $enrollment) {
                    array_push($this->enrollment_roles, $enrollment->role);
                }
            }
        }
    }

    /**
     * Retrieves the corresponding iPeer User based on Canva user. null if not found.
     *
     * @param $enquirer the User doing the search
     *
     * @access public
     * @return User
     */
    public function getMatchingiPeerUser($enquirer)
    {
        $user_key = $this->canvas_user_key;
        $result = $enquirer->find('first', array(
            'conditions' => array('User.username' => $this->$user_key)));

        return $result['User'];
    }
}
