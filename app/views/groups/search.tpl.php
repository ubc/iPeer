<?php
$data = $this->controller->Group->findAll($conditions, $fields, $this->controller->order, $this->controller->show, $this->controller->page, null, $joinTable);

if(!isset($data[0]['Group']['id']))
{
	$data = null;
	$paging['count'] = 0;
}
else
	$paging['count'] = $this->controller->Group->findCount($conditions, 0, $joinTable);
	
$paging['style'] = 'ajax';
$paging['link'] = '/groups/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'groups', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('groups/ajax_group_list', $params);
?>
