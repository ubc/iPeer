<?php
$params = array('controller'=>'users', 'data'=>$data, 'paging'=>$paging, 'course_id'=>$courseId);
echo $this->renderElement('users/ajax_user_list', $params);
?>
