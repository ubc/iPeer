<?php
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
        $dueIn = "";
        if(date('I') == 1){
            $dueIn = date('Y-m-d H:i:s', time());
        }
        else{
            $dueIn = date('Y-m-d H:i:s', time() + 3600);
        }
        $this->virtualFields['due_in'] = sprintf('TIMESTAMPDIFF(SECOND,"%s",due_date)', $dueIn);
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
     * @return void
     */
    function afterFind(array $results, $primary)
    {
        $currentDate = strtotime('NOW');
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
        }

        return $results;
    }

    /**
     * beforeValidate
     *
     * @access public
     * @return void
     */
    function beforeValidate()
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
    function beforeSave(array $options) {
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
        // restore the validate if it is been changed
        if (null != $this->_backupValidate) {
            $this->validate = $this->_backupValidate;
            $this->_backupValidate = null;
        }
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
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId),
            'fields' => $fields));
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
        return $this->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'fields' => $fields));
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
            'order' => array('due_in ASC'),
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
            'order' => array('due_in ASC'),
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
     * @param mixed $userId user id
     * @param mixed $fields the fields to retreive
     *
     * @access public
     * @return void
     */
    public function getPendingEventsByUserId($userId, $fields = null)
    {
        $pendingEvents = array();
        $events = $this->getEventsByUserId($userId, $fields);
        $events = array_merge($events['Evaluations'], $events['Surveys']);
        foreach ($events as $event) {
            if ($event['Event']['is_released'] && empty($event['EvaluationSubmission'])) {
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
}
