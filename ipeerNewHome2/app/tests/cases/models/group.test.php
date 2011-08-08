<?php
App::import('Model', 'Group');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class GroupTestCase extends CakeTestCase {
  var $name = 'Group';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey'
                       );
  var $Group = null;

  function startCase() {
		$this->Group = ClassRegistry::init('Group');
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

  // Run before EVERY test.
  function startTest($method) {
    // extra setup stuff here
  }
	
  function endTest($method) {
  }

  function testGroupInstance() {
    
    $this->assertTrue(is_a($this->Group, 'Group'));
  }
	
  function testGetCourseGroupCount() {
  	
    $empty = null;  	    
  	
    // Test valid course id      
    $group= $this->Group->getCourseGroupCount(1);
  	$this->assertEqual($group, 4);

  	// Test course with no groups
  	$group= $this->Group->getCourseGroupCount(3);
  	$this->assertEqual($group, 0);
  	
  	// Test invalid course id
  	$group= $this->Group->getCourseGroupCount(999);
  	$this->assertEqual($group, $empty);
  	
  }	
	
  // Function is not used anywhere
  function testPrepDataImport() {  	
  	
  }
  
  function testGetLastGroupNumByCourseId() {  
  
    $empty = null;  	
    
    // Test valid course
    $group = $this->Group->getLastGroupNumByCourseId(1);
  	$this->assertEqual($group, 4);
  	
  	// Test course with no groups;
  	$group = $this->Group->getLastGroupNumByCourseId(3);
  	$this->assertEqual($group, $empty);

  	// Test course with no groups;
  	$group = $this->Group->getLastGroupNumByCourseId(3);
  	$this->assertEqual($group, $empty);  	      
  }
  
  function testGetStudentsNotInGroup() {  
  
  	$empty = null;  	
  	
  	// Test group with some students in it
  	$students = $this->Group->getStudentsNotInGroup(4);
  	$this->assertEqual($this->toNameArray($students), array('StudentY'));
  	
  	 // Test group with all students in it
  	$students = $this->Group->getStudentsNotInGroup(1);
  	$this->assertEqual($this->toNameArray($students), $empty);
  	
    // Test group with no students in it
  	$students = $this->Group->getStudentsNotInGroup(3);
  	$this->assertEqual($this->toNameArray($students), array('StudentZ', 'StudentY'));
  	
  	// Test invalid group
  	$students = $this->Group->getStudentsNotInGroup(999);
  	$this->assertEqual($students, $empty);
  }
    
  function testGetMembersByGroupId() {
  	
  	$empty = null;  
  	
  	// Test group with students in it
  	$students = $this->Group->getMembersByGroupId(1);
  	$this->assertEqual($this->toNameArray($students), array('StudentZ','StudentY'));  

  	// Test group with no students in it
  	$students = $this->Group->getMembersByGroupId(3);
  	$this->assertEqual($students, $empty);
  	
	  // Test invalid group
  	$students = $this->Group->getMembersByGroupId(999);
  	$this->assertEqual($students, $empty);
  	  	
  }  
  
  function testGetGroupByGroupId() {
  	
  	$empty = null;  
  	
  	// Test valid group
  	$group = $this->Group->getGroupByGroupId(1);
  	$this->assertEqual($group[0]['Group']['group_name'], 'group1');
  	
  	// Test invalid group
  	$group = $this->Group->getGroupByGroupId(999);
  	$this->assertEqual($group, $empty);
  	
  }
  
  function testGetGroupsByCouseId(){
  	
  	$empty=null;
  	
  	// Test valid course with groups
  	$groups = $this->Group->getGroupsByCouseId(1);
  	$this->assertEqual($groups, array(1=>1,2=>2,3=>3,4=>4));
  	
  	// Test valid course with no groups
  	$groups = $this->Group->getGroupsByCouseId(2);
  	$this->assertEqual($groups, $empty);

  	// Test invalid course
  	$groups = $this->Group->getGroupsByCouseId(999);
  	$this->assertEqual($groups, $empty);  	
  	
  }

#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################
	
	
	function toNameArray($students){
		$nameArray = array();
			foreach ($students as $student){
			array_push($nameArray, $student['Member']['username']); 
		}
		return $nameArray;
	}
}		
	
?>
