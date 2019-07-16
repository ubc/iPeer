<?php
App::import('Lib', 'neat_string');

/**
 * SimpleevaluationsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SimpleevaluationsController extends AppController
{
    public $name = 'SimpleEvaluations';

    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $NeatString;
    public $Sanitize;
    public $uses = array('SimpleEvaluation', 'Event', 'Personalize', 'UserCourse');
    public $components = array('AjaxList');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->NeatString = new NeatString;
        $this->show = empty($_REQUEST['show'])? 'null': $this->Sanitize->paranoid($_REQUEST['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'name': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);
        $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;

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

        $this->set('title_for_layout', __('Simple Evaluations', true));
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
                $inUse = (0 != $entry['SimpleEvaluation']['event_count']);

                // Put in the custom column
                $data[$key]['!Custom']['inUse'] = $inUse ? "Yes" : "No";
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
            array("SimpleEvaluation.id",   "",       "",        "hidden"),
            array("SimpleEvaluation.event_count",   "",       "",        "hidden"),
            array("SimpleEvaluation.name", __("Name", true),   "12em",    "action",   "View Evaluation"),
            array("SimpleEvaluation.description", __("Description", true), "auto",  "action", "View Evaluation"),
            array("!Custom.inUse", __("In Use", true),          "4em",    "number"),
            array("SimpleEvaluation.availability", __("Availability", true), "6em", "map", 
                array('private' => 'private', 'public' => 'public')),
            array("SimpleEvaluation.point_per_member", __("Points/Member", true), "10em", "number"),
            array("SimpleEvaluation.creator_id",           "",            "",     "hidden"),
            array("SimpleEvaluation.creator",     __("Creator", true),  "10em", "action", "View Creator"),
            array("SimpleEvaluation.created", __("Creation Date", true), "10em", "date"));

        $userList = array($myID => "My Evaluations");

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

        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        } else {
            $creators = array();
            // grab course ids of the courses admin/instructor has access to
            $courseIds = User::getAccessibleCourses();

            // grab all instructors that have access to the courses above
            $instructors = $this->UserCourse->findAllByCourseId($courseIds);

            $extraFilters = "(";
            // only admins will go through this loop
            foreach ($instructors as $instructor) {
                $id = $instructor['UserCourse']['user_id'];
                $creators[] = $id;
                $extraFilters .= "creator_id = $id or ";
            }
            // allow instructors/admins to see their own simple eval templates
            $extraFilters .= "creator_id = $myID or ";
            // can see all public simple evaluation templates
            $extraFilters .= "availability = 'public')";
        }

        // Instructors can only edit their own simple evaluations
        $restrictions = array();
        // instructors
        $basicRestrictions = array(
            $myID => true,
            "!default" => false);
        // super admins
        if (User::hasPermission('functions/superadmin')) {
            $basicRestrictions = "";
        // faculty admins
        } else if (User::hasPermission('controllers/departments')) {
            foreach ($creators as $creator) {
                $basicRestrictions = $basicRestrictions + array($creator => true);
            }
        }

        empty($basicRestrictions) ? $restrictions = $basicRestrictions :
            $restrictions['SimpleEvaluation.creator_id'] = $basicRestrictions;

        // Set up actions
        $warning = __("Are you sure you want to delete this evaluation permanently?", true);
        $actions = array(
            array(__("View Evaluation", true), "", "", "", "view", "SimpleEvaluation.id"),
            array(__("Edit Evaluation", true), "", $restrictions, "", "edit", "SimpleEvaluation.id"),
            array(__("Copy Evaluation", true), "", "", "", "copy", "SimpleEvaluation.id"),
            array(__("Delete Evaluation", true), $warning, $restrictions, "", "delete", "SimpleEvaluation.id"),
            array(__("View Creator", true), "",    "", "users", "view", "SimpleEvaluation.creator_id"));

        // No recursion in results
        $recursive = 0;

        // Set up the list itself
        $this->AjaxList->setUp($this->SimpleEvaluation, $columns, $actions,
            "SimpleEvaluation.name", "SimpleEvaluation.name", $joinTables, $extraFilters, $recursive, "postProcess");
    }


    /**
     * index
     *
     * @access public
     * @return void
     */
    function index()
    {
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
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
     * @param mixed $id id
     *
     * @access public
     * @return void
     */
    function view($id = null)
    {
        $eval = $this->SimpleEvaluation->getEventSub($id);

        // check to see if $id is valid - numeric & is a simple evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // check whether the user has access to the evaluation if the eval is not public
        if ($eval['SimpleEvaluation']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id must be in the array of accessible user ids
            if (!(in_array($eval['SimpleEvaluation']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to view this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        $data = $this->SimpleEvaluation->read(null, $id);
        $this->set('data', $data);
        $user = $this->Auth->user();
        $this->set('user', $user['User']);
        $this->set('breadcrumb',
            $this->breadcrumb->push('simple_evaluations')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($data['SimpleEvaluation']['name'])
        );
    }

    /**
     * add
     *
     * @access public
     * @return void
     */
    function add()
    {
        $this->set('breadcrumb',
            $this->breadcrumb->push('simple_evaluations')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
        if (!empty($this->data)) {
            if ($this->__processForm()) {
                $this->Session->setFlash(__("The evaluation was added successfully.", true), 'good');
                $this->redirect('index');
                return;
            } else {
                $this->Session->setFlash(__("The evaluation was not added successfully.", true));
                $this->set('data', $this->data);
            }
        }
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
            $this->data['SimpleEvaluation'] = array_map('trim', $this->data['SimpleEvaluation']);
            //Save Data
            if ($this->SimpleEvaluation->save($this->data)) {
                $this->data['SimpleEvaluation']['id'] = $this->SimpleEvaluation->id;
                return true;
            }
        }

        return false;
    }

    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function edit($id = null)
    {
        // retrieving the requested simple evaluation
        $eval = $this->SimpleEvaluation->getEventSub($id);

        // check to see if $id is valid - numeric & is a simple evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['SimpleEvaluation']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        // check to see if submissions had been made - if yes - simple evaluation can't be edited
        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $this->Session->setFlash(sprintf(__('Submissions had been made. %s cannot be edited. Please make a copy.', true), $eval['SimpleEvaluation']['name']));
                $this->redirect('index');
            }
        }

        $this->data['SimpleEvaluation']['id'] = $id;

        if ($this->__processForm()) {
            $this->Session->setFlash(__('The simple evaluation was updated successfully.', true), 'good');
            $this->redirect('index');
            return;
        } else {
            $this->data = $this->SimpleEvaluation->findById($id);
            $this->Output->filter($this->data);//always filter
            //converting nl2br back so it looks better
            $this->Output->br2nl($this->data);
        }

        $this->set('breadcrumb',
            $this->breadcrumb->push('simple_evaluations')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($this->data['SimpleEvaluation']['name'])
        );
    }

    /**
     * copy
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function copy($id = null)
    {
        $eval = $this->SimpleEvaluation->getEventSub($id);

        // check to see if $id is valid - numeric & is a simple evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // can be copied if eval is public
        if ($eval['SimpleEvaluation']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['SimpleEvaluation']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to copy this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        $this->set('breadcrumb',
            $this->breadcrumb->push('simple_evaluations')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
        $this->render = false;
        $this->data = $this->SimpleEvaluation->read(null, $id);
        $this->data['SimpleEvaluation']['id'] = null;
        $this->data['SimpleEvaluation']['name'] = 'Copy of '.$this->data['SimpleEvaluation']['name'];
        //converting nl2br back so it looks better
        $this->Output->br2nl($this->data);
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
    function delete($id = null)
    {
        // retrieving the requested simple evaluation
        $eval = $this->SimpleEvaluation->getEventSub($id);

        // check to see if $id is valid - numeric & is a simple evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['SimpleEvaluation']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to delete this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        // Deny Deleting evaluations in use:
        if ($this->SimpleEvaluation->getEventCount($id)) {
            $message = __("This evaluation is now in use, and can NOT be deleted.<br />", true);
            $message.= __("Please remove all the events assosiated with this evaluation first.", true);
            $this->Session->setFlash($message);
            $this->redirect('index');
        }

        if ($this->SimpleEvaluation->delete($id)) {
            $this->Session->setFlash(__('The evaluation was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Evaluation delete failed.', true));
        }
        $this->redirect('index');
    }
}
