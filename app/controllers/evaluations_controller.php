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
 * @lastmodified $Date: 2006/11/14 16:10:40 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Events
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses ('file','folder');
class EvaluationsController extends AppController
{
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
  var $Sanitize;
  var $uses = array('GroupEvent', 'EvaluationRubric', 'EvaluationRubricDetail', 
                    'EvaluationSubmission', 'Event', 'EvaluationSimple',
                    'SimpleEvaluation', 'Rubric', 'Group', 'User',
                    'GroupsMembers','RubricsLom','RubricsCriteria',
                    'RubricsCriteriaComment', 'Personalize','SurveyQuestion',
                    'Question','Response','Survey','SurveyInput','Course','MixevalsQuestion',
                    'EvaluationMixeval','EvaluationMixevalDetail');
  var $components = array( 'Auth','AjaxList', 'rdAuth','Output','sysContainer',
                          'globalConstant', 'userPersonalize','framework',
                          'EvaluationResult', 'EvaluationHelper', 'EvaluationRubricHelper', 
                          'EvaluationSimpleHelper', 'RubricHelper','EvaluationSurveyHelper',
                          'MixevalHelper', 'EvaluationMixevalHelper','ExportHelper');

  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->set('title_for_layout', 'Evaluations');
    parent::__construct();
  }

