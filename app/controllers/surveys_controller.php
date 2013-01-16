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
            array("Survey.name",        __("Name", true),        "auto",  "action", "Edit Survey"),
            array("Survey.question_count", __("Questions", true),        "6em",  "action", "View Questions"),
            array("!Custom.inUse",      __("In Use", true),      "4em",   "number"),
            array("Survey.creator_id",   "", "", "hidden"),
            array("Survey.creator",  __("Created By", true),    "8em", "action", "View Creator"),
            array("Survey.created",     __("Creation Date", true), "10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array($myID => "My Surveys");

        $joinTableCreator =
            array("id" => "Survey.creator_id",
                "localKey" => "creator_id",
                "description" => __("Surveys to show:", true),
                "list" => $userList,
                "joinTable"=>"users",
                "joinModel" => "Creator");

        // Add the join table into the array
        $joinTables = array($joinTableCreator);

        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        } else {
            // For instructors: only list their own course events (surveys)
            $extraFilters = $conditions;
        }

        // Set up actions
        $warning = __("Are you sure you want to delete this survey permanently?", true);
        $actions = array(
            array(__("View Survey", true), "", "", "", "view", "Survey.id"),
            array(__("Edit Survey", true), "", "", "", "edit", "Survey.id"),
            array(__("View Questions", true), "", "", "", "questionsSummary", "Survey.id"),
            array(__("Copy Survey", true), "", "", "", "copy", "Survey.id"),
            array(__("Delete Survey", true), $warning, "", "", "delete", "Survey.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Survey.creator_id"));

        // No recursion in results (at all!)
        $recursive = 1;

        // Set up the list itself
        $this->AjaxList->setUp($this->Survey, $columns, $actions,
            "Survey.name", "Survey.name", $joinTables, $extraFilters, $recursive, "__postProcess");
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
        // retrieving the requested survey
        $survey = $this->Survey->find('first',
            array(
                'conditions' => array('id' => $id),
                'contain' => false
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($survey)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // Get all required data from each table for every question
        $questions = $this->Question->find('all', array(
            'conditions' => array('Survey.id' => $id),
            'order' => 'SurveyQuestion.number',
            'recursive' => 1)
        );

        // Convert the response array into a flat options array for
        // the form input helper
        foreach ($questions as &$q) {
            if (isset($q['Response'])) {
                $options = array();
                foreach ($q['Response'] as $resp) {
                    $options[$resp['id']] = $resp['response'];
                }
                $q['ResponseOptions'] = $options;
            }
        }

        $this->set('breadcrumb', $this->breadcrumb->push('surveys')->
            push(__('View', true))->push($survey['Survey']['name']));
        $this->set('survey', $survey);
        $this->set('questions', $questions);
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

                $this->Session->setFlash(__('Survey is saved!', true), 'good');
                $this->redirect('index');
                return;
            } else {
                $this->Session->setFlash(__('Error on saving survey.', true));
            }
        }

        // Get the course data
        $templates = $this->Survey->find('list');
        $this->set('templates', $templates);
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
        $survey = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        // for storing submissions - for checking if there are any submissions
        $submissions = array();

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($survey)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        // check to see if submissions had been made - if yes - survey can't be edited
        if (isset($survey['Event'])) {
            foreach ($survey['Event'] as $event) {
                if (!empty($event['EvaluationSubmission'])) {
                    $submissions[] = $event['EvaluationSubmission'];
                }
            }
            if (!empty($submissions)) {
                $this->Session->setFlash(sprintf(__('Submissions had been made. %s cannot be edited. Please make a copy.', true), $survey['Survey']['name']));
                $this->redirect('index');
            }
        }

        if (!empty($this->data)) {
            if ($this->Survey->save($this->data)) {
                $this->Session->setFlash(__('The Survey was edited successfully.', true), 'good');
                $this->redirect('index');
                return;
            } else {
                $this->Session->setFlash($this->Survey->errorMessage);
            }
        } else {
            $this->data = $survey;
        }
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
        $survey = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => false,
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($survey)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        $this->data = $survey;
        unset($this->data['Survey']['id']);
        $this->data['Survey']['name'] = 'Copy of '.$this->data['Survey']['name'];

        $this->set('action', 'copy');
        $this->set('template_id', $id);
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
        $survey = $this->Survey->find(
            'first',
            array(
                'conditions' => array('id' => $id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($id) || empty($survey)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        // check to see if submissions had been made - if yes - survey can't be edited
        if (isset($survey['Event'])) {
            $submissions = array();
            foreach ($survey['Event'] as $event) {
                if (!empty($event['EvaluationSubmission'])) {
                    $submissions[] = $event['EvaluationSubmission'];
                }
            }
            if (!empty($submissions)) {
                $this->Session->setFlash(sprintf(__('Submissions had been made. %s cannot be edited. Please make a copy.', true), $survey['Survey']['name']));
                $this->redirect('index');
            }
        }

        if ($this->Survey->delete($id)) {
            $this->Session->setFlash(__('The survey was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Survey delete failed.', true));
        }
        $this->redirect('index');
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
        $survey = $this->Survey->find('first',
            array(
                'conditions' => array('id' => $survey_id),
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );

        // check to see if $id is valid - numeric & is a survey
        if (!is_numeric($survey_id) || empty($survey)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // for storing submissions - for checking if there are any submissions
        $hasSubmission = false;
        // check to see if submissions had been made - if yes - survey can't be edited
        if (isset($survey['Event'])) {
            foreach ($survey['Event'] as $event) {
                if (!empty($event['EvaluationSubmission'])) {
                    $hasSubmission = true;
                }
            }
        }

        // Get all required data from each table for every question
        $questions = $this->Question->find('all', array(
            'conditions' => array('Survey.id' => $survey_id),
            //'contain' => array('Question', 'Response'),
            'order' => 'SurveyQuestion.number',
            'recursive' => 1));

        $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(__('View Questions', true)));
        $this->set('survey_id', $survey_id);
        $this->set('questions', $questions);
        $this->set('is_editable', !$hasSubmission);
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
      // move question to bottom of survey list so deletion can be done
      // without affecting the number order
      $this->SurveyQuestion->moveQuestion($survey_id, $question_id, 'BOTTOM');

      // remove the question from the survey association as well as all other
      // references to the question in the responses and questions tables
      $this->Survey->habtmDelete('Question', $survey_id, $question_id);
      // for some reason, habtmDelete does not remove the question's entry
      // in the Question model, so doing it here as a quick fix
      $this->Question->delete($question_id);

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
      if (isset($this->params['form']['cancel'])) {
          $this->redirect('questionsSummary/'.$survey_id);
          return;
      }
      if (isset($this->params['form']['load'])) {
          // load values from selected question into temp array
          $this->data = $this->Question->find(
              'first', 
              array('conditions' => 
              array('id' => $this->data['Question']['template_id'])));
      } 
      else if (!empty($this->data)) {
          $this->data['Survey']['id'] = $survey_id;
          // Strip ID from responses or the original master question will
          // lose its responses. We want a copy, not the original.
          if (isset($this->data['Response'])) {
              foreach($this->data['Response'] as &$response) {
                  unset($response['id']);
              }
          }

          if ($this->Question->saveAll($this->data)) {
              $this->Session->setFlash(__('The question was added successfully.', true), 'good');
              // Need to run reorderQuestions once in order to correctly set the question position numbers
              $surveyQuestionId = $this->SurveyQuestion->find('first', array('conditions' => array('survey_id' => $survey_id), 'fields' => array('MIN(number) as minQuestionId')));
              $this->SurveyQuestion->reorderQuestions($survey_id, $surveyQuestionId['0']['minQuestionId'], 'TOP');
              $this->redirect('questionsSummary/'.$survey_id);
              //$this->questionsSummary($this->params['form']['survey_id'], null, null);
          } else {
              $this->Session->setFlash(_('Failed to save question.'));
          }
      }
      $this->set('templates', $this->Question->find('list', array('conditions' => array('master' => 'yes'))));
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
      if (isset($this->params['form']['cancel'])) {
          $this->redirect('questionsSummary/'.$survey_id);
          return;
      }
      if (isset($this->params['form']['load'])) {
          // load values from selected question into temp array
          $this->data = $this->Question->find(
              'first', 
              array('conditions' => 
              array('id' => $this->data['Question']['template_id'])));
      } 
      else if (!empty($this->data)) {
          $this->data['Question']['id'] = $question_id;
          // filter - remove the removed answers from the database
          $responses = $this->Response->find('list', array('conditions' => array('question_id' => $question_id)));
          $newResponses = array();
          foreach ($this->data['Response'] as $new) {
            if (isset($new['id'])) {
                $newResponses[] = $new['id'];
            }
          }
          foreach ($responses as $resp) {
            if (!in_array($resp, $newResponses)) {
                $this->Response->delete($resp);
            }
          }
          if ($this->Question->saveAll($this->data)) {
              $this->Session->setFlash(__('The question was updated successfully.', true), 'good');
              $this->redirect('questionsSummary/'.$survey_id);
          } else {
              $this->Session->setFlash(__('Error in saving question.', true));
          }
      } else {
          $this->data = $this->Question->find('first', array('conditions' => array('id' => $question_id)));
      }

      $this->set('templates', $this->Question->find('list', array('conditions' => array('master' => 'yes'))));
      $this->set('breadcrumb', $this->breadcrumb->push('surveys')->push(__('Edit Question', true)));
      $this->render('addQuestion');
  }
}
