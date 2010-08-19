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
uses('neat_string');
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
 		$this->pageTitle = 'Sys Parameters';
		parent::__construct();
	}

	function setUpAjaxList() {
        $columns = array(
            array("SysParameter.id",             "ID",      "", "number"),
            array("SysParameter.parameter_code", "Code",    "", "string"),
            array("SysParameter.parameter_value","Value",   "", "string"),
            array("SysParameter.parameter_type", "Type",    "", "map",
                array("I" => "Interger", "B" => "Boolean", "S" => "String")),
            array("SysParameter.record_status",  "Status",   "", "map",
                array("A" => "Active", "I" => "Inactive")),
            array("SysParameter.created",        "Created", "", "date"),
            array("SysParameter.modified",       "Updated", "", "date"));

        $warning = "Are you sure you with to delete this System Parameter?";
        $actions = array(
            array("View", "", "", "view", "SysParameter.id"),
            array("Edit", "", "", "edit", "SysParameter.id"),
            array("Delete", $warning, "", "delete", "SysParameter.id"));

        $this->AjaxList->setUp($this->SysParameter, $columns, $actions,
            "SysParameter.id", "SysParameter.title");
	}


    function index($message='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
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
		$this->set('data', $this->SysParameter->read());
	}

	function add()
	{
		if (empty($this->params['data'])) {
			$this->render();
		} else {
			if ($this->SysParameter->save($this->params['data'])) {
				$message = 'The record is saved successfully';
				$this->redirect('sysparameters/index/'.$message);
			} else {
				$this->set('data', $this->params['data']);
				$this->render('edit');
			}
		}
	}

	function edit($id=null)
	{
		if (empty($this->params['data'])) {
			$this->SysParameter->setId($id);
			$this->params['data'] = $this->SysParameter->read();
			$this->render();
		} else {
			if ( $this->SysParameter->save($this->params['data'])) {
				$message = 'The record is edited successfully';
				$this->redirect('sysparameters/index/'.$message);
			} else {
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}

  function delete($id = null)
  {
    if (isset($this->params['form']['id']))
    {
      $id = intval(substr($this->params['form']['id'], 5));
    }   //end if
    if ($this->SysParameter->del($id)) {
				$message = 'The record is deleted successfully';
				$this->redirect('sysparameters/index/'.$message);
    }
  }

}

?>