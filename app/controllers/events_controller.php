<?php
/**
 * EventsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EventsController extends AppController
{
    public $name = 'Events';
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $uses = array('GroupEvent', 'User', 'Group', 'Course', 'Event', 'EventTemplateType', 'SimpleEvaluation', 'Rubric', 'Mixeval', 'Personalize', 'GroupsMembers', 'Penalty');
    public $components = array("AjaxList", "Session");

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->set('title_for_layout', __('Events', true));
        parent::__construct();
    }

    /**
     * postProcessData Post Process Data : add released column
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function postProcessData($data)
    {
        // Check the release dates, and match them up with present date
        if (empty($data)) {
            return $data;
        }
        // loop through each data point, and display it.
        foreach ($data as $i => $entry) {
            $releaseDate = strtotime($entry["Event"]["release_date_begin"]);
            $endDate = strtotime($entry["Event"]["release_date_end"]);
            $timeNow = time();

            if (!$releaseDate) {
                $releaseDate = 0;
            }
            if (!$endDate) {
                $endDate = 0;
            }

            $isReleased = "";
            if ($timeNow < $releaseDate) {
                $isReleased = __("Not Yet Open", true);
            } else if ($timeNow > $endDate) {
                $isReleased = __("Already Closed", true);
            } else {
                $isReleased = __("Open Now", true);
            }

            // Set the is released string
            $entry['!Custom']['isReleased'] = $isReleased;

            // Set the view results column
            $entry['!Custom']['results'] = __("Results", true);

            // Write the entry back
            $data[$i] = $entry;
        }

        // Return the modified data
        return $data;
    }


    /**
     * setUpAjaxList
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function setUpAjaxList($courseId = null)
    {
        // Grab the course list
        $coursesList = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');

        // Set up Columns
        $columns = array(
            array("Event.id",             "",            "",     "hidden"),
            array("Course.id",            "",            "",     "hidden"),
            array("Course.course",        __("Course", true),      "9em", "action", "View Course"),
            array("Event.Title",          __("Title", true),       "auto", "action", "View Event"),
            array("!Custom.results",       __("View", true),       "4em", "action", "View Results"),
            array("Event.event_template_type_id", __("Type", true), "", "map",
            array("1" => __("Simple", true), "2" => __("Rubric", true), "4" => __("Mixed", true))),
            array("Event.due_date",       __("Due Date", true),    "10em", "date"),
            array("!Custom.isReleased",    __("Released ?", true), "8em", "string"),
            array("Event.self_eval",       __("Self Eval", true),   "6em", "map",
            array("0" => __("Disabled", true), "1" => __("Enabled", true))),
            array("Event.com_req",        __("Comment", true),      "6em", "map",
            array("0" => __("Optional", true), "1" => __("Required", true))),

            // Release window
            array("Event.release_date_begin", "", "",    "hidden"),
            array("Event.release_date_end",   "", "",    "hidden"),
        );

        // put all the joins together
        // shows all events of courses the user (not student) has access to
        if ($courseId == null) {
            $joinTables =  array( array (
                // GUI aspects
                "id" => "course_id",
                "description" => __("for Course:", true),
                // The choice and default values
                "list" => $coursesList,
            ));
        // shows only the events from the current selected course (parameter)
        // non-numeric and invalid ids will result in "No Results"
        } else {
            $joinTables =  array( array (
                // GUI aspects
                "id" => "course_id",
                "description" => __("for Course:", true),
                // The choice and default values
                "list" => $coursesList,
                "default" => $courseId,
            ));
        }

        // super admins
        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        // faculty admins or instructors - $coursesList from above
        } else {
            $extraFilters = " ( ";
            $courseIds = array_keys($coursesList);
            foreach ($courseIds as $id) {
                $extraFilters .= "course_id=$id or ";
            }
            $extraFilters .= "1=0 ) ";
        }

        // Leave the survey types out, always
        $extraFilters .= !empty($extraFilters) ? "and " : "";
        $extraFilters .= "Event.event_template_type_id<>3";


        // Set up actions
        $warning = __("Are you sure you want to delete this event permanently?", true);
        $actions = array(
            array("View Results", "", "", "evaluations", "view", "Event.id"),
            array("View Event", "", "", "", "view", "Event.id"),
            array("Edit Event", "", "", "", "edit", "Event.id"),
            array("View Course", "", "", "courses", "view", "Course.id"),
            array("View Groups", "", "", "", "viewGroups", "Event.id"),
            array("Export Results", "", "", "evaluations", "export/event", "Event.id"),
            array("Delete Event", $warning, "", "", "delete", "Event.id"));

        $recursive = 0;

        $this->AjaxList->setUp($this->Event, $columns, $actions,
            "Course.course", "Course.course", $joinTables, $extraFilters,
            $recursive, "postProcessData");
    }

    /**
     * index
     *
     * @param mixed $courseId
     * @param string $message
     *
     * @access public
     * @return void
     */
    function index($courseId = null, $message = '')
    {
        // check for permission to course ($courseId) only when $courseId is provided
        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (!$course) {
                $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
                $this->redirect('index');
            }
            $this->breadcrumb->push(array('course' => $course['Course']));
        }

        $this->set('message', $message);

        // We need to change the session state to point to this
        // course:
        // Initialize a basic non-funcional AjaxList
        $this->AjaxList->quickSetUp();
        // Clear the state first, we don't want any previous searches/selections.
        $this->AjaxList->clearState();
        // Set and update session state Variable
        $joinFilterSelections->course_id = $courseId;
        $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);

        // Set up the basic static ajax list variables
        $this->Session->write('eventsControllerCourseId', $courseId);
        $this->setUpAjaxList($courseId);
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
        $this->set('courseId', $courseId);
        $this->set('breadcrumb', $this->breadcrumb->push(__('Events', true)));
    }


    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Set up the list
        $courseId = $this->Session->read('eventsControllerCourseId');
        $this->setUpAjaxList($courseId);
        // Process the request for data
        $this->AjaxList->asyncGet();
    }


    /**
     * view
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function view ($id = null)
    {
        if (!is_numeric($id) || !($event = $this->Event->getEventByid($id))) {
            $this->Session->setFlash(__('Event does not exist.', true));
            $this->redirect('index');
        }
        if ($event['Event']['event_template_type_id'] == '3') {

            $this->Session->setFlash(__('Invalid Id', true));
            $this->redirect('index');
        }

        $courseId = $this->Event->getCourseByEventId($id);

        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        $this->data = $event;

        //Clear $id to only the alphanumeric value
        $id = $this->Sanitize->paranoid($id);
        $this->set('event_id', $id);

        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $id),
            'contain' => array('Group.Member', 'Course')));
        $courseId = $event['Event']['course_id'];
        $this->set('title_for_layout', $this->Course->getCourseName($courseId).__(' > Events > View', true));

        //Format Evaluation Selection Boxes
        $default = null;
        $model = '';
        $eventTemplates = array();
        $templateId = $event['Event']['event_template_type_id'];
        if (!empty($templateId)) {
            if ($templateId == 1) {
                $default = 'Default Simple Evaluation';
                $model = 'SimpleEvaluation';
                $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'));
            } else if ($templateId == 2) {
                $default = 'Default Rubric';
                $model = 'Rubric';
                $eventTemplates = $this->Rubric->getBelongingOrPublic($this->Auth->user('id'));
            } else if ($templateId == 4) {
                $default = 'Default Mixed Evaluation';
                $model = 'Mixeval';
                $eventTemplates = $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'));
            }
        }
        $days = $this->Penalty->find('count', array('conditions' => array('Penalty.event_id' => $id))) - 1;
        $penalty = $this->Penalty->find('all', array('conditions' => array('Penalty.event_id' => $id), 'order' => array('Penalty.days_late ASC')));

        $this->set('days', $days);
        $this->set('penalty', $penalty);
        $this->set('event', $event);
        $this->set('course_id', $courseId);
        $this->set('eventTemplates', $eventTemplates);
        $this->set('default', $default);
        $this->set('model', $model);


        //Get all display event types
        //$eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
        $eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
        $this->set('eventTypes', $eventTypes);
        $this->render();
    }

    /**
     * deprecated?
     * eventTemplatesList
     *
     * @param int $templateId
     *
     * @access public
     * @return void
     */
    /*function eventTemplatesList($templateId = 1)
    {
        $currentUser = $this->User->getCurrentLoggedInUser();
        $this->layout = 'ajax';
        //$conditions = null;
        $eventTemplates = array();
        $default = null;
        $model = '';
        if (!empty($templateId)) {
            if ($templateId == 1) {
                $default = 'Default Simple Evaluation';
                $model = 'SimpleEvaluation';
                $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($currentUser['id']);
            } else if ($templateId == 2) {
                $default = 'Default Rubric';
                $model = 'Rubric';
                $eventTemplates = $this->Rubric->getBelongingOrPublic($currentUser['id']);
            } else if ($templateId == 4) {
                $default = 'Default Mixed Evaluation';
                $model = 'Mixeval';
                $eventTemplates = $this->Mixeval->getBelongingOrPublic($currentUser['id']);
            }

        }

        $this->set('eventTemplates', $eventTemplates);
        $this->set('default', $default);
        $this->set('model', $model);
    }*/



    /**
     * Add an event
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function add($courseId)
    {
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/home');
            return;
        }

        // Init form variables needed for display
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Add Event', true)));
        $this->set('groups', $this->Group->getGroupsByCourseId($courseId));
        $courseList = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $this->set('courses', $courseList);
        $this->set(
            'eventTemplateTypes',
            $this->EventTemplateType->getEventTemplateTypeList(true)
        );
        $this->set(
            'mixevals',
            $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'simpleEvaluations',
            $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'rubrics',
            $this->Rubric->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set('course_id', $courseId);

        // Try to save the data
        if (!empty($this->data)) {
            // need to set the template_id based on the event_template_type_id
            $typeId = $this->data['Event']['event_template_type_id'];
            if ($typeId == 1) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['SimpleEvaluation'];
            } else if ($typeId == 2) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Rubric'];
            } else if ($typeId == 4) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Mixeval'];
            }

            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Add event successful!", 'good');
            } else {
                $this->Session->setFlash("Add event failed.");
            }
            $this->redirect('index/'.$courseId);
        }
    }

    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function edit($eventId)
    {
        // Check whether the course exists
        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission(), array('Course', 'Group', 'Penalty')))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('index');
            return;
        } else if ($event['Event']['event_template_type_id'] == '3') {
            // can't edit survey event from this view
            $this->redirect('/surveys/edit/'.$eventId);
            return;
        }


        if (!empty($this->data)) {
            // need to set the template_id based on the event_template_type_id
            $typeId = $this->data['Event']['event_template_type_id'];
            if ($typeId == 1) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['SimpleEvaluation'];
            } else if ($typeId == 2) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Rubric'];
            } else if ($typeId == 4) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Mixeval'];
            }

            $penaltyData = $this->Penalty->find('all', array('conditions' => array('event_id' => $eventId), 'contain' => false));
            $penalties = array();
            foreach ($penaltyData as $tmp) {
                array_splice($tmp['Penalty'], 1, -2);
                $penalties[] = $tmp['Penalty'];
            }

            isset($this->data['Penalty']) ? $formPenalty = $this->data['Penalty'] : $formPenalty = array();
            // check differences (table vs form data), delete what's missing in form data from db
            foreach ($penalties as $pTmp) {
                if (!in_array($pTmp, $formPenalty)) {
                    $this->Penalty->delete($pTmp['id']);
                }
            }
            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Edit event successful!", 'good');
                $this->redirect('index/'.$event['Event']['course_id']);
                return;
            } else {
                $this->Session->setFlash("Edit event failed.");
            }
        }

        // Sets up the already assigned groups

        $this->set('groups', $this->Group->getGroupsByCourseId($event['Event']['course_id']));
        $this->set(
            'mixevals',
            $this->Mixeval->getBelongingOrPublic(User::get('id'))
        );
        $this->set(
            'simpleEvaluations',
            $this->SimpleEvaluation->getBelongingOrPublic(User::get('id'))
        );
        $this->set(
            'rubrics',
            $this->Rubric->getBelongingOrPublic(User::get('id'))
        );

        $this->set('event', $event);
        $this->set('eventTemplateTypes', $this->EventTemplateType->find('list', array('conditions' => array('NOT' => array('id' => 3)))));
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))->push(array('event' => $event['Event']))->push(__('Edit', true)));

        $this->data = $event;
    }

    /**
     * delete
     *
     * @param int $eventId
     *
     * @access public
     * @return void
     */
    function delete ($eventId)
    {
        // Check whether the course exists
        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission(), array('Course', 'Group', 'Penalty')))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        // what's this????
        if (isset($this->params['form']['id'])) {
            $eventId = intval(substr($this->params['form']['id'], 5));

        }

        if ($this->Event->delete($eventId)) {
            $this->Session->setFlash(__('The event has been deleted successfully.', true), 'good');
            $this->redirect('index/'.$event['Event']['course_id']);
            return;
        } else {
            $this->Session->setFlash(__('Failed to delete the event', true));
            $this->redirect('index/'.$event['Event']['course_id']);
            return;
        }
    }


    /**
     * checkDuplicateTitle
     *
     * @access public
     * @return void
     */
    function checkDuplicateTitle()
    {
        $this->layout = 'ajax';
        $this->render('checkDuplicateTitle');
    }

    /**
     * viewGroups
     *
     * @param mixed $groupId
     *
     * @access public
     * @return void
     */
    function viewGroups ($groupId)
    {
        if (!is_numeric($groupId) || !($this->data = $this->Group->findGroupByid($groupId))) {
            $this->Session->setFlash(__('Group does not exist.', true));
            $this->redirect('index');
        }

        $this->layout = 'pop_up';

        //Clear $id to only the alphanumeric value
        $id = $this->Sanitize->paranoid($groupId);

        $this->set('event_id', $id);
        $this->set('assignedGroups', $this->_getAssignedGroups($groupId));
    }

    /**
     * editGroup
     *
     * @param mixed $groupId group id
     * @param mixed $eventId event id
     * @param mixed $popup   popup
     *
     * @access public
     * @return void
     */
    function editGroup($groupId, $eventId, $popup)
    {
        if (isset($popup) && $popup == 'y') {
            $this->layout = 'pop_up';
        }

        //Clear $id to only the alphanumeric value
        $id = $this->Sanitize->paranoid($groupId);
        $event_id = $this->Sanitize->paranoid($eventId);
        $this->set('event_id', $event_id);
        $this->set('group_id', $id);
        $this->set('popup', $popup);

        // gets all students not listed in the group for unfiltered box
        //$this->set('user_data', $this->Group->groupDifference($id, $courseId));

        // gets all students in the group
        $this->set('group_data', $this->Group->getMembersByGroupId($id));

        if (empty($this->params['data'])) {
            $this->Group->id = $id;
            $this->params['data'] = $this->Group->read();
        } else {
            $this->params = $this->Group->prepData($this->params);

            if ( $this->Group->save($this->params['data'])) {
                $this->GroupsMembers->updateMembers($this->Group->id, $this->params['data']['Group']);

                if (isset($popup) && $popup == 'y') {
                    $this->flash(__('Group Updated', true), '/events/viewGroups/'.$event_id, 1);
                } else {
                    $this->flash(__('Group Updated', true), '/events/view/'.$event_id, 1);
                }
            } else {
                $this->set('data', $this->params['data']);
                $this->render();
            }
        }
    }


    /**
     * _getAssignedGroups
     *
     * @param bool $eventId
     *
     * @access public
     * @return void
     */
    function _getAssignedGroups($eventId=null)
    {
        $assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($eventId);
        $assignedGroups = array();

        if (!empty($assignedGroupIDs)) {

            // retrieve string of group ids
            for ($i = 0; $i < count($assignedGroupIDs); $i++) {
                $group = $this->Group->find('first', array('conditions' => array('Group.id' => $assignedGroupIDs[$i]['GroupEvent']['group_id'])));
                //$students = $this->GroupsMembers->getMembersByGroupId($assignedGroupIDs[$i]['GroupEvent']['group_id']);
                $assignedGroups[$i] = $group['Group'];
                $assignedGroups[$i]['Member']=$group['Member'];
            }
        }

        return $assignedGroups;
    }
}