// =-=-=-=-=-== New list routines =-=-=-=-=-===-=-


  function postProcess($data) {
    // Creates the custom in use column
    if ($data) {
      foreach ($data as $key => $entry) {
        $custom = array();

        $groupID = $entry['Group']['id'];
        $groupEventID = $entry['GroupEvent']['id'];
        $eventID = $entry['Event']['id'];
        $completedEvaluations =
            $this->EvaluationSubmission->find('count',array(
                'conditions' => array('grp_event_id'=>$groupEventID)));
        $totalMembers =
            $this->GroupsMembers->find('count',array(
                'conditions' => array('group_id'=>$groupID)));

        $custom['completion'] = "<center><img border=0 src='" . $this->webroot . "img/icons/" .
            // Display Check or X for completion
            (($completedEvaluations == $totalMembers) ? "green_check.gif" : "red_x.gif")
            . "'>&nbsp;&nbsp;&nbsp;<b>$completedEvaluations</b> / <b>$totalMembers </b></center>";

        $custom['results'] = 'Results';

        // Include missing submissions into the lates
        $lates = $this->GroupEvent->getLateGroupMembers($groupEventID) +
            ($totalMembers - $completedEvaluations);

        // Is it time for this event to be late yet?
        $eventIsNowLate = $this->Event->checkIfNowLate($eventID);

        if ($eventIsNowLate) {
            $custom['lates'] = ($lates > 0) ? " <b>$lates</b> Late" : "No Lates";
        } else {
            $custom['lates'] = "Not Yet";
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

  function setUpAjaxList ($eventId) {
    // The columns to show
    $columns = array(
      //    Model   columns       (Display Title) (Type Description)
      array("GroupEvent.id",     "",           "",    "hidden"),
      array("Group.id",          "",           "",    "hidden"),
      array("Group.group_num",   "Group #",    "7em", "action", "View Group"),
      array("Group.group_name",  "Group Name", "auto","action", "View Results"),
      array("!Custom.completion","Completed",  "6em", "string"),
      array("!Custom.results",    "View",      "4em", "action", "View Results"),
      array("!Custom.lates",     "Late?",      "7em", "action", "View Submission"),

      // Release and mark status
      array("GroupEvent.marked", "Status",      "9em",  "map",
          array("not reviewed" => "Not Reviewed", "to review" => "To Review",
                "reviewed" => "Reviewed")),
      array("GroupEvent.grade_release_status","Grade", "7em",   "map",
          array("None" => "Not Released", "Some" => "Some Released", "All" => "Released")),
      array("GroupEvent.comment_release_status", "Comment", "7em",   "map",
          array("None" => "Not Released", "Some" => "Some Released", "All" => "Released")),

      // Extra info about course and Event
      array("Event.id", "", "","hidden"),
      array("Event.title",       "Event",        "10em",    "action", "View Event"),
    );

    $joinTables = array(
      array( "joinTable" => "groups",
             "joinModel" => "Group",
             "localKey" => "group_id"),
      array( "joinTable" => "events",
             "joinModel" => "Event",
             "localKey" => "event_id"),
    );

    $extraFilters ="`Group`.`id` is not null and `Event`.`id` is not null ";

    if (!empty($eventId)) {
      $extraFilters .="and `Event`.`id`=$eventId ";
    }

    $actions = array(
      //   parameters to cakePHP controller:,
      //   display name, (warning shown), fixed parameters or Column ids
      array("View Results",    "", "", "", "!viewEvaluationResults", "Event.id", "Group.id"),
      array("View Submission", "", "", "", "!viewGroupSubmissionDetails", "Event.id", "Group.id"),
      array("View Group",      "", "", "groups", "!view", "Group.id"),
      array("View Event",      "", "", "events", "!view", "Event.id"),
      array("Edit Event",      "", "", "events", "edit", "Event.id"),
    );

    $recursive = (-1);

    $this->AjaxList->setUp($this->GroupEvent, $columns, $actions,
      "Group.group_num", "Group.group_name",
      $joinTables, $extraFilters, $recursive, "postProcess");
  }


  function ajaxList() {
    // Make sure the present user is not a student
    $this->rdAuth->noStudentsAllowed();
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
  function view($eventId='') {
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();

    // Record the event id into the session
    if (!empty($eventId) && is_numeric($eventId)) {
      $this->Session->write("evaluationsControllerEventIdSession", $eventId);
      $data = $this->Event->find('all', array(
          'conditions' => array('Event.id' => $eventId)));
      $this->set("data", $data['0']);
      // Set the course ID to the evaluation's course id
      // This required, as export functions assume the course id is set
      // During the view, and will break if it's not.
      //$this->rdAuth->setCourseId($data['Event']['course_id']);
    } else {
      // Use last event ID if none was passed with a parameter
      $eventId = $this->Session->read("evaluationsControllerEventIdSession");
    }

    // Set up the basic static ajax list variables
    $this->setUpAjaxList($eventId);

    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  // =-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-

  /**
   * Index
   *
   * @param <type> $message Message
   */
  function index ($message="")
  {
    $currentUser = $this->User->getCurrentLoggedInUser();
    $this->set('currentUser', $currentUser);
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();

    $courseId = $this->rdAuth->courseId;
    $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > List Evaluation Results');

    $personalizeData = $this->Personalize->find('all', 'user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
    if ($personalizeData && $this->userPersonalize->inPersonalizeList('Eval.ListMenu.Limit.Show')
            && $this->userPersonalize->getPersonalizeValue('Eval.ListMenu.Limit.Show') != 'null') {
      $this->show = $this->userPersonalize->getPersonalizeValue('Eval.ListMenu.Limit.Show');
      $this->set('userPersonalize', $this->userPersonalize);
    } else {
      $this->show = '10';
      $this->update($attributeCode = 'Eval.ListMenu.Limit.Show',$attributeValue = $this->show);
    }

    $conditions = 'course_id = '.$courseId.' AND event_template_type_id <> 3';
    $data = $this->Event->find('all', $conditions, '*, (NOW() >= release_date_begin AND NOW() <= release_date_end) AS is_released',$this->order, $this->show, $this->page);

    $paging['style'] = 'ajax';
    $paging['link'] = '/evaluations/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

    $paging['count'] = count($data);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $this->page;
    $paging['limit'] = $this->show;
    $paging['direction'] = $this->direction;

    $this->set('paging',$paging);
    $this->set('data',$data);
    $this->set('courseId', $courseId);
  }

  function search()
  {
      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();

      $this->layout = 'ajax';
      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
          $personalizeData = $this->Personalize->find('all', 'user_id = '.$this->rdAuth->id);
          if ($personalizeData) {
              $this->userPersonalize->setPersonalizeList($personalizeData);
              $this->show = $this->userPersonalize->getPersonalizeValue('Eval.ListMenu.Limit.Show');
              $this->set('userPersonalize', $this->userPersonalize);
          }
          $this->show = '10';
      }

      $courseId = $this->rdAuth->courseId;
      $conditions = 'course_id = '.$courseId;

      if (!empty($this->params['form']['livesearch2']) && !empty($this->params['form']['select'])) {
          $pagination->loadingId = 'loading';
          //parse the parameters
          $searchField=$this->params['form']['select'];
          $searchValue=$this->params['form']['livesearch2'];
          $conditions = ' AND '.$searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }
      $this->update($attributeCode = 'Eval.ListMenu.Limit.Show',$attributeValue = $this->show);
      $this->set('conditions',$conditions);
  }

  function update($attributeCode='',$attributeValue='') {
      if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
  }


  function test($groupEventId, $userId) {
      $subScore = $this->EvaluationMixeval->getResultsDetailByEvaluatee($groupEventId, $userId);
      //var_dump($subScore[0]);
      exit;
  }


  function export() {

      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();
      $courseId = $this->rdAuth->courseId;
      $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Export Evaluation Results');
      //do stuff
      if (isset($this->params['form']) && !empty($this->params['form'])) {
          $this->autoRender = false;
          $fileContent = $this->ExportHelper->createCSV($this->params);
          $fileName = isset($this->params['form']['file_name']) && empty($this->params['form']['file_name']) ? $this->params['form']['file_name']:date('m.d.y');

          header('Content-Type: application/csv');
          header('Content-Disposition: attachment; filename=' . $fileName . '.csv');
          echo $fileContent;
      } else {
          $this->set('file_name',date('m.d.y'));
      }
  }

  function makeSimpleEvaluation ($param = null) {
      $this->autoRender = false;
      $tok = strtok($param, ';');
      $eventId = $tok;
      $group_Events = $this->GroupEvent->getGroupEventByEventId($eventId);
      $groupId;
      $userId=$this->Auth->user('id');
      foreach($group_Events as $events){
               if($this->GroupsMembers->checkMembershipInGroup($events['group_events']['group_id'],$userId));
                      $groupId=$events['group_events']['group_id'];
      }
      if (empty($this->params['data'])) {
          //Get the target event
          $eventId = $this->Sanitize->paranoid($eventId);
          $event = $this->EvaluationHelper->formatEventObj($eventId, $groupId);
          $this->set('event', $event);

          //Setup the courseId to session
          //$this->rdAuth->setCourseId($event['Event']['course_id']);
          $this->set('courseId', $event['Event']['course_id']);
          $courseId = $event['Event']['course_id'];
          $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId, 'S').' > Evaluate Peers');

          //Set userId, first_name, last_name
          $this->set('userId', $userId);
          $this->set('fullName', $this->Auth->user('first_name').' '.$this->Auth->user('last_name'));


          //Get Members for this evaluation
          $groupMembers = $this->GroupsMembers->getEventGroupMembers($groupId, $event['Event']['self_eval']	,
                                                      $userId);
          $this->set('groupMembers', $groupMembers);

          // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
          $numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->find('count', array('conditions'=>array('group_id'=>$event['group_id']))) :
                                          $this->GroupsMembers->find('count',array('conditions'=>array('group_id'=>$event['group_id']))) - 1;                                          
          $simpleEvaluation = $this->SimpleEvaluation->find('id='.$event['Event']['template_id']);
          $remaining = $simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers;
          //          if($in['points']) $out['points']=$in['points']; //saves previous points
          $points_to_ratio = $numMembers==0 ? 0 : 1 / ($simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers);
          //          if($in['comments']) $out['comments']=$in['comments'];

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
          $seval = $this->SimpleEvaluation->read();

          //Get the target group event
          $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);

          //Get the target event submission
          $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEvent['GroupEvent']['id'],
                                                                                         $evaluator);                                                                                      
          $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
          /*if (!$this->validSimpleEvalComplete($this->params)) {
              $this->redirect('/evaluations/makeSimpleEvaluation/'.$this->params['form']['event_id']);
          }*/
          if ($this->EvaluationSimpleHelper->saveSimpleEvaluation($this->params, $groupEvent, $evaluationSubmission)) {
              $this->redirect('/home/index/Your Evaluation was submitted successfully.');
          } else {
              //Found error
              //Validate the error why the Event->save() method returned false
              $this->validateErrors($this->Event);
              $this->set('errmsg', 'Save Evaluation failure.');
              $this->redirect('/evaluations/makeSimpleEvaluation');
          }//end if
      }
  }


function validSimpleEvalComplete ($params=null)
{
  $status = false;
  
  return $status;
}

function makeSurveyEvaluation ($param = null) {

              //var_dump($this->params);

      $this->autoRender = false;
      //print_r($this->params);
      $tok = strtok($param, ';');
      $eventId = $tok;
      $this->set('courseId', $eventId);
      $thisUser = $this->Auth->user();
      $userId = $thisUser['User']['id'];
      $this->set('id',$userId);
      if (empty($this->params['data'])) {
          //Get the target event
          $eventId = $this->Sanitize->paranoid($eventId);
          $this->Event->id = $eventId;
          $event = $this->Event->read();

          //Setup the courseId to session
          $this->rdAuth->setCourseId($event['Event']['course_id']);
          $courseId = $event['Event']['course_id'];
              $survey_id = $event['Event']['template_id'];

          $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId, 'S').' > Survey');
          //$survey_id = $this->Survey->getSurveyIdByCourseIdTitle($courseId, $courseName);
          $this->set('survey_id', $survey_id);

          // Get all required data from each table for every question
          $tmp = $this->surveyQuestion->getQuestionsID($survey_id);
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

          $this->set('questions', $result);
          $this->set('event', $event);
          $this->render('survey_eval_form');

      } else {
          $courseId = $this->params['form']['course_id'];
                      //var_dump($this->params);
          if (!$this->validSurveyEvalComplete($this->params))  {
              $this->set('errmsg', 'validSurveyEvalCompleten failure.');
              //$this->redirect('/evaluations/makeSurveyEvaluation/'.$eventId);
          }
          //var_dump($this->params);
          if ($this->EvaluationSurveyHelper->saveSurveyEvaluation($this->params)) {
              $this->redirect('/home/index/Your survey was submitted successfully.');
          } else {
              echo "<h1>Hello!</h1>";
              //Validate the error why the Event->save() method returned false
              // $this->validateErrors($this->Event);
              //this->redirect('/evaluations/makeSurveyEvaluation/'.$eventId);
          }//end if
      }
  }

  function validSurveyEvalComplete($param = null) {
      return true;
  }

  function makeRubricEvaluation ($param = '')  {
      $this->autoRender = false;
      if (empty($this->params['data'])) {
          $tok = strtok($param, ';');
          $eventId = $tok;
          $groupId = strtok(';');
          //$msg = strtok(';');
          $event = $this->EvaluationHelper->formatEventObj($eventId, $groupId);
          $rubricId = $event['Event']['template_id'];
          var_dump($rubricId);
          $data = $this->Rubric->getRubricById($rubricId);
          $this->set('data', $data[0]);
          $this->set('event', $event);
          //Setup the courseId to session
          $courseId = $event['Event']['course_id'];
          $this->set('courseId',$courseId);
          $this->rdAuth->setCourseId($courseId);
          //Setup the evaluator_id
          $evaluatorId = $this->Auth->user('id');
              $this->set('evaluatorId', $evaluatorId);
              //Setup the fullName of the evaluator
                      $firstName=$this->Auth->user('first_name');
                      $lastName =$this->Auth->user('last_name');
                      $this->set('firstName', $firstName);
                      $this->set('lastName', $lastName);
              //Setup the viewData
              $rubricId = $event['Event']['template_id'];
                      $rubric = $this->Rubric->getRubricById($rubricId);
                      $rubricEvalViewData = $this->RubricHelper->compileViewData($rubric);
                      $this->set('viewData',$rubricEvalViewData);

          $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId, 'S').' > Evaluate Peers');

          $rubricDetail = $this->EvaluationRubricHelper->loadRubricEvaluationDetail($event, $groupId);
          $this->set('rubric', $rubricDetail['rubric']);
          $this->set('groupMembers', $rubricDetail['groupMembers']);
          $this->set('evaluateeCount', $rubricDetail['evaluateeCount']);

          $this->render('rubric_eval_form');
      }  else {
          $eventId = $this->params['form']['event_id'];
          $groupId = $this->params['form']['group_id'];
          //$groupEventId = $this->params['form']['group_event_id'];
          $groupEventId = $this->GroupEvent->getGroupEventByEventIdAndGroupId($eventId,$groupId);
          $courseId = $this->params['form']['course_id'];
          $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
          if (!$this->validRubricEvalComplete($this->params['form'])) {
              $this->redirect('/evaluations/makeRubricEvaluation/'.$eventId.';'.$groupId);
          }
          if ($this->EvaluationRubricHelper->saveRubricEvaluation($this->params)) {
              $this->redirect('/evaluations/makeRubricEvaluation/'.$eventId.';'.$groupId);
          }
          //Found error
          else {
              //Validate the error why the Event->save() method returned false
              $this->validateErrors($this->Event);
              $this->set('errmsg', 'Save Evaluation failure.');
              $this->redirect('/evaluations/makeRubricEvaluation/'.$eventId.';'.$groupId);
          }//end if
      }
  }

  function validRubricEvalComplete ($form=null)
  {
  $status = true;
  return $status;
  }

  function completeEvaluationRubric () {
      $status = true;

      $eventId = $this->params['form']['event_id'];
      $groupId = $this->params['form']['group_id'];
      $courseId = $this->params['form']['course_id'];
      $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
      $evaluateeCount = $this->params['form']['evaluateeCount'];

      $groupEventId = $this->params['form']['group_event_id'];
      //Get the target group event
      $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
      $this->GroupEvent->id = $groupEvent['GroupEvent']['id'];

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
      $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupId, $groupEventId);
      $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
      //Check to see if all members are completed this evaluation
      if($numOfCompletedCount == $evaluateeCount ){
          $groupEvent['GroupEvent']['marked'] = 'to review';
          if (!$this->GroupEvent->save($groupEvent)) {
              $status = false;
          }
      }

      if ($status) {
          $this->redirect('/home/index/Your Evaluation was submitted successfully.');
      } else {
          $this->redirect('/evaluations/makeRubricEvaluation/'.$eventId.';'.$groupId);
      }
  }

  function makeMixevalEvaluation ($param = '')  {
      $this->autoRender = false;

      if (empty($this->params['data'])) {
          $tok = strtok($param, ';');
          $eventId = $tok;
          $groupId = strtok(';');
          //$msg = strtok(';');
          $event = $this->EvaluationHelper->formatEventObj($eventId, $groupId);
          $this->set('event', $event);

          $this->set('evaluator_id',$this->Auth->user('id'));
          $this->set('full_name', $this->Auth->user('first_name').' '.$this->Auth->user('last_name'));
          //Setup the courseId to session
          $this->rdAuth->setCourseId($event['Event']['course_id']);
          $courseId = $event['Event']['course_id'];
          $this->set('courseId', $courseId);
          $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId, 'S').' > Evaluate Peers');
          $mixEvalDetail = $this->EvaluationMixevalHelper->loadMixEvaluationDetail($event);
          $this->set('view_data', $this->MixevalHelper->compileViewData($mixEvalDetail['mixeval']));
          $this->set('data', $mixEvalDetail['mixeval']);
          $this->set('groupMembers', $mixEvalDetail['groupMembers']);
          $this->set('evaluateeCount', $mixEvalDetail['evaluateeCount']);

          $this->render('mixeval_eval_form');
      } else {
          $eventId = $this->params['form']['event_id'];
          $groupId = $this->params['form']['group_id'];
          $groupEventId = $this->params['form']['group_event_id'];
          $courseId = $this->params['form']['course_id'];
          $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
          if (!$this->validMixevalEvalComplete($this->params['form'])) {
              $this->redirect('/evaluations/makeMixevalEvaluation/'.$eventId.';'.$groupId);
          }

          if ($this->EvaluationMixevalHelper->saveMixevalEvaluation($this->params)) {
              $this->redirect('/evaluations/makeMixevalEvaluation/'.$eventId.';'.$groupId);
          }
          //Found error
          else {
              //Validate the error why the Event->save() method returned false
              $this->validateErrors($this->Event);
              $this->set('errmsg', 'Save Evaluation failure.');
              $this->redirect('/evaluations/makeMixevalEvaluation/'.$eventId.';'.$groupId);
          }//end if
      }
  }

  function validMixevalEvalComplete ($form=null)
  {
      $status = true;
      return $status;
  }

  function completeEvaluationMixeval ()  {
      $status = true;

      $eventId = $this->params['form']['event_id'];
      $groupId = $this->params['form']['group_id'];
      $courseId = $this->params['form']['course_id'];
      $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
      $evaluateeCount = $this->params['form']['evaluateeCount'];

      $groupEventId = $this->params['form']['group_event_id'];
      //Get the target group event
      $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
      $this->GroupEvent->id = $groupEvent['GroupEvent']['id'];

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
      if (!$this->EvaluationSubmission->save($evaluationSubmission))  {
          $status = false;
      }

      //checks if all members in the group have submitted
      //the number of submission equals the number of members
      //means that this group is ready to review
      $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupId, $groupEventId);
      $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
      //Check to see if all members are completed this evaluation
      if($numOfCompletedCount == $evaluateeCount ) {
          $groupEvent['GroupEvent']['marked'] = 'to review';
          if (!$this->GroupEvent->save($groupEvent)) {
              $status = false;
          }
      }

      if ($status) {
          $this->redirect('/home/index/Your Evaluation was submitted successfully.');
      } else {
          $this->redirect('/evaluations/makeMixevalEvaluation/'.$eventId.';'.$groupId);
      }
  }

  function viewEvaluationResults($eventId, $groupId, $displayFormat="")
  {
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();

    if (!is_numeric($eventId) || !is_numeric($groupId)) {
        exit;
    }


    $this->autoRender = false;
    $this->layout = 'pop_up';

    $courseId = $this->rdAuth->courseId;
    $event = $this->EvaluationHelper->formatEventObj($eventId, $groupId);
    $this->set('event', $event);
    $this->set('title_for_layout', !empty($event['Event']) ? $this->sysContainer->getCourseName($courseId).' > '.$event['Event']['title']. ' > Results ':'');


    switch ($event['Event']['event_template_type_id'])
    {
    case 1:  //View Simple Evaluation Result
        $formattedResult = $this->EvaluationSimpleHelper->formatSimpleEvaluationResult($event);
        $this->set('scoreRecords', $formattedResult['scoreRecords']);
        $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
        $this->set('evalResult', $formattedResult['evalResult']);
        $this->set('groupMembers', $formattedResult['groupMembers']);
        $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
        $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
        $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
        $this->render('view_simple_evaluation_results');
        break;

    case 2: //View Rubric Evaluation
          $formattedResult = $this->EvaluationRubricHelper->formatRubricEvaluationResult($event, $displayFormat);
          $this->set('rubric', $formattedResult['rubric']);
          if (isset($formattedResult['groupMembers'])) $this->set('groupMembers', $formattedResult['groupMembers']);
          if (isset($formattedResult['reviewEvaluations'])) $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
          if (isset($formattedResult['rubricCriteria'])) $this->set('rubricCriteria', $formattedResult['rubricCriteria']);
          $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
          $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
          $this->set('scoreRecords', $formattedResult['scoreRecords']);
          $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
          $this->set('evalResult', $formattedResult['evalResult']);
          $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
        if ($displayFormat == 'Detail') {
          $this->render('view_rubric_evaluation_results_detail');
        } else {
          $this->render('view_rubric_evaluation_results');
        }
        break;

    case 3: // View Survey
      $studentId = $groupId;
      $formattedResult = $this->EvaluationSurveyHelper->formatSurveyEvaluationResult($event,$studentId);

      $this->set('survey_id', $formattedResult['survey_id']);
      $this->set('answers', $formattedResult['answers']);
      $this->set('questions', $formattedResult['questions']);
      $this->set('event', $formattedResult['event']);

      $this->render('view_survey_results');
      break;

    case 4:  //View Mix Evaluation
          $formattedResult = $this->EvaluationMixevalHelper->formatMixevalEvaluationResult($event, $displayFormat);
          $this->set('mixeval', $formattedResult['mixeval']);
          if (isset($formattedResult['groupMembers'])) $this->set('groupMembers', $formattedResult['groupMembers']);
          if (isset($formattedResult['reviewEvaluations'])) $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
          if (isset($formattedResult['mixevalQuestion'])) $this->set('mixevalQuestion', $formattedResult['mixevalQuestion']);
          $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
          $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
          $this->set('scoreRecords', $formattedResult['scoreRecords']);
          $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
          $this->set('evalResult', $formattedResult['evalResult']);
          $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);



        if ($displayFormat == 'Detail') {
          $this->render('view_mixeval_evaluation_results_detail');

        } else {
          $this->render('view_mixeval_evaluation_results');
        }
      break;

    }
  }

  function viewSurveyGroupEvaluationResults($params=null) {
      $this->layout = 'pop_up';
      $this->autoRender = false;

      $surveyId = strtok($params,';');
      $surveyGroupId = strtok(';');

      $formattedResult = $this->EvaluationSurveyHelper->formatSurveyGroupEvaluationResult($surveyId,$surveyGroupId);

      $this->set('questions', $formattedResult);
      $this->render('view_survey_summary');
  }

  function studentViewEvaluationResult($param=null)
  {
      $this->autoRender = false;
      $this->layout = 'pop_up';
      $tok = strtok($param, ';');
      $eventId = $tok;
      $groupId = strtok(';');

      //Setup CurrentUser Info
      $currentUser = $this->User->getCurrentLoggedInUser();
      $this->set('currentUser', $currentUser);

      //Get the target event
      $event = $this->EvaluationHelper->formatEventObj($eventId, $groupId);
      $this->set('event', $event);

      //Setup the courseId to session
      $this->rdAuth->setCourseId($event['Event']['course_id']);
      $courseId = $this->rdAuth->courseId;
      $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId,$this->rdAuth->role).' > '.$event['Event']['title']. ' > View My Results ');

      //Get Group Event
      $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($event['Event']['id'], $event['group_id']);

      switch ($event['Event']['event_template_type_id'])
      {
          case 1: //View Simple Evaluation Result
              $studentResult = $this->EvaluationSimpleHelper->formatStudentViewOfSimpleEvaluationResult($event);
              $this->set('studentResult', $studentResult);
              $this->render('student_view_simple_evaluation_results');
              break;

          case 2: //View Rubric Evaluation Result
              $formattedResult = $this->EvaluationRubricHelper->formatRubricEvaluationResult($event, 'Detail', 1);
              $this->set('rubric', $formattedResult['rubric']);
              if (isset($formattedResult['groupMembers'])) $this->set('groupMembers', $formattedResult['groupMembers']);
              if (isset($formattedResult['reviewEvaluations'])) $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
              if (isset($formattedResult['rubricCriteria'])) $this->set('rubricCriteria', $formattedResult['rubricCriteria']);
              $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
              $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
              $this->set('scoreRecords', $formattedResult['scoreRecords']);
              $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
              $this->set('evalResult', $formattedResult['evalResult']);
              $this->set('gradeReleaseStatus', $formattedResult['gradeReleaseStatus']);
              $this->render('student_view_rubric_evaluation_results');
              break;

          case 3: //View Survey Result
              $formattedResult = $this->EvaluationSurveyHelper->formatSurveyEvaluationResult($event);
              $this->set('survey_id', $result['survey_id']);
              $this->set('answers', $result['answers']);
              $this->set('questions', $result['questions']);
              $this->set('event', $result['event']);
              $this->render('student_view_survey_evaluation_results');
              break;

          case 4: //View Mix Evaluation Result
              $formattedResult = $this->EvaluationMixevalHelper->formatMixevalEvaluationResult($event, 'Detail', 1);
              $this->set('mixeval', $formattedResult['mixeval']);
              if (isset($formattedResult['groupMembers'])) $this->set('groupMembers', $formattedResult['groupMembers']);
              if (isset($formattedResult['reviewEvaluations'])) $this->set('reviewEvaluations', $formattedResult['reviewEvaluations']);
              if (isset($formattedResult['mixevalQuestion'])) $this->set('mixevalQuestion', $formattedResult['mixevalQuestion']);
              $this->set('allMembersCompleted', $formattedResult['allMembersCompleted']);
              $this->set('inCompletedMembers', $formattedResult['inCompletedMembers']);
              $this->set('scoreRecords', $formattedResult['scoreRecords']);
              $this->set('memberScoreSummary', $formattedResult['memberScoreSummary']);
              $this->set('evalResult', $formattedResult['evalResult']);

              $this->render('student_view_mixeval_evaluation_results');
              break;
      }
  }

  function markEventReviewed ()
  {
      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();

    $this->autoRender = false;
  $eventId = $this->params['form']['event_id'];
  $groupId =  $this->params['form']['group_id'];
  $groupEventId = $this->params['form']['group_event_id'];
      $reviewStatus = isset($this->params['form']['mark_reviewed'])? "mark_reviewed" : "mark_not_reviewed";
  $courseId = $this->rdAuth->courseId;

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
      else if ($reviewStatus == "mark_not_reviewed"){
          //Get Members whom completed evaluation
          $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupId, $groupEventId);
          //Check to see if all members are completed this evaluation
          $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
          $numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->find(count,'group_id='.$groupId) :
                                                     $this->GroupsMembers->find(count,'group_id='.$groupId) - 1;
          ($numOfCompletedCount == $numMembers) ? $completeStatus = 1:$completeStatus = 0;
          if ($completeStatus)
          {
              $groupEvent['GroupEvent']['marked'] ='to review';
              $this->GroupEvent->save($groupEvent);
          }
          else {
              $groupEvent['GroupEvent']['marked'] ='not reviewed';
              $this->GroupEvent->save($groupEvent);
          }
      }
      $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId);
  }

  function markGradeRelease($param)
  {
    // Make sure the present user is not a student
    $this->rdAuth->noStudentsAllowed();

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
        $this->EvaluationSimpleHelper->changeEvaluationGradeRelease ($eventId, $groupId, $groupEventId, $evaluateeId, $releaseStatus);
      $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId);
      break;

      case "2":
        $this->EvaluationRubricHelper->changeEvaluationGradeRelease($eventId, $groupId, $groupEventId,
                                                                    $evaluateeId, $releaseStatus);
      $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
      break;

      case "4":
        $this->EvaluationMixevalHelper->changeEvaluationGradeRelease($eventId, $groupId, $groupEventId,
                                                                     $evaluateeId, $releaseStatus);
      $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
      break;
    }

  }

  function markCommentRelease($param = null)
  {
      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();


    $this->autoRender = false;
    if ($param !=null) {
           $tok = strtok($param, ';');
      $eventId = $tok;
    } else {
      $eventId = $this->params['form']['event_id'];
    }

    $this->Event->id = $eventId;
    $event = $this->Event->read();


    switch ($event['Event']['event_template_type_id']) {
      case "1":
              $groupId =  $this->params['form']['group_id'];

          if(isset($this->params['form']['evaluator_ids']))
      {
              $groupEventId = $this->params['form']['group_event_id'];
              $evaluatorIds = $this->params['form']['evaluator_ids'];
              $this->EvaluationSimpleHelper->changeEvaluationCommentRelease ($eventId, $groupId, $groupEventId, $evaluatorIds, $this->params);
      }

          $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId);
        break;

      case "2":
      $groupId =  strtok(';');
      $evaluateeId =  strtok(';');
      $groupEventId = strtok(';');
          $releaseStatus = strtok(';');
        $this->EvaluationRubricHelper->changeEvaluationCommentRelease($eventId, $groupId,
                                                                  $groupEventId, $evaluateeId, $releaseStatus);
          $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
        break;

      case "4":
      $groupId =  strtok(';');
      $evaluateeId =  strtok(';');
      $groupEventId = strtok(';');
          $releaseStatus = strtok(';');
        $this->EvaluationMixevalHelper->changeEvaluationCommentRelease($eventId, $groupId,
                                                                  $groupEventId, $evaluateeId, $releaseStatus);
          $this->redirect('evaluations/viewEvaluationResults/'.$eventId.'/'.$groupId.'/Detail');
        break;
    }

  }

  function changeAllCommentRelease ($param=null)
  {
    // Make sure the present user is not a student
    $this->rdAuth->noStudentsAllowed();

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
      $this->GroupEvent->id = $groupEvent['GroupEvent']['id'];

      //Get Members whom completed evaluation
      $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent['GroupEvent']['group_id'],
                                                                                 $groupEvent['GroupEvent']['id']);

      //Check to see if all members are completed this evaluation
      $numOfCompletedCount = $memberCompletedNo[0][0]['count'];

      $numMembers = $this->GroupsMembers->find(count,'group_id='.$groupEvent['GroupEvent']['group_id']);

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

  function changeAllGradeRelease ($param=null)
  {
    // Make sure the present user is not a student
    $this->rdAuth->noStudentsAllowed();

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
    foreach($groupEventList as $groupEvent) {
      $this->GroupEvent->id = $groupEvent['GroupEvent']['id'];

      //Get Members whom completed evaluation
      $memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent['GroupEvent']['group_id'],
                                                                                 $groupEvent['GroupEvent']['id']);
      //Check to see if all members are completed this evaluation
      $numOfCompletedCount = $memberCompletedNo[0][0]['count'];
      $numMembers = $this->GroupsMembers->find(count,'group_id='.$groupEvent['GroupEvent']['group_id']);

      if (($numOfCompletedCount != 0) && ($numOfCompletedCount < $numMembers)) {
        $completeStatus = 'Some';
      } elseif ($releaseStatus && ($numOfCompletedCount == $numMembers)) {
        $completeStatus = 'All';
      } else {
        $completeStatus = 'None';
      }

      if ($releaseStatus == 0)
        $groupEvent['GroupEvent']['grade_release_status'] = 'None';
      else
        $groupEvent['GroupEvent']['grade_release_status'] = $completeStatus;
      $this->GroupEvent->save($groupEvent);
    }

    $this->redirect('/evaluations/view/'.$eventId);
  }

  function viewGroupSubmissionDetails ($eventId, $groupId)
  {
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();

    $this->layout = 'pop_up';

    $this->set('title_for_layout', 'Submission Details');

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
    $this->set('groupEventId', $groupEvent['GroupEvent']['id']);
  }

