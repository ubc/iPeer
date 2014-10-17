<?php
App::import('Component', 'Search');

class FakeSearchController extends Controller {
  var $name = 'FakeSearchController';
  var $components = array('Search');
  var $uses = null;
  var $params = array('action' => 'test');
}

class SearchTestCase extends CakeTestCase {
  	var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
                        'app.faculty', 'app.user_faculty', 
                        'app.department', 'app.course_department'
                        );
                    
  function startCase() {
	$this->SearchComponentTest = new SearchComponent();
  }  
  
  function testSetEvaluationCondition() { 
   	// Set up test data
   	$data = array();
   	$data['form']['course_id'] = 1;
   	$data['data']['Search']['event_type'] = 'Mixeval';
   	$data['data']['Search']['due_date_begin'] = '2011-06-10 00:00:00'; 
   	$data['data']['Search']['due_date_end'] = '2012-06-10 00:00:00'; 
   	$data['data']['Search']['release_date_begin'] = '2011-07-10 00:00:00' ;
   	$data['data']['Search']['release_date_end'] = '2012-07-10 00:00:00';
   	
   	$condition = $this->SearchComponentTest->setEvaluationCondition($data);
   	
   	$expected = array('condition' => array(
   	                   'course_id' => 1, 'EventTemplateType.id' => 'Mixeval', 'due_date > ' => '2011-06-10 00:00:00',
   	                   'due_date < ' => '2012-06-10 00:00:00', 'release_date_end > ' =>'2011-07-10 00:00:00', 'release_date_begin < ' =>'2012-07-10 00:00:00', 
   	                  ),'sticky' => array(
   	                  'course_id' => 1, 'EventTemplateType.id' => 'Mixeval', 'due_date_begin' => '2011-06-10 00:00:00',
   	                   'due_date_end' => '2012-06-10 00:00:00', 'release_date_begin' =>'2011-07-10 00:00:00', 'release_date_end' =>'2012-07-10 00:00:00',  	                 
   	                  ));
    $this->assertEqual($condition, $expected);
    
    $data = array();
   	$condition = $this->SearchComponentTest->setEvaluationCondition($data);
   	
   	$expected = array('condition' => array(),
   										'sticky' => array(
   	                  'course_id' => null, 'EventTemplateType.id' => null, 'due_date_begin' => null,
   	                   'due_date_end' => null, 'release_date_begin' =>null, 'release_date_end' =>null,  	                 
   	                  ));
    $this->assertEqual($condition, $expected);    
    
  }
/*
  // Method needs to be fixed
  function testSetInstructorCondition() { 
    $data = array();
   	$data['form']['course_id'] = 1;
   	$data['form']['instructorname'] = 'instructor';
   	$data['form']['email'] = 'email';    	
   	$condition = $this->SearchComponentTest->setInstructorCondition($data);   	
   	$expected = array('condition' => array(
   	                   'User.id' => array(4=>4), 'User.full_name LIKE ' => '%instructor%', 
   	                   'email LIKE ' => '%email%'),
   	                   'sticky' => array(
   	                  'course_id' => 1, 'instructor_name' => 'instructor',
   	                   'email' => 'email'));
    $this->assertEqual($condition, $expected);
    
    $data = array();
   	$condition = $this->SearchComponentTest->setInstructorCondition($data);
   	$expected = array('condition' => array(),
   										'sticky' => array(
   	                  'course_id' => null, 'instructor_name' => null, 'email' => null,
   	                   ));
    $this->assertEqual($condition, $expected);
  }  
*/
  function testSetEvalResultCondition(){
    $data = array();
   	$data['form']['course_id'] = 1;
   	$data['form']['event_id'] = 1;
   	$data['form']['status'] = 'A';    	
   	$data['data']['Search']['mark_from'] = 0;   
   	$data['data']['Search']['mark_to'] = 50; 
   	$condition = $this->SearchComponentTest->setEvalResultCondition($data);   	
   	$expected = array(
   	            "maxPercent"=> 0.5, "minPercent"=> 0,
   	            "sticky"=> array("course_id"=> 1, "event_id"=> 1,
   	            "status"=> "A", "mark_from"=> 0, "mark_to"=> 50 ),
   	            "event_id"=> 1, "status"=> "A" ); 
    $this->assertEqual($condition, $expected);
    $data = array();
   	$condition = $this->SearchComponentTest->setEvalResultCondition($data);
   	$expected = array(
   	            "maxPercent"=> null, "minPercent"=> null,
   	            "sticky"=> array("course_id"=> null, "event_id"=> null,
   	            "status"=> null, "mark_from"=> null, "mark_to"=> null ),
   	            "event_id"=> null, "status"=> null );                 
    $this->assertEqual($condition, $expected);    
  }
}  
