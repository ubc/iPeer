<?php
/* SVN FILE: $Id$ */
/*
 * rdAuth Component for ipeerSession
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
/**
 * rdAuthComponent
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class rdAuthComponent extends AppController // This component is in fact a
    //controller since is accessed the USERS model
{

    public $modelClass;  // For contructClasses to Work with no warnin

/*    function __construct()
{
        $this->constructClasses();
}*/

    public $components = array('Session', 'Auth');
    public $uses = array('User');
    public $name = 'rdAuthComponent';

    /**
     * id of the logged in user
     *
     * @public unknown_type
     * @access private
     */
    public $id = null;

    /**
     * username of the logged in user
     *
     * @public string
     * @access private
     */
    public $username = null;

    /**
     * fullname of the logged in user
     *
     * @public string
     * @access private
     */
    public $fullname = null;

    /**
     * email of the logged in user
     *
     * @public string
     * @access private
     */
    public $email = null;

    /**
     * role assigned to the logged in user
     *
     * @public string
     * @access private
     */
    public $role = null;

    /**
     * Error messages to be displayed if the user is short of access for the requested action.
     *
     * @public string
     * @access private
     */
    public $errors = null;

    public $courseId = null;

    public $customIntegrateCWL = 0;

    // =-=-=-==-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=
    // User privilege level functions
    /**
     * getPrivilegeLevel
     * Get the privilege level for a user ID or a user type.
     *
     * @param bool $user
     *
     * @access public
     * @return void
     */
    function getPrivilegeLevel($user=null)
    {
        // For no parameters passed, get the privilege level of this user.
        if (empty($user)) {
            //return $this->getPrivilegeLevel($this->role);
            return $this->getPrivilegeLevel($this->Auth->user('role'));
        } else if (is_numeric($user)) {
            // If $user is a numberic user ID, look it up in the database.
            $data = $this->User->findById($user);
            return $data ? $this->getPrivilegeLevel($data['User']['role']) : 0;
        } else {
            // Or if this is a string, look up the user type, and return privilege
            switch (strtolower($user)) {
            case "s" : return 200;
            case "i" : return 600;
            case "a" : return 1200;
            default : return 0;
            }
        }
    }


    /**
     * studentPrivilegeLevel
     *
     * @access public
     * @return void
     */
    function studentPrivilegeLevel()
    {
        return $this->getPrivilegeLevel('s');
    }


    /**
     * noStudentsAllowed
     *
     * @access public
     * @return void
     */
    function noStudentsAllowed()
    {
        // Make sure the present user is not a student
        if ($this->getPrivilegeLevel() <= $this->studentPrivilegeLevel()) {
            $this->privilegeError("rdAuth->noStudentsAllowed() caught a student user.");
        }
    }


    /**
     * Display a simple message the access was denied, with the option to go back.
     */
/*    function privilegeError($message = "")
{
        echo "<html><head><title>iPeer: Access Denied</title></head><body>";
        echo "<center><div style='width:50%; padding: 20px;border: 1px solid #000; background-color:#FFFFE0'>";
        echo "<h1>iPeer</h1>";
        echo "<h2>Access Denied</h2>";
        echo "<h4>( to: <tt><script language='javascript' type='text/javascript'>document.write (document.location.href);</script> )</tt></h4>";
        echo "<h5>" . (!empty($message) ? "reason: $message" : "") . "</h5>";
        echo "<input type='button' value='Click here to go back' onClick='history.back()'>";
        echo "</div>";
        echo "</center></body></html>";
        exit;
}*/

    /**
     * loadFromSession
     * Loads the rdAuth data from the Session.
     *
     *
     * @access public
     * @return void
     */
    function loadFromSession()
    {
        if ($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession')) {
            $this->id = $this->Session->read('ipeerSession.id');
            $this->username = $this->Session->read('ipeerSession.username');
            $this->fullname = $this->Session->read('ipeerSession.fullname');
            $this->role = $this->Session->read('ipeerSession.role');
            $this->email = $this->Session->read('ipeerSession.email');
            $this->customIntegrateCWL = $this->Session->read('ipeerSession.customIntegrateCWL');
            $this->courseId = $this->Session->read('ipeerSession.courseId');
        } else {
            return $this->Session->error();
        }
    }


    /**
     * setFromData
     * Updates the user session from the user data passed, and loads it into this rdAuth object.
     *
     * @param mixed $userData
     *
     * @access public
     * @return void
     */
    function setFromData($userData)
    {
        $this->Session->write('ipeerSession.id', $userData['id']);
        $this->Session->write('ipeerSession.username', $userData['username']);
        $this->Session->write('ipeerSession.fullname', $userData['last_name'].' '.$userData['first_name']);
        $this->Session->write('ipeerSession.role', $userData['role']);
        $this->Session->write('ipeerSession.email', $userData['email']);
        return $this->loadFromSession();
    }

    /**
     * clearUserDataFromSession
     * Clear the Session of rdAuth user data
     *
     *
     * @access public
     * @return void
     */
    function clearUserDataFromSession()
    {
        $this->Session->delete('ipeerSession.id');
        $this->Session->delete('ipeerSession.username');
        $this->Session->delete('ipeerSession.fullname');
        $this->Session->delete('ipeerSession.role');
        $this->Session->delete('ipeerSession.email');
        $this->Session->delete('ipeerSession.courseId');
        $this->Session->delete('ipeerSession');
    }

    /**
     * logout
     * logout method deletes session
     *
     *
     * @access public
     * @return errors
     */
    function logout()
    {
        // Clear this object's data
        $this->role = null;
        $this->id = null;
        $this->username = null;
        $this->fullname = null;

        // Then Clear up the user data
        $this->clearUserDataFromSession();

        // Return any errors
        if ($this->Session->error()) {
            return $this->Session->error();
        }
    }


    /**
     * Function to check the access for the action based on the access list
     *
     * @param string $action  The action for which we need to check the access
     * @param array  $actions Access array for the controller's actions
     *
     * @return boolean
     */
    function check($action, $actions)
    {

        if (is_array($actions) && array_key_exists($action, $actions)) {
            return $this->role;
        } else {
            return false;
        }
    }

    /**
     * setCourseId
     *
     * @param bool $courseId
     *
     * @access public
     * @return void
     */
    function setCourseId($courseId=null)
    {
        $this->Session->delete('ipeerSession.courseId');
        $this->Session->write('ipeerSession.courseId', $courseId);
        $this->courseId = $this->Session->read('ipeerSession.courseId');
    }


    /**
     * setUserId
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function setUserId($userId)
    {
        $this->id=$userId;
    }
}
