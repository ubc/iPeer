<?php
/*
 * Custom AppController for iPeer
 *
 * @author
 * @version     0.10.6.1865
 * @license		OPPL
 *
 */
uses('sanitize');

class AppController extends Controller  {
	var $startpage = 'pages';
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework');
	var $beforeFilter =	 array('checkAccess');
	var $access = array ();
	var $actionList = array ();

	function __setAccess()
	{
		$access = $this->sysContainer->getAccessFunctionList();
		if (!empty($access)){
			$this->set('access', $access);
		}
	}

	function __setActions()
	{
		$actionList = $this->sysContainer->getActionList();
		if (!empty($actionList)){
			$this->set('action', $actionList);
		}
	}

	function __setCourses()
	{
		$coursesList = $this->sysContainer->getMyCourseList();

		if (!empty($coursesList)){
			$this->set('coursesList', $coursesList);
		}
	}

	function extractModel($model,$array,$field)
	{
		$return=array();
		foreach ($array as $row)
		array_push($return,$row[$model][$field]);
		return $return;
	}

	function checkAccess()
	{
		$this->rdAuth->loadFromSession();

        // Used when error messasges are displayed, just let cake display them.
        // No controller -> no security risk.
        if (empty($this->params)) {
            return true;
        }

		//set access function list
		$this->__setAccess();
		$this->__setActions();
		$this->__setCourses();

		// Enable for debug output
		//$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>";

		//Save the current URL in session
		$pass = (!empty($this->params['pass'])) ? join('/',$this->params['pass']) : null;

        $URL = $this->params['controller'].'/'.$this->params['action'].'/'.$pass;
		//$a=print_r($this->rdAuth,true);echo "<pre>$a</pre>";
		//check if user not logined
		if ($this->params['controller']=='' || $this->params['controller']=="loginout" || $this->params['controller']=="install")
		{
			$this->set('rdAuth',$this->rdAuth);

		} else {
			//Check whether the user is current login yet
			if (!isset($this->rdAuth->role)){
				$this->Session->write('URL', $URL);
				$this->Session->write('AccessErr', 'NO_LOGIN');
				$redirect = 'loginout/login';
				$this->redirect($redirect);
				exit;
			}

			//check permission
			$functions = $this->sysContainer->getActionList();

			$url = $this->params['url']['url'];
            // Cut a trailing shash in url if it exists
            if ($url[strlen($url) - 1] == "/") {
                $url = substr($url, 0, (-1) );
            }

			$allowedExplicitly = false;
			$allowedByEntry = "";
            // First, check that this URL has been explicitly specified in Sys functions table.
            foreach ($functions as $func) {
                if ($func['url_link'] === $url) {
                    $allowedExplicitly = true;
                    $allowedByEntry = $url;
                    break;
                }
            }

            if ($allowedExplicitly) echo "<b style='color:green'>Allowed Explicitly by $allowedByEntry</b>";

			if (!$this->rdAuth->check($this->params['controller'], $this->sysContainer->getActionList()))
			{
				$this->Session->write('URL', $URL);
				$this->Session->write('AccessErr', 'NO_PERMISSION');
				$redirect = 'loginout/login';
				$this->redirect($redirect);
				exit;
			}

			//render the authorized controller
			$this->set('rdAuth',$this->rdAuth);
			$this->set('sysContainer', $this->sysContainer);
			$this->set('Output', $this->Output);

			$this->Session->del('URL');
		}
		return true;
	}


}
?>
