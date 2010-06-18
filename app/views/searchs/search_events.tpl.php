<?php

$params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'display'=>'search');
echo $this->renderElement('evaluations/ajax_evaluation_list', $params);

?>
