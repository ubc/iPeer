<?php


class SearchsController extends AppController
{
/**
 * This controller does not use a model
 *
 * @var $uses
 */
  var $uses =  array('User', 'UserCourse', 'Event', 'GroupEvent', 'Group', 'EvaluationSubmission', 'Course','Personalize', 'GroupsMembers');
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $Sanitize;
	var $functionCode = 'ADV_SEARCH';
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework', 'SearchHelper','sysContainer');

	
	
	
	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->set('title_for_layout', 'Advanced Search');
 		
		parent::__construct();
	}

	function index($msg='') {
	$currentUser = $this->User->getCurrentLoggedInUser();
	$this->set('currentUser', $currentUser);
		
    $role = $this->Auth->user('role');  
  	$personalizeData = $this->Personalize->find('all',array('conditions' =>'user_id = '.$this->Auth->user('id')));
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
    $courses = 	
      $coursesList = $this->sysContainer->getMyCourseList();
      $this->set('coursesList', $coursesList);
	 
	 

      $searchMartix = $this->SearchHelper->formatSearchEvaluation('', $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
      
      $courses = $searchMartix['data'];
  
      $i=0;
      foreach($courses as $row): 
        $evaluation = $row['Event'];
        $name[$i] = $this->sysContainer->getCourseName($evaluation['course_id']);
        $i++;
        endforeach;
        $this->set('names', $name);
      $this->set('data', $searchMartix['data']);
      $this->set('paging', $searchMartix['paging']);
      $this->set('display', 'evaluation');
	 
    }
    $this->render('index');
	}

  function searchEvaluation(){
  	$this->params['form']['search_type'] = 'evaluation';
    $this->display();
  }

  function searchResult(){
    $this->params['form']['search_type'] = 'eval_result';
    $this->display();
  }

  function searchInstructor(){
    $this->params['form']['search_type'] = 'instructor';
    $this->display();
  }

	function display() {

	 // if (!isset($this->params['form']['select']))
      $this->layout = false;
	$currentUser = $this->User->getCurrentLoggedInUser();
	$this->set('currentUser', $currentUser);
    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
    	$personalizeData = $this->Personalize->find('all', array('conditions'=> 'user_id = '.$this->Auth->user('id')));
    	if ($personalizeData) {
    	   $this->userPersonalize->setPersonalizeList($personalizeData);
         $this->show = $this->userPersonalize->getPersonalizeValue('Search.ListMenu.Limit.Show');
         $this->set('userPersonalize', $this->userPersonalize);
    	}
    }
    $coursesList = $this->sysContainer->getMyCourseList();
    $this->set('coursesList', $coursesList);
    
     $courses = 	
      $coursesList = $this->sysContainer->getMyCourseList();
      $this->set('coursesList', $coursesList);
	 
	 

      $searchMartix = $this->SearchHelper->formatSearchEvaluation('', $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
      
      $courses = $searchMartix['data'];
  
      $i=0;
      foreach($courses as $row) {
        $evaluation = $row['Event'];
        $name[$i] = $this->sysContainer->getCourseName($evaluation['course_id']);
        $i++;
      }
        $this->set('names', $name);
    
  
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

        $eventList = $this->Auth->user('role') == 'A' ? $this->Event->find('all', array('conditions' => 'event_template_type_id != 3')) : $this->Event->find('all', array('conditions' => 'creator_id = '.$this->Auth->user('id') . ' AND event_template_type_id !=3'));
        $this->set('sticky', $sticky);
        $this->set('eventList', $eventList);
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'eval_result');
        break;

      case 'instructor':
      	$nibble = $this->SearchHelper->setInstructorCondition($this->params);
     //	var_dump($nibble);
        $condition = $nibble['condition'];
        $sticky = $nibble['sticky'];

        $searchMartix = $this->SearchHelper->formatSearchInstructor($condition, $this->order, $this->show, $this->page, $this->sortBy, $this->direction);
        $this->set('sticky', $sticky);
        $this->set('data', $searchMartix['data']);
        $this->set('paging', $searchMartix['paging']);
        $this->set('display', 'instructor');
     //   var_dump($searchMartix);
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
  		$this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
	}

  function eventBoxSearch() {
    $this->layout = false;
    $courseId = $this->params['form']['course_id'];
    $condition = 'course_id='.$courseId;
    if ($courseId == 'A') {
      $condition = '';
  }
    $this->set('eventList',$this->Event->find('all', array ('conditions' => $condition)));
  }

}?>
