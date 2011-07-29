<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailtemplatesController extends AppController
{
  var $name = 'EmailTemplates';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate', 'EmailMerge', 'EmailSchedule', 'Personalize', 'SysParameter', 'SysFunction');
  var $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
  var $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Pagination', 'Js' => array('Prototype'));
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;

  function __construct(){
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'EmailTemplate.description': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy . ' ' . strtoupper($this->direction);
    $this->pageTitle = 'Email';
    $this->mergeStart = '{{{';
    $this->mergeEnd = '}}}';
    parent::__construct();
  }

  function setUpAjaxList() {
    $myID = $this->Auth->user('id');

    // Set up Columns
    $columns = array(
            array("EmailTemplate.id",   "",       "",        "hidden"),
            array("EmailTemplate.name", __("Name", true),   "12em",    "action",   "View Email Template"),
            array("EmailTemplate.subject", __("Subject", true),   "12em",    "string"),
            array("EmailTemplate.description", __("Description", true),"auto",  "string"),
            array("EmailTemplate.creator_id",           "",            "",     "hidden"),
            array("EmailTemplate.creator",     __("Creator", true),  "10em", "action", "View Creator"),
            array("EmailTemplate.created", __("Creation Date", true), "10em", "date"));

    $userList = array($myID => "My Email Template");

    // Join with Users
    $jointTableCreator =
      array("id"         => "Creator.id",
            "localKey"   => "creator_id",
            "description" => __("Email Template to show:", true),
            "default" => $myID,
            "list" => $userList,
            "joinTable"  => "users",
            "joinModel"  => "Creator");
    //put all the joins together
    $joinTables = array($jointTableCreator);

    $extraFilters = "";

    // Restriction for Instructor
    $restrictions = "";
    if ($this->Auth->user('role') != 'A') {
      $restrictions = array(
          "EmailTemplate.creator_id" => array($myID => true, "!default" => false)
      );
      $extraFilters = "(EmailTemplate.creator_id=$myID or EmailTemplate.availability='1')";
    }

    // Set up actions
    $warning = __("Are you sure you want to delete this email template permanently?", true);
    $actions = array(
                     array(__("View Email Template", true), "", "", "", "view", "EmailTemplate.id"),
                     array(__("Edit Email Template", true), "", $restrictions, "", "edit", "EmailTemplate.id"),
                     array(__("Delete Email Template", true), $warning, $restrictions, "", "delete", "EmailTemplate.id"),
                     array(__("View Creator", true), "",    "", "users", "view", "EmailTemplate.creator_id"));

    // Set up the list itself
    $this->AjaxList->setUp($this->EmailTemplate, $columns, $actions,
                           "EmailTemplate.id", "EmailTemplate.subject", $joinTables, $extraFilters);
  }

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  /**
   * Index
   */
  function index(){
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  /**
   * Add an email template
   */
  function add(){
    //Set up user info
    $currentUser = $this->User->getCurrentLoggedInUser();
    $this->set('currentUser', $currentUser);
    $this->set('mergeList', $this->EmailMerge->getMergeList());
    if (empty($this->params['data'])) {

    }
    else{
      //Save Data
      if ($this->EmailTemplate->save($this->params['data'])) {
        $this->Session->setFlash(__('Successful', true));
        $this->redirect('/emailtemplates/index');
      }
      else{
        $this->Session->setFlash(__('Failed to save', true));
      }
    }

  }

  /**
   * Edit an email template
   * @param <type> $id template id
   */
  function edit ($id){
    $creator_id = $this->EmailTemplate->getCreatorId($id);
    $user_id = $this->Auth->user('id');
    if($creator_id == $user_id){
      //Set up user info
      $currentUser = $this->User->getCurrentLoggedInUser();
      $this->set('currentUser', $currentUser);
      $this->set('mergeList', $this->EmailMerge->getMergeList());

      $data = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $id)
      ));

      if (empty($this->params['data'])) {
          $this->data = $data;
          $this->render('add');
      }
      else{
        //Save Data
        if ($this->EmailTemplate->save($this->params['data'])) {
          $this->Session->setFlash(__('Successful', true));
          $this->redirect('/emailtemplates/index');
        }
        else{
          $this->Session->setFlash(__('Failed to save', true));
        }
      }
    }
    else{
      $this->Session->setFlash(__('No Permission', true));
      $this->redirect('/emailtemplates/index');
    }
  }

  /**
   * Delete an email template
   * @param <type> $id template id
   */
  function delete ($id) {
    $creator_id = $this->EmailTemplate->getCreatorId($id);
    $user_id = $this->Auth->user('id');
    if($creator_id == $user_id){
      if ($this->EmailTemplate->delete($id)) {
        $this->Session->setFlash(__('The Email Template was deleted successfully.', true));
      } else {
        $this->Session->setFlash(__('Failed to delete the Email Template.', true));
      }
      $this->redirect('index/');
    }
    else{
      $this->Session->setFlash(__('No Permission', true));
      $this->redirect('/emailtemplates/index');
    }
  }

  /**
   * View an email template
   * @param <type> $id template id
   */
  function view ($id){
    $this->data = $this->EmailTemplate->find('first', array(
        'conditions' => array('EmailTemplate.id' => $id)
    ));
    $this->set('readonly', true);
    $this->render('add');

  }

  /**
   * display template content for updating field by selecting a template
   * @param <type> $templateId template id
   */
  function displayTemplateContent($templateId = null) {
      $this->layout = 'ajax';
      $template = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $templateId)
      ));
      $this->set('template', $template);
  }

  /**
   * display template subjec for updating field by selecting a template
   * @param $templateId template id
   */
  function displayTemplateSubject($templateId = null) {
      $this->layout = 'ajax';
      $template = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $templateId)
      ));
      $this->set('template', $template);
  }
}
?>

