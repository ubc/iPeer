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
 * @lastmodified $Date: 2006/11/01 20:20:34 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: SurveyGroups
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
uses ('file','folder');
class SurveyGroupsController extends AppController
{
  var $uses =  array('Course', 'Survey', 'User', 'Question', 'SurveyQuestion', 'Response','Personalize','Event','EvaluationSubmission','UserEnrol','SurveyInput','SurveyGroupMember','SurveyGroupSet','SurveyGroup','Group','GroupsMembers','Event');
  var $name = 'SurveyGroups';
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;
  var $helpers = array('Html','Ajax','Javascript');
  var $components = array('Output','sysContainer', 'userPersonalize', 'framework','XmlHandler','AjaxList');

  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'created': $this->Sanitize->paranoid($_GET['sort']);
    $this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->set('title_for_layout', __('Survey Groups', true));
    parent::__construct();
  }

  function postProcess($data) {
    if (empty($data)) {
      return $data;
    }

    foreach ($data as $key => $entry) {
      $data[$key]['SurveyGroupSet']['released'] = $entry['SurveyGroupSet']['released'] == 0 ? 'No' : 'Yes';
    }
    return $data;
  }

  function setUpAjaxList() {
    $course_id = $this->Session->read('ipeerSession.courseId');

    // Set up Columns
    $columns = array(
        array("SurveyGroupSet.id",            "",            "",      "hidden"),
        array("Survey.name",      __("Survey", true),         "auto",  "string",   ""),
        array("SurveyGroupSet.group_count",        __("Number of Groups", true),      "13em",  "number", ""),
        array("SurveyGroupSet.released",         __("Released?", true),       "9em", "string", ""),
        );

    $extraFilters = array("Survey.course_id" => $course_id);

    // Set up actions
    $warning = __("Are you sure you want to delete this group set permanently?", true);
    $actions = array(
        array(__("Release", true), "", "", "", "release", "SurveyGroupSet.id"),
        array(__("View/Edit Group Set", true), "", "", "", "edit", "SurveyGroupSet.id"),
        array(__("Delete Group Set", true), $warning, "", "", "delete", "SurveyGroupSet.id"),
        );

    $recursive = 0;

    $this->AjaxList->setUp($this->SurveyGroupSet, $columns, $actions,
        "SurveyGroupSet.id", "SurveyGroupSet.id", array(), $extraFilters, $recursive, 'postProcess');
  }

  function index() {
    $this->set('course_id', $this->Session->read('ipeerSession.courseId'));
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  function viewresult($params=null) {
    if (is_null($params))
      $courseId = $this->Session->read('ipeerSession.courseId');
    else { //TODO
//      $eventId = $params;
//      $event = $this->Event->findById($eventId);
//      $courseId = $event['Event']['course_id'];
//      $this->rdAuth->setCourseId($courseId);
       $courseId = $params;
    }

    $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).__(' > View Survey Result', true));
    //get surveys for the course
    $survey_list = $this->Survey->find('list', array('conditions' => array('course_id' => $courseId)));
    $ids = array_keys($survey_list);
    $survey = $this->Survey->getSurveyWithSubmissionById($ids);

    $this->set('survey_list', $survey_list);
    $this->set('data',$survey);
  }

  function viewresultsearch() {
    $this->layout = false;
    $conditions = array();

    //search function
    if (!empty($this->data['livesearch2']) && !empty($this->data['select']))
    {
      $pagination->loadingId = 'loading';
      //parse the parameters
      $searchField=$this->data['select'];
      $searchValue=$this->data['livesearch2'];
      $conditions = array('OR' => array('Enrol.first_name LIKE' => '%'.$searchValue.'%',
                                        'Enrol.last_name LIKE' => '%'.$searchValue.'%'));
    }

    $survey = $this->Survey->getSurveyWithSubmissionById($this->data['survey_select'], $conditions);
    $this->set('data',$survey);
  }

  function makegroups($course_id) {
    $course = $this->Course->find('first', array('conditions' => array('id' => $course_id),
                                                 'contain' => array()));
    $courseName = $course['Course']['course'];
    $this->set('title_for_layout', $courseName.__(' > Create Group Set', true));
    $this->set('surveys', $this->Survey->find('list', array('conditions' => array('course_id' => $course_id))));
  }

  function makegroupssearch() {
    $this->layout = false;

    if(empty($this->data['survey_select'])) {
      $this->autoRender = false;
       return '';
    }

    $survey = $this->Survey->read(null, $this->data['survey_select']);
    $this->set('group_list', $this->__makeGroupList($survey['Course']['student_count']));

    $this->set('data', $survey);
  }

  function maketmgroups($params=null,$time=null,$make=true) {
      /*$time = 1290644146;
      $make = false;*/
    $numGroups = $this->data['group_config'];
    $survey_id = $this->data['survey_id'];
    $survey = $this->Survey->find('first', array('conditions' => array('Survey.id' => $survey_id),
                                                 'recursive' => 2));

    //make xml for TeamMaker
    $doc = $this->XmlHandler->makeTeamMakerXML($survey, $numGroups, $this->params['form']['weight']);

    //saves the 'in' file
    $time = (isset($time) ? $time: (String)time());

    $file_path = TMP.$time;
    $this->File = new File($file_path.'.xml');
    $this->File->write($doc);
    //execute TeamMaker
    $cmdline = $this->__getTeamMaker().' '.$file_path.'.xml '.$file_path.'.txt';// > ../tmp/tm_log.txt";

    set_time_limit(1200);
    if ($make) exec($cmdline);

    //parse results and display
    $teams_tmp = file($file_path.'.txt');
    $teams = array();

    //format the team recordset
    for ($i=0; $i < count($teams_tmp); $i++) {
      $team = $teams_tmp[$i];
      $members = explode(' ',$team);
      for ($j=0; $j < count($members); $j++) {
        $member = $members[$j];
        $member_id = $this->User->getUserIdByStudentNo($member);
        $teams[$i]['member_'.$j]['student_no'] = $member;
        $teams[$i]['member_'.$j]['id'] = $member_id;
      }
    }

    $this->__cleanXmlFile($file_path.'.txt.scores');

    $scores = $this->XmlHandler->readTMXml(count($survey['Question']), $file_path.'.txt.scores');
    $this->set('scores',$scores);
    $this->set('teams',$teams);

    $this->set('filename',$time);
    $this->set('survey_id', $survey_id);
    $this->set('event_id',$survey['Event'][0]['id']);
  }

  function savegroups() {
    $this->autoRender = false;
    $time = $this->data['filename'];
    $setDescription = $this->data['team_set_name'];
    $surveyId = $this->data['survey_id'];
    //get teams
    $teams_tmp = file(TMP.$time.'.txt');

    //save group sets
    $numGroups = count($teams_tmp);
    $surveyGroupSet = array();
    $surveyGroupSet['SurveyGroupSet']['survey_id'] = $surveyId;
    $surveyGroupSet['SurveyGroupSet']['set_description'] = $setDescription;
    $surveyGroupSet['SurveyGroupSet']['num_groups'] = $numGroups;
    $surveyGroupSet['SurveyGroupSet']['date'] = $time;

    for ($i = 0; $i < count($teams_tmp); $i++) {
        //save groups
        $team = $teams_tmp[$i];

        $group = array();
        $group['group_number'] = $i+1;

        $members = explode(' ',trim($team));
        $group_members = array();
        foreach ($members as $member) {
            //save group members
            $member_id = $this->User->getUserIdByStudentNo($member);
            $surveyGroupMember = array();
            $surveyGroupMember['user_id'] = $member_id;
            $group_members[] = $surveyGroupMember;
        }
        $surveyGroupSet['SurveyGroup'][]['SurveyGroup'] = $group;
        $surveyGroupSet['SurveyGroup'][$i]['SurveyGroupMember'] = $group_members;
    }

    // use overwritten save method to save all
    if($this->SurveyGroupSet->save($surveyGroupSet)) {
      $this->Session->setFlash(__('The group set was added successfully.', true));
    } else {
      $this->Session->setFlash(__('The group set saving failed.', true));
    }
    $this->redirect('index');
  }

  function release($group_set_id) {
    // Check for a valid parameter
    if (!is_numeric($group_set_id)) {
      $this->Session->setFlash(__('Group Set must be a numeric id', true));
      $this->redirect('index');
    }

    if($this->SurveyGroupSet->release($group_set_id)) {
      $this->Session->setFlash(__('The group set was released successfully.', true));
    } else {
      $this->Session->setFlash(__('Releasing group set failed.', true));
    }
    $this->redirect('index');
  }


  function delete($group_set_id) {
    $this->autoRender = false;

    if($this->SurveyGroupSet->delete($group_set_id)) {
      $this->Session->setFlash(__('The group set was deleted successfully.', true));
    } else {
      $this->Session->setFlash(__('Failed to delete group set.', true));
    }
    $this->redirect('index');
  }

  function edit($group_set_id, $question_id = null) {
    $this->set('title_for_layout', $this->sysContainer->getCourseName($this->Session->read('ipeerSession.courseId')).__(' > Edit Groupset', true));
    //get group set
    $group_set = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => $group_set_id),
                                                            'recursive' => 2));
    $time = $group_set['SurveyGroupSet']['date'];
    $scoreFilePathAndName = TMP.$time.'.txt.scores';
    $this->__cleanXmlFile($scoreFilePathAndName);

    $inputs = array();
    foreach ($group_set['SurveyGroup'] as $i => $survey_group) {
      foreach ($survey_group['Member'] as $j => $surveyGroupMember) {
        //if question selected, add responses to data
        if ($question_id != null) {
          $surveyInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($group_set['Survey']['id'],$surveyGroupMember['id'],$question_id);

          for ($k=0; $k < count($surveyInput); $k++) {
            $inputData = $surveyInput[$k]['SurveyInput'];
            $inputQuestionId = $inputData['question_id'];
            $inputResponseId = $inputData['response_id'];
            $inputResponseText = $inputData['response_text'];
            $inputQuestionType = $this->Question->getTypeById($inputQuestionId);

            $inputs[$surveyGroupMember['id']]['type'] = $inputQuestionType;
            if (!isset($inputs[$surveyGroupMember['id']]['id']) || !is_array($inputs[$surveyGroupMember['id']]['id']))
              $inputs[$surveyGroupMember['id']]['id'] = array();
            array_push($inputs[$surveyGroupMember['id']]['id'], $inputResponseId);
            $inputs[$surveyGroupMember['id']]['response_text'] = $inputResponseText;
          }
        } else {
          //links student to survey result if submitted
          $surveyInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId($group_set['Survey']['id'],$surveyGroupMember['id']);
          if (!empty($surveyInput))
            $inputs[$surveyGroupMember['id']] = 'yes';
        }
      }
    }

    if ($question_id != '') {
      $responses = $this->Response->getResponseByQuestionId($question_id);
      $this->set('responses',$responses);
    }

    $questions = array();
    foreach($group_set['Survey']['Question'] as $q) {
      $questions[$q['id']] = $q['prompt'];
    }

    $score = $this->XmlHandler->readTMXml(count($group_set['Survey']['Question']),$scoreFilePathAndName);

    //set data
    $this->set('score',$score);
    $this->set('selected_question',$question_id);
    $this->set('survey_id', $group_set['Survey']['id']);
    $this->set('questions', $questions);
    $this->set('inputs', $inputs);
    $this->set('data',$group_set);
    $this->set('event_id',$group_set['Survey']['Event'][0]['id']);
    $this->set('group_set_id',$group_set_id);
  }

  function changegroupset() {
    $this->autoRender = false;

    foreach ($this->data['move'] as $value) {
      if (empty($value)) continue;
      $data = explode('_',$value);
      if (!$this->SurveyGroupMember->updateAll(array('group_id' => $data[2]),
                                               array('user_id' => $data[0],
                                                     'group_id' => $data[1]))) {
        $this->Session->setFlash(__('Group set change failed.', true));
        $this->redirect('index');
      }
    }

    //group code end
    /*$time = $group_set['SurveyGroupSet']['date'];
    $out = file_get_contents($xmlFilePathAndName);
    $out = @ereg_replace("</team_input>",'',$out);
    $out = @ereg_replace("(<fixed>).+(</fixed>)",'',$out);
    $out .= "<fixed>";
    foreach ($this->data['move'] as $move) {
      if(empty($move)) continue;
      $move = explode('_', $move);
      as $i => $surveyGroup) {
      foreach ($surveyGroup['Member'] as $j => $member) {
        $out .= '<member name="'.$member['student_no'].'"/>'."\n";
      }
    }
      $out .= "</fixed>\n";
    $out .= "</team_input>";
    file_put_contents($xmlFilePathAndName,$out);

    //execute TeamMaker saves the 'in' file
    if(file_exists(COMPONENTS.'TeamMaker')) {
      $team_maker = 'TeamMaker';
    } else {//windows
      $team_maker = 'TeamMaker.exe';
    }
    $cmdline .= COMPONENTS.$team_maker.' '.$xmlFilePathAndName.' '.TMP.$time.'.txt > '.TMP.'tm_log.txt';

    set_time_limit(1200);
    exec($cmdline);*/

    $this->Session->setFlash(__('Group set changed successfully.', true));
    $this->redirect('index');
  }

  function __makeGroupList($num_students){
    $group_list = array();
    for($i = 2; $i <= $num_students / 2; $i++) {

      $teams = array_pad(array(),$i,0);
      $maxie=0;
      for($j = 0; $j < $num_students; $j++)  $maxie=max(++$teams[$j % $i],$maxie);

      $counts = array_pad(array(),$maxie+1,0);
      for($j = 0; $j < $i; $j++) $counts[$teams[$j]]++;

      $output = array();
      foreach($counts as $size => $number) if($number!=0){
        array_push($output, "$number teams of $size");
      }

      rsort($output);
      $group_list[$i] = join(', ' , $output);
    }
    return $group_list;
  }

  function __cleanXmlFile($file_path_name) {
    $xmlFile = file_get_contents($file_path_name);
    $xmlFile = @ereg_replace('(<\?=).*(\?>)','',$xmlFile);
    file_put_contents($file_path_name,$xmlFile);
  }

  function __getTeamMaker() {
    $cmdline = COMPONENTS.'TeamMaker';
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')  {
      $cmdline .= '.exe';
    } elseif(strtoupper(PHP_OS) == 'DARWIN') {
      $cmdlin .= '.osx';
    }
    return $cmdline;
  }
}
?>
