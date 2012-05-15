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
    public $uses =  array('Event', 'Rubric', 'RubricsLom', 'RubricsCriteria', 'RubricsCriteriaComment', 'Personalize');
    public $name = 'Rubrics';
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $Sanitize;
    public $components = array('AjaxList', 'Output', 'sysContainer', 'userPersonalize', 'framework');

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
        $this->set('title_for_layout', __('Rubrics', true));
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
            array("Rubric.availability",__("Availability", true), "6em",   "string"),
            array("Rubric.lom_max",     __("LOM", true),         "4em",   "number"),
            array("Rubric.criteria",    __("Criteria", true),    "4em",   "number"),
            array("Rubric.total_marks", __("Total", true),       "4em",   "number"),
            array("Rubric.event_count",   "",       "",        "hidden"),
            array("Rubric.creator_id",         "",            "",      "hidden"),
            array("Rubric.creator",   __("Creator", true),     "8em",   "action", "View Creator"),
            array("Rubric.created",     __("Creation Date", true), "10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array($this->Auth->user('id') => "My Evaluations");

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

        // List only my own or
        $myID = $this->Auth->user('id');
        $extraFilters = "(Rubric.creator_id=$myID or Rubric.availability='public')";

        // Instructors can only edit their own evaluations
        $restrictions = "";
        if (!User::hasRole('superadmin') && !User::hasRole('admin')) {
            $restrictions = array("Rubric.creator_id" => array(
                $myID => true,
                "!default" => false));
        }

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
     * @param int    $id     id
     * @param string $layout layout
     *
     * @access public
     * @return void
     */
    function view($id, $layout='')
    {
        if ($layout != '') {
            $this->layout = $layout;
        }

        $this->data = $this->Rubric->find('first', array('conditions' => array('id' => $id),
            'contain' => array('RubricsCriteria.RubricsCriteriaComment',
            'RubricsLom')));
        $this->set('data', $this->data);
        $this->set('readonly', true);
        $this->set('evaluate', false);
        $this->set('action', __('View Rubric', true));
        $this->render('edit');
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
            $this->set('action', __('Add Rubric (Step 2)', true));
            $this->set('data', $this->data);

            if (isset($this->params['form']['submit'])) {
                if ($this->__processForm()) {
                    $this->Session->setFlash(__('The rubric was added successfully.', true));
                    $this->redirect('index');
                }
            }
        } else {
            $this->set('action', __('Add Rubric', true));
        }
        $this->render('edit');
/*
          $this->params['data']['Rubric']['total_marks'] = $this->params['form']['total_marks'];

          if ($this->Rubric->save($this->params['data'])) {
            //prepare the data from the form fields in array
            $this->params['data']['Rubric'] = $this->Rubric->prepData($this->params, $this->Auth->user('id'));

            //insert all the rubric data into other associated tables
            $this->RubricsLom->insertLOM($this->Rubric->id, $this->params['data']['Rubric']);
            $this->RubricsCriteria->insertCriteria($this->Rubric->id, $this->params['data']['Rubric']);
            $this->RubricsCriteriaComment->insertCriteriaComm($this->Rubric->id, $this->params['data']['Rubric']);

            $this->Session->setFlash('The rubric was added successfully.');
            $this->redirect('/rubrics/index');
          } else {
            $this->set('data', $this->params['data']);
            $this->set('errmsg', $this->Rubric->errorMessage);
            $this->render('add');
          }
        }

}*/
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
                }
            }
        }
        $this->set('action', __('Edit Rubric', true));
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

            //$this->log($this->data);
            if ($this->Rubric->saveAllWithCriteriaComment($this->data)) {
                $this->data['Rubric']['id'] = $this->Rubric->id;
                return true;
            } else {
                $this->Session->setFlash($this->Rubric->errorMessage, 'error');
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
        $this->data = $this->Rubric->copy($id);
        $this->set('data', $this->data);
        $this->set('action', __('Copy Rubric', true));
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
        if ($this->Rubric->getEventCount($id)) {
            $this->Session->setFlash(__('This evaluation is in use. Please remove all the events assosiated with this evaluation first.', true),
                'error');
        } else {
            if ($this->Rubric->delete($id, true)) {
        /*$this->RubricsLom->deleteLOM($id);
          $this->RubricsCriteria->deleteCriterias($id);
          $this->RubricsCriteriaComment->deleteCriteriaComments($id);
        //$this->set('data', $this->Rubric->find('all',null, null, 'id'));
        $this->index();*/
                $this->Session->setFlash(__('The rubric was deleted successfully.', true));
            }
        }
        $this->redirect('index');
    }


    /**
     * setForm_RubricName
     *
     * @param mixed $name
     *
     * @access public
     * @return void
     */
    function setForm_RubricName($name)
    {
        $this->data['Rubric']['name'] = $name;
        //$this->log($this->data['Rubric']['name']);
    }


/*  function previewRubric()
  {
    $this->layout = 'ajax';
        $this->render('preview');
  }

    function renderRows($row=null, $criteria_weight=null )
    {
        $this->layout = 'ajax';
        $this->render('row');
    }


    function update($attributeCode='', $attributeValue='')
{
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
}*/
}
