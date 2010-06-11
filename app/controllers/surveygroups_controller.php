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
  var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
  var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework','EvaluationSurveyHelper','XmlHandler','SurveyHelper');

  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'created': $this->Sanitize->paranoid($_GET['sort']);
    $this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->pageTitle = 'Survey Groups';
    parent::__construct();
  }
  function index() {
    $this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Groupsets';
    $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
    if ($personalizeData && $this->userPersonalize->inPersonalizeList('SurveyGroupSet.List.Limit.Show')) {
      $this->show = $this->userPersonalize->getPersonalizeValue('SurveyGroupSet.List.Limit.Show');
      $this->set('userPersonalize', $this->userPersonalize);
    } else {
      $this->show = '10';
      $this->update($attributeCode = 'SurveyGroupSet.List.Limit.Show',$attributeValue = $this->show);
    }
    if (substr_count($this->order,'created') > 0) {
      $this->order = 'id ASC';
    }

    $data = $this->SurveyGroupSet->findAll($conditions=null, $fields=null, $this->order, $this->show, $this->page);

    $paging['style'] = 'ajax';
    $paging['link'] = '/surveygroups/listgroupssearch/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

    $paging['count'] = $this->SurveyGroupSet->findCount($conditions=null);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $this->page;
    $paging['limit'] = $this->show;
    $paging['direction'] = $this->direction;

    $this->set('paging',$paging);
    $this->set('data',$data);
  }

  function listgroupssearch() {
    $this->layout = false;
    if (substr_count($this->order,'created') > 0) {
      $this->order = 'id ASC';
    }
    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      $this->show = '10';
      if ($personalizeData) {
        $this->userPersonalize->setPersonalizeList($personalizeData);
        $this->show = $this->userPersonalize->getPersonalizeValue('SurveyGroupSet.List.Limit.Show');
        $this->set('userPersonalize', $this->userPersonalize);
      }
    }
    $this->update($attributeCode = 'SurveyGroupSet.List.Limit.Show',$attributeValue = $this->show);

    //search function
    $conditions = null;
    if (!empty($this->params['form']['livesearch2']) && !empty($this->params['form']['select']))
    {
      $pagination->loadingId = 'loading';
      //parse the parameters
      $searchField=$this->params['form']['select'];
      $searchValue=$this->params['form']['livesearch2'];
      if ($searchField == 'set_description')
        $conditions = "set_description='".$searchValue."'";
      else
        $conditions = "num_groups=".$searchValue;
    }

    $data = $this->SurveyGroupSet->findAll($conditions, $fields=null, $this->order, $this->show, $this->page);

    $paging['style'] = 'ajax';
    $paging['link'] = '/surveygroups/listgroupssearch/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

    $paging['count'] = $this->SurveyGroupSet->findCount($conditions);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $this->page;
    $paging['limit'] = $this->show;
    $paging['direction'] = $this->direction;

    $this->set('paging',$paging);
    $this->set('data',$data);
  }

  function viewresult($params=null) {
    $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
    if ($personalizeData && $this->userPersonalize->inPersonalizeList('Survey.ResultList.Limit.Show')) {
      $this->show = $this->userPersonalize->getPersonalizeValue('Survey.ResultList.Limit.Show');
      $this->set('userPersonalize', $this->userPersonalize);
    } else {
      $this->show = '10';
      $this->update($attributeCode = 'Survey.ResultList.Limit.Show',$attributeValue = $this->show);
    }

    if (is_null($params))
      $courseId = $this->rdAuth->courseId;
    else {
      $eventId = $params;
      $event = $this->Event->findById($eventId);
      $courseId = $event['Event']['course_id'];
      $this->rdAuth->setCourseId($courseId);
    }

    $this->pageTitle = $this->sysContainer->getCourseName($courseId).' > View Survey Result';
    //get surveys for the course
    $data = $this->Survey->findAll($conditions='course_id='.$courseId);
    //get users
    $page = (($this->page-1)*$this->show);
    $userData = $this->User->getEnrolledStudents($courseId, $fields=null);
    $userData = array_splice($userData, $page, $this->show);

    //append user survey array if submitted for hyperlink
    for ($i=0; $i < count($data); $i++) {
      $data[$i]['User'] = array();
      $title = $data[$i]['Survey']['name'];
      if ($eventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$title)) {
        $data[$i]['eventId'] = $eventId['Event']['id'];
        foreach ($userData as $user) {
          $evalSub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId['Event']['id'],$user['User']['id']);
          if (isset($evalSub['EvaluationSubmission'])) {
            array_push($data[$i]['User'],array('id'=>$user['User']['id'],'time'=>$evalSub['EvaluationSubmission']['date_submitted']));
          }
        }
      }
    }

    $data['User'] = $userData;
    //print_r($data);
    $paging['style'] = 'ajax';
    $paging['link'] = '/surveygroups/viewresult/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';
    $paging['count'] = $this->UserEnrol->findCount('course_id='.$courseId);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $this->page;
    $paging['limit'] = $this->show;
    $paging['direction'] = $this->direction;

    $this->set('paging',$paging);
    $this->set('data',$data);
  }

  function viewresultsearch() {
    $this->layout = false;
    //show limit personal setting
    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      $this->show = '10';
      if ($personalizeData) {
        $this->userPersonalize->setPersonalizeList($personalizeData);
        $this->show = $this->userPersonalize->getPersonalizeValue('Survey.ResultList.Limit.Show');
        $this->set('userPersonalize', $this->userPersonalize);
      }
    }
    $this->update($attributeCode = 'Survey.ResultList.Limit.Show',$attributeValue = $this->show);

    //search function
    $conditions = null;
    if (!empty($this->params['form']['livesearch2']) && !empty($this->params['form']['select']))
    {
      $pagination->loadingId = 'loading';
      //parse the parameters
      $searchField=$this->params['form']['select'];
      $searchValue=$this->params['form']['livesearch2'];
      //$conditions = $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
    }

    $courseId = $this->rdAuth->courseId;

    if (!empty($this->params['form']['survey_select']))
      $conditions = "id=".$this->params['form']['survey_select'];
    else
      $conditions = "course_id=".$courseId;

    if (isset($this->params['form']) && !empty($this->params['form']) && $this->params['form']['select'] == 'user' && !empty($this->params['form']['livesearch2']))
      $userCondition = "User.first_name LIKE '%".mysql_real_escape_string($searchValue)."%' OR User.last_name LIKE'%".mysql_real_escape_string($searchValue)."%'";
    else
      $userCondition = null;
    //get surveys for the course
    $data = $this->Survey->findAll($conditions);
    //get users
    $page = (($this->page-1)*$this->show);

    $tmpUserData = $this->User->getEnrolledStudents($courseId, $fields=null, $userCondition);
    $userData = array_splice($tmpUserData, $page, $this->show);

    //append user survey array if submitted for hyperlink
    for ($i=0; $i < count($data); $i++) {
      $data[$i]['User'] = array();
      $title = $data[$i]['Survey']['name'];
      if ($eventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$title)) {
        $data[$i]['eventId'] = $eventId['Event']['id'];
        foreach ($userData as $user) {
          $evalSub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId['Event']['id'],$user['User']['id']);
          if (isset($evalSub['EvaluationSubmission'])) {
            array_push($data[$i]['User'],array('id'=>$user['User']['id'],'time'=>$evalSub['EvaluationSubmission']['date_submitted']));
          }
        }
      }
    }

    $data['User'] = $userData;
    //print_r($data);
    $paging['style'] = 'ajax';
    $paging['link'] = '/surveygroups/viewresultsearch/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';
    $paging['count'] = count($tmpUserData);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $this->page;
    $paging['limit'] = $this->show;
    $paging['direction'] = $this->direction;

    $this->set('paging',$paging);
    $this->set('data',$data);
  }

  function makegroups($params=null) {
    $this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Create Group Set';
    $courseId = $this->rdAuth->courseId;
    $courseName = $this->Course->find('id='.$courseId,'course');
    $courseName = $courseName['Course']['course'];
    //get surveys for the course
    $data = $this->Survey->findAll($conditions='course_id='.$courseId);

    $survey = $this->Survey->find('course_id='.$courseId);
    $survey_id = $survey['Survey']['id'];
    $surveyTitle = $survey['Survey']['name'];
    $surveyEventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$surveyTitle);
    $surveyEventId = $surveyEventId['Event']['id'];
    //display number of students and number responded
    $studentCount = count($this->User->getEnrolledStudents($courseId, 'id'));
    $studentResponseCount = count($this->EvaluationSubmission->findAll('event_id='.$surveyEventId,'id'));

    // Get all required data from each table for every question
    $tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
    $tmp = $this->Question->fillQuestion($tmp);
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
    $this->set('course_name',$courseName);
    $this->set('survey_id', $survey_id);
    $this->set('questions', $result);
    $this->set('student_count',$studentCount);
    $this->set('student_response_count',$studentResponseCount);

    $this->set('data',$data);
  }

  function makegroupssearch($params=null) {
    $this->layout = false;
    $courseId = $this->rdAuth->courseId;
    if (!empty($this->params['form']['survey_select'])) {
      $surveyId = $this->params['form']['survey_select'];
      $survey = $this->Survey->find('id='.$surveyId);
      $survey_id = $survey['Survey']['id'];
      $surveyTitle = $survey['Survey']['name'];
      $surveyEventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$surveyTitle);
    } else {
      $survey = $this->Survey->find('course_id='.$courseId);
      $survey_id = $survey['Survey']['id'];
      $surveyTitle = $survey['Survey']['name'];
      $surveyEventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$surveyTitle);
    }
    $surveyEventId = $surveyEventId['Event']['id'];
    //display number of students and number responded
    $studentCount = count($this->User->getEnrolledStudents($courseId, 'id'));
    $studentResponseCount = count($this->EvaluationSubmission->findAll('event_id='.$surveyEventId,'id'));

    // Get all required data from each table for every question
    $tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
    $tmp = $this->Question->fillQuestion($tmp);
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

    $this->set('survey_id', $survey_id);
    $this->set('questions', $result);
    $this->set('student_count',$studentCount);
    $this->set('student_response_count',$studentResponseCount);
    //$this->set('data',true);
  }

  function maketmgroups($params=null,$time=null,$make=true) {
    $numGroups = $this->params['form']['group_config'];
    $surveyId = $this->params['form']['survey_id'];
    $courseId = $this->rdAuth->courseId;
    $surveyName = $this->Survey->getSurveyTitleById($surveyId);
    $eventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$surveyName);
    $eventId = $eventId['Event']['id'];

    // Get all required data from each table for every question
    $tmp = $this->SurveyQuestion->getQuestionsID($surveyId);
    $tmp = $this->Question->fillQuestion($tmp);
    $questions = null;

    // Sort the resultant array by question number
    $count = 1;
    for( $i=0; $i<=$tmp['count']; $i++ ){
      for( $j=0; $j<$tmp['count']; $j++ ){
        if( $i == $tmp[$j]['Question']['number'] ){
          $questions[$count]['Question'] = $tmp[$j]['Question'];
          $count++;
        }
      }
    }
    $doc = '';
    //make xml for TeamMaker
    if (phpversion() < 5) {
      $doc = $this->XmlHandler->makeTMXml4($questions,$surveyId,$numGroups,$this->params);
    } else {
      $doc = $this->XmlHandler->makeTMXml5($questions,$surveyId,$numGroups,$this->params);
    }

    //saves the 'in' file
    $time = (isset($time) ? $time: (String)time());

    $unix_path = '../uploads/'.$time.'.xml';
    $windows_path = '..\\uploads\\'.$time.'.xml';
    $this->File = new File($unix_path);
    $this->File->write($doc);
    //execute TeamMaker
    if(!file_exists('..\\controllers\\components\\TeamMaker')) //not windows
    {
      //$cmdline = "./TeamMaker $out_filename $in_filename > output.txt";
      $cmdline = "../controllers/components/TeamMaker ".$unix_path." ../uploads/".$time.".txt";// > ../uploads/tm_log.txt";
      //$cmdline =  "/var/www/ipeer.apsc.ubc.ca/htdocs/prod/app/controllers/components/TeamMaker ".$unix_path."/var/www/ipeer.apsc.ubc.ca/htdocs/prod/app/uploads/".$time.".txt";
    }
    else //windows
      //$cmdline = "TeamMaker 1133823949_in.xml $in_filename > output.txt";
      $cmdline = "..\\controllers\components\\TeamMaker ".$windows_path." ..\\uploads\\".$time.".txt > ..\\uploads\\tm_log.txt";

    set_time_limit(1200);
    if ($make) exec($cmdline);

    //parse results and display
    $teams_tmp = file('../uploads/'.$time.'.txt');
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
    $xmlFile = file_get_contents('../uploads/'.$time.'.txt.scores');
    $xmlFile = ereg_replace('(<\?=).*(\?>)','',$xmlFile);
    file_put_contents('../uploads/'.$time.'.txt.scores',$xmlFile);
    //$scores = file_get_contents('../uploads/'.$time.'.txt.scores');

    $scores = $this->XmlHandler->readTMXml(count($questions),'../uploads/'.$time.'.txt.scores');
    //print_r($scores);
    $this->set('scores',$scores);
    $this->set('teams',$teams);

    $this->set('filename',$time);
    $this->set('survey_id', $surveyId);
    $this->set('event_id',$eventId);
  }

  function savegroups($params=null) {
    $this->autoRender = false;
    $time = $this->params['form']['filename'];
    $setDescription = $this->params['form']['team_set_name'];
    $surveyId = $this->params['form']['survey_id'];
    //get teams
    $teams_tmp = file('../uploads/'.$time.'.txt');

    //save group sets
    $numGroups = count($teams_tmp);
    $surveyGroupSet = array();
    $surveyGroupSet['SurveyGroupSet']['survey_id'] = $surveyId;
    $surveyGroupSet['SurveyGroupSet']['set_description'] = $setDescription;
    $surveyGroupSet['SurveyGroupSet']['num_groups'] = $numGroups;
    $surveyGroupSet['SurveyGroupSet']['date'] = $time;
    $this->SurveyGroupSet->save($surveyGroupSet);

    $surveyGroupSetId = $this->SurveyGroupSet->getIdBySurveyIdSetDescription($surveyId,$setDescription);

    for ($i=0; $i < count($teams_tmp); $i++) {
      //save groups
      $team = $teams_tmp[$i];

      $group = array();
      $group['SurveyGroup']['group_set_id'] = $surveyGroupSetId;
      $group['SurveyGroup']['group_number'] = $i+1;
      $this->SurveyGroup->save($group);
      $groupId = $this->SurveyGroup->getIdByGroupSetIdGroupNumber($surveyGroupSetId,$i+1);
      $this->SurveyGroup->id = null;

      $members = explode(' ',$team);
      for ($j=0; $j < count($members); $j++) {
        //save group members
        $member = $members[$j];
        $member_id = $this->User->getUserIdByStudentNo($member);
        $surveyGroupMember = array();
        $surveyGroupMember['SurveyGroupMember']['group_set_id'] = $surveyGroupSetId;
        $surveyGroupMember['SurveyGroupMember']['group_id'] = $groupId;
        $surveyGroupMember['SurveyGroupMember']['user_id'] = $member_id;
        $this->SurveyGroupMember->save($surveyGroupMember);
        $this->SurveyGroupMember->id = null;
      }
    }

    //set data for redirect
    $this->index();

    $this->set('message','The group set was added successfully.');
    $this->render('index');
  }

  function releasesurveygroupset($params=null) {
    $courseId = $this->rdAuth->courseId;
    $userId = $this->rdAuth->id;
    $groupSetId = $params;
    //get survey groups
    $surveyGroups = $this->SurveyGroup->findAll('group_set_id='.$groupSetId);
    //get last group number if exists; add groups
    $maxGroupNum = $this->Group->getLastGroupNumByCourseId($courseId);
    foreach ($surveyGroups as $surveyGroup) {
      $group = array();
      $this->Group->id = null;
      $groupNum = $surveyGroup['SurveyGroup']['group_number'];
      $group['Group']['group_num'] = $groupNum+$maxGroupNum;
      $group['Group']['group_name'] = 'Team #'.$surveyGroup['SurveyGroup']['group_number'];
      $group['Group']['course_id'] = $courseId;
      $group['Group']['creator_id'] = $userId;
      if ($this->Group->save($group)) {
        $groupId = $this->Group->id;
        $surveyGroupMembers = $this->SurveyGroupMember->findAll('group_set_id='.$groupSetId.' AND group_id='.$groupId);
        //add group members
        foreach ($surveyGroupMembers as $surveyGroupMember) {
          $groupMember = array();
          $groupMember['GroupsMembers']['group_id'] = $groupId;
          $groupMember['GroupsMembers']['user_id'] = $surveyGroupMember['SurveyGroupMember']['user_id'];
          $this->GroupsMembers->save($groupMember);
          $this->GorupsMembers->id = null;
        }
      } else {
        //complain about error...
        $this->set('message','Group set release failed.');
        $this->index();
        $this->render('index');
        die;
      }
    }
    //change status of survey set to released
    $this->SurveyGroupSet->setId($groupSetId);
    $surveyGroupSet = array();
    $surveyGroupSet['SurveyGroupSet']['released'] = 1;
    if ($this->SurveyGroupSet->save($surveyGroupSet)) {
      $this->set('message','The group set was released successfully.');
      $this->index();
      $this->render('index');
    } else {
      $this->set('message','Group set release failed.');
      $this->index();
      $this->render('index');
    }
  }

  function deletesurveygroupset($params=null) {
    $this->autoRender = false;
    $groupSetId = $params;
    $time = $this->SurveyGroupSet->find('id='.$groupSetId,'date');
    $time = $time['SurveyGroupSet']['date'];

    $this->SurveyHelper->deleteGroupSet($groupSetId);

    //delete teammaker crums
    unlink('../uploads/'.$time.'.txt');
    unlink('../uploads/'.$time.'.xml');
    unlink('../uploads/'.$time.'.txt.scores');

    $this->index();

    $this->set('message','The group set was deleted successfully.');
    $this->render('index');
  }

  function editgroupset($params=null,$data=null) {
    $this->pageTitle = $this->sysContainer->getCourseName($this->rdAuth->courseId).' > Edit Groupset';
    //get group set
    $groupSetId = strtok($params,';');
    $questionId = strtok(';');
    $courseId = $this->rdAuth->courseId;
    $surveyId = $this->SurveyGroupSet->getSurveyIdById($groupSetId);
    $surveyName = $this->Survey->getSurveyTitleById($surveyId);
    $eventId = $this->Event->getSurveyEventIdByCourseIdDescription($courseId,$surveyName);
    $eventId = $eventId['Event']['id'];
    $time = $this->SurveyGroupSet->find('id='.$groupSetId,'date');
    $time = $time['SurveyGroupSet']['date'];
    $scoreFilePathAndName = '../uploads/'.$time.'.txt.scores';
    $xmlFile = file_get_contents('../uploads/'.$time.'.txt.scores');
    $xmlFile = ereg_replace('(<\?=).*(\?>)','',$xmlFile);
    file_put_contents('../uploads/'.$time.'.txt.scores',$xmlFile);

    //get group
    $surveyGroups = $this->SurveyGroup->findAll('group_set_id='.$groupSetId);
    $data = array();
    //   print_r($surveyGroups);
    for ($i=0; $i < count($surveyGroups); $i++) {
      $surveyGroup = $surveyGroups[$i];
      //get member
      $groupId = $surveyGroup['SurveyGroup']['id'];
      $data[$i]['id'] = $groupId;

      $surveyGroupMembers = $this->SurveyGroupMember->findAll('group_set_id='.$groupSetId.' AND group_id='.$groupId);
      $data[$i]['members'] = array();
      for ($j=0; $j < count($surveyGroupMembers); $j++) {
        $surveyGroupMember = $surveyGroupMembers[$j];
        $memberId = $surveyGroupMember['SurveyGroupMember']['user_id'];
        //add to data
        $user = $this->User->findUserById($memberId);
        $studentId = $user['User']['student_no'];
        $name = $user['User']['last_name'].', '.$user['User']['first_name'];
        //if question selected, add responses to data
        if ($questionId != '') {
          $surveyInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId,$memberId,$questionId);

          for ($k=0; $k < count($surveyInput); $k++) {
            $inputData = $surveyInput[$k]['SurveyInput'];
            $inputQuestionId = $inputData['question_id'];
            $inputResponseId = $inputData['response_id'];
            $inputResponseText = $inputData['response_text'];
            $inputQuestionType = $this->Question->getTypeById($inputQuestionId);

            $data[$i]['members'][$j]['response']['type'] = $inputQuestionType;
            if (!isset($data[$i]['members'][$j]['response']['id']) || !is_array($data[$i]['members'][$j]['response']['id']))
              $data[$i]['members'][$j]['response']['id'] = array();
            array_push($data[$i]['members'][$j]['response']['id'], $inputResponseId);
            $data[$i]['members'][$j]['response']['response_text'] = $inputResponseText;
          }
        } else {
          //links student to survey result if submitted
          $surveyInput = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId($surveyId,$memberId);
          if (!empty($surveyInput))
            $data[$i]['members'][$j]['response'] = 'yes';
        }
        $data[$i]['members'][$j]['id'] = $memberId;
        $data[$i]['members'][$j]['name'] = $name;
        $data[$i]['members'][$j]['student_id'] = $studentId;
      }
    }
    // Get all required data from each table for every question
    $tmp = $this->SurveyQuestion->getQuestionsID($surveyId);
    $tmp = $this->Question->fillQuestion($tmp);
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
    if ($questionId != '') {
      $responses = $this->Response->getResponseByQuestionId($questionId);
      $this->set('responses',$responses);
    }

    $score = $this->XmlHandler->readTMXml(count($result),$scoreFilePathAndName);
    //print_r($score);
    //print_r($data);
    //set data
    $this->set('score',$score);
    $this->set('question_id',$questionId);
    $this->set('questions', $result);
    $this->set('survey_id', $surveyId);
    $this->set('data',$data);
    $this->set('event_id',$eventId);
    $this->set('group_set_id',$groupSetId);
    //render
  }

  function changegroupset($params=null) {
    $this->autoRender = false;
    //get data
    $formData = $this->params['form'];
    $surveyId = $formData['survey_id'];
    $groupSetId = $formData['group_set_id'];
    $time = $this->SurveyGroupSet->find('id='.$groupSetId,'date');
    $time = $time['SurveyGroupSet']['date'];
    $xmlFilePathAndName = '../uploads/'.$time.'.xml';
    $groupChangeData = array();
    $i=0;
    foreach ($formData['move'] as $value) {
      if ($value != '-1') {
        $data = explode('_',$value);
        $groupChangeData[$i]['user_id'] = $data[0];
        $groupId = $this->SurveyGroup->find('group_set_id='.$groupSetId.' AND group_number='.$data[1],'id');
        $newGroupId = $this->SurveyGroup->find('group_set_id='.$groupSetId.' AND group_number='.$data[2],'id');
        $groupId = $groupId['SurveyGroup']['id'];
        $newGroupId = $newGroupId['SurveyGroup']['id'];
        $groupChangeData[$i]['group_id'] = $groupId;
        $groupChangeData[$i]['new_group_id'] = $newGroupId;
        $i++;
      }
    }
    //find group member id and change group id
    foreach ($groupChangeData as $groupMember) {
      $groupMemberData = $this->SurveyGroupMember->find('user_id='.$groupMember['user_id'].' AND group_id='.$groupMember['group_id']);
      $groupMemberData['SurveyGroupMember']['group_id'] = $groupMember['new_group_id'];
      if (!$this->SurveyGroupMember->save($groupMemberData)) {
        $this->set('message','Group set change failed.');
        $this->index();
        $this->render('index');
        die;
      }
      $this->SurveyGroupMember->id = null;
    }

    //get team data
    $data = array();
    //group code begin
    $courseId = $this->rdAuth->courseId;
    $surveyId = $this->SurveyGroupSet->getSurveyIdById($groupSetId);

    //get group
    $surveyGroups = $this->SurveyGroup->findAll('group_set_id='.$groupSetId);
    $data = array();
    for ($i=0; $i < count($surveyGroups); $i++) {
      $surveyGroup = $surveyGroups[$i];
      //get member
      $groupId = $surveyGroup['SurveyGroup']['id'];
      $data[$i]['id'] = $groupId;

      $surveyGroupMembers = $this->SurveyGroupMember->findAll('group_set_id='.$groupSetId.' AND group_id='.$groupId);
      $data[$i]['members'] = array();
      for ($j=0; $j < count($surveyGroupMembers); $j++) {
        $surveyGroupMember = $surveyGroupMembers[$j];
        $memberId = $surveyGroupMember['SurveyGroupMember']['user_id'];
        //add to data
        $user = $this->User->findUserById($memberId);
        $studentId = $user['User']['student_no'];
        $name = $user['User']['last_name'].', '.$user['User']['first_name'];
        $data[$i]['members'][$j]['id'] = $memberId;
        $data[$i]['members'][$j]['name'] = $name;
        $data[$i]['members'][$j]['student_id'] = $studentId;
      }
    }
    //group code end
    $out = file_get_contents($xmlFilePathAndName);
    $out = ereg_replace("</team_input>",'',$out);
    $out = ereg_replace("(<fixed>).+(</fixed>)",'',$out);
    //print_r($data);
    for ($i=0; $i < count($data); $i++) {
      $team = $data[$i]['members'];
      $out .= "<fixed>";
      for ($j=0; $j < count($team); $j++) {
        //  print_r($data);die;
        $student_no = $team[$j]['student_id'];
        $out .= '<member name="'.$student_no.'"/>';
        $out .= "\n";
      }
      $out .= "</fixed>\n";
    }
    $out .= "</team_input>";
    file_put_contents($xmlFilePathAndName,$out);

    //saves the 'in' file
    $unix_path = '../uploads/'.$time.'.xml';
    $windows_path = '..\\uploads\\'.$time.'.xml';
    //execute TeamMaker
    if(file_exists('../controllers/components/TeamMaker')) //unix
    {
      //$cmdline = "./TeamMaker $out_filename $in_filename > output.txt";
      $cmdline = "../controllers/components/TeamMaker ".$unix_path." ../uploads/".$time.".txt > ../uploads/tm_log.txt";
    }
    else //windows
      //$cmdline = "TeamMaker 1133823949_in.xml $in_filename > output.txt";
      $cmdline = "..\\controllers\\components\\TeamMaker.exe ".$windows_path." ..\\uploads\\".$time.".txt > ..\\uploads\\tm_log.txt";


    set_time_limit(1200);
    exec($cmdline);

    $this->index();
    $this->set('message','Group set changed successfully.');
    $this->render('index');
  }

  function update($attributeCode='',$attributeValue='') {
    if ($attributeCode != '' && $attributeValue != '') //check for empty params
      $this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
  }
}
?>
