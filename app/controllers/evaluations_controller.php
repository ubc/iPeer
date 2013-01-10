<?php
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

    public $uses = array('SurveyQuestion', 'GroupEvent', 'EvaluationRubric', 'EvaluationRubricDetail',
        'EvaluationSubmission', 'Event', 'EvaluationSimple',
        'SimpleEvaluation', 'Rubric', 'Group', 'User',
        'GroupsMembers', 'RubricsLom', 'RubricsCriteria',
        'RubricsCriteriaComment', 'Personalize', 'Penalty',
        'Question', 'Response', 'Survey', 'SurveyInput', 'Course', 'MixevalsQuestion',
        'EvaluationMixeval', 'EvaluationMixevalDetail', 'Mixeval', 'MixevalsQuestionDesc');
    public $components = array('ExportBaseNew', 'Auth', 'AjaxList', 'Output',
        'userPersonalize', 'framework',
        'Evaluation', 'Export', 'ExportCsv', 'ExportExcel');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->set('title_for_layout', __('Evaluations', true));
        parent::__construct();
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
            array("None" => __("Not Released", true), "Some" => __("Some Released", true), "All" => __("Released", true))),
            array("GroupEvent.comment_release_status", __("Comment", true), "7em",   "map",
            array("None" => __("Not Released", true), "Some" => __("Some Released", true), "All" => __("Released", true))),

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

        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());

        $this->set('data', $event);
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
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $fileName . '.csv');
            switch($this->params['form']['export_type']) {
            case "csv" :
                $this->ExportCsv->createCsv($this->params['form'], $event);
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

    /**
     * makeEvaluation proxy method for makeing different evaluations
     *
     * @param mixed $eventId  event id
     * @param mixed $objectId object id
     *
     * @access public
     * @return void
     */
    function makeEvaluation($eventId, $objectId = null) {
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
            $this->_makeSimpleEvaluation($event, $objectId);
            break;
        case 2:
            $this->_makeRubricEvaluation($event, $objectId);
            break;
        case 3:
            $this->_makeSurveyEvaluation($event);
            break;
        case 4:
            $this->_makeMixevalEvaluation($event, $objectId);
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
        $this->SysParameter->reload();
        $email = User::get('email');
        if (empty($email)) {
            return;
        }

        if (!$this->TemplateEmail->send(array(User::get('id') => $email), 'Submission Confirmation')) {
            $this->log('Sending email to '.$email.' failed.'. $this->Email->smtpError);
            $this->Session->setFlash('Sending confirmation email failed!');
        }
    }

    /**
     * makeSimpleEvaluation
     *
     * @param mixed $event   event object
     * @param mixed $groupId group id
     *
     * @access public
     * @return void
     */
    function _makeSimpleEvaluation($event, $groupId)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];

        if (empty($this->params['data'])) {
            $group = array();
            $group_events = $this->GroupEvent->getGroupEventByEventId($eventId);
            $userId = User::get('id');
            foreach ($group_events as $events) {
                if ($this->GroupsMembers->checkMembershipInGroup($events['GroupEvent']['group_id'], $userId) !== 0) {
                    $group[] = $events['GroupEvent']['group_id'];
                }
            }

            // filter out users that don't have access to this eval, invalid ids
            if (!in_array($groupId, $group)) {
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
            $submission = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
            if (!empty($submission)) {
                // load the submitted values
                $evaluation =  $this->EvaluationSimple->getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, User::get('id'));
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
            $this->set('userId', $userId);
            $this->set('fullName', $this->Auth->user('full_name'));


            //Get Members for this evaluation
            $groupMembers = $this->GroupsMembers->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $userId);
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
                $this->_sendConfirmationEmail();
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
     * makeSurveyEvaluation
     *
     * @param mixed $event event
     *
     * @access public
     * @return void
     */
    function _makeSurveyEvaluation ($event)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];

        if (empty($this->params['data'])) {
            $courseId = $event['Event']['course_id'];
            $course = $this->Course->find('first', array(
                'conditions' => array(
                    'Course.id' => $courseId,
                    'Enrol.id' => $this->Auth->user('id')
                )
            ));

            // user is not an instructor of course or event is not a survey
            if (null == $course) {
                $this->Session->setFlash(__('Error: Invalid Id', true));
                $this->redirect('/home/index');
                return;
            }

            // students can submit again
            $submission = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, User::get('id'));
//            if (!empty($submission)) {
//                $this->Session->setFlash(__('Error: Survey has already been submitted', true));
//                $this->redirect('/home/index');
//                return;
//            }

            // students can't submit outside of release date range
            $event = $this->Event->getEventById($eventId);
            $now = time();

            if ($now < strtotime($event['Event']['release_date_begin']) ||
                $now > strtotime($event['Event']['release_date_end'])) {
                $this->Session->setFlash(__('Error: Survey is unavailable', true));
                $this->redirect('/home/index');
                return;
            }

            //Setup the courseId to session
            $courseId = $event['Event']['course_id'];
            $survey_id = $event['Event']['template_id'];

            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Survey', true));
            $this->set('survey_id', $survey_id);

            // Get all required data from each table for every question
            $survey = $this->Survey->getSurveyWithQuestionsById($survey_id);

            $this->set('event', $event);
            $this->set('courseId', $courseId);
            $this->set('eventId', $event['Event']['id']);
            $this->set('survey', $survey);
            $this->render('survey_eval_form');

        } else {
            $courseId = $this->params['form']['course_id'];
            $eventId = $this->params['form']['event_id'];
            if (!$this->validSurveyEvalComplete($this->params)) {
                $this->set('errmsg', 'validSurveyEvalCompleten failure.');
                //$this->redirect('/evaluations/makeEvaluation/'.$eventId);
            }
            if ($this->Evaluation->saveSurveyEvaluation($this->params)) {
                $this->Session->setFlash(__('Your survey was submitted successfully', true), 'good');
                $this->redirect('/home/index/');
                return;
            } else {
                $this->Session->setFlash(__('Your survey was not submitted successfully', true));
                $this->redirect('evaluations/makeEvaluation/'.$eventId);
                return;
            }
        }
    }


    /**
     * validSurveyEvalComplete
     *
     * @param bool $param
     *
     * @access public
     * @return void
     */
    function validSurveyEvalComplete($param = null)
    {
        return true;
    }

    /**
     * makeRubricEvaluation
     *
     * @param mixed $event   event object
     * @param mixed $groupId group id
     *
     * @access public
     * @return void
     */
    function _makeRubricEvaluation ($event, $groupId)
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

            $rubricId = $event['Event']['template_id'];
            $courseId = $event['Event']['course_id'];

            $group = array();
            $group_events = $this->GroupEvent->getGroupEventByEventId($eventId);
            $userId = User::get('id');
            foreach ($group_events as $events) {
                if ($this->GroupsMembers->checkMembershipInGroup($events['GroupEvent']['group_id'], $userId) !== 0) {
                    $group[] = $events['GroupEvent']['group_id'];
                }
            }

            // if group id provided does not match the group id the user belongs to or
            // template type is not rubric - they are redirected
            if (!in_array($groupId, $group)) {
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
            $this->set('event', $event);

            //Setup the viewData
            $rubricId = $event['Event']['template_id'];
            $rubric = $this->Rubric->getRubricById($rubricId);
            $rubricEvalViewData = $this->Rubric->compileViewData($rubric);
            $this->set('viewData', $rubricEvalViewData);
            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));

            $rubricDetail = $this->Evaluation->loadRubricEvaluationDetail($event, $groupId);
            $this->set('groupMembers', $rubricDetail['groupMembers']);
            $this->set('evaluateeCount', $rubricDetail['evaluateeCount']);

            $this->render('rubric_eval_form');
        } else {
            $eventId = $this->params['form']['event_id'];
            $groupId = $this->params['form']['group_id'];

            $courseId = $this->params['form']['course_id'];
            if (!$this->validRubricEvalComplete($this->params['form'])) {
                $this->Session->setFlash(__('validRubricEvalCompleten failure', true));
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }

            if ($this->Evaluation->saveRubricEvaluation($this->params)) {
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }
            //Found error
            else {
                //Validate the error why the Event->save() method returned false
                $this->validateErrors($this->Event);
                $this->Session->setFlash(__('Your evaluation was not saved successfully', true));
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }//end if
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
    function validRubricEvalComplete ($form=null)
    {
        $status = true;
        return $status;
    }


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
        $evaluateeCount = $this->params['form']['evaluateeCount'];

        $groupEventId = $this->params['form']['group_event_id'];
        //Get the target group event
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        $this->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];

        // if no submission exists, create one
        //Get the target event submission
        $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
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



        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEventId);
        $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
        //Check to see if all members are completed this evaluation
        if ($numOfCompletedCount == $evaluateeCount ) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->GroupEvent->save($groupEvent)) {
                $status = false;
            }
        }

        if ($status) {
            $this->_sendConfirmationEmail();
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
     * @param mixed $event   event object
     * @param mixed $groupId group id
     *
     * @access public
     * @return void
     */
    function _makeMixevalEvaluation ($event, $groupId)
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
            $userId = User::get('id');
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

            // students can submit again
            $submission = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));

            // students can't submit outside of release date range
            $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
            $now = time();

            if ($now < strtotime($event['Event']['release_date_begin']) ||
                $now > strtotime($event['Event']['release_date_end'])) {
                $this->Session->setFlash(__('Error: Evaluation is unavailable', true));
                $this->redirect('/home/index');
                return;
            }

            $penalty = $this->Penalty->getPenaltyByEventId($eventId);
            $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
            $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
            $this->set('penaltyFinal', $penaltyFinal);
            $this->set('penaltyDays', $penaltyDays);
            $this->set('penalty', $penalty);
            $this->set('event', $event);
            //Setup the courseId to session
            $courseId = $event['Event']['course_id'];
            $this->set('courseId', $courseId);
            $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));
            $mixEvalDetail = $this->Evaluation->loadMixEvaluationDetail($event);
            $this->set('data', $mixEvalDetail['mixeval']);
            $this->set('groupMembers', $mixEvalDetail['groupMembers']);
            $this->set('evaluateeCount', $mixEvalDetail['evaluateeCount']);

            $this->render('mixeval_eval_form');
        } else {
            $eventId = $this->params['form']['event_id'];
            $groupId = $this->params['form']['group_id'];
            $courseId = $this->params['form']['course_id'];
            if (!$this->validMixevalEvalComplete($this->params['form'])) {
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }
            if ($this->Evaluation->saveMixevalEvaluation($this->params)) {
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }
            //Found error
            else {
                //Validate the error why the Event->save() method returned false
                $this->validateErrors($this->Event);
                $this->set('errmsg', __('Save Evaluation failure.', true));
                $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                return;
            }//end if
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
    function validMixevalEvalComplete ($form=null)
    {
        $status = true;
        return $status;
    }


    /**
     * completeEvaluationMixeval
     *
     *
     * @access public
     * @return void
     */
    function completeEvaluationMixeval ()
    {
        $status = true;

        $eventId = $this->params['form']['event_id'];
        $groupId = $this->params['form']['group_id'];
        $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
        $evaluateeCount = $this->params['form']['evaluateeCount'];

        $groupEventId = $this->params['form']['group_event_id'];
        //Get the target group event
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        $this->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];

        // if no submission exists, create one
        //Get the target event submission
        $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
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

        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEventId);
        $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
        //Check to see if all members are completed this evaluation
        if ($numOfCompletedCount == $evaluateeCount ) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->GroupEvent->save($groupEvent)) {
                $status = false;
            }
        }

        if ($status) {
            $this->_sendConfirmationEmail();
            $this->Session->setFlash(__('Your Evaluation was submitted successfully.', true), 'good');
            $this->redirect('/home/index/', true);
            return;
        } else {
            $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
            return;
        }
    }


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
        }

        $this->autoRender = false;

        $this->set('event', $event);
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $event['Course']))
            ->push(array('event' => $event['Event']))
            ->push(__('Results', true)));


        switch ($event['Event']['event_template_type_id'])
        {
        case 1:  //View Simple Evaluation Result
            $formattedResult = $this->Evaluation->formatSimpleEvaluationResult($event);
            $this->set('scoreRecords', $formattedResult['scoreRecords']);
            $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
            $this->set('evalResult', $formattedResult['evalResult']);
            $this->set('groupMembers', $formattedResult['groupMembers']);
            $this->set('groupMembersNoTutors', $formattedResult['groupMembersNoTutors']);
            $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
            $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
            $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
            // Set penalty
            $penalties = $this->SimpleEvaluation->formatPenaltyArray($formattedResult['groupMembersNoTutors'], $eventId, $groupId);
            $this->set('penalties', $penalties);
            $this->render('view_simple_evaluation_results');
            break;

        case 2: //View Rubric Evaluation

            $formattedResult = $this->Evaluation->formatRubricEvaluationResult($event, $displayFormat);
            $this->set('rubric', $formattedResult['rubric']);
            if (isset($formattedResult['groupMembers'])) {
                $this->set('groupMembers', $formattedResult['groupMembers']);
            }
            if (isset($formattedResult['groupMembersNoTutors'])) {
                $this->set('groupMembersNoTutors', $formattedResult['groupMembersNoTutors']);
                $members = array();
                foreach ($formattedResult['groupMembersNoTutors'] as $user) {
                    $members[$user['User']['id']] = $user['User']['first_name'].' '.$user['User']['last_name'];
                }
                $this->set('summaryMembers', $members);
            }
            if (isset($formattedResult['reviewEvaluations'])) {
                $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
            }
            if (isset($formattedResult['rubric']['RubricsCriteria'])) {
                $this->set('rubricCriteria', $formattedResult['rubric']['RubricsCriteria']);
            }
            $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
            $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
            $this->set('scoreRecords', $formattedResult['scoreRecords']);
            $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
            $this->set('evalResult', $formattedResult['evalResult']);
            $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
            // set penalty data
            $formattedPenalty = $this->Rubric->formatPenaltyArray($formattedResult['groupMembersNoTutors'], $eventId, $groupId);
            $this->set('penalties', $formattedPenalty);

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
            $formattedResult = $this->Evaluation->formatMixevalEvaluationResult($event, $displayFormat);
            $this->set('mixeval', $formattedResult['mixeval']);
            if (isset($formattedResult['groupMembers'])) {
                $this->set('groupMembers', $formattedResult['groupMembers']);
            }
            if (isset($formattedResult['groupMembersNoTutors'])) {
                $this->set('groupMembersNoTutors', $formattedResult['groupMembersNoTutors']);
            }
            if (isset($formattedResult['reviewEvaluations'])) {
                $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
            }

            $this->set('mixevalQuestion', $formattedResult['mixevalQuestion']);
            $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
            $this->set('scoreRecords', $formattedResult['scoreRecords']);
            $this->set('evalResult', $formattedResult['evalResult']);

            // Set Penalty
            $penalties = $this->Mixeval->formatPenaltyArray($formattedResult['groupMembersNoTutors'], $eventId, $groupId);
            $this->set('penalties', $penalties);

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
     *
     * @access public
     * @return void
     */
    function studentViewEvaluationResult($eventId, $groupId = null)
    {
        $this->autoRender = false;

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
                !($group = $this->Group->getGroupByGroupIdEventIdMemberId($groupId, $eventId, User::get('id')))) {

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
            $this->render('student_view_simple_evaluation_results');
            break;

        case 2: //View Rubric Evaluation Result
            $formattedResult = $this->Evaluation->formatRubricEvaluationResult($event, 'Detail', 1, User::get('id'));
            $this->set('rubric', $formattedResult['rubric']);
            if (isset($formattedResult['groupMembers'])) {
                $this->set('groupMembers', $formattedResult['groupMembers']);
            }
            if (isset($formattedResult['reviewEvaluations'])) {
                $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
            }
            if (isset($formattedResult['rubric']['RubricsCriteria'])) {
                $this->set('rubricCriteria', $formattedResult['rubric']['RubricsCriteria']);
            }
            $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
            $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
            $this->set('scoreRecords', $formattedResult['scoreRecords']);
            $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
            $this->set('evalResult', $formattedResult['evalResult']);
            $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
            $this->set('penalty', $formattedResult['penalty']);

            $ratingPenalty = $formattedResult['memberScoreSummary'][$userId]['received_ave_score'] * ($formattedResult['penalty'] / 100);
            $this->set('ratingPenalty', $ratingPenalty);

            $this->render('student_view_rubric_evaluation_results');
            break;

        case 3: //View Survey Result
            $answers = array();
            $formattedResult = $this->Evaluation->formatSurveyEvaluationResult($event, User::get('id'));

            foreach ($formattedResult['answers'] as $answer) {
                $answers[$answer['SurveyInput']['question_id']][] = $answer;
            }

            $this->set('survey_id', $formattedResult['survey_id']);
            $this->set('answers', $answers);
            $this->set('questions', $formattedResult['questions']);
            $this->render('student_view_survey_evaluation_results');
            break;

        case 4: //View Mix Evaluation Result
            $formattedResult = $this->Evaluation->formatMixevalEvaluationResult($event, 'Detail', 1);
            $this->set('mixeval', $formattedResult['mixeval']);
            if (isset($formattedResult['groupMembers'])) {
                $this->set('groupMembers', $formattedResult['groupMembers']);
            }
            if (isset($formattedResult['reviewEvaluations'])) {
                $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
            }
            if (isset($formattedResult['mixevalQuestion'])) {
                $this->set('mixevalQuestion', $formattedResult['mixevalQuestion']);
            }
            $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
            $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
            $this->set('scoreRecords', $formattedResult['scoreRecords']);
            $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
            $this->set('evalResult', $formattedResult['evalResult']);
            $this->set('penalty', $formattedResult['penalty']);

            $avePenalty = $formattedResult['memberScoreSummary'][$userId]['received_total_score'] * ($formattedResult['penalty'] / 100);
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
        $display_format = $this->params['form']['display_format'];

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
        $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/'.$display_format);
    }


    /**
     * markGradeRelease
     *
     * @param mixed $param
     *
     * @access public
     * @return void
     */
    function markGradeRelease($param)
    {
        // Make sure the present user has permission
        if (!User::hasPermission('functions/evaluation')) {
            $this->Session->setFlash('Error: You do not have permission to release grades', true);
            $this->redirect('/home');
            return;
        }

        $tok = strtok($param, ';');
        $eventId = $tok;
        $groupId =  strtok(';');
        $evaluateeId =  strtok(';');
        $groupEventId = strtok(';');
        $releaseStatus = strtok(';');

        $this->autoRender = false;

        $this->Event->id = $eventId;
        $event = $this->Event->read();

        switch ($event['Event']['event_template_type_id']) {
        case "1":
            $this->Evaluation->changeSimpleEvaluationGradeRelease($groupEventId, $evaluateeId, $releaseStatus);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId);
            break;

        case "2":
            $this->Evaluation->changeRubricEvaluationGradeRelease($groupEventId,
                $evaluateeId, $releaseStatus);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
            break;

        case "4":
            $this->Evaluation->changeMixevalEvaluationGradeRelease($groupEventId,
                $evaluateeId, $releaseStatus);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
            break;
        }

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
            $eventId = $this->params['form']['event_id'];
        }

        // Check whether the event exists or user has permission to access it
        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission(), array()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
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
            }

            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId);
            break;

        case "2":
            $groupId =  strtok(';');
            $evaluateeId =  strtok(';');
            $groupEventId = strtok(';');
            $releaseStatus = strtok(';');
            $this->Evaluation->changeRubricEvaluationCommentRelease($groupEventId, $evaluateeId, $releaseStatus);
            $this->redirect('viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
            break;

        case "4":
            $groupId =  strtok(';');
            $evaluateeId =  strtok(';');
            $groupEventId = strtok(';');
            $releaseStatus = strtok(';');
            $this->Evaluation->changeMixevalEvaluationCommentRelease($groupEventId, $evaluateeId, $releaseStatus);
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

        switch ($event['Event']['event_template_type_id']) {
        case 1://simple
            $this->EvaluationSimple->setAllEventCommentRelease($eventId, $releaseStatus);
            break;
        case 2://rubric
            $this->EvaluationRubric->setAllEventCommentRelease($eventId, $releaseStatus);
            break;
        case 4://mix
            $this->EvaluationMixeval->setAllEventCommentRelease($eventId, $releaseStatus);
            break;
        default:
            break;
        }


        //Update all groupEvent's comment release Status based on submission
        $groupEventList = $this->GroupEvent->getGroupListByEventId($eventId);
        foreach ($groupEventList as $groupEvent) {
            $this->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];

            //Get the total number of members who has completed this evaluation
            $numOfCompletedCount = $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent['GroupEvent']['id']);
            //$numMembers = $this->GroupsMembers->find(count, 'group_id='.$groupEvent['GroupEvent']['group_id']);
            $numMembers = $this->GroupsMembers->find('count', array('conditions' => array('group_id' => $groupEvent['GroupEvent']['group_id'])));

            if (($numOfCompletedCount != 0) && ($numOfCompletedCount < $numMembers)) {
                $completeStatus = 'Some';
            } elseif ($releaseStatus && ($numOfCompletedCount == $numMembers)) {
                $completeStatus = 'All';
            } else {
                $completeStatus = 'None';
            }

            if ($releaseStatus == 0) {
                $groupEvent['GroupEvent']['comment_release_status'] = 'None';
            } else {
                $groupEvent['GroupEvent']['comment_release_status'] = $completeStatus;
            }
            $this->GroupEvent->save($groupEvent);
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
            break;
        case 2://rubric
            $this->EvaluationRubric->setAllEventGradeRelease($eventId, $releaseStatus);
            break;
        case 4://mix
            $this->EvaluationMixeval->setAllEventGradeRelease($eventId, $releaseStatus);
            break;
        default:
            break;
        }


        //Update all groupEvent's grade release Status based on submission
        $groupEventList = $this->GroupEvent->getGroupListByEventId($eventId);
        foreach ($groupEventList as $groupEvent) {
            $this->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];

            //Get Members count whom completed evaluation
            $numOfCompletedCount = $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent['GroupEvent']['id']);
            $numMembers = $this->GroupsMembers->find('count', array('conditions' => array('group_id' => $groupEvent['GroupEvent']['group_id'])));
            if (($numOfCompletedCount != 0) && ($numOfCompletedCount < $numMembers)) {
                $completeStatus = 'Some';
            } elseif ($releaseStatus && ($numOfCompletedCount == $numMembers)) {
                $completeStatus = 'All';
            } else {
                $completeStatus = 'None';
            }

            if ($releaseStatus == 0) {
                $groupEvent['GroupEvent']['grade_release_status'] = 'None';
            } else {
                $groupEvent['GroupEvent']['grade_release_status'] = $completeStatus;
            }
            $this->GroupEvent->save($groupEvent);
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
        $event = $this->Event->find('first', array(
            'conditions' => array(
                'id' => $eventId
            ),
            'contain' => false,
        ));
        if (null == $event) {
            $this->Session->setFlash(__('Error: Invalid event Id', true));
            $this->redirect('index');
            return;
        }

        // Check that $surveyId is valid
        $survey = $this->Survey->find('first', array(
            'conditions' => array(
                'id' => $event['Event']['template_id']
            ),
            'contain' => false,
        ));
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
            ->push(array('survey' => $survey['Survey']))
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
    function export_rubic($eventId, $rubicEvalId)
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



    }


    /**
     * export_test
     *
     * @param bool $eventId
     * @param bool $groupID
     *
     * @access public
     * @return void
     */
    function export_test($eventId=null, $groupID=null)
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



    }
}
