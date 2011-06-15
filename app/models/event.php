<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/31 21:03:09 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Event
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
App::import('Model', 'EvaluationMixeval');
App::import('Model', 'EvaluationMixevalDetail');

class Event extends AppModel
{
  var $name = 'Event';
  /* Accordion's panelHeight - Height various based on the no. of questions and evaluation types*/
  var $SIMPLE_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');
  var $RUBRIC_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');

  var $validate = array(
      'title' => array('rule' => 'notEmpty',
                       'message' => 'Title is required.',
                       'allowEmpty' => false),
      'due_date' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/',
      'release_date_begin' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/',
      'release_date_end' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/'
  );

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

  /* tables related: events, group_events,
     * evaluation_submissions,
     * evaluation_rubrics, evaluation_rubrics_details,
     * evaluation_simples,
     * evaluation_mixevals, evaluation_mixeval_details
     */

  var $belongsTo = array('EventTemplateType');

  var $hasAndBelongsToMany = array('Group' =>
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

  var $hasMany = array(
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
 /*                     'EvaluationRubric' =>
                        array(
                          'className' => 'EvaluationRubric',
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
                      'EvaluationMixeval' =>
                        array(
                          'className' => 'EvaluationMixeval',
                          'conditions' => '',
                          'order' => '',
                          'dependent' => true,
                          'foreignKey' => 'event_id'
                        )*/
  );

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['response_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as sub WHERE sub.event_id = %s.id', $this->alias);
    $this->virtualFields['to_review_count'] = sprintf('SELECT count(*) as count FROM group_events as ge WHERE ge.event_id = %s.id AND marked LIKE "to review"', $this->alias);
    $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM group_events as vge RIGHT JOIN groups_members as vgm ON vge.group_id = vgm.group_id WHERE vge.event_id = %s.id', $this->alias);
    $this->virtualFields['completed_count'] = sprintf('SELECT count(*) as count FROM evaluation_submissions as ves WHERE ves.submitted = 1 AND ves.event_id = %s.id', $this->alias);
  }
  
	// Overwriting Function - will be called before save operation
	function beforeSave(){
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['title'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

      // Remove any signle quotes in the name, so that custom SQL queries are not confused.
      $this->data[$this->name]['title'] =
        str_replace("'", "", $this->data[$this->name]['title']);

	  $allowSave = true;
	  if (empty($this->data[$this->name]['id'])) {
      //check the duplicate title
      $allowSave = $this->__checkDuplicateTitle();
    }
    return $allowSave;
	}
  //Validation check on duplication of title
	function __checkDuplicateTitle($title = null) {
	  $duplicate = false;
    $field = 'title';
    $value = null === $title ? $this->data[$this->name]['title'] : $title;
    if ($result =  $this->find('all' , array('conditions'=>array('title'=>$value) , 'fields'=>'title' ))){
      $duplicate = true;
     }

    if ($duplicate == true) {
      $this->errorMessage='Duplicate Title found. Please change the title of this event.';
      return false;
    }
    else {
      return true;
    }
	}

	// parses the grous from the hidden field assigned
	function prepData($data=null){
		$tmp = $data['form']['assigned'];
		$num = null;
		$member_count=0;

		// parse group member id
		for( $i=0; $i<strlen($tmp); $i++ ){
			if( $tmp{$i} != ":" ){
				$num = $num.$tmp{$i};
			}
			else{
				$member_count++;
				$data['data']['Event']['group'.$member_count] = $num;
				$num = "";
			}

			if( $i == (strlen($tmp)-1) ){
				$member_count++;
				$data['data']['Event']['group'.$member_count] = $num;
			}
		}

		$data['data']['Event']['group_count'] = $member_count;

		//print_r($data);
		return $data;
	}

    function getCourseEvent($courseId=null)
    {
        //return $this->find('all','course_id ='.$courseId);
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId)
        ));

    }

    function getCourseEvalEvent($courseId=null) {
        //return $this->find('all','course_id='.$courseId.' AND event_template_type_id!=3');
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id !=' => '3')
        ));
    }

    function getCourseEventCount($courseId=null) {
        //return $this->find('course_id='.$courseId, 'COUNT(*) as total');
        return $this->find('count', array(
            'conditions' => array('course_id' => $courseId)
        ));
    }

    function getCourseByEventId($eventId) {
    	$tmp = $this->find('all', array('conditions'=>array('Event.id' => $eventId), 'fields'=>array('course_id')));
    	return $tmp[0]['Event']['course_id'];
    }
    
    function getSurveyEventIdByCourseIdDescription($courseId=null,$title=null) {
        //return $this->find('course_id='.$courseId.' AND title=\''.$title.'\' AND event_template_type_id=3','id');
        return $this->find('first', array(
            'conditions' => array('course_id' => $courseId, 'title' => $title, 'event_template_type_id' => '3'),
            'fields' => array('Event.id')
        ));
    }

    function getActiveSurveyEvents($courseId=null) {
        //return $this->find('all','course_id='.$courseId.' AND event_template_type_id=3');
        return $this->find('all', array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id' => '3')
        ));
    }

  // deprecated function, use event_count attribute instead
    /*function checkEvaluationToolInUse($evalTool=null, $templateId=null)
    {
        $event = $this->find('event_template_type_id = '.$evalTool.' AND template_id = '.$templateId);
        return !empty($event);
    }*/

  //TODO: unfinished function
  function cascadeRemove($id)
  {
  	/* tables related: events, group_events,
     * evaluation_submissions,
     * evaluation_rubrics, evaluation_rubrics_details,
     * evaluation_simples,
     * evaluation_mixevals, evaluation_mixeval_details
     */

    if($id) {
      $this->id = $id;
    }

    $event = $this->read(null, $id);

    // delete evaluation_mixevals and evaluation_mixeval_details
    $evaluation_mixeval = new EvaluationMixeval();
    $evaluation_mixeval_detail = new EvaluationMixevalDetail();

    //$ems = $evaluation_mixeval->find('all','event_id = '.$this->id);
    $ems = $evaluation_mixeval->find('all',array(
        'conditions' => array('event_id' => $this->id)
    ));
    if(!empty($ems))
    {
    	foreach($ems as $em) {
    	//$emds = $evaluation_mixeval_detail->find('all','evaluation_mixeval_id = '.$em->id);
        $emds = $evaluation_mixeval_detail->find('all',array(
            'conditions' => array('evaluation_mixeval_id' => $em->id)
        ));

    	if(!empty($emds))
    	{
	    	foreach($emds as $emd)
	      		$emd->delete();
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
  }

  /**
   * removeEventsBySurveyId remove all events associated with a survey by survey ID
   *
   * @param mixed $survey_id
   * @access public
   * @return void
   */
  function removeEventsBySurveyId($survey_id)
  {
    //$events = $this->find('all',$this->name.'.event_template_type_id = 3 AND '.$this->name.'.template_id = '.$survey_id);
    $events = $this->find('all', array(
            'conditions' => array($this->name.'.event_template_type_id' => '3' , $this->name.'.template_id' => $survey_id)
            ));
    if(empty($events)) return true;

    foreach($events as $e){
      $this->cascadeRemove($e[$this->name]['id']);
    }
    return true;
  }
 
    function checkIfNowLate($eventID) {
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
     * @param mixed $event event array
     * @param mixed $assigned_group_ids already assigned group ids
     * @access public
     * @return array unassigned groups
     */
    function getUnassignedGroups($event, $assigned_group_ids = null) {
      $group = Classregistry::init('Group');

      if(!is_array($event) || !isset($event['Event']['id'])) return array();

      if(!isset($event['Event']['course_id'])) {
        $event = $this->find('first', array('conditions' => array('Event.id' => $event['Event']['id']),
                                            'contain' => array()));
      }

      if(null == $assigned_group_ids && !isset($event['Group'])) {
        $assigned_group_ids = array_keys($group->find('list', array('conditions' => array('Event.id' => $event['Event']['id']))));
      } elseif(isset($event['Group'])){
        $assigned_group_ids = Set::extract('/Group/id', $event);
      }

      return $group->find('list', array('conditions' => array('course_id' => $event['Event']['course_id'],  
                                                             'NOT' => array('Group.id' => $assigned_group_ids)),
      									'fields'=> array('Group.group_name')));
    }

    function getEventById($id){
//    	$sql = "SELECT *
//    			FROM events
//    			WHERE id=$id";
//    	return $this->query($sql);
      return $this->find('first', array(
          'conditions' => array('Event.id' => $id)
      ));
    }

    function getEventTemplateTypeId($id){
        $this->recursive = 0;
        $event = $this->find('first', array(
            'conditions' => array('Event.id' => $id),
            'fields' => array('Event.event_template_type_id')
        ));

        return $event['Event']['event_template_type_id'];
    }

    function formatEventObj ($eventId, $groupId=null)
    {
//      //Get the target event
//      $this->Event = new Event;
//      $this->Event->id = $eventId;
//      $event = $this->Event->read();
//
//      //Get the group name
//      if ($groupId != null) {
//        $this->Group = new Group;
//        $this->Group->id = $groupId;
//
//        $group = $this->Group->read();
//        $event['group_name'] = 'Group '.$group['Group']['group_num'].' - '.$group['Group']['group_name'];
//        $event['group_id'] = $group['Group']['id'];
//
//        $this->GroupEvent = new GroupEvent;
//        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
//        $event['group_event_id'] = $groupEvent['GroupEvent']['id'];
//        $event['group_event_marked'] = $groupEvent['GroupEvent']['marked'];
//      }
      $this->recursive = 0;
      $conditions['Event.id'] = $eventId;
      if($groupId != null)
        $conditions['Group.id'] = $groupId;
      
      $event = $this->find('first', array('conditions' => $conditions));

      //This part can be removed after correcting array indexing on controller and view files
      if($groupId != null){
        $event['group_name'] = 'Group '.$event['Group']['group_num']." - ".$event['Group']['group_name'];
        $event['group_id'] = $event['Group']['id'];
        $event['group_event_id'] = $event['GroupEvent']['id'];
        $event['group_event_marked'] = $event['GroupEvent']['marked'];
      }
      return $event;
    }

    function getEventTitleById($id){
      $this->recursive = -1;
      $event = $this->find('first', array(
          'conditions' => array('Event.id' => $id),
          'fields' => array('Event.title')
      ));
      return $event['Event']['title'];
    }
}

?>
