<?php
$params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'display'=>'search');
echo $this->element('users/ajax_user_list', $params);
