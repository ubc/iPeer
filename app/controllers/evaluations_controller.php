<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

/**
 * EvaluationsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationsController extends AppController
{
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Evaluation');
    public $Sanitize;

    public $uses = array('SurveyQuestion', 'GroupEvent', 'EvaluationRubric',
        'EvaluationRubricDetail',
        'EvaluationSubmission', 'Event', 'EvaluationSimple',
        'SimpleEvaluation', 'Rubric', 'Group', 'User', 'UserEnrol', 'UserCourse',
        'GroupsMembers', 'RubricsLom', 'RubricsCriteria',
        'RubricsCriteriaComment', 'Personalize', 'Penalty',
        'Question', 'Response', 'Survey', 'SurveyInput', 'Course',
        'MixevalQuestion', 'MixevalQuestionType',
        'EvaluationMixeval', 'EvaluationMixevalDetail', 'Mixeval',
        'MixevalQuestionDesc');
    public $components = array('ExportBaseNew', 'Auth', 'AjaxList', 'Output',
        'userPersonalize', 'framework',
        'Evaluation', 'Export', 'ExportCsv', 'ExportExcel', 'ExportPdf',
        'RequestHandler');

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

        $this->set('title_for_layout', __('Evaluations', true));
    }

    /**
     * postProcess
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function _postProcess($data)
    {
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                $custom = array();

                $groupID = $entry['Group']['id'];
                $groupEventID = $entry['GroupEvent']['id'];
                $eventID = $entry['Event']['id'];
                $completedEvaluations =
                    $this->EvaluationSubmission->numCountInGroupCompleted($groupEventID);
                $totalMembers =
                    $this->GroupsMembers->find('count', array(
                        'conditions' => array('group_id' => $groupID)));

                $custom['completion'] = "<center><img border=0 src='" . $this->webroot . "img/icons/" .
                    // Display Check or X for completion
                    (($completedEvaluations == $totalMembers) ? "green_check.gif" : "red_x.gif")
                    . "'>&nbsp;&nbsp;&nbsp;<b>$completedEvaluations</b> / <b>$totalMembers </b></center>";

                $custom['results'] = __('Results', true);

                // Include missing submissions into the lates
                $lates = $this->GroupEvent->getLateGroupMembers($groupEventID) +
                    ($totalMembers - $completedEvaluations);

                // Is it time for this event to be late yet?
                $eventIsNowLate = $this->Event->checkIfNowLate($eventID);

                if ($eventIsNowLate) {
                    $custom['lates'] = ($lates > 0) ? " <b>$lates</b> ".__("Late", true) : __("No Lates", true);
                } else {
                    $custom['lates'] = __("Not Yet", true);
                }

                $data[$key]['!Custom'] = $custom;

                // Make the groups a little easier to read
                $groupNumber = "Group #" . $data[$key]['Group']['group_num'];
                $data[$key]['Group']['group_num'] = $groupNumber;
            }
        }
        // Return the processed data back
        return $data;
    }


    /**
     * setUpAjaxList
     *
     * @param mixed $eventId
     *
     * @access public
     * @return void
     */
    function setUpAjaxList ($eventId)
    {
        // The columns to show
        $columns = array(
            //    Model   columns       (Display Title) (Type Description)
            array("GroupEvent.id",     "",           "",    "hidden"),
            array("Group.id",          "",           "",    "hidden"),
            array("Group.group_num",   __("Group #", true),    "7em", "action", "View Group"),
            array("Group.group_name",  __("Group Name", true), "auto", "action", "View Results"),
            array("!Custom.completion", __("Completed", true),  "6em", "string"),
            array("!Custom.results",    __("View", true),      "4em", "action", "View Results"),
            array("!Custom.lates",     __("Late?", true),      "7em", "action", "View Submission"),

            // Release and mark status
            array("GroupEvent.marked", __("Status", true),      "7em",  "map",
            array("not reviewed" => __("Not Reviewed", true), "to review" => "To Review",
            "reviewed" => __("Reviewed", true))),
            array("GroupEvent.grade_release_status",__("Grade", true), "7em",   "map",
            array("None" => __("Not Released", true), "Some" => __("Some Released", true),
                "Auto" => __("Auto-Release", true), "All" => __("Released", true))),
            array("GroupEvent.comment_release_status", __("Comment", true), "7em",   "map",
            array("None" => __("Not Released", true), "Some" => __("Some Released", true),
                "Auto" => __("Auto-Release", true), "All" => __("Released", true))),

            // Extra info about course and Event
            array("Event.id", "", "", "hidden"),
        );

        $extraFilters = array();
        if (!empty($eventId)) {
            $extraFilters = array('Event.id' => $eventId);
        }

        $actions = array(
            //   parameters to cakePHP controller:,
            //   display name, (warning shown), fixed parameters or Column ids
            array(__("View Results", true),    "", "", "", "viewEvaluationResults", "Event.id", "Group.id"),
            array(__("View Submission", true), "", "", "", "viewGroupSubmissionDetails", "Event.id", "Group.id"),
            array(__("View Group", true),      "", "", "groups", "view", "Group.id"),
            array(__("View Event", true),      "", "", "events", "view", "Event.id"),
            array(__("Edit Event", true),      "", "", "events", "edit", "Event.id"),
        );

        $recursive = 0;

        $this->AjaxList->setUp($this->GroupEvent, $columns, $actions,
            "Group.group_num", "Group.group_name",
            array(), $extraFilters, $recursive, "_postProcess");
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
        $eventId = $this->Session->read("evaluationsControllerEventIdSession");
        $this->setUpAjaxList($eventId);
        // Process the request for data
        $this->AjaxList->asyncGet("view");
    }


    /**
     * View
     *
     * @param <type> $eventId Event Id
     */
    function view($eventId = null)
    {
        // Record the event id into the session
        if (!empty($eventId) && is_numeric($eventId)) {
            $this->Session->write("evaluationsControllerEventIdSession", $eventId);
        } else {
            // Use last event ID if none was passed with a parameter
            $eventId = $this->Session->read("evaluationsControllerEventIdSession");
        }

        if (!($event = $this->Event->getAccessibleEventById($eventId,
            User::get('id'), User::getCourseFilterPermission(), array('Course')))) {

            $this->Session->setFlash(__('Error: Invalid id or you do not have permission to access this event.', true));
            $this->redirect('index');
            return;
        }

        // Survey Results are on a different page for now
        if ($event['Event']['event_template_type_id'] == 3) {
            $this->redirect("viewSurveySummary/$eventId");
            return;
        }

        // Set up the basic static ajax list variables
        $this->setUpAjaxList($eventId);

        // set status whether instructors can view un/release comments & grades
        $viewReleaseBtns = time() >= strtotime($event['Event']['release_date_end']);

        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());

        $this->set('data', $event);
        $this->set('viewReleaseBtns', $viewReleaseBtns);
        $this->set('canvasEnabled', in_array($this->SysParameter->get('system.canvas_enabled', 'false'), array('1', 'true', 'yes')));
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))
            ->push(array('event' => $event['Event']))
            ->push(__('Results', true)));
    }

    // =-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-

    /**
     * Index
     *
     */
    function index ()
    {
        // Evaluation index was merged with events ajaxList
        $this->redirect('/events/index');
        return;
    }

    /**
     * export
     *
     * @param mixed $type course or event
     * @param mixed $id   course/event id
     *
     * @access public
     * @return void
     */
    function export($type, $id)
    {
        // increase the execution time
        ini_set('max_execution_time', 600);

        // $type must be course or event
        if ('course' != $type && 'event' != $type) {
            $this->Session->setFlash('Error: Invalid export type', true);
            $this->redirect('/courses');
            return;
        } else if (!is_numeric($id)) {
            $this->Session->setFlash(__('Error: Invalid id', true));
            $this->redirect('/courses');
            return;
        }

        $this->set('type', $type);
        $fileTypes = array('csv' => 'csv', 'pdf' => 'pdf');
        //$fileTypes = array('csv' => 'csv');
        $this->set('fileTypes', $fileTypes);

        if ('course' == $type) {
            $courseId = $id;

            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (!$course) {
                $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
                $this->redirect('/courses');
                return;
            }

            $events = $this->Event->getCourseEvalEvent($id);

            $this->set('events', $events);
            $this->set('fromEvent', false);
            $this->breadcrumb->push(array('course' => $course['Course']));

        } else if ('event' == $type) {
            $courseId = $this->Event->getCourseByEventId($id);

            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (!$course) {
                $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
                $this->redirect('/courses');
                return;
            }

            $selectedEvent = $this->Event->getEventById($id);

            if ($selectedEvent['Event']['event_template_type_id'] == '3') {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/courses');
                return;
            }

            $this->set('selectedEvent', $selectedEvent);
            $this->set('fromEvent', true);
        }

        $this->set('id', $id);
        $this->set('breadcrumb', $this->breadcrumb->push(__('Export Evaluation Results', true)));

        //do stuff
        if (isset($this->params['form']) && !empty($this->params['form'])) {
            $this->autoRender = false;

            if (!($event = $this->Event->getAccessibleEventById($this->params['form']['event_id'], User::get('id'), User::getCourseFilterPermission(), array('Course' => array('Instructor'), 'GroupEvent', 'EventTemplateType', 'Penalty' => array('order' => array('days_late ASC')))))) {
                $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
                $this->redirect('index');
                return;
            }

            $fileName = isset($this->params['form']['file_name']) && !empty($this->params['form']['file_name']) ? $this->params['form']['file_name']:date('m.d.y');

            switch($this->params['form']['export_type']) {
                case "csv" :
                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename=' . $fileName . '.csv');
                    $this->ExportCsv->createCsv($this->params['form'], $event);
                    break;
                case "pdf":
                    $this->ExportPdf->createPdf($this->params['form'], $event);
                    break;
                case "excel" :
                    $this->ExportExcel->createExcel($this->params['form'], $event);
                    break;
                default :
                    throw new Exception("Invalid evaluation selection.");
            }
            $this->log('Memory Usage for exporting event '.$event['Event']['title'].': '.memory_get_peak_usage(), 'debug');
        } else {
            // Set up data
            $this->set('file_name', date('m.d.y'));
        }
    }

    function exportCanvas($eventId, $progressId=false) {
        $canvasEnabled = in_array($this->SysParameter->get('system.canvas_enabled', 'false'), array('1', 'true', 'yes'));

        if (!$canvasEnabled){
            $this->Session->setFlash(__('Error: Canvas integration not enabled.', true));
            $this->redirect('index');
        }

        App::import('Component', 'CanvasCourse');
        App::import('Component', 'CanvasCourseAssignment');

        // if progress id is passed in, we return a json object with the progress of this export
        if ($progressId) {

            $result = array();
            $statusCode = 'HTTP/1.1 400 Bad Request'; // unrecognized request type

            if ($this->RequestHandler->isGet()) {
                $result = CanvasCourseComponent::getProgress($this, User::get('id'), $progressId);
                $statusCode = 'HTTP/1.1 200 OK';
            }

            header('Content-Type: application/json');
            header($statusCode);
            if ($result == null) {
                $result = array();
            }
            $json = json_encode($result);
            header("Content-length: ".strlen($json));
            echo $json;
            die;
        }

        // Get event
        $event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission());
        if (!$event) {
            $this->Session->setFlash(__('Error: Event does not exist or you do not have permission to view this event.', true));
            $this->redirect('index');
            return;
        }
        $event = $event['Event'];

        $this->set('eventName', $event['title']);
        $this->set('eventIdBack', $eventId);

        // Get course
        $courseId = $this->Event->getCourseByEventId($eventId);
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: The associated course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }
        $course = $course['Course'];

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course))
             ->push(array('event' => $event))
             ->push(__('Push Grades to Canvas', true)));

        // trigger the Canvas api here so that if we are returning from Canvas authorization with a GET method,
        // the token got saved in both iPeer and Canvas
        if (!$course['canvas_id']) {
            $this->Session->setFlash(__('Error: You need to first associate this course with a Canvas course in order to push grades. You can do so from the course Edit screen.', true));
            $this->redirect('index');
            return;
        }
        $canvasCourse = CanvasCourseComponent::getById($this, User::get('id'), $course['canvas_id']);

        // if form is submitted, process it
        if (isset($this->params['form']['submit'])) {

            $exportReport = array();
            $errorCount = 0;

            $gradeExportProgress = false;
            $this->set('canvasProgressId', false);

            // Check Canvas course
            if (!$canvasCourse) {
                $this->Session->setFlash(__('Error: Canvas course not found or you do not have access to it.', true));
                $this->redirect('index');
                return;
            }
            $this->set('canvasCourseUrl', CanvasCourseComponent::getCourseUrl(User::get('id'), $course['canvas_id']));

            $eventTemplateType = $this->Event->getEventTemplateTypeId($eventId);

            $points_possible = 0;
            if ($eventTemplateType == 1) {
                $simpleEvaluation = $this->SimpleEvaluation->find('first', array(
                    'conditions' => array('id' => $event['template_id']),
                    'contain' => false,
                ));
                $points_possible = $simpleEvaluation['SimpleEvaluation']['point_per_member'];
            } else if ($eventTemplateType == 2) {
                $rubric = $this->Rubric->getRubricById($event['template_id']);
                $points_possible = $rubric['Rubric']['total_marks'];
            } else if ($eventTemplateType == 4) {
                $mixeval = $this->Mixeval->getEvaluation($event['template_id']);
                $points_possible = $mixeval['Mixeval']['total_marks'];
            }

            $scores = array();
            $key = "";

            $fields = array('id', 'evaluatee', 'evaluator', 'score');
            $conditions = array('event_id' => $eventId);
            if ($eventTemplateType == 1) {
                $scores = $this->EvaluationSimple->simpleEvalScore($eventId, $fields, $conditions);
                $key = "EvaluationSimple";
            } else if ($eventTemplateType == 2) {
                $scores = $this->EvaluationRubric->rubricEvalScore($eventId, $conditions);
                $key = "EvaluationRubric";
            } else if ($eventTemplateType == 4) {
                $scores = $this->EvaluationMixeval->mixedEvalScore($eventId, $fields, $conditions);
                $key = "EvaluationMixeval";
            }

            $grades = array();
            if (count($scores)) {

                $user_ids = Set::classicExtract($scores, "{n}.$key.evaluatee", $scores);
                $usernames = $this->User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.username')));
                $canvasUsers = $canvasCourse->getUsers($this, User::get('id'), array(CanvasCourseUserComponent::ENROLLMENT_QUERY_STUDENT));

                foreach ($scores as &$score) {
                    if(isset($usernames[$score[$key]['evaluatee']]) ) {
                        $username = $usernames[$score[$key]['evaluatee']];
                        if (isset($canvasUsers[$username])) {
                            $canvasUserId = $canvasUsers[$username]->id;
                            $grades[$canvasUserId] = $score[$key]['score'];
                        }
                        else {
                            $exportReport[] = 'User with username "' . $username . '" does not have an associated account in Canvas, so their grade was not exported.';
                            $errorCount++;
                        }
                    } else {
                        $exportReport[] = 'User with iPeer id ' . $score[$key]['evaluatee'] . ' was not found, so their grade was not exported.';
                        $errorCount++;
                    }
                }
            }

            if ($points_possible && count($grades)) {

                // Get Canvas assignment
                $canvasAssignment = null;
                if ($event['canvas_assignment_id']) {
                    $canvasAssignment = $canvasCourse->getAssignment($this, User::get('id'), $event['canvas_assignment_id']);
                }

                // Create assignment in Canvas if no assignment already associated or if assignment does not exist in Canvas
                if (!$canvasAssignment) {
                    $assignmentArgs = array(
                        'integration_id' => $eventId,
                        'name' => $event['title'],
                        'description' => $event['description'],
                        'published' => true,
                        'grading_type' => 'points',
                        'points_possible' => $points_possible
                    );
                    $canvasAssignment = $canvasCourse->createAssignment($this, User::get('id'), $assignmentArgs, "iPeer Evaluations");

                    if (!$canvasAssignment) {
                        $exportReport[] = 'There was a problem creating the assignment in Canvas. Please try again later.';
                        $errorCount++;
                    }
                    else {
                        // save the canvas assignment association with this event
                        $event['canvas_assignment_id'] = $canvasAssignment->id;
                        $this->Event->id = $eventId;
                        $this->Event->save(array('Event' => $event));

                        if ($event['canvas_assignment_id']) {
                            $exportReport[] = 'The previously associated assignment for the event "' . $event['title'] . '" could not be found in Canvas, so a new one was created.';
                        }
                        else {
                            $exportReport[] = 'A new assignment was created in Canvas for the event "' . $event['title'] . '".';
                        }

                        // update the newly created Canvas assignment with manual posting policy
                        try {
                            $mute_result = $canvasAssignment->setAssignmentPostPolicy($this, User::get('id'), true);
                        } catch (Exception $e) {
                            err_log('Problem marking the assignment grade for manual release');
                            error_log($e->getMessage());
                        }
                    }
                }

                // Export grades
                if ($canvasAssignment) {

                    $gradeExportProgress = $canvasAssignment->grade($this, User::get('id'), $grades);

                    if (!$gradeExportProgress) {
                        $exportReport[] = 'Grades could not be exported.';
                        $errorCount++;
                    }
                    else {
                        $this->set('canvasProgressId', $gradeExportProgress->id);
                    }
                }
            }
            elseif (count($grades)) {
                $exportReport[] = 'This evaluation type cannot be exported.';
                $errorCount++;
            }
            else {
                $exportReport[] = 'There are no grades that can be exported.';
                $errorCount++;
            }

            if ($errorCount > 0) {
                $supportEmail = $this->SysParameter->get('display.contact_info');
                $exportReport[] = 'If you are having issues with the export and require assistance, please <a href="mailto:' . $supportEmail .
                                  '?subject=Problem using iPeer Canvas grade sync feature">contact support</a>.';
            }

            $this->set('exportReportDetails', $exportReport);
            $this->set('exportDetails', $gradeExportProgress);
        }
    }

    /**
     * makeEvaluation proxy method for makeing different evaluations
     *
     * @param mixed $eventId   event id
     * @param mixed $objectId  object id
     * @param mixed $studentId student id
     *
     * @access public
     * @return void
     */
    function makeEvaluation($eventId, $objectId = null, $studentId = null) {
        // invalid event ids
        if (!is_numeric($eventId) || null == ($event = $this->Event->getEventById($eventId))) {
            $this->Session->setFlash(__('Error: Invalid Id', true));
            $this->redirect('/home/index');
            return;
        }

        $this->Event->id = $eventId;
        $templateTypeId = $this->Event->field('event_template_type_id');
        switch($templateTypeId) {
        case 1:
            $this->_makeSimpleEvaluation($event, $objectId, $studentId);
            break;
        case 2:
            $this->_makeRubricEvaluation($event, $objectId, $studentId);
            break;
        case 3:
            $this->_makeSurveyEvaluation($event, $studentId);
            break;
        case 4:
            $this->_makeMixevalEvaluation($event, $objectId, $studentId);
            break;
        }
    }

    /**
     * sendConfirmationEmail
     *
     * @access public
     * @return void
     */
    function _sendConfirmationEmail()
    {
        $email = User::get('email');
        if (empty($email)) {
            return;
        }

        /*if (!$this->TemplateEmail->sendByTemplate(array(User::get('id') => $email), 'Submission Confirmation')) {
            $this->log('Sending email to '.$email.' failed.'. $this->TemplateEmail->smtpError);
            $this->Session->setFlash('Sending confirmation email failed!');
        }*/
    }

    /**
     * makeSimpleEvaluation
     *
     * @param mixed $event     event object
     * @param mixed $groupId   group id
     * @param mixed $studentId student id
     *
     * @access public
     * @return void
     */
    function _makeSimpleEvaluation($event, $groupId, $studentId = null)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];

        if (empty($this->params['data'])) {
            $userId = User::get('id');

            $grpMem = $this->GroupsMembers->find('first', array(
                'conditions' => array('GroupsMembers.user_id' => empty($studentId) ? $userId : $studentId,
                    'GroupsMembers.group_id' => $groupId)));

            // filter out users that don't have access to this eval, invalid ids
            if (empty($grpMem)) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
            }

            $now = time();
            // students can't submit outside of release date range
            if ($now < strtotime($event['Event']['release_date_begin']) ||
                $now > strtotime($event['Event']['release_date_end'])) {
                $this->Session->setFlash(__('Error: Evaluation is unavailable', true));
                $this->redirect('/home/index');
                return;
            }

            // students can submit again
            $submission = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, empty($studentId) ? User::get('id') : $studentId);
            if (!empty($submission)) {
                // load the submitted values
                $evaluation =  $this->EvaluationSimple->getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, empty($studentId) ? User::get('id') : $studentId);
                foreach ($evaluation as $eval) {
                    $this->data['Evaluation']['point'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['score'];
                    $this->data['Evaluation']['comment_'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['comment'];
                }
            }

            //Get the target event
            $eventId = $this->Sanitize->paranoid($eventId);
            $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
            $this->set('event', $event);


            $penalty = $this->Penalty->getPenaltyByEventId($eventId);
            $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
            $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
            $this->set('penaltyFinal', $penaltyFinal);
            $this->set('penaltyDays', $penaltyDays);
            $this->set('penalty', $penalty);


            //Setup the courseId to session
            $this->set('courseId', $event['Event']['course_id']);
            $courseId = $event['Event']['course_id'];
            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));

            //Set userId, first_name, last_name
            $this->set('userId', empty($studentId) ? $userId : $studentId);
            $this->set('fullName', $this->Auth->user('full_name'));


            //Get Members for this evaluation
            $groupMembers = $this->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
            $this->set('groupMembers', $groupMembers);

            // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
            $numMembers = count($groupMembers);
            $simpleEvaluation = $this->SimpleEvaluation->find('first', array(
                'conditions' => array('id' => $event['Event']['template_id']),
                'contain' => false,
            ));
            $remaining = $simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers;
            //          if ($in['points']) $out['points']=$in['points']; //saves previous points
            //$points_to_ratio = $numMembers==0 ? 0 : 1 / ($simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers);
            //          if ($in['comments']) $out['comments']=$in['comments'];

            $this->set('remaining', $remaining);
            $this->set('evaluateeCount', $numMembers);
            $this->set('courseId', $courseId);
            $this->render('simple_eval_form');
        } else {
            $eventId = $this->params['form']['event_id'];
            $groupId = $this->params['form']['group_id'];
            $courseId = $this->params['form']['course_id'];
            $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
            // check that all points given are not negative numbers
            $minimum = min($this->params['form']['points']);
            if ($minimum < 0) {
                $this->Session->setFlash(__('One or more of your group members have negative points. Please use positive numbers.', true));
                $this->redirect("/evaluations/makeEvaluation/$eventId/$groupId");
                return;
            }

            //Get the target event
            $this->Event->id = $eventId;
            $event = $this->Event->read();

            //Get simple evaluation tool
            $this->SimpleEvaluation->id = $event['Event']['template_id'];

            //Get the target group event
            $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);

            //Get the target event submission
            $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEvent['GroupEvent']['id'],
                $evaluator);
            $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];

            if ($this->Evaluation->saveSimpleEvaluation($this->params, $groupEvent, $evaluationSubmission)) {
                CaliperHooks::submit_simple_evaluation($eventId, $evaluator, $groupEvent['GroupEvent']['id'], $groupId);

                $this->Session->setFlash(__('Your Evaluation was submitted successfully.', true), 'good');
                $this->redirect('/home/index/', true);
            } else {
                //Found error
                //Validate the error why the Event->save() method returned false
                $this->validateErrors($this->Event);
                $this->set('errmsg', __('Save Evaluation failure.', true));
                $this->redirect("/evaluations/makeEvaluation/$eventId/$groupId");
            }//end if
        }
    }

    /**
     * This is where students go to take surveys. Loads and passes data to
     * the view for displaying the survey questions and then process the
     * form submit when the student is done.
     *
     * NOTE: If save fails, the student has to re-enter all data, there is
     * no persistence available unlike other forms. This is due to how we
     * redirect to evaluation/makeEvaluation on failure.
     *
     * @param mixed $event     event - The survey event that we're processing.
     * @param mixed $studentId student id
     *
     * @access public
     * @return void
     */
    function _makeSurveyEvaluation ($event, $studentId = null)
    {
        //TODO Move validation to parent, since it's shared among all
        $surveyId = $event['Event']['template_id'];
        $eventId = $event['Event']['id'];
        $userId = $this->Auth->user('id');
        $courseId = $event['Event']['course_id'];
        if (empty($studentId)) {
            // Make sure user is a student in this course
            $ret = $this->UserEnrol->field('id',
                array('course_id' => $courseId, 'user_id' => $userId ));
        } else {
            // Make sure user has access to the course (eg. instructor, admin)
            $ret = $this->Course->getAccessibleCourseById($courseId, $userId,
                User::getCourseFilterPermission(), array('Instructor', 'Department'));
        }
        if (!$ret) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
        }
        // Students can't submit outside of release date range
        $now = time();
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->Session->setFlash(__('Error: Survey is unavailable', true));
            $this->redirect('/home/index');
            return;
        }
        // TODO Can't submit surveys twice due to no data persistence in forms
        // i.e.: We don't load previously submitted data into the survey form
        $sub = $this->EvaluationSubmission->
            getEvalSubmissionByEventIdSubmitter($eventId, $userId);
        if (!empty($sub)) {
            $this->Session->setFlash(
                __('Error: Survey has already been submitted', true));
            $this->redirect('/home/index');
            return;
        }

        // Process form submit
        if (!empty($this->data)) {
            // We need an evaluation submission entry
            $sub['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $userId : $studentId;
            $sub['EvaluationSubmission']['submitted'] = 1;
            $sub['EvaluationSubmission']['date_submitted'] =
                date('Y-m-d H:i:s');
            $sub['EvaluationSubmission']['event_id'] = $eventId;
            // Cakephp doesn't know how to deal with the checkboxes response for
            // "Choose any of..." questions, so have to manually correct data
            // to create a separate SurveyInput entry for each selected chkbox
            $chkboxResps = array();
            foreach ($this->data['SurveyInput'] as $key => &$input) {
                if (!isset($input['response_id'])) {
                    continue;
                }
                if (is_array($input['response_id'])) {
                    // the "Choose any of..." questions that has chkboxes
                    foreach ($input['response_id'] as $respId) {
                        $tmp = array();
                        $tmp['event_id'] = $input['event_id'];
                        $tmp['user_id'] = $input['user_id'];
                        $tmp['question_id'] = $input['question_id'];
                        $tmp['response_id'] = $respId;
                        $tmp['response_text'] = $this->Response->field(
                            'response', array('id' => $respId));
                        $chkboxResps[] = $tmp;
                    }
                    unset($this->data['SurveyInput'][$key]);
                }
                else {
                    // "Multiple choice" question, just fill in response text
                    $input['response_text'] = $this->Response->field(
                        'response', array('id' => $input['response_id']));
                }
            }
            $this->data['SurveyInput'] = array_merge($this->data['SurveyInput'],
                $chkboxResps);
            // Try to save SurveyInput first and then the EvaluationSubmission
            // since SurveyInput fails safe (we'll just have some extra entries)
            if ($this->SurveyInput->saveAll($this->data['SurveyInput']) &&
                $this->EvaluationSubmission->save($sub)
            ) {
                CaliperHooks::submit_survey($eventId, $userId);
                $this->Session->setFlash(
                    __('Your survey was submitted successfully!', true), 'good');
                $this->redirect('/home/index/');
                return;
            } else {
                $this->Session->setFlash(
                    __('Survey save failed, please try again.', true));
                $this->redirect('evaluations/makeEvaluation/'.$eventId);
                return;
            }
        }

        // Display questions
        $this->set('title_for_layout',
            $this->Course->getCourseName($courseId, 'S').__(' > Survey', true));

        // Get all required data from each table for every question
        $this->set('questions', $this->Survey->getQuestions($surveyId));
        $this->set('event', $this->Event->findById($eventId));
        $this->set('userId', $userId);
        $this->set('studentId', $studentId);
        $this->set('eventId', $event['Event']['id']);
        $this->render('survey_eval_form');
    }

    /**
     * makeRubricEvaluation
     *
     * @param mixed $event     event object
     * @param mixed $groupId   group id
     * @param mixed $studentId student id
     *
     * @access public
     * @return void
     */
    function _makeRubricEvaluation ($event, $groupId, $studentId = null)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];

        if (empty($this->params['data'])) {
            $rubricId = $event['Event']['template_id'];
            $courseId = $event['Event']['course_id'];

            $groupEvents = $this->GroupEvent->findAllByEventId($eventId);
            $groups = Set::extract('/GroupEvent/group_id', $groupEvents);

            // if group id provided does not match the group id the user belongs to or
            // template type is not rubric - they are redirected
            if (!is_numeric($groupId) || !in_array($groupId, $groups) ||
                !$this->GroupsMembers->checkMembershipInGroup($groupId, empty($studentId) ? User::get('id') : $studentId)) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
            }

            // students can't submit outside of release date range
            $now = time();

            if ($now < strtotime($event['Event']['release_date_begin']) ||
                $now > strtotime($event['Event']['release_date_end'])) {
                $this->Session->setFlash(__('Error: Evaluation is unavailable', true));
                $this->redirect('/home/index');
                return;
            }

            $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
            $this->set('event', $event);

            $data = $this->Rubric->getRubricById($rubricId);

            $penalty = $this->Penalty->getPenaltyByEventId($eventId);
            $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
            $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
            $this->set('penaltyFinal', $penaltyFinal);
            $this->set('penaltyDays', $penaltyDays);
            $this->set('penalty', $penalty);

            $this->set('data', $data);

            //Setup the viewData
            $rubricId = $event['Event']['template_id'];
            $rubric = $this->Rubric->getRubricById($rubricId);
            $rubricEvalViewData = $this->Rubric->compileViewData($rubric);
            $this->set('viewData', $rubricEvalViewData);
            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));

            $rubricDetail = $this->Evaluation->loadRubricEvaluationDetail($event, $studentId);
            $this->set('groupMembers', $rubricDetail['groupMembers']);
            $this->set('userIds', implode(',', Set::extract('/User/id', $rubricDetail['groupMembers'])));
            $this->set('evaluateeCount', $rubricDetail['evaluateeCount']);

            $evaluated = 0; // # of group members evaluated
            $commentsNeeded = false;
            foreach ($rubricDetail['groupMembers'] as $row) {
                $user = $row['User'];
                if (isset($user['Evaluation'])) {
                    foreach ($user['Evaluation']['EvaluationRubricDetail'] as $eval) {
                        if (!$commentsNeeded && empty($eval['criteria_comment'])) {
                            $commentsNeeded = true;
                        }
                    }
                    // only check if $commentsNeeded is false
                    if (!$commentsNeeded && empty($user['Evaluation']['EvaluationRubric']['comment'])) {
                        $commentsNeeded = true;
                    }
                    if (count($user['Evaluation']['EvaluationRubricDetail']) == count($rubricDetail['rubric']['RubricsCriteria'])){
                        $evaluated++;
                    }
                } else {
                    $commentsNeeded = true; // not evaluated = comments needed
                }
            }
            $allDone = ($evaluated == $rubricDetail['evaluateeCount']);
            $comReq = ($commentsNeeded && $event['Event']['com_req']);
            $this->set('allDone', $allDone);
            $this->set('comReq', $comReq);

            if (!empty($studentId)) {
                $this->set('studentId', $studentId);
            }

            $this->set('fullName', User::get('full_name'));
            $this->set('userId', User::get('id'));

            $this->render('rubric_eval_form');
        } else {
            $eventId = $this->params['form']['event_id'];
            $groupId = $this->params['form']['group_id'];

            $event = $this->Event->findById($eventId);

            // Student View Mode
            if(isset($this->params['form']['memberIDs'])){
                // find out whose evaluation is submitted
                foreach ($this->params['form']['memberIDs'] as $userId) {
                    if (isset($this->params['form'][$userId])) {
                        $targetEvaluatee = $userId;
                        break;
                    }
                }

                // validation has been modified to only return true
                /*if (!$this->validRubricEvalComplete($this->params['form'])) {
                    $this->Session->setFlash(__('validRubricEvalCompleten failure', true));
                    $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                    return;
                }*/

                if ($this->Evaluation->saveRubricEvaluation($targetEvaluatee, 0, $this->params)) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $this->params['form'][$targetEvaluatee.'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    $msg = array();
                    $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = __('some comments are missing', true);
                    }
                    if (empty($sub)) {
                        $msg[] = __('you still have to submit the evaluation with the Submit button below', true);
                    }
                    $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
                    $this->Session->setFlash(__('Your evaluation has been saved', true).$suffix);
                    if (!$this->RequestHandler->isAjax()) {
                        $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                    }
                    return;
                } else {
                    //Found error
                    //Validate the error why the Event->save() method returned false
                    $this->validateErrors($this->Event);
                    $this->Session->setFlash(__('Your evaluation was not saved successfully', true));
                    if (!$this->RequestHandler->isAjax()) {
                        $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                    }
                    return;
                }
            }
            // Criteria View Mode
            elseif(isset($this->params['form']['criteriaIDs'])){
                // find out the criteria submitted
                // general comments section should be given value of null
                $targetCriteria = null;
                foreach ($this->params['form']['criteriaIDs'] as $criteriaId) {
                    if (isset($this->params['form'][$criteriaId])) {
                        $targetCriteria = $criteriaId;
                        break;
                    }
                }

                $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
                $groupMembers = $this->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator);

                // Criteria will be null if the submitted section was 'General Comments'
                if ($targetCriteria != null) {
                    $viewMode = 1;
                }
                else {
                    $viewMode = 0;
                }

                // Loop through and save every group member for specified criteria
                foreach ($groupMembers as $groupMember){
                    $targetEvaluatee = $groupMember['User']['id'];

                    if ($this->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $this->params, $targetCriteria)) {
                        // check whether comments are given, if not and it is required, send msg
                        $comments = $this->params['form'][$targetEvaluatee.'comments'];
                        $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                        $msg = array();
                        $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                        if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                            $msg[] = __('some comments are missing', true);
                        }
                        if (empty($sub)) {
                            $msg[] = __('you still have to submit the evaluation with the Submit button below', true);
                        }
                        $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
                        $this->Session->setFlash(__('Your evaluation has been saved', true).$suffix);
                    } else {
                        //Found error
                        //Validate the error why the Event->save() method returned false
                        $this->validateErrors($this->Event);
                        $this->Session->setFlash(__('Your evaluation was not saved successfully', true));
                        break;
                    }
                }
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }
        }
    }


    /**
     * validRubricEvalComplete
     *
     * @param bool $form
     *
     * @access public
     * @return void
     */
    /*function validRubricEvalComplete ($form=null)
    {
        $status = true;
        return $status;
    }*/


    /**
     * completeEvaluationRubric
     *
     *
     * @access public
     * @return void
     */
    function completeEvaluationRubric ()
    {
        $status = true;

        $eventId = $this->params['form']['event_id'];
        $groupId = $this->params['form']['group_id'];
        $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
        $evaluators = $this->GroupsMembers->findAllByGroupId($groupId);
        $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);

        /*$studentId = $this->params['form']['student_id'];
        if (!empty($studentId)) {
            $evaluator = $studentId;
        }*/
        $groupEventId = $this->params['form']['group_event_id'];
        //Get the target group event
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        $this->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];

        // if no submission exists, create one
        //Get the target event submission
        $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
        if (empty($evaluationSubmission)) {
            $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
            // save evaluation submission
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
            if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
                $status = false;
            }
        }



        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo = $this->EvaluationSubmission->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'submitter_id' => $evaluators)
        ));
        $evaluators = count($evaluators);
        //Check to see if all members are completed this evaluation
        if ($memberCompletedNo == $evaluators) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->GroupEvent->save($groupEvent)) {
                $status = false;
            }
        }

        if ($status) {
            CaliperHooks::submit_rubric($eventId, $evaluator, $groupEvent['GroupEvent']['id'], $groupId);
            $this->Session->setFlash(__('Your Evaluation was submitted successfully.', true), 'good');
            $this->redirect('/home/index/', true);
            return;
        } else {
            $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
            return;
        }
    }


    /**
     * makeMixevalEvaluation
     *
     * @param mixed $event     event object
     * @param mixed $groupId   group id
     * @param mixed $studentId student id
     *
     * @access public
     * @return void
     */
    function _makeMixevalEvaluation ($event, $groupId, $studentId = null)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];

        if (empty($this->params['data'])) {

            // invalid group id
            if (!is_numeric($groupId)) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
            }

            $courseId = $this->Event->getCourseByEventId($eventId);

            $group = array();
            $group_events = $this->GroupEvent->getGroupEventByEventId($eventId);
            $userId = empty($studentId) ? User::get('id') : $studentId;
            foreach ($group_events as $events) {
                if ($this->GroupsMembers->checkMembershipInGroup($events['GroupEvent']['group_id'], $userId) !== 0) {
                    $group[] = $events['GroupEvent']['group_id'];
                }
            }

            // if group id provided does not match the group id the user belongs to
            if (!in_array($groupId, $group)) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
            }

            // students can't submit outside of release date range
            $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
            $now = time();

            if ($now < strtotime($event['Event']['release_date_begin']) ||
                $now > strtotime($event['Event']['release_date_end'])) {
                $this->Session->setFlash(__('Error: Evaluation is unavailable', true));
                $this->redirect('/home/index');
                return;
            }

            $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
            $members = $this->GroupsMembers->findAllByGroupId($groupId);

            $penalty = $this->Penalty->getPenaltyByEventId($eventId);
            $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
            $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
            $enrol = $this->UserEnrol->find('count', array(
                'conditions' => array('user_id' => $userId, 'course_id' => $courseId)
            ));
            $this->set('penaltyFinal', $penaltyFinal);
            $this->set('penaltyDays', $penaltyDays);
            $this->set('penalty', $penalty);
            $this->set('event', $event);
            $this->set('sub', $sub);
            $this->set('members', count($members));
            //Setup the courseId to session
            $this->set('courseId', $event['Event']['course_id']);
            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));
            $this->set('groupMembers', $this->Evaluation->loadMixEvaluationDetail($event));
            $self = $this->EvaluationMixeval->find('first', array(
                'conditions' => array('evaluator' => $userId, 'evaluatee' => $userId, 'event_id' => $eventId)
            ));
            $this->set('self', $self);
            $questions = $this->MixevalQuestion->findAllByMixevalId($event['Event']['template_id']);
            $mixeval = $this->Mixeval->find('first', array(
                'conditions' => array('id' => $event['Event']['template_id']), 'contain' => false, 'recursive' => 2));
            $this->set('questions', $questions);
            $this->set('mixeval', $mixeval);
            $this->set('enrol', $enrol);
            $this->set('userId', $userId);

            if (!empty($studentId)) {
                $this->set('studentId', $studentId);
            }

            $this->render('mixeval_eval_form');
        } else {
            $data = $this->data['data'];
            unset($this->data['data']);
            $mixeval = $this->Mixeval->findById($data['template_id']);
            $groupEventId = $data['grp_event_id'];
            $evaluator = empty($studentId) ? $data['submitter_id'] : $studentId;
            $required = true;
            $failures = array();

            // check peer evaluation questions
            if ($mixeval['Mixeval']['peer_question'] > 0) {
                foreach ($this->data as $userId => $eval) {
                    if (!isset($eval['Evaluation'])) {
                        continue; // only has self-evaluation so skip
                    }
                    if (!empty($studentId)) {
                        $eval['Evaluation']['evaluator_id'] = $studentId;
                    }
                    $eventId = $eval['Evaluation']['event_id'];
                    $groupId = $eval['Evaluation']['group_id'];
                    $evaluatee = $eval['Evaluation']['evaluatee_id'];
                    /*if (!$this->validMixevalEvalComplete($this->params['form'])) {
                        $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                        return;
                    }*/
                    if (!$this->Evaluation->saveMixevalEvaluation($eval)) {
                        $failures[] = $userId;
                    }
                    $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                        $groupEventId, $evaluator, $evaluatee);
                    $evaluation = !empty($evalMixeval['EvaluationMixevalDetail']) ? $evalMixeval['EvaluationMixevalDetail'] : null;
                    $details = Set::combine($evaluation, '{n}.question_number', '{n}');
                    foreach ($mixeval['MixevalQuestion'] as $ques) {
                        if ($ques['required'] && !$ques['self_eval'] && !isset($details[$ques['question_num']])) {
                            $required = false;
                        }
                    }
                }
            }
            // check self evaluation questions
            // second condition to exclude tutors
            if ($mixeval['Mixeval']['self_eval'] > 0 && isset($this->data[$evaluator]['Self-Evaluation'])) {
                $evaluatee = empty($studentId) ? User::get('id') : $studentId;
                $eventId = $this->data[$evaluatee]['Self-Evaluation']['event_id'];
                $groupId = $this->data[$evaluatee]['Self-Evaluation']['group_id'];
                $this->data[$evaluatee]['Evaluation'] = $this->data[$evaluatee]['Self-Evaluation'];
                if (!$this->Evaluation->saveMixevalEvaluation($this->data[$evaluatee])) {
                    $failures[] = $evaluatee;
                }
                $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                    $groupEventId, $evaluator, $evaluatee);
                $evaluation = !empty($evalMixeval['EvaluationMixevalDetail']) ? $evalMixeval['EvaluationMixevalDetail'] : null;
                $details = Set::combine($evaluation, '{n}.question_number', '{n}');
                foreach ($mixeval['MixevalQuestion'] as $ques) {
                    if ($ques['required'] && $ques['self_eval'] && !isset($details[$ques['question_num']])) {
                        $required = false;
                    }
                }
            }
            // success
            if (empty($failures)) {
                if ($required) {
                    $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
                    if (empty($evaluationSubmission)) {
                        $this->EvaluationSubmission->id = null;
                        $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
                        $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
                        $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : $studentId;
                        $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                        $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
                        if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
                            $this->Session->setFlash(__('Error: Unable to submit the evaluation. Please try again.', true));
                        }
                    }
                    CaliperHooks::submit_mixeval($eventId, $evaluator, $groupEventId, $groupId);

                    //checks if all members in the group have submitted the number of
                    //submission equals the number of members means that this group is ready to review
                    $evaluators = $this->GroupsMembers->findAllByGroupId($groupId);
                    $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);
                    $memberCompletedNo = $this->EvaluationSubmission->find('count', array(
                        'conditions' => array('grp_event_id' => $groupEventId, 'submitter_id' => $evaluators)
                    ));
                    $evaluators = count($evaluators);
                    //Check to see if all members are completed this evaluation
                    if ($memberCompletedNo == $evaluators) {
                        $this->GroupEvent->id = $groupEventId;
                        $groupEvent['GroupEvent']['marked'] = 'to review';
                        if (!$this->GroupEvent->save($groupEvent)) {
                            $this->Session->setFlash(__('Error', true));
                        } else {
                            $this->Session->setFlash(__('Your Evaluation was submitted successfully.', true), 'good');
                            $this->redirect('/home');
                        }
                    } else {
                        $this->Session->setFlash(__('Your Evaluation was submitted successfully.', true), 'good');
                        $this->redirect('/home');
                    }
                } else {
                    // Supposed to go here
                    $this->Session->setFlash(__('Your answers have been saved. Please answer all the required questions before it can be considered submitted.', true));
                }
            } else {
                $failures = $this->User->getFullNames($failures);
                $failures = join(' and ', array_filter(array_merge(array(join(
                    ', ', array_slice($failures, 0, -1))), array_slice($failures, -1))));
                $this->Session->setFlash(__('Error: It was unsuccessful to save evaluation(s) for ', true).$failures);
            }
            $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
        }
    }


    /**
     * validMixevalEvalComplete
     *
     * @param bool $form
     *
     * @access public
     * @return void
     */
    /*function validMixevalEvalComplete ($form=null)
    {
        $status = true;
        return $status;
    }*/


    /**
     * viewEvaluationResults
     *
     * @param mixed  $eventId       event id
     * @param mixed  $groupId       group id
     * @param string $displayFormat display format
     *
     * @access public
     * @return void
     */
    function viewEvaluationResults($eventId, $groupId = null, $displayFormat="")
    {
        // check to see if the ids are numeric
        if (!is_numeric($eventId) ||
            !($event = $this->Event->getAccessibleEventById(
                $eventId,
                User::get('id'), User::getCourseFilterPermission(), array('Course')))) {

            $this->Session->setFlash(__('Error: Invalid id or you do not have permission to access this event.', true));
            $this->redirect('/home/index');
            return;
        }

        if ('3' != $event['Event']['event_template_type_id']) {
            // not survey, we need group
            if (!is_numeric($groupId) ||
                !($group = $this->Group->getGroupWithMemberRoleByGroupIdEventId($groupId, $eventId))) {

                    $this->Session->setFlash(__('Error: Invalid group id.', true));
                    $this->redirect('/home/index');
                    return;
            }
            $event = array_merge($event, $group);
            $groupEventId = $event['GroupEvent']['id'];
        }

        $this->autoRender = false;

        $this->set('event', $event);
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))
            ->push(array('event' => $event['Event']))
            ->push(__('Results', true)));

        // set status whether instructors can view un/release comments & grades
        $viewReleaseBtns = time() >= strtotime($event['Event']['release_date_end']);
        $this->set('viewReleaseBtns', $viewReleaseBtns);

        switch ($event['Event']['event_template_type_id'])
        {
        case 1:  //View Simple Evaluation Result
            $formattedResult = $this->Evaluation->formatSimpleEvaluationResult($event);
            $this->set('event', $event);
            $this->set('results', $formattedResult);
            $this->render('view_simple_evaluation_results');
            break;

        case 2: //View Rubric Evaluation
            $sub = $this->EvaluationSubmission->findAllByGrpEventId($groupEventId);
            $groupMembers = $this->GroupEvent->getGroupMembers($groupEventId);
            $inCompleteMembers = array_diff(Set::extract($groupMembers, '/GroupsMembers/user_id'), Set::extract($sub, '/EvaluationSubmission/submitter_id'));
            $notInGroup = array_diff(Set::extract($sub, '/EvaluationSubmission/submitter_id'), Set::extract($groupMembers, '/GroupsMembers/user_id'));
            $inCompleteMembers = $this->User->getUsers($inCompleteMembers, array('Role'), array('User.full_name'));
            $notInGroup = $this->User->getUsers($notInGroup, array('Role'), array('User.id', 'User.full_name'));
            $submitted = $this->EvaluationSubmission->findAllByGrpEventId($groupEventId);
            $submitted = Set::extract('/EvaluationSubmission/submitter_id', $submitted);
            $rubricDetails = $this->EvaluationRubric->find('all', array(
                'conditions' => array('grp_event_id' => $groupEventId, 'evaluator' => $submitted)
            ));
            $memberList = array_unique(array_merge(Set::extract($rubricDetails, '/EvaluationRubric/evaluator'),
                Set::extract($rubricDetails, '/EvaluationRubric/evaluatee')));
            $fullNames = $this->User->getFullNames($memberList);
            $members = $this->User->findAllById($memberList);
            $rubric = $this->Rubric->findById($event['Event']['template_id']);
            $scoreRecords = Toolkit::formatRubricEvaluationResultsMatrix($rubricDetails);

            $this->set('rubric', $rubric);
            $this->set('inCompleteMembers', $inCompleteMembers);
            $this->set('notInGroup', $notInGroup);
            $this->set('members', $members);
            $this->set('memberList', $fullNames);
            $this->set('penalties', $this->Rubric->formatPenaltyArray($eventId, $groupId, $fullNames));
            $this->set('scoreRecords', $scoreRecords);
            $this->set('grpEventId', $groupEventId);

            if ($displayFormat == 'Detail') {
                $this->render('view_rubric_evaluation_results_detail');
            } else {
                $this->render('view_rubric_evaluation_results');
            }
            break;
        case 3: // View Survey
            $studentId = $groupId;
            $formattedResult = $this->Evaluation->formatSurveyEvaluationResult($event, $studentId);
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array('User.id' => $studentId),
                    'contain' => false
                )
            );

            $answers = array();

            foreach ($formattedResult['answers'] as $answer) {
                $answers[$answer['SurveyInput']['question_id']][] = $answer;
            }

            $this->set('name', $user['User']['full_name']);
            $this->set('answers', $answers);
            $this->set('questions', $formattedResult['questions']);

            $this->render('view_survey_results');
            break;
        case 4:  //View Mix Evaluation
            $sub = $this->EvaluationSubmission->findAllByGrpEventId($groupEventId);
            $groupMembers = $this->GroupEvent->getGroupMembers($groupEventId);
            $inCompleteMembers = array_diff(Set::extract($groupMembers, '/GroupsMembers/user_id'), Set::extract($sub, '/EvaluationSubmission/submitter_id'));
            $notInGroup = array_diff(Set::extract($sub, '/EvaluationSubmission/submitter_id'), Set::extract($groupMembers, '/GroupsMembers/user_id'));
            $mixevalDetails = $this->EvaluationMixeval->getResultsByEvaluateesOrEvaluators($groupEventId, Set::extract($sub, '/EvaluationSubmission/submitter_id'));
            $memberList = array_unique(array_merge(Set::extract($mixevalDetails, '/EvaluationMixeval/evaluator'),
                Set::extract($mixevalDetails, '/EvaluationMixeval/evaluatee')));
            $memberList = array_unique(array_merge($memberList, $inCompleteMembers));
            $fullNames = $this->User->getFullNames($memberList);
            $members = $this->User->findAllById($memberList);
            $sub = Set::extract('/EvaluationSubmission/submitter_id', $sub);

            $mixeval = $this->Mixeval->find('first', array(
                'conditions' => array('Mixeval.id' => $event['Event']['template_id']),
                'recursive' => 2
            ));
            $required = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.required');
            $peerQues = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.self_eval');
            // only required peer evaluation questions are counted toward the averages
            $required = array_flip(array_intersect(array_keys($required, 1), array_keys($peerQues, 0)));

            $member_ids = Set::extract($groupMembers, '/GroupsMembers/user_id');
            $details = $this->Evaluation->getMixevalResultDetail($groupEventId, $members, $member_ids, array_keys($required));
            $inCompleteMembers = $this->User->getUsers($inCompleteMembers, array('Role'), array('User.full_name'));
            $notInGroup = $this->User->getUsers($notInGroup, array('Role'), array('User.id', 'User.full_name'));
            $gradeReleaseStatus = $this->EvaluationMixeval->getTeamReleaseStatus($groupEventId);
            $questions = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}');
            $quesTypes = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.mixeval_question_type_id');
            $status = array();
            foreach ($details['evalResult'] as $id => $result) {
                $tmpStat = array('gradeRelease' => array(), 'commentRelease' => array());
                foreach ($result as $eval) {
                    $evaluator = $eval['EvaluationMixeval']['evaluator'];
                    $tmpStat['gradeRelease'][] = $eval['EvaluationMixeval']['grade_release'];
                    foreach ($eval['EvaluationMixevalDetail'] as $detail) {
                        $detail['evaluator'] = $evaluator;
                        $questions[$detail['question_number']]['Submissions'][] = $detail;
                        if (in_array($quesTypes[$detail['question_number']], array(2, 3))) {
                            // short or long answers
                            $tmpStat['commentRelease'][] = $detail['comment_release'];
                        }
                    }
                }
                $tmpStat['gradeRelease'] = array_product($tmpStat['gradeRelease']);
                $tmpStat['commentRelease'] = array_product($tmpStat['commentRelease']);
                $status[$id]['release_status'] = $tmpStat;
            }
            $this->set('mixeval', $mixeval);
            $this->set('memberList', $fullNames);
            $this->set('mixevalDetails', $details['scoreRecords']);
            $this->set('evalResult', $details['evalResult']);
            $this->set('penalty', $this->Mixeval->formatPenaltyArray($eventId, $groupId, $fullNames));
            $this->set('inCompleteMembers', $inCompleteMembers);
            $this->set('notInGroup', $notInGroup);
            $this->set('required', $required);
            $this->set('gradeReleaseStatus', $gradeReleaseStatus);
            $this->set('groupByQues', $questions);
            $this->set('grpEventId', $groupEventId);
            $this->set('status', $status);

            if ($displayFormat == 'Detail') {
                $this->render('view_mixeval_evaluation_results_detail');

            } else {
                $this->render('view_mixeval_evaluation_results');
            }
            break;
        }
    }

    /**
     * studentViewEvaluationResult
     *
     * @param int $eventId
     * @param int $groupId
     * @param mixed $studentId
     *
     * @access public
     * @return void
     */
    function studentViewEvaluationResult($eventId, $groupId = null, $studentId = null)
    {
        $this->autoRender = false;

        // check to see if the ids are numeric and the user can view the event
        // only need FILTER_PERMISSION_ENROLLED permission as this view is only for student. If user is an instructor,
        // he/she should not use this view
        if (!is_numeric($eventId) ||
            !($event = $this->Event->getAccessibleEventById(
                $eventId,
                User::get('id'), Course::FILTER_PERMISSION_ENROLLED, array('Course')))) {

            $this->Session->setFlash(__('Error: Invalid id or you do not have permission to access this event.', true));
            $this->redirect('/home/index');
            return;
        }

        if ('3' != $event['Event']['event_template_type_id']) {
            // not survey, we need group
            if (!is_numeric($groupId) ||
                //!($group = $this->Group->getGroupByGroupIdEventIdMemberId($groupId, $eventId, User::get('id')))) {
                !($group = $this->Group->getGroupWithMemberRoleByGroupIdEventId($groupId, $eventId))) {

                    $this->Session->setFlash(__('Error: Invalid group id or you are not in this group.', true));
                    $this->redirect('/home/index');
                    return;
            }

            if (!$event['Event']['is_result_released']) {
                $this->Session->setFlash(__('Error: The results are not released.', true));
                $this->redirect('/home/index');
                return;
            }
            $event = array_merge($event, $group);
            $groupEventId = $event['GroupEvent']['id'];
            $autoRelease = $event['Event']['auto_release'];
        }

        // set up page variables
        $this->set('event', $event);
        $this->set('breadcrumb', $this->breadcrumb
            ->push('home_student')
            ->push(__('View My Results', true)));

        $userId = User::get('id');

        switch ($event['Event']['event_template_type_id'])
        {
        case 1: //View Simple Evaluation Result
            $studentResult = $this->EvaluationSimple->formatStudentViewOfSimpleEvaluationResult($event, $userId);
            $this->set('studentResult', $studentResult);
            $this->set('gradeReleased', $studentResult['gradeReleased']);
            $this->set('commentReleased', $studentResult['commentReleased']);
            $this->render('student_view_simple_evaluation_results');
            break;

        case 2: //View Rubric Evaluation Result
            $rubric = $this->Rubric->findById($event['Event']['template_id']);
            $submitted = $this->EvaluationSubmission->findAllByGrpEventId($groupEventId);
            $submitted = Set::extract('/EvaluationSubmission/submitter_id', $submitted);
            $evaluatorDetails = $this->EvaluationRubric->find('all', array(
                'conditions' => array('grp_event_id' => $groupEventId, 'evaluator' => $userId)
            ));
            $evaluateeDetails = $this->EvaluationRubric->find('all', array(
                'conditions' => array('grp_event_id' => $groupEventId, 'evaluatee' => $userId, 'evaluator' => $submitted)
            ));
            $userIds = array_unique(array_merge(
                Set::extract($evaluatorDetails, '/EvaluationRubric/evaluatee'), array($userId)));
            $fullNames = $this->User->getFullNames($userIds);
            $sub = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $userId);
            $penalty = empty($sub) ? $this->Penalty->getPenaltyPercent($event) : $this->Penalty->getPenaltyPercent($sub);
            $generalCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubric/comment_release'));
            $detailedCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubricDetail/comment_release'));
            $status = array(
                'comment' => ($generalCommentRelease + $detailedCommentRelease),
                'grade' => array_product(Set::extract($evaluateeDetails, '/EvaluationRubric/grade_release')),
                'autoRelease' => $autoRelease
            );

            $this->set('rubric', $rubric);
            $this->set('membersList', $fullNames);
            $this->set('evaluatorDetails', $evaluatorDetails);
            $this->set('evaluateeDetails', $evaluateeDetails);
            $this->set('status', $status);
            $this->set('penalty', $penalty);
            $this->set('status', $status);

            $this->render('student_view_rubric_evaluation_results');
            break;
        case 3: //View Survey Result
            $answers = array();
            $formattedResult = $this->Evaluation->formatSurveyEvaluationResult($event, empty($studentId) ? User::get('id') : $studentId);

            foreach ($formattedResult['answers'] as $answer) {
                $answers[$answer['SurveyInput']['question_id']][] = $answer;
            }

            $this->set('survey_id', $formattedResult['survey_id']);
            $this->set('answers', $answers);
            $this->set('questions', $formattedResult['questions']);
            $this->set('name', $this->Auth->user('full_name'));
            $this->render('view_survey_results');
            break;

        case 4: //View Mix Evaluation Result
            $mixeval = $this->Mixeval->find('first', array(
                'conditions' => array('Mixeval.id' => $event['Event']['template_id']),
                'recursive' => 2
            ));
            $required = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.required');
            $peerQues = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.self_eval');
            // only required peer evaluation questions are counted toward the averages
            $required = array_flip(array_intersect(array_keys($required, 1), array_keys($peerQues, 0)));

            $user = $this->User->findById($userId);
            $groupMembers = $this->GroupEvent->getGroupMembers($groupEventId);
            $member_ids = Set::extract($groupMembers, '/GroupsMembers/user_id');
            $details = $this->Evaluation->getMixevalResultDetail($groupEventId, array($user), $member_ids, array_keys($required));
            $eventSub = $this->Event->getEventSubmission($eventId, $userId);
            $penalty = $this->Penalty->getPenaltyPercent($eventSub);
            $score[$userId]['received_ave_score'] = array_sum(array_intersect_key($details['scoreRecords'][$userId], $required));
            $avePenalty = $score[$userId]['received_ave_score'] * ($penalty / 100);

            $this->set('mixeval', $mixeval);
            $this->set('evalResult', $details['evalResult']);
            $this->set('memberScoreSummary', $score);
            $this->set('penalty', $penalty);
            $this->set('avePenalty', $avePenalty);

            $this->render('student_view_mixeval_evaluation_results');
            break;
        }
    }


    /**
     * markEventReviewed
     *
     *
     * @access public
     * @return void
     */
    function markEventReviewed ()
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to mark events reviewed', true);
            $this->redirect('/home');
            return;
        }

        $this->autoRender = false;
        $eventId = $this->params['form']['event_id'];
        $groupId =  $this->params['form']['group_id'];
        $groupEventId = $this->params['form']['group_event_id'];
        $reviewStatus = isset($this->params['form']['mark_reviewed'])? "mark_reviewed" : "mark_not_reviewed";

        //Get the target event
        $this->Event->id = $eventId;
        $event = $this->Event->read();

        $this->GroupEvent->id = $groupEventId;
        $groupEvent = $this->GroupEvent->read();
        // if marking peer evaluations as reviewed/not reviewed
        if ($reviewStatus == "mark_reviewed") {
            $groupEvent['GroupEvent']['marked'] ='reviewed';
            $this->GroupEvent->save($groupEvent);
        }
        //not reviewed is more complicate
        //if all members submitted, then it should be 'to review'
        //if not all members submitted, then it should be 'not reviewed'
        else if ($reviewStatus == "mark_not_reviewed") {
            //Get Members whom completed evaluation
            $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEventId);
            //Check to see if all members are completed this evaluation
            $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
            $numMembers=$event['Event']['self_eval'] ?
                $this->GroupsMembers->find('count', array('conditions' => array('group_id' => $groupId))) :
                $this->GroupsMembers->find('count', array('conditions' => array('group_id' => $groupId))) - 1;

            ($numOfCompletedCount == $numMembers) ? $completeStatus = 1:$completeStatus = 0;
            if ($completeStatus) {
                $groupEvent['GroupEvent']['marked'] ='to review';
                $this->GroupEvent->save($groupEvent);
            } else {
                $groupEvent['GroupEvent']['marked'] ='not reviewed';
                $this->GroupEvent->save($groupEvent);
            }
        }
        $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId);
    }


    /**
     * markGradeRelease
     *
     * @param mixed $grpEventId
     * @param mixed $releaseStatus
     * @param mixed $evaluatee
     *
     * @access public
     * @return void
     */
    function markGradeRelease($grpEventId, $releaseStatus, $evaluatee = null)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to release grades', true);
            $this->redirect('/home');
            return;
        }

        $grpEvent = $this->GroupEvent->findById($grpEventId);
        $eventId = $grpEvent['GroupEvent']['event_id'];
        $event = $this->Event->findById($eventId);
        $this->autoRender = false;

        $conditions = array('grp_event_id' => $grpEventId);
        if (!is_null($evaluatee)) {
            $conditions['evaluatee'] = $evaluatee;
        }
        $url = '';

        switch ($event['Event']['event_template_type_id']) {
        case "1":
            $model = 'EvaluationSimple';
            break;
        case "2":
            $url = '/Detail';
            $model = 'EvaluationRubric';
            break;
        case "4":
            $url = '/Detail';
            $model = 'EvaluationMixeval';
            break;
        }

        $evalIds = $this->$model->find('list', array('conditions' => $conditions));
        $this->$model->updateAll(
            array($model.'.grade_release' => $releaseStatus),
            array($model.'.id' => $evalIds)
        );

        $this->GroupEvent->id = $grpEventId;
        $evals = $this->$model->find('list', array(
            'conditions' => array($model.'.grp_event_id' => $grpEventId),
            'fields' => $model.'.grade_release'
        ));
        $all = array_product($evals);
        $some = array_sum($evals);

        if ($all) {
            $grpEvent['GroupEvent']['grade_release_status'] = 'All';
        } else if ($some) {
            $grpEvent['GroupEvent']['grade_release_status'] = 'Some';
        } else {
            $grpEvent['GroupEvent']['grade_release_status'] = 'None';
        }

        $this->GroupEvent->save($grpEvent);

        $this->redirect('viewEvaluationResults/'.$eventId.'/'.$grpEvent['GroupEvent']['group_id'].$url);

    }


    /**
     * markCommentRelease
     *
     * @param bool $param
     *
     * @access public
     * @return void
     */
    function markCommentRelease($param = null)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to release comments', true);
            $this->redirect('/home');
            return;
        }

        $this->autoRender = false;
        if ($param !=null) {
            $tok = strtok($param, ';');
            $eventId = $tok;
        } else {
            $grpEvent = $this->GroupEvent->findById($this->params['form']['group_event_id']);
            $eventId = $grpEvent['GroupEvent']['event_id'];
        }

        // Check whether the event exists or user has permission to access it
        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission(), array()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you don\'t have access to it', true));
            $this->redirect('index');
            return;
        }

        switch ($event['Event']['event_template_type_id']) {
        case "1":
            $groupId =  $this->params['form']['group_id'];

            if (isset($this->params['form']['evaluator_ids'])) {
                $groupEventId = $this->params['form']['group_event_id'];
                $evaluatorIds = $this->params['form']['evaluator_ids'];
                $this->Evaluation->changeSimpleEvaluationCommentRelease($groupEventId, $evaluatorIds, $this->params);
                $this->Evaluation->markSimpleEvalReviewed($eventId, $groupEventId);
            }
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId);
            break;

        case "2":
            $grpEvents = array($this->params['form']['group_event_id']);
            $eventId = $grpEvent['GroupEvent']['event_id'];
            $groupId = $grpEvent['GroupEvent']['group_id'];
            switch ($this->params['form']['submit']) {
            case "Save Changes":
                $this->Evaluation->changeIndivRubricEvalCommentRelease($this->params['form']);
                break;
            case "Release Comments":
                $evaluateee = $this->params['form']['evaluatee'];
                $this->Evaluation->changeRubricEvalCommentRelease(1, $grpEvents, $evaluateee);
                break;
            case "Unrelease Comments":
                $evaluateee = $this->params['form']['evaluatee'];
                $this->Evaluation->changeRubricEvalCommentRelease(0, $grpEvents, $evaluateee);
                break;
            case "Release All Comments":
                $this->Evaluation->changeRubricEvalCommentRelease(1, $grpEvents);
                break;
            case "Unrelease All Comments":
                $this->Evaluation->changeRubricEvalCommentRelease(0, $grpEvents);
                break;
            }
            // mark group event as reviewed when all evaluations have been looked at by an instructor
            $this->Evaluation->markRubricEvalReviewed($eventId, $grpEvents[0]);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
            break;

        case "4":
            $groupId = $grpEvent['GroupEvent']['group_id'];
            $eventId = $grpEvent['GroupEvent']['event_id'];
            $groupEvents = array($this->params['form']['group_event_id']);
            switch($this->params['form']['submit']) {
            case "Save Changes":
                $this->Evaluation->changeIndivMixedEvalCommentRelease($this->params['form']);
                break;
            case "Release Comments":
                $evaluateeId = $this->params['form']['evaluatee'];
                $this->Evaluation->changeMixedEvalCommentRelease(1, $groupEvents, $evaluateeId);
                break;
            case "Unrelease Comments":
                $evaluateeId = $this->params['form']['evaluatee'];
                $this->Evaluation->changeMixedEvalCommentRelease(0, $groupEvents, $evaluateeId);
                break;
            case "Release All Comments":
                $this->Evaluation->changeMixedEvalCommentRelease(1, $groupEvents);
                break;
            case "Unrelease All Comments":
                $this->Evaluation->changeMixedEvalcommentRelease(0, $groupEvents);
                break;
            }
            // TODO: change this
            $this->Evaluation->markMixedEvalReviewed($eventId, $groupEvents[0]);
            //$this->Evaluation->markMixedEvalReviewed($eventId, $groupEventId);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
            break;
        }

    }


    /**
     * changeAllCommentRelease
     *
     * @param bool $param
     *
     * @access public
     * @return void
     */
    function changeAllCommentRelease ($param=null)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to change comment release statuses', true);
            $this->redirect('/home');
            return;
        }

        $tok = strtok($param, ';');
        $eventId = $tok;
        $releaseStatus = strtok(';');
        $this->Event->id = $eventId;
        $event = $this->Event->read();

        $groupEventList = $this->GroupEvent->getGroupListByEventId($eventId);
        $groupEvents = Set::extract('/GroupEvent/id', $groupEventList);
        switch ($event['Event']['event_template_type_id']) {
        case 1://simple
            $this->EvaluationSimple->setAllEventCommentRelease($eventId, $this->Auth->user('id'), $releaseStatus);
            foreach ($groupEvents as $groupEventId) {
                $this->Evaluation->markSimpleEvalReviewed($eventId, $groupEventId);
            }
            break;
        case 2://rubric
            $this->Evaluation->changeRubricEvalCommentRelease($releaseStatus, $groupEvents);
            //Update all groupEvent's comment release status
            foreach ($groupEvents as $groupEventId) {
                $this->Evaluation->markRubricEvalReviewed($eventId, $groupEventId);
            }
            break;
        case 4://mixed
            $this->Evaluation->changeMixedEvalCommentRelease($releaseStatus, $groupEvents);
            foreach ($groupEvents as $groupEventId) {
                $this->Evaluation->markMixedEvalReviewed($eventId, $groupEventId);
            }
            break;
        default:
            break;
        }

        $this->redirect('/evaluations/view/'.$eventId);
    }


    /**
     * changeAllGradeRelease
     *
     * @param bool $param
     *
     * @access public
     * @return void
     */
    function changeAllGradeRelease ($param=null)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to change grade release statuses', true);
            $this->redirect('/home');
            return;
        }

        $this->autoRender = false;
        $tok = strtok($param, ';');
        $eventId = $tok;
        $releaseStatus = strtok(';');
        $this->Event->id = $eventId;
        $event = $this->Event->read();

        switch ($event['Event']['event_template_type_id']) {
        case 1://simple
            $this->EvaluationSimple->setAllEventGradeRelease($eventId, $releaseStatus);
            $model = 'EvaluationSimple';
            break;
        case 2://rubric
            $this->EvaluationRubric->setAllEventGradeRelease($eventId, $releaseStatus);
            $model = 'EvaluationRubric';
            break;
        case 4://mix
            $this->EvaluationMixeval->setAllEventGradeRelease($eventId, $releaseStatus);
            $model = 'EvaluationMixeval';
            break;
        default:
            break;
        }

        $grpEventList = $this->GroupEvent->getGroupListByEventId($eventId);
        foreach ($grpEventList as $grpEvent) {
            $this->GroupEvent->id = $grpEvent['GroupEvent']['id'];
            $evals = $this->$model->find('list', array(
                'conditions' => array('grp_event_id' => $grpEvent['GroupEvent']['id']),
                'fields' => $model.'.grade_release'
            ));
            $all = array_product($evals);
            $some = array_sum($evals);

            if ($all) {
                $grpEvent['GroupEvent']['grade_release_status'] = 'All';
            } else if ($some) {
                $grpEvent['GroupEvent']['grade_release_status'] = 'Some';
            } else {
                $grpEvent['GroupEvent']['grade_release_status'] = 'None';
            }

            $this->GroupEvent->save($grpEvent);
        }

        $this->redirect('/evaluations/view/'.$eventId);
    }


    /**
     * viewGroupSubmissionDetails
     *
     * @param mixed $eventId
     * @param mixed $groupId
     *
     * @access public
     * @return void
     */
    function viewGroupSubmissionDetails ($eventId = null, $groupId = null)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('controllers/evaluations/viewevaluationresults')) {
            $this->Session->setFlash('Error: You do not have permission to view submission details', true);
            $this->redirect('/home');
            return;
        }

        // check whether $eventId and $groupId are numeric
        if (!is_numeric($eventId) || !is_numeric($groupId) || !($event = $this->Event->getEventById($eventId))) {
            $this->Session->setFlash('Error: Invalid Id', true);
            $this->redirect('index');
            return;
        }

        $courseId = $this->Event->getCourseByEventId($eventId);

        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        $this->set('title_for_layout', __('Submission Details', true));

        $this->Event->id = $eventId;
        $event = $this->Event->read();
        $this->Group->id = $groupId;
        $group = $this->Group->read();
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        $students = $this->Group->getMembersByGroupId($groupId, 'all');

        $pos = 0;
        foreach ($students as $row) {
            $user = $row['Member'];
            $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEvent['GroupEvent']['id'],
                $user['id']);

            $name = $this->User->find(
                'all',
                array(
                    'conditions' => array('id' => $user['id']),
                    'recursive' => -1
                )
            );
            $name = $name[0]['User'];

            $students[$pos]['Member']['full_name'] = $name['full_name'];
            $students[$pos]['Member']['student_no'] = $name['student_no'];

            if (isset($evalSubmission)) {
                $students[$pos]['users']['submitted'] = $evalSubmission['EvaluationSubmission']['submitted'];
                $students[$pos]['users']['date_submitted'] = $evalSubmission['EvaluationSubmission']['date_submitted'];
                $students[$pos]['users']['due_date'] = $event['Event']['due_date'];
                if ($evalSubmission['EvaluationSubmission']['submitted']) {
                    $lateBy = $this->framework->getTimeDifference($evalSubmission['EvaluationSubmission']['date_submitted'],
                        $event['Event']['due_date'], 'days');
                    if ($lateBy > 0) {
                        $students[$pos]['users']['time_diff'] = ceil($lateBy);
                    }
                }
            }
            $pos++;
        }
        $this->set('members', $students);
        $this->set('group', $group);
        $this->set('eventId', $eventId);
        $this->set('groupEventId', $groupEvent['GroupEvent']['group_id']);
    }


    /**
     * viewSurveySummary
     *
     * @param int $eventId
     *
     * @access public
     * @return void
     */
    function viewSurveySummary($eventId)
    {
        // Check that $eventId is valid
        $event = $this->Event->findById($eventId);
        if (null == $event) {
            $this->Session->setFlash(__('Error: Invalid event Id', true));
            $this->redirect('index');
            return;
        }

        if ($event['Course']['student_count'] < 1) {
            $this->Session->setFlash(__('Error: There are no students in the class', true));
            $this->redirect('index');
            return;
        }

        // Check that $surveyId is valid
        $survey = $this->Survey->findById($event['Event']['template_id']);
        if (null == $survey) {
            $this->Session->setFlash(__('Error: Invalid survey Id', true));
            $this->redirect('index');
            return;
        }

        // Check that course is accessible by user
        $course = $this->Course->getAccessibleCourseById(
            $event['Event']['course_id'], User::get('id'), User::getCourseFilterPermission(), array('Enrol'));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        // Prepare data to pass to view, get current enrolled student Ids first
        // so that we can exclude the dropped students
        $studentIds = Set::extract($course['Enrol'], '/id');
        $formattedResult = $this->Evaluation->formatSurveyEvaluationSummary(
            $survey['Survey']['id'], $eventId, $studentIds);

        $this->set('questions', $formattedResult);
        $this->set('breadcrumb',
            $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(array('event' => $event['Event']))
            ->push(__('Summary', true)));

        $submissions = $this->SurveyInput->findAllByEventId($eventId);
        // add submission status for each enroled user
        foreach ($course['Enrol'] as $key => $student) {
            $course['Enrol'][$key]['submitted'] = false;
            foreach ($submissions as $submission) {
                if ($student['id'] == $submission['SurveyInput']['user_id']) {
                    $course['Enrol'][$key]['submitted'] = true;
                    break;
                }
            }
        }

        $this->set('students', $course['Enrol']);
        $this->set('eventId', $eventId);
    }


    /**
     * export_rubic
     *
     * @param int $eventId     event id
     * @param int $rubicEvalId rubric evaluation id
     *
     * @access public
     * @return void
     */
    /*function export_rubic($eventId, $rubicEvalId)
    {
        $this->autoRender=false;
        $this->autoLayout=false;
        $questions= $this->RubricsCriteria->find('all', array('conditions' => array('rubric_id' => $rubicEvalId), 'fields' => array("id", "criteria")));
        $numberOfCriteria = count($questions);

        $groups = $this->GroupEvent->find('all', array('conditions' => array('event_id' => $eventId,
            'group_id <>' => 0),
        'fields' => array('group_id'),
        'order' => 'group_id ASC'));//, null, null,false);

        foreach ($groups as $key => $array) {
            $groupsArray[]=$array['GroupEvent']['group_id'];
        }

        foreach ($groupsArray as $key => $value) {
            $groupMembersArrayComplete[]=$this->GroupsMembers->find('all', array('conditions' => array('group_id' => $value)));
        }

        for ($i=0; $i<count($groupMembersArrayComplete); $i++) {
            foreach ($groupMembersArrayComplete[$i] as $key => $array) {
                $groupMembersArraySimple[$groupsArray[$i]][]=$array['GroupsMembers']['user_id'];
            }
        }

        foreach ($groupMembersArraySimple as $key => $array) {
            foreach ($array as $index => $value) {
                $groupMembersArrayWithName[$key][$value]=$this->User->field("first_name", "id=$value").' '.$this->User->field('last_name', "id=$value");
            }
        }

        foreach ($groupMembersArrayWithName as $key => $array) {
            foreach ($array as $index => $value) {
                $studetnsWithName[$index]=$value;
            }
        }

        ksort($studetnsWithName);

        $groupEvents=$this->GroupEvent->find('all', array('conditions' => array('event_id' => $eventId),
            'fields' => array('id'),
            'order' => 'id ASC'));//, null, null,false);

        $this->pre($groupEvents);

        $groupEventsArray=array();

        foreach ($groupEvents as $key => $array) {
            $groupEventsArray[]=$array['GroupEvent']['id'];
        }

        //$this->pre ($groupEventsArray);

        //        $evalutorToEvaluateesByGroup=array();

        foreach ($groupEventsArray as $key => $value) {
            //          $sql = "SELECT id,evaluator,evaluatee FROM `evaluation_rubrics` WHERE `grp_event_id` = $value ORDER BY `evaluator` ASC, `evaluatee` ASC";
            //          $result=$this->EvaluationRubric->query($sql);
            $result = $this->EvaluationRubric->find('all', array(
                'conditions' => array('EvaluationRubric.grp_event_id' => $value),
                'fields' => array('EvaluationRubric.id', 'EvaluationRubric.evaluator', 'EvaluationRubric.evaluatee'),
                'order' => array('EvaluationRubric.evaluator' => 'ASC', 'EvaluationRubric.evaluatee' => 'ASC')
            ));
            $evalutorToEvaluatees=array();
            foreach ($result as $resultArray) {
                if (!isset($evalutorToEvaluatees[$resultArray['evaluation_rubrics']['evaluator']])) {
                    $evalutorToEvaluatees[$resultArray['evaluation_rubrics']['evaluator']]=array();
                }

                foreach ($resultArray as $rubricEvalValue) {
                    for ($i=1; $i<=$numberOfCriteria; $i++) {
                        $evalutorToEvaluatees[$rubricEvalValue['evaluator']]['evaluatee'][$rubricEvalValue['evaluatee']]['grade'][$i]=$this->EvaluationRubricDetail->field('grade', array('evaluation_rubric_id' => $rubricEvalValue['id'], 'criteria_number' => $i));

                        $evalutorToEvaluatees[$rubricEvalValue['evaluator']]['evaluatee'][$rubricEvalValue['evaluatee']]['criteria_comment'][$i]=$this->EvaluationRubricDetail->field('criteria_comment', array('evaluation_rubric_id' => $rubricEvalValue['id'], 'criteria_number' => $i));
                    }
                }
            }
            $evalutorToEvaluateesByGroup[]=$evalutorToEvaluatees;

        }



        $evalutorToEvaluateesArray=array();
        foreach ($evalutorToEvaluateesByGroup as $groupIndex => $groupData) {
            foreach ($groupData as $evaluatorId => $evaluateeList) {
                foreach ($evaluateeList as $evalDetails) {
                    foreach ($evalDetails as $key => $value) {
                        $evalutorToEvaluateesArray[$evaluatorId][$key]=$value;
                    }
                }
            }
        }

        $this->pre(array_keys($evalutorToEvaluateesByGroup)); die;
        $options=array('Grade' => 'grade', 'Criteria Comment' => 'criteria_comment');
        foreach ($options as $optionsIndex => $mode) {
            echo "<h1>$optionsIndex</h1>";
            for ($questionIndex=1; $questionIndex<=$numberOfCriteria; $questionIndex++) {
                foreach ($evalutorToEvaluateesByGroup as $groupIndex => $groupData) {
                    $evaluators=array_keys($groupData);
                    if (empty($evaluators)) {
                        continue;
                    }
                    echo "<h2>Question $questionIndex Group ".($groupIndex+1)."($optionsIndex)</h2>";
                    echo "<table border=1>";
                    //row
                    for ($rowIndex=0; $rowIndex<count($evaluators)+1; $rowIndex++) {
                        echo "<tr>";
                        //column
                        for ($columnIndex=0; $columnIndex<count($evaluators)+1; $columnIndex++) {
                            if ($rowIndex==0 && $columnIndex==0) {
                                echo "<td>&nbsp</td>";
                            }
                            elseif ($rowIndex==0 && $columnIndex >0 ) echo "<td>".$studetnsWithName[$evaluators[$columnIndex-1]]."</td>";
                            elseif ($rowIndex>0 && $columnIndex == 0 ) echo "<td>".$studetnsWithName[$evaluators[$rowIndex-1]]."</td>";
                            elseif ($rowIndex==$columnIndex && $rowIndex !=0) echo "<td> / </td>";
                            else {
                                if (isset($evalutorToEvaluateesArray[$evaluators[$rowIndex-1]][$evaluators[$columnIndex-1]][$mode][$questionIndex])) {
                                    echo "<td>".$evalutorToEvaluateesArray[$evaluators[$rowIndex-1]][$evaluators[$columnIndex-1]][$mode][$questionIndex]."&nbsp</td>";
                                } else {
                                    echo "<td>".__('N/A', true)."</td>";
                                }
                            }
                        }
                        echo "</tr>";
                    }
                    echo "</table>";

                }
            }

        }



    }*/


    /**
     * export_test
     *
     * @param bool $eventId
     * @param bool $groupID
     *
     * @access public
     * @return void
     */
    /*function export_test($eventId=null, $groupID=null)
    {
        $this->autoLayout=false;
        $this->autoRender=false;
        //step 1: get Event title
        $event_title=$this->Event->field('title', "id=$eventId");

        //step 2: get Group Info
        $group_number=$this->Group->field('group_num', "id=$groupID"); //field 1
        $group_name=$this->Group->field('group_name', "id=$groupID");//field 2

        //step 3: Get Group members

        //step 3.1 Get Group Membrer user_id
        $user_id=$this->_extractModel("GroupsMembers", $this->GroupsMembers->find('all', "group_id=$groupID", "user_id", "user_id asc", null, null, false), "user_id");
        //step 3.2 Get Group Membrer user_data
        $user_data=array();
        foreach ($user_id as $key => $value) {
            $user_data["$value"]=$this->User->find("id=$value", array("first_name", "last_name", "student_no", "email"), "id asc", false);
        }   //field 3,4,5,6

        //pre ($user_data);

        //step 4   (if Rubric)

        //step 4.1 Get evaluation_rubic id(s)

        $evaluation_rubric_id=$this->_extractModel("EvaluationRubric", $this->EvaluationRubric->find('all', "event_id=$eventId", "id", "id asc", null, null, false), "id");
        //pre($evaluation_rubric_id);

        //step 4.2 Get evaluation_rubic general data
        $evaluation_rubric_general_data=array();
        foreach ($evaluation_rubric_id as $key => $value) {
            //field 7,8
            $evaluation_rubric_general_data["$value"]=$this->EvaluationRubric->find("id=$value", array("evaluator", "evaluatee", "comment", "score"), "id asc", false);
        }

        //pre($evaluation_rubric_general_data);

        //step 4.2.1 get evaluatee->evaluator array
        $evaluateesToEvaluators=array();
        foreach ($evaluation_rubric_general_data as $key => $value) {
            $evaluateesToEvaluators[$user_data[$value['EvaluationRubric']['evaluatee']]['User']['student_no']][]=$key;
        }
        // pre ($evaluateesToEvaluators);

        //step 4.3 Get rubric evaluation specific data
        $evaluation_rubric_specific_data=array();
        foreach ($evaluation_rubric_id as $key => $value) {
            $evaluation_rubric_specific_data["$value"]=$this->EvaluationRubricDetail->find('all', "evaluation_rubric_id=$value", array("criteria_number", "criteria_comment",), "evaluation_rubric_id asc", null, false);     //field 9
        }
        //pre($evaluation_rubric_specific_data);

        //step 5 Get Rubric title
        $rubric_id=$this->Event->field('template_id', "id=$eventId");
        //step 6 Get Rubric criteria
        //$rubric_criteria=$this->RubricsCriteria->generateList("rubric_id=$rubric_id", "rubric_id asc", null, "{n}.RubricsCriteria.criteria_num", "{n}.RubricsCriteria.criteria");
        $rubric_criteria = $this->RubricsCriteria->find("list", array('conditions' => 'rubric_id = '.$rubric_id,
            'order'      => 'rubric_id asc',
            'limit'      => null,
            'fields'     => array('RubricsCriteria.criteria_num', 'RubricsCriteria.criteria')
        ));

        //pre($rubric_criteria);

        //output
        $fh=fopen("../tmp/test/output.csv", "w");
        fputcsv($fh, array("Event Name", $event_title));
        fputcsv($fh, array("Event Type", "Rubric"));
        fputcsv($fh, array("Criteria number", "Criteria"));
        foreach ($rubric_criteria as $key => $value) {
            fputcsv($fh, array($key, $value));
        }

        $header=array(__("Group Number", true), __("Group Name", true), __("First Name", true), __("Last Name", true), __("Student Number", true), __("Email", true), __("General Comments", true), __("Score", true), __("Specific Comments", true));

        fputcsv($fh, $header);
        foreach ($user_data as $key => $value) {
            $line=array();
            array_push($line, $group_name);
            array_push($line, $group_number);
            $score_total=0;
            //bad coding style
            foreach ($value['User'] as $value_2) {
                array_push($line, $value_2);
            }
            $general_comments='';
            //general comments
            foreach ($evaluation_rubric_general_data as $key => $array) {
                //1) get evaluatee id;
                $evaluatee_id=$array['EvaluationRubric']['evaluatee'];
                //2) get student number from user_data
                $student_number=$user_data[$evaluatee_id]['User']['student_no'];                //3) if current student is the evaluatee
                if ($value['User']['student_no']==$student_number) {
                    $general_comments=$general_comments.''.$array['EvaluationRubric']['comment'].';';
                    //add up score
                    $score_total=$score_total+$array['EvaluationRubric']['score'];
                }
            }

            array_push($line, $general_comments);
            array_push($line, $score_total);
            $specific_commens='';
            foreach ($evaluateesToEvaluators[$value['User']['student_no']] as $value) {
                //bad coding style
                foreach ($evaluation_rubric_specific_data[$value] as $array_2) {
                    $specific_commens=$specific_commens.''.$array_2['EvaluationRubricDetail']['criteria_comment'].';';
                }
            }
            array_push($line, $specific_commens);
            fputcsv($fh, $line);
            unset($line);
        }
        fclose($fh);
        header(__("Location:", true)." ../tmp/test/output.csv");



    }*/
}