function reReleaseEvaluation ()
{
      // Make sure the present user is not a student
      $this->rdAuth->noStudentsAllowed();

  $this->autoRender = false;

  $groupEventId = $this->params['form']['group_event_id'];
  $groupId = $this->params['form']['group_id'];
  $eventId = $this->params['form']['event_id'];
  if (!empty($this->params['form']['release_member'])) {  // were any students selected?
      $releaseMemberIds = $this->params['form']['release_member'];
      foreach ($releaseMemberIds as $userId) {
          $evalSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $userId);
          $evalSubmission['EvaluationSubmission']['submitted'] = 0;
          $this->EvaluationSubmission->id = $evalSubmission['EvaluationSubmission']['id'];
          //$this->EvaluationSubmission->save($evalSubmission);
          $this->EvaluationSubmission->del();
      }
  }
  $this->redirect('/evaluations/viewGroupSubmissionDetails/'.$eventId.'/'.$groupId);
}

function viewSurveySummary($surveyId=null) {
  $this->layout = 'pop_up';

  $formattedResult = $this->EvaluationSurveyHelper->formatSurveyEvaluationSummary($surveyId);

 // $this->set('survey_id', $formattedResult['survey_id']);
  //$this->set('answers', $formattedResult['answers']);
  $this->set('questions', $formattedResult);
 // print_r($formattedResult);
}

  function export_rubic($eventId=301,$rubicEvalId=72)
  {
      $this->autoRender=false;
      $this->autoLayout=false;
      $courseId=$this->Session->read('ipeerSession.courseId');
      $questions= $this->RubricsCriteria->find('all', array('conditions' => array('rubric_id' => $rubicEvalId), 'fields' => array("id","criteria")));
      $numberOfCriteria = count($questions);

      $groups = $this->GroupEvent->find('all', array('conditions' => array('event_id' => $eventId,
                                                                           'group_id <>' => 0),
                                                     'fields' => array('group_id'),
                                                     'order' => 'group_id ASC'));//,null,null,false);

      foreach ($questions as $key=>$array){
         $questionsArray[$array['RubricsCriteria']['id']]=$array['RubricsCriteria']['criteria'];
      }

      foreach ($groups as $key=>$array)
          $groupsArray[]=$array['GroupEvent']['group_id'];

      foreach ($groupsArray as $key=>$value)
          $groupMembersArrayComplete[]=$this->GroupsMembers->find('all', array('conditions' => array('group_id' => $value)));

      for ($i=0;$i<count($groupMembersArrayComplete);$i++)
          foreach ($groupMembersArrayComplete[$i] as $key=>$array)
          $groupMembersArraySimple[$groupsArray[$i]][]=$array['GroupsMembers']['user_id'];

      foreach ($groupMembersArraySimple as $key=>$array)
          foreach ($array as $index=>$value)
              $groupMembersArrayWithName[$key][$value]=$this->User->field("first_name","id=$value").' '.$this->User->field('last_name',"id=$value");

      foreach ($groupMembersArrayWithName as $key=>$array)
          foreach ($array as $index=>$value)
              $studetnsWithName[$index]=$value;

      ksort($studetnsWithName);

      $groupEvents=$this->GroupEvent->find('all', array('conditions' => array('event_id' => $eventId),
                                                        'fields' => array('id'),
                                                        'order' => 'id ASC'));//,null,null,false);

      $this->pre($groupEvents);

      $groupEventsArray=array();

      foreach ($groupEvents as $key=>$array)
          $groupEventsArray[]=$array['GroupEvent']['id'];

//$this->pre ($groupEventsArray);

//        $evalutorToEvaluateesByGroup=array();

      foreach ($groupEventsArray as $key=>$value) {
          $sql = "SELECT id,evaluator,evaluatee FROM `evaluation_rubrics` WHERE `grp_event_id` = $value ORDER BY `evaluator` ASC, `evaluatee` ASC";
          $result=$this->EvaluationRubric->query($sql);
          $evalutorToEvaluatees=array();
          foreach ($result as $resultKey=>$resultArray){
              if (!isset($evalutorToEvaluatees[$resultArray['evaluation_rubrics']['evaluator']]))
                  $evalutorToEvaluatees[$resultArray['evaluation_rubrics']['evaluator']]=array();

          foreach ($resultArray as $rubricEvalKey=>$rubricEvalValue) {
                  for($i=1;$i<=$numberOfCriteria;$i++){
                     $evalutorToEvaluatees[$rubricEvalValue['evaluator']]['evaluatee'][$rubricEvalValue['evaluatee']]['grade'][$i]=$this->EvaluationRubricDetail->field('grade',array('evaluation_rubric_id'=>$rubricEvalValue['id'],'criteria_number'=>$i));

                     $evalutorToEvaluatees[$rubricEvalValue['evaluator']]['evaluatee'][$rubricEvalValue['evaluatee']]['criteria_comment'][$i]=$this->EvaluationRubricDetail->field('criteria_comment',array('evaluation_rubric_id'=>$rubricEvalValue['id'],'criteria_number'=>$i));
                  }
              }
          }
          $evalutorToEvaluateesByGroup[]=$evalutorToEvaluatees;

      }



      $evalutorToEvaluateesArray=array();
      foreach ($evalutorToEvaluateesByGroup as $groupIndex => $groupData)
          foreach ($groupData as $evaluatorId => $evaluateeList)
              foreach ($evaluateeList as $evaluateeId => $evalDetails)
                  foreach ($evalDetails as $key => $value)
                      $evalutorToEvaluateesArray[$evaluatorId][$key]=$value;

$this->pre(array_keys($evalutorToEvaluateesByGroup)); die;
      $options=array('Grade'=>'grade','Criteria Comment'=>'criteria_comment');
      foreach ($options as $optionsIndex => $mode) {
          echo "<h1>$optionsIndex</h1>";
          for ($questionIndex=1;$questionIndex<=$numberOfCriteria;$questionIndex++){
              foreach ($evalutorToEvaluateesByGroup as $groupIndex => $groupData) {
                  $evaluators=array_keys($groupData);
                  if (empty($evaluators)) continue;
                  echo "<h2>Question $questionIndex Group ".($groupIndex+1)."($optionsIndex)</h2>";
                  echo "<table border=1>";
                  $numberOfGroupMembers=count($groupMembersArrayComplete[$groupIndex]);
                  //row
              for ($rowIndex=0;$rowIndex<count($evaluators)+1;$rowIndex++) {
              echo "<tr>";
              //column
                  for ($columnIndex=0;$columnIndex<count($evaluators)+1;$columnIndex++) {
                      if ($rowIndex==0 && $columnIndex==0) echo "<td>&nbsp</td>";
                      elseif ($rowIndex==0 && $columnIndex >0 ) echo "<td>".$studetnsWithName[$evaluators[$columnIndex-1]]."</td>";
                      elseif ($rowIndex>0 && $columnIndex == 0 ) echo "<td>".$studetnsWithName[$evaluators[$rowIndex-1]]."</td>";
                      elseif ($rowIndex==$columnIndex && $rowIndex !=0) echo "<td> / </td>";
                  else {
                      if (isset($evalutorToEvaluateesArray[$evaluators[$rowIndex-1]][$evaluators[$columnIndex-1]][$mode][$questionIndex]))
                          echo "<td>".$evalutorToEvaluateesArray[$evaluators[$rowIndex-1]][$evaluators[$columnIndex-1]][$mode][$questionIndex]."&nbsp</td>";
                      else
                          echo "<td>N/A</td>";
                  }
              }
              echo "</tr>";
          }
          echo "</table";

      }
      }

      }



  }

