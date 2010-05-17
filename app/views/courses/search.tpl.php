<?php
$data = $this->controller->Course->findAccessibleCoursesListByUserIdRole($rdAuth->id, $rdAuth->role, $conditions);

$paging['style'] = 'ajax';
$paging['link'] = '/courses/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$coursesCount = $this->controller->Course->findAccessibleCoursesCount($rdAuth->id, $rdAuth->role, $conditions);
$paging['count'] = isset($coursesCount[0][0]['total'])? $coursesCount[0][0]['total'] : 0;
  	
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'courses', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('courses/ajax_course_list', $params);
?>