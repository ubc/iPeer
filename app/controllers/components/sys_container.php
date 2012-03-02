<?php
// ************* WARNING ******************
// PLEASE PHASE OUT THIS COMPONENT
// JUST USE THE SysParameter AND SysFunction
// MODELS DIRECTLY INSTEAD
//
// It's generally a bad idea to use models in
// a component.

App::import('Model', 'SysParameter');
App::import('Model', 'SysFunction');

/**
 * sysContainerComponent
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class sysContainerComponent
{
    public $components = array('Session');

    /**
     * List of accessible functions using function code as index key
     *
     * @public array
     * @access private
     */
    public $accessFunctionList = array();

    /**
     * List of accessible functions using action as index key
     *
     * @public array
     * @access private
     */
    public $actionList = array();

    /**
     * List of my courses
     *
     * @public array
     * @access private
     */
    public $myCourseList = array();

    public $myCourseIDs = '';
    public $SysParameter = null;
    public $SysFunction = null;

    /**
     * initialize
     *
     * @param mixed &$controller controller
     * @param bool  $settings    settings
     *
     * @access public
     * @return void
     */
    function initialize(&$controller, $settings=array())
    {
        $this->controller = $controller;
        $this->SysParameter = new SysParameter;
        $this->SysFunction= new SysFunction;
        //parent::initialize($controller);
    }


    /**
     * Function to set the accessible functions
     *
     * @param string $funcList
     *
     * @return errors
     */
    function setAccessFunctionList($funcList='')
    {
        $result = array();
        foreach ($funcList as $row) {
            $sysFunction = $row['SysFunction'];

            // Do no accept certain functions, in case the database has bad entries.
            // No low ID's
            if ($sysFunction['id'] == 0) {
                continue;
            }
            // No empty URL's.
            if (empty($sysFunction['id'])) {
                continue;
            }
            // No empty URL's.
            if ($sysFunction['id'] == "/") {
                continue;
            }

            // Cut a trailing shash in url if it exists
            if ($sysFunction['url_link'][strlen($sysFunction['url_link']) - 1] == "/") {
                $sysFunction['url_link'] = substr($sysFunction['url_link'], 0, (-1));
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
     *
     * @return errors
     */
    function getAccessFunctionList()
    {
        $this->accessFunctionList = $this->Session->read('ipeerSession.accessFunctionList');

        if (empty($this->accessFunctionList)) {
            $accessFunction = $this->SysFunction->getAllAccessibleFunction(User::get('role'));
            $this->setAccessFunctionList($accessFunction);
            $this->accessFunctionList = $this->Session->read('ipeerSession.accessFunctionList');
        }
        return $this->accessFunctionList;
    }

    /**
     * Function to set the accessible functions by action
     *
     * @param string $actionList
     *
     * @return errors
     */
    function setActionList($actionList='')
    {
        $result = array();
        foreach ($actionList as $row) {
            $sysFunction = $row['SysFunction'];

            // Do no accept certain functions, in case the database has bad entries.
            // No low ID's
            if ($sysFunction['id'] == 0) {
                continue;
            }
            // No empty URL's.
            if (empty($sysFunction['id'])) {
                continue;
            }
            // No empty URL's.
            if ($sysFunction['id'] == "/") {
                continue;
            }

            // Cut a trailing shash in url if it exists
            if ($sysFunction['url_link'][strlen($sysFunction['url_link']) - 1] == "/") {
                $sysFunction['url_link'] = substr($sysFunction['url_link'], 0, (-1));
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
     *
     * @return errors
     */
    function getActionList()
    {
        $this->actionList=$this->Session->read('ipeerSession.actionList');
        return $this->actionList;
    }

    /**
     * getFieldByFunctionCode
     *
     * @param string $funcCode function code
     * @param mixed  $field    field
     *
     * @access public
     * @return void
     */
    function getFieldByFunctionCode($funcCode, $field)
    {
        if (!empty($this->accessFunctionList)) {
            $function= $this->accessFunctionList[$funcCode];
            return $function[$field];
        }
    }


    /**
     * getFieldByAction
     *
     * @param mixed $action action
     * @param mixed $field  field
     *
     * @access public
     * @return void
     */
    function getFieldByAction($action, $field)
    {
        if (!empty($this->actionList)) {
            $function= $this->actionList[$action];
            return $function[$field];
        }
    }


    /**
     * Function to set the system parameters
     *
     * @param string $paramList
     *
     * @return errors
     */
    function setParamList($paramList='')
    {
        $result = array();
        foreach ($paramList as $row) {
            $sysParameter = $row['SysParameter'];
            $result[$sysParameter['parameter_code']] = $sysParameter;
        }

        $this->controller->Session->write('ipeerSession.paramList', $result);
        return $result;
    }

    /**
     * Function to get the system parameters
     * Method to get: $tmp= $result['system.debug_mode'];
     *                echo $tmp['parameter_value'];
     *
     * @param mixed $paraCode param code
     * @param bool  $default  default
     *
     * @return errors
     */
    function getParamByParamCode($paraCode, $default = null)
    {
        $paramList = $this->controller->Session->read('ipeerSession.paramList');
        if ($paramList === null) {
            $paramList = $this->SysParameter->find('all', array('fields' => array('id', 'parameter_code', 'parameter_value', 'parameter_type')));
            $paramList = $this->setParamList($paramList);
        }

        $sysParameter = isset($paramList[$paraCode]) ? $paramList[$paraCode] : $default;
        return $sysParameter;
    }

    /**
     * Function to set the my courses functions
     *
     * @param string $coursesList
     *
     * @return errors
     */
    function setMyCourseList($coursesList='')
    {
        $result = array();

        if ($coursesList!=null) {
            $pos = 1;
            foreach ($coursesList as $row) {
                $course = $row['Course'];
                $result[$course['id']] = $course;

                $this->myCourseIDs .= $course['id'];
                if ($pos < count($coursesList)) {
                    $this->myCourseIDs .= ', ';
                    $pos++;
                }
            }

            $this->myCourseList = $result;
            $this->Session->write('ipeerSession.myCourseList', $this->myCourseList);
            $this->Session->write('ipeerSession.myCourseIDs', $this->myCourseIDs);
        }
    }

    /**
     * Function to get the accessible functions
     * Method to get: $tmp= $result['USR'];
     *                echo $tmp['function_name'];
     *
     * @return errors
     */
    function getMyCourseList()
    {
        trigger_error('This function is deprecated. Use User::getMyCourseList() instead.', E_USER_DEPRECATED);
        $this->myCourseList = $this->Session->read('ipeerSession.myCourseList');
        if ($this->myCourseList == null) {
            $this->myCourseList = array();
        }
        return $this->myCourseList;
    }

    /**
     * getMyCourseIDs
     *
     * @access public
     * @return void
     */
    function getMyCourseIDs()
    {
        $this->myCourseIDs=$this->Session->read('ipeerSession.myCourseIDs');
        return explode(', ', $this->myCourseIDs);
    }


    /**
     * Function to get the accessible functions
     * Method to get: $tmp= $result['USR'];
     *                echo $tmp['function_name'];
     *
     * @param bool   $courseId  course id
     * @param string $userRole  user role
     * @param bool   $courseObj course object
     *
     * @return errors
     */
    function getCourseName($courseId=null, $userRole='', $courseObj=null)
    {
        $this->Course = new Course;
        $course = null;
        if ($courseObj!=null) {
            $course = $courseObj;
        } else {
            $this->myCourseList = $this->getMyCourseList();
            if (isset($this->myCourseList[$courseId])) {
                $course= $this->myCourseList[$courseId];
            } else {
                $courseLink = '&nbsp;<img alt="home" src="'. dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" valign="middle" />&nbsp;'.$this->Course->getCourseName($courseId);
                return $courseLink;
            }
        }
        //TODO: Change /ipeer2 using Session Name in SysParameter
        if (!empty($course['homepage'])) {
            $courseLink = '&nbsp;<a href="'.$course['homepage'].'"  target="_blank"><img alt="home" src="'.dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" valign="middle" /></a>&nbsp;';
        } else {
            $courseLink = '&nbsp;<img alt="home" src="'. dirname($_SERVER['PHP_SELF']).'/img/icons/home.gif" border="0" align="middle" />&nbsp;';
        }
        if ($userRole != 'S') {
            $courseLink .=  '<a href="' . preg_replace('/\/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])) . '/courses/home/'.$courseId.'" ><u>'.$course['course'].'</u></a>';
        } else {
            $courseLink .=  $course['course'];
        }

        return $courseLink;
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
        $this->Session->write('ipeerSession.courseId', $courseId);
    }

    /**
     * getCourseId
     *
     * @access public
     * @return void
     */
    function getCourseId()
    {
        return $this->Session->read('ipeerSession.courseId');
    }

    /**
     * getUserInfo
     *
     * @param int    $user_id user id
     * @param string $opt     opt
     *
     * @access public
     * @return void
     */
    function getUserInfo($user_id, $opt='fullname')
    {

        $this->User = new User;
        $user = $this->User->findUserByid($user_id);
        if ($opt == 'fullname') {
            return $user['User']['first_name'].' '.$user['User']['last_name'];
        }
    }

    // deprecated function, use event_count attribute instead
    /*function checkEvaluationToolInUse($evalTool=null, $templateId=null)
    {
        //Get the target event
        $this->Event = new Event;
        return $this->Event->checkEvaluationToolInUse($evalTool, $templateId);
    }*/
}