function export_test($eventId=null,$groupID=null)
  {
      $this->autoLayout=false;
      $this->autoRender=false;
      //step 1: get Event title
      $event_title=$this->Event->field('title',"id=$eventId");

      //step 2: get Group Info
      $group_number=$this->Group->field('group_num',"id=$groupID"); //field 1
      $group_name=$this->Group->field('group_name',"id=$groupID");//field 2

      //step 3: Get Group members

      //step 3.1 Get Group Membrer user_id
      $user_id=$this->extractModel("GroupsMembers",$this->GroupsMembers->find('all', "group_id=$groupID","user_id","user_id asc",null,null,false),"user_id");
      //step 3.2 Get Group Membrer user_data
      $user_data=array();
      foreach ($user_id as $key=>$value)
      {
          $user_data["$value"]=$this->User->find("id=$value",array("first_name","last_name","student_no","email"),"id asc",false);
      }   //field 3,4,5,6

      //pre ($user_data);

      //step 4   (if Rubric)

      //step 4.1 Get evaluation_rubic id(s)

       $evaluation_rubric_id=$this->extractModel("EvaluationRubric",$this->EvaluationRubric->find('all', "event_id=$eventId","id","id asc",null,null,false),"id");
       //pre($evaluation_rubric_id);

      //step 4.2 Get evaluation_rubic general data
      $evaluation_rubric_general_data=array();
      foreach ($evaluation_rubric_id as $key=>$value)
      {
          $evaluation_rubric_general_data["$value"]=$this->EvaluationRubric->find("id=$value",array("evaluator","evaluatee","general_comment","score"),"id asc",false);  //field 7,8
      }

      //pre($evaluation_rubric_general_data);

      //step 4.2.1 get evaluatee->evaluator array
      $evaluateesToEvaluators=array();
      foreach ($evaluation_rubric_general_data as $key=>$value)
          $evaluateesToEvaluators[$user_data[$value['EvaluationRubric']['evaluatee']]['User']['student_no']][]=$key;
      // pre ($evaluateesToEvaluators);

      //step 4.3 Get rubric evaluation specific data
      $evaluation_rubric_specific_data=array();
      foreach ($evaluation_rubric_id as $key=>$value)
      {
          $evaluation_rubric_specific_data["$value"]=$this->EvaluationRubricDetail->find('all', "evaluation_rubric_id=$value",array("criteria_number","criteria_comment",),"evaluation_rubric_id asc",null,false);     //field 9
      }
      //pre($evaluation_rubric_specific_data);

      //step 5 Get Rubic title
      $rubtic_id=$this->Event->field('template_id',"id=$eventId");
      $rubtic_title=$this->Rubric->field("name","id=$rubtic_id");
      //step 6 Get Rubic criteria
      //$rubric_criteria=$this->RubricsCriteria->generateList("rubric_id=$rubtic_id","rubric_id asc",null,"{n}.RubricsCriteria.criteria_num","{n}.RubricsCriteria.criteria");
      $rubric_criteria = $this->RubricsCriteria->find("list", array('conditions' => 'rubric_id = '.$rubtic_id,
                                                                    'order'      => 'rubric_id asc',
                                                                    'limit'      => null,
                                                                    'fields'     => array('RubricsCriteria.criteria_num', 'RubricsCriteria.criteria')
                                                                   ));

      //pre($rubric_criteria);

      //output
       $fh=fopen("../tmp/test/output.csv","w");
       fputcsv($fh,array("Event Name",$event_title));
       fputcsv($fh,array("Event Type","Rubric"));
       fputcsv($fh,array("Criteria number","Criteria"));
       foreach ($rubric_criteria as $key=>$value)
       fputcsv($fh,array($key,$value));

      $header=array("Group Number","Group Name","First Name","Last Name","Student Number","Email","General Comments","Score","Specific Comments");

      fputcsv($fh,$header);
      foreach ($user_data as $key=>$value)
      {
          $line=array();
          array_push($line,$group_name);
          array_push($line,$group_number);
          $score_total=0;
          //bad coding style
          foreach ($value['User'] as $key_2=>$value_2)
          array_push($line,$value_2);
          $general_comments='';
          //general comments
          foreach ($evaluation_rubric_general_data as $key=>$array)
          {
              //1) get evaluatee id;
              $evaluatee_id=$array['EvaluationRubric']['evaluatee'];
              //2) get student number from user_data
              $student_number=$user_data[$evaluatee_id]['User']['student_no'];                //3) if current student is the evaluatee
              if ($value['User']['student_no']==$student_number)
              {
                  $general_comments=$general_comments.''.$array['EvaluationRubric']['general_comment'].';';
                  //add up score
                  $score_total=$score_total+$array['EvaluationRubric']['score'];
              }
          }

          array_push($line,$general_comments);
          array_push($line,$score_total);
          $specific_commens='';
          foreach ($evaluateesToEvaluators[$value['User']['student_no']] as $index=>$value)
          {

              //bad coding style
              foreach ($evaluation_rubric_specific_data[$value] as $index_2=>$array_2)
                    $specific_commens=$specific_commens.''.$array_2['EvaluationRubricDetail']['criteria_comment'].';';
      }
      array_push($line,$specific_commens);
      fputcsv($fh,$line);
      unset($line);
      }
      fclose($fh);
  header("Location: ../tmp/test/output.csv");



  }

  public function pre($para) {
      $this->autoRender=false;
      $this->autoLayout=false;
      $para=print_r($para,true);
      echo "<pre>$para</pre>";
  }

}
?>
