<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/11/03 16:55:33 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Events
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class EventsController extends AppController
{
  var $name = 'Events';
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $NeatString;
	var $Sanitize;
	var $uses = array('GroupEvent', 'User', 'Group', 'Course', 'Event', 'EventTemplateType', 'SimpleEvaluation', 'Rubric', 'Mixeval', 'Personalize', 'GroupsMembers');
  var $components = array("AjaxList", "SysContainer", "Session");

  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy.' '.strtoupper($this->direction);
    $this->set('title_for_layout', 'Events');
    parent::__construct();
  }

  // Post Process Data : add released column
  function postProcessData($data) {
  	
    // Check the release dates, and match them up with present date
    if (empty($data)) return $data;
    // loop through each data point, and display it.
    foreach ($data as $i => $entry) {
      $releaseDate = strtotime($entry["Event"]["release_date_begin"]);
      $endDate = strtotime($entry["Event"]["release_date_end"]);
      $timeNow = strtotime($entry[0]["now()"]);

      if (!$releaseDate) $releaseDate = 0;
      if (!$endDate) $endDate = 0;

      $isReleased = "";
      if ($timeNow < $releaseDate) {
        $isReleased = "Not Yet Open";
      } else if ($timeNow > $endDate) {
        $isReleased = "Already Closed";
      } else {
        $isReleased = "Open Now";
      }


      // Set the is released string
      $entry['!Custom']['isReleased'] = $isReleased;

      // Set the view results column
      $entry['!Custom']['results'] = "Results";

      // Write the entry back
      $data[$i] = $entry;
    }

    // Return the modified data
    return $data;
  }

  function setUpAjaxList() {

    // Grab the course list
    $userCourseList = $this->SysContainer->getMyCourseList();
    $coursesList = array();
    foreach ($userCourseList as $id => $course) {
      $coursesList[$id] = $course['course'];
    }

    // Set up Columns
    $columns = array(
            array("Event.id",             "",            "",     "hidden"),
            array("Course.id",            "",            "",     "hidden"),
            array("Course.course",        "Course",      "13em", "action", "View Course"),
            array("Event.Title",          "Title",       "auto", "action", "View Event"),
            array("!Custom.results",       "View",       "4em", "action", "View Results"),
            array("Event.event_template_type_id", "Type", "", "map",
                array("1" => "Simple", "2" => "Rubric", "4" => "Mixed")),
            array("Event.due_date",       "Due Date",    "10em", "date"),
            array("!Custom.isReleased",    "Released ?", "10em", "string"),
            array("Event.self_eval",       "Self Eval",   "6em", "map",
                array("0" => "Disabled", "1" => "Enabled")),
            array("Event.com_req",        "Comment",      "6em", "map",
                array("0" => "Optional", "1" => "Required")),

            // Release window
            array("Event.release_date_begin", "", "",    "hidden"),
            array("Event.release_date_end",   "", "",    "hidden"),
            array("now()",           "",          "",    "hidden"));

    // put all the joins together
    $joinTables =  array( array (
                // GUI aspects
                "id" => "course_id",
                "description" => "for Course:",
                // The choise and default values
                "list" => $coursesList,
                "default" => $this->Session->read('ipeerSession.courseId'),
                // What tables do we join?
                "joinTable" => "courses",
                "joinModel" => "Course",
                "localKey" => "course_id"
        ));

    // For instructors: only list their own course events
    $extraFilters = "";
    if ($this->Auth->user('role') != 'A') {
      $extraFilters = " ( ";
      foreach ($coursesList as $id => $course) {
        $extraFilters .= "course_id=$id or ";
      }
      $extraFilters .= "1=0 ) "; // just terminates the or condition chain with "false"
    }

    // Leave the survey types out, always
    $extraFilters .= !empty($extraFilters) ? "and " : "";
    $extraFilters .= "Event.event_template_type_id<>3";


    // Set up actions
    $warning = "Are you sure you want to delete this event permanently?";
    $actions = array(
                     array("View Results", "", "", "evaluations", "view", "Event.id"),
                     array("View Event", "", "", "", "!view", "Event.id"),
                     array("Edit Event", "", "", "", "edit", "Event.id"),
                     array("View Course", "", "", "courses", "view", "Course.id"),
                     array("View Groups", "", "", "", "!viewGroups", "Event.id"),
                     array("Delete Event", $warning, "", "", "delete", "Event.id"));

    $recursive = 0;

    $this->AjaxList->setUp($this->Event, $columns, $actions,
                           "Course.course", "Course.course", $joinTables, $extraFilters,
                           $recursive, "postProcessData");
  }

  function index($message = '') {
  	
  	$this->set('message', $message);
  	
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  function ajaxList() {
    // Make sure the present user is not a student
    //$this->rdAuth->noStudentsAllowed();
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  // Show a class list
  function goToClassList($course) {
    if (is_numeric($course)) {
      $courses = $this->sysContainer->getMyCourseList();
      if (!empty($courses[$course])) {
        // We need to change the session state to point to this
        // course:
        // Initialize a basic non-funcional AjaxList
        $this->AjaxList->quickSetUp();
        // Clear the state first, we don't want any previous searches/selections.
        $this->AjaxList->clearState();
        // Set and update session state Variable
        $joinFilterSelections->course_id = $course;
        $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);
      }
    }
    // Redirect to user list after state modifications (or in case of error)
    $this->redirect("/events/index");
  }

  /**
   * view 
   * 
   * @param mixed $id 
   * @access public
   * @return void
   */
  function view ($id) {
    $courseId = $this->Session->read('ipeerSession.courseId');

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);
    $this->set('event_id', $id);

		$this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Events');

		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id),
                                               'contain' => array('Group.Member')));

    //Format Evaluation Selection Boxes
    $default = null;
    $model = '';
    $eventTemplates = array();
    $templateId = $event['Event']['event_template_type_id'];
    if (!empty($templateId))
    {
      $eventTemplateType = $this->EventTemplateType->find('id = '.$templateId);

      if ($templateId == 1 )
      {
        $default = 'Default Simple Evaluation';
        $model = 'SimpleEvaluation';
        $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'));
      }
      else if ($templateId == 2)
      {
        $default = 'Default Rubric';
        $model = 'Rubric';
	      $eventTemplates = $this->Rubric->getBelongingOrPublic($this->Auth->user('id'));
      }
      else if ($templateId == 4)
      {
        $default = 'Default Mixed Evaluation';
        $model = 'Mixeval';
        $eventTemplates = $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'));
      }

    }

    $this->set('event', $event);
    $this->set('course_id', $courseId);
    $this->set('eventTemplates', $eventTemplates);
    $this->set('default',$default);
    $this->set('model', $model);


    //Get all display event types
    //$eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
    $eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
	  $this->set('eventTypes', $eventTypes);
		$this->render();
  }

  function eventTemplatesList($templateId = 1)
  {
  	  $currentUser = $this->User->getCurrentLoggedInUser();
      $this->layout = 'ajax';
      //$conditions = null;
      $eventTemplates = array();
      $default = null;
      $model = '';
      if (!empty($templateId))
      {
        $eventTemplateType = $this->EventTemplateType->find('id = '.$templateId);

        if ($templateId == 1 )
        {
          $default = 'Default Simple Evaluation';
          $model = 'SimpleEvaluation';
          $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($currentUser['id']);
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
          $eventTemplates = $this->Rubric->getBelongingOrPublic($currentUser['id']);
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
          $eventTemplates = $this->Mixeval->getBelongingOrPublic($currentUser['id']);
        }

      }    
      
      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);
  }


  function add()
  {
    $eventTypes = $this->EventTemplateType->find('list', array(
          'conditions'=> array('EventTemplateType.display_for_selection'=>1)
    ));
    //Set up user info
    $currentUser = $this->User->getCurrentLoggedInUser();
    $this->set('currentUser', $currentUser);
    
    $courseId = $this->Session->read('ipeerSession.courseId');
    $this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Events');
    //List Add Page
    if (empty($this->params['data'])) {

      $unassignedGroups = $this->Group->find('list', array('conditions'=> array('course_id'=>$courseId), 'fields'=>array('group_name')));
      $this->set('unassignedGroups', $unassignedGroups);

      //Get all display event types
      $this->set('eventTypes', $eventTypes);
      //Set default template
      $default = '-- Select a Evaluation Tool -- ';
      $model = 'SimpleEvaluation';
      $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'));
      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);
    }
		else {
			//Format Data
			$this->params['data']['Event']['course_id'] = $courseId;
			$this->params = $this->Event->prepData($this->params);
			//print_r($this->params['data']);

      // uniqueness check for title
      if(!$this->Event->__checkDuplicateTitle($this->params['data']['Event']['title']))
      {
        $this->Event->invalidate('title_unique');
      }

      //Save Data
      if ($this->Event->save($this->params['data'])) {
        //Save Groups for the Event
        $this->GroupEvent->insertGroups($this->Event->id, $this->params['data']['Member']);
        $this->redirect('/events/index/The event is added successfully.');
      }
      //Found error
      else {
        $this->set('data', $this->params['data']);
        $this->set('courseId', $courseId);
        $this->set('unassignedGroups', $this->Group->find('list', array(
            'conditions'=> array('course_id'=>$courseId),
            'fields'=>array('group_name'))));
        $this->set('eventTypes', $eventTypes);
        //Set default template
        $default = 'Default Simple Evaluation';
        //Check the event template type
        $model = $this->EventTemplateType->find('first', array(
            'conditions' => array('EventTemplateType.id' => $this->params['data']['Event']['event_template_type_id']),
            'fields' => array('model_name')
        ));
        $model = $model['EventTemplateType']['model_name'];
        
        $eventTemplates = $this->$model->getBelongingOrPublic($this->Auth->user('id'));
        $this->set('eventTemplates', $eventTemplates);
        $this->set('default',$default);
        $this->set('model', $model);

        $this->set('errmsg', 'Please correct errors below.');
        $this->render();
      }//end if
		}
  }


  function edit($id) {
  	
    if(!is_numeric($id)) {
      $this->Session->setFlash('Invalid survey ID.');
      $this->redirect('index');
    }
    $data = $this->Event->find('first', array('conditions' => array('id' => $id),
                                               'contain' => array('Group')));

      $courseId = $this->Session->read('ipeerSession.courseId');

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);
        $this->set('event_id', $id);

		$this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Events');

		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id),
                                               'contain' => array('Group.Member')));

    //Format Evaluation Selection Boxes
    $default = null;
    $model = '';
    $eventTemplates = array();
    $templateId = $event['Event']['event_template_type_id'];
    $eventTypes = $this->EventTemplateType->find('list', array(
          'conditions'=> array('EventTemplateType.display_for_selection'=>1)
    ));
    if (!empty($templateId))
    {
      $eventTemplateType = $this->EventTemplateType->find('id = '.$templateId);

      if ($templateId == 1 )
      {
        $default = 'Default Simple Evaluation';
        $model = 'SimpleEvaluation';
        $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->Auth->user('id'));
      }
      else if ($templateId == 2)
      {
        $default = 'Default Rubric';
        $model = 'Rubric';
	      $eventTemplates = $this->Rubric->getBelongingOrPublic($this->Auth->user('id'));
      }
      else if ($templateId == 4)
      {
        $default = 'Default Mixed Evaluation';
        $model = 'Mixeval';
        $eventTemplates = $this->Mixeval->getBelongingOrPublic($this->Auth->user('id'));
      }

    }
  	// Sets up the already assigned groups
    $assignedGroupIds = $this->GroupEvent->getGroupListByEventId($id);
    $assignedGroups=array();
    foreach($assignedGroupIds as $groups) {
    	$groupId = $groups['GroupEvent']['group_id'];
    	$groupName = $this->Group->getGroupByGroupId($groupId, array('group_name'));
    	$assignedGroups[$groupId] = $groupName[0]['Group']['group_name'];
    }
    $this->set('eventTypes', $eventTypes);
    $this->set('assignedGroups', $assignedGroups);
    $this->set('data', $data); 
    $this->set('event', $event);
    $this->set('course_id', $courseId);
    $this->set('courses', $this->Course->getCourseList());
    $this->set('eventTemplates', $eventTemplates);
    $this->set('default',$default);
    $this->set('model', $model);
    $this->set('id', $id);
    $courseId = $data['Event']['course_id'];
		$this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Events');
