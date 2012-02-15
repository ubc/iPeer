<?php

class SearchsController extends AppController
{
  /**
   * This controller does not use a model
   *
   * @var $uses
   */
  var $uses =  array('GroupEvent', 'User', 'UserCourse', 'Event', 'Group',
      'EvaluationSubmission', 'Course','Personalize', 'GroupsMembers', 'EventTemplateType');
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;
  var $functionCode = 'ADV_SEARCH';
  var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
  var $components = array('Output','sysContainer', 'Search',
      'userPersonalize', 'framework', 'sysContainer', 'Evaluation');
  
	
  function __construct() {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->set('title_for_layout', __('Advanced Search', true));

    parent::__construct();
  }

  function  beforeFilter() {
    parent::beforeFilter();
    $currentUser = $this->User->getCurrentLoggedInUser();
    $this->set('currentUser', $currentUser);
    $coursesList = $this->sysContainer->getMyCourseList();
    $this->set('coursesList', $coursesList);

    $role = $this->Auth->user('role');
    $personalizeData = $this->Personalize->find('all',array('conditions' =>'user_id = '.$this->Auth->user('id')));
    $this->userPersonalize->setPersonalizeList($personalizeData);
    if ($personalizeData && $this->userPersonalize->inPersonalizeList('Search.ListMenu.Limit.Show')) {
        $this->show = $this->userPersonalize->getPersonalizeValue('Search.ListMenu.Limit.Show');
        $this->set('userPersonalize', $this->userPersonalize);
    } else {
      $this->show = '15';
      $this->update($attributeCode = 'Search.ListMenu.Limit.Show',$attributeValue = $this->show);
    }
  }

  function update($attributeCode='',$attributeValue='') {
    if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
  }

  /**
   * Index Page: Redirects to searchEvaluation page initially
   *
   * @param $msg A Message
   */
  function index($msg='') {
    $this->set('message', $msg);
    $this->redirect('/searchs/searchEvaluation');
  }

  /**
   * Advanced Search Evaluation
   */
  function searchEvaluation(){
    $nibble = $this->Search->setEvaluationCondition($this->params);
    $sticky = $nibble['sticky'];
    $condition = $nibble['condition'];
    $searchMartix = $this->formatSearchEvaluation($condition, $this->sortBy, $this->show);

    $courses = $searchMartix;

    $i=0;
    $name = array();
    foreach($courses as $row):
      $evaluation = $row['Event'];
      $name[$i] = $this->sysContainer->getCourseName($evaluation['course_id']);
      $i++;
    endforeach;
    $this->set('eventTypes',$this->EventTemplateType->getEventTemplateTypeList(false));
    $this->set('names', $name);
    $this->set('data', $searchMartix);
    $this->set('display', 'evaluation');
  }

  /**
   * Advanced Search Evaluation Result
   */
  function searchResult(){

    $nibble = $this->Search->setEvalResultCondition($this->params);
    $sticky = $nibble['sticky'];
    $eventId = $nibble['event_id'];
    $status = $nibble['status'];
    $maxPercent = $nibble['maxPercent'];
    $minPercent = $nibble['minPercent'];

    $searchMartix = $this->formatSearchEvaluationResult($maxPercent,$minPercent,$eventId,$status, $this->order, $this->sortBy, $this->show);

    $eventList = $this->Auth->user('role') == 'A' ? 
      $this->Event->find('all', array(
          'conditions' => array('event_template_type_id !=' => '3'))) :
      $this->Event->find('all', array(
          'conditions' => array('Event.creator_id' => $this->Auth->user('id') , 'event_template_type_id !=' => '3')));
    $this->set('sticky', $sticky);
    $this->set('eventList', $eventList);
    $this->set('data', $searchMartix['data']);
    $this->set('paging', $searchMartix['paging']);
    $this->set('display', 'eval_result');

  }

  /**
   * Advanced Search Instructor
   */
  function searchInstructor(){
    $nibble = $this->Search->setInstructorCondition($this->params);
    $condition = $nibble['condition'];
    $sticky = $nibble['sticky'];

    $searchMartix = $this->formatSearchInstructor($condition, $this->sortBy, $this->show);

    $this->set('sticky', $sticky);
    $this->set('data', $searchMartix);
    $this->set('display', 'instructor');
  }

  /**
   * Search box for searchResult
   */
  function eventBoxSearch() {
    $this->layout = false;
    $courseId = $this->params['form']['course_id'];
    $condition['course_id'] = $courseId;    
    if ($courseId == 'A') {
      $condition = array();
    }
    $condition['event_template_type_id !='] = '3';
    $this->set('eventList',$this->Event->find('all', array ('conditions' => $condition)));
  }

   /**
   * This func returns paginated evaluation search result
   *
   * @param unknown_type $conditions the conditions
   * @param unknown_type $sortBy the sortBy
   * @param unknown_type $limit the limit per page
   */
  function formatSearchEvaluation($conditions, $sortBy, $limit) {

    $courseIDs =  $this->sysContainer->getMyCourseIDs();
    //$conditions .= !empty($conditions) ? ' AND course_id IN ('.$courseIDs.')':'course_id IN ('.$courseIDs.')';
    if(!isset($conditions['course_id']))
        $conditions['course_id'] = $courseIDs;

    $this->paginate = array(
      'conditions' => $conditions,
      'fields' => array('*', '(NOW() >= release_date_begin AND NOW() <= release_date_end) AS is_released'),
      'order' => 'Event.'.$sortBy,
      'limit' => $limit
    );

    return $this->paginate('Event');
  }

