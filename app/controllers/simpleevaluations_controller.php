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
App::import('Lib', 'neat_string');

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
	var $uses = array('SimpleEvaluation', 'Event', 'Personalize');
	var $components = array('AjaxList');

//	var $components = array('EvaluationSimpleHelper');

	function __construct() {
		$this->Sanitize = new Sanitize;
		$this->NeatString = new NeatString;
		$this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->set('title_for_layout', 'Simple Evaluations');
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;

		parent::__construct();
	}

  function postProcess($data) {

    // Creates the custom in use column
    if ($data) {
      foreach ($data as $key => $entry) {
        // is it in use?
        $inUse = (0 != $entry['SimpleEvaluation']['event_count']);

        // Put in the custom column
        $data[$key]['!Custom']['inUse'] = $inUse ? "Yes" : "No";
      }
    }
    // Return the processed data back
    return $data;
  }

  function setUpAjaxList() {
    $myID = $this->Auth->user('id');

    // Set up Columns
    $columns = array(
            array("SimpleEvaluation.id",   "",       "",        "hidden"),
            array("SimpleEvaluation.event_count",   "",       "",        "hidden"),
            array("SimpleEvaluation.name", "Name",   "12em",    "action",   "View Evaluation"),
            array("SimpleEvaluation.description", "Description","auto",  "action", "View Evaluation"),
            array("!Custom.inUse", "In Use",          "4em",    "number"),
            array("SimpleEvaluation.point_per_member", "Points/Member", "10em", "number"),
            array("SimpleEvaluation.creator_id",           "",            "",     "hidden"),
            array("SimpleEvaluation.creator",     "Creator",  "10em", "action", "View Creator"),
            array("SimpleEvaluation.created", "Creation Date", "10em", "date"));

    $userList = array($myID => "My Evaluations");

    // Join with Users
    $jointTableCreator =
      array("id"         => "Creator.id",
            "localKey"   => "creator_id",
            "description" => "Evaluations to show:",
            "default" => $myID,
            "list" => $userList,
            "joinTable"  => "users",
            "joinModel"  => "Creator");
    // put all the joins together
    $joinTables = array();

    $extraFilters = "";

    // Instructors can only edit their own evaluations
    $restrictions = "";
    if ($this->Auth->user('role') != 'A') {
      $restrictions = array(
                            "SimpleEvaluation.creator_id" => array(
                                                  $myID => true,
                                                  "!default" => false));
    }

    // Set up actions
    $warning = "Are you sure you want to delete this evaluation permanently?";
    $actions = array(
                     array("View Evaluation", "", "", "", "view", "SimpleEvaluation.id"),
                     array("Edit Evaluation", "", $restrictions, "", "edit", "SimpleEvaluation.id"),
                     array("Copy Evaluation", "", "", "", "copy", "SimpleEvaluation.id"),
                     array("Delete Evaluation", $warning, $restrictions, "", "delete", "SimpleEvaluation.id"),
                     array("View Creator", "",    "", "users", "view", "SimpleEvaluation.creator_id"));

    // No recursion in results
    $recursive = 0;

    // Set up the list itself
    $this->AjaxList->setUp($this->SimpleEvaluation, $columns, $actions,
                           "SimpleEvaluation.name", "SimpleEvaluation.name", $joinTables, $extraFilters, $recursive, "postProcess");
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
	    if ($layout == 'pop_up') $this->set('popUp', 1);
	  }
	  $data = $this->SimpleEvaluation->read(null, $id);
		$this->set('data', $data);
	}

	function add($layout='') {
    if ($layout != '') {
      $this->layout = $layout;
    }

    if(!empty($this->data)) {
      if($this->__processForm()) {
        $this->Session->setFlash("The evaluation was added successfully.");
        $this->redirect('index');
      } else {
        $this->Session->setFlash($this->SimpleEvaluation->errorMessage);
        $this->set('data', $this->data);
      }
    }
    $this->render('edit');
	}

  function __processForm() {
    if (!empty($this->data)) {
      $this->Output->filter($this->data);//always filter

      //Save Data
      if ($this->SimpleEvaluation->save($this->data)) {
        $this->data['SimpleEvaluation']['id'] = $this->SimpleEvaluation->id;
        return true;
      } 
    }

    return false;
  }

	function edit($id) {
    if(!is_numeric($id)) {
      $this->Session->setFlash('Invalid ID.');
      $this->redirect('index');
    }

    $this->data['SimpleEvaluation']['id'] = $id;

		if ($this->__processForm()) {
      $this->Session->setFlash('The simple evaluation was updated successfully.');
      $this->redirect('index');
    } else {
			$this->data = $this->SimpleEvaluation->find('first', array('conditions' => array('id' => $id),
                                                                 'contain' => false));

			$this->Output->filter($this->data);//always filter
			//converting nl2br back so it looks better
			$this->Output->br2nl($this->data);
		} 
	}

  function copy($id) {
    $this->render = false;
    $this->data = $this->SimpleEvaluation->read(null, $id);
    $this->data['SimpleEvaluation']['id'] = null;
    $this->data['SimpleEvaluation']['name'] = 'Copy of '.$this->data['SimpleEvaluation']['name'];
    //converting nl2br back so it looks better
    $this->Output->br2nl($this->data);
    $this->render('edit');
	}

	function delete($id) {
    // Deny Deleting evaluations in use:
    if ($this->SimpleEvaluation->getEventCount($id)) {
      $message = "This evaluation is now in use, and can NOT be deleted.<br />";
      $message.= "Please remove all the events assosiated with this evaluation first.";
      $this->Session->setFlash($message);
      $this->redirect('index');
    }

    /*if (isset($this->params['form']['id'])) {
      $id = intval(substr($this->params['form']['id'], 5));
    }   //end if*/

    if ($this->SimpleEvaluation->delete($id)) {
			$this->Session->setFlash('The evaluation was deleted successfully.');
		} else {
      $this->Session->setFlash('Evaluation delete failed.');
		}
    $this->redirect('index');
	}

  /*function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
	}*/
}

?>
