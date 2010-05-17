<?php
/* SVN FILE: $Id: rd_auth.php,v 1.7 2006/06/27 21:44:41 zoeshum Exp $ */
/*
 * rdAuth Component for ipeerSession
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class rdAuthComponent
{
	var $components = array('Session');
	
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
	* if admins passes then set admin var
	*
	* @var bool
	* @access private
	*/
	var $admin = null;
	/**
	* if role assigned to the logged in user matches admins array
	*
	* @var array
	* @access private
	*/
	var $admins = array();
	
	/**
	* Error messages to be displayed if the user is short of access for the requested action.
	*
	* @var string
	* @access private
	*/
	var $errors = null;
	
	var $courseId = null;
	
	var $customIntegrateCWL = 0;
	
	/**
	* Function to check the session and return local vars
	*
	* @param string $data used for login method
	* @return errors
	*/
	function set($data='')
	{
		if($data)
		{
			$this->Session->write('ipeerSession.id', $data['id']);
			$this->Session->write('ipeerSession.username', $data['username']);
			$this->Session->write('ipeerSession.fullname', $data['last_name'].' '.$data['first_name']);			
			$this->Session->write('ipeerSession.role', $data['role']);
			$this->Session->write('ipeerSession.email', $data['email']);
		}
		if($this->Session->check('ipeerSession') && $this->Session->valid('ipeerSession'))
		{
			$this->id = $this->Session->read('ipeerSession.id');
			$this->username = $this->Session->read('ipeerSession.username');
			$this->fullname = $this->Session->read('ipeerSession.fullname');			
			$this->role = $this->Session->read('ipeerSession.role');
			$this->email = $this->Session->read('ipeerSession.email');
			$this->customIntegrateCWL = $this->Session->read('ipeerSession.customIntegrateCWL');
			$this->courseId = $this->Session->read('ipeerSession.courseId');

			if(in_array($this->role,$this->admins))
			{
				$this->admin = 1;	
			}
		}
		elseif($this->Session->error())
		{
			return $this->Session->error();
		}
	}
	
	/**
	* logout method deletes session
	*
	* @return errors
	*/
	function logout()
	{
		$this->Session->del('ipeerSession.id');
		$this->Session->del('ipeerSession.username');
		$this->Session->del('ipeerSession.fullname');
		$this->Session->del('ipeerSession.role');
		$this->Session->del('ipeerSession.email');
		$this->Session->del('ipeerSession.courseId');
		$this->Session->del('ipeerSession');
		
    $this->Session->del('URL');
		$this->Session->del('AccessErr');
		$this->Session->del('Message');
		$this->Session->del('CWLErr');
			
		$this->role = null;
		$this->id = null;
		$this->username = null;
		$this->fullname = null;
		if($this->Session->error())
		{
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
	  
		if (is_array($actions) && array_key_exists($action, $actions))
		{
			if($this->role)
			{	
				$function = $actions[$action];
				//echo $function['function_name'];
				return true;
			}
			else
			{
				return false; 
			}
		}
		return	false;
	} 
	
	function setCourseId($courseId=null)
	{
	  $this->Session->del('ipeerSession.courseId');
	  $this->Session->write('ipeerSession.courseId', $courseId);
	  $this->courseId = $this->Session->read('ipeerSession.courseId');  
	}
 

}