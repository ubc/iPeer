<?php
App::import('Model', 'Event');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EventTestCase extends CakeTestCase {
  var $name = 'Event';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $Course = null;

  function startCase() {
		$this->Event = ClassRegistry::init('Event');
    $admin = array('User' => array('username' => 'root',
                                   'password' => 'ipeer'));
    $this->controller = new FakeController();
    $this->controller->constructClasses();
    $this->controller->startupProcess();
    $this->controller->Component->startup($this->controller);
    $this->controller->Auth->startup($this->controller);
    ClassRegistry::addObject('view', new View($this->Controller));
    ClassRegistry::addObject('auth_component', $this->controller->Auth);

    $this->controller->Auth->login($admin);
  }

  function endCase() {
    $this->controller->Component->shutdown($this->controller);
    $this->controller->shutdownProcess();
  }

  //Run before EVERY test.
  function startTest($method) {
    // extra setup stuff here
  }
	
  function endTest($method) {
		$this->flushDatabase();
  }

  function testCourseInstance() {
    $this->assertTrue(is_a($this->Event, 'Event'));
  }

  function testGetCourseEvent(){

  	$empty = null;
  	
  	$this->Event = & ClassRegistry::init('Event');
  	
  	//Test a valid course number  	
  	$course = $this->Event->getCourseEvent(2);  	
    $events = $this->toEventNameArray($course);
  	$this->assertEqual($events, array('Event3', 'Event4'));
  	$this->assertEqual($course[0]['Event']['title'], 'Event3');
  	$this->assertEqual($course[1]['Event']['title'], 'Event4');  	
  	
  	//Test an invalid course number  	
  	$course = $this->Event->getCourseEvent(999);
  	$this->assertEqual($course, $empty);
  }
 
  
  function testGetCourseEvalEvent() {
      	
   	$empty = null;
  	 $this->Event = & ClassRegistry::init('Event');
  	
  	//Test a valid course number
  	
  	$course = $this->Event->GetCourseEvalEvent(2);
  	$events = $this->toEventNameArray($course);
  	$this->assertEqual($events, array('Event3'));
  	//Test an invalid course number
  	
  	$course = $this->Event->GetCourseEvalEvent(999);
  	$this->assertEqual($course, $empty);  	
  	
  }
  
  function testGetCourseEventCount() {

 	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
 	  	
  	//Test a valid course number  	
  	$course = $this->Event->getCourseEventCount(2);
  	$this->assertEqual($course, 2);
  	
  	//Test an invalid course number  	
  	$course = $this->Event->getCourseEventCount(999);
   	$this->assertEqual($course, 0);  	  	
  }
  
  function testGetCourseByEventId() {
  	
  	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
 	  	
  	//Test a valid event number  	
  	$course = $this->Event->getCourseByEventId(1);
  	$this->assertEqual($course, 1);
  	
  	//Test an invalid event number  	
  	$course = $this->Event->getCourseEventCount(999);
   	$this->assertEqual($course, 0);  	  	
  	
  }
  
  function testGetSurveyEventIdByCourseIdDescription() {
  	
  	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
 	  	
  	//Test a valid course number  	
  	$event = $this->Event->getSurveyEventIdByCourseIdDescription(2, 'Event4');
  	$this->assertEqual($event['Event']['id'], 4);
  	
  	//Test an invalid course number  	
  	$course = $this->Event->getSurveyEventIdByCourseIdDescription(999, 'Event4');
   	$this->assertEqual($course, $empty);  	

   	//Test an invalid event name 	
  	$course = $this->Event->getSurveyEventIdByCourseIdDescription(2, 'invalidName');
   	$this->assertEqual($course, $empty);  
  	  	
  } 
 
   function testGetActiveSurveyEvents() {
      	
   	  	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
 	  	
  	//Test a valid course number  	
  	$event = $this->Event->getActiveSurveyEvents(2);
  	$events = $this->toEventNameArray($event);
  	$this->assertEqual($events, array('Event4'));

  	//Test a valid course with one inactive survey
  	$event = $this->Event->getActiveSurveyEvents(3);
  	$events = $this->toEventNameArray($event);
  	$this->assertEqual($events, array('Event5'));  	
  	
  	//Test invalid course
  	$event = $this->Event->getActiveSurveyEvents(4);
  	$this->assertEqual($event, $empty);  	
   }
  
/*
 * this function is not used anywhere 
 * 
 */   
   function testRemoveEventsBySurveyId() {

   }

   
  function testGetUnassignedGroups() {
  	  	
  	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
  	
  	//Test valid event without group assigned
  	
  	$event= $this->Event->getCourseEvent(1);
  	$groups = $this->Event->getUnassignedGroups($event[0]);
  	
  	$groups= $this->toGroupArray($groups);
	$this->assertEqual($groups, array('group1', 'group2', 'group3'));	
	
	//Test valid event with a group assigned
  	
  	$event= $this->Event->getCourseEvent(1);
  	$groups = $this->Event->getUnassignedGroups($event[0], array(1));
  	
  	$groups= $this->toGroupArray($groups);
	$this->assertEqual($groups, array('group2', 'group3'));		
       	
	//Test invalid event id
	$event= $this->Event->getCourseEvent(999);
	$this->assertEqual($event, $empty);		
	
    //Test invalid event id
  	$event= $this->Event->getCourseEvent(1);
  	$groups = $this->Event->getUnassignedGroups($event[0], 999);
  	
  	$groups= $this->toGroupArray($groups);
	$this->assertEqual($groups, array('group1', 'group2', 'group3'));	
  }
   
  function testGetEventById() {
  	
    $empty = null;
  	$this->Event = & ClassRegistry::init('Event');
   	
  	//Test valid event
  	$event = $this->Event->getEventById(1);
  	$this->assertEqual($event['Event']['title'], 'Event1');
  	
  	//Test invalid event
  	$event = $this->Event->getEventById(999);
  	$this->assertEqual($event, $empty);  	
  	 	
  }
  
  
  function testGetEventTemplateTypeId() {
    $empty = null;
  	$this->Event = & ClassRegistry::init('Event');
  	
  	//Test simple eval events
  	$id = $this->Event->getEventTemplateTypeId(2);
  	$this->assertEqual($id, 1);
  	
  	//Test rubric events
  	$id = $this->Event->getEventTemplateTypeId(1);
  	$this->assertEqual($id, 2);

  	//Test survey eval events
  	$id = $this->Event->getEventTemplateTypeId(4);
  	$this->assertEqual($id, 3);

  	//Test simple eval events
  	$id = $this->Event->getEventTemplateTypeId(7);
  	$this->assertEqual($id, 4);
  	
  	//Test invalid events
  	$id = $this->Event->getEventTemplateTypeId(999);
  	$this->assertEqual($id, $empty);
  	  	
  }
  
  /*
   * Function is not used anywhere
   * 
   */
  
 function testFormatEventObj() {
 	
 }
  
 function testGetEventTitleById(){

 	$empty = null;
  	$this->Event = & ClassRegistry::init('Event');
  	
  	//Test valid event
  	$title = $this->Event->getEventTitleById(1);
  	$this->assertEqual($title, 'Event1');
  	
  	//Test invalid event
  	$title = $this->Event->getEventTitleById(999);
  	$this->assertEqual($title, $empty);
 	
 }
 
 
#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################

	
	function deleteAllTuples($table){

		$this->Event= & ClassRegistry::init('Event');
		$sql = "DELETE FROM $table";
		$this->Event->query($sql);
	}
	
	function flushDatabase(){
			
		$this->deleteAllTuples('events');
				$this->deleteAllTuples('courses');
		$this->deleteAllTuples('users');
		$this->deleteAllTuples('user_courses');
		$this->deleteAllTuples('user_enrols');
		$this->deleteAllTuples('roles_users');
		$this->deleteAllTuples('groups');
		$this->deleteAllTuples('groups_members');
	}

	
	function toEventNameArray($events){
		$courseNameArray = array();
			foreach ($events as $event){
			array_push($courseNameArray, $event['Event']['title']); 
		}
		return $courseNameArray;
	}
	
	function toGroupArray($events){
		$groups = array();
			foreach ($events as $event){
			array_push($groups, $event); 
		}
		return $groups;
	}
	
}	
	
?>