   /**
   * This func returns paginated instructor search result
   *
   * @param unknown_type $conditions the conditions
   * @param unknown_type $sortBy the sortBy
   * @param unknown_type $limit the limit per page
   */
  function formatSearchInstructor($conditions='', $sortBy, $limit)
  {
    //$conditions .= empty($conditions) ? 'role = "I"':' AND role ="I" ';
    if(!isset($conditions['role']))
        $conditions['role'] = 'I';

    $this->paginate = array(
                    'conditions' => $conditions,
                    'order' => 'User.'.$sortBy,
                    'limit' => $limit
    );

    return $this->paginate('User');
  }

  /**
   * This func returns paginated evaluation result search result
   *
   * @param unknown_type $maxPercent
   * @param unknown_type $minPercent
   * @param unknown_type $eventId
   * @param unknown_type $status
   * @param unknown_type $sortBy the sortBy
   * @param unknown_type $limit the limit per page
   */
  function formatSearchEvaluationResult($maxPercent=1,$minPercent=0,$eventId=null,$status=null, $sortBy, $limit) {
    $matrixAry = array();
    
    $assignedGroupIDs = array();
    $course_id = isset($this->params['form']['course_id'])? $this->params['form']['course_id']: "0";

    $conditions = $course_id == "A" ?
      ($eventId == "A"?
        ($this->Auth->user('role') == 'A' ? 
            array():
            array('Event.creator_id' => $this->Auth->user('id'))):
        array('Event.id' => $eventId)) :
      ($eventId == "A"? array('Event.course_id' => $course_id): array('Event.id' => $eventId));

    $conditions['event_template_type_id !='] = '3';
    
    $this->Event->recursive = -1;
    $events = $this->Event->find('all', array(
        'conditions' => $conditions
    ));

    foreach($events as $event){
      switch ($status) {
        case "listNotReviewed":
          $assignedGroupIDs = array_merge($assignedGroupIDs , $this->GroupEvent->getNotReviewed($event['Event']['id']));
          break;
        case "late":
          $assignedGroupIDs = array_merge($assignedGroupIDs , $this->GroupEvent->getLate($event['Event']['id']));
          break;
        case "low":
                $eventTypeId = $event['Event']['event_template_type_id'];
                $assignedGroupIDs = array_merge($assignedGroupIDs , $this->GroupEvent->getLowMark($event['Event']['id'], $eventTypeId, $maxPercent, $minPercent));
          break;
        default:
            //$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($eventId);
          $assignedGroupIDs = array_merge($assignedGroupIDs , $this->GroupEvent->getGroupsByEventId($event['Event']['id']));
          break;
      }
    }
    
    if (!empty($assignedGroupIDs)) {
      $assignedGroups = array();

            // retrieve string of group ids
      for ($i = 0; $i < count($assignedGroupIDs); $i++) {
        $groupid = $assignedGroupIDs[$i]['GroupEvent']['group_id'];
        $groupEventId = $assignedGroupIDs[$i]['GroupEvent']['id'];
        $group = $this->Group->find('first', array('conditions' => array('Group.id' => $groupid)));
        $assignedGroups[$i] = $group;
        //Get Members whom completed evaluation
        $numOfCompletedCount = $this->EvaluationSubmission->numCountInGroupCompleted($group['Group']['id'],
                                                                               $groupEventId);
        //Check to see if all members are completed this evaluation

        $numMembers=$this->GroupsMembers->find('count', array('conditions' => 'group_id='.$group['Group']['id']));
        ($numOfCompletedCount == $numMembers) ? $completeStatus = 1:$completeStatus = 0;


        //Get release status
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($assignedGroupIDs[$i]['GroupEvent']['event_id'], $group['Group']['id']);
        $released = $this->Evaluation->getGroupReleaseStatus($groupEvent);

        $assignedGroups[$i]['Group']['complete_status'] = $completeStatus;
        $assignedGroups[$i]['Group']['num_completed'] = $numOfCompletedCount;
        $assignedGroups[$i]['Group']['num_members'] = $numMembers;
        $assignedGroups[$i]['Group']['marked'] = $assignedGroupIDs[$i]['GroupEvent']['marked'];
        $assignedGroups[$i]['Group']['grade_release_status'] = $released['grade_release_status'];
        $assignedGroups[$i]['Group']['comment_release_status'] = $released['comment_release_status'];
        $assignedGroups[$i]['Group']['event_title'] = $this->Event->getEventTitleById($assignedGroupIDs[$i]['GroupEvent']['event_id']);
        $assignedGroups[$i]['Group']['event_id'] = $assignedGroupIDs[$i]['GroupEvent']['event_id'];

      }

      $evlResult['Evaluation']['assignedGroups'] = $assignedGroups;
    }else {
      $evlResult['Evaluation']['assignedGroups'] = array();
    }

    $paging['style'] = 'ajax';

    $paging['count'] = count($assignedGroupIDs);
    $paging['show'] = array('10','25','50','all');
    $paging['limit'] = $limit;

    $matrixAry['data'] = $evlResult;
    $matrixAry['paging'] = $paging;

    return $matrixAry;
  }


}?>
