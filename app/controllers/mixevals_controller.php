<?php
/* SVN FILE: $Id: mixevals_controller.php,v 1.16 2006/09/12 20:58:00 kamilon Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.16 $
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
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'MixevalHelper');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Mixed Evaluations';
		parent::__construct();
	}

	function index($msg='') {
	  $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('Mixeval.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('Mixeval.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'Mixeval.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}

    $conditions = 'creator_id = '.$this->rdAuth->id;
		$data = $this->Mixeval->findAll($conditions, $fields=null, $this->order, $this->show, $this->page);

		$paging['style'] = 'ajax';
		$paging['link'] = '/mixevals/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

		$paging['count'] = $this->Mixeval->findCount($conditions);
		$paging['show'] = array('10','25','50','all');
		$paging['page'] = $this->page;
		$paging['limit'] = $this->show;
		$paging['direction'] = $this->direction;

		$this->set('paging',$paging);
		$this->set('data',$data);
		$this->set('message',$msg);
		$this->set('event', $this->Event);
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
		if ($layout != ''){
	    $this->layout = $layout;
	    $this->set('layout', $layout);
		}

		if (empty($this->params['data']))
		{
		  $this->render();
		}
		else
		{
//print_r($this->params['data']);
      $this->Mixeval->setId(null);
      //print_r($this->params['data']['total_marks']);
      //$this->params['data']['Mixeval']['total_marks'] = isset($this->params['data']['Mixeval']['total_marks'])?$this->params['data']['total_marks']:'';
			//check to see if user has clicked next
			if(!empty($this->params['form']['next'])){
			  //print_r($this->params['data']);
			  //$this->set('total_mark',1);
				$this->set('data', $this->params['data']);
				$this->render('add');
			}
			//adding a new rubric
			else{
			  $data = $this->params['data'];
			  $data['Mixeval']['total_question'] = $data['Mixeval']['total_question'] -1 ;
			  if ($this->Mixeval->save($data))
			  {
  				//prepare the data from the form fields in array
  				$mixEvaluation = $this->Mixeval->prepData($this->params, $this->rdAuth->id);

  				//insert all the rubric data into other associated tables
  				$this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $mixEvaluation);
  				$this->MixevalsQuestionDesc->insertQuestionDescriptor($this->Mixeval->id, $mixEvaluation);

  				$this->redirect('/mixevals/index/The mixed evaluation was added successfully.');
  			}
  			//updating a current rubric
  			else{
            $this->set('data', $this->params['data']);
            $this->set('errmsg', $this->Mixeval->errorMessage);
            $this->render('add');
  			  }
  		  }
  		}
	}

	function edit($id=null)
	{
		$this->Mixeval->setId($id);

		if (empty($this->params['data']))
		{
			$this->params['data'] = $this->Mixeval->read();
			$this->set('data', $this->params['data']);
			$this->set('mixeval_id',$this->params['data']['Mixeval']['id']);
		}
		else
		{
			//check to see if user has clicked preview
			if(!empty($this->params['form']['preview'])){
				$this->set('data', $this->params['data']);
				$this->set('mixeval_id',$this->params['data']['Mixeval']['id']);
				$this->render('edit');
			}
			else {
			  $this->params['data']['Mixeval']['total_marks'] = $this->params['form']['total_marks'];
  			if ( $this->Mixeval->save($this->params['data']))
  			{
  				//prepare the data from the form fields in array
  				$mixEvaluation = $this->Mixeval->prepData($this->params, $this->rdAuth->id);

  				//insert all the rubric data into other associated tables
  				$this->MixevalsQuestion->updateQuestion($this->Mixeval->id, $mixEvaluation);
  				$this->MixevalsQuestionDesc->updateQuestionDescriptor($this->Mixeval->id, $mixEvaluation);

          $this->redirect('/mixevals/index/The mixed evaluation was updated successfully.');
  			}
  			else
  			{
  				$this->set('data', $this->params['data']);
  				$this->set('mixeval_id',$this->params['data']['mixeval']['id']);
  				$this->set('errmsg', $this->Mixeval->errorMessage);
  				$this->render('edit');
  			}
  		}
		}
	}

  function copy($id=null)
  {
    $this->render = false;
		$this->Mixeval->setId($id);
		$data = $this->Mixeval->read();
		$this->set('data', $data);
		$this->render('edit');
  }

	function delete($id)
	{
		if ($this->Mixeval->del($id))
		{
			$this->MixevalsQuestion->deleteQuestions($id);
			$this->MixevalsQuestionDesc->deleteQuestionDescriptors($id);
			$this->redirect('/mixevals/index/The mixed evaluation was deleted successfully.');
		}
	}

	function search()
  	{
      $this->layout = 'ajax';
      $pagination->loadingId = 'loading';

      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      	if ($personalizeData) {
      	   $this->userPersonalize->setPersonalizeList($personalizeData);
           $this->show = $this->userPersonalize->getPersonalizeValue('Mixeval.ListMenu.Limit.Show');
           $this->set('userPersonalize', $this->userPersonalize);
      	}
      }

      $conditions = '';
      if (!empty($this->params['form']['show_my_tool']) && $this->params['form']['show_my_tool']){
        $conditions .= 'creator_id = '.$this->rdAuth->id;
      } else if (empty($this->params['form']['show_my_tool'])){
        $conditions .= ' (creator_id = '.$this->rdAuth->id. ' OR availability = "public" ) ';
      }

      if (!empty($this->params['form']['livesearch']) && !empty($this->params['form']['select'])){
        $pagination->loadingId = 'loading';
        //parse the parameters
        $searchField=$this->params['form']['select'];
        $searchValue=$this->params['form']['livesearch'];
        (empty($conditions))? $conditions = '' : $conditions .= ' AND ';
        $conditions = $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }
      $this->update($attributeCode = 'Mixeval.ListMenu.Limit.Show',$attributeValue = $this->show);
      $this->set('conditions',$conditions);
      $this->set('event', $this->Event);

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
		$tmp = $this->Mixeval->findBySql("SELECT username FROM users WHERE id=$user_id");
		echo $tmp[0]['users']['username'];
	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>