<?php
$data = $this->controller->Group->field('group_num', "group_num = '".mysql_real_escape_string($this->params['form']['group_num'])."'");
$params = array('controller'=>'groups', 'data'=>$data, 'fieldvalue'=>$this->params['form']['group_num']);
echo $this->renderElement('groups/ajax_group_validate', $params);
?>