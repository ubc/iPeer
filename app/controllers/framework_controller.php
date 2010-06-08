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

?>