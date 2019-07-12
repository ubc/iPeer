<?php
App::import('Model', 'EvaluationBase');
App::import('Model', 'MixevalQuestion');
App::import('Model', 'MixevalQuestionDesc');
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

/**
 * Event
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Event extends AppModel
{
    public $name = 'Event';
    /* Accordion's panelHeight - Height various based on the no. of questions and evaluation types*/
    public $SIMPLE_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');
    public $RUBRIC_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');
    const DATETIMEREGEX = '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/';

    // key => values; values are column names in events table
    public static $importSurveyCutoff = 6; // last column that applies to surveys
    public static $importColumns = array(
            'Title' => 'title', //0
            'Description' => 'description', //1
            'Type' => 'event_template_type_id', //2
            'Template' => 'template_id', //3
            'Opens' => 'release_date_begin', //4
            'Due' => 'due_date', //5
            'Closes' => 'release_date_end', //6
            // surveys don't have the following fields
            'Results Open' => 'result_release_date_begin',
            'Results Close' => 'result_release_date_end',
            'Self-Evaluation?' => 'self_eval',
            'Comments required?' => 'com_req',
            'Auto-release results?' => 'auto_release',
            'Student result mode' => 'enable_details'
            /* Groups will always be the last item
            but is not listed here because it belongs 
            to a different table */
            
    );
    
    private $insertedAutoRelease = array();
    
    public $_backupValidate = null;
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Title is required.',
            'allowEmpty' => false
        ),
        'course_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a course.'
        ),
        'event_template_type_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a template type.'
        ),
        'template_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a template.'
        ),
        'self_eval' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a self-eval option.'
        ),
        'com_req' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select whether comments are required.'
        ),
        'due_date' => array(
            'rule' => self::DATETIMEREGEX,
            'message' => 'Must be in Year-Month-Day Hour:Minute:Second format.'
        ),
        'release_date_begin' => array(
            'rule' => self::DATETIMEREGEX,
            'message' => 'Must be in Year-Month-Day Hour:Minute:Second format.'
        ),
        'release_date_end' => array(
            'rule' => self::DATETIMEREGEX,
            'message' => 'Must be in Year-Month-Day Hour:Minute:Second format.'
        ),
        'result_release_date_begin' => array(
            'rule' => self::DATETIMEREGEX,
            'message' => 'Must be in Year-Month-Day Hour:Minute:Second format.'
        ),
        'result_release_date_end' => array(
            'rule' => self::DATETIMEREGEX,
            'message' => 'Must be in Year-Month-Day Hour:Minute:Second format.'
        )
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

    /* tables related: events, group_events,
     * evaluation_submissions,
     * evaluation_rubrics, evaluation_rubrics_details,
     * evaluation_simples,
     * evaluation_mixevals, evaluation_mixeval_details
     */

    public $belongsTo = array('EventTemplateType', 'Course');

    public $hasAndBelongsToMany = array('Group' =>
        array('className'    =>  'Group',
            'joinTable'    =>  'group_events',
            'foreignKey'   =>  'event_id',
            'associationForeignKey'    =>  'group_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
    );

    public $hasMany = array(
        'GroupEvent' =>
        array(
            'className' => 'GroupEvent',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'EvaluationSubmission' =>
        array(
            'className' => 'EvaluationSubmission',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'Penalty' =>
        array(
            'className' => 'Penalty',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'EvaluationSimple' =>
        array(
            'className' => 'EvaluationSimple',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'EvaluationRubric' =>
        array(
            'className' => 'EvaluationRubric',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'SurveyInput' =>
        array(
            'className' => 'SurveyInput',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
        'EvaluationMixeval' =>
        array(
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'event_id'
        ),
    );

    private $timezone = null;

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        $this->SysParameter = ClassRegistry::init('SysParameter');
        $timezone = $this->SysParameter->findByParameterCode('system.timezone');
        // default to UTC if no timezone is set
        if (!(empty($timezone) || empty($timezone['SysParameter']['parameter_value']))) {
            $timezone = $timezone['SysParameter']['parameter_value'];
        } else if (ini_get('date.timezone')) {
            $timezone = ini_get('date.timezone');
        } else {
            $timezone = 'UTC';
        }
        // check that the timezone is valid
        $validTZ = array_flip(DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC));
        if (!isset($validTZ[$timezone])) {
            $timezone = (ini_get('date.timezone') && isset($validTZ[ini_get('date.timezone')])) ?
                ini_get('date.timezone') : 'UTC';
        }
        date_default_timezone_set($timezone);
        $this->timezone = $timezone;
        parent::__construct($id, $table, $ds);
        $this->virtualFields['response_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as sub WHERE sub.event_id = %s.id', $this->alias);
        $this->virtualFields['to_review_count'] = sprintf('SELECT count(*) as count FROM group_events as ge WHERE ge.event_id = %s.id AND marked LIKE "to review"', $this->alias);
        $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM group_events as vge RIGHT JOIN groups_members as vgm ON vge.group_id = vgm.group_id WHERE vge.event_id = %s.id', $this->alias);
        $this->virtualFields['group_count'] = sprintf('SELECT count(*) as count FROM group_events as vge WHERE vge.event_id = %s.id', $this->alias);
        $this->virtualFields['completed_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as ves WHERE ves.submitted = 1 AND ves.event_id = %s.id', $this->alias);

        /**
         * The following does time correction for daylight savings.
         * SQL does not recognize daylight savings, therefore when not in a daylight savings period, the timezone is 1hr (3600s) behind the actual time.
         * NOTE: UNIX_TIMESTAMP() appears to work, but online sources say otherwise.
         */
//        $dueIn = "";
//        if(date('I') == 1){
//            $dueIn = date('Y-m-d H:i:s', time());
//        }
//        else{
//            $dueIn = date('Y-m-d H:i:s', time() + 3600);
//        }
//        $this->virtualFields['due_in'] = sprintf('TIMESTAMPDIFF(SECOND,"%s",due_date)', $dueIn);
        //$this->virtualFields['due_in'] = sprintf('UNIX_TIMESTAMP(due_date) - UNIX_TIMESTAMP("%s")', date('Y-m-d H:i:s'));
        //$this->virtualFields['due_in'] = sprintf('TIMESTAMPDIFF(SECOND,"%s",due_date)', date('Y-m-d H:i:s'));
    }

    /**
     * afterFind
     *
     * @param array $results
     * @param mixed $primary
     *
     * @access public
     * @return mixed
     */
    function afterFind($results, $primary = false)
    {
        $currentDate = time();
        foreach ($results as $key => $event) {
            if (isset($event['Event']['release_date_begin'])) {
                $results[$key]['Event']['is_released']  =
                    ($currentDate >= strtotime($event['Event']['release_date_begin']) &&
                    $currentDate < strtotime($event['Event']['release_date_end']));
            }
            if (isset($event['Event']['result_release_date_begin'])) {
                $results[$key]['Event']['is_result_released']  =
                    ($currentDate >= strtotime($event['Event']['result_release_date_begin']) &&
                    $currentDate < strtotime($event['Event']['result_release_date_end']));
            }
            if (isset($event['Event']['release_date_end'])) {
                $results[$key]['Event']['is_ended']  =
                    ($currentDate > strtotime($event['Event']['release_date_end']));
            }
            if (isset($event['Event']['due_date'])) {
                $due = new DateTime($event['Event']['due_date'], new DateTimeZone($this->timezone));
                $results[$key]['Event']['due_in']  = $due->getTimestamp() - $currentDate;
            }
        }

        return $results;
    }

    /**
     * beforeValidate
     *
     * @access public
     * @return void
     */
    function beforeValidate($options = array())
    {
        if ($this->data['Event']['event_template_type_id'] == 3) {
            // remove the result release validation
            $this->_backupValidate = $this->validate;
            unset($this->validate['result_release_date_begin']);
            unset($this->validate['result_release_date_end']);
            unset($this->validate['com_req']);
            unset($this->validate['self_eval']);
        }

        return true;
    }

    /**
     * beforeSave
     *
     * @param array $options options
     *
     * @access public
     * @return void
     */
    function beforeSave($options = array()) {
        if (isset($this->data['Group']['Group']) && isset($this->data[$this->alias]['id'])) {
            $this->GroupEvent->updateGroups($this->data[$this->alias]['id'], $this->data['Group']['Group']);
            unset($this->data['Group']);
        }

        return true;
    }

    /**
     * afterSave
     *
     * @param mixed $created
     *
     * @access public
     * @return void
     */
    function afterSave($created) {
        parent::afterSave($created);

        // restore the validate if it is been changed
        if (null != $this->_backupValidate) {
            $this->validate = $this->_backupValidate;
            $this->_backupValidate = null;
        }
        if($created && isset($this->data['Event']['auto_release']) && $this->data['Event']['auto_release']==1) {
            $this->insertedAutoRelease[] = $this->getInsertID();
        }

        CaliperHooks::event_after_save($this, $created);
    }
    /**
     * prepData
     * parses the grous from the hidden field assigned
     *
     * @param bool $data
     *
     * @access public
     * @return void
     */
    function prepData($data=null)
    {
        $tmp = $data['form']['assigned'];
        $num = null;
        $member_count=0;

        // parse group member id
        for ($i=0; $i<strlen($tmp); $i++) {
            if ($tmp{$i} != ":" ) {
                $num = $num.$tmp{$i};
            } else {
                $member_count++;
                $data['data']['Event']['group'.$member_count] = $num;
                $num = "";
            }

            if ($i == (strlen($tmp)-1) ) {
                $member_count++;
                $data['data']['Event']['group'.$member_count] = $num;
            }
        }

        $data['data']['Event']['group_count'] = $member_count;

        //print_r($data);
        return $data;
    }


    /**
     * Get event by course id
     *
     * @param int $courseId course id
     *
     * @return array with event info
     */
    function getCourseEvent($courseId=null)
    {
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId)
        ));
    }

    /**
     * Get course evaluation events by course id
     *
     * @param int $courseId course id
     *
     * @return course evaluation events
     */
    function getCourseEvalEvent($courseId=null)
    {
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id !=' => '3')
        ));
    }

    /**
     * Number of events for a course
     *
     * @param int $courseId course id
     *
     * @return count of course events
     */
    function getCourseEventCount($courseId=null)
    {
        return $this->find('count', array(
            'conditions' => array('course_id' => $courseId)
        ));
    }

    /**
     * Get course for an event
     *
     * @param int $eventId event id
     *
     * @return course by event id
     */

    function getCourseByEventId($eventId)
    {
        $tmp = $this->find('all', array('conditions'=>array('Event.id' => $eventId), 'fields'=>array('course_id')));
        return $tmp[0]['Event']['course_id'];
    }


    /**
     * Get active survey events by course id
     *
     * @param mixed $courseId course id
     * @param mixed $type     type
     *
     * @return array of survey events
     */
    function getActiveSurveyEvents($courseId = null, $type='all')
    {
        return $this->find($type, array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id' => '3', 'Event.record_status !='=>'I')
        ));
    }

    /**
     * getSurveyByCourseIdTemplateId
     *
     * @param mixed $courseIds  course ids
     * @param mixed $templateId template id
     * @param mixed $type       type
     *
     * @access public
     * @return void
     */
    function getSurveyByCourseIdTemplateId($courseIds, $templateId, $type='list')
    {
        return $this->find($type, array(
            'conditions' => array(
                'Event.event_template_type_id' => 3,
                'Event.template_id' => $templateId,
                'Event.course_id' => $courseIds
            )
        ));
    }



    /**
     * cascadeRemove
     * TODO: unfinished function
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    /*function cascadeRemove($id)
    {
        // tables related: events, group_events,
        // evaluation_submissions,
        // evaluation_rubrics, evaluation_rubrics_details,
        // evaluation_simples,
        // evaluation_mixevals, evaluation_mixeval_details

        if ($id) {
            $this->id = $id;
        }

        $event = $this->read(null, $id);

        // delete evaluation_mixevals and evaluation_mixeval_details
        $evaluation_mixeval = new EvaluationMixeval();
        $evaluation_mixeval_detail = new EvaluationMixevalDetail();

        $ems = $evaluation_mixeval->find('all', array(
            'conditions' => array('event_id' => $this->id)
        ));
        if (!empty($ems)) {
            foreach ($ems as $em) {
                $emds = $evaluation_mixeval_detail->find('all', array(
                    'conditions' => array('evaluation_mixeval_id' => $em->id)
                ));

                if (!empty($emds)) {
                    foreach ($emds as $emd) {
                        $emd->delete();
                    }
                }
                $em->delete();
            }
        }

        // delete evaluation_rubrics and evaluation_rubrics_details
        $evaluation_rubric = new EvaluationRubric();
        $evaluation_rubric_detail = new EvaluationRubricDetail();

        // delete evaluation_simples
        $evaluation_simple = new EvaluationSimple();

        // delete evaluation_submissions

        // delete group_events

        // now, delete this event
        $this->delete();
    }*/


    /**
     * removeEventsBySurveyId remove all events associated with a survey by survey ID
     *
     * @param mixed $survey_id
     *
     * @access public
     * @return void
     */
    function removeEventsBySurveyId($survey_id)
    {
        $events = $this->find('all', array(
            'conditions' => array($this->name.'.event_template_type_id' => '3' , $this->name.'.template_id' => $survey_id)
        ));
        if (empty($events)) {
            return true;
        }

        foreach ($events as $e) {
            $this->cascadeRemove($e[$this->name]['id']);
        }
        return true;
    }

    /**
     * Check if event is late
     *
     * @param int $eventID event id
     *
     * @return true if late, false otherwise
     */

    function checkIfNowLate($eventID)
    {
        if (is_numeric($eventID)) {
            $count = $this->find('count', array(
                'conditions' => array('Event.due_date < ' => date('Y-m-d H:i:s'), 'Event.id' => $eventID)
            ));
            return ($count>0);
        } else {
            return false;
        }

    }

    /**
     * getUnassignedGroups get unassigned groups for an event from the course
     *
     * @param mixed $event              event array
     * @param mixed $assigned_group_ids already assigned group ids
     *
     * @access public
     * @return array of unassigned groups
     */
    function getUnassignedGroups($event, $assigned_group_ids = null)
    {
        $group = Classregistry::init('Group');

        if (!is_array($event) || !isset($event['Event']['id'])) {
            return array();
        }

        if (!isset($event['Event']['course_id'])) {
            $event = $this->find('first', array('conditions' => array('Event.id' => $event['Event']['id']),
                'contain' => array()));
        }

        if (null == $assigned_group_ids && !isset($event['Group'])) {
            $assigned_group_ids = array_keys($group->find('list', array('conditions' => array('Event.id' => $event['Event']['id']))));
        } elseif(null == $assigned_group_ids && isset($event['Group'])){
            $assigned_group_ids = Set::extract('/Group/id', $event);
        }

        return $group->find('list', array('conditions' => array('course_id' => $event['Event']['course_id'],
            'NOT' => array('Group.id' => $assigned_group_ids)),
        'fields'=> array('Group.group_name')));
    }

    /**
     * Get event by event id
     *
     * @param int $id event id
     *
     * @return array with event info
     */
    function getEventById($id)
    {
        return $this->find('first', array(
            'conditions' => array('Event.id' => $id)
        ));
    }

    /**
     * Get event template ty id by event id
     *
     * @param int $id event id
     *
     * @return event template type id
     */
    function getEventTemplateTypeId($id)
    {
        $this->recursive = 0;
        $event = $this->find('first', array(
            'conditions' => array('Event.id' => $id),
            'fields' => array('Event.event_template_type_id')
        ));

        return $event['Event']['event_template_type_id'];
    }


    /**
     * getEventByIdGroupId
     *
     * @param mixed $eventId event id
     * @param bool  $groupId group id
     *
     * @access public
     * @return void
     */
    function getEventByIdGroupId($eventId, $groupId=null)
    {
        $this->recursive = 0;
        $conditions['Event.id'] = $eventId;
        $contain = array();
        if ($groupId != null) {
            $conditions['Group.id'] = $groupId;
            $contain[] = 'Group';
        }

        $event = $this->find('first', array(
            'conditions' => $conditions,
            'contain' => $contain,
        ));

        return $event;
    }


    /**
     * Get event title by event id
     *
     * @param int $id event id
     *
     * @return event title
     */
    function getEventTitleById($id)
    {
        $this->recursive = -1;
        $event = $this->find('first', array(
            'conditions' => array('Event.id' => $id),
            'fields' => array('Event.title')
        ));
        return $event['Event']['title'];
    }

    /**
     * Get fields of all events by course id
     *
     * @param mixed $courseId course id
     * @param mixed $fields  fields
     *
     * @return events
     */
    function getEventFieldsByCourseId($courseId, $fields)
    {
        $events = $this->find('all', array(
            'conditions' => array('course_id' => $courseId),
            'fields' => $fields,
            'contain' => array()));

        return $this->filterFields($events, $fields);
    }

    /**
     * Get fields of all event by event id
     *
     * @param mixed $eventId event id
     * @param mixed $fields  fields
     *
     * @return events
     */
    function getEventFieldsByEventId($eventId, $fields)
    {
        $events = $this->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'fields' => $fields,
            'contain' => array()));

        $result = $this->filterFields($events, $fields);
        if (!empty($result)) {
            $result = $result[0];
        }
        return $result;
    }

    function filterFields($events, $fields) {
        $result = array();
        if (!$events) {
            return array();
        }

        if (array_key_exists('Event', $events)) {
            $events = array($events);
        }
        foreach ($events as $e) {
            foreach ($e['Event'] as $key => $value) {
                if (!in_array($key, $fields)) {
                    unset($e['Event'][$key]);
                }
            }
            $result[] = $e['Event'];
        }

        return $result;
    }
    /**
     * Get evaluations and surveys assigned to the given user. Also gets the
     * evaluation submission entries made by this specific user.
     *
     * @param mixed $userId user id
     * @param mixed $fields the fields to retreive
     * @param mixed $extraId
     *
     * @access public
     * @return array array of events with related models, e.g. course, group, submission
     */
    function getEventsByUserId($userId, $fields = null, $extraId = null)
    {
        ini_set('display_errors', 1);
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
        $evaluationFields = $surveyFields = $fields;
        if ($evaluationFields != null) {
            $evaluationFields[] = 'GroupEvent.*';
        }
        if ($extraId) {
            if (is_array($extraId)) {
                $courseIds = array();
                foreach ($extraId as $course) {
                    $courseIds[] = $course;
                }
            } else {
                $courses = $this->Course->getCourseByInstructor($extraId);
                $courseIds = array();
                foreach ($courses as $course) {
                    $courseIds[] = $course['Course']['id'];
                }
            }
            $groups = $this->Group->find('all', array(
                'fields' => 'id',
                'conditions' => array('Member.id' => $userId, 'course_id' => $courseIds),
                'contain' => array('Member', 'GroupEvent.id')));

            $groupEventIds = Set::extract('/GroupEvent/id', $groups);
        } else {
            // get the groups that this user is in
            $groups = $this->Group->find('all', array(
                'fields' => 'id',
                'conditions' => array('Member.id' => $userId),
                'contain' => array('Member', 'GroupEvent.id')));
            $groupEventIds = Set::extract('/GroupEvent/id', $groups);
        }
        // find evaluation events based on the groups this user is in
        $evaluationEvents = $this->find('all', array(
            'fields' => $evaluationFields,
            'conditions' => array('GroupEvent.id' => $groupEventIds),
            'order' => array('due_date ASC'),
            'contain' => array(
                'Course',
                'Group',
                'GroupEvent',
                'Penalty' => array(
                    'order' => array('days_late ASC')
                ),
                'EvaluationSubmission' => array(
                    'conditions' => array(
                        'submitter_id' => $userId,
                        'submitted' => 1,
                    ),
                )
            )
        ));

        // as there should be only one submission for each event+group+user,
        // we need to find out the correct submission by grp_event_id, then overwrite
        // the EvaluationSubmission array in the result
        foreach ($evaluationEvents as &$event) {
            $hasSubmission = false;
            foreach ($event['EvaluationSubmission'] as $submission) {
                if ($submission['grp_event_id'] == $event['GroupEvent']['id']) {
                    $hasSubmission = true;
                    $event['EvaluationSubmission'] = $submission;
                    break;
                }
            }
            if (!$hasSubmission) {
                // no submission matched, set EvaluationSubmission to emtpy
                $event['EvaluationSubmission'] = array();
            }
        }

        if (empty($courseIds)) {
            // to find the surveys, we need to find the courses that user is enrolled in
            // can't use find('list') as we are query the conditions on HABTM
            $courses = $this->Course->find('all', array(
                'fields' => array('id'),
                'conditions' => array('Enrol.id' => $userId),
                'contain' => 'Enrol',
            ));
            $courseIds = Set::extract($courses, '/Course/id');
        }
        // find survey events based on the groups this user is in
        $surveyEvents = $this->find('all', array(
            'fields' => $surveyFields,
            'conditions' => array('event_template_type_id' => '3', 'course_id' => $courseIds),
            'order' => array('due_date ASC'),
            'contain' => array(
                'Course',
                'EvaluationSubmission' => array(
                    'conditions' => array(
                        'submitter_id' => $userId,
                        'submitted' => 1,
                    ),
                ),
            )
        ));
        // some clean up for submission
        foreach ($surveyEvents as &$event) {
            if (isset($event['EvaluationSubmission'][0])) {
                $event['EvaluationSubmission'] = $event['EvaluationSubmission'][0];
            }
        }

        return array('Evaluations' => $evaluationEvents,
            'Surveys' => $surveyEvents);
    }

    /**
     * getPendingEventsByUserId
     * get all the events that are open and user haven't submitted for user with id = userId
     *
     * @param mixed $userId  user id
     * @param mixed $options options
     * @param mixed $fields  the fields to retreive
     *
     * @access public
     * @return array
     */
    public function getPendingEventsByUserId($userId, $options, $fields = array())
    {
        $pendingEvents = array();
        $events = $this->getEventsByUserId($userId, $fields);
        $events = array_merge($events['Evaluations'], $events['Surveys']);
        foreach ($events as $event) {
            # temporary hack for removing due_in field as it is not a virtualfield anymore
            if (!array_key_exists('due_in', $fields)) {
                unset($event['Event']['due_in']);
            }

            if ($options['submission'] && $event['Event']['is_released'] && empty($event['EvaluationSubmission'])) {
                // filter for released evaluations that are available for submission
                $pendingEvents[] = $event['Event'];
            } else if ($options['results'] && isset($event['Event']['is_result_released']) &&
                $event['Event']['is_result_released']) {
                // filter for evaluations that have their results released
                $pendingEvents[] = $event['Event'];
            } else if (!$options['submission'] && !$options['results']) {
                // no filter implemented; return all events
                $pendingEvents[] = $event['Event'];
            }
        }

        return $pendingEvents;
    }

    /**
     * getAccessibleEventById
     *
     * @param mixed $eventId    event id
     * @param mixed $userId     user id
     * @param mixed $permission permission
     * @param array $contain    contain
     *
     * @access public
     * @return void
     */
    public function getAccessibleEventById($eventId, $userId, $permission, $contain = array())
    {
        $event = $this->find('first', array(
            'conditions' => array($this->alias.'.id' => $eventId),
            'contain' => $contain,
        ));

        if (empty($event)) {
            return false;
        }

        // check if the user has access to the course that event associated with
        $course = $this->Course->getAccessibleCourseById($event[$this->alias]['course_id'], $userId, $permission);
        if (empty($course)) {
            return false;
        }

        return $event;
    }

    /**
     * getEventSubmission
     *
     * @param mixed $eventId event id
     * @param mixed $userId  user id
     *
     * @access public
     * @return void
     */
    public function getEventSubmission($eventId, $userId)
    {
        return $this->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array('EvaluationSubmission' => array(
                'conditions' => array('EvaluationSubmission.submitter_id' => $userId)
        ))));
    }
    
    /**
     * csvExportEventsByCourseId
     *
     * @param mixed $courseId course id
     *
     * @return events
     */
    function csvExportEventsByCourseId($courseId)
    {
        $exportData = array(); // will get plugged into the csv generator
        $csvHeader = array(); // array for csv header / first row
        $tableColumns = array(); // names of relevant columns in events table
        
        foreach(Event::$importColumns as $key => $value) {
            $csvHeader[] = $key;
            $tableColumns[] = $value;
        }
        
        // add "Groups" to the header row
        $csvHeader[] = "Groups";

        // get number of groups in course for later
        $numGroups = $this->Group->getCourseGroupCount($courseId);
        
        $Survey = ClassRegistry::init('Survey'); // get survey type id later
        
        $events = $this->find('all', array(
            'conditions' => array('course_id' => $courseId),
            'fields' => $tableColumns,
            'callbacks' => false)); // avoid afterfind() which injects unneeded columns
        
        $exportData[] = $csvHeader; // add header to csv
        foreach($events as $value) {
            unset($value['Event']['id']); // id comes by default, so needs to be removed
            
            $groups = "";
            
            if($value['Event']['event_template_type_id'] == $Survey::TEMPLATE_TYPE_ID) {
                // remove release dates if a survey (sometimes have all zero dates)
                $value['Event']['result_release_date_begin'] = "";
                $value['Event']['result_release_date_end'] = "";
                // remove booleans 
                $value['Event']['self_eval'] = "";
                $value['Event']['com_req'] = "";
                $value['Event']['auto_release'] = "";
                $value['Event']['enable_details'] = "";
            } else if(isset($value['Group'])) {
                // otherwise, a non-survey event, so we can populate the groups
                if(count($value['Group'])==$numGroups) {
                    $groups = "*"; // event included all groups
                } else if(count($value['Group'])>0) {
                    // semi-colon separated list of groups (not all of the course groups)
                    $groups = implode(';',Set::classicExtract($value['Group'],'{n}.group_name'));
                }
            }
            
            $value['Event'][] = $groups; // add group to row
            $exportData[] = $value['Event']; // add current event row
        }
        
        return $exportData;
    }
    
    /**
     * importEventsByCsv
     *
     * @param int $courseId course id
     * @param mixed $events  $events
     * @param int $userId
     *
     * @return Integer || "Error Message"
     * (a number of successfully imported events or the error message)
     */
    function importEventsByCsv($courseId,$events,$userId)
    {
        
        // the row we are currently on (used for error reporting)
        $csvRow = 1;
        
        // if the header row is present, remove it
        if(isset($events[0][0]) && isset($events[0][1]) && $events[0][0] == "Title" && $events[0][1] == "Description") {
            unset($events[0]);
            $csvRow += 1;
        }
        
        if(empty($events)) {
            return __('Error: No events to import.',true);
        }
        
        $validatedEvents = array();
        
        // load up the models that are needed for validation
        $SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');
        $Rubric = ClassRegistry::init('Rubric');
        $Survey = ClassRegistry::init('Survey');
        $Mixeval = ClassRegistry::init('Mixeval');
        $Group = ClassRegistry::init('Group');
        
        // get ids for event types; used later in validation
        $SimpleEvaluationId = $SimpleEvaluation::TEMPLATE_TYPE_ID;
        $RubricId = $Rubric::TEMPLATE_TYPE_ID;
        $SurveyId = $Survey::TEMPLATE_TYPE_ID;
        $MixevalId = $Mixeval::TEMPLATE_TYPE_ID;
        
        // have caches for retrieved events from the database
        // due to the tendency for the same template to be used multiple times
        // validated = template exists and user has access to it
        $validatedEventCache = array(
            $SimpleEvaluationId => array(), // all validated simpleevals
            $RubricId => array(), // all validated rubrics
            $SurveyId => array(), // all validated survey
            $MixevalId => array() // all validated mixevals
        );
        
        // get all groups once for validation
        $validGroupIds = array_keys($Group->getGroupsByCourseId($courseId));
        
        // validate all the events
        foreach($events as $event) {
            $eventData = array();
            $i = 0;
            
            $isSurvey = false;
            
            // format csv row into proper key/value pairs
            foreach(Event::$importColumns as $description => $column) {
                if($isSurvey && $i>Event::$importSurveyCutoff) {
                    $event[$i] = ""; //insert temporary dummy data for the survey
                } else if(!isset($event[$i])) {
                    return 'Event on row ' . $csvRow . ' does not have an entry for column: ' . $description;
                }
                if($column==='event_template_type_id' && $event[$i]==$SurveyId) {
                    $isSurvey = true;
                }
                $eventData[$column] = $event[$i];
                $i += 1;
            }
            
            if(!empty($event[$i+1])) {
                return 'Event on row ' . $csvRow . ' has too many columns';
            }
            
            // DO NOT RESET $i, it will later determine the index of the groups info
            
            // add course id info
            $eventData['course_id'] = $courseId;
            
            //convert DateTime String to standard format (some programs like excel change date foramts while editing)
            $event_date_fields = array('due_date', 'release_date_begin', 'release_date_end', 'result_release_date_begin', 'result_release_date_end');
            foreach ($event_date_fields as $eventDate) {
                if (is_string($eventData[$eventDate])) {
                    $timeStamp = strtotime($eventData[$eventDate]);
                    if($timeStamp) {
                        $eventData[$eventDate] = date("Y-m-d H:i:s", $timeStamp);
                    }
                }
            }
            // tell the model we are working with the current event (we later use model data validation)
            $this->create($eventData);
            
            // template for error message
            $eventErrorPreamble = 'Event "' . $eventData['title'] . '" (on row ' . $csvRow . '), has ';
            
            // model based validation
            // takes care of dates and checks for non-empty fields
            if (!$this->validates()) {
                // didn't validate logic, reset the errors array to get an error message (so we can access the first element)
                $value = reset($this->validationErrors);
                // extract the first error from the validation errors array
                return $eventErrorPreamble . 'an invalid field: "' . array_search(key($this->validationErrors), Event::$importColumns) . '": ' . $value;
            }
            
            // date validation
            // users may forget to change the dates after exporting
            if (strtotime('NOW') > strtotime($eventData['release_date_end'])) {
                return $eventErrorPreamble . 'a closing date in the past. Please check that you have updated the dates for the new session/term';
            }
            
            // do a switch based on event_template_type_id; catch invalid types
            // in each switch case, check if template exists and the user has permissions to access it
            $templateId = $eventData['template_id'];
            $templateType = intval($eventData['event_template_type_id']);

            if(empty($validatedEventCache[$templateType]) ||
               empty($validatedEventCache[$templateType][$templateId])) {
                // cache miss - need to check the DB if its valid
                switch($templateType) {
                    case $SimpleEvaluationId: // simple
                        $template = $SimpleEvaluation->getEvaluation($templateId);
                        if(empty($template)) {
                            return $eventErrorPreamble . "a non-existent simple evaluation of id: " . $templateId;
                        }
                        if($template['SimpleEvaluation']['creator_id'] != $userId && $template['SimpleEvaluation']['availability']!= "public") {
                            return $eventErrorPreamble . "a non-accessible template: " . $templateId;
                        }
                        $validatedEventCache[$SimpleEvaluationId][$templateId] = true; // save in cache
                        break;
                    case $RubricId: // rubric
                        $template = $Rubric->getEvaluation($templateId);
                        if(empty($template)) {
                            return $eventErrorPreamble . "a non-existent rubric of id: " . $templateId;
                        }
                        if($template['Rubric']['creator_id'] != $userId && $template['Rubric']['availability']!= "public") {
                            return $eventErrorPreamble . "a non-accessible template: " . $templateId;
                        }
                        $validatedEventCache[2][1] = "hello"; // save in cache
                        break;
                    case $SurveyId: // survey
                        $template = $Survey->getSurveyWithQuestionsById($templateId);
                        if(empty($template)) {
                            return $eventErrorPreamble . "a non-existent survey of id: " . $templateId;
                        }
                        if($template['Survey']['creator_id'] != $userId && $template['Survey']['availability']!= "public") {
                            return $eventErrorPreamble . "a non-accessible template: " . $templateId;
                        }

                        $validatedEventCache[$SurveyId][$templateId] = true; // save in cache
                        break;
                    case $MixevalId: // mixed
                        $template = $Mixeval->getEvaluation($templateId);
                        if(empty($template)) {
                            return $eventErrorPreamble . "a non-existent mixed eval of id: " . $templateId;
                        }
                        if($template['Mixeval']['creator_id'] != $userId && $template['Mixeval']['availability']!= "public") {
                            return $eventErrorPreamble . "a non-accessible template: " . $templateId;
                        }
                        $validatedEventCache[$MixevalId][$templateId] = true; // save in cache
                        break;
                    case 0: // not a number
                    default: // some other number
                        return $eventErrorPreamble . "an invalid template type \"" . $eventData['event_template_type_id'] .  "\"";
                }
            }
             
            // validate the groups and then format the data correctly
            $groupsData = array();
            // $i is from the loop that mapped columns to db keys
            // $i now has the array index of the group column 
            
            // if (there is a column for groups)
            if(!empty($event[$i])) {
                if($event[$i]=="*") { // add all the groups
                    $groupsData = $validGroupIds; //add all ids
                } else { // add semi-colon delimited ids
                    $groupIds = array_filter(explode(";",$event[$i])); 
                    if(!empty($groupIds)) {
                        foreach($groupIds as $groupId) {
                            if(!is_numeric($groupId) || array_search($groupId,$validGroupIds)===false) {
                                return $eventErrorPreamble . 'an invalid group of id: "' . $groupId . '"';
                            } else {
                                $groupsData[] = $groupId;
                            }
                        }
                    }
                }
            }
            
            // Surveys cannot have groups - report an error
            if(!empty($groupsData) && $templateType == $SurveyId) {
                return $eventErrorPreamble . "groups, which are not allowed for a survey.";
            }

            // Put in default booleans if not selected
            if($eventData['self_eval']!=="1") { // if not the non-default (ie. is default or invalid)
                $eventData['self_eval'] = 0; // default 
            }
            if($eventData['com_req']!=="1") {
                $eventData['com_req'] = 0;
            }
            if($eventData['auto_release']!=="1") {
                $eventData['auto_release'] = 0;
            }
            if($eventData['enable_details']!=="0") {
                $eventData['enable_details'] = 1;
            }
            
            // add this event to the validated events
            $validatedEvents[] = array( 'Event' => $eventData , 'Group' => array ( 'Group' => $groupsData ));
            
            $csvRow += 1; // we will now parse the new row in the csv ($csvRow used for error reporting)
        }
        
        // now add the events
        if ($this->saveAll($validatedEvents)) {
            // We do not set the (email) scheduler because we don't import that

            // before completing, we need to set the release status for the groupEvent
            // $insertedAutoRelease has the event ids we just added that have ['Event']['auto_release']===true
            $groupEvents = $this->GroupEvent->find('list',
                array('conditions' => array('event_id'=>$this->insertedAutoRelease)));
            App::import('Component','Evaluation');
            $evalComponent = new EvaluationComponent();
            $evalComponent->setGroupEventsReleaseStatus($groupEvents, 'Auto');  
        } else {
            return 'Final save of events failed.';
        }
        
        return count($validatedEvents);
    }


    /**
     * Called after every deletion operation.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterDelete-1055
     */
	function afterDelete() {
        parent::afterDelete();
        CaliperHooks::event_after_delete($this);
	}


    /**
     * Called before every deletion operation.
     *
     * @param boolean $cascade If true records that depend on this record will also be deleted
     * @return boolean True if the operation should continue, false if it should abort
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#beforeDelete-1054
     */
	function beforeDelete($cascade = true) {
        CaliperHooks::event_before_delete($this);
        return parent::beforeDelete($cascade);
	}
}
