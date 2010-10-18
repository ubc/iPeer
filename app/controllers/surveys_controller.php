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
 * @lastmodified $Date: 2006/09/12 14:16:32 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Surveys
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since

 */

class SurveysController extends AppController
{
  var $uses =  array('Course', 'Survey', 'User', 'Question', 'SurveyQuestion', 'Response','Personalize','Event','EvaluationSubmission','UserEnrol','SurveyInput','SurveyGroupMember','SurveyGroupSet','SurveyGroup');
  var $name = 'Surveys';
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;
  var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
  var $components = array('AjaxList','rdAuth','Output','sysContainer', 'globalConstant','userPersonalize', 'framework','SurveyHelper');


	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'Survey.created': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
 		$this->pageTitle = 'Surveys';
		parent::__construct();
	}


    function postProcess($data) {

        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                // is it in use?
                $inUse = $this->Event->checkEvaluationToolInUse('3',$entry['Survey']['id']) ;

                // Put in the custom inUse column
                $data[$key]['!Custom']['inUse'] = $inUse ? "Yes" : "No";

                // Decide whether the course is release or not ->
                // (from the events controller postProcess function)
                $releaseDate = strtotime($entry["Survey"]["release_date_begin"]);
                $endDate = strtotime($entry["Survey"]["release_date_end"]);
                $timeNow = strtotime($entry[0]["now()"]);

                if (!$releaseDate) $releaseDate = 0;
                if (!$endDate) $endDate = 0;

                $isReleased = "";
                if ($timeNow < $releaseDate) {
                    $isReleased = "Not Yet Open";
                } else if ($timeNow > $endDate) {
                    $isReleased = "Already Closed";
                } else {
                    $isReleased = "Open Now";
                }

                // Put in the custom isReleased string
                $data[$key]['!Custom']['isReleased'] = $isReleased;
            }
        }
        // Return the processed data back
        return $data;
    }

    function setUpAjaxList() {
        $myID = $this->rdAuth->id;

       // Get the course data
        $userCourseList = $this->sysContainer->getMyCourseList();
        $coursesList = array();
        foreach ($userCourseList as $id => $course) {
            $coursesList{$id} = $course['course'];
        }

        // Set up Columns
        $columns = array(
            array("Survey.id",          "ID",          "4em",   "number"),
            array("Course.id",          "",             "",     "hidden"),
            array("Course.course",      "Course",      "15em",  "action",
              "View Course"),
            array("Survey.name",        "Name",        "auto",  "action",
                "View Survey"),
            array("!Custom.inUse",      "In Use",      "4em",   "number"),
            array("Survey.due_date",     "Due Date",   "10em",  "date"),
            // The release window columns
            array("now()",   "", "", "hidden"),
            array("Survey.release_date_begin", "", "", "hidden"),
            array("Survey.release_date_end",   "", "", "hidden"),
            array("!Custom.isReleased", "Released ?",   "  4em",   "string"),
            array("Creator.id",   "", "", "hidden"),
            array("Creator.username",  "Created By",    "8em", "action",
              "View Creator"),
            array("Survey.created",     "Creation Date","10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array($myID => "My Evaluations");

        // Join in the course name
        $joinTableCourse =
             array("id"        => "Course.id",
                   "localKey"  => "course_id",
                   "description" => "Course:",
                   "default"   => $this->rdAuth->courseId,
                   "list" => $coursesList,
                   "joinTable" => "courses",
                   "joinModel" => "Course");

        $joinTableCreator =
              array("joinTable"=>"users",
                    "localKey" => "creator_id",
                    "joinModel" => "Creator");

        // Add the join table into the array
        $joinTables = array($joinTableCourse, $joinTableCreator);

        // For instructors: only list their own course events (surveys
        $extraFilters = "";
        if ($this->rdAuth->role != 'A') {
            $extraFilters = " ( ";
            foreach ($coursesList as $id => $course) {
                $extraFilters .= "course_id=$id or ";
            }
            $extraFilters .= "1=0 ) "; // just terminates the or condition chain with "false"
        }

        // Set up actions
        $warning = "Are you sure you want to delete this evaluation permanently?";
        $actions = array(
            array("View Event", "", "", "", "view", "Survey.id"),
            array("Edit Event", "", "", "", "edit", "Survey.id"),
            array("Edit Questions", "", "", "", "questionssummary", "Survey.id"),
            array("Copy Survey", "", "", "", "copy", "Survey.id"),
            array("Delete Survey", $warning, "", "", "delete", "Survey.id"),
            array("View Course", "",    "", "courses", "home", "Course.id"),
            array("View Creator", "",    "", "users", "view", "Creator.id"));

        // No recursion in results (at all!)
        $recursive = -1;

        // Set up the list itself
        $this->AjaxList->setUp($this->Survey, $columns, $actions,
            "Course.course", "Survey.name", $joinTables, $extraFilters, $recursive, "postProcess");
    }


    function index($message='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set the top message
        $this->set('message', $message);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    function ajaxList() {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }


	function view($id) {
        $data = $this->Survey->read();
		$this->set('data', $data);
	}

	// Gets all the questions associated with selected survey and displays them
	function questionssummary($survey_id, $question_id=null, $move=null)
	{
		$this->set('survey_id', $survey_id);

		// Move request for a question
		if( $move != null && $question_id != null){
			//$this->SurveyQuestion->moveQuestion($survey_id, $question_id, $move);
			$this->SurveyHelper->moveQuestion($survey_id, $question_id, $move);
		}

		// Get all required data from each table for every question
		$tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
		$tmp = $this->Question->fillQuestion($tmp);
		$tmp = $this->Response->fillResponse($tmp);
		$result = null;

		// Sort the resultant array by question number
		$count = 1;
		for( $i=0; $i<=$tmp['count']; $i++ ){
			for( $j=0; $j<$tmp['count']; $j++ ){
				if( $i == $tmp[$j]['Question']['number'] ){
					$result[$count]['Question'] = $tmp[$j]['Question'];
					$count++;
				}
			}
		}

		$survey = $this->Survey->findById($survey_id);
        $this->set('data',$survey);
		$this->set('questions', $result);
		$this->render('questionssummary');
	}

	// Used when remove is clicked on questionssummary page
	function removequestion($sq_id, $survey_id, $question_id)
	{
		$this->autoRender = false;

		// move question to bottom of survey list so deletion can be done
		// without affecting the number order
		$this->SurveyQuestion->moveQuestion($survey_id, $question_id, "BOTTOM");

		// remove the question from the survey association as well as all other
		// references to the question in the responses and questions tables
		$this->SurveyQuestion->del($sq_id);
		//$this->Question->editCleanUp($question_id);

		$this->set('message', 'The question was removed successfully.');
		$this->set('survey_id', $survey_id);

		$this->questionssummary($survey_id, $question_id);
		//$this->render('index');
	}


	function add()
	{
		$coursesList = $this->sysContainer->getMyCourseList();
		$this->set('coursesList', $coursesList);

		// We need at least one course to be defined before Survey creation.
        $this->set ('createSurveyPossible', sizeof($coursesList) > 0 );

		$this->set('templates', $this->Survey->findAll($conditions=null, $fields="id, name"));

		if (empty($this->params['data']))
		{
			$this->render();
		}
		else
		{
			$this->params['data']['Survey']['course_id'] = $this->params['form']['course_id'];
			$this->params['data']['Survey']['creator_id'] = $this->rdAuth->id;

			if ($this->Survey->save($this->params['data']))
			{
				// check to see if a template has been selected
				if( !empty($this->params['form']['survey_template'] )){
					$this->SurveyQuestion->copyQuestions($this->params['form']['survey_template'], $this->Survey->id);
				}

				$this->set('survey_id', $this->Survey->id);
				$this->set('data', $this->params['data']);
				$this->questionssummary($this->Survey->id);

    	  $eventArray = array();

    		//$this->Survey->setId($id);
    		$this->params['data'] = $this->Survey->read();
    		$this->params['data']['Survey']['released'] = 1;

    		//add survey to events
    		  //set up Event params
    		$eventArray['Event']['title'] = $this->params['data']['Survey']['name'];
    		$eventArray['Event']['course_id'] = $this->params['data']['Survey']['course_id'];
    		$eventArray['Event']['event_template_type_id'] = 3;
    		$eventArray['Event']['template_id'] = $this->params['data']['Survey']['id'];
    		$eventArray['Event']['self_eval'] = 0;
    		$eventArray['Event']['com_req'] = 0;
    		$eventArray['Event']['due_date'] = $this->params['data']['Survey']['due_date'];
    		$eventArray['Event']['release_date_begin'] = $this->params['data']['Survey']['release_date_begin'];
    		$eventArray['Event']['release_date_end'] = $this->params['data']['Survey']['release_date_end'];
    		$eventArray['Event']['creator_id'] = $this->params['data']['Survey']['creator_id'];
    		$eventArray['Event']['created'] = $this->params['data']['Survey']['created'];

        //Save Data
    		$this->Event->save($eventArray);
    		$this->Survey->save($this->params['data']);
			}
			else
			{
                $this->set('errmsg', $this->Survey->errorMessage);
				$this->set('data', $this->params['data']);
				$this->render('edit');
			}
		}
	}

	function addquestion()
	{
		$this->autorender = false;
		$this->set('templates', $this->Question->findAll( $conditions="master='yes'", $fields="id, prompt"));

		//check to see if user has clicked load question
		if(!empty($this->params['form']['loadq'])){

			// load values from selected question into temp array
			$tmp = $this->Question->findAll( $conditions="id=".$this->params['form']['questions']);

			$this->params['data']['Question']['prompt'] = $tmp[0]['Question']['prompt'];
			$this->params['data']['Question']['type'] = $tmp[0]['Question']['type'];
			$this->params['data']['Question']['count'] = $this->Response->countResponses($this->params['form']['questions']);
			$this->set('count', $this->params['data']['Question']['count'] );
			$this->set('type', $this->params['data']['Question']['type']);
			$this->params['data'] = $this->Response->prepData($this->params['data'], $this->params['form']['questions']);

			//$this->render('addquestion');
			$this->set('survey_id',$this->params['form']['survey_id']);
		}

		elseif (empty($this->params['data']['Question']))
		{
			$this->set('survey_id', $this->params['form']['survey_id']);
			$this->render();
		}
		else
		{
			$this->params = $this->Question->prepData($this->params);

			if ($this->Question->save($this->params['data']))
			{
				// save values to survey_questions table
				$this->SurveyQuestion->linkQuestions($this->params['form']['survey_id'], $this->Question->id);
				// save values to responses table
				$this->Response->linkResponses($this->Question->id, $this->params['data']);

				$this->set('message', 'The question was added successfully.');
				$this->questionssummary($this->params['form']['survey_id'], null, null);
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render('editquestion');
			}
		}
	}

	function edit($id=null)
	{
		$coursesList = $this->sysContainer->getMyCourseList();
		$this->set('coursesList', $coursesList);

		if (empty($this->params['data'])) {
			$this->Survey->setId($id);
			$this->params['data'] = $this->Survey->read();
		    $this->set('course_id',$this->params['data']['Survey']['course_id']);
			$this->render();
		} else {
			$this->params['data']['Survey']['name'] = $this->params['form']['name'];
			$this->params['data']['Survey']['course_id'] = $this->params['form']['course_id'];
            $this->set('course_id',$this->params['form']['course_id']);

			if ( $this->Survey->save($this->params['data']))
			{
			  //alter dates for the event
                $eventArray = $this->Event->find('template_id='.$this->Survey->id.' AND event_template_type_id=3');
                $eventArray['Event']['title'] = $this->params['data']['Survey']['name'];
                $eventArray['Event']['course_id'] = $this->params['data']['Survey']['course_id'];
                $eventArray['Event']['due_date'] = $this->params['data']['Survey']['due_date'];
                $eventArray['Event']['release_date_begin'] = $this->params['data']['Survey']['release_date_begin'];
                $eventArray['Event']['release_date_end'] = $this->params['data']['Survey']['release_date_end'];
                $this->Event->save($eventArray);

                $this->set('course_id', $this->params['data']['Survey']['course_id']);
 				$this->redirect('/surveys/index/The Survey was edited successfully.');
			} else {
                $this->set('errmsg', $this->Survey->errorMessage);
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}

	function copy($id=null)
	{
        $this->render = false;
		$this->Survey->setId($id);
		$coursesList = $this->sysContainer->getMyCourseList();
		$this->set('coursesList', $coursesList);
		$this->set('templates', $this->Survey->findAll($conditions=null, $fields="id, name"));
		$this->params['data'] = $this->Survey->read();
		unset($this->params['data']['Survey']['id']);
		$this->params['data']['Survey']['name'] = "";
            //converting nl2br back so it looks better
		$this->Output->br2nl($this->params['data']);
        $this->render('add');
	}

	function editquestion( $question_id=null, $survey_id=null )
	{
		if(empty($this->params['data'])){
			// get current answer choices and set it to accessible var
			$this->set('responses', $this->Response->findAll($conditions='question_id='.$question_id));

			$this->set('question_id', $question_id);
			$this->set('survey_id', $survey_id);

			$this->Question->setId($question_id);
			$this->params['data'] = $this->Question->read();

			$this->render();
		}
		else
		{
			$this->params = $this->Question->prepData($this->params);

			if ($this->Question->save($this->params['data']))
			{
				// delete the old question and its references from db
				$this->Question->editCleanUp($this->params['form']['question_id']);
				// save new values to survey_questions table
				$this->SurveyQuestion->linkQuestions($this->params['form']['survey_id'], $this->Question->id);
				// save new values to responses table
				$this->Response->linkResponses($this->Question->id, $this->params['data']);

				$this->set('message', 'The question was updated successfully.');
				$this->questionssummary($this->params['form']['survey_id'], null, null);
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render('editquestion');
			}
		}
	}

	function delete($id)
	{
		if ($this->Survey->del($id)) {
		  $groupSets = $this->SurveyGroupSet->findAll('survey_id='.$id);

		  foreach ($groupSets as $groupSet)
		  {
    	  	$groupSetId = $groupSet['SurveyGroupSet']['id'];
        	$time = $groupSet['SurveyGroupSet']['date'];

        	$this->SurveyHelper->deleteGroupSet($groupSetId);

        	//delete teammaker crums
        	if (!empty($time)) {
	          unlink('../uploads/'.$time.'.txt');
	          unlink('../uploads/'.$time.'.xml');
	          unlink('../uploads/'.$time.'.txt.scores');
	        }
		  }

		  //delete associating event
		  $events = $this->Event->findAll('event_template_type_id=3 AND template_id='.$id);
		  if(!empty($events)) {
			  foreach ($events as $event) {
			    $this->Event->del($event['Event']['id']);
			  }
		  }
	    //delete possible submissions
		  $inputs = $this->SurveyInput->findAll('survey_id='.$id);
		  foreach ($inputs as $input) {
		    $this->SurveyInputs->del($input['SurveyInput']['id']);
		  }

			$this->set('message', 'The survey was deleted successfully.');
			$this->index();
			$this->render('index');
		} else {
		    $this->set('message', 'Survey delete failed.');
            $this->index();
			$this->render('index');
		}
	}

  // called to add/remove response field from add/edit question pages
  function adddelquestion($question_id=null)
  {
    if(!empty($question_id))
      $this->set('responses', $this->Response->findAll($conditions='question_id='.$question_id));

    $this->layout = 'ajax';
  }

  function checkDuplicateName()
  {
    $course_id = $this->rdAuth->courseId;
    $this->layout = 'ajax';
    $this->set('course_id', $course_id);
    $this->render('checkDuplicateName');
  }

  // called to change survey status to release
  function releaseSurvey($id=null)
  {//deprecated, this function is not used
    $eventArray = array();

    $this->Survey->setId($id);
    $this->params['data'] = $this->Survey->read();
    $this->params['data']['Survey']['released'] = 1;

    //add survey to eventsx();
    //set up Event params
    $eventArray['Event']['title'] = $this->params['data']['Survey']['name'];
    $eventArray['Event']['course_id'] = $this->params['data']['Survey']['course_id'];
    $eventArray['Event']['event_template_type_id'] = 3;
    $eventArray['Event']['template_id'] = $this->params['data']['Survey']['id'];
    $eventArray['Event']['self_eval'] = 0;
    $eventArray['Event']['com_req'] = 0;
    $eventArray['Event']['due_date'] = $this->params['data']['Survey']['due_date'];
    $eventArray['Event']['release_date_begin'] = $this->params['data']['Survey']['release_date_begin'];
    $eventArray['Event']['release_date_end'] = $this->params['data']['Survey']['release_date_end'];
    $eventArray['Event']['creator_id'] = $this->params['data']['Survey']['creator_id'];
    $eventArray['Event']['created'] = $this->params['data']['Survey']['created'];

    //Save Data
    if ($this->Event->save($eventArray)) {
      //Save Groups for the Event
      //$this->GroupEvent->insertGroups($this->Event->id, $this->params['data']['Event']);

      //$this->redirect('/events/index/The event is added successfully.');
    }

    $this->Survey->save($this->params['data']);


		$this->set('data', $this->Survey->findAll(null, null, 'id'));
		$this->set('message', 'The survey was released.');
		$this->index();
		$this->render('index');
	}
	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
	}
}

?>
