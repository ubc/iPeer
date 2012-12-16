<?php
/**
 * SurveysController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveysController extends AppController
{
    public $uses =  array('SurveyQuestion', 'Course', 'Survey', 'User', 'Question', 'Response', 'Personalize', 'Event', 'EvaluationSubmission', 'UserEnrol', 'SurveyInput', 'SurveyGroupMember', 'SurveyGroupSet', 'SurveyGroup');
    public $name = 'Surveys';
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $components = array('AjaxList', 'Output', 'framework');


    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
        $this->set('title_for_layout', __('Surveys', true));
        parent::__construct();
    }


    /**
     * __postProcess
     *
     * @param mixed $data
     *
     * @access protected
     * @return void
     */
    function __postProcess($data)
    {
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                // is it in use?
                //        $inUse = $this->Event->checkEvaluationToolInUse('3', $entry['Survey']['id']) ;
                $inUse = $this->Survey->getEventCount($entry['Survey']['id']);

                // Put in the custom inUse column
                $data[$key]['!Custom']['inUse'] = $inUse ? __("Yes", true) : __("No", true);

                // Decide whether the course is release or not ->
                // (from the events controller postProcess function)
                $releaseDate = strtotime($entry["Survey"]["release_date_begin"]);
                $endDate = strtotime($entry["Survey"]["release_date_end"]);
                $timeNow = strtotime($entry[0]["now()"]);

                if (!$releaseDate) {
                    $releaseDate = 0;
                }
                if (!$endDate) {
                    $endDate = 0;
                }

                $isReleased = "";
                if ($timeNow < $releaseDate) {
                    $isReleased = __("Not Yet Open", true);
                } else if ($timeNow > $endDate) {
                    $isReleased = __("Already Closed", true);
                } else {
                    $isReleased = __("Open Now", true);
                }

                // Put in the custom isReleased string
                $data[$key]['!Custom']['isReleased'] = $isReleased;
            }
        }
        // Return the processed data back
        return $data;
    }


    /**
     * setUpAjaxList
     *
     * @param bool $conditions
     *
     * @access public
     * @return void
     */
    function setUpAjaxList($conditions = array())
    {
        $myID = $this->Auth->user('id');

        // Get the course data
        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');

        // Set up Columns
        $columns = array(
            array("Survey.id",          __("ID", true),         "4em",   "hidden"),
            array("Course.id",          "",             "",     "hidden"),
            array("Course.course",      __("Course", true),      "15em",  "action", "View Course"),
            array("Survey.name",        __("Name", true),        "auto",  "action", "View Event"),
            array("!Custom.inUse",      __("In Use", true),      "4em",   "number"),
            array("Survey.due_date",    __("Due Date", true),   "10em",  "date"),
            // The release window columns
            array("now()",   "", "", "hidden"),
            array("Survey.release_date_begin", "", "", "hidden"),
            array("Survey.release_date_end",   "", "", "hidden"),
            array("!Custom.isReleased", __("Released ?", true),   "  4em",   "string"),
            array("Survey.creator_id",   "", "", "hidden"),
            array("Survey.creator",  __("Created By", true),    "8em", "action", "View Creator"),
            array("Survey.created",     __("Creation Date", true), "10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array($myID => "My Surveys");

        // Join in the course name
        $joinTableCourse =
            array("id"        => "Survey.course_id",
                "localKey"  => "course_id",
                "description" => "Course:",
                "list" => $courses,
                "default"   => $this->Session->read('ipeerSession.courseId'),
                "joinTable" => "courses",
                "joinModel" => "Course");

        $joinTableCreator =
            array("id" => "Survey.creator_id",
                "localKey" => "creator_id",
                "description" => __("Surveys to show:", true),
                "list" => $userList,
                "joinTable"=>"users",
                "joinModel" => "Creator");

        // Add the join table into the array
        $joinTables = array($joinTableCreator, $joinTableCourse);

        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        } else {
            // For instructors: only list their own course events (surveys)
            $extraFilters = $conditions;
            $extraFilters = " ( ";
            $courseIds = array_keys($courses);
            foreach ($courseIds as $id) {
                $extraFilters .= "course_id=$id or ";
            }
            $extraFilters .= "1=0 ) "; // just terminates the or condition chain with "false"
        }

        // Set up actions
        $warning = __("Are you sure you want to delete this evaluation permanently?", true);
        $actions = array(
            array(__("View Event", true), "", "", "", "view", "Survey.id"),
            array(__("Edit Event", true), "", "", "", "edit", "Survey.id"),
            array(__("Edit Questions", true), "", "", "", "questionsSummary", "Survey.id"),
            array(__("Copy Survey", true), "", "", "", "copy", "Survey.id"),
            array(__("Delete Survey", true), $warning, "", "", "delete", "Survey.id"),
            array(__("View Course", true), "",    "", "courses", "home", "Course.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Survey.creator_id"));

        // No recursion in results (at all!)
        $recursive = 1;

        // Set up the list itself
        $this->AjaxList->setUp($this->Survey, $columns, $actions,
            "Course.course", "Survey.name", $joinTables, $extraFilters, $recursive, "__postProcess");
    }


    /**
     * index
     *
     * @param bool $course_id
     *
     * @access public
     * @return void
     */
    function index($course_id = null)
    {
        // Set up the basic static ajax list variables
        $conditions = array();
        if (null != $course_id) {
            $conditions = array('Course.id' => $course_id);
            $course = $this->Course->getCourseById($course_id);
            $this->breadcrumb->push(array('course' => $course['Course']));
        }
        $this->setUpAjaxList($conditions);
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
        $this->set('course_id', $course_id);
        $this->set('breadcrumb', $this->breadcrumb->push(__('Surveys', true)));
    }


    /**
     * ajaxList
     *
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
     * view
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function view($id)
    {
        $eval = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => false
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getAccessibleCourseById($eval['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        $data = $this->Survey->read(null, $id);
        $this->set('data', $data);
    }


    /**
     * add
     *
     *
     * @access public
     * @return void
     */
    function add()
    {
        if (!empty($this->data)) {
            if ($result = $this->Survey->save($this->data)) {
                $this->data = $result;
                $this->data['Survey']['id'] = $this->Survey->id;

                // check to see if a template has been selected
                if (!empty($this->data['Survey']['template_id'] )) {
                    $this->SurveyQuestion->copyQuestions($this->data['Survey']['template_id'], $this->Survey->id);
                }

                //$this->questionsSummary($this->Survey->id);

                //$this->params['data']['Survey']['released'] = 1;
                $eventArray = array();

                //add survey to events
                $eventArray['Event']['title'] = $this->data['Survey']['name'];
                $eventArray['Event']['course_id'] = $this->data['Survey']['course_id'];
                $eventArray['Event']['event_template_type_id'] = 3;
                $eventArray['Event']['template_id'] = $this->data['Survey']['id'];
                $eventArray['Event']['self_eval'] = 1;
                $eventArray['Event']['com_req'] = 1;
                $eventArray['Event']['due_date'] = $this->data['Survey']['due_date'];
                $eventArray['Event']['release_date_begin'] = $this->data['Survey']['release_date_begin'];
                $eventArray['Event']['release_date_end'] = $this->data['Survey']['release_date_end'];

                //Save Data
                $this->Event->save($eventArray);
                $this->Session->setFlash(__('Survey is saved!', true), 'good');
                $this->redirect('index');
            } else {
                //$this->set('errmsg', $this->Survey->errorMessage);
                $this->Session->setFlash(__('Error on saving survey.', true));
            }
        }

        // Get the course data
        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $templates = $this->Survey->find('list', array('conditions' => array('course_id' => array_keys($courses))));
        $this->set('templates', $templates);
        $this->set('courses', $courses);
        $this->render('edit');
    }


    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function edit($id)
    {
        // retrieving the requested survey
        $eval = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        // for storing submissions - for checking if there are any submissions
        $submissions = array();

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getAccessibleCourseById($eval['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $submissions[] = $event['EvaluationSubmission'];
            }
        }

        // check to see if submissions had been made - if yes - survey can't be edited
        if (!empty($submissions)) {
            $this->Session->setFlash(__('Submissions had been made. '.$eval['Survey']['name'].' cannot be edited. Please make a copy.', true));
            $this->redirect('index');
        }

        $data = $this->Survey->find('first', array('conditions' => array('id' => $id),
            'contain' => array('Event')));
        if (!empty($this->data)) {
            //alter dates for the event
            //TODO: separate date from survey
            $data['Survey'] = $this->data['Survey'];
            $data['Event'][0]['title'] = $this->data['Survey']['name'];
            $data['Event'][0]['course_id'] = $this->data['Survey']['course_id'];
            $data['Event'][0]['due_date'] = $this->data['Survey']['due_date'];
            $data['Event'][0]['release_date_begin'] = $this->data['Survey']['release_date_begin'];
            $data['Event'][0]['release_date_end'] = $this->data['Survey']['release_date_end'];

            if ($this->Survey->save($data)) {
                $this->Session->setFlash(__('The Survey was edited successfully.', true), 'good');
                $this->redirect('index');
            } else {
                $this->Session->setFlash($this->Survey->errorMessage);
            }
        } else {
            $this->data = $data;
        }

        // Get the course data
        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $this->set('courses', $courses);
    }


    /**
     * copy
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function copy($id)
    {
        $eval = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => false,
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getAccessibleCourseById($eval['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');

        $this->data = $this->Survey->read(null, $id);
        unset($this->data['Survey']['id']);
        $this->data['Survey']['name'] = 'Copy of '.$this->data['Survey']['name'];
        //converting nl2br back so it looks better
        $this->Output->br2nl($this->data);

        $this->set('template_id', $id);
        $this->set('courses', $courses);
        $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(__('Copy', true)));
        $this->render('edit');
    }


    /**
     * delete
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function delete($id)
    {
        // retrieving the requested survey
        $eval = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getAccessibleCourseById($eval['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $submissions[] = $event['EvaluationSubmission'];
            }
        }

        // check to see if submissions had been made - if yes - survey can't be edited
        if (!empty($submissions)) {
            $this->Session->setFlash(__('Submissions had been made. '.$eval['Survey']['name'].' cannot be deleted', true));
            $this->redirect('index');
        }

        if ($this->Survey->delete($id)) {
            $this->Session->setFlash(__('The survey was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Survey delete failed.', true));
        }
        $this->redirect('index');
    }

  /**
   * releaseSurvey
   * called to change survey status to release
   *
   * @param bool $id
   *
   * @access public
   * @return void
   */
  function releaseSurvey($id=null)
  {//deprecated, this function is not used
      $eventArray = array();

      $this->Survey->id = $id;
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


      $this->set('data', $this->Survey->find('all', null, null, 'id'));
      $this->set('message', __('The survey was released.', true));
      $this->index();
      $this->render('index');
  }


  /************ Question Functions ***********/

  /**
   * questionsSummary
   * Gets all the questions associated with selected survey and displays them
   *
   * @param mixed $survey_id
   *
   * @access public
   * @return void
   */
    function questionsSummary($survey_id)
    {
        // retrieving the requested survey
        $eval = $this->Survey->find('first',
            array(
                'conditions' => array('id' => $survey_id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        // for storing submissions - for checking if there are any submissions
        $submissions = array();

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($survey_id) || empty($eval)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        $course = $this->Course->getAccessibleCourseById($eval['Survey']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
        }

        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $submissions[] = $event['EvaluationSubmission'];
            }
        }

        // check to see if submissions had been made - if yes - survey can't be edited
        if (!empty($submissions)) {
            $this->Session->setFlash(__('Submissions had been made. Questions for "'.$eval['Survey']['name'].'" cannot be edited.', true));
            $this->redirect('index');
        }

        // Get all required data from each table for every question
        $questions = $this->Question->find('all', array(
            'conditions' => array('Survey.id' => $survey_id),
            //'contain' => array('Question', 'Response'),
            'order' => 'SurveyQuestion.number',
            'recursive' => 1));

        $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(__('Edit Question', true)));
        $this->set('survey_id', $survey_id);
        $this->set('questions', $questions);
        $this->set('is_editable', true);
        $this->render('questionssummary');
    }


  /**
   * moveQuestion
   *
   * @param mixed $survey_id   survey id
   * @param mixed $question_id question id
   * @param mixed $position    position
   *
   * @access public
   * @return void
   */
  function moveQuestion($survey_id, $question_id, $position)
  {
      // Move request for a question
      if ($survey_id != null && $position != null && $question_id != null) {
          //$this->SurveyQuestion->moveQuestion($survey_id, $question_id, $move);
          $this->SurveyQuestion->moveQuestion($survey_id, $question_id, $position);
      }
      $this->redirect('questionsSummary/'.$survey_id);
  }


  /**
   * removeQuestion
   * Used when remove is clicked on questionssummary page
   *
   * @param mixed $survey_id   survey id
   * @param mixed $question_id question id
   *
   * @access public
   * @return void
   */
  function removeQuestion($survey_id, $question_id)
  {
      $this->autoRender = false;

      // move question to bottom of survey list so deletion can be done
      // without affecting the number order
      $this->SurveyQuestion->moveQuestion($survey_id, $question_id, 'BOTTOM');

      // remove the question from the survey association as well as all other
      // references to the question in the responses and questions tables
      $this->Survey->habtmDelete('Question', $survey_id, $question_id);
      //$this->Question->editCleanUp($question_id);

      $this->Session->setFlash(__('The question was removed successfully.', true), 'good');

      $this->redirect('questionsSummary/'.$survey_id);
  }


  /**
   * addQuestion
   *
   * @param mixed $survey_id
   *
   * @access public
   * @return void
   */
  function addQuestion($survey_id)
  {
      //check to see if user has clicked load question
      if (!empty($this->params['form']['loadq'])) {
          // load values from selected question into temp array
          $this->data = $this->Question->find('first', array('conditions' => array('id' => $this->data['Question']['template_id'])));
          $this->set('responses', $this->data['Response']);
      } elseif (!empty($this->params['data']['Question'])) {
          //$maxQuestionNum = $this->SurveyQuestion->getMaxSurveyQuestionNumber($this->data['Survey']['id']);
          //$this->data['number'] = $maxQuestionNum+1;
          if ($this->Question->saveAll($this->data)) {
              $this->Session->setFlash(__('The question was added successfully.', true), 'good');
              // Need to run reorderQuestions once in order to correctly set the question position numbers
              $surveyQuestionId = $this->SurveyQuestion->find('first', array('conditions' => array('survey_id' => $survey_id), 'fields' => array('MIN(number) as minQuestionId')));
              $this->SurveyQuestion->reorderQuestions($survey_id, $surveyQuestionId['0']['minQuestionId'], 'TOP');
              $this->redirect('questionsSummary/'.$survey_id);
              //$this->questionsSummary($this->params['form']['survey_id'], null, null);
          } else {
              $this->set('responses', $this->data['Response']);
              $this->render('editQuestion');
          }
      } else {
          $this->set('responses', array());
      }

      $this->autorender = false;
      $this->set('templates', $this->Question->find('list', array('conditions' => array('master' => 'yes'))));
      $this->set('survey_id', $survey_id);
      $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(Inflector::humanize(Inflector::underscore($this->action))));
  }


  /**
   * editQuestion
   *
   * @param mixed $question_id question id
   * @param mixed $survey_id   survey id
   *
   * @access public
   * @return void
   */
  function editQuestion( $question_id, $survey_id )
  {
      if (!empty($this->data)) {
          if ($this->Question->saveAll($this->data)) {
              $this->Session->setFlash(__('The question was updated successfully.', true), 'good');
              $this->redirect('questionsSummary/'.$survey_id);
          } else {
              $this->Session->setFlash(__('Error in saving question.', true));
          }
      } else {
          $this->data = $this->Question->find('first', array('conditions' => array('id' => $question_id)));
      }

      $this->set('question_id', $question_id);
      $this->set('survey_id', $survey_id);
      $this->set('responses', $this->data['Response']);
      $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(__('Edit Question', true)));
      $this->render('addQuestion');
  }
}
