<?php
App::import('Model', 'rubric');
App::import('Controller', 'Rubrics');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class RubricTestCase extends CakeTestCase {
  var $name = 'Rubric';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $Rubric = null;

  function startCase() {
	$this->Rubric = ClassRegistry::init('Rubric');
	$this->RubricsLom = ClassRegistry::init('RubricsLom');
	$this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
	$this->RubricsCriteriaComment = ClassRegistry::init('RubricsCriteriaComment');
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
  }

  function testRubricInstance() {
    $this->assertTrue(is_a($this->Rubric, 'Rubric'));
  }

  function testSaveAllWithCriteriaComment() {
	//Set up test data
	$tmp = $this->setUpRubricsArray();
	// Run the test function
	$this->Rubric->SaveAllWithCriteriaComment($tmp);
	
	// Assert that Rubrics was saved correctly
	$rubric = $this->Rubric->find('first', array('conditions' => array('Rubric.id' => 1)));
	$this->assertEqual($rubric['Rubric']['id'], $tmp['Rubric']['id']);
	$this->assertEqual($rubric['Rubric']['name'], $tmp['Rubric']['name']);
	
	// Assert that RubricsLom was saved correctly
	$rubricsLom1 = $this->RubricsLom->find('first', array('conditions' => array('RubricsLom.id' => 1)));
	$rubricsLom2 = $this->RubricsLom->find('first', array('conditions' => array('RubricsLom.id' => 2)));
	$this->assertEqual($rubricsLom1['RubricsLom']['lom_comment'], $tmp['RubricsLom'][0]['lom_comment']);
	$this->assertEqual($rubricsLom2['RubricsLom']['lom_comment'], $tmp['RubricsLom'][1]['lom_comment']);
	
	// Assert that RubricsCriteria was saved correctly
	$rubricsCriteria = $this->RubricsCriteria->find('first', array('conditions' => array('RubricsCriteria.id' => 1)));
	$this->assertEqual($rubricsCriteria['RubricsCriteria']['criteria'], $tmp['RubricsCriteria'][0]['criteria']);
	
	// Assert that RubricsCriteriaComment was saved correctly
	$rubricsCriteriaComment0 = $this->RubricsCriteriaComment->find('first', array('conditions' => array('criteria_num' => 1)));
	$this->assertEqual($rubricsCriteriaComment0['RubricsCriteriaComment']['criteria_comment'], 
						$tmp['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['criteria_comment']);
  }
  
  function testAfterFind(){

  	// Set up test data
	$rubric = $this->Rubric->find('first', array('conditions' => array('id' => 4),
                                       'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                          'RubricsLom')));
	$this->assertEqual($rubric['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['criteria_comment'], 'HELLO 11');
	$this->assertEqual($rubric['RubricsCriteria'][0]['RubricsCriteriaComment'][1]['criteria_comment'], 'HELLO 21');
  	$this->assertEqual($rubric['RubricsCriteria'][1]['RubricsCriteriaComment'][0]['criteria_comment'], 'HELLO 12');
  	$this->assertEqual($rubric['RubricsCriteria'][1]['RubricsCriteriaComment'][1]['criteria_comment'], 'HELLO 22');
  }
  
  function testCopy(){

  	$copyRubric = $this->Rubric->copy(4);
	
	// Assert the Rubric name is copied
	$this->assertEqual($copyRubric['Rubric']['name'], 'Copy of Some Rubric');
	// Assert that the Rubric and all of its associated id's are delete
	$this->assertTrue(!isset($copyRubric['Rubric']['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsLom'][0]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsLom'][1]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['RubricsCriteriaComment'][1]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['RubricsCriteriaComment'][0]['id']));
	$this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['RubricsCriteriaComment'][1]['id']));
  }

  function testGetRubricById(){
  	
  	$rubric = $this->Rubric->getRubricById(4);
	$this->assertEqual(4, $rubric['Rubric']['id']);
	$this->assertTrue(isset($rubric['RubricsCriteria']));
	$this->assertTrue(isset($rubric['RubricsLom'])); 	
  }
  
  
  
### Helper Functions ###

  function setUpRubricsArray(){
  	$tmp = Array
	(
    'Rubric' => Array
        (
            'id' => 1,
            'template' => 'horizontal',
            'name' => 'Some Rubric',
            'lom_max' => 2,
            'criteria' => 1,
            'availability' => '',
            'zero_mark' => 0,
            'criteria_mark_0_0' => 0.5,
            'criteria_mark_0_1' => 1
        ),
    'RubricsLom' => Array
        (
            '0' => Array
                (
                    'lom_comment' => 'LOM 1',
                    'id' => 1,
                    'lom_num' => 1
                ),
            '1' => Array
                (
                    'lom_comment' => 'LOM 2',
                    'id' => 2,
                    'lom_num' => 2
                )
        ),
    'RubricsCriteria' => Array
        (
            '0' => Array
                (
                    'criteria' => 'Criteria 1',
                    'id' => 1,
                    'criteria_num' => 1,
                    'RubricsCriteriaComment' => Array
                        (
                            '0' => Array
                                (
                                    'criteria_comment' => 'HELLO 11',
                                    'id' => 1,
                                ),
                            '1' => Array
                                (
                                    'criteria_comment' => 'HELLO 21',
                                    'id' => 2,
                                )
                        ),
                    'multiplier' => 1
                )
        )
	);
	return $tmp;
  }
}
?>