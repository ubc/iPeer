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
  var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework','SurveyHelper');


	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'Survey.created': $this->Sanitize->paranoid($_GET['sort']);
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->mine_only = (!empty($_REQUEST['show_my_tool']) && 'on' == $_REQUEST['show_my_tool']) ? true : false;
 		$this->pageTitle = 'Surveys';
		parent::__construct();
	}

	function index()
	{
        $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
        $this->userPersonalize->setPersonalizeList($personalizeData);
        if ($personalizeData && $this->userPersonalize->inPersonalizeList('Survey.ListMenu.Limit.Show')) {
            $this->show = $this->userPersonalize->getPersonalizeValue('Survey.ListMenu.Limit.Show');
            $this->set('userPersonalize', $this->userPersonalize);
        } else {
            $this->show = '10';
            $this->update($attributeCode = 'Survey.ListMenu.Limit.Show',$attributeValue = $this->show);
        }

        $conditions = array('Survey.creator_id' =>  $this->rdAuth->id);
        if ($this->rdAuth->courseId > 1)  { // If the course is set, use that value as well.
            $conditions['Survey.course_id'] = $this->rdAuth->courseId;
        }

		$data = $this->Survey->findAll($conditions, '', $this->order, $this->show, $this->page,null,null);//array('JOIN courses AS Course ON Survey.course_id = Course.id'));

		$paging['style'] = 'ajax';
		$paging['link'] = '/surveys/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

		$paging['count'] = !empty($data) ? count($data):0;
		$paging['show'] = array('10','25','50','all');
		$paging['page'] = $this->page;
		$paging['limit'] = $this->show;
		$paging['direction'] = $this->direction;

		$this->set('paging',$paging);
		$this->set('data',$data);
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

		$survey = $this->Survey->find('id = '.$survey_id);
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
		$courseList = $this->sysContainer->getMyCourseList();
		$this->set('courseList', $courseList);
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
		$courseList = $this->sysContainer->getMyCourseList();
		$this->set('courseList', $courseList);

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
		$courseList = $this->sysContainer->getMyCourseList();
		$this->set('courseList', $courseList);
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

  function search()
  {
    $this->layout = 'ajax';
    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      if ($personalizeData) {
        $this->userPersonalize->setPersonalizeList($personalizeData);
        $this->show = $this->userPersonalize->getPersonalizeValue('Survey.ListMenu.Limit.Show');
        $this->set('userPersonalize', $this->userPersonalize);
      }
    }

    $conditions = '';
    if ($this->mine_only){
      $conditions = array('Survey.creator_id' =>  $this->rdAuth->id);
    }

    if ($this->rdAuth->courseId > 1)  { // If the course is set, use that value as well.
        $conditions['Survey.course_id'] = $this->rdAuth->courseId;
    }


    if (!empty($this->params['form']['livesearch2']) && !empty($this->params['form']['select']))
    {
      $pagination->loadingId = 'loading';
      //parse the parameters
      $searchField=$this->params['form']['select'];
      $searchValue=$this->params['form']['livesearch2'];
      (empty($conditions))? $conditions = '' : $conditions .= ' AND ';
      if('Creator.name' == $searchField)
      {
        $conditions = "(Creator.first_name LIKE '%".mysql_real_escape_string($searchValue)."%'
          OR Creator.last_name LIKE '%".mysql_real_escape_string($searchValue)."%')";
      }else{
        $conditions = $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }
    }
    $this->update($attributeCode = 'Survey.ListMenu.Limit.Show',$attributeValue = $this->show);
    $this->set('conditions',$conditions);
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
