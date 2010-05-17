<?php
//$tmptitle=$this->params['form']['newtitle'];
$data = $this->controller->Event->field('title', "title = '".mysql_real_escape_string($this->params['form']['newtitle'])."'");
$params = array('controller'=>'events', 'data'=>$data, 'fieldvalue'=>$this->params['form']['newtitle']);
echo $this->renderElement('events/ajax_title_validate', $params);
?>