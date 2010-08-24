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
 * @lastmodified $Date: 2006/08/04 18:04:40 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Rubrics
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class RubricsController extends AppController
{
	var $uses =  array('Event', 'Rubric','RubricsLom','RubricsCriteria','RubricsCriteriaComment','Personalize');
  var $name = 'Rubrics';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $Sanitize;
    var $components = array('AjaxList','rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'RubricHelper');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
 		$this->pageTitle = 'Rubrics';
    parent::__construct();
	}


    function postProcess($data) {

        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                // is it in use?
                $inUse = $this->Event->checkEvaluationToolInUse('4',$entry['Rubric']['id']) ;

                // Put in the custom column
                $data[$key]['!Custom']['inUse'] = $inUse ? "Yes" : "No";
            }
        }
        // Return the processed data back
        return $data;
    }


    function setUpAjaxList() {
        // Set up Columns
        $columns = array(
            array("Rubric.id",          "ID",          "4em",   "number"),
            array("Rubric.name",        "Name",        "auto",  "action",
                "View Evaluation"),
            array("!Custom.inUse",      "In Use",      "4em",   "number"),
            array("Rubric.availability","Availability","6em",   "string"),
            array("Rubric.criteria",    "Criteria",    "4em",   "number"),
            array("Rubric.total_marks", "Total",       "4em",   "number"),
            array("Creator.id",         "",            "",      "hidden"),
            array("Creator.username",   "Creator",     "8em",   "action",
                "View Creator"),
            array("Rubric.created",     "Creation Date","12em", "date"));

        // Just list all and my evaluations for selections
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

        // List only my own or
        $myID = $this->rdAuth->id;
        $extraFilters = "(Creator.id=$myID or Rubric.availability='public')";

        // Instructors can only edit their own evaluations
        $restrictions = "";
        if ($this->rdAuth->role != 'A') {
            $restrictions = array(
                "Creator.id" => array(
                    $myID => true,
                    "!default" => false));
        }

        // Set up actions
        $warning = "Are you sure you want to delete this evaluation permanently?";
        $actions = array(
            array("View Evaluation", "", "", "", "view", "Rubric.id"),
            array("Edit Evaluation", "", $restrictions, "", "edit", "Rubric.id"),
            array("Copy Evaluation", "", "", "", "copy", "Rubric.id"),
            array("Delete Evaluation", $warning, $restrictions, "", "delete", "Rubric.id"),
            array("View Creator", "",    "", "users", "view", "Creator.id"));

        // No recursion in results
        $recursive = 0;

        // Set up the list itself
        $this->AjaxList->setUp($this->Rubric, $columns, $actions,
            "Rubric.id", "Rubric.name", $joinTables, $extraFilters, $recursive, "postProcess");
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
		  }
		$this->set('data', $this->Rubric->read());
	}

    function add($layout='')
    {
        if (empty($this->params['data'])) {
            if ($layout != '') {
                $this->layout = $layout;
            }
            $this->render();
        } else {
            //check to see if user has clicked preview
            if(!empty($this->params['form']['preview'])){
                $this->set('data', $this->params['data']);
                $this->set('preview', 1);
                $this->render('add');
            } else {
                $this->params['data']['Rubric']['total_marks'] = $this->params['form']['total_marks'];

                if ($this->Rubric->save($this->params['data'])) {
                    //prepare the data from the form fields in array
                    $this->params['data']['Rubric'] = $this->Rubric->prepData($this->params, $this->rdAuth->id);

                    //insert all the rubric data into other associated tables
                    $this->RubricsLom->insertLOM($this->Rubric->id, $this->params['data']['Rubric']);
                    $this->RubricsCriteria->insertCriteria($this->Rubric->id, $this->params['data']['Rubric']);
                    $this->RubricsCriteriaComment->insertCriteriaComm($this->Rubric->id, $this->params['data']['Rubric']);

                    $this->Session->setFlash('The rubric was added successfully.');
                    $this->redirect('/rubrics/index');
                } else {
                    $this->set('data', $this->params['data']);
                    $this->set('errmsg', $this->Rubric->errorMessage);
                    $this->render('add');
                }
            }
        }
    }

	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
            $data = $this->Rubric->find('id = '.$id);
			$this->params['data'] = $data;
            $this->set('data', $this->params['data']);
            $this->render('edit');
		} else {
			//check to see if user has clicked preview
			if(!empty($this->params['form']['preview'])) {
				$this->set('data', $this->params['data']);
				$this->render('edit');
			} else {
                if ( $this->Rubric->save($this->params['data']))  {
                    //prepare the data from the form fields in array
                    $this->params['data']['Rubric'] = $this->Rubric->prepData($this->params, $this->rdAuth->id);

                    //insert all the rubric data into other associated tables
                    $this->RubricsLom->updateLOM($this->params['data']['Rubric']);
                    $this->RubricsCriteria->updateCriteria($this->params['data']['Rubric']);
                    $this->RubricsCriteriaComment->updateCriteriaComm($this->params['data']['Rubric']);
                    $this->Rubric->save($this->params['data']);

                    $this->redirect('/rubrics/index/The rubric was updated successfully.');
                } else {
                    $this->set('data', $this->params['data']);
                    $this->set('errmsg', $this->Rubric->errorMessage);
                    $this->render('edit');
                }
            }
        }
	}

	function copy($id=null)
	{
        $this->render = false;
		$this->Rubric->setId($id);
		$this->params['data'] = $this->Rubric->read();
        //$this->params['data']['Rubric']['id'] = null;
        $this->params['data']['Rubric']['name'] = "";
        $this->set('preview', 1);
        $this->set('copy', 1);
		$this->render('edit');
	}

	function delete($id)
	{
		if ($this->Rubric->del($id))        // No recursion in results
        $recursive = 0;

        // Set up the list itself
		{
			$this->RubricsLom->deleteLOM($id);
			$this->RubricsCriteria->deleteCriterias($id);
			$this->RubricsCriteriaComment->deleteCriteriaComments($id);
			//$this->set('data', $this->Rubric->findAll(null, null, 'id'));
			$this->index();
			$this->set('message', 'The rubric was deleted successfully.');
			$this->render('index');
		}
	}

  function previewRubric()
  {
    $this->layout = 'ajax';
		$this->render('preview');
  }

	function renderRows($row=null, $criteria_weight=null )
	{
		$this->layout = 'ajax';
		$this->render('row');
	}


	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>
