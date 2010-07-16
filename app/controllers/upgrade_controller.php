<?php
/* SVN FILE: $Id$ */

/**
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Upgrade
 *
 * @package
 * @subpackage
 * @since
 */
class UpgradeController extends Controller
{
	var $Sanitize;
  var $uses         = array(); 
	var $components   = array('Output', 
                            'framework',
                            'Session',
                            'rdAuth',
                            'DbPatcher'
                            );
	var $beforeFilter =	 array('preExecute');

  function preExecute()
  {
 		$this->rdAuth->loadFromSession();
    $this->set('rdAuth',$this->rdAuth);
  }

  function index()
  {
    $this->checkPermission();
    $this->set('message_content', 'You are about to upgrade your iPeer instance. Please make sure you have backed up your database and files before proceeding!<br /><a href="/upgrade/step2">Confirm</a>');
    $this->render(null, null, 'views/pages/message.tpl.php');
  }

  function step2()
  {
    $this->checkPermission();
    $dbv = $this->sysContainer->getParamByParamCode('database.version', array('parameter_value' => 0));

    // patch the database
    if(true !== ($ret = $this->dbPatchHelper->patch($dbv['parameter_value'])))
    {
        $this->set('message_content', $ret);
        $this->render(null, null, 'views/pages/message.tpl.php');
    }
   
    // logout the user
		$this->rdAuth->logout();
		$this->Session->del('URL');
		$this->Session->del('AccessErr');
		$this->Session->del('Message');
		$this->Session->del('CWLErr');

    $this->set('message_content', 'Your iPeer instance has been upgraded. Please login again.<br /><a href="/loginout/login">Login</a>');
    $this->render(null, null, 'views/pages/message.tpl.php');
  }



  function checkPermission()
  {
    if('A' != $this->rdAuth->role)
    {
      $this->set('message_content', 'Sorry, you do not have access to this page. Only administrator can perform a upgrade. If you are an administrator, please login and then go to this page to perform the upgrade.');
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    }
  }
}
