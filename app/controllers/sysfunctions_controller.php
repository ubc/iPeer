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
 * Controller :: Sysfunctions
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses('neat_string');
class SysFunctionsController extends AppController
{
  var $name = 'SysFunctions';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
    var $uses = array('SysFunction','Personalize');
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
 		$this->pageTitle = 'Sys Functions';
		parent::__construct();
	}


    function setUpAjaxList() {
        $columns = array(
            array("SysFunction.id",              "ID",         "3em", "number"),
            array("SysFunction.function_code",   "Code",       "15em","string"),
            array("SysFunction.function_name",   "Name",       "auto","string"),
            array("SysFunction.controller_name", "Controller", "6em", "string"),
            array("SysFunction.url_link",        "URL Link",   "5em", "string"),
            array("SysFunction.permission_type", "Permissions","5em", "string"),
            array("SysFunction.record_status",  "Status",   "5em", "map",
                array("A" => "Active", "I" => "Inactive")),
            array("SysFunction.created",        "Created", "7em", "date"),
            array("SysFunction.modified",       "Updated", "7em", "date"));

        $warning = "Are you sure you wish to delete this System Function?";
        $actions = array(
            array("View", "", "", "", "view", "SysFunction.id"),
            array("Edit", "", "", "", "edit", "SysFunction.id"),
            array("Delete", $warning, "", "", "delete", "SysFunction.id"));

        $this->AjaxList->setUp($this->SysFunction, $columns, $actions,
            "SysFunction.id", "SysFunction.function_code");
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
        $data = $this->SysFunction->read();
		$this->set('data', $data);
	}

	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
			$this->SysFunction->setId($id);
			$this->params['data'] = $this->SysFunction->read();
			$this->render();
		}
		else
		{
			if ( $this->SysFunction->save($this->params['data']))
			{
				$message = 'The record is edited successfully';
				$this->redirect('sysfunctions/index/'.$message);
			}
			else
			{
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
    if ($this->SysFunction->del($id)) {
				$message = 'The record is deleted successfully';
				$this->redirect('sysfunctions/index/'.$message);
    }
  }

    function update($attributeCode='',$attributeValue='') {
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
    }
}

?>