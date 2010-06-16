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
	var $uses = array('Course', 'Event', 'EventTemplateType', 'SimpleEvaluation', 'Rubric', 'Mixeval', 'Group', 'GroupEvent', 'Personalize', 'GroupsMembers');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'id': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Events';
		parent::__construct();
	}

  /**
   * Enter description here...
   *
   * @return
   */
  function index ($msg=null)
  {
    $courseId = $this->rdAuth->courseId;
		$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Events';

  	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('Event.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('Event.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'Event.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}

    $conditions = 'course_id = '.$courseId;
  	$data = $this->Event->findAll($conditions, '*, (NOW() >= release_date_begin AND NOW() <= release_date_end) AS is_released', $this->order, $this->show, $this->page);

  	$paging['style'] = 'ajax';
  	$paging['link'] = '/events/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

  	$paging['count'] = $this->Event->findCount($conditions);
  	$paging['show'] = array('10','25','50','all');
  	$paging['page'] = $this->page;
  	$paging['limit'] = $this->show;
  	$paging['direction'] = $this->direction;

  	$this->set('paging',$paging);
  	$this->set('data',$data);
  	$this->set('courseId', $courseId);
  	if (isset($msg)) {
  	 $this->set('message', $msg);
  	}
  }

  /**
   * Enter description here...
   *
   * @return
   */
  function view ($id)
  {
    $courseId = $this->rdAuth->courseId;

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);
    $this->set('event_id', $id);

		$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Events';
		$this->Event->setId($id);


		$this->params['data'] = $this->Event->read();
		//print_r($this->params['data']);
		//$this->Output->filter($this->params['data']);

		$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($id);
		$groupIDs = '';
		$groupIDSQL = '';
		if (!empty($assignedGroupIDs))
		{
		  $assignedGroups = array();

			// retrieve string of group ids
  		for ($i = 0; $i < count($assignedGroupIDs); $i++) {
  			$groupIDs .= $assignedGroupIDs[$i]['GroupEvent']['group_id']. ":";
  			if ($i != count($assignedGroupIDs) -1 ) {
  			  $groupIDs .= ":";
  			}
  			$group = $this->Group->find('id = '.$assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			$students = $this->Group->groupStudents($assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			$assignedGroups[$i] = $group;
  			$assignedGroups[$i]['Group']['Students']=$students;
  		}

		  $this->set('assignedGroups', $assignedGroups);
  	}else {
      $this->set('assignedGroups', array());
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
        $eventTemplates = $this->SimpleEvaluation->findAll();
      }
      else if ($templateId == 2)
      {
        $default = 'Default Rubric';
        $model = 'Rubric';
	      $eventTemplates = $this->Rubric->findAll();
      }
      else if ($templateId == 4)
      {
        $default = 'Default Mixed Evaluation';
        $model = 'MixedEvaluation';
	      //$eventTemplates = $this->Rubric->findAll($conditions=null);
      }

    }
    $this->set('eventTemplates', $eventTemplates);
    $this->set('default',$default);
    $this->set('model', $model);


    //Get all display event types
    $eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
	  $this->set('eventTypes', $eventTypes);
		$this->render();
  }

  /**
   * Enter description here...
   *
   * @return
   */
  function add ()
  {
    $courseId = $this->rdAuth->courseId;
		$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Events';
	  //List Add Page
		if (empty($this->params['data'])) {

			$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
			$this->set('unassignedGroups', $unassignedGroups);

 	    //Get all display event types
 	    $eventTypes = $this->EventTemplateType->findAll(' EventTemplateType.display_for_selection = 1 ');
		  $this->set('eventTypes', $eventTypes);

		  //Set default template
      $default = '-- Select a Evaluation Tool -- ';
      $model = 'SimpleEvaluation';
      $eventTemplates = $this->SimpleEvaluation->findAll();
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
			  $this->GroupEvent->insertGroups($this->Event->id, $this->params['data']['Event']);

				$this->redirect('/events/index/The event is added successfully.');
			}
      //Found error
      else {
        $this->set('data', $this->params['data']);
        $this->set('courseId', $courseId);

				$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
				$this->set('unassignedGroups', $unassignedGroups);
				$eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
		    $this->set('eventTypes', $eventTypes);
  		  //Set default template
        $default = 'Default Simple Evaluation';
        $model = 'SimpleEvaluation';
        $eventTemplates = $this->SimpleEvaluation->findAll();
        $this->set('eventTemplates', $eventTemplates);
        $this->set('default',$default);
        $this->set('model', $model);

        $this->set('errmsg', 'Please correct errors below.');
        $this->render();
      }//end if
		}
  }


  function edit ($id=null)
  {
    $courseId = $this->rdAuth->courseId;
		$unassignedGroups = array();
    $assignedGroups = array();

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);

		$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Events';

		if (empty($this->params['data']))
		{
			$this->Event->setId($id);
			$event = $this->Event->read();
			$this->params['data'] = $event;
			$this->Output->br2nl($this->params['data']);

                     $assignedGroupIDs = $this->GroupEvent->findAllByEvent_id($id);
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
			  $assignedGroups = $this->Group->findAll('id IN ('.$groupIDSQL.')');

			  $this->set('assignedGroups', $assignedGroups);

  			$unassignedGroups = $this->Group->findAll('course_id='.$courseId.' AND id NOT IN ('.$groupIDSQL.')');
  			$this->set('unassignedGroups', $unassignedGroups);

    	}else {
   			$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
  			$this->set('assignedGroups', $assignedGroups);
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
          $eventTemplates = $this->SimpleEvaluation->findAll();
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
		      $eventTemplates = $this->Rubric->findAll();
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
		      $eventTemplates = $this->Mixeval->findAll();
        }

      }
      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);


 	    //Get all display event types
 	    $eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
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

				$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
				$this->set('unassignedGroups', $unassignedGroups);
				$eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
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
  function editOld ($id=null)
  {

    $courseId = $this->rdAuth->courseId;

	  //Clear $id to only the alphanumeric value
		$id = $this->Sanitize->paranoid($id);


		$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Events';
		if (empty($this->params['data']))
		{

			$this->Event->setId($id);
			$event = $this->Event->read();
			$this->params['data'] = $event;
			$this->Output->br2nl($this->params['data']);

			//$assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($id);
			// No need to use getGroupIDsByEventId method, can call Cake's findAllBy... directly 
                        //$assignedGroupIDs =    $this->GroupEvent->findAllByEvent_id($id);
                     $assignedGroupIDs =    $this->GroupEvent->findAll("event_id=$id",null,null,null,1,FALSE);
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
			  $assignedGroups = $this->Group->findAll('id IN ('.$groupIDSQL.')');

			  $this->set('assignedGroups', $assignedGroups);

  			$unassignedGroups = $this->Group->findAll('course_id='.$courseId.' AND id NOT IN ('.$groupIDSQL.')');
  			$this->set('unassignedGroups', $unassignedGroups);

    	}else {
   			$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
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
          $eventTemplates = $this->SimpleEvaluation->findAll();
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
		      $eventTemplates = $this->Rubric->findAll();
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
		      $eventTemplates = $this->Mixeval->findAll();
        }

      }


      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);


 	    //Get all display event types
 	    $eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
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

				$unassignedGroups = $this->Group->findAll('course_id = '.$courseId);
				$this->set('unassignedGroups', $unassignedGroups);
				$eventTypes = $this->EventTemplateType->findAll(' display_for_selection = 1 ');
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
    }   //end if
    if ($this->Event->del($id)) {
			$this->redirect('/events/index/The event is deleted successfully.');
    }
  }

  function search()
  {
      $this->layout = 'ajax';
      $courseId = $this->rdAuth->courseId;
      $conditions = 'course_id = '.$courseId;

      if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      	$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
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

  function eventTemplatesList($templateId = 1)
  {
      $this->layout = 'ajax';
      //$conditions = null;
      $conditions=array('availability'=>'public');
      $modelName = '';
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
          $eventTemplates = $this->SimpleEvaluation->findAll();
        }
        else if ($templateId == 2)
        {
          $default = 'Default Rubric';
          $model = 'Rubric';
		      $eventTemplates = $this->Rubric->findAll($conditions);
        }
        else if ($templateId == 4)
        {
          $default = 'Default Mixed Evaluation';
          $model = 'Mixeval';
		      $eventTemplates = $this->Mixeval->findAll($conditions);
        }

      }
      $this->set('eventTemplates', $eventTemplates);
      $this->set('default',$default);
      $this->set('model', $model);
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
		$this->set('user_data', $this->Group->groupDifference($id,$courseId));

		// gets all students in the group
		$this->set('group_data', $this->Group->groupStudents($id));

		if (empty($this->params['data']))
		{
			$this->Group->setId($id);
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
  			$group = $this->Group->find('id = '.$assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			$students = $this->Group->groupStudents($assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			$assignedGroups[$i] = $group;
  			$assignedGroups[$i]['Group']['Students']=$students;
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