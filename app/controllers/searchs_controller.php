<?php


class SearchsController extends AppController
{
/**
 * This controller does not use a model
 *
 * @var $uses
 */
  var $uses =  array('User', 'UserCourse', 'Event', 'GroupEvent', 'Group', 'EvaluationSubmission', 'Course','Personalize');
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $Sanitize;
	var $functionCode = 'ADV_SEARCH';
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'SearchHelper');

	function __construct()
	{
		$this->Sanitize = &new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Advanced Search';
		parent::__construct();
	}

	function index($msg='') {

    $role = $this->rdAuth->role;

  	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('Search.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('Search.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'Search.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}

    $this->set('message', $msg);
    //General Home Rendering for Admin and Instructor
    if ($role == $this->User->USER_TYPE_ADMIN || $role == $this->User->USER_TYPE_INSTRUCTOR)
    {
      $courseList = $this->sysContainer->getMyCourseList();
      $this->set('courseList', $courseList);

      $searchMartix = $this->SearchHelper->formatSearchEvaluation('', $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
      $this->set('data', $searchMartix['data']);
      $this->set('paging', $searchMartix['paging']);
      $this->set('display', 'evaluation');

    }
    $this->render('index');
	}

	function display() {
	 // if (!isset($this->params['form']['select']))
      $this->layout = false;

    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
    	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    	if ($personalizeData) {
    	   $this->userPersonalize->setPersonalizeList($personalizeData);
         $this->show = $this->userPersonalize->getPersonalizeValue('Search.ListMenu.Limit.Show');
         $this->set('userPersonalize', $this->userPersonalize);
    	}
    }
    $courseList = $this->sysContainer->getMyCourseList();
    $this->set('courseList', $courseList);
//print_r($this->params['form']);
    $search_select = isset($this->params['form']['select']) ? $this->params['form']['select']:$this->params['form']['search_type'];
    switch ($search_select) {
      case 'evaluation':
        $nibble = $this->SearchHelper->setEvaluationCondition($this->params);
        $sticky = $nibble['sticky'];
        $condition = $nibble['condition'];

        $searchMartix = $this->SearchHelper->formatSearchEvaluation($condition, $this->order, $this->show, $this->page, $this->sortBy, $this->direction);

        $this->set('sticky',$sticky);
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'evaluation');
        break;

      case 'eval_result':
        $nibble = $this->SearchHelper->setEvalResultCondition($this->params);
        $sticky = $nibble['sticky'];
        $eventId = $nibble['event_id'];
        $status = $nibble['status'];
        $maxPercent = $nibble['maxPercent'];
        $minPercent = $nibble['minPercent'];

        $searchMartix = $this->SearchHelper->formatSearchEvaluationResult($maxPercent,$minPercent,$eventId,$status, $this->order, $this->show, $this->page, $this->sortBy, $this->direction);

        $this->set('sticky', $sticky);
        $this->set('eventList',$this->Event->findAll());
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'eval_result');
        break;

      case 'instructor':
        $nibble = $this->SearchHelper->setInstructorCondition($this->params);
        $condition = $nibble['condition'];
        $sticky = $nibble['sticky'];

        $searchMartix = $this->SearchHelper->formatSearchInstructor($condition, $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
        $this->set('sticky', $sticky);
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'instructor');
        break;

      default:
        $searchMartix = $this->SearchHelper->formatSearchEvaluation('', $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'evaluation');
        break;
    }
	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}

  function eventBoxSearch() {
    $this->layout = false;
    $courseId = $this->params['form']['course_id'];
    $condition = 'course_id='.$courseId;
    if ($courseId == 'A')
      $condition = '';
    $this->set('eventList',$this->Event->findAll($condition));
  }

}?>