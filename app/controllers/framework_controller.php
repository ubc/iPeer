<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/10 23:39:17 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Users
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class FrameworkController extends AppController
{
/**
 * This controller does not use a model
 *
 * @var $uses
 */
  var $uses =  array('User','SysFunction', 'SysParameter');
	var $Sanitize;
	var $components = array('rdAuth','Output','sysContainer','userPersonalize', 'framework');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
 		$this->set('title_for_layout', __('Framework', true));
		parent::__construct();
	}

	function calendarDisplay($datetime = '', $id='') {
		$this->autoRender = false;
        $this->layout = false;
        $redirect = "calendar";
        $this->render($redirect);
	}

	function userInfoDisplay($id) {
    $this->AccessControl->check('functions/user', 'read');

    if (!is_numeric($id) || !($this->data = $this->User->findUserByid($id))) {
      $this->Session->setFlash(__('Invalid user ID.', true));
      $this->redirect('index');
    }

    $roles = $this->User->getRoles($id);
    if(!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('ViewUser', $roles)) {
      $this->Session->setFlash(__('You do not have permission to view this user.', true));
      $this->redirect('index');
    }

    $this->autoRender = false;
    $this->layout = 'pop_up';
    $this->set('data', $this->data);
    $this->render("userinfo");
  }

	function tutIndex($tut=null) {
        $this->layout = 'tutorial_pop_up';
        $this->set('tut',$tut);
	}
}

?>
