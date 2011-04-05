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
 * @lastmodified $Date: 2006/09/12 20:58:00 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Mixevals
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class MixevalsController extends AppController
{
	var $uses =  array('Event', 'Mixeval','MixevalsQuestion', 'MixevalsQuestionDesc', 'Personalize');
	var $name = 'Mixevals';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $Sanitize;
	var $components = array('AjaxList','rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'MixevalHelper');


  function __construct() {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->set('title_for_layout', 'Mixed Evaluations');
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
    parent::__construct();
  }

  function postProcess($data) {
    // Creates the custom in use column
    if ($data) {
      foreach ($data as $key => $entry) {
        // is it in use?
        $inUse = (0 != $entry['Mixeval']['event_count']);

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
      array("Mixeval.id",            "",              "",  "hidden"),
      array("Mixeval.name",          "Name",         "auto", "action", "View Evaluation"),
      array("!Custom.inUse",         "In Use",       "4em",  "number"),
      array("Mixeval.availability",  "Availability", "6em",  "string"),
      array("Mixeval.scale_max",     "LOM",          "3em",  "number"),
      array("Mixeval.total_question",  "Questions",    "4em", "number"),
      array("Mixeval.total_marks",  "Total Marks",    "4em", "number"),
      array("Mixeval.event_count",   "",       "",        "hidden"),
      array("Mixeval.creator_id",           "",               "",    "hidden"),
      array("Mixeval.creator",     "Creator",        "8em", "action", "View Creator"),
      array("Mixeval.created",      "Creation Date",  "10em", "date"));

    // Just list all and my evaluations for selections
    $userList = array($this->Auth->user('id') => "My Evaluations");

    // put all the joins together
    $joinTables = array();

    // List only my own or
    $myID = $this->Auth->user('id');
    $extraFilters = "(Mixeval.creator_id=$myID or Mixeval.availability='public')";

    // Instructors can only edit their own evaluations
    $restrictions = "";
    if ($this->Auth->user('role') != 'A') {
      $restrictions = array(
        "Mixeval.creator_id" => array(
          $myID => true,
          "!default" => false));
    }

    // Set up actions
    $warning = "Are you sure you want to delete this evaluation permanently?";
    $actions = array(
      array("View Evaluation", "", "", "", "view", "Mixeval.id"),
      array("Edit Evaluation", "", $restrictions, "", "edit", "Mixeval.id"),
      array("Copy Evaluation", "", "", "", "copy", "Mixeval.id"),
      array("Delete Evaluation", $warning, $restrictions, "", "delete", "Mixeval.id"),
      array("View Creator", "",    "", "users", "view", "Creator.id"));

    // No recursion in results
    $recursive = 0;

    // Set up the list itself
    $this->AjaxList->setUp($this->Mixeval, $columns, $actions,
                           "Mixeval.name", "Mixeval.name", $joinTables, $extraFilters, $recursive, "postProcess");
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

	function view($id='', $layout='')
	{
	  if ($layout != '')
		  {
		    $this->layout = $layout;
		  }
		  $this->Mixeval->id = $id;
		$this->params['data'] = $this->Mixeval->read();

    $this->Output->filter($this->params['data']);
		$this->set('data', $this->params['data']);
	}

	function add($layout='')
	{
		if ($layout != '') {
	    $this->layout = $layout;
	    $this->set('layout', $layout);
		}

		if (empty($this->params['data'])) {
		  $this->render();
		} else {
      $this->Mixeval->setId(null);
      //print_r($this->params['data']['total_marks']);
      //$this->params['data']['Mixeval']['total_marks'] = isset($this->params['data']['Mixeval']['total_marks'])?$this->params['data']['total_marks']:'';
			//check to see if user has clicked next
			if(!empty($this->params['form']['next'])){
			  //print_r($this->params['data']);
			  //$this->set('total_mark',1);
				$this->set('data', $this->params['data']);
				$this->render('add');
			} else {
        //adding a new rubric
			  $data = $this->params['data'];
//			  $data['Mixeval']['total_question'] = $data['Mixeval']['total_question'] -1 ;
			  if ($this->Mixeval->save($data))
			  {
  				//prepare the data from the form fields in array
  				$mixEvaluation = $this->Mixeval->prepData($this->params, $this->rdAuth->id);

  				//insert all the rubric data into other associated tables
  				$this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $mixEvaluation);
  				$this->MixevalsQuestionDesc->insertQuestionDescriptor($this->Mixeval->id, $mixEvaluation);

  				$this->redirect('/mixevals/index/The mixed evaluation was added successfully.');
  			} else{
          //updating a current rubric
            $this->set('data', $this->params['data']);
            $this->set('errmsg', $this->Mixeval->errorMessage);
            $this->render('add');
        }
      }
    }
	}

  function deleteQuestion($question_id) {
    $this->layout = false;
    $this->MixevalsQuestion->deleteAll(array('id' => $question_id), true);
  }

	function edit($id) {
		if (empty($this->data)) {
      $this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                                                        'contain' => array('Question.Description',
                                                                          )));
		} else {
			//check to see if user has clicked preview
			if(empty($this->params['form']['preview'])) {
        if($this->__processForm()) {
          $this->Session->setFlash(__('The mixed evaluation was updated successfully'));
          $this->redirect('index');
        }
      }
      
      // generate empty questions
      $count_text = 0;
      $count_lickert = 0;
      for($i = 0; $i < $this->data['Mixeval']['lickert_question_max']+ $this->data['Mixeval']['prefill_question_max']; $i++) {
        if(!isset($this->data['Question'][$i])) {
          $this->data['Question'][$i] = array('question_type' => ($count_lickert < $this->data['Mixeval']['lickert_question_max']) ? 'S' : 'T',
                                              'Description' => range(0, $this->data['Mixeval']['scale_max']));
        } 

        if($this->data['Question'][$i]['question_type'] == 'S') {
          $count_lickert++;
        } else {
          $count_text++;
        }
      }
    }
    $this->set('data', $this->data);
    $this->set('action', 'Edit Mixed Evaluation');
    $this->render('edit');
	}

  function __processForm() {
    if (!empty($this->data)) {
      $this->Output->filter($this->data);//always filter

      //Save Data
      if ($this->Mixeval->saveAllWithDescription($this->data)) {
        $this->data['Mixeval']['id'] = $this->Mixeval->id;
        return true;
      } else {
        $this->Session->setFlash($this->Mixeval->errorMessage, 'error');
      }
    }
    return false;
  }

  function copy($id=null)
  {
    $this->render = false;
		$this->Mixeval->setId($id);
		$data = $this->Mixeval->read();
		$data['Mixeval']['name'] = ""; // Clear the name when evaluation copied
		$this->set('data', $data);
		$this->render('edit');
  }

	function delete($id)
	{
        // Deny Deleting evaluations in use:
        $inUse = $this->Event->checkEvaluationToolInUse('4',$id);

        if ($inUse) {
            $this->index();
            $message = "<span style='color:red'>";
            $message.= "This evaluation is now in use, and can NOT be deleted.<br />";
            $message.= "Please remove all the events assosiated with this evaluation first.";
            $message.= "</span>";
            $this->set('message', $message);
            $this->render('index');
            exit;
        }

		if ($this->Mixeval->del($id))
		{
			$this->MixevalsQuestion->deleteQuestions($id);
			$this->MixevalsQuestionDesc->deleteQuestionDescriptors($id);
			$this->redirect('/mixevals/index/The mixed evaluation was deleted successfully.');
		}
	}

  function previewMixeval()
  {
    //print_r(array_values($this->params));

    $this->layout = 'ajax';
    $this->render('preview');
  }

	function renderRows($row=null, $criteria_weight=null )
	{
		$this->layout = 'ajax';
		$this->render('row');
	}

	function printUserName($user_id)
	{
		$tmp = $this->Mixeval->query("SELECT username FROM users WHERE id=$user_id");
		echo $tmp[0]['users']['username'];
	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>
