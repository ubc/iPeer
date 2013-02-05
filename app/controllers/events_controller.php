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
    public $uses = array('GroupEvent', 'User', 'Group', 'Course', 'Event', 'EventTemplateType', 
        'SimpleEvaluation', 'Rubric', 'Mixeval', 'Personalize', 'GroupsMembers', 'Penalty', 'Survey');
    public $components = array("AjaxList", "Session", "RequestHandler");

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
            array(
                "1" => __("Simple", true),
                "2" => __("Rubric", true),
                "3" => __("Survey", true),
                "4" => __("Mixed", true))),
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

        // Set up actions
        $warning = __("Are you sure you want to delete this event permanently?", true);
        $actions = array(
            array("View Results", "", "", "evaluations", "view", "Event.id"),
            array("View Event", "", "", "", "view", "Event.id"),
            array("Edit Event", "", "", "", "edit", "Event.id"),
            array("View Course", "", "", "courses", "view", "Course.id"),
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
     *
     * @access public
     * @return void
     */
    function index($courseId = null)
    {
        $course = array('Course' => array('id' => $courseId));
        // check for permission to course ($courseId) only when $courseId is provided
        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (!$course) {
                $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
                $this->redirect('index');
                return;
            }
            $this->breadcrumb->push(array('course' => $course['Course']));
        }

        // We need to change the session state to point to this
        // course:
        // Initialize a basic non-functional AjaxList
        $this->AjaxList->quickSetUp();
        // Clear the state first, we don't want any previous searches/selections
        $this->AjaxList->clearState();
        // Set and update session state Variable
        $joinFilterSelections->course_id = $courseId;
        $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);

        // Set up the basic static ajax list variables
        $this->Session->write('eventsControllerCourseId', $courseId);
        $this->setUpAjaxList($courseId);
        // Set the display list
        $this->set('course', $course);
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
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
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function view ($eventId)
    {
        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission(), array('Course', 'Group.Member', 'EventTemplateType', 'Penalty' => array('order' => array('days_late ASC')))))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('index');
            return;
        }

        switch ($event['Event']['event_template_type_id']) {
        case 1:
            $modelName = 'SimpleEvaluation';
            break;
        case 2:
            $modelName = 'Rubric';
            break;
        case 3:
            $modelName = 'Survey';
            break;
        case 4:
            $modelName = 'Mixeval';
        }

        // find the related evaluation
        $evaluation = $this->$modelName->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']),
            'contain' => false
        ));

        //merge into event
        $event = array_merge($event, $evaluation);

        //Get all display event types
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))->push(array('event' => $event['Event']))->push(__('View', true)));
        $this->set('event', $event);
        $this->set('modelName', $modelName);
    }

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
            'surveys',
            $this->Survey->getBelongingOrPublic($this->Auth->user('id'))
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
            } else if ($typeId == 3) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Survey'];
            } else if ($typeId == 4) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Mixeval'];
            }

            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Add event successful!", 'good');
                $this->redirect('index/'.$courseId);
                return;
            } else {
                $this->Session->setFlash("Add event failed.");
            }
        }
    }

    /**
     * edit
     *
     * @param mixed $eventId
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
            } else if ($typeId == 3) {
                $this->data['Event']['template_id'] =
                    $this->data['Event']['Survey'];
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

        // Populate the template selections
        $this->set(
            'mixevals',
            $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'simpleEvaluations',
            $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'surveys',
            $this->Survey->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'rubrics',
            $this->Rubric->getBelongingOrPublic($this->Auth->user('id'))
        );
        $this->set(
            'eventTemplateTypes',
            $this->EventTemplateType->getEventTemplateTypeList(true)
        );
        $this->set('simpleSelected', '');
        $this->set('rubricSelected', '');
        $this->set('surveySelected', '');
        $this->set('mixevalSelected', '');
        $typeId = $event['Event']['event_template_type_id'];
        if ($typeId == 1) {
            $this->set('simpleSelected', $event['Event']['template_id']);
        } else if ($typeId == 2) {
            $this->set('rubricSelected', $event['Event']['template_id']);
        } else if ($typeId == 3) {
            $this->set('surveySelected', $event['Event']['template_id']);
        } else if ($typeId == 4) {
            $this->set('mixevalSelected', $event['Event']['template_id']);
        }

        $this->set('event', $event);
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
     * checkDuplicateName
     *
     * @param mixed $courseId course id
     * @param mixed $eventId  event id
     *
     * @access public
     * @return void
     */
    function checkDuplicateName($courseId, $eventId=null)
    {
        if (!$this->RequestHandler->isAjax()) {
            $this->cakeError('error404');
        }
        $this->layout = 'ajax';
        $this->autoRender = false;
        
        $conditions = array('Event.title' => $this->data['Event']['title'], 'Event.course_id' => $courseId);
        if (!is_null($eventId)) {
            $conditions = $conditions + array('not' => array('Event.id' => $eventId));
        }
        
        $sFound = $this->Event->find('all', array('conditions' => $conditions));
 
        return ($sFound) ? __('Event title "', true).$this->data['Event']['title'].__('" already exists in this course.', true) : '';
    }
}
