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
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time','FileUpload.FileUpload');
    public $uses = array('GroupEvent', 'User', 'Group', 'Course', 'Event', 'EventTemplateType',
        'SimpleEvaluation', 'Rubric', 'Mixeval', 'Personalize', 'GroupsMembers', 'Penalty', 'Survey','EmailSchedule',
        'EvaluationSubmission', 'EmailTemplate', 'EvaluationRubric', 'EvaluationSimple', 'EvaluationMixeval');
    public $components = array("AjaxList", "Session", "RequestHandler","Email", "Evaluation","ExportBaseNew","ExportCsv","FileUpload.FileUpload");

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $this->set('title_for_layout', __('Events', true));

        $this->FileUpload->allowedTypes(array(
            'csv' => null,
        ));
        $this->FileUpload->uploadDir(TMP);
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
        $this->FileUpload->attr('forceWebroot', false);
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
            $resultStart = strtotime($entry['Event']['result_release_date_begin']);
            $resultEnd = strtotime($entry['Event']['result_release_date_end']);
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

            if ($entry['Event']['event_template_type_id'] == 3) {
                $entry['!Custom']['resultReleased'] = 'N/A';
            } else if ($timeNow < $resultStart) {
                $entry['!Custom']['resultReleased'] = 'Not Yet';
            } else if ($timeNow >= $resultEnd) {
                $entry['!Custom']['resultReleased'] = 'Closed';
            } else if ($entry['Event']['auto_release']){
                $entry['!Custom']['resultReleased'] = 'Auto';
            } else {
                $entry['!Custom']['resultReleased'] = 'Manual';
            }

            // group count
            if ($entry['Event']['event_template_type_id'] == 3) {
                $entry['!Custom']['group_count'] = 'N/A';
            } else if ($entry['Event']['group_count'] > 0) {
                $entry['!Custom']['group_count'] = $entry['Event']['group_count'];
            } else {
                // evaluation event without any groups - warning
                $entry['!Custom']['group_count'] = "<font color='red'>0</font>";
            }

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
            array("Event.group_count", "", "", "hidden"),
            array("Course.course",        __("Course", true),      "9em", "action", "View Course"),
            array("Event.Title",          __("Title", true),       "auto", "action", "View Event"),
            array("!Custom.results",       __("View", true),       "4em", "action", "View Results"),
            array("!Custom.group_count", __("Groups", true), "2em", "string"),
            array("Event.event_template_type_id", __("Type", true), "", "map",
            array(
                "1" => __("Simple", true),
                "2" => __("Rubric", true),
                "3" => __("Survey", true),
                "4" => __("Mixed", true))),
            array("Event.due_date",       __("Due Date", true),    "10em", "date"),
            array("!Custom.isReleased",    __("Released ?", true), "8em", "string"),
            array("!Custom.resultReleased",       __("Result Released", true),   "6em", "string"),

            // Release window
            array("Event.release_date_begin", "", "",    "hidden"),
            array("Event.release_date_end",   "", "",    "hidden"),
            array("Event.result_release_date_begin", "", "", "hidden"),
            array("Event.result_release_date_end", "", "", "hidden"),
            array("Event.auto_release", "", "", "hidden"),
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

        // Surveys cannot be exported
        $restrictions['Event.event_template_type_id'] = array(1 => true, 2 => true,
            4 => true, "!default" => false);

        // Set up actions
        $warning = __("Are you sure you want to delete this event permanently?", true);
        $actions = array(
            array("View Results", "", "", "evaluations", "view", "Event.id"),
            array("View Event", "", "", "", "view", "Event.id"),
            array("Edit Event", "", "", "", "edit", "Event.id"),
            array("View Course", "", "", "courses", "view", "Course.id"),
            array("Export Results", "", $restrictions, "evaluations", "export/event", "Event.id"),
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
        $joinFilterSelections = new CakeObject();
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
            'recursive' => -1
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
        $emailReminders = array('0'=> 'Disable', '1' => '1 Day', '2'=>'2 Days','3'=>'3 Days','4'=>'4 Days','5'=>'5 Days','6'=>'6 Days','7'=>'7 Days');
        $this->set('emailTemplates', $this->EmailTemplate->getPermittedEmailTemplate(User::get('id'), 'list'));
        $this->set('emailSchedules', $emailReminders);
        $this->set('course_id', $courseId);
        $this->set('reminder_enabled', $this->SysParameter->get('email.reminder_enabled', true));

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
            $this->data = $this->_multiMap($this->data);
            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Add event successful!", 'good');
                //Call the setSchedule function to Schedule reminder emails
                $this->setSchedule($this->Event->id, $this->data);
                // set release status for group event
                $groupEvents = $this->GroupEvent->find('list',
                    array('conditions' => array('event_id'=>$this->Event->id)));
                if (isset($this->data['Event']['auto_release']) && $this->data['Event']['auto_release']) {
                    $this->Evaluation->setGroupEventsReleaseStatus($groupEvents, 'Auto');
                } else {
                    $this->Evaluation->setGroupEventsReleaseStatus($groupEvents, 'None');
                }
                $this->redirect('index/'.$courseId);
                return;
            } else {
                $this->Session->setFlash("Add event failed.");
            }
        }
    }


    /**
     * setSchedule
     *
     * @param mixed $eventId
     * @param mixed $eventData
     *
     * @access public
     * @return void
     */
     function setSchedule($eventId, $eventData)
     {
        $emailFreq = $eventData['Event']['email_schedule'];
        $to = $this->getGroupMembers($eventId); // get members
        if ($emailFreq == 0 || empty($to)) {
            return;
        }

        $emailTemp = $eventData['Event']['EmailTemplate'];
        $template = $this->EmailTemplate->findById($emailTemp);
        $subject = $template['EmailTemplate']['subject'];
        $courseId = $this->Event->getCourseByEventId($eventId);
        //Get the startdate, duedate and frequency of emails
        $startDate = strtotime($eventData['Event']['release_date_begin']) > time() ?
            $eventData['Event']['release_date_begin'] : date('Y-m-j H:i:s');
        $dueDate = $eventData['Event']['due_date'];
        $emailFreq = '+' . $emailFreq . ' day';

        $courseName = $this->Course->getCourseName($courseId);

        //Prepare the data for pushing to the email_schedules table
        $data = array();
        $data['course_id']= $courseId;
        $data['event_id'] = $eventId;
        $data['from'] = $this->Auth->user('id');
        $data['subject'] = $subject;
        $data['content'] = $emailTemp; //saving email template id
        $data['to'] = 'save_reminder;'.implode(';', $to);

        while (strtotime($startDate) < strtotime($dueDate)) {
            $data['date'] = $startDate;
            $startDate = strtotime($emailFreq, strtotime($startDate));
            $startDate = date('Y-m-j H:i:s', $startDate);
            $data = $this->_multiMap($data);
            $this->EmailSchedule->saveAll($data);
        }
     }

    /**
     * getGroupMembers
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function getGroupMembers($eventId)
    {
        $event = $this->Event->findById($eventId);
        if ($event['Event']['event_template_type_id'] == 3) {
            $class = $this->User->getEnrolledStudents($event['Event']['course_id']);

            return Set::extract('/User/id', $class);
        } else {
            //Get the groups by the eventId
            $groups = $this->GroupEvent->findAllByEventId($eventId);
            //Get the assigned groupids and their resepctive members for the event
            $groupIds = Set::extract('/GroupEvent/group_id', $groups);
            $members = $this->GroupsMembers->findAllByGroupId($groupIds);

            return Set::extract('/GroupsMembers/user_id', $members);
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

        $orig_email_frequency = $this->calculateFrequency($eventId);
        $this->set('email_schedule', $orig_email_frequency);
        $emailTemp = $this->EmailSchedule->find('first', array(
            'conditions' => array('EmailSchedule.sent' => 0, 'EmailSchedule.event_id' => $eventId)
        ));
        $this->set('emailId', $emailTemp['EmailSchedule']['content']);
        $event = $this->Event->getEventById($eventId);
        $this->set('reminder_enabled', $this->SysParameter->get('email.reminder_enabled', true));

        if (!empty($this->data) && array_key_exists('formLoaded', $this->data)) {
            if (!array_key_exists('formLoaded', $this->data)) {
                $this->Session->setFlash("Edit event failed because the form hasn't finished loading yet.");
                return;
            }
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

            // if all groups unselected - delete groupEvents
            if (empty($this->data['Group']['Group'])) {
                $this->GroupEvent->deleteAll(array('GroupEvent.event_id' => $eventId));
            }

            // update submitted evaluations release status if auto-release status has been changed
            $groupEvents = $this->GroupEvent->find('list',
                array('conditions' => array('event_id'=>$eventId)));
            if ($this->data['Event']['auto_release'] != $event['Event']['auto_release']) {
                $model = null;
                switch ($event['Event']['event_template_type_id']) {
                case 1://simple
                    //$model = 'EvaluationSimple';
                    $this->EvaluationSimple->setAllEventCommentRelease($eventId, $this->Auth->user('id'), $this->data['Event']['auto_release']);
                    $this->EvaluationSimple->setAllEventGradeRelease($eventId, $this->data['Event']['auto_release']);
                    break;
                case 2://rubric
                    $this->Evaluation->changeRubricEvalCommentRelease($this->data['Event']['auto_release'], $groupEvents);
                    $this->EvaluationRubric->setAllEventGradeRelease($eventId, $this->data['Event']['auto_release']);
                    break;
                case 4:
                    $this->Evaluation->changeMixedEvalCommentRelease($this->data['Event']['auto_release'], $groupEvents);
                    $this->EvaluationMixeval->setAllEventGradeRelease($eventId, $this->data['Event']['auto_release']);
                    break;
                }

                if ($this->data['Event']['auto_release']) {
                    $this->Evaluation->setGroupEventsReleaseStatus($groupEvents, 'Auto');
                } else {
                    $this->Evaluation->setGroupEventsReleaseStatus($groupEvents, 'None');
                }
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
            $this->data = $this->_multiMap($this->data);
            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Edit event successful!", 'good');
                if ($this->checkIfChanged($event, $this->data, $orig_email_frequency, $emailTemp)) {
                    // only delete emails that haven't been sent
                    $this->EmailSchedule->deleteAll(array('event_id' => $eventId, 'sent' => 0), false);
                    $this->setSchedule($eventId, $this->data);
                }
                $this->redirect('index/'.$event['Event']['course_id']);
                return;
            } else {
                $this->Session->setFlash("Edit event failed.");
            }
        } else if (!empty($this->data)) {
            $this->Session->setFlash("Edit event failed because the form hasn't finished loading yet.");
        }

        // Sets up the already assigned groups
        $this->set('groups', $this->Group->getGroupsByCourseId($event['Event']['course_id']));

        // Populate the template selections
        $this->set('simpleSelected', '');
        $this->set('rubricSelected', '');
        $this->set('surveySelected', '');
        $this->set('mixevalSelected', '');
        $typeId = $event['Event']['event_template_type_id'];
        $simpleSelected = NULL;
        $rubricSelected = NULL;
        $surveySelected = NULL;
        $mixevalSelected = NULL;
        if ($typeId == 1) {
            $simpleSelected = $event['Event']['template_id'];
            $this->set('simpleSelected', $event['Event']['template_id']);
        } else if ($typeId == 2) {
            $rubricSelected = $event['Event']['template_id'];
            $this->set('rubricSelected', $event['Event']['template_id']);
        } else if ($typeId == 3) {
            $surveySelected = $event['Event']['template_id'];
            $this->set('surveySelected', $event['Event']['template_id']);
        } else if ($typeId == 4) {
            $mixevalSelected = $event['Event']['template_id'];
            $this->set('mixevalSelected', $event['Event']['template_id']);
        }
        $this->set(
            'mixevals',
            $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'), $mixevalSelected)
        );
        $this->set(
            'simpleEvaluations',
            $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'), $simpleSelected)
        );
        $this->set(
            'surveys',
            $this->Survey->getBelongingOrPublic($this->Auth->user('id'), $surveySelected)
        );
        $this->set(
            'rubrics',
            $this->Rubric->getBelongingOrPublic($this->Auth->user('id'), $rubricSelected)
        );
        $this->set(
            'eventTemplateTypes',
            $this->EventTemplateType->getEventTemplateTypeList(true)
        );
        $emailReminders = array('0'=> 'Disable', '1' => '1 Day', '2'=>'2 Days','3'=>'3 Days','4'=>'4 Days','5'=>'5 Days','6'=>'6 Days','7'=>'7 Days');
        $this->set('emailTemplates', $this->EmailTemplate->getPermittedEmailTemplate(User::get('id'), 'list'));
        $this->set('emailSchedules', $emailReminders);

        $this->set('event', $event);
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))->push(array('event' => $event['Event']))->push(__('Edit', true)));

        $this->data = $event;
    }

    /**
     * checkIfChanged
     *
     * @param mixed $event
     * @param mixed $data
     * @param int $email_frequency
     * @param int $originalTemp   original template id
     *
     * @access public
     * @return bool -  return 1 if the data has been modified else returns 0
     **/
    function checkIfChanged($event, $data, $email_frequency, $originalTemp)
    {
        $orig_release_date_begin = $event['Event']['release_date_begin'];
        $orig_due_date = $event['Event']['due_date'];
        $new_release_date_begin = $data['Event']['release_date_begin'];
        $new_due_date = $data['Event']['due_date'];
        $orig_frequency = $email_frequency;
        $new_frequency =  $data['Event']['email_schedule'];
        $new_template = $data['Event']['EmailTemplate'];

        if ($orig_release_date_begin != $new_release_date_begin || $orig_due_date != $new_due_date
            || $orig_frequency != $new_frequency || $originalTemp != $new_template) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * calculateFrequency
     *
     * @param int $eventId
     *
     * @access public
     * @return int - email_frequency
     **/
    function calculateFrequency($eventId)
    {
        $schedule = $this->EmailSchedule->find('all', array(
            'conditions' => array('event_id' => $eventId),
            'order' => 'date DESC'
        ));
        $count = count($schedule);
        if ($count <= 0) {
            $email_frequency = 0;
        } else if ($count == 1) {
            $email_frequency = 7; // set to 7 days
        } else {
            // last two emails
            $last = strtotime($schedule[0]['EmailSchedule']['date']);
            $second = strtotime($schedule[1]['EmailSchedule']['date']);
            $diff = $last - $second;
            $email_frequency = ceil($diff/(60*60*24));
        }
        return $email_frequency;
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

    /**
     * export 
     *
     * Exports the event listings for a course in csv format
     * Intended to be used in tandem with /events/import
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function export($courseId)
    {
        
        if (!is_numeric($courseId)) {
            $this->Session->setFlash(__('Error: Invalid course id', true));
            $this->redirect('/courses');
            return;
        }
        
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }
        
        $this->breadcrumb->push(array('course' => $course['Course']));
        $this->set('breadcrumb', $this->breadcrumb->push(__('Export Events', true)));
        
        $fileName = "Events-" . date('m.y') . "-" . str_replace(' ', '.', $course['Course']['course']);
        
        // form submission
        if (isset($this->params['form']) && !empty($this->params['form'])) {
            $this->autoRender = false;
            
            $fileName = isset($this->params['form']['file_name']) && !empty($this->params['form']['file_name']) ? $this->params['form']['file_name']: $fileName;
            
            if(!$this->ExportCsv->exportCSV($this->Event->csvExportEventsByCourseId($courseId),$fileName)) {
                $this->autoRender = true;
                $this->Session->setFlash(__('Error: could not generate csv', true));
            };
            
        }
        
        $this->set('file_name', $fileName);
    }
    
    /**
     * Imports the event listings for a course from csv format
     *
     * Intended to be used in tandem with /events/export
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function import($courseId)
    {   
        if (!is_numeric($courseId)) {
            $this->Session->setFlash(__('Error: Invalid course id', true));
            $this->redirect('/courses');
            return;
        }
        
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }
        
        $this->breadcrumb->push(array('course' => $course['Course']));
        $this->set('breadcrumb', $this->breadcrumb->push(__('Import Events', true))); 
        $this->set('groups', $this->Group->getGroupsByCourseId($courseId));

        
        // File got uploaded
        // ensure file got submitted!
        if (!empty($this->params['form'])) {
            // did the file submit properly
            if ($this->FileUpload->success) {
                $uploadFile = $this->FileUpload->uploadDir.DS.$this->FileUpload->finalFile;
            } else {
                $this->Session->setFlash($this->FileUpload->showErrors());
                return;
            }
            
            // attempt to parse the csv
            $eventData = Toolkit::parseCSV($uploadFile);
            
            // we can now remove the file so we can "return" from the function whenever
            $this->FileUpload->removeFile($uploadFile);
            
            // something wrong with the file
            if(empty($eventData) || empty($eventData[0])) { 
                $this->Session->setFlash(__('Error: The file got uploaded, but could not be processed as a csv.',true));
                return;
            }
            
            // attempt to import
            $importResult = $this->Event->importEventsByCsv($courseId,$eventData,User::get('id'));
            
            if(is_numeric($importResult)) {
                // success
                $this->Session->setFlash("$importResult event(s) were imported successfully.", 'good');
                $this->redirect('index/'.$courseId);
            } else {
                // failure
                $this->Session->setFlash("Import Failed <br />" . $importResult);
                return;
            }
            
        }

        
    }
    
    /**
     * _multiMap
     *
     * @param mixed $data data
     *
     * @access private
     * @return void
     */
    function _multiMap($data)
    {
        $ret = array();
        foreach($data as $key => $value) {
            $ret[$key] = is_array($value) ? $this->_multiMap($value) : trim($value);
        }
        return $ret;
    }
}
