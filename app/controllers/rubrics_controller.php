<?php
/**
 * RubricsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsController extends AppController
{
    public $uses =  array('Event', 'Rubric', 'RubricsLom', 'RubricsCriteria', 'RubricsCriteriaComment', 'Personalize', 'UserCourse');
    public $name = 'Rubrics';
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $Sanitize;
    public $components = array('AjaxList', 'Output', 'userPersonalize', 'framework');

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

        $this->set('title_for_layout', __('Rubrics', true));
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
                $inUse = (0 != $entry['Rubric']['event_count']);

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
            array("Rubric.id",          "",            "",      "hidden"),
            array("Rubric.name",        __("Name", true),        "auto",  "action", "View Rubric"),
            array("!Custom.inUse",      __("In Use", true),      "4em",   "number"),
            array("Rubric.availability",__("Availability", true), "6em",   "map",
                array('private' => 'private', 'public' => 'public')),
            array("Rubric.lom_max",     __("LOM", true),         "4em",   "number"),
            array("Rubric.criteria",    __("Criteria", true),    "4em",   "number"),
            array("Rubric.total_marks", __("Total", true),       "4em",   "number"),
            array("Rubric.event_count",   "",       "",        "hidden"),
            array("Rubric.creator_id",         "",            "",      "hidden"),
            array("Rubric.creator",   __("Creator", true),     "8em",   "action", "View Creator"),
            array("Rubric.created",     __("Creation Date", true), "10em", "date"),
            array("Rubric.view_mode", __("View Mode", true), "4em", "number")
            );

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

        // Instructors can only edit their own rubric templates
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
            $restrictions['Rubric.creator_id'] = $basicRestrictions;

        // Set up actions
        $warning = __("Are you sure you want to delete this Rubric permanently?", true);
        $actions = array(
            array(__("View Rubric", true), "", "", "", "view", "Rubric.id"),
            array(__("Edit Rubric", true), "", $restrictions, "", "edit", "Rubric.id"),
            array(__("Copy Rubric", true), "", "", "", "copy", "Rubric.id"),
            array(__("Delete Rubric", true), $warning, $restrictions, "", "delete", "Rubric.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Rubric.creator_id"));

        // No recursion in results
        $recursive = 0;

        // Set up the list itself
        $this->AjaxList->setUp($this->Rubric, $columns, $actions,
            "Rubric.name", "Rubric.name", $joinTables, $extraFilters, $recursive, "postProcess");
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
    function view($id)
    {
        $eval = $this->Rubric->getEventSub($id);

        // check to see if $id is valid - numeric & is a rubric
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // check whether the user has access to the evaluation if the rubric is not public
        if ($eval['Rubric']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins & super admins
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
            if (!(in_array($eval['Rubric']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to view this rubric', true));
                $this->redirect('index');
                return;
            }
        }

        $this->data = $this->Rubric->find('first', array('conditions' => array('id' => $id),
            'contain' => array('RubricsCriteria.RubricsCriteriaComment',
            'RubricsLom')));
        $this->set('data', $this->data);
        $this->set('readonly', true);
        $this->set('evaluate', false);
        $this->set('action', __('View Rubric', true));
        $this->set('breadcrumb',
            $this->breadcrumb->push('rubrics')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($this->data['Rubric']['name'])
        );
        $this->render('edit');
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
            $this->breadcrumb->push('rubrics')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
        if (!empty($this->data)) {
            $this->set('action', __('Add Rubric (Step 2)', true));
            $this->set('data', $this->data);
            
            //Sets initial values for radio buttons
            if($this->data['Rubric']['availability'] == ""){
                $this->data['Rubric']['availability'] = 'private';
            }
            if($this->data['Rubric']['view_mode'] == ""){
                $this->data['Rubric']['view_mode'] = 'student';
            }
            if (isset($this->params['form']['submit'])) {
                if ($this->__processForm()) {
                    $this->Session->setFlash(__('The rubric was added successfully.', true), 'good');
                    $this->redirect('index');
                    return;
                } else {
                    $this->set('data', $this->data);
                    $this->Session->setFlash(__("The evaluation was not added successfully.", true));
                    $error = $this->Rubric->getErrorMessage();
                    if (!is_array($error)) {
                        $this->Session->setFlash($error);
                    }
                }
            }
        } else {
            $this->set('action', __('Add Rubric', true));
        }
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
        // retrieving the requested rubric
        $eval = $this->Rubric->getEventSub($id);

        // check to see if $id is valid - numeric & is a rubric
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

            if (!(in_array($eval['Rubric']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this rubric', true));
                $this->redirect('index');
                return;
            }
        }

        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $this->Session->setFlash(sprintf(__('Submissions had been made. %s cannot be edited. Please make a copy.', true), $eval['Rubric']['name']));
                $this->redirect('index');
                return;
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Rubric->find('first', array('conditions' => array('id' => $id),
                'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom')));
            $this->set('data', $this->data);
        } else {
            //check to see if user has clicked preview
            if (!empty($this->params['form']['preview'])) {
                $this->set('data', $this->data);
            } else {
                if ($this->__processForm()) {
                    $this->Session->setFlash(__('The rubric evaluation was updated successfully', true), 'good');
                    $this->redirect('index');
                    return;
                } else { 
                    $this->set('data', $this->data);
                    $this->Session->setFlash(__("The evaluation was not updated successfully.", true));
                    $error = $this->Rubric->getErrorMessage();
                    if (!is_array($error)) {
                        $this->Session->setFlash($error);
                    }
                }
            }
        }
        $this->set('breadcrumb',
            $this->breadcrumb->push('rubrics')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($this->data['Rubric']['name'])
        );
        $this->set('action', __('Edit Rubric', true));
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
            $this->data = $this->_multiMap($this->data);
            //Save Data

            //$this->log($this->data);
            if ($this->Rubric->saveAllWithCriteriaComment($this->data)) {
                $this->data['Rubric']['id'] = $this->Rubric->id;
                return true;
            }
        }
        return false;
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
        $eval = $this->Rubric->getEventSub($id);

        // check to see if $id is valid - numeric & is a rubric
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // can be copied if rubric is public
        if ($eval['Rubric']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
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

            // creator id must be in the array of accessible user ids
            if (!(in_array($eval['Rubric']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to copy this rubric', true));
                $this->redirect('index');
                return;
            }
        }

        $this->data = $this->Rubric->copy($id);
        $this->set('data', $this->data);
        $this->set('action', __('Copy Rubric', true));
        $this->set('breadcrumb',
            $this->breadcrumb->push('rubrics')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
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
        // retrieving the requested rubric
        $eval = $this->Rubric->getEventSub($id);

        // check to see if $id is valid - numeric & is a rubric
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

            // creator id must be in the array of accessible user ids
            if (!(in_array($eval['Rubric']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to delete this rubric', true));
                $this->redirect('index');
                return;
            }
        }

        // Deny Deleting evaluations in use:
        if ($this->Rubric->getEventCount($id)) {
            $this->Session->setFlash(__('This evaluation is in use. Please remove all the events assosiated with this evaluation first.', true),
                'error');
        } else {
            if ($this->Rubric->delete($id, true)) {
                $this->Session->setFlash(__('The rubric was deleted successfully.', true), 'good');
            }
        }
        $this->redirect('index');
    }
    
    /**
     * _multiMap
     *
     * @param mixed $data data
     *
     * @access private
     * @return void
     */
    function _multiMap($data)
    {
        $ret = array();
        foreach($data as $key => $value) {
            $ret[$key] = is_array($value) ? $this->_multiMap($value) : trim($value);
        }
        return $ret;
    }

}
