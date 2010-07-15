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
 * @lastmodified $Date: 2006/08/22 17:31:26 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Sysfunctions
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses('neat_string');
class SysFunctionsController extends AppController
{
  var $name = 'SysFunctions';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
    var $uses = array('SysFunction','Personalize');

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
 		$this->pageTitle = 'Sys Functions';
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
        $data = $this->SysFunction->findAll($conditions=null, $fields=null, $this->order, $this->show, $this->page);

        $searchIndexURL = isset($_GET['searchIndex']) ? $_GET['searchIndex'] : '';
        $liveSearchURL  = isset($_GET['livesearch'])  ? $_GET['livesearch']  : '';

        $searchIndex = isset($this->params['form']['searchIndex']) ?
            $this->params['form']['searchIndex'] : $searchIndexURL;
        $liveSearch =  isset($this->params['form']['livesearch']) ?
                    $this->params['form']['livesearch'] : $liveSearchURL;



        if (isset($message)) {
            $this->set('message', $message);
        }

        $this->set('data',$data);
        $this->set('searchIndex',$searchIndex);
        $this->set('liveSearch',$liveSearch);
	}

	function view($id)
	{
        $data = $this->SysFunction->read();
		$this->set('data', $data);
	}

	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
			$this->SysFunction->setId($id);
			$this->params['data'] = $this->SysFunction->read();
			$this->render();
		}
		else
		{
			if ( $this->SysFunction->save($this->params['data']))
			{
				$message = 'The record is edited successfully';
				$this->redirect('sysfunctions/index/'.$message);
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
    if ($this->SysFunction->del($id)) {
				$message = 'The record is deleted successfully';
				$this->redirect('sysfunctions/index/'.$message);
    }
  }

    function search()
    {
        if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
            $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
            if ($personalizeData) {
                $this->userPersonalize->setPersonalizeList($personalizeData);
                $this->show = $this->userPersonalize->getPersonalizeValue('SysFunc.ListMenu.Limit.Show');
                $this->set('userPersonalize', $this->userPersonalize);
            }
        }


        $this->layout = 'ajax';
        $pagination->loadingId = 'loading';

        $conditions = null;

        $searchIndexURL = isset($_GET['searchIndex']) ? $_GET['searchIndex'] : '';
        $liveSearchURL  = isset($_GET['livesearch'])  ? $_GET['livesearch']  : '';

        $searchIndex = isset($this->params['form']['searchIndex']) ?
            $this->params['form']['searchIndex'] : $searchIndexURL;
        $liveSearch =  isset($this->params['form']['livesearch']) ?
                    $this->params['form']['livesearch'] : $liveSearchURL;

        if (!empty($searchIndex) && !empty($liveSearch)){
            $searchField = $searchIndex;
            $searchValue = $liveSearch;

            $conditions = $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";

        }

        $this->set('conditions',$conditions);
        $this->set('searchIndex', $searchIndex);
        $this->set('liveSearch', $liveSearch);
        $this->update($attributeCode = 'SysFunc.ListMenu.Limit.Show',$attributeValue = $this->show);
    }

    function update($attributeCode='',$attributeValue='') {
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
    }
}

?>