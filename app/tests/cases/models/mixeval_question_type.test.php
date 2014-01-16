<?php
/* MixevalQuestionType Test cases generated on: 2013-01-30 16:08:21 : 1359590901*/
App::import('Model', 'MixevalQuestionType');

class MixevalQuestionTypeTestCase extends CakeTestCase {
	var $fixtures = array('app.mixeval_question_type', 'app.mixeval_question', 'app.mixeval_question_desc');

	function startTest() {
		$this->MixevalQuestionType =& ClassRegistry::init('MixevalQuestionType');
	}

	function endTest() {
		unset($this->MixevalQuestionType);
		ClassRegistry::flush();
	}

}
