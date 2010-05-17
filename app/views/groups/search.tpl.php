<?php
$data = $this->controller->Group->findAll($conditions, $fields, $this->controller->order, $this->controller->show, $this->controller->page, null, $joinTable);

$paging['style'] = 'ajax';
$paging['link'] = '/groups/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->Group->findCount($conditions, 0, $joinTable);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'groups', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('groups/ajax_group_list', $params);
?>