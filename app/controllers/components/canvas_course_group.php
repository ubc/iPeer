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
class CanvasCourseGroupComponent extends CakeObject
{
    public $id = null;
    public $name = null;
    public $group_category_id = null;

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
            if ($key == 'id' || $key == 'name' || $key == 'group_category_id') {
                $this->$key = $val;
            } 
        }
    }
    
    public function getUsers($_controller, $user_id, $force_auth=false, $key='canvas_user_key', $per_page=100)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/groups/' . $this->id . '/users';
        $params = array(
            'per_page' => $per_page
        );
        
        $usersArray = $api->getCanvasData($_controller, $force_auth, $uri);

        $groupUsers = array();
        if (!empty($usersArray)) {
            foreach ($usersArray as $user) {
                $user_obj = new CanvasCourseUserComponent($user);
                if ($key == 'canvas_user_key') {
                    $key = $user_obj->canvas_user_key;    // key used to map canvas user to iPeer username
                }
                if (!empty($user_obj->$key)) {
                    $groupUsers[$user_obj->$key] = $user_obj;
                }
            }
        }

        return $groupUsers;
    }

    /**
     * Adds a user to this group in Canvas
     *
     * @param object $_controller
     * @param integer $user_id  this is the user id of the person performing this change (i.e. current user)
     * @param boolean $force_auth
     * @param integer $user_id_to_add this is the Canvas user id of the user to be added to this group
     * @return object returned object
     */
    public function addUser($_controller, $user_id, $force_auth=false, $user_id_to_add)
    {
        $api = new CanvasApiComponent($user_id);
        $uri = '/groups/' . $this->id . '/memberships';
        
        $params = array(
            'user_id' => $user_id_to_add
        );

        $retObj = $api->postCanvasData($_controller, $force_auth, $uri, $params);

        return $retObj;
    }
}
