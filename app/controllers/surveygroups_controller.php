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
    public $components = array('Output', 'userPersonalize', 'framework', 'XmlHandler', 'AjaxList',
        'ExportBaseNew', 'ExportCsv');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $this->set('title_for_layout', __('Survey Groups', true));
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
            $data[$key]['!Custom']['isReleased'] = $entry['SurveyGroupSet']['released'] == 0 ? 'No' : 'Yes';
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
            array("Event.title",      __("Event", true),         "auto",  "action",   "View/Edit Group Set"),
            array("SurveyGroupSet.set_description",        __("Group Set Name", true),      "13em",  "string", ""),
            array("SurveyGroupSet.group_count",        __("Number of Groups", true),      "13em",  "number", ""),
            array("!Custom.isReleased",         __("Released?", true),       "9em", "string"),
            array("SurveyGroupSet.released", "", "", "hidden"),
        );

        $extraFilters = array("Event.course_id" => $course_id);

        // Set up actions
        $warning = __("Are you sure you want to delete this group set permanently?", true);
        $actions = array(
            array(__("Release", true), "", "", "", "release", "SurveyGroupSet.id"),
            array(__("View/Edit Group Set", true), "", "", "", "edit", "SurveyGroupSet.id"),
            array(__("Delete Group Set", true), $warning, "", "", "delete", "SurveyGroupSet.id"),
        );

        $recursive = 0;

        $this->AjaxList->setUp($this->SurveyGroupSet, $columns, $actions,
            "SurveyGroupSet.id", "Event.title", array(), $extraFilters, $recursive, 'postProcess');
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
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        $this->set('course', $course);
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
            return;
        }

        // checks that the dom extension and DOMDocument class exists which are needed for TeamMaker
        if (!(extension_loaded('dom') && class_exists('DOMDocument'))) {
            $this->Session->setFlash(__('DOMDocument could not be found. Please contact your
                system administrator to use TeamMaker.', true));
        }

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(__('Create Group Set', true)));
        $this->set('events', $this->Event->find('list', array('conditions' => array('course_id' => $course_id, 'event_template_type_id' => 3))));
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

        if (empty($this->data['event_select'])) {
            $this->autoRender = false;
            return '';
        }

        $event = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $this->data['event_select']),
            'contain' => 'Course',
        ));
        $questions = $this->Question->getQuestionsForMakingGroupBySurveyId($event['Event']['template_id']);

        $this->set('group_list', $this->__makeGroupList($event['Course']['student_count']));
        $this->set('event', $event);
        $this->set('questions', $questions);
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
        // for debug
        //$time = 1357520601;
        //$make = false;
        $this->set('title_for_layout', __('Survey Groups > Teams Summary', true));
        $numGroups = $this->data['group_config'];
        $event_id = $this->data['event_id'];
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $event_id),
            'contain' => false));

        //make xml for TeamMaker
        $questions = $this->Question->getQuestionsForMakingGroupBySurveyId($event['Event']['template_id']);
        $responses = $this->User->getWithSurveyResponsesByEvent($event);
        $doc = $this->XmlHandler->makeTeamMakerXML($questions, $responses, $numGroups, $this->params['form']['weight'], $event_id);

        //saves the 'in' file
        $time = (isset($time) ? $time: (String) time());

        $file_path = TMP.$time;
        $this->File = new File($file_path.'.xml');
        $this->File->write($doc);
        //execute TeamMaker
        $cmdline = $this->__getTeamMaker().' '.$file_path.'.xml '.$file_path.'.txt';// > ../tmp/tm_log.txt";
        set_time_limit(1200);
        if ($make) {
            exec($cmdline, $out, $ret);
            if ($ret) {
                $this->Session->setFlash("Unable to execute TeamMaker.");
                $this->redirect($this->referer());
                return;
            }
        }

        //parse results and display
        $teams_tmp = file($file_path.'.txt');
        $teams = array();

        //format the team recordset
        $students = Set::combine($responses, '{n}.User.id', '{n}.User');
        foreach ($teams_tmp as $team) {
            $members = explode(' ', $team);
            $teamArray = array();
            foreach ($members as $member) {
                $teamArray[] = $students[trim($member)];
            }
            $teams[] = $teamArray;
        }

        $this->__cleanXmlFile($file_path.'.txt.scores');

        $scores = $this->XmlHandler->readTMXml(count($questions), $file_path.'.txt.scores');
        $this->set('scores', $scores);
        $this->set('teams', $teams);

        $this->set('filename', $time);
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
        $eventId = $this->data['event_id'];

        if (!($event = $this->Event->getAccessibleEventById($eventId, User::get('id'), User::getCourseFilterPermission()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        //get teams
        $teams_tmp = file(TMP.$time.'.txt');

        //save group sets
        $numGroups = count($teams_tmp);
        $surveyGroupSet = array();
        $surveyGroupSet['SurveyGroupSet']['survey_id'] = $eventId;
        $surveyGroupSet['SurveyGroupSet']['set_description'] = $setDescription;
        $surveyGroupSet['SurveyGroupSet']['num_groups'] = $numGroups;
        $surveyGroupSet['SurveyGroupSet']['date'] = $time;

        for ($i = 0; $i < count($teams_tmp); $i++) {
            //save groups
            $team = $teams_tmp[$i];

            $members = explode(' ', trim($team));
            $surveyGroupSet['SurveyGroup'][$i]['SurveyGroupMember'] = $members;
        }

        // use overwritten save method to save all
        if ($this->SurveyGroupSet->save($surveyGroupSet)) {
            $this->Session->setFlash(__('The group set was added successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('The group set saving failed.', true));
        }

        $this->redirect('index/'.$event['Event']['course_id']);
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
        //get group set
        $group_set = $this->SurveyGroupSet->find('first', array(
            'conditions' => array('SurveyGroupSet.id' => $group_set_id),
            'contain' => array('SurveyGroup' => array('Member')),
        ));
        $event_id = $group_set['SurveyGroupSet']['survey_id'];
        if (!($event = $this->Event->getAccessibleEventById($event_id, User::get('id'), User::getCourseFilterPermission()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        if ($this->SurveyGroupSet->release($group_set_id)) {
            $this->Session->setFlash(__('The group set was released successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Releasing group set failed.', true));
        }
        $this->redirect('index/'.$event['Event']['course_id']);
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

        //get group set
        $group_set = $this->SurveyGroupSet->find('first', array(
            'conditions' => array('SurveyGroupSet.id' => $group_set_id),
            'contain' => array('SurveyGroup' => array('Member')),
        ));
        $event_id = $group_set['SurveyGroupSet']['survey_id'];
        if (!($event = $this->Event->getAccessibleEventById($event_id, User::get('id'), User::getCourseFilterPermission()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        if ($this->SurveyGroupSet->delete($group_set_id)) {
            $this->Session->setFlash(__('The group set was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Failed to delete group set.', true));
        }
        $this->redirect('index/'.$event['Event']['course_id']);
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
        //get group set
        $group_set = $this->SurveyGroupSet->find('first', array(
            'conditions' => array('SurveyGroupSet.id' => $group_set_id),
            'contain' => array('SurveyGroup' => array('Member')),
        ));
        $event_id = $group_set['SurveyGroupSet']['survey_id'];
        if (!($event = $this->Event->getAccessibleEventById($event_id, User::get('id'), User::getCourseFilterPermission()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        // load scores
        $time = $group_set['SurveyGroupSet']['date'];
        $scoreFilePathAndName = TMP.$time.'.txt.scores';
        $this->__cleanXmlFile($scoreFilePathAndName);

        $inputs = array();
        foreach ($group_set['SurveyGroup'] as $survey_group) {
            foreach ($survey_group['Member'] as $surveyGroupMember) {
                //if question selected, add responses to data
                if ($question_id != null) {
                    $surveyInput = $this->SurveyInput->getByEventIdUserIdQuestionId($event_id, $surveyGroupMember['id'], $question_id);

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
                    $surveyInput = $this->SurveyInput->getByEventIdUserId($event_id, $surveyGroupMember['id']);
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

        $questions = $this->Question->getQuestionsListBySurveyId($event['Event']['template_id']);
        $score = $this->XmlHandler->readTMXml(count($questions), $scoreFilePathAndName);

        //set data
        $this->set('score', $score);
        $this->set('selected_question', $question_id);
        $this->set('survey_id', $event['Event']['template_id']);
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

        //get group set
        $group_set = $this->SurveyGroupSet->find('first', array(
            'conditions' => array('SurveyGroupSet.id' => $this->data['group_set_id']),
            'contain' => array('SurveyGroup' => array('Member')),
        ));
        $event_id = $group_set['SurveyGroupSet']['survey_id'];
        if (!($event = $this->Event->getAccessibleEventById($event_id, User::get('id'), User::getCourseFilterPermission()))) {
            $this->Session->setFlash(__('Error: That event does not exist or you dont have access to it', true));
            $this->redirect('/home');
            return;
        }

        foreach ($this->data['move'] as $value) {
            if (empty($value)) {
                continue;
            }
            $data = explode('_', $value);
            if (!$this->SurveyGroupMember->updateAll(
                array('group_id' => $data[2]),
                array('user_id' => $data[0], 'group_id' => $data[1]))) {

                $this->Session->setFlash(__('Group set change failed.', true));
                $this->redirect('index/'.$event['Event']['course_id']);
            }
        }

        $this->Session->setFlash(__('Group set changed successfully.', true), 'good');
        $this->redirect('index/'.$event['Event']['course_id']);
    }

    /**
     * export
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function export($courseId)
    {
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }
        $surveys = $this->Event->find('all', array(
            'conditions' => array('course_id' => $courseId, 'event_template_type_id' => 3)
        ));
        $surveyGrp = $this->SurveyGroupSet->find('list', array(
            'conditions' => array('survey_id' => Set::extract('/Event/id', $surveys)),
            'fields' => array('set_description')
        ));
        $header = array(
            'group_no' => __('Group #', true),
            'username' => __('Username', true),
            'student_no' => __('Student No', true),
            'first_name' => __('First Name', true),
            'last_name' =>__('Last Name', true)
        );

        if (!User::hasPermission('functions/viewusername')) {
            unset($header['username']);
        }

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(__('Export Survey Groups', true)));
        $this->set('survey_group_set', $surveyGrp);
        $this->set('fields', $header);
        if ($this->data) {
            if (!$this->SurveyGroup->save($this->data, array('validate' => 'only'))) {
                // check a filename is provided
                return;
            } else if (!isset($this->data['SurveyGroup']['survey_group_set'])) {
                // check that a survey group set is selected
                $this->Session->setFlash(__('Please select a survey group set to export.', true));
                return;
            } else if (empty($this->data['SurveyGroup']['fields'])) {
                // check that a survey group set is selected
                $this->Session->setFlash(__('Please select at least one export field.', true));
                return;
            }
            $this->autoRender = false;
            $fields = array_flip($this->data['SurveyGroup']['fields']);
            $header = array_intersect_key($header, $fields);
            $group_no = false;
            if (in_array('group_no', $this->data['SurveyGroup']['fields'])) {
                unset($fields['group_no']);
                $group_no = true;
            }
            $groups = $this->SurveyGroup->find('all', array(
                'conditions' => array('group_set_id' => $this->data['SurveyGroup']['survey_group_set']),
                'contain' => array('Member' => array('fields' => array_keys($fields)))
            ));
            $members = array_merge(array(implode(',', $header)),
                $this->ExportCsv->buildSurveyGroupSet($groups, $fields, $group_no));
            $members = implode("\n", $members);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' .$this->data['SurveyGroup']['file_name']. '.csv');
            echo $members;
        }
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

