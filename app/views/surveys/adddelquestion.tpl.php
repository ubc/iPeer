<?php
if(empty($responses))
	$responses=null;
$params = array('controller'=>'surveys', 'count'=>$this->params['form']['add'], 'responses'=>$responses);
echo $this->renderElement('surveys/ajax_survey_answers', $params);
?>