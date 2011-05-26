<?php
App::import('Model', 'SimpleEvaluation');

class SimpleEvaluationTestCase extends CakeTestCase{
	
	function Test__checkDuplicateTitle(){
		
		$this->SimpleEvaluation= & ClassRegistry::init('SimpleEvaluation');
		$empty=null;
		$this->flushDatabase();
			
		//Set up test data	
		$this->createSimpleEvaluation(1,'Test');
		//Run tests
		$yesDuplicate = $this->SimpleEvaluation->__checkDuplicateTitle('Test');
		$this->assertEqual($yesDuplicate, false);
		$this->SimpleEvaluation->printHelp($yesDuplicate);
		$noDuplicate = $this->SimpleEvaluation->__checkDuplicateTitle('Nope');
		$this->assertEqual($noDuplicate, false);
	}
 	
	
###### Helper Functions ######

	function createSimpleEvaluation($id='', $name=''){
		
		$this->SimpleEvaluation= & ClassRegistry::init('simple_evaluations');
		
		$sql = "INSERT INTO simple_evaluations 
				VALUES ('$id', '$name', '', '50', 'A', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->SimpleEvaluation->query($sql);
	}
	
	function flushDatabase(){
		
		$this->SimpleEvaluation= & ClassRegistry::init('SimpleEvaluation');
		
		$this->SimpleEvaluation->deleteAllTuples('SimpleEvaluation');
	}
}
?>