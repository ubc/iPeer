<?php
App::import('Model', 'GroupsMembers');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class GroupsMembersTestCase extends CakeTestCase {
  var $name = 'GroupsMembers';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $GroupsMembers = null;

  function startCase() {
		$this->GroupsMembers = ClassRegistry::init('GroupsMembers');
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
    $this->assertTrue(is_a($this->GroupsMembers, 'GroupsMembers'));
  }	

  function testInsertMembers(){

    // set up test input
    $data = array();
    $data['member_count'] = 2;
    $data['member1'] = 3;
    $data['member2'] = 4;
  
    $this->GroupsMembers->insertMembers(5, $data);
     	// Assert the data was saved in database
  	$searched = $this->GroupsMembers->find('all', array('conditions' => array('group_id' => 5)));
  	$this->assertEqual($searched[0]['GroupsMembers']['group_id'], 5);
  	$this->assertEqual($searched[1]['GroupsMembers']['group_id'], 5);
  	$this->assertEqual($searched[0]['GroupsMembers']['user_id'], 3);
  	$this->assertEqual($searched[1]['GroupsMembers']['user_id'], 4);
  	
  	// Test for incorrect inputs
  
  	$incorrectResult = $this->GroupsMembers->insertMembers(5, null);
  	$this->assertFalse($incorrectResult);

  }
  
  function testUpdateMembers(){  

    $data = array();    
    $data['member_count'] = 1;
    $data['member1'] = 2;
    
    $this->GroupsMembers->updateMembers(2, $data);
    $searched = $this->GroupsMembers->find('all', array('conditions' => array('group_id' => 2)));
  	$this->assertEqual($searched[0]['GroupsMembers']['group_id'], 2);
  	$this->assertEqual($searched[0]['GroupsMembers']['user_id'], 2);
  	$this->assertEqual(sizeof($searched), 1);

  	$incorrectData = $this->GroupsMembers->updateMembers(2, null);
  	$this->assertFalse($incorrectData);
  	
    
  }
  
  function testGetMembers(){
    	
    //Test valid group with members
  	$members = $this->GroupsMembers->getMembers(1);
  	$this->assertEqual($members, array(1=>3,2=>4));  	
  	
    //Test valid group with no members
  	$members = $this->GroupsMembers->getMembers(3);
  	$this->assertEqual($members, null);
  	
    //Test invalid group
  	$members = $this->GroupsMembers->getMembers(999);
  	$this->assertEqual($members, null); 
  	
  }  

  function testCountMembers() {  
  	
  	//Test valid group with members
  	$members = $this->GroupsMembers->countMembers(4);
  	$this->assertEqual($members, 1);
  	
  	//Test valid group with no members
  	$members = $this->GroupsMembers->countMembers(3);
  	$this->assertEqual($members, 0);

  	//Test invalid group
  	$members = $this->GroupsMembers->countMembers(999);
  	$this->assertEqual($members, null);  	
  	  	
  }  
  
  function testGetEventGroupMembers () {
  	
  	//Test group, selfeval  	
  	$members = $this->GroupsMembers->getEventGroupMembers(1, true, 0);
  	$members = $this->toNameArray($members);
  	$this->assertEqual($members, array('StudentY', 'StudentZ'));
  	
  	//Test group, no selfeval, valid used id  	
  	$members = $this->GroupsMembers->getEventGroupMembers(1, false, 3);
  	$members = $this->toNameArray($members);
  	$this->assertEqual($members, array('StudentZ'));  	

  	//Test group, no selfeval, invalid used id  	
  	$members = $this->GroupsMembers->getEventGroupMembers(1, false, 999);
  	$members = $this->toNameArray($members);
  	$this->assertEqual($members, array('StudentY', 'StudentZ')); 	

  	//Test invalid group	
  	$members = $this->GroupsMembers->getEventGroupMembers(999, false, 3);
  	$members = $this->toNameArray($members);
  	$this->assertEqual($members, null); 	
  }   
  
  /*
   * Returns full GroupMembers array instead on group ids only
   * Not used anywhere anyway
   * 
   * */

  function testGetGroupsByUserId(){

  	$groups=$this->GroupsMembers->getGroupsByUserId(3);  	
  	
  }
  
  function testCheckMembershipInGroup(){
  
  	//Test student in existing group
  	$inGroup = $this->GroupsMembers->checkMembershipInGroup(1,3);
  	$this->assertEqual($inGroup, true);
  	
  	//Test student not in existing group
  	$inGroup = $this->GroupsMembers->checkMembershipInGroup(4,3);
  	$this->assertEqual($inGroup, false);  	

  	//Test invalid student in existing group
  	$inGroup = $this->GroupsMembers->checkMembershipInGroup(4,999);
  	$this->assertEqual($inGroup, false); 

  	//Test student in invalid existing group
  	$inGroup = $this->GroupsMembers->checkMembershipInGroup(999,3);
  	$this->assertEqual($inGroup, false); 
  	
    //Test invalid student in invalid existing group
  	$inGroup = $this->GroupsMembers->checkMembershipInGroup(999,999);
  	$this->assertEqual($inGroup, false); 
  }  

  function testGetUserListInGroups(){

    //Test valid group with members
  	$users = $this->GroupsMembers->getUserListInGroups(1);
    $this->assertEqual($users, array(1=>3, 2=>4));
    
    //Test valid group with no members
  	$users = $this->GroupsMembers->getUserListInGroups(3);
    $this->assertEqual($users, null);

    //Test invalid group
  	$users = $this->GroupsMembers->getUserListInGroups(999);
    $this->assertEqual($users, null);    
  	
  }

  
#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################

function deleteAllTuples($table){

		$this->GroupsMembers= & ClassRegistry::init('GroupsMembers');
		$sql = "DELETE FROM $table";
		$this->GroupsMembers->query($sql);
	}
	
	function flushDatabase(){
			
		$this->deleteAllTuples('courses');
		$this->deleteAllTuples('users');
		$this->deleteAllTuples('user_courses');
		$this->deleteAllTuples('user_enrols');
		$this->deleteAllTuples('roles_users');
		$this->deleteAllTuples('groups');
		$this->deleteAllTuples('groups_members');
	}
	
	
	function toNameArray($students){
		$nameArray = array();
			foreach ($students as $student){
			array_push($nameArray, $student['User']['username']); 
		}
		return $nameArray;
	}
}		
	
?>
