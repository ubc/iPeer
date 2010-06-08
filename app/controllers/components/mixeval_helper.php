<?php
/* SVN FILE: $Id$ */
/*
 * To use your Model’s inside of your components, you can create a new instance like this:
 *  $this->foo = new Foo;
 *
 * @author      
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class MixevalHelperComponent extends Object
{ 
  var $components = array('Output');
  
  function compileViewData($mixeval=null)
	{
	  $this->MixevalsQuestion = new MixevalsQuestion;
	  $this->MixevalsQuestionDesc = new MixevalsQuestionDesc;
  
		$mixeval_id = $mixeval['Mixeval']['id'];
		$mixEvalDetail = $this->MixevalsQuestion->getQuestion($mixeval_id);

		$tmp = array();
	  
	  if (!empty($mixEvalDetail)) {
    	foreach ($mixEvalDetail as $row) {
    	  $evalQuestion = $row['MixevalsQuestion'];
    	  $this->Output->filter($evalQuestion);	
    	  $tmp['questions'][$evalQuestion['question_num']] = $evalQuestion;
    	  if ($evalQuestion['question_type'] == 'S') {
    	    //Retrieve the lickert descriptor 
    	    $descriptors = $this->MixevalsQuestionDesc->getQuestionDescriptor($mixeval_id, $evalQuestion['question_num']);
    	    $tmp['questions'][$evalQuestion['question_num']]['descriptors'] = $descriptors;
    	  }
    	}
    }
    $mixEvalDetail = array_merge($mixeval,$tmp);
		return $mixEvalDetail;
	}  
	
}
?>