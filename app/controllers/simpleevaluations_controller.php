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
//	var $components = array('EvaluationSimpleHelper');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->NeatString = new NeatString;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Simple Evaluations';
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && 'on' == $_REQUEST['show_my_tool']) ? true : false;

		parent::__construct();
	}

	function index($msg=null)
	{

    $this->pageTitle = 'Evaluation Tools';

    $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('SimpleEval.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('SimpleEval.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'SimpleEval.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}


  	$conditions = 'creator_id = '.$this->rdAuth->id;
  	$data = $this->SimpleEvaluation->findAll($conditions, $fields=null, $this->order, $this->show, $this->page);

  	$paging['style'] = 'ajax';
  	$paging['link'] = '/simpleevaluations/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

  	$paging['count'] = $this->SimpleEvaluation->findCount($conditions);
  	$paging['show'] = array('10','25','50','all');
  	$paging['page'] = $this->page;
  	$paging['limit'] = $this->show;
  	$paging['direction'] = $this->direction;

  	$this->set('paging',$paging);
  	$this->set('data',$data);
  	if (isset($msg)) {
  	 $this->set('message', $msg);
  	}
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

        //Validate the error why the SimpleEvaluation->save() method returned false
        $this->validateErrors($this->SimpleEvaluation);
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
    $this->params['data']['SimpleEvaluation']['name'] = null;
		//converting nl2br back so it looks better
		$this->Output->br2nl($this->params['data']);
    $this->render('add');

	}

	function delete($id)
	{
    if (isset($this->params['form']['id']))
    {
      $id = intval(substr($this->params['form']['id'], 5));
    }   //end if
    if ($this->SimpleEvaluation->del($id)) {

			$this->redirect('/simpleevaluations/index/The record is deleted successfully.');
    }
	}

  function search()
  {
      $this->layout = 'ajax';
      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      	if ($personalizeData) {
      	   $this->userPersonalize->setPersonalizeList($personalizeData);
           $this->show = $this->userPersonalize->getPersonalizeValue('SimpleEval.ListMenu.Limit.Show');
           $this->set('userPersonalize', $this->userPersonalize);
      	}
      }
      $conditions = '';
      if ($this->mine_only){
        $conditions .= 'creator_id = '.$this->rdAuth->id;
      }

      if (!empty($this->params['form']['livesearch']) && !empty($this->params['form']['select'])){
        $pagination->loadingId = 'loading';
        //parse the parameters
        $searchField=$this->params['form']['select'];
        $searchValue=$this->params['form']['livesearch'];

        (empty($conditions))? $conditions = '' : $conditions .= ' AND ';
        $conditions .= $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }

      $this->update($attributeCode = 'SimpleEval.ListMenu.Limit.Show',$attributeValue = $this->show);
      $this->set('conditions',$conditions);
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
