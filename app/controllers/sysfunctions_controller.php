<?php
App::import('Lib', 'neat_string');
/**
 * SysFunctionsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SysFunctionsController extends AppController
{
    public $name = 'SysFunctions';
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $NeatString;
    public $Sanitize;
    public $uses = array('SysFunction', 'Personalize');
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
        $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);
        $this->set('title_for_layout', __('Sys Functions', true));
        parent::__construct();
    }

    /**
     * setUpAjaxList
     *
     * @access public
     * @return void
     */
    function setUpAjaxList()
    {
        $columns = array(
            array("SysFunction.id",              __("ID", true),         "3em", "number"),
            array("SysFunction.function_code",   __("Code", true),       "15em", "string"),
            array("SysFunction.function_name",   __("Name", true),      "auto", "string"),
            array("SysFunction.controller_name", __("Controller", true), "6em", "string"),
            array("SysFunction.url_link",        __("URL Link", true),   "5em", "string"),
            array("SysFunction.permission_type", __("Permissions", true), "5em", "string"),
            array("SysFunction.record_status",  __("Status", true),   "5em", "map",
            array("A" => "Active", "I" => "Inactive")),
            array("SysFunction.created",        __("Created", true), "10em", "date"),
            array("SysFunction.modified",       __("Updated", true), "10em", "date"));

        $warning = __("Are you sure you wish to delete this System Function?", true);
        $actions = array(
            array(__("View", true), "", "", "", "view", "SysFunction.id"),
            array(__("Edit", true), "", "", "", "edit", "SysFunction.id"),
            array(__("Delete", true), $warning, "", "", "delete", "SysFunction.id"));

        $this->AjaxList->setUp($this->SysFunction, $columns, $actions,
            "SysFunction.id", "SysFunction.function_code");
    }

    /**
     * index
     *
     * @param string $message
     *
     * @access public
     * @return void
     */
    function index($message='')
    {
        // Make sure the present user has permission
        if (!User::hasPermission('controllers/sysfunctions')) {
            $this->Session->setFlash('You do not have permission to view system functions', true);
            $this->redirect('/home');
        }
        // Set the top message
        $this->set('message', $message);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
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
        // Make sure the present user has permission
        if (!User::hasPermission('controllers/sysfunctions')) {
            $this->Session->setFlash('You do not have permission to view system functions', true);
            $this->redirect('/home');
        }
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
        $this->SysFunction->id = $id;
        $data = $this->SysFunction->read();
        $this->set('data', $data);
    }


    /**
     * edit
     *
     * @param bool $id
     *
     * @access public
     * @return void
     */
    function edit($id=null)
    {

        if (empty($this->data)) {
            $this->SysFunction->id = $id;
            $this->data = $this->SysFunction->read();
            $this->set('data', $this->data);
            $this->render();
        } else {
            if ( $this->SysFunction->save($this->data)) {

                $this->Session->setFlash(__('The record is edited successfully.', true));
                $this->redirect('index');
            } else {
                $this->Session->setFlash($this->SysFunction->errorMessage, true);
                $this->set('data', $this->data);
                $this->render();
            }
        }
    }

    /**
     * delete
     *
     * @param bool $id
     *
     * @access public
     * @return void
     */
    function delete($id = null)
    {
        if ($this->SysFunction->delete($id)) {
            $this->Session->setFlash(__('The record is deleted successfully.', true));
            $this->redirect('index');
        }
    }


    /**
     * update
     *
     * @param string $attributeCode  attribute code
     * @param string $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function update($attributeCode='', $attributeValue='')
    {
        if ($attributeCode != '' && $attributeValue != '') {
            $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
        }
    }
}
