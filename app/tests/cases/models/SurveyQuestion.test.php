<?php
App::import('Model', 'SurveyQuestion');
App::import('Controller', 'SurveyQuestion');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SurveyQuestionTestCase extends CakeTestCase {
  var $name = 'SurveyQuestion';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 'app.survey',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom', 
  					 	'app.rubrics_criteria', 'app.rubrics_criteria_comment'
                       );
  var $SurveyQuestion = null;

  function startCase() {
	$this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
	$this->Survey = ClassRegistry::init('Survey');
	$this->Question = ClassRegistry::init('Question');
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

  function testSurveyQuestionInstance() {
    $this->assertTrue(is_a($this->SurveyQuestion, 'SurveyQuestion'));
  }
  
  function testReorderQuestions() {
  	// Set up test data
  	$result = $this->SurveyQuestion->reorderQuestions(2);
	/* Assert the function returns a result with correctly ordered
	 * question number, instead of the default 9999.
	 */ 
  	$this->assertEqual($result[0]['SurveyQuestion']['number'], 1);
  	$this->assertEqual($result[1]['SurveyQuestion']['number'], 2);
  	
  	// Assert the result is saved in the fixtures
  	$DBResult1 = $this->SurveyQuestion->find('first', 
  						array('conditions' => array('SurveyQuestion.id' => 3)));
  	$DBResult2 = $this->SurveyQuestion->find('first', 
  						array('conditions' => array('SurveyQuestion.id' => 4)));
	$this->assertEqual($DBResult1['SurveyQuestion']['number'], 1);
	$this->assertEqual($DBResult2['SurveyQuestion']['number'], 2);
  }
  
  function testMoveQuestion() {
  	/*
  	 * The data structure is set up as fallows
  	 * Question_id = 1 | Question_num = 1
  	 * Question_id = 2 | Question_num = 2
  	 * Question_id = 6 | Question_num = 3
  	 */
  	// Test move TOP
  	$this->SurveyQuestion->moveQuestion(1, 6, 'TOP');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);
	
	// Test move BOTTOM
	$this->SurveyQuestion->moveQuestion(1, 6, 'BOTTOM');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 3);
	
	// Test move UP
	$this->SurveyQuestion->moveQuestion(1, 6, 'UP');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 2);
	
	// Test move DOWN
	$this->SurveyQuestion->moveQuestion(1, 6, 'DOWN');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 3);
	
	// Test move BOTTOM on the bottom question
	$this->SurveyQuestion->moveQuestion(1, 6, 'BOTTOM');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 3);
	
	// Test move DOWN on the bottom question
	$this->SurveyQuestion->moveQuestion(1, 6, 'BOTTOM');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 3);
	
	// Test move TOP on the TOP question
	$this->SurveyQuestion->moveQuestion(1, 6, 'TOP');
	$this->SurveyQuestion->moveQuestion(1, 6, 'TOP');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);
	
	// Test move UP on the top question
	$this->SurveyQuestion->moveQuestion(1, 6, 'UP');
  	$movedQuestion = $this->SurveyQuestion->find('first', 
  								array('conditions' => array('question_id' => 6)));
	$this->assertEqual($movedQuestion['SurveyQuestion']['number'], 1);
  }
  
  function testGetQuestionsID() {
  	// Set question data
  	$result = $this->SurveyQuestion->getQuestionsID(1);
  	$surveyQ1 = $result[0]['SurveyQuestion'];
  	$surveyQ2 = $result[1]['SurveyQuestion'];
  	$surveyQ3 = $result[2]['SurveyQuestion'];
  	$this->assertEqual($surveyQ1['question_id'], 1);
  	$this->assertEqual($surveyQ2['question_id'], 2);
  	$this->assertEqual($surveyQ3['question_id'], 6);
  	$this->assertEqual($result['count'], 3);
  	
  	$result = $this->SurveyQuestion->getQuestionsID(null);
  	$this->assertFalse($result);
  	
  }
  
  function testCopyQuestions() {
  	// Set up test data copy survey.id = 1 to survey.id =3 (empty survey)
  	$this->SurveyQuestion->copyQuestions(1, 3);
  	$surveyOriginal = $this->Survey->find('all', 
  									array('conditions' => array('Survey.id' => 1)));
	$surveyCopied = $this->Survey->find('all', 
  									array('conditions' => array('Survey.id' => 3)));
	$surveyOriginalQuestion = $surveyOriginal[0]['Question'];
	$surveyCopiedQuestion = $surveyCopied[0]['Question'];
	// Assert that survey has been copied
	$this->assertEqual($surveyOriginalQuestion[0]['prompt'], $surveyCopiedQuestion[0]['prompt']);
	$this->assertEqual($surveyOriginalQuestion[1]['prompt'], $surveyCopiedQuestion[1]['prompt']);
	$this->assertEqual($surveyOriginalQuestion[2]['prompt'], $surveyCopiedQuestion[2]['prompt']);
  }
  
  function testGetLastSurveyQuestionNum() {
  	$lastQuestionNum = $this->SurveyQuestion->getLastSurveyQuestionNum(1);
	$this->assertEqual($lastQuestionNum, 3);
  }
}
?>