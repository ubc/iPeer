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
 		$this->set('title_for_layout', 'Rubrics');
    parent::__construct();
	}
	
  function postProcess($data) {
    // Creates the custom in use column
    if ($data) {
      foreach ($data as $key => $entry) {
        // is it in use?
        $inUse = (0 != $entry['Rubric']['event_count']);

        // Put in the custom column
        $data[$key]['!Custom']['inUse'] = $inUse ? "Yes" : "No";
      }
    }
    // Return the processed data back
    return $data;
  }

  function getRubricById($id){
  	$sql="SELECT *
  		  FROM rubrics
  		  WHERE id=$id";
  	return $this->query($sql);
  }

  function setUpAjaxList() {
    // Set up Columns
    $columns = array(
            array("Rubric.id",          "",            "",      "hidden"),
            array("Rubric.name",        "Name",        "auto",  "action", "View Rubric"),
            array("!Custom.inUse",      "In Use",      "4em",   "number"),
            array("Rubric.availability","Availability","6em",   "string"),
            array("Rubric.lom_max",     "LOM",         "4em",   "number"),
            array("Rubric.criteria",    "Criteria",    "4em",   "number"),
            array("Rubric.total_marks", "Total",       "4em",   "number"),
            array("Rubric.event_count",   "",       "",        "hidden"),
            array("Rubric.creator_id",         "",            "",      "hidden"),
            array("Rubric.creator",   "Creator",     "8em",   "action", "View Creator"),
            array("Rubric.created",     "Creation Date","10em", "date"));

    // Just list all and my evaluations for selections
    $userList = array($this->Auth->user('id') => "My Evaluations");

    // put all the joins together
    $joinTables = array();

    // List only my own or
    $myID = $this->Auth->user('id');
    $extraFilters = "(Rubric.creator_id=$myID or Rubric.availability='public')";

    // Instructors can only edit their own evaluations
    $restrictions = "";
    if ($this->Auth->user('role') != 'A') {
      $restrictions = array("Rubric.creator_id" => array(
        $myID => true,
        "!default" => false));
    }

    // Set up actions
    $warning = "Are you sure you want to delete this Rubric permanently?";
    $actions = array(
            array("View Rubric", "", "", "", "view", "Rubric.id"),
            array("Edit Rubric", "", $restrictions, "", "edit", "Rubric.id"),
            array("Copy Rubric", "", "", "", "copy", "Rubric.id"),
            array("Delete Rubric", $warning, $restrictions, "", "delete", "Rubric.id"),
            array("View Creator", "",    "", "users", "view", "Creator.id"));

    // No recursion in results
    $recursive = 0;

    // Set up the list itself
    $this->AjaxList->setUp($this->Rubric, $columns, $actions,
                           "Rubric.name", "Rubric.name", $joinTables, $extraFilters, $recursive, "postProcess");
  }


  function index() {
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }


  function view($id, $layout='') {
    if ($layout != '') {
      $this->layout = $layout;
    }

    $this->data = $this->Rubric->find('first', array('conditions' => array('id' => $id),
                                                     'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                                        'RubricsLom')));
    $this->set('data', $this->data);
    $this->set('readonly', true);
    $this->set('evaluate', false);
    $this->set('action', 'View Rubric');
    $this->render('edit');
  }
  
  function add($layout='') {
    if ($layout != '') {
      $this->layout = $layout;
    }

    if(!empty($this->data)) {
      $this->set('action', 'Add Rubric (Step 2)');
      $this->set('data', $this->data);

      if(isset($this->params['form']['submit'])) {
        if($this->__processForm()) {
          $this->Session->setFlash(__('The rubric was added successfully.', true));
          $this->redirect('index');
        } 
      }
    } else {
      $this->set('action', 'Add Rubric');
    }
    $this->render('edit');
/*
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
      }*/
  }

	function edit($id) {
		if (empty($this->data)) {
      $this->data = $this->Rubric->find('first', array('conditions' => array('id' => $id),
                                                       'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                                          'RubricsLom')));
      $this->set('data', $this->data);
		} else {
			//check to see if user has clicked preview
			if(!empty($this->params['form']['preview'])) {
				$this->set('data', $this->data);
			} else {
        if($this->__processForm()) {
          $this->Session->setFlash(__('The rubric evaluation was updated successfully'));
          $this->redirect('index');
        }
      }
    }
    $this->set('action', 'Edit Rubric');
    $this->render('edit');
	}

  function __processForm() {
    if (!empty($this->data)) {
      $this->Output->filter($this->data);//always filter
      //Save Data
      
      //$this->log($this->data);
      if ($this->Rubric->saveAllWithCriteriaComment($this->data)) {
        $this->data['Rubric']['id'] = $this->Rubric->id;
        return true;
      } else {
        $this->Session->setFlash($this->Rubric->errorMessage, 'error');
      }
    }
    return false;
  }

	function copy($id) {
    $this->data = $this->Rubric->copy($id);
    $this->set('data', $this->data);
    $this->set('action', 'Copy Rubric');
		$this->render('edit');
	}

	function delete($id) {
    // Deny Deleting evaluations in use:
    if ($this->Rubric->getEventCount($id)) {
      $this->Session->setFlash('This evaluation is in use. Please remove all the events assosiated with this evaluation first.', 
                               'error');
    } else {
      if ($this->Rubric->delete($id, true)) {
        /*$this->RubricsLom->deleteLOM($id);
          $this->RubricsCriteria->deleteCriterias($id);
          $this->RubricsCriteriaComment->deleteCriteriaComments($id);
        //$this->set('data', $this->Rubric->find('all',null, null, 'id'));
        $this->index();*/
        $this->Session->setFlash(__('The rubric was deleted successfully.', true));
      }
    }
    $this->redirect('index');
	}
	
	function test(){
		$this->log("Test Success");
	}
	
	function setForm_RubricName($name){
		$this->data['Rubric']['name'] = $name;
		$this->log($this->data['Rubric']['name']);
	}

/*  function previewRubric()
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
	}*/
}

?>
