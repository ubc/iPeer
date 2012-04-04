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
 		$this->pageTitle = 'Framework';
		parent::__construct();
	}

	function calendarDisplay($datetime = '', $id='') {
		$this->autoRender = false;
        $this->layout = false;
        $redirect = "calendar";
        $this->render($redirect);
	}

	function userInfoDisplay($id='') {
        // Make sure the present user is not a student
        if ($this->rdAuth->getPrivilegeLevel() <= $this->rdAuth->studentPrivilegeLevel()) {
           $this->rdAuth->privilegeError();
        }

        if (!is_numeric($id)) {
            $this->rdAuth->privilegeError();
        }
            // Make sure that the privileges of the asking user is at least as high
            //  as the privileges of the user being viewed.
        if ($this->rdAuth->getPrivilegeLevel() < $this->rdAuth->getPrivilegeLevel($id)) {
            $this->rdAuth->privilegeError();
        }

		$this->autoRender = false;
        $this->layout = 'pop_up';
        $this->set('userId', $id);
        $this->render("userinfo");
	}

	function tutIndex($tut=null) {
        $this->layout = 'tutorial_pop_up';
        $this->set('tut',$tut);
	}
}

