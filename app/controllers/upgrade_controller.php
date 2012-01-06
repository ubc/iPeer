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
class UpgradeController extends AppController
{
  var $name = "Upgrade";
  var $uses         = array('SysParameter');
	var $components   = array('Output',
                            'framework',
                            'Session',
                            'Guard.Guard',
                            'DbPatcher'
                            );

  function __construct()
  {
    $this->set('title_for_layout', __('Upgrade Database', true));
    parent::__construct();
  }

  function index()
  {
    if ($this->checkPermission())
    {
      $this->set('isadmin', false); // tell view user doesn't have access
    }
    else
    {
      $this->set('isadmin', true);
    }
  }

  function step2()
  {
    if ($this->checkPermission())
    {
      $this->set('isadmin', false);
    }
    else
    {
      $this->set('isadmin', true);
      $this->set('upgradefailed', false); // tell view the upgrade worked fine
      $dbv = $this->SysParameter->getDatabaseVersion();
      $ret = $this->DbPatcher->patch($dbv);
      if ($ret)
      {
        $this->set('upgradefailed', $ret);
      }
    }
  }

  function checkPermission()
  {
    if('A' != $this->Auth->user('role'))
    {
      $this->Session->setFlash(__('Sorry, you do not have access to this page. Only administrators can perform a database upgrade. If you are an administrator, please login and then go to this page to perform the upgrade.', true));
      return true;
    }
    return false;
  }
}

?>
