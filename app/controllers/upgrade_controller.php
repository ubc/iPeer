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
    $message  = __("You are about to upgrade your iPeer instance. ", true);
    $message .= __("Please make sure you have backed up your database and files before proceeding!<br />", true);
    $message .= "<a href='" . $this->webroot . "upgrade/step2'>".__('Confirm', true)."</a>";
    $this->set('message_content', $message);
    $this->render(null, null, 'views/pages/message.tpl.php');
  }

  function step2()
  {
    $this->checkPermission();
    $dbv = $this->sysContainer->getParamByParamCode('database.version', array('parameter_value' => 0));

    // patch the database
    if(true !== ($ret = $this->DbPatcher->patch($dbv['parameter_value'])))
    {
        $this->set('message_content', $ret);
        $this->render(null, null, 'views/pages/message.tpl.php');
        return;
    }

    // logout the user
		$this->rdAuth->logout();
		$this->Session->delete('URL');
		$this->Session->delete('AccessErr');
		$this->Session->delete('Message');
		$this->Session->delete('CWLErr');

    $message  = __("Your iPeer instance has been upgraded. Please login again.<br />", true);
    $message .= "<a href='" . $this->webroot . "loginout/login'>".__('Login', true)."</a>";
    $this->set('message_content', $message);
    $this->render(null, null, 'views/pages/message.tpl.php');
  }



  function checkPermission()
  {
    if('A' != $this->Auth->user('role'))
    {
      $this->set('message_content', __('Sorry, you do not have access to this page. Only administrator can perform a upgrade. If you are an administrator, please login and then go to this page to perform the upgrade.', true));
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    }
  }
}
