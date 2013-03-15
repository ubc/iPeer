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
        'SimpleEvaluation', 'Rubric', 'Mixeval', 'Personalize', 'GroupsMembers', 'Penalty', 'Survey','EmailSchedule',
        'EvaluationSubmission');
    public $components = array("AjaxList", "Session", "RequestHandler","Email");

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
            $this->data = $this->_multiMap($this->data);			
			
            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Add event successful!", 'good');
			    //Call the setSchedule function to Schedule reminder emails			
			    $this->setSchedule($this->Event->id,$this->data);
                $this->redirect('index/'.$courseId);
                return;
            } else {
                $this->Session->setFlash("Add event failed.");
            }
        }
    }


	/**
	 * 
	 * setSchedule
	 * 
     * @param $eventid - id of the event for which the reminders are being set
     * @param $eventdata -  passing $this->data which contains the information for the event
	 * 
	 * @access public
	 * @return void
	 */
	 function setSchedule($eventid,$eventdata)
	 {
        $emailfreq = $eventdata['Event']['email_schedule'];
        if($emailfreq == 0){   //Return if email reminders are disable. There is no need to put the data in the table
            return;                
        }
            
        $to = $this->getGroupMembers($eventid);
        if(is_null($to)){
             return; 
        }
        
        //Get the startdate, duedate and frequency of emails
        $start_date = $eventdata['Event']['release_date_begin'];
        $duedate = $eventdata['Event']['due_date'];
        $emailfreq = '+' . $emailfreq . ' day';
        
        $eventtitle = $this -> Event -> getEventTitleById($eventid);
        $courseid = $this->Event->getCourseByEventId($eventid);
        $coursename = $this -> Course -> getCourseById($courseid);
        $coursename = $coursename['Course']['course'];
      
        //Prepare the data for pushing to the email_schedules table
        $data = array();
        $data['course_id']= $courseid;
        $data['event_id'] = $eventid;
        $data['from'] = $this->Auth->user('id');
        $data['subject'] = 'Please Submit your Ipeer Evaluation for '.$coursename.' - '.$eventtitle;
        $data['content'] = 'You have not yet submitted your Ipeer Evaluation for '.$coursename.' - '.$eventtitle.
        '. Please login to Ipeer and click on the submit button to Submit your Ipeer Eval';
        $data['to' ] = $to;

        while (strtotime($start_date) <= strtotime($duedate)) {
            $data['date'] = $start_date;
            $start_date = strtotime ($emailfreq,strtotime($start_date)) ;
            $start_date = date ( 'Y-m-j H:i:s' , $start_date );
            $data = $this->_multiMap($data);
            $this->EmailSchedule->saveAll($data);
        }

        return;
    }

     /**
     * getGroupMembers
     *
     * @param mixed $eventid
     *
     * @access public
     * @return $eventid - the event id of the concerning event
     */
     function getGroupMembers($eventid){
         //Get the groups by the eventID
        $groups = $this -> Group -> getGroupsByEventId($eventid, array());
        if(empty($groups)){
            return null;
        } 
        $groupids = array();
        //Get the assigned groupids and their resepctive members for the event
        foreach ($groups as $group) {
            array_push($groupids,$group['Group']['id' ]);
        }

        $members[] = $this->GroupsMembers->getUserListInGroups($groupids);
        $to = array();
        $to[0] = 'save_reminder';

        foreach($members[0] as $m){
            array_push($to,$m);
        }
        $to = implode(';', $to);
        
        return $to;
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
        $this->set('email_schedule',$orig_email_frequency);
        $event = $this->Event->getEventById($eventId);
        $orig_group_members = $this->getGroupMembers($eventId);


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
            $this->data = $this->_multiMap($this->data);
            if ($this->Event->saveAll($this->data)) {
                $this->Session->setFlash("Edit event successful!", 'good');
                $this->checkIfChanged($event,$this->data,$orig_email_frequency,$orig_group_members) ? $this->modifySchedule($eventId,$this->data) : 0; 
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

    /*
     * checkIfChanged
     * @param int $eventid
     * @param int $data for the event being modified
     * @param int $orig_email_frequency - the original email frequency
     * @param int $orig_group_members - the members in the group before it was modified
     *
     * @access public
     * @return bool -  return 1 if the data has been modified else returns 0
     * */
    function checkIfChanged($event,$data,$email_frequency,$orig_group_members){
        $orig_release_date_begin = $event['Event']['release_date_begin'];
        $orig_due_date = $event['Event']['due_date'];
        $new_release_date_begin = $data['Event']['release_date_begin'];
        $new_due_date = $data['Event']['due_date'];

        $new_groups = $this->getGroupMembers($data['Event']['id']);
        
        $orig_frequency = $email_frequency;
        $new_frequency =  $data['Event']['email_schedule'];
        
        if($orig_release_date_begin!=$new_release_date_begin || $orig_due_date != $new_due_date || $orig_frequency != $new_frequency){
            return 1;
        }
        else {
            return 0;
        }
        
    }
    
    /*
     * calculateFrequency
     * @param int $eventId
     *
     * @access public
     * @return int - email_frequency
     * */
     function calculateFrequency($eventId)
     {
         $count = $this->EmailSchedule->find('count', array('conditions' => array('event_id' => $eventId)));
         if($count == 0) {
             $email_frequency = 0;
         }
         else {
            $event = $this->Event->getEventById($eventId);
            $release_date_begin = strtotime($event['Event']['release_date_begin']);
            $due_date = strtotime($event['Event']['due_date']); 
            $difference = abs($release_date_begin - $due_date); //Will return difference in seconds
            //Convert difference to days  
            $days = ($difference/(60*60*24));
      
            $email_frequency = ceil($days/$count); //Might need to change floor to ceiling or something else 
         }
         return $email_frequency;
    }

    /*
     * modifySchedule
     * @param int eventId
     * @param array() data containing the data for the corresponding event
     *
     * @access public
     * @return void
     * */
     function modifySchedule($eventid,$data){
         $this->EmailSchedule->deleteAll(array('event_id' => $eventid),false);
         $event = $this->Event->getEventById($eventid);        
         $this->setSchedule($eventid, $data);
         return;
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
