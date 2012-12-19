<?php
/**
 * SurveyGroupsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroupsController extends AppController
{
    public $uses =  array('Course', 'Survey', 'User', 'Question', 'SurveyQuestion', 'Response', 'Personalize', 'Event', 'EvaluationSubmission', 'UserEnrol', 'SurveyInput', 'SurveyGroupMember', 'SurveyGroupSet', 'SurveyGroup', 'Group', 'GroupsMembers', 'Event');
    public $name = 'SurveyGroups';
    public $helpers = array('Html', 'Ajax', 'Javascript');
    public $components = array('Output', 'userPersonalize', 'framework', 'XmlHandler', 'AjaxList');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->set('title_for_layout', __('Survey Groups', true));
        parent::__construct();
    }

    /**
     * postProcess
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function postProcess($data)
    {
        if (empty($data)) {
            return $data;
        }

        foreach ($data as $key => $entry) {
            $data[$key]['SurveyGroupSet']['released'] = $entry['SurveyGroupSet']['released'] == 0 ? 'No' : 'Yes';
        }
        return $data;
    }


    /**
     * setUpAjaxList
     *
     * @access public
     * @return void
     */
    function setUpAjaxList()
    {
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

    /**
     * index
     *
     * @param int $courseId
     *
     * @access public
     * @return void
     */
    function index($courseId)
    {
        $course = $this->Course->getCourseById($courseId);
        $this->set('course_id', $courseId);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(__('Survey Groups', true)));
    }


    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }


    /**
     * viewresult
     *
     * @param int $courseId
     * @param int $eventId
     *
     * @access public
     * @return void
     */
    function viewresult($courseId, $eventId=null)
    {
        $surveys = array(); // holds all the surveys' titles in the course

        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission(), array(
            'Event' => 'event_template_type_id = 3',
            'Enrol' => array(
                'fields' => array('id', 'username', 'full_name', 'student_no', 'email'),
                'Submission'
            )
        ));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
        }

        $eventIds = Set::extract($course['Event'], '/id');
        // if an event id is entered
        if ($eventId != null) {
            if (!in_array($eventId, $eventIds)) {
                $eventId = null;
            }
        } else {
            $eventId = array_shift($eventIds);
        }

        if (null == $eventId) {
            $this->Session->setFlash(__('Error: Invalid course ID or event ID.', true));
            $this->redirect('index/'.$courseId);
        }

        //filtering for the data to be printed in the view
        foreach ($course['Enrol'] as $key => $student) {
            $course['Enrol'][$key]['submitted'] = 'Not Submitted';
            foreach ($student['Submission'] as $submission) {
                if ($submission['event_id'] == $eventId) {
                    $course['Enrol'][$key]['submitted'] = $submission['date_submitted'];
                    break;
                }
            }
        }

        // for populating the drop down menu to switch to different surveys in the course
        $surveys = Set::combine($course['Event'], '{n}.id', '{n}.title');

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('View Survey Result', true)));
        $this->set('view', $course['Enrol']);
        $this->set('courseId', $courseId);
        $this->set('eventId', $eventId);
        $this->set('surveysList', $surveys);
    }

    /**
     * makegroups
     *
     * @param mixed $course_id
     *
     * @access public
     * @return void
     */
    function makegroups($course_id)
    {

        $course = $this->Course->getAccessibleCourseById($course_id, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(__('Create Group Set', true)));
        $this->set('surveys', $this->Survey->find('list', array('conditions' => array('course_id' => $course_id))));
    }


    /**
     * makegroupssearch
     *
     * @access public
     * @return void
     */
    function makegroupssearch()
    {
        $this->layout = false;

        if (empty($this->data['survey_select'])) {
            $this->autoRender = false;
            return '';
        }

        $survey = $this->Survey->read(null, $this->data['survey_select']);
        $this->set('group_list', $this->__makeGroupList($survey['Course']['student_count']));

        $this->set('data', $survey);
    }


    /**
     * maketmgroups
     *
     * @param bool $time
     * @param bool $make
     *
     * @access public
     * @return void
     */
    function maketmgroups($time=null, $make=true)
    {
      /*$time = 1290644146;
      $make = false;*/
        $this->set('title_for_layout', __('Survey Groups > Teams Summary', true));
        $numGroups = $this->data['group_config'];
        $survey_id = $this->data['survey_id'];
        $survey = $this->Survey->find('first', array('conditions' => array('Survey.id' => $survey_id),
            'recursive' => 2));
        foreach ($survey['Course']['Event'] as $data) {
            if ($data['event_template_type_id'] == 3 &&
               $data['template_id'] == $survey['Survey']['id']) {
                $event_id = $data['id'];
            }
        }
        //make xml for TeamMaker
        $doc = $this->XmlHandler->makeTeamMakerXML($survey, $numGroups, $this->params['form']['weight'], $event_id);

        //saves the 'in' file
        $time = (isset($time) ? $time: (String) time());

        $file_path = TMP.$time;
        $this->File = new File($file_path.'.xml');
        $this->File->write($doc);
        //execute TeamMaker
        $cmdline = $this->__getTeamMaker().' '.$file_path.'.xml '.$file_path.'.txt';// > ../tmp/tm_log.txt";
        set_time_limit(1200);
        if ($make) {
            exec($cmdline);
        }

        //parse results and display
        $teams_tmp = file($file_path.'.txt');
        $teams = array();

        //format the team recordset
        for ($i=0; $i < count($teams_tmp); $i++) {
            $team = $teams_tmp[$i];
            $members = explode(' ', $team);
            for ($j=0; $j < count($members); $j++) {
                $member = $members[$j];
                $member = trim($member);
                $member_id =
                    $this->User->field('id', array('student_no' => $member));
                $teams[$i]['member_'.$j]['student_no'] = $member;
                $teams[$i]['member_'.$j]['id'] = $member_id;
            }
        }

        // count how many MC or Checkbox questions are in survey
        $sq_count = 0;
        foreach ($survey['Question'] as $tmp) {
            if ($tmp['type'] == 'M' || $tmp['type'] == 'C') {
                $sq_count++;
            }
        }

        $this->__cleanXmlFile($file_path.'.txt.scores');

        $scores = $this->XmlHandler->readTMXml($sq_count, $file_path.'.txt.scores');
        $this->set('scores', $scores);
        $this->set('teams', $teams);

        $this->set('filename', $time);
        $this->set('survey_id', $survey_id);
        $this->set('event_id', $event_id);
    }


    /**
     * savegroups
     *
     * @access public
     * @return void
     */
    function savegroups()
    {
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

            $members = explode(' ', trim($team));
            $group_members = array();
            foreach ($members as $member) {
                //save group members
                $member_id =
                    $this->User->field('id', array('student_no' => $member));
                $surveyGroupMember = array();
                $surveyGroupMember['user_id'] = $member_id;
                $group_members[] = $surveyGroupMember;
            }
            $surveyGroupSet['SurveyGroup'][]['SurveyGroup'] = $group;
            $surveyGroupSet['SurveyGroup'][$i]['SurveyGroupMember'] = $group_members;
        }

        // use overwritten save method to save all
        if ($this->SurveyGroupSet->save($surveyGroupSet)) {
            $this->Session->setFlash(__('The group set was added successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('The group set saving failed.', true));
        }
        $this->redirect('index');
    }


    /**
     * release
     *
     * @param mixed $group_set_id
     *
     * @access public
     * @return void
     */
    function release($group_set_id)
    {
        // Check for a valid parameter
        if (!is_numeric($group_set_id)) {
            $this->Session->setFlash(__('Group Set must be a numeric id', true));
            $this->redirect('index');
        }

        if ($this->SurveyGroupSet->release($group_set_id)) {
            $this->Session->setFlash(__('The group set was released successfully.', true));
        } else {
            $this->Session->setFlash(__('Releasing group set failed.', true));
        }
        $this->redirect('index');
    }



    /**
     * delete
     *
     * @param mixed $group_set_id
     *
     * @access public
     * @return void
     */
    function delete($group_set_id)
    {
        $this->autoRender = false;

        $groupSet = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => $group_set_id)));

        $course = $this->Course->getAccessibleCourseById($groupSet['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        if ($this->SurveyGroupSet->delete($group_set_id)) {
            $this->Session->setFlash(__('The group set was deleted successfully.', true));
        } else {
            $this->Session->setFlash(__('Failed to delete group set.', true));
        }
        $this->redirect('index');
    }


    /**
     * edit
     *
     * @param mixed $group_set_id group set id
     * @param bool  $question_id  question id
     *
     * @access public
     * @return void
     */
    function edit($group_set_id, $question_id = null)
    {
        $this->set('title_for_layout', $this->Course->getCourseName($this->Session->read('ipeerSession.courseId')).__(' > Edit Groupset', true));
        //get group set
        $group_set = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => $group_set_id),
            'recursive' => 2));
        $time = $group_set['SurveyGroupSet']['date'];
        $scoreFilePathAndName = TMP.$time.'.txt.scores';
        $this->__cleanXmlFile($scoreFilePathAndName);

        $survey = $this->Survey->find('first', array('conditions' => array('Survey.id' => $group_set['Survey']['id']),
            'recursive' => 2));
        foreach ($survey['Course']['Event'] as $data) {
            if ($data['title'] == $group_set['Survey']['name']) {
                $event_id = $data['id'];
            }
        }

        $inputs = array();
        foreach ($group_set['SurveyGroup'] as $survey_group) {
            foreach ($survey_group['Member'] as $surveyGroupMember) {
                //if question selected, add responses to data
                if ($question_id != null) {
                    $surveyInput = $this->SurveyInput->getBySurveyIdUserIdQuestionId($event_id, $surveyGroupMember['id'], $question_id);

                    for ($k=0; $k < count($surveyInput); $k++) {
                        $inputData = $surveyInput[$k]['SurveyInput'];
                        $inputQuestionId = $inputData['question_id'];
                        $inputResponseId = $inputData['response_id'];
                        $inputResponseText = $inputData['response_text'];
                        $inputQuestionType = $this->Question->getTypeById($inputQuestionId);

                        $inputs[$surveyGroupMember['id']]['type'] = $inputQuestionType;
                        if (!isset($inputs[$surveyGroupMember['id']]['id']) || !is_array($inputs[$surveyGroupMember['id']]['id'])) {
                            $inputs[$surveyGroupMember['id']]['id'] = array();
                        }
                        array_push($inputs[$surveyGroupMember['id']]['id'], $inputResponseId);
                        $inputs[$surveyGroupMember['id']]['response_text'] = $inputResponseText;
                    }
                } else {
                    //links student to survey result if submitted
                    $surveyInput = $this->SurveyInput->getBySurveyIdUserId($event_id, $surveyGroupMember['id']);
                    if (!empty($surveyInput)) {
                        $inputs[$surveyGroupMember['id']] = 'yes';
                    }
                }
            }
        }

        if ($question_id != '') {
            $responses = $this->Response->getResponseByQuestionId($question_id);
            $this->set('responses', $responses);
        }

        $questions = array();
        foreach ($group_set['Survey']['Question'] as $q) {
            $questions[$q['id']] = $q['prompt'];
        }

        $score = $this->XmlHandler->readTMXml(count($group_set['Survey']['Question']), $scoreFilePathAndName);

        //set data
        $this->set('score', $score);
        $this->set('selected_question', $question_id);
        $this->set('survey_id', $group_set['Survey']['id']);
        $this->set('questions', $questions);
        $this->set('inputs', $inputs);
        $this->set('data', $group_set);
        $this->set('event_id', $event_id);
        $this->set('group_set_id', $group_set_id);
    }


    /**
     * changegroupset
     *
     * @access public
     * @return void
     */
    function changegroupset()
    {
        $this->autoRender = false;

        foreach ($this->data['move'] as $value) {
            if (empty($value)) {
                continue;
            }
            $data = explode('_', $value);
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
    $out = @ereg_replace("</team_input>", '', $out);
    $out = @ereg_replace("(<fixed>).+(</fixed>)", '', $out);
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
    file_put_contents($xmlFilePathAndName, $out);

    //execute TeamMaker saves the 'in' file
    if (file_exists(COMPONENTS.'TeamMaker')) {
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

    /**
     * __makeGroupList
     *
     * @param mixed $num_students
     *
     * @access protected
     * @return void
     */
    function __makeGroupList($num_students)
    {
        $group_list = array();
        for ($i = 2; $i <= $num_students / 2; $i++) {

            $teams = array_pad(array(), $i, 0);
            $maxie=0;
            for ($j = 0; $j < $num_students; $j++) {
                $maxie=max(++$teams[$j % $i], $maxie);
            }

            $counts = array_pad(array(), $maxie+1, 0);
            for ($j = 0; $j < $i; $j++) {
                $counts[$teams[$j]]++;
            }

            $output = array();
            foreach ($counts as $size => $number) {
                if ($number!=0) {
                    array_push($output, "$number teams of $size");
                }
            }

            rsort($output);
            $group_list[$i] = join(', ', $output);
        }
        return $group_list;
    }


    /**
     * __cleanXmlFile
     *
     * @param mixed $file_path_name
     *
     * @access protected
     * @return void
     */
    function __cleanXmlFile($file_path_name)
    {
        $xmlFile = file_get_contents($file_path_name);
        $xmlFile = preg_replace('/(<\?=.+?)+(\?>)/i', '', $xmlFile); // ereg_replace() is deprecated
        file_put_contents($file_path_name, $xmlFile);
    }


    /**
     * __getTeamMaker
     *
     * @access protected
     * @return void
     */
    function __getTeamMaker()
    {
        $cmdline = COMPONENTS.'TeamMaker';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmdline .= '.exe';
        } elseif(strtoupper(PHP_OS) == 'DARWIN') {
            $cmdline .= '.osx';
        }
        return $cmdline;
    }
}

