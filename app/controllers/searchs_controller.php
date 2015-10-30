<?php
/**
 * SearchsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SearchsController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array('GroupEvent', 'User', 'UserCourse', 'Event', 'Group',
        'EvaluationSubmission', 'Course', 'Personalize', 'GroupsMembers', 'EventTemplateType');
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $Sanitize;
    public $functionCode = 'ADV_SEARCH';
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $components = array('Output', 'Search',
        'userPersonalize', 'framework', 'Evaluation');


    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);

        parent::__construct();
    }

    /**
     * beforeFilter
     *
     *
     * @access public
     * @return void
     */
    function  beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', __('Advanced Search', true));

        $currentUser = $this->User->getCurrentLoggedInUser();
        $this->set('currentUser', $currentUser);
        $coursesList = User::getMyCourseList();
        $this->set('coursesList', $coursesList);

        $personalizeData = $this->Personalize->find('all', array('conditions' =>'user_id = '.$this->Auth->user('id')));
        $this->userPersonalize->setPersonalizeList($personalizeData);
        if ($personalizeData && $this->userPersonalize->inPersonalizeList('Search.ListMenu.Limit.Show')) {
            $this->show = $this->userPersonalize->getPersonalizeValue('Search.ListMenu.Limit.Show');
            $this->set('userPersonalize', $this->userPersonalize);
        } else {
            $this->show = '15';
            //$this->update($attributeCode = 'Search.ListMenu.Limit.Show', $attributeValue = $this->show);
        }
    }


    /**
     * update
     *
     * @param string $attributeCode  attribute code
     * @param string $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function update($attributeCode='', $attributeValue='')
    {
        if ($attributeCode != '' && $attributeValue != '') {
            $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
        }
    }


    /**
     * index
     * Index Page: Redirects to searchEvaluation page initially
     *
     * @param string $msg
     *
     * @access public
     * @return void
     */
    function index($msg='')
    {
        $this->set('message', $msg);
        // redirect wanderers back to home - search is not used anymore
        $this->redirect('/home');
    }

    /**
     * searchEvaluation
     * Advanced Search Evaluation
     *
     * @access public
     * @return void
     */
    function searchEvaluation()
    {
        $nibble = $this->Search->setEvaluationCondition($this->params);
        //$sticky = $nibble['sticky'];
        $condition = $nibble['condition'];
        $searchMatrix = $this->formatSearchEvaluation($condition, $this->sortBy, $this->show);

        $courses = $searchMatrix;

        $index = 0;
        $name = array();
        foreach ($courses as $row) {
            $evaluation = $row['Event'];
            $name[$index] = $this->Course->getCourseName($evaluation['course_id']);
            $index++;
        }
        $user = $this->User->findById($this->Auth->user('id'));
        $courseList = array();
        foreach ($user['Course'] as $course) {
            $courseList[$course['id']] = $course['course'];
        }
        $this->set('courseList', $courseList);

        $this->set('eventTypes', $this->EventTemplateType->getEventTemplateTypeList(false));
        $this->set('names', $name);
        $this->set('data', $searchMatrix);
        $this->set('display', 'evaluation');
        // redirect wanderers
        $this->redirect('/home');
    }


    /**
     * Advanced Search Evaluation Result
     */
    function searchResult()
    {

        $nibble = $this->Search->setEvalResultCondition($this->params);
        $sticky = $nibble['sticky'];
        $eventId = $nibble['event_id'];
        $status = $nibble['status'];
        $maxPercent = $nibble['maxPercent'];
        $minPercent = $nibble['minPercent'];

        $searchMatrix = $this->formatSearchEvaluationResult($maxPercent, $minPercent, $eventId, $status, $this->show);

        $eventList = (User::hasRole('superadmin') || User::hasRole('admin')) ?
            $this->Event->find('all', array(
                'conditions' => array('event_template_type_id !=' => '3'))) :
                $this->Event->find('all', array(
                    'conditions' => array('Event.creator_id' => $this->Auth->user('id') , 'event_template_type_id !=' => '3')));
        $this->set('sticky', $sticky);
        $this->set('eventList', $eventList);
        $this->set('data', $searchMatrix['data']);
        $this->set('paging', $searchMatrix['paging']);
        $this->set('display', 'eval_result');
        // redirect wanderers
        $this->redirect('/home');

    }

    /**
     * Advanced Search Instructor
     */
    function searchInstructor()
    {
        /*$nibble = $this->Search->setInstructorCondition($this->params);
        $condition = $nibble['condition'];
        $sticky = $nibble['sticky'];

        $searchMatrix = $this->formatSearchInstructor($condition, $this->sortBy, $this->show);

        $this->set('sticky', $sticky);
        $this->set('data', $searchMatrix);
        $this->set('display', 'instructor');*/
        // redirect wanderers
        $this->redirect('/home');
    }

    /**
     * Search box for searchResult
     */
    function eventBoxSearch()
    {
        $this->layout = false;
        $courseId = $this->params['form']['course_id'];
        $condition['course_id'] = $courseId;
        if ($courseId == 'A') {
            $condition = array();
        }
        $condition['event_template_type_id !='] = '3';
        $this->set('eventList', $this->Event->find('all', array ('conditions' => $condition)));
    }

    /**
     * This func returns paginated evaluation search result
     *
     * @param array  $conditions the conditions
     * @param string $sortBy     the sortBy
     * @param int    $limit      the limit per page
     *
     * @access public
     * @return void
     */
    function formatSearchEvaluation($conditions, $sortBy, $limit)
    {
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
     * @param string $conditions condition
     * @param mixed  $sortBy     sortBy
     * @param mixed  $limit      limit
     *
     * @access public
     * @return void
     */
    function formatSearchInstructor($conditions, $sortBy, $limit)
    {
        if (!isset($conditions['role'])) {
            $conditions['role'] = 'I';
        }

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
     * @param unknown_type $maxPercent max percent
     * @param unknown_type $minPercent min percent
     * @param unknown_type $eventId    event id
     * @param unknown_type $status     status
     * @param unknown_type $limit      the limit per page
     *
     * @access public
     * @return void
     */
    function formatSearchEvaluationResult($maxPercent, $minPercent, $eventId, $status, $limit)
    {
        $matrixAry = array();

        $assignedGroupIDs = array();
        $course_id = isset($this->params['form']['course_id'])? $this->params['form']['course_id']: "0";

        $conditions = $course_id == "A" ?
            ($eventId == "A"?
            ((User::hasRole('superadmin') || User::hasRole('admin')) ?
            array():
            array('Event.creator_id' => $this->Auth->user('id'))):
            array('Event.id' => $eventId)) :
            ($eventId == "A"? array('Event.course_id' => $course_id): array('Event.id' => $eventId));

        $conditions['event_template_type_id !='] = '3';

        $this->Event->recursive = -1;
        $events = $this->Event->find('all', array(
            'conditions' => $conditions
        ));

        foreach ($events as $event) {
            switch ($status) {
            case "listNotReviewed":
                $assignedGroupIDs = array_merge($assignedGroupIDs, $this->GroupEvent->getNotReviewed($event['Event']['id']));
                break;
            case "late":
                $assignedGroupIDs = array_merge($assignedGroupIDs, $this->GroupEvent->getLate($event['Event']['id']));
                break;
            case "low":
                $eventTypeId = $event['Event']['event_template_type_id'];
                $assignedGroupIDs = array_merge($assignedGroupIDs, $this->GroupEvent->getLowMark($event['Event']['id'], $eventTypeId, $maxPercent, $minPercent));
                break;
            default:
                //$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($eventId);
                $assignedGroupIDs = array_merge($assignedGroupIDs, $this->GroupEvent->getGroupsByEventId($event['Event']['id']));
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
                $numOfCompletedCount = $this->EvaluationSubmission->numCountInGroupCompleted($groupEventId);
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
        } else {
            $evlResult['Evaluation']['assignedGroups'] = array();
        }

        $paging['style'] = 'ajax';

        $paging['count'] = count($assignedGroupIDs);
        $paging['show'] = array('10', '25', '50', 'all');
        $paging['limit'] = $limit;

        $matrixAry['data'] = $evlResult;
        $matrixAry['paging'] = $paging;

        return $matrixAry;
    }
}
