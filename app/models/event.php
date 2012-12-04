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

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Title is required.',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Duplicate title found.'
            )
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
        parent::__construct($id, $table, $ds);
        $this->virtualFields['response_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as sub WHERE sub.event_id = %s.id', $this->alias);
        $this->virtualFields['to_review_count'] = sprintf('SELECT count(*) as count FROM group_events as ge WHERE ge.event_id = %s.id AND marked LIKE "to review"', $this->alias);
        $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM group_events as vge RIGHT JOIN groups_members as vgm ON vge.group_id = vgm.group_id WHERE vge.event_id = %s.id', $this->alias);
        $this->virtualFields['completed_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as ves WHERE ves.submitted = 1 AND ves.event_id = %s.id', $this->alias);
        $this->virtualFields['due_in'] = 'DATEDIFF(due_date, NOW())';
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
        }

        return $results;
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
        //return $this->find('course_id='.$courseId, 'COUNT(*) as total');
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
     * @param int $courseId course id
     *
     * @return array of survey events
     */
    function getActiveSurveyEvents($courseId = null)
    {
        //return $this->find('all', 'course_id='.$courseId.' AND event_template_type_id=3');
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id' => '3', 'Event.record_status !='=>'I')
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
                'conditions' => array('Event.due_date < NOW()', 'Event.id' => $eventID)
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
     * formatEventObj
     *
     * @param mixed $eventId event id
     * @param bool  $groupId group id
     *
     * @access public
     * @return void
     */
    function formatEventObj ($eventId, $groupId=null)
    {
        $this->recursive = 0;
        $conditions['Event.id'] = $eventId;
        if ($groupId != null) {
            $conditions['Group.id'] = $groupId;
        }

        $event = $this->find('first', array('conditions' => $conditions));

        //This part can be removed after correcting array indexing on controller and view files
        if ($groupId != null) {
            $event['group_name'] = 'Group '.$event['Group']['group_num']." - ".$event['Group']['group_name'];
            $event['group_id'] = $event['Group']['id'];
            $event['group_event_id'] = $event['GroupEvent']['id'];
            $event['group_event_marked'] = $event['GroupEvent']['marked'];
        }
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
     * @param mixed $fields fields
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
     * @param mixed $fields fields
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
     * getEventsByUserId get events that associated with a specific user
     *
     * @param mixed $userId
     *
     * @access public
     * @return array array of events with related models, e.g. course, group, submission
     */
    function getEventsByUserId($userId)
    {
        $groups = $this->Group->find('all', array(
            'conditions' => array('Member.id' => $userId),
            'fields' => array('id'),
            'contain' => array('Member')));
        $groupIds = Set::extract('/Group/id', $groups);

        // find evaluation events
        $evaluationEvents = $this->find('all', array(
            'conditions' => array('Group.id' => $groupIds),
            'contain' => array(
                'Course',
                'Group',
                'Penalty' => array(
                    'conditions' => array(
                        'OR' => array(
                            array('days_late' => 'Event.due_in'),
                            array('days_late <' => 0)
                        )
                    ),
                    'order' => array('days_late DESC')
                )
            )
        ));

        // find submission separately, doesn't work in on query
        $submissions = $this->GroupEvent->EvaluationSubmission->find('all', array(
            'conditions' => array('grp_event_id' => Set::extract('/GroupEvent/id', $evaluationEvents), 'submitter_id' => $userId),
            'contain' => false,
        ));

        foreach ($submissions as $submission) {
            foreach ($evaluationEvents as $key => $event) {
                if ($submission['EvaluationSubmission']['grp_event_id'] == $event['GroupEvent']['id']) {
                    $evaluationEvents[$key]['EvaluationSubmission'] = $submission['EvaluationSubmission'];
                }
            }
        }

        // find survey events
        $this->bindModel(array(
            'hasOne' => array(
                'EvaluationSubmission' => array('conditions' => array('EvaluationSubmission.submitter_id' => $userId))
        )));
        $surveyEvents = $this->find('all', array(
            'conditions' => array('event_template_type_id' => '3'),
            'contain' => array(
                'Course',
                'EvaluationSubmission',
            )
        ));

        // clean up the empty EvaluationSubmissions, Cake put them in even if they are empty
        foreach ($surveyEvents as $key => $events) {
            if (!isset($events['EvaluationSubmission']['id']) || empty($events['EvaluationSubmission']['id'])) {
                unset($surveyEvents[$key]['EvaluationSubmission']);
            }
        }

        return array_merge($evaluationEvents, $surveyEvents);
    }
}
