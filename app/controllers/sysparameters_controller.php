<?php
/* SVN FILE: $Id: sysparameters_controller.php,v 1.2 2006/08/22 17:31:26 davychiu Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.2 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/22 17:31:26 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Sysparameters
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses('neat_string');
class SysParametersController extends AppController
{
  var $name = 'SysParameters';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
	var $uses = array('SysParameter','Personalize');

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
 		$this->pageTitle = 'Sys Parameters';
		parent::__construct();
	}

	function index($message='')
	{
  	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('SysParam.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('SysParam.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'SysParam.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}
  	$data = $this->SysParameter->findAll($conditions=null, $fields=null, $this->order, $this->show, $this->page);

  	$paging['style'] = 'ajax';
  	$paging['link'] = '/sysparameters/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

  	$paging['count'] = $this->SysParameter->findCount($conditions=null);
  	$paging['show'] = array('10','25','50','all');
  	$paging['page'] = $this->page;
  	$paging['limit'] = $this->show;
  	$paging['direction'] = $this->direction;

    if (isset($message)) {
      $this->set('message', $message);
    }

  	$this->set('paging',$paging);
  	$this->set('data',$data);
	}

	function view($id)
	{
		$this->set('data', $this->SysParameter->read());
	}

	function add()
	{
		if (empty($this->params['data']))
		{
			$this->render();
		}
		else
		{
			if ($this->SysParameter->save($this->params['data']))
			{
				$message = 'The record is saved successfully';
				$this->redirect('sysparameters/index/'.$message);
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render('edit');
			}
		}
	}

	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
			$this->SysParameter->setId($id);
			$this->params['data'] = $this->SysParameter->read();
			$this->render();
		}
		else
		{
			if ( $this->SysParameter->save($this->params['data']))
			{
				$message = 'The record is edited successfully';
				$this->redirect('sysparameters/index/'.$message);
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
    if ($this->SysParameter->del($id)) {
				$message = 'The record is deleted successfully';
				$this->redirect('sysparameters/index/'.$message);
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
         $this->show = $this->userPersonalize->getPersonalizeValue('Survey.ListMenu.Limit.Show');
         $this->set('userPersonalize', $this->userPersonalize);
    	}
    }

    $conditions = null;
    if (isset($this->params['form']['livesearch'])){
      $searchField=$this->params['form']['searchIndex'];
      $searchValue=$this->params['form']['livesearch'];

      $conditions = $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
    }
    $this->update($attributeCode = 'SysParam.ListMenu.Limit.Show',$attributeValue = $this->show);
    $this->set('conditions',$conditions);
  }
	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>