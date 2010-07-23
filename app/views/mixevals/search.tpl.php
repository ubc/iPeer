<?php
$params = array('controller'=>'mixevals', 'data'=>$data, 'paging'=>$paging, 'event'=>$event);
echo $this->renderElement('mixevals/ajax_mixeval_list', $params);
?>