$forsave =array();

		if (!empty($this->data)) {
	$this->data['Event']['id'] = $id;
			if($result = $this->Event->save($this->data)) {
				 //Save Groups for the Event
		//$this->GroupEvent->insertGroups($this->Event->id, $this->data['Member']);
		$this->GroupEvent->updateGroups($this->Event->id, $this->data['Member']);
        $this->Session->setFlash('The event was edited successfully.');
        $this->redirect('index');
			} else {
        $this->Session->setFlash($this->Survey->errorMessage);
        $this->render('edit');
			}
		} else {
      $this->data = $data;
        }
        
    $this->set('eventTemplateTypes', $this->EventTemplateType->find('list', array('conditions' => array('NOT' => array('id' => 3)))));
    $this->set('unassignedGroups', $this->Event->getUnassignedGroups($data));
    $this->set('courses', $this->Course->find('list', array('recursive' => -1)));
    $this->set('course_id', $courseId);

    /*
		if (empty($this->params['data']))
		{
			$event = $this->Event->read(null, $id);
			$this->data = $event;
			$this->Output->br2nl($this->data);

      $assignedGroupIDs = $this->GroupEvent->find('all','event_id = '.$id);
//$a=print_r($assignedGroupIDs,true);
//print "<pre>($a)</pre>";
			$groupIDs = '';
			$groupIDSQL = '';
			if (!empty($assignedGroupIDs))
			{

  			// retrieve string of group ids
    		for ($i = 0; $i < count($assignedGroupIDs); $i++) {
    			$groupIDs .= $assignedGroupIDs[$i]['GroupEvent']['group_id']. ":";
    			$groupIDSQL .= $assignedGroupIDs[$i]['GroupEvent']['group_id'];
    			if ($i != count($assignedGroupIDs) -1 ) {
    			  $groupIDs .= ":";
    			  $groupIDSQL .= ",";
    			}
    		}
			  $assignedGroups = $this->Group->find('all','id IN ('.$groupIDSQL.')');

			  $this->set('assignedGroups', $assignedGroups);

  			$unassignedGroups = $this->Group->find('all','course_id='.$courseId.' AND id NOT IN ('.$groupIDSQL.')');
  			$this->set('unassignedGroups', $unassignedGroups);

    	}else {
   			$unassignedGroups = $this->Group->find('all','course_id = '.$courseId);
  			$this->set('assignedGroups', $assignedGroups);
        $this->set('unassignedGroups', $unassignedGroups);

    	}
		  $this->set('groupIDs', $groupIDs);


      //Format Evaluation Selection Boxes
      $default = null;
      $model = '';
      $eventTemplates = array();

      $templateId = $this->data['Event']['event_template_type_id'];
      if (!empty($templateId))
      {
        $eventTemplateType = $this->EventTemplateType->find('id = '.$templateId);

        if ($templateId == 1 )
        {
          $default = 'Default Simple Evaluation';
          $model = 'SimpleEvaluation';
          $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->rdAuth->id);
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
		      $eventTemplates = $this->Rubric->getBelongingOrPublic($this->rdAuth->id);
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
		      $eventTemplates = $this->Mixeval->getBelongingOrPublic($this->rdAuth->id);
        }

      }
      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);


 	    //Get all display event types
 	    $eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
		  $this->set('eventTypes', $eventTypes);

		} else {
			//Format Data
			$this->params['data']['Event']['course_id'] = $courseId;
			$this->params = $this->Event->prepData($this->params);

			$this->Output->filter($this->params['data']);//always filter

			if ( $this->Event->save($this->params['data']))
			{
        //Save Groups for the Event
			  $this->GroupEvent->updateGroups($this->Event->id, $this->params['data']['Event']);

				$this->redirect('/events/index/The event is updated successfully.');
			}//Error Found
			else
			{
			  $this->Output->br2nl($this->params['data']);
        $this->set('data', $this->params['data']);

				$unassignedGroups = $this->Group->find('all','course_id = '.$courseId);
				$this->set('unassignedGroups', $unassignedGroups);
				$eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
		    $this->set('eventTypes', $eventTypes);

        //Validate the error why the Event->save() method returned false
        $this->validateErrors($this->Event);
        $this->set('errmsg', $this->Event->errorMessage);
			}
		}*/
  }

  /**
   * Enter description here...
   *
   * @return
   */
  function editOld ($id=null)
  {

    $courseId = $this->rdAuth->courseId;

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);


		$this->set('title_for_layout', $this->sysContainer->getCourseName($courseId).' > Events');
		if (empty($this->params['data']))
		{

			$this->Event->id = $id;
			$event = $this->Event->read();
			$this->params['data'] = $event;
			$this->Output->br2nl($this->params['data']);

			//$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($id);
			// No need to use getGroupIDsByEventId method, can call Cake's findAllBy... directly
                        //$assignedGroupIDs =    $this->GroupEvent->findAllByEvent_id($id);
                     $assignedGroupIDs =    $this->GroupEvent->find('all',"event_id=$id",null,null,null,1,FALSE);
			//$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($id);
//$a=print_r($assignedGroupIDs,true);
//print "<pre>($a)</pre>";

			$groupIDs = '';
			$groupIDSQL = '';

			if (!empty($assignedGroupIDs))
			{

  			// retrieve string of group ids
    		for ($i = 0; $i < count($assignedGroupIDs); $i++) {
    			$groupIDs .= $assignedGroupIDs[$i]['GroupEvent']['group_id']. ":";
    			$groupIDSQL .= $assignedGroupIDs[$i]['GroupEvent']['group_id'];
    			if ($i != count($assignedGroupIDs) -1 ) {
    			  $groupIDs .= ":";
    			  $groupIDSQL .= ",";
    			}
    		}
			  $assignedGroups = $this->Group->find('all','id IN ('.$groupIDSQL.')');

			  $this->set('assignedGroups', $assignedGroups);

  			$unassignedGroups = $this->Group->find('all','course_id='.$courseId.' AND id NOT IN ('.$groupIDSQL.')');
  			$this->set('unassignedGroups', $unassignedGroups);

    	}else {
   			$unassignedGroups = $this->Group->find('all','course_id = '.$courseId);
  			$this->set('unassignedGroups', $unassignedGroups);

    	}
		  $this->set('groupIDs', $groupIDs);


      //Format Evaluation Selection Boxes
      $default = null;
      $model = '';
      $eventTemplates = array();

      $templateId = $this->params['data']['Event']['event_template_type_id'];
      if (!empty($templateId))
      {
        $eventTemplateType = $this->EventTemplateType->find('id = '.$templateId);

        if ($templateId == 1 )
        {
          $default = 'Default Simple Evaluation';
          $model = 'SimpleEvaluation';
          $eventTemplates = $this->SimpleEvaluation->getBelongingOrPublic($this->rdAuth->id);
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
		      $eventTemplates = $this->Rubric->getBelongingOrPublic($this->rdAuth->id);
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
		      $eventTemplates = $this->Mixeval->getBelongingOrPublic($this->rdAuth->id);
        }

      }


      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);


 	    //Get all display event types
 	    $eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
		  $this->set('eventTypes', $eventTypes);

			$this->render();
		}
		else
		{
			//Format Data
			$this->params['data']['Event']['course_id'] = $courseId;
			$this->params = $this->Event->prepData($this->params);

			$this->Output->filter($this->params['data']);//always filter

			if ( $this->Event->save($this->params['data']))
			{
        //Save Groups for the Event
			  $this->GroupEvent->updateGroups($this->Event->id, $this->params['data']['Event']);

				$this->redirect('/events/index/The event is updated successfully.');
			}//Error Found
			else
			{
			  $this->Output->br2nl($this->params['data']);
        $this->set('data', $this->params['data']);

				$unassignedGroups = $this->Group->find('all','course_id = '.$courseId);
				$this->set('unassignedGroups', $unassignedGroups);
				$eventTypes = $this->EventTemplateType->find('all', array('conditions'=>array('display_for_selection'=>1)));
		    $this->set('eventTypes', $eventTypes);

        //Validate the error why the Event->save() method returned false
        $this->validateErrors($this->Event);
        $this->set('errmsg', $this->Event->errorMessage);
			}
		}
  }

  /**
   * Enter description here...
   *
   * @return
   */
  function delete ($id=null)
  {
    if (isset($this->params['form']['id']))
    {
      $id = intval(substr($this->params['form']['id'], 5));
      //var_dump($id);
    }   //end if
    if ($this->Event->delete($id)) {
			$this->redirect('/events/index/The event is deleted successfully.');
    }
  }

  function search()
  {
      $this->layout = 'ajax';
      $courseId = $this->rdAuth->courseId;
      $conditions = 'course_id = '.$courseId;

      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      	$personalizeData = $this->Personalize->find('all','user_id = '.$this->rdAuth->id);
      	if ($personalizeData) {
      	   $this->userPersonalize->setPersonalizeList($personalizeData);
           $this->show = $this->userPersonalize->getPersonalizeValue('Event.ListMenu.Limit.Show');
           $this->set('userPersonalize', $this->userPersonalize);
      	}
      }

      if (!empty($this->params['form']['livesearch2']) && !empty($this->params['form']['select']))
      {
        $pagination->loadingId = 'loading';
        //parse the parameters
        $searchField=$this->params['form']['select'];
        $searchValue=$this->params['form']['livesearch2'];
        $conditions .= ' AND '.$searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
      }
      $this->update($attributeCode = 'Event.ListMenu.Limit.Show',$attributeValue = $this->show);
      $this->set('conditions',$conditions);
  }

  function checkDuplicateTitle()
  {
      $this->layout = 'ajax';
      $this->render('checkDuplicateTitle');
  }



    /**
   * Enter description here...
   *
   * @return
   */
  function viewGroups ($eventId)
  {
    $this->layout = 'pop_up';

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($eventId);

    $this->set('event_id', $id);
    $this->set('assignedGroups', $this->getAssignedGroups($eventId));
	}

  function editGroup($groupId, $eventId, $popup)
  {
		if(isset($popup) && $popup == 'y')
			$this->layout = 'pop_up';

	    $courseId = $this->rdAuth->courseId;

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($groupId);
 		$event_id = $this->Sanitize->paranoid($eventId);
 		$this->set('event_id', $event_id);
    	$this->set('group_id', $id);
   		$this->set('popup', $popup);

		// gets all students not listed in the group for unfiltered box
		//$this->set('user_data', $this->Group->groupDifference($id,$courseId));

		// gets all students in the group
		$this->set('group_data', $this->Group->getMembersByGroupId($id));

		if (empty($this->params['data']))
		{
			$this->Group->id = $id;
			$this->params['data'] = $this->Group->read();
		}
		else
		{
			$this->params = $this->Group->prepData($this->params);

			if ( $this->Group->save($this->params['data']))
			{
				$this->GroupsMembers->updateMembers($this->Group->id, $this->params['data']['Group']);

				if(isset($popup) && $popup == 'y')
					$this->flash('Group Updated', '/events/viewGroups/'.$event_id, 1);
				else
					$this->flash('Group Updated', '/events/view/'.$event_id, 1);
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}

	function getAssignedGroups($eventId=null)
	{
 		$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($eventId);
                $assignedGroups = array();

                            if (!empty($assignedGroupIDs))
                            {

                                    // retrieve string of group ids
                            for ($i = 0; $i < count($assignedGroupIDs); $i++) {
                                    $group = $this->Group->find('first',array('conditions' => array('Group.id' => $assignedGroupIDs[$i]['GroupEvent']['group_id'])));
                                    //$students = $this->GroupsMembers->getMembersByGroupId($assignedGroupIDs[$i]['GroupEvent']['group_id']);
                                    $assignedGroups[$i] = $group['Group'];
                                    $assignedGroups[$i]['Member']=$group['Member'];
                            }
                }

                return $assignedGroups;
	}

	function update($attributeCode='',$attributeValue='')
  {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
  		$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
  }
}
?>