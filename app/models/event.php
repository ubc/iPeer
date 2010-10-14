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
class Event extends AppModel
{
  var $name = 'Event';
  /* Accordion's panelHeight - Height various based on the no. of questions and evaluation types*/
  var $SIMPLE_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');
  var $RUBRIC_EVAL_HEIGHT = array('2'=>'200', '3'=>'250');

  /* tables related: events, group_events,
     * evaluation_submissions,
     * evaluation_rubrics, evaluation_rubrics_details,
     * evaluation_simples,
     * evaluation_mixevals, evaluation_mixeval_details
     */

  var $hasOne = array(

  );


  var $validate = array(
      'title' => VALID_NOT_EMPTY,
      'due_date' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/',
      'release_date_begin' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/',
      'release_date_end' => '/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])( ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)*$/'
  );
 /* var $hasMany = array(
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
                      'EvaluationRubric' =>
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
                        )
  );*/


	//Overwriting Function - will be called before save operation
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
    if ($result = $this->find($field . ' = "' . $value.'"', $field)){
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
        return $this->findAll('course_id ='.$courseId);
    }

    function getCourseEvalEvent($courseId=null) {
        return $this->findAll('course_id='.$courseId.' AND event_template_type_id!=3');
    }

    function getCourseEventCount($courseId=null) {
        return $this->find('course_id='.$courseId, 'COUNT(DISTINCT id) as total');
    }

    function getSurveyEventIdByCourseIdDescription($courseId=null,$title=null) {
        return $this->find('course_id='.$courseId.' AND title=\''.$title.'\' AND event_template_type_id=3','id');
    }

    function getActiveSurveyEvents($courseId=null) {
        return $this->findAll('course_id='.$courseId.' AND event_template_type_id=3');
    }

    function checkEvaluationToolInUse($evalTool=null, $templateId=null)
    {
        $event = $this->find('event_template_type_id = '.$evalTool.' AND template_id = '.$templateId);
        return !empty($event);
    }

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

    $ems = $evaluation_mixeval->findAll('event_id = '.$this->id);
    if(!empty($ems))
    {
    	foreach($ems as $em) {
    	$emds = $evaluation_mixeval_detail->findAll('evaluation_mixeval_id = '.$em->id);

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
    $events = $this->findAll($this->name.'.event_template_type_id = 3 AND '.$this->name.'.template_id = '.$survey_id);
    if(empty($events)) return true;

    foreach($events as $e){
      $this->cascadeRemove($e[$this->name]['id']);
    }
    return true;
  }

    function checkIfNowLate($eventID) {
        if (is_numeric($eventID)) {
            $conditions = "`Event`.`due_date` < now() AND `id`=$eventID";
            $count = $this->findCount($conditions);
            return ($count>0);
        } else {
            return false;
        }

    }

}

?>
