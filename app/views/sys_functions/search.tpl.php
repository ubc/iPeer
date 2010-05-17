<?php
$data = $this->controller->SysFunction->findAll($conditions, $fields=null, $this->controller->order, $this->controller->show, $this->controller->page);

$paging['style'] = 'ajax';
$paging['link'] = '/sysfunctions/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->SysFunction->findCount($conditions);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'sysfunctions', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
echo $this->renderElement('sys_functions/ajax_sysfunctions_list', $params);

?>