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
  var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'RubricHelper');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Rubrics';
		parent::__construct();
	}

	function index($msg='') {
	  $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('Rubric.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('Rubric.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'Rubric.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}
    $conditions = 'creator_id = '.$this->rdAuth->id;
		$data = $this->Rubric->findAll($conditions, $fields=null, $this->order, $this->show, $this->page);

		$paging['style'] = 'ajax';
		$paging['link'] = '/rubrics/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

		$paging['count'] = $this->Rubric->findCount($conditions);
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
            $data = $this->Rubric->findById($id);
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
    $this->params['data']['Rubric']['name'] = null;
    $this->set('preview', 1);
    $this->set('copy', 1);
		$this->render('edit');
	}

	function delete($id)
	{
		if ($this->Rubric->del($id))
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

	function search()
  	{
      $this->layout = 'ajax';
      $pagination->loadingId = 'loading';

      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      	if ($personalizeData) {
      	   $this->userPersonalize->setPersonalizeList($personalizeData);
           $this->show = $this->userPersonalize->getPersonalizeValue('Rubric.ListMenu.Limit.Show');
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
        $conditions .= $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }
      $this->update($attributeCode = 'Rubric.ListMenu.Limit.Show',$attributeValue = $this->show);
      $this->set('conditions',$conditions);
      $this->set('event', $this->Event);

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
