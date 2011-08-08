<?php
/* SVN FILE: $Id: sysfunctions_controller.php 541 2011-06-06 23:24:24Z tae $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 541 $
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
App::import('Lib', 'neat_string');
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
 		$this->set('title_for_layout', 'Sys Functions');
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
            array("SysFunction.created",        "Created", "10em", "date"),
            array("SysFunction.modified",       "Updated", "10em", "date"));

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
      //  $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }


	function view($id)
	{	$this->SysFunction->id = $id;
        $data = $this->SysFunction->read();
		$this->set('data', $data);
	}

	function edit($id=null)
	{
		
		if (empty($this->data))
		{
			$this->SysFunction->id = $id;
			$this->data = $this->SysFunction->read();
			$this->set('data', $this->data);
			$this->render();
		}
		else
		{
			if ( $this->SysFunction->save($this->data))
			{
				
				$this->Session->setFlash(__('The record is edited successfully.', true));
				$this->redirect('index');
				}
			else
			{
				$this->Session->setFlash($this->SysFunction->errorMessage, true);
				$this->set('data', $this->data);
				$this->render();
			}
		}
	}

  function delete($id = null)
  {
    if ($this->SysFunction->delete($id)) {
				$this->Session->setFlash(__('The record is deleted successfully.', true));
				$this->redirect('index');
    }
  }

    function update($attributeCode='',$attributeValue='') {
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
    }
}

?>
