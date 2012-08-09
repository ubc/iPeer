<?php
/**
 * MixevalsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalsController extends AppController
{
    public $uses =  array('Event', 'Mixeval','MixevalsQuestion', 'MixevalsQuestionDesc', 'Personalize', 'UserCourse');
    public $name = 'Mixevals';
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $helpers = array('Html','Ajax','Javascript','Time');
    public $Sanitize;
    public $components = array('AjaxList','Auth','Output','sysContainer', 'userPersonalize', 'framework');


    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);
        $this->set('title_for_layout', __('Mixed Evaluations', true));
        $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
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
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                // is it in use?
                $inUse = (0 != $entry['Mixeval']['event_count']);

                // Put in the custom column
                $data[$key]['!Custom']['inUse'] = $inUse ? __("Yes", true) : __("No", true);
            }
        }
        // Return the processed data back
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
        $myID = $this->Auth->user('id');

        // Set up Columns
        $columns = array(
            array("Mixeval.id",            "",              "",  "hidden"),
            array("Mixeval.name",          __("Name", true),         "auto", "action", "View Evaluation"),
            array("!Custom.inUse",         __("In Use", true),       "4em",  "number"),
            array("Mixeval.availability",  __("Availability", true), "6em",  "string"),
            array("Mixeval.scale_max",     __("LOM", true),          "3em",  "number"),
            array("Mixeval.total_question",  __("Questions", true),    "4em", "number"),
            array("Mixeval.total_marks",  __("Total Marks", true),    "4em", "number"),
            array("Mixeval.event_count",   "",       "",        "hidden"),
            array("Mixeval.creator_id",           "",               "",    "hidden"),
            array("Mixeval.creator",     __("Creator", true),        "8em", "action", "View Creator"),
            array("Mixeval.created",      __("Creation Date", true),  "10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array($this->Auth->user('id') => "My Evaluations");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator_id",
                "localKey"   => "creator_id",
                "description" => __("Evaluations to show:", true),
                "default" => $myID,
                "list" => $userList,
                "joinTable"  => "users",
                "joinModel"  => "Creator");
        // put all the joins together
        $joinTables = array($jointTableCreator);

        $myID = $this->Auth->user('id');
        $creators = array();
        // grab course ids of the courses admin/super admin has access to
        $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
        // grab all instructors that have access to the courses above
        $instructors = $this->UserCourse->find(
            'all', 
            array(
                'conditions' => array('UserCourse.course_id' => $courseIds)
        ));
        $extraFilters = "(";
        // only admins/super admins will go through this loop
        foreach ($instructors as $instructor) {
            $id = $instructor['UserCourse']['user_id'];
            $creators[] = $id;
            $extraFilters .= "creator_id = $id or ";
        }
        // allow instructors/admins/super admins to see their own mixeval templates
        $extraFilters .= "creator_id = $myID or ";
        // can see all public mixeval templates
        $extraFilters .= "availability = 'public')";

        // Instructors can only edit their own evaluations
        $restrictions = "";
        $basicRestrictions = array(
                $myID => true,
                "!default" => false);
        if (User::hasRole('superadmin') || User::hasRole('admin')) {
            foreach ($creators as $creator) {
                $basicRestrictions = $basicRestrictions + array($creator => true);
            }
        }
        
        $restrictions['Mixeval.creator_id'] = $basicRestrictions;

        // Set up actions
        $warning = __("Are you sure you want to delete this evaluation permanently?", true);
        $actions = array(
            array(__("View Evaluation", true), "", "", "", "view", "Mixeval.id"),
            array(__("Edit Evaluation", true), "", $restrictions, "", "edit", "Mixeval.id"),
            array(__("Copy Evaluation", true), "", "", "", "copy", "Mixeval.id"),
            array(__("Delete Evaluation", true), $warning, $restrictions, "", "delete", "Mixeval.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Mixeval.creator_id"));

        // No recursion in results
        $recursive = 0;

        // Set up the list itself
        $this->AjaxList->setUp($this->Mixeval, $columns, $actions,
            "Mixeval.name", "Mixeval.name", $joinTables, $extraFilters, $recursive, "postProcess");
    }

    /**
     * index
     *
     * @access public
     * @return void
     */
    function index()
    {
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('Error: You do not have permission to access mixed evaluations', true));
            $this->redirect('/home');
        }

        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
        $Auth = $this->Auth;

        $this->set('Auth', $Auth);
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
     * view
     *
     * @param string $id     id
     * @param string $layout layout
     *
     * @access public
     * @return void
     */
    function view($id, $layout='')
    {
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('Error: You do not have permission to view mixed evaluations', true));
            $this->redirect('/home');
        }
        
        $eval = $this->Mixeval->find(
            'first', 
            array(
                'conditions' => array('id' => $id), 
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        
        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }
        
        // check whether the user has access to the evaluation if the eval is not public
        if ($eval['Mixeval']['availability'] != 'public') {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins & super admin
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->find(
                    'all',
                    array(
                        'conditions' => array('UserCourse.course_id' => $courseIds)
                ));
                $instructorIds = array();
                foreach ($instructors as $instructor) {
                    $instructorIds[] = $instructor['UserCourse']['user_id'];
                }
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }
            
            // creator's id must be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to view this evaluation', true));
                $this->redirect('index');
            }
        }
        
        if ($layout != '') {
            $this->layout = $layout;
        }

        if (empty($this->data)) {
            $this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                'contain' => array('Question.Description',
            )));

        }

        $this->set('data', $this->data);
        $Auth = $this->Auth;

        $this->set('Auth', $Auth);
        $this->Mixeval->id = $id;
        $this->params['data'] = $this->Mixeval->read();

        $this->Output->filter($this->params['data']);
        $prepare_data = $this->Mixeval->compileViewData($this->params['data']);
        $this->set('prepare', $prepare_data);
    }

    /**
     * add
     *
     * @param string $layout
     *
     * @access public
     * @return void
     */
    function add($layout='')
    {
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('Error: You do not have permission to add mixed evaluations', true));
            $this->redirect('/home');
        }
        
        if ($layout != '') {
            $this->layout = $layout;
            $this->set('layout', $layout);
        }

        if (empty($this->data)) {
            $this->data['Mixeval']= array();
            $this->data['Question'] = array();
            $this->set('data', $this->data);

        } else {
            $data = $this->data;

            if ($this->Mixeval->save($data)) {
                $this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $this->data['Question']);
                $id = $this->Mixeval->id;
                $question_ids= $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> $id), 'fields' => array('MixevalsQuestion.id, question_num')));
                $this->MixevalsQuestionDesc->insertQuestionDescriptor($this->data['Question'], $question_ids);
                $this->Session->setFlash(__('The Mixed Evaluation was added successfully.', true), 'good');
                $this->redirect('index');
            } else {
                $this->set('data', $this->data);
                $this->Session->setFlash($this->Mixeval->getErrorMessage());
            }
        }
        $this->set('data', $this->data);
        $this->set('action', __('Add Mixed Evaluation', true));
        $this->render('edit');
    }

    /**
     * deleteQuestion
     *
     * @param mixed $question_id
     *
     * @access public
     * @return void
     */
    function deleteQuestion($question_id)
    {
        $this->autoRender = false;
        $this->MixevalsQuestion->deleteAll(array('id' => $question_id), true);
    }


    /**
     * deleteDescriptor
     *
     * @param mixed $descriptor_id
     *
     * @access public
     * @return void
     */
    function deleteDescriptor($descriptor_id)
    {
        $this->autoRender = false;
        $this->MixevalsQuestionDesc->delete(array('id' => $descriptor_id));
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
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('Error: You do not have permission to edit mixed evaluations', true));
            $this->redirect('/home');
        }

        // retrieving the requested mixed evaluation
        $eval = $this->Mixeval->find(
            'first', 
            array(
                'conditions' => array('id' => $id), 
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        // for storing submissions - for checking if there are any submissions
        $submissions = array();
        
        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }
        
        // instructor
        if (!User::hasPermission('controllers/departments')) {
            $instructorIds = array($this->Auth->user('id'));
        // admins & super admin
        } else {
            // course ids
            $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
            // instructors
            $instructors = $this->UserCourse->find(
                'all',
                array(
                    'conditions' => array('UserCourse.course_id' => $courseIds)
            ));
            $instructorIds = array();
            foreach ($instructors as $instructor) {
                $instructorIds[] = $instructor['UserCourse']['user_id'];
            }
            // add the user's id
            array_push($instructorIds, $this->Auth->user('id'));
        }
        
        // creator's id be in the array of accessible user ids
        if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
            $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
            $this->redirect('index');
        }
        
        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $submissions[] = $event['EvaluationSubmission'];
            }
        }
        
        // check to see if submissions had been made - if yes - mixed evaluation can't be edited
        if (!empty($submissions)) {
            $this->Session->setFlash(__('Submissions had been made. '.$eval['Mixeval']['name'].' cannot be edited. Please make a copy.', true));
            $this->redirect('index');
        }
     
        if (empty($this->data)) {
            $this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                'contain' => array('Question.Description',
            )));

        } else {
            $data = $this->data;

            if ($this->Mixeval->save($data)) {
                $this->MixevalsQuestion->insertQuestion($this->Mixeval->id, $this->data['Question']);
                $id = $this->Mixeval->id;
                $question_ids= $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> $id), 'fields'=>'id, question_num'));
                $this->MixevalsQuestionDesc->insertQuestionDescriptor($this->data['Question'], $question_ids);
                $this->Session->setFlash(__('The Mixed Evaluation was edited successfully.', true), 'good');
                $this->redirect('index');

            } else {
                $this->set('data', $this->data);
                $this->set('errmsg', $this->Mixeval->errorMessage);
            }
        }
        $this->set('data', $this->data);
        $this->set('action', __('Edit Mixed Evaluation', true));
        $this->render('edit');
    }


    /**
     * __processForm
     *
     * @access protected
     * @return void
     */
    function __processForm()
    {
        if (!empty($this->data)) {
            $this->Output->filter($this->data);//always filter

            //Save Data
            if ($this->Mixeval->saveAllWithDescription($this->data)) {
                $this->data['Mixeval']['id'] = $this->Mixeval->id;
                return true;
            } else {
                $this->Session->setFlash($this->Mixeval->errorMessage, 'error');
            }
        }
        return false;
    }


    /**
     * copy
     *
     * @param bool $id
     *
     * @access public
     * @return void
     */
    function copy($id=null)
    {
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('Error: You do not have permission to copy mixed evaluations', true));
            $this->redirect('/home');
        }
    
        $eval = $this->Mixeval->find(
            'first', 
            array(
                'conditions' => array('id' => $id), 
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        
        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
        }

        // instructor
        if (!User::hasPermission('controllers/departments')) {
            $instructorIds = array($this->Auth->user('id'));
        // admins & super admin
        } else {
            // course ids
            $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
            // instructors
            $instructors = $this->UserCourse->find(
                'all',
                array(
                    'conditions' => array('UserCourse.course_id' => $courseIds)
            ));
            $instructorIds = array();
            foreach ($instructors as $instructor) {
                $instructorIds[] = $instructor['UserCourse']['user_id'];
            }
            // add the user's id
            array_push($instructorIds, $this->Auth->user('id'));
        }
        
        // creator's id be in the array of accessible user ids
        if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
            $this->Session->setFlash(__('Error: You do not have permission to copy this evaluation', true));
            $this->redirect('index');
        }

        $this->data = $this->Mixeval->copy($id);
        $this->set('data', $this->data);
        $this->set('action', __('Copy Mixed Evaluation', true));
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
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('You do not have permission to delete mixed evaluations', true));
            $this->redirect('/home');
        }
        
        // for checking the user's role
        $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
        // retrieving the requested mixed evaluation
        $eval = $this->Mixeval->find(
            'first', 
            array(
                'conditions' => array('id' => $id), 
                'contain' => array('Event' => 'EvaluationSubmission')
            )
        );
        
        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }
        
        // instructor
        if (!User::hasPermission('controllers/departments')) {
            $instructorIds = array($this->Auth->user('id'));
        // admins & super admin
        } else {
            // course ids
            $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
            // instructors
            $instructors = $this->UserCourse->find(
                'all',
                array(
                    'conditions' => array('UserCourse.course_id' => $courseIds)
            ));
            $instructorIds = array();
            foreach ($instructors as $instructor) {
                $instructorIds[] = $instructor['UserCourse']['user_id'];
            }
            // add the user's id
            array_push($instructorIds, $this->Auth->user('id'));
        }
        
        // creator's id be in the array of accessible user ids
        if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
            $this->Session->setFlash(__('Error: You do not have permission to delete this evaluation', true));
            $this->redirect('index');
        }
        
        // Deny Deleting evaluations in use:
        $this->Mixeval->id = $id;
        $data = $this->Mixeval->read();
        $inUse = (0 != $data['Mixeval']['event_count']);
        //	$inUse = $this->Event->checkEvaluationToolInUse('4',$id);

        if ($inUse) {
            $message = "<span style='color:red'>";
            $message.= __("This evaluation is now in use, and can NOT be deleted.<br />", true);
            $message.= __("Please remove all the events assosiated with this evaluation first.", true);
            $message.= "</span>";
            $this->Session->setFlash(__($message, true));
            $this->redirect('index');
            //	exit;
        } else {
            if ($this->Mixeval->delete($id)) {
                //Automatically deleted by dependent setting on Model
                //				$this->MixevalsQuestionDesc->deleteQuestionDescriptors($id);
                //				$this->MixevalsQuestion->deleteQuestions($id);

                $this->Session->setFlash(__('The Mixed Evaluation was removed successfully.', true), 'good');
                $this->redirect('index');

            } else {
                $this->Session->setFlash($this->Mixeval->errorMessage, 'error');

            }
        }
    }

    /**
     * previewMixeval
     *
     *
     * @access public
     * @return void
     */
    function previewMixeval()
    {
        //print_r(array_values($this->params));

        $this->layout = 'ajax';
        $this->render('preview');
    }


    /**
     * renderRows
     *
     * @param bool $row             row
     * @param bool $criteria_weight criteria weight
     *
     * @access public
     * @return void
     */
    function renderRows($row=null, $criteria_weight=null )
    {
        $this->layout = 'ajax';
        $this->render('row');
    }


    //	function printUserName($user_id)
    //	{
    //		$tmp = $this->Mixeval->query("SELECT username FROM users WHERE id=$user_id");
    //		echo $tmp[0]['users']['username'];
    //	}

    /**
     * update
     *
     * @param string $attributeCode  attribute code
     * @param string $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function update($attributeCode='',$attributeValue='')
    {
        if ($attributeCode != '' && $attributeValue != '') {
            //check for empty params
            $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
        }
    }

}
