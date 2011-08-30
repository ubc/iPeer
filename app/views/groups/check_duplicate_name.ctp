<?php
$data = $this->controller->Group->field('group_num', sprintf("group_num = '%d' AND course_id = '%d'", mysql_real_escape_string($this->params['form']['group_num']), $course_id));
$params = array('controller'=>'groups', 'data'=>$data, 'fieldvalue'=>$this->params['form']['group_num']);
echo $this->element('groups/ajax_group_validate', $params);
?>
