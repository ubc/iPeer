<?php
/* SVN FILE: $Id: mixevals_controller.php 627 2011-07-04 21:56:00Z tae $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 627 $
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
	var $components = array('AjaxList','Auth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework');


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
          $myID = $this->Auth->user('id');
          
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
          $joinTables = array($jointTableCreator);

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
		$Auth = $this->Auth;
			
		$this->set('Auth', $Auth);
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

		if (empty($this->data)) {
			$this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                                                        'contain' => array('Question.Description',
			)));
				
		}

		$this->set('data', $this->data);
		$Auth = $this->Auth;
			
		$this->set('Auth', $Auth);
		$this->Mixeval->id = $id;
		$this->params['data'] = $this->Mixeval->read();

		$this->Output->filter($this->params['data']);
		$prepare_data = $this->Mixeval->compileViewData($this->params['data']);
		$this->set('prepare', $prepare_data);
	}

	function add($layout='')
	{
		if ($layout != '') {
			$this->layout = $layout;
			$this->set('layout', $layout);
		}

		if (empty($this->data)) {
			$this->data['Mixeval']= array();
			$this->data['Question'] = array();
			$this->set('data', $this->data);

		} else{

			$data = $this->data;

			if ($this->Mixeval->save($data)){
				$this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $this->data['Question']);
				$id = $this->Mixeval->id;
				$question_ids= $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> $id), 'fields' => array('MixevalsQuestion.id, question_num')));
				$this->MixevalsQuestionDesc->insertQuestionDescriptor($this->data['Question'], $question_ids);
				$this->Session->setFlash(__('The Mixed Evaluation was added successfully.', true));
				$this->redirect('index');

			}else{
				$this->set('data', $this->data);
				$this->set('errmsg', $this->Mixeval->errorMessage);
			}
		}
		$this->set('data', $this->data);
		$this->set('action', 'Add Mixed Evaluation');
		$this->render('edit');
	}

	function deleteQuestion($question_id) {
		$this->autoRender = false;
		$this->MixevalsQuestion->deleteAll(array('id' => $question_id), true);
	}

	function deleteDescriptor($descriptor_id) {
		$this->autoRender = false;
		$this->MixevalsQuestionDesc->delete(array('id' => $descriptor_id));
	}

	function edit($id) {
		if (empty($this->data)) {
			$this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                                                        'contain' => array('Question.Description',
			)));

		} else{


			$data = $this->data;

			if ($this->Mixeval->save($data)){
				$this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $this->data['Question']);
				$id = $this->Mixeval->id;
				$question_ids= $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> $id), 'fields'=>'id, question_num'));
				$this->MixevalsQuestionDesc->insertQuestionDescriptor($this->data['Question'], $question_ids);
				$this->Session->setFlash(__('The Mixed Evaluation was edited successfully.', true));
				$this->redirect('index');

			}else{
				$this->set('data', $this->data);
				$this->set('errmsg', $this->Mixeval->errorMessage);
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
		$this->set('action', 'Copy Mixed Evaluation');
		$this->render = false;
		$this->Mixeval->id = $id;
		$data = $this->Mixeval->read();
		$data['Mixeval']['name'] = ""; // Clear the name when evaluation copied
		$this->set('data', $data);
		$this->render('edit');
	}

	function delete($id)
	{
		// Deny Deleting evaluations in use:
		$this->Mixeval->id = $id;
		$data = $this->Mixeval->read();
		$inUse = (0 != $data['Mixeval']['event_count']);
		//	$inUse = $this->Event->checkEvaluationToolInUse('4',$id);

		if ($inUse) {
			$message = "<span style='color:red'>";
			$message.= "This evaluation is now in use, and can NOT be deleted.<br />";
			$message.= "Please remove all the events assosiated with this evaluation first.";
			$message.= "</span>";
			$this->Session->setFlash(__($message, true));
			$this->redirect('index');
			//	exit;
		} else {

			if ($this->Mixeval->delete($id))
			{
                          //Automatically deleted by dependent setting on Model
//				$this->MixevalsQuestionDesc->deleteQuestionDescriptors($id);
//				$this->MixevalsQuestion->deleteQuestions($id);
					
				$this->Session->setFlash(__('The Mixed Evaluation was removed successfully.', true));
				$this->redirect('index');

			} else {
				$this->Session->setFlash($this->Mixeval->errorMessage, 'error');
					
			}
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

//	function printUserName($user_id)
//	{
//		$tmp = $this->Mixeval->query("SELECT username FROM users WHERE id=$user_id");
//		echo $tmp[0]['users']['username'];
//	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
		$this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
	}
}

?>