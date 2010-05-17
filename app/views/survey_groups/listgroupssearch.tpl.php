<?php
  $params = array('controller'=>'surveygroups', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
  echo $this->renderElement('survey_groups/ajax_survey_group_list', $params);
?>