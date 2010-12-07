<?php
$params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'display'=>'search');
echo $this->element('evaluations/ajax_evaluation_list', $params);
