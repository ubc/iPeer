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
    public $uses = array('SimpleEvaluation', 'Event', 'Personalize');
    public $components = array('AjaxList');

    //	public $components = array('EvaluationSimpleHelper');

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
        $this->set('title_for_layout', __('Simple Evaluations', true));
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
            array("SimpleEvaluation.point_per_member", __("Points/Member", true), "10em", "number"),
            array("SimpleEvaluation.creator_id",           "",            "",     "hidden"),
            array("SimpleEvaluation.creator",     __("Creator", true),  "10em", "action", "View Creator"),
            array("SimpleEvaluation.created", __("Creation Date", true), "10em", "date"));

        $userList = array($myID => "My Evaluations");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator.id",
                "localKey"   => "creator_id",
                "description" => __("Evaluations to show:", true),
                "default" => $myID,
                "list" => $userList,
                "joinTable"  => "users",
                "joinModel"  => "Creator");
        // put all the joins together
        $joinTables = array($jointTableCreator);

        $extraFilters = "";

        // Instructors can only edit their own evaluations
        $restrictions = "";
        if (!User::hasRole('superadmin') && !User::hasRole('admin')) {
            $restrictions = array(
                "SimpleEvaluation.creator_id" => array(
                    $myID => true,
                    "!default" => false));
        }

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
     * @param mixed  $id     id
     * @param string $layout layout
     *
     * @access public
     * @return void
     */
    function view($id, $layout='')
    {
        if ($layout != '') {
            $this->layout = $layout;
            if ($layout == 'pop_up') {
                $this->set('popUp', 1);
            }
        }
        $data = $this->SimpleEvaluation->read(null, $id);
        $this->set('data', $data);
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
        if ($layout != '') {
            $this->layout = $layout;
        }

        if (!empty($this->data)) {
            if ($this->__processForm()) {
                $this->Session->setFlash(__("The evaluation was added successfully.", true));
                $this->redirect('index');
            } else {
                $this->Session->setFlash($this->SimpleEvaluation->errorMessage);
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
    function edit($id)
    {
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        $this->data['SimpleEvaluation']['id'] = $id;

        if ($this->__processForm()) {
            $this->Session->setFlash(__('The simple evaluation was updated successfully.', true));
            $this->redirect('index');
        } else {
            $this->data = $this->SimpleEvaluation->find('first', array('conditions' => array('id' => $id),
                'contain' => false));

            $this->Output->filter($this->data);//always filter
            //converting nl2br back so it looks better
            $this->Output->br2nl($this->data);
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
    function delete($id)
    {
        // Deny Deleting evaluations in use:
        if ($this->SimpleEvaluation->getEventCount($id)) {
            $message = __("This evaluation is now in use, and can NOT be deleted.<br />", true);
            $message.= __("Please remove all the events assosiated with this evaluation first.", true);
            $this->Session->setFlash($message);
            $this->redirect('index');
        }

    /*if (isset($this->params['form']['id'])) {
      $id = intval(substr($this->params['form']['id'], 5));
    }   //end if*/

        if ($this->SimpleEvaluation->delete($id)) {
            $this->Session->setFlash(__('The evaluation was deleted successfully.', true));
        } else {
            $this->Session->setFlash(__('Evaluation delete failed.', true));
        }
        $this->redirect('index');
    }

  /*function update($attributeCode='',$attributeValue='')
{
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
  }*/
}
