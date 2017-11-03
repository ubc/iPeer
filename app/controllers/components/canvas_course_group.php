<?php
App::import('Model', 'Group');
App::import('Component', 'CanvasApi');

/**
 * CanvasCourseGroupComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Aidin Niavarani <aidin.niavarani@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasCourseGroupComponent extends Object
{
    public $id = null;
    public $name = null;

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
        
        foreach ($args as $key => $val) {
            if ($key == 'id' || $key == 'name') {
                $this->$key = $val;
            } 
        }
    }

    public function getGroupUsers($_controller, $user_id, $force_auth=false){

        $api = new CanvasApiComponent($user_id);
        $uri = '/groups/' . $this->id . '/users';
        
        $usersArray = $api->getCanvasData($_controller, Router::url(null, true), $force_auth, $uri);

        $groupUsers = array();
        if (!empty($usersArray)) {
            foreach ($usersArray as $user) {
                $user_obj = new CanvasCourseUserComponent($user);
                $key = $user_obj->canvas_user_key;    // key used to map canvas user to iPeer username
                if (!empty($user_obj->$key)) {
                    $groupUsers[$user_obj->$key] = $user_obj;
                }
            }
        }

        return $groupUsers;
    }
}