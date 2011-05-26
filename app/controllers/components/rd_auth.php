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
class rdAuthComponent extends AppController // This component is in fact a
                                            //controller since is accessed the USERS model
{

    var $modelClass;  // For contructClasses to Work with no warnin

    function __construct() {
        $this->constructClasses();
    }

	var $components = array('Session');
    var $uses = array('User');
    var $name = "rdAuthComponent";

	/**
	 * id of the logged in user
	 *
	 * @var unknown_type
	 * @access private
	 */
	var $id = null;

	/**
	 * username of the logged in user
	 *
	 * @var string
	 * @access private
	 */
	var $username = null;

	/**
	 * fullname of the logged in user
	 *
	 * @var string
	 * @access private
	 */
	var $fullname = null;

	/**
	 * email of the logged in user
	 *
	 * @var string
	 * @access private
	 */
	var $email = null;

	/**
	 * role assigned to the logged in user
	 *
	 * @var string
	 * @access private
	 */
	var $role = null;

	/**
	 * Error messages to be displayed if the user is short of access for the requested action.
	 *
	 * @var string
	 * @access private
	 */
	var $errors = null;

	var $courseId = null;

	var $customIntegrateCWL = 0;

    // =-=-=-==-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=
    // User privilege level functions

    // Get the privilege level for a user ID or a user type.
    function getPrivilegeLevel($user=null) {
        // For no parameters passed, get the privilege level of this user.
        if (empty($user)) {
            return $this->getPrivilegeLevel($this->role);
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

    function studentPrivilegeLevel() {
        return $this->getPrivilegeLevel('s');
    }

    function noStudentsAllowed() {
        // Make sure the present user is not a student
        if ($this->getPrivilegeLevel() <= $this->studentPrivilegeLevel()) {
            $this->privilegeError("rdAuth->noStudentsAllowed() caught a student user.");
        }
    }

    /**
     * Display a simple message the access was denied, with the option to go back.
     */
    function privilegeError($message = "") {
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
    }



	/**
	 * Loads the rdAuth data from the Session.
	 */
	function loadFromSession() {
		if($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession')) {
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
	 * Updates the user session from the user data passed, and loads it into this rdAuth object.
	 * @param unknown_type userData
	 */
	function setFromData($userData) {
		$this->Session->write('ipeerSession.id', $userData['id']);
		$this->Session->write('ipeerSession.username', $userData['username']);
		$this->Session->write('ipeerSession.fullname', $userData['first_name'].' '.$userData['last_name']);
		$this->Session->write('ipeerSession.role', $userData['role']);
		$this->Session->write('ipeerSession.email', $userData['email']);
		return $this->loadFromSession();
	}

	/**
	 * Clear the Session of rdAuth user data
	 */
	function clearUserDataFromSession() {
		$this->Session->del('ipeerSession.id');
		$this->Session->del('ipeerSession.username');
		$this->Session->del('ipeerSession.fullname');
		$this->Session->del('ipeerSession.role');
		$this->Session->del('ipeerSession.email');
		$this->Session->del('ipeerSession.courseId');
		$this->Session->del('ipeerSession');
	}

	/**
	 * logout method deletes session
	 *
	 * @return errors
	 */
	function logout() {
		// Clear this object's data
		$this->role = null;
		$this->id = null;
		$this->username = null;
		$this->fullname = null;

		// Then Clear up the user data
		$this->clearUserDataFromSession();

		// Return any errors
		if($this->Session->error()) {
			return $this->Session->error();
		}
	}

	/**
	 * Function to check the access for the action based on the access list
	 *
	 * @param string $action The action for which we need to check the access
	 * @param array $access Access array for the controller's actions
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

	function setCourseId($courseId=null)
	{
		$this->Session->del('ipeerSession.courseId');
		$this->Session->write('ipeerSession.courseId', $courseId);
		$this->courseId = $this->Session->read('ipeerSession.courseId');
	}


}
