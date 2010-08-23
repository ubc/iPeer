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
 * @lastmodified $Date: 2006/09/13 18:19:23 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Simpleevaluations
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses('neat_string');
class SimpleevaluationsController extends AppController
{
  var $name = 'SimpleEvaluations';

	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
	var $uses = array('SimpleEvaluation', 'Personalize');
	var $components = array('AjaxList');

//	var $components = array('EvaluationSimpleHelper');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->NeatString = new NeatString;
		$this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Simple Evaluations';
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;

		parent::__construct();
	}


    function setUpAjaxList() {
        // Set up Columns
        $columns = array(
            array("SimpleEvaluation.id",   "ID",          "4em",        "number"),
            array("SimpleEvaluation.name", "Name",        "12em",       "action",   "View Evaluation"),
            array("SimpleEvaluation.description", "Description", "auto",  "action", "View Evaluation"),
            array("SimpleEvaluation.point_per_member", "Points/Member", "10em", "number"),
            array("Creator.id",           "",            "",     "hidden"),
            array("Creator.username",     "Creator",  "10em", "action", "View Creator"),
            array("SimpleEvaluation.created", "Creation Date", "12em", "date"));

        $userList = array($this->rdAuth->id => "My Evaluations");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator.id",
                  "localKey"   => "creator_id",
                  "description" => "Evaluations to show:",
                  "default" => $this->rdAuth->id,
                  "list" => $userList,
                  "joinTable"  => "users",
                  "joinModel"  => "Creator");
        // put all the joins together
        $joinTables = array($jointTableCreator);

        // For instructors: only list their own courses
        $extraFilters = "";

        // Set up actions
        $warning = "Are you sure you want to delete this evaluation permanently?";
        $actions = array(
            array("View Evaluation", "", "", "", "view", "SimpleEvaluation.id"),
            array("Edit Evaluation", "", "", "", "edit", "SimpleEvaluation.id"),
            array("Copy Evaluation", "", "", "", "copy", "SimpleEvaluation.id"),
            array("Delete Evaluation", $warning, "", "", "delete", "SimpleEvaluation.id"),
            array("View Creator", "",    "", "users", "view", "Creator.id"));
        $recursive = 0;

        $this->AjaxList->setUp($this->SimpleEvaluation, $columns, $actions,
            "SimpleEvaluation.id", "SimpleEvaluation.name", $joinTables, $extraFilters, $recursive);
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

	function view($id='', $layout='')
	{
  	if ($layout != '')
	  {
	    $this->layout = $layout;
	    if ($layout == 'pop_up') $this->set('popUp', 1);
	  }
	  $this->SimpleEvaluation->setId($id);
	  $data = $this->SimpleEvaluation->read();
		$this->set('data', $data);
	}

	function add($layout='')
	{
	  //List Add Page
		if (empty($this->params['data'])) {
		  if ($layout != '')
		  {
		    $this->layout = $layout;
		  }
  	}
		else {

      if (empty($this->params['data']['SimpleEvaluation']['name'])) $this->params['data']['SimpleEvaluation']['name'] = $this->params['form']['newtitle'];

      //Save Data
			if ($this->SimpleEvaluation->save($this->params['data'])) {

  			$this->redirect('/simpleevaluations/index/The simple evaluation was added successfully.');
			}
      //Found error
      else {
        $this->set('data', $this->params['data']);
        $this->render();
      }//end if
		}
	}

	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
			$this->SimpleEvaluation->setId($id);
			$this->params['data'] = $this->SimpleEvaluation->read();
			$this->Output->filter($this->params['data']);//always filter
			//converting nl2br back so it looks better
			$this->Output->br2nl($this->params['data']);
		}
		else
		{
      if (empty($this->params['data']['SimpleEvaluation']['name'])) $this->params['data']['SimpleEvaluation']['name'] = $this->params['form']['newtitle'];

			if ( $this->SimpleEvaluation->save($this->params['data']))
			{
  			$this->redirect('/simpleevaluations/index/The record is updated successfully.');
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}

	function copy($id=null)
	{
        $this->render = false;
		$this->params['data'] = $this->SimpleEvaluation->read();
        $this->params['data']['SimpleEvaluation']['id'] = null;
        $this->params['data']['SimpleEvaluation']['name'] = "";
		//converting nl2br back so it looks better
		$this->Output->br2nl($this->params['data']);
        $this->render('add');
	}

	function delete($id) {
        if (isset($this->params['form']['id'])) {
            $id = intval(substr($this->params['form']['id'], 5));
        }   //end if
        if ($this->SimpleEvaluation->del($id)) {
			$this->redirect('/simpleevaluations/index/The record is deleted successfully.');
        }
	}

  function checkDuplicateTitle()
  {
      $this->layout = 'ajax';
      $this->render('checkDuplicateTitle');
  }

  function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>
