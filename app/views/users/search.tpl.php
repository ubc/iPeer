<?php
//wvar_dump($conditions);
$data = $this->controller->User->findAll($conditions, $fields, $this->controller->order, $this->controller->show, $this->controller->page, null, $joinTable);

//$dataSource = ConnectionManager::getDataSource('default');
//echo !empty($dataSource) ? $dataSource->showLog() : "No SQL data";


$paging['style'] = 'ajax';
$paging['link'] = '/users/search/?show='.$this->controller->show .
                '&livesearch=' . $liveSearch . '&select=' . $select .
                '&display_user_type='.$displayUserType.'&course_id=' . $courseId .
                '&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->User->findCount($conditions, 0, $joinTable);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'users', 'data'=>$data, 'paging'=>$paging, 'course_id'=>$courseId);
echo $this->renderElement('users/ajax_user_list', $params);
?>