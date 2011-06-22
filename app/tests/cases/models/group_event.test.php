<?php
App::import('Model', 'GroupEvent');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class GroupEventTestCase extends CakeTestCase {
  var $name = 'GroupEvent';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $GroupEvent = null;

  function startCase() {
		$this->GroupEvent = ClassRegistry::init('GroupEvent');
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
    $this->assertTrue(is_a($this->GroupEvent, 'GroupEvent'));
  }

  function testGetGroupIDsByEventId() {
  	
  	//Test valid event with groups
  	$groups = $this->GroupEvent->getGroupIDsByEventId(1);
    $groups = $this->toArray($groups);
    $this->assertEqual($groups, array(1,2)); 

    //Test valid event with no groups
  	$groups = $this->GroupEvent->getGroupIDsByEventId(3);
    $this->assertEqual($groups, null); 
    
    //Test invalid event
  	$groups = $this->GroupEvent->getGroupIDsByEventId(999);
    $this->assertEqual($groups, null);     
    
  }

  function testGetGroupListByEventId() {
  		
    //Test valid event with groups
  	$groups = $this->GroupEvent->getGroupListByEventId(1);
    $groups = $this->toArray($groups);
    $this->assertEqual($groups, array(1,2)); 

    //Test valid event with no groups
  	$groups = $this->GroupEvent->getGroupListByEventId(3);
    $this->assertEqual($groups, null); 
    
    //Test invalid event
  	$groups = $this->GroupEvent->getGroupListByEventId(999);
    $this->assertEqual($groups, null);      	

  }

  function testGetGroupEventByEventIdGroupId() {
  	
  	//Test valid event and valid group
  	$group = $this->GroupEvent->getGroupEventByEventIdGroupId(1,1);
  	$this->assertEqual($group['GroupEvent']['group_id'], '1');
  	$this->assertEqual($group['GroupEvent']['event_id'], '1'); 	
  	  	
  	//Test valid event and not related valid group
  	$group = $this->GroupEvent->getGroupEventByEventIdGroupId(1,3);
  	$this->assertEqual($group, null);
  	
  	//Test valid event and invalid group
  	$group = $this->GroupEvent->getGroupEventByEventIdGroupId(1,999);
  	$this->assertEqual($group, null);
  	
    //Test invalid event and valid group
  	$group = $this->GroupEvent->getGroupEventByEventIdGroupId(999,1);
  	$this->assertEqual($group, null);
  	
  	    //Test invalid event and invalid group
  	$group = $this->GroupEvent->getGroupEventByEventIdGroupId(999,999);
  	$this->assertEqual($group, null); 	
  	
  }
  
  function testGetGroupEventByUserId() {
  	
  	//Test valid user in group
	$groups = $this->GroupEvent->getGroupEventByUserId(3, 1);
	$groups = $this->toArray($groups);
	$this->assertEqual($groups, array(1,2));
	
  	//Test valid user not in group
	$groups = $this->GroupEvent->getGroupEventByUserId(1, 1);
	$groups = $this->toArray($groups);
	$this->assertEqual($groups, null);	
	
	//Test invalid user
	$groups = $this->GroupEvent->getGroupEventByUserId(999, 1);
	$groups = $this->toArray($groups);
	$this->assertEqual($groups, null);	

	//Test invalid event
	$groups = $this->GroupEvent->getGroupEventByUserId(3, 999);
	$groups = $this->toArray($groups);
	$this->assertEqual($groups, null);  	
  	
  }
  
  /*
   * Deprecated
   *
   */
  
  function testGetLowMark() {
    
  }

  function testGetNotReviewed() {

  	//Test event with not reviewed
  	$events = $this->GroupEvent->getNotReviewed(1);
  	$events = $this->toIdArray($events);
  	$this->assertEqual($events, array(2)); 	
  	
  	//Test event with reviewed only
  	$events = $this->GroupEvent->getNotReviewed(2);
  	$events = $this->toIdArray($events);
  	$this->assertEqual($events, null); 
  	
  	//Test invalid event
  	$events = $this->GroupEvent->getNotReviewed(999);
  	$events = $this->toIdArray($events);
  	$this->assertEqual($events, null); 
  	
  }

  function testGetLateGroupMembers() {

  	$events = $this->GroupEvent->getLateGroupMembers(1);
    $this->assertEqual($events, 1);
  	
  }

  function testGetLate() {

  	//Test events with late groups
  	$events = $this->GroupEvent->getLate(1);
  	$events = $this->toIdArray($events);
  	$this->assertEqual($events, array(1,2)); 

  	//Test events with no late groups
  	$events = $this->GroupEvent->getLate(2);
  	$this->assertEqual($events, null); 

  	//Test invalid event
  	$events = $this->GroupEvent->getLate(999);
  	$events = $this->toIdArray($events);
  	$this->assertEqual($events, null);   	
  	
  }
  
  function testGetGroupEventByEventId() {

  	//Test valid event
  	$groups = $this->GroupEvent->getGroupEventByEventId(1);
  	$groups = $this->toIdArray($groups);
  	$this->assertEqual($groups, array(1,2));

  	//Test valid event with no groups
  	$groups = $this->GroupEvent->getGroupEventByEventId(3);
  	$this->assertEqual($groups, null);
  	  	
  	//Test invalid event
  	$groups = $this->GroupEvent->getGroupEventByEventId(999);
  	$this->assertEqual($groups, null);  	
  	  	
  }
  
  function testGetGroupEventByEventIdAndGroupId() {
  	
  	//Test valid event and valid group
  	$group = $this->GroupEvent->getGroupEventByEventIdAndGroupId(2,1);
  	$this->assertEqual($group, 3);
  	
  	//Test valid event and valid group not in group-event
  	$group = $this->GroupEvent->getGroupEventByEventIdAndGroupId(3,3);
  	$this->assertEqual($group, null);

  	//Test invalid event and valid group
  	$group = $this->GroupEvent->getGroupEventByEventIdAndGroupId(999,1);
  	$this->assertEqual($group, null);  	
  	
  	//Test valid event and invalid group
  	$group = $this->GroupEvent->getGroupEventByEventIdAndGroupId(1,999);
  	$this->assertEqual($group, null); 

  	//Test invalid event and invalid group
  	$group = $this->GroupEvent->getGroupEventByEventIdAndGroupId(999,9);
  	$this->assertEqual($group, null);   	

  }	
  
#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################

  function deleteAllTuples($table){

		$this->GroupEvent= & ClassRegistry::init('GroupEvent');
		$sql = "DELETE FROM $table";
		$this->GroupEvent->query($sql);
	}
	
	function flushDatabase(){
			
		$this->deleteAllTuples('courses');
		$this->deleteAllTuples('users');
		$this->deleteAllTuples('user_courses');
		$this->deleteAllTuples('user_enrols');
		$this->deleteAllTuples('roles_users');
		$this->deleteAllTuples('groups');
		$this->deleteAllTuples('groups_members');
		$this->deleteAllTuples('group_events');
		
	}
	
	
	function toArray($groups){
		$nameArray = array();
			foreach ($groups as $group){
			array_push($nameArray, $group['GroupEvent']['group_id']); 
		}
		return $nameArray;
	}
	
function toIdArray($groups){
		$nameArray = array();
			foreach ($groups as $group){
			array_push($nameArray, $group['GroupEvent']['id']); 
		}
		return $nameArray;
	}
}		
	
?>
