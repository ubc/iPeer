<?php
if(empty($responses))
	$responses=null;

//adding an answer
while(!empty($responses) && sizeof($responses) < $this->params['form']['add'])
	$responses[sizeof($responses)] = array('Response'=>array('id'=>'', 'question_id' => $responses[0]['Response']['question_id'], 'response' => ''));


$params = array('controller'=>'surveys', 'count'=>$this->params['form']['add'], 'responses'=>$responses);
echo $this->element('surveys/ajax_survey_answers', $params);
?>
