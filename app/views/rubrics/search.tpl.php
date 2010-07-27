<?php
$params = array('controller'=>'rubrics', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('rubrics/ajax_rubric_list', $params);
?>
