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
class sysContainerComponent
{
	var $components = array('Session');

	/**
	 * List of accessible functions using function code as index key
	 *
	 * @var array
	 * @access private
	 */
	var $accessFunctionList = array();

	/**
	 * List of accessible functions using action as index key
	 *
	 * @var array
	 * @access private
	 */
	var $actionList = array();

	/**
	 * List of system parameters
	 *
	 * @var array
	 * @access private
	 */
	var $paramList = array();

	/**
	 * List of my courses
	 *
	 * @var array
	 * @access private
	 */
	var $myCourseList = array();

	var $myCourseIDs = '';
	// var $course
	/**
	 * Function to set the accessible functions
	 *
	 * @param string $data used for login method
	 * @return errors
	 */
	function setAccessFunctionList($funcList='')
	{

		$result = array();
		foreach($funcList as $row) {
            $sysFunction = $row['SysFunction'];

            // Do no accept certain functions, in case the database has bad entries.
            if ($sysFunction['id'] == 0) continue;      // No low ID's
            if (empty($sysFunction['id'])) continue;    // No empty URL's.
            if ($sysFunction['id'] == "/") continue;    // No empty URL's.

            // Cut a trailing shash in url if it exists
            if ($sysFunction['url_link'][strlen($sysFunction['url_link']) - 1] == "/") {
                $sysFunction['url_link'] = substr($sysFunction['url_link'], 0, (-1) );
            }

            $result[$sysFunction['function_code']] = $sysFunction;
		}

		$this->accessFunctionList = $result;
		$this->Session->write('ipeerSession.accessFunctionList', $this->accessFunctionList);
	}

	/**
	 * Function to get the accessible functions
	 * Method to get: $tmp= $result['USR'];
	 *                echo $tmp['function_name'];
	 * @param string $data used for login method
	 * @return errors
	 */
	function getAccessFunctionList()
	{

		$this->accessFunctionList=$this->Session->read('ipeerSession.accessFunctionList');
		return $this->accessFunctionList;
	}

	/**
	 * Function to set the accessible functions by action
	 *
	 * @param string $data used for login method
	 * @return errors
	 */
	function setActionList($actionList='')
	{
		$result = array();
		foreach($actionList as $row) {
            $sysFunction = $row['SysFunction'];

            // Do no accept certain functions, in case the database has bad entries.
            if ($sysFunction['id'] == 0) continue;      // No low ID's
            if (empty($sysFunction['id'])) continue;    // No empty URL's.
            if ($sysFunction['id'] == "/") continue;    // No empty URL's.

            // Cut a trailing shash in url if it exists
            if ($sysFunction['url_link'][strlen($sysFunction['url_link']) - 1] == "/") {
                $sysFunction['url_link'] = substr($sysFunction['url_link'], 0, (-1) );
            }

            $result[$sysFunction['controller_name']] = $sysFunction;
		}

		$this->actionList = $result;
		$this->Session->write('ipeerSession.actionList', $this->actionList);
	}

	/**
	 * Function to get the accessible functions
	 * Method to get: $tmp= $result['USR'];
	 *                echo $tmp['function_name'];
	 * @param string $data used for login method
	 * @return errors
	 */
	function getActionList()
	{
		$this->actionList=$this->Session->read('ipeerSession.actionList');
		return $this->actionList;
	}

	function getFieldByFunctionCode($funcCode='', $field)
	{
		if (!empty($this->accessFunctionList)){
			$function= $this->accessFunctionList[$funcCode];
			return $function[$field];
		}
	}

	function getFieldByAction($action='', $field)
	{
		if (!empty($this->actionList)){
			$function= $this->actionList[$action];
			return $function[$field];
		}
	}

	/**
	 * Function to set the system parameters
	 *
	 * @param string $data used for login method
	 * @return errors
	 */
	function setParamList($paramList='')
	{
		$result = array();
		foreach($paramList as $row): $sysParameter = $row['SysParameter'];
		$result[$sysParameter['parameter_code']] = $sysParameter;
		endforeach;

		$this->paramList = $result;
		$this->Session->write('ipeerSession.paramList', $this->paramList);
	}

	/**
	 * Function to get the system parameters
	 * Method to get: $tmp= $result['system.debug_mode'];
	 *                echo $tmp['parameter_value'];
	 * @param string $data used for login method
	 * @return errors
	 */
	function getParamByParamCode($paraCode, $default = null)
	{
		// echo "<h1>" . $paraCode . "</h1>";
		$paramList = $this->Session->read('ipeerSession.paramList');

		// echo "<pre>"; print_r($paramList);echo "</pre>";
		$sysParameter = isset($paramList[$paraCode]) ? $paramList[$paraCode] : $default;
		return $sysParameter;
	}

	/**
	 * Function to set the my courses functions
	 *
	 * @param string
	 * @return errors
	 */
	function setMyCourseList($coursesList='')
	{
		$result = array();

		if ($coursesList!=null)
		{
			$pos = 1;
			foreach($coursesList as $row): $course = $row['courses'];
			$result[$course['id']] = $course;

			$this->myCourseIDs .= $course['id'];
			if ($pos < count($coursesList)) {
				$this->myCourseIDs .= ', ';
				$pos++;
			}
			endforeach;

			$this->myCourseList = $result;
			$this->Session->write('ipeerSession.myCourseList', $this->myCourseList);
			$this->Session->write('ipeerSession.myCourseIDs', $this->myCourseIDs);
		}
	}

	/**
	 * Function to get the accessible functions
	 * Method to get: $tmp= $result['USR'];
	 *                echo $tmp['function_name'];
	 * @param string $data used for login method
	 * @return errors
	 */
	function getMyCourseList()
	{
		$this->myCourseList=$this->Session->read('ipeerSession.myCourseList');
    if($this->myCourseList == null) $this->myCourseList = array();
		return $this->myCourseList;
	}

	function getMyCourseIDs()
	{
		$this->myCourseIDs=$this->Session->read('ipeerSession.myCourseIDs');
		return $this->myCourseIDs;
	}

	/**
	 * Function to get the accessible functions
	 * Method to get: $tmp= $result['USR'];
	 *                echo $tmp['function_name'];
	 * @param string $data used for login method
	 * @return errors
	 */
	function getCourseName($courseId=null, $userRole='', $courseObj=null)
	{
		$this->Course = new Course;
		$course = null;
		if ($courseObj!=null)
		{
			$course = $courseObj;
		} else {
			$this->myCourseList = $this->getMyCourseList();
			if (isset($this->myCourseList[$courseId]))
			$course= $this->myCourseList[$courseId];
			else {
				$courseLink = '&nbsp;<img alt="home" src="'. dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" align="middle" />&nbsp;'.$this->Course->getCourseName($courseId);
				return $courseLink;
			}
		}
		//TODO: Change /ipeer2 using Session Name in SysParameter
		if (!empty($course['homepage'])) {
			$courseLink = '&nbsp;<a href="'.$course['homepage'].'"  target="_blank"><img alt="home" src="'.dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" align="middle" /></a>&nbsp;';
		} else {
			$courseLink = '&nbsp;<img alt="home" src="'. dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" align="middle" />&nbsp;';
		}
		if ($userRole != 'S') {
			$courseLink .=  '<a href="' . preg_replace( '/\/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])) . '/courses/home/'.$courseId.'" ><u>'.$course['course'].'</u></a>';
		}
		else {
			$courseLink .=  $course['course'];
		}

		return $courseLink;
	}

	function setCourseId($courseId=null)
	{
		$this->Session->write('ipeerSession.courseId', $courseId);
	}

	function getCourseId()
	{
		return $this->Session->read('ipeerSession.courseId');
	}

	function getUserInfo($user_id, $opt='fullname')
	{

		$this->User = new User;
		$user = $this->User->findUserByid($user_id);
		if ($opt == 'fullname') {
			return $user['User']['first_name'].' '.$user['User']['last_name'];
		}
	}

	function checkEvaluationToolInUse($evalTool=null, $templateId=null)
	{
		//Get the target event
		$this->Event = new Event;
		return $this->Event->checkEvaluationToolInUse($evalTool, $templateId);
	}
}
