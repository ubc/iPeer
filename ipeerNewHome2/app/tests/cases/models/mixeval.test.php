<?php
App::import('Model', 'Mixeval');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class MixevalTestCase extends CakeTestCase {
  var $name = 'Mixeval';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.mixeval', 'app.mixevals_question',
  						'app.mixevals_question_desc'
                       );
  var $Mixeval = null;

  function startCase() {
	$this->Mixeval = ClassRegistry::init('Mixeval');
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

  function testMixevalInstance() {
    $this->assertTrue(is_a($this->Mixeval, 'Mixeval'));
  }
  
  function testCompileViewData() {
  	// Set up test input
  	$input = $this->setUpTestInput();

  	// Set up test data
  	$result = $this->Mixeval->compileViewData($input);
  	
  	// Assert that old input still exists in the result
  	$this->assertEqual($input['Mixeval'], $result['Mixeval']);
  	$this->assertEqual($input['Event'], $result['Event']);
  	$this->assertEqual($input['Question'], $result['Question']);
  	// Assert that a new array is merged with the old input
  	$this->assertFalse(isset($input['questions']));
  	$this->assertNotNull($result['questions']);
  	$this->assertEqual(count($result['questions']), 3);
  }
  
  function testCompileViewDataShort() {
  	
  	// Set up test input with some fake questions
  	$input = array('Mixeval' => array('id' => 2),
				   'Question' => array('Q1' => 1, 'Q2' => 2));
  	// Set up test data
  	$result = $this->Mixeval->compileViewDataShort($input);
  	// Assert that a new Question array is merged with real fixture questions
  	$this->assertEqual($result['Question'][0]['MixevalsQuestion']['id'], 1); 
  	$this->assertEqual($result['Question'][1]['MixevalsQuestion']['id'], 5);
  	$this->assertEqual($result['Question'][2]['MixevalsQuestion']['id'], 6);
  	
	
  	// Set up alternate input without Question index
  	$altInput = array('Mixeval' => array('id' => 2));
  	$result = $this->Mixeval->compileViewDataShort($altInput);
  	// Assert no addition array entry was merged into result
  	$this->assertEqual($altInput, $result);  	
  }
  
  function setUpTestInput() {
    $tmp = array
	(
    'Mixeval' => array
        (
            'id' => 5,
            'name' => 'mixed2',
            'zero_mark' => 0,
            'scale_max' => 0,
            'availability' => 0,
            'creator_id' => 1,
            'created' => '2011-06-06 10:53:03',
            'updater_id' => 1,
            'modified' => '2011-06-06 10:53:03',
            'creator' => null,
            'updater' => null,
            'event_count' => 1,
            'lickert_question_max' => 1,
            'prefill_question_max' => 2,
            'total_question' => 3,
            'total_marks' => 1
        ),

    'Event' => array
        (
            '0' => array
                (
                    'id' => 56,
                    'title' => 'mixed2',
                    'course_id' => 1,
                    'description' => null,
                    'event_template_type_id' => 4,
                    'template_id' => 5,
                    'self_eval' => 0,
                    'com_req' => 0, 
                    'due_date' => '2011-06-10 10:53:26',
                    'release_date_begin' => '2011-06-01 10:53:29',
                    'release_date_end' => '2011-06-19 10:53:33',
                    'record_status' => 'A',
                    'creator_id' => 1,
                    'created' => '2011-06-06 10:53:39',
                    'updater_id' => null,
                    'modified' => '2011-06-06 10:53:39',
                    'creator' => null,
                    'updater' => null,
                    'response_count' => 1,
                    'to_review_count' => 0,
                    'student_count' => 0,
                    'completed_count' => 1
                )
        ),
        
    'Question' => array
        (
            '0' => array
                (
                    'id' => 21,
                    'mixeval_id' => 5,
                    'question_num' => 0,
                    'title' => 'l1',
                    'instructions' => null,
                    'question_type' => 'S',
                    'required' => 1,
                    'multiplier' => 1,
                    'scale_level' => 0,
                    'response_type' => null
                ),

            '1' => array
                (
                    'id' => 22,
                    'mixeval_id' => 5,
                    'question_num' => 1,
                    'title' => 'c1',
                    'instructions' => 'c1i1',
                    'question_type' => 'T',
                    'required' => 1,
                    'multiplier' => 0,
                    'scale_level' => 0,
                    'response_type' => 'L'
                ),

            '2' => array
                (
                    'id' => 23,
                    'mixeval_id' => 5,
                    'question_num' => 2,
                    'title' => 'c2',
                    'instructions' => 'c2i1',
                    'question_type' => 'T',
                    'required' => 1,
                    'multiplier' => 0,
                    'scale_level' => 0,
                    'response_type' => 'L'
                )
        )
  );
  return $tmp;
	}
}
?>