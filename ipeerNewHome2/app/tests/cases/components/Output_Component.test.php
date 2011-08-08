<?php
App::import('Component', 'Output');

class FakeOutputController extends Controller {
  var $name = 'FakeOutputController';
  var $components = array('Output');
  var $uses = null;
  var $params = array('action' => 'test');
}

class OutputTestCase extends CakeTestCase {
  	var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
                        'app.response', 'app.survey_question', 'app.user_course', 
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
                        );
                    
  function startCase() {
	$this->OutputComponentTest = new OutputComponent();
  }  
  
  function testBr2nl() { 
 	$input = 'line <br/> line';
 	$input = $this->OutputComponentTest->br2nl($input);

 	$this->assertEqual($input, "line \n line");

 	$input = 'line <br /> line';
 	$input = $this->OutputComponentTest->br2nl($input);

 	$this->assertEqual($input, "line \n line");
 	 	
 	$input = 'line <BR> line';
 	$input = $this->OutputComponentTest->br2nl($input);

 	$this->assertEqual($input, "line \n line");

 	$input = 'line';
 	$input = $this->OutputComponentTest->br2nl($input);

 	$this->assertEqual($input, "line");  
  }
                     
   function testFilter() {
 	$input = 'a<script>script</script>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab");

 	$input = 'a<object>here</object>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab"); 	
 	
 	$input = 'a<iframe>here</iframe>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab"); 
 	
 	$input = 'a<applet>here</applet>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab"); 
 	
 	$input = 'a<meta>here</meta>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab"); 
 	
 	$input = 'a<form>here</form>b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "ab"); 
 	
 	$input = "a \n b";
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, "a <br/> b"); 
 	
 	$input = 'a &quot; &#34; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a " " b'); 
 	
 	$input = 'a &amp; &#38; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a & & b'); 
 	
 	$input = 'a &lt; &#60; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a < < b'); 

 	$input = 'a &gt; &#62; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a > > b'); 
 	
 	$input = 'a &nbsp; &#160; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a     b'); 
 	
	$input = 'a &iexcl; &#161; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a '.chr(161).' '.chr(161) .' b'); 
 	
	$input = 'a &cent; &#162; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a '.chr(162).' '.chr(162) .' b');  	
 	
	$input = 'a &pound; &#163; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a '.chr(163).' '.chr(163) .' b'); 
 	
	$input = 'a &copy; &#169; b';
 	$input = $this->OutputComponentTest->filter($input);
 	$this->assertEqual($input, 'a '.chr(169).' '.chr(169) .' b');  	
   }
}  