<?php
App::import('Model', 'MixevalsQuestionDesc');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class MixevalsQuestionDescTestCase extends CakeTestCase {
  var $name = 'MixevalsQuestionDesc';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.mixeval', 'app.mixevals_question',
  						'app.mixevals_question_desc'
                       );
  var $MixevalsQuestionDesc = null;

  function startCase() {
	$this->MixevalsQuestionDesc = ClassRegistry::init('MixevalsQuestionDesc');
	$this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
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

  function testMixevalsQuestionDescInstance() {
    $this->assertTrue(is_a($this->MixevalsQuestionDesc, 'MixevalsQuestionDesc'));
  }
  
  function testInsertQuestionDescriptor() {
  	// Set up test inputs
  	$question_ids = $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> 2), 'fields' => array('MixevalsQuestion.id, question_num')));
  	$data = $this->setUpTestData();
  	// Set up test
  	$this->MixevalsQuestionDesc->insertQuestionDescriptor($data, $question_ids);
  	// Assert Question Descriptors have been successfully added to MixevalsQuestionDesc
	$searched1 = $this->MixevalsQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 1')));
	$searched2 = $this->MixevalsQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 2')));
	$this->assertEqual($searched1[0]['MixevalsQuestionDesc']['descriptor'], 'ONLY ENTRY 1');
	$this->assertEqual($searched2[0]['MixevalsQuestionDesc']['descriptor'], 'ONLY ENTRY 2');
  }
  
  function testGetQuestionDescriptor() {
  	// Data comes from fixture tables
  	$result = $this->MixevalsQuestionDesc->getQuestionDescriptor(1);
  	$this->assertEqual($result[0]['MixevalsQuestionDesc']['id'], 1);
  	$this->assertEqual($result[0]['MixevalsQuestionDesc']['descriptor'], 'HELLO');
  	$this->assertEqual($result[1]['MixevalsQuestionDesc']['id'], 3);
  	$this->assertEqual($result[1]['MixevalsQuestionDesc']['descriptor'], 'HELLO 1');
  }
  
  function setUpTestData() {
  	$tmp = array(
    '0' => array(
            'id' => '',
            'question_type' => 'S',
            'question_num' => 0,
            'title' => 'Q1 Lickert',
            'multiplier' => 15,
            'Description' => array(
                    '0' => array(
                            'id' => '', 
                            'descriptor' => 'ONLY ENTRY 1'
                        ),
                    '1' => array(
                            'id' => '',
                            'descriptor' => 'ONLY ENTRY 2'
                        )
                )
        ),
    '1' => array(
            'id' => '',
            'question_type' => 'T',
            'question_num' => 1,
            'title' => 'Q1 Comment',
            'instructions' => 'Ins 1',
            'response_type' => 'L'
        	)
		);
	return $tmp;
  }
}
?>