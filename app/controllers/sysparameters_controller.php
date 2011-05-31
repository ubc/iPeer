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
 * @lastmodified $Date: 2006/08/22 17:31:26 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Sysparameters
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
App::import('Lib', 'neat_string');

class SysParametersController extends AppController
{
  var $name = 'SysParameters';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
	var $uses = array('SysParameter','Personalize');
	var $components = array('AjaxList');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->NeatString = new NeatString;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->set('title_for_layout', 'Sys Parameters');
		parent::__construct();
	}

	function setUpAjaxList() {
        $columns = array(
            array("SysParameter.id",             "ID",      "3em", "number"),
            array("SysParameter.parameter_code", "Code",    "15em", "string"),
            array("SysParameter.parameter_value","Value",   "auto","string"),
            array("SysParameter.parameter_type", "Type",    "6em",   "map",
                array("I" => "Interger", "B" => "Boolean", "S" => "String")),
            array("SysParameter.record_status",  "Status",   "5em", "map",
                array("A" => "Active", "I" => "Inactive")),
            array("SysParameter.created",        "Created", "10em", "date"),
            array("SysParameter.modified",       "Updated", "10em", "date"));

        $warning = "Are you sure you wish to delete this System Parameter?";

        $actions = array(
            array("View", "", "", "", "view", "SysParameter.id"),
            array("Edit", "", "", "", "edit", "SysParameter.id"),
            array("Delete", $warning, "", "", "delete", "SysParameter.id"));

        $this->AjaxList->setUp($this->SysParameter, $columns, $actions,
            "SysParameter.id", "SysParameter.parameter_code");
	}


    function index($message='') {
        // Make sure the present user is not a student
     //   $this->rdAuth->noStudentsAllowed();
        // Set the top message
        $this->set('message', $message);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    function ajaxList() {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

	function view($id)
	{
			$this->SysParameter->id = $id;
			$this->set('data', $this->SysParameter->read());
	}

	function add()
	{
		if (empty($this->data)) {
			$this->render();
		} else {
			if ($this->SysParameter->save($this->params['data'])) {
				$message = 'The record is saved successfully';
				$this->redirect('sysparameters/index/'.$message);
			} else {
				$this->set('data', $this->data);
				$this->render('edit');
			}
		}
	}

	function edit($id=null)
	{
		if (empty($this->data)) {
			$this->SysParameter->id = $id;
			$this->data = $this->SysParameter->read();
			$this->set('data', $this->data);
			$this->render();
		} else {
			if ( $this->SysParameter->save($this->data)) {
				$this->Session->setFlash(__('The record is edited successfully.', true));
				$this->redirect('index');
			} else {
					$this->Session->setFlash($this->SysParameter->errorMessage, true);
				$this->set('data', $this->data);
				$this->render();
			}
		}
	}

  function delete($id = null)
  {
   if ($this->SysParameter->delete($id)) {
				$this->Session->setFlash(__('The record is deleted successfully.', true));
				$this->redirect('index');
    }
  }

}

?>
