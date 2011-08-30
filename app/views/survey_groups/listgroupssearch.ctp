<?php
  $params = array('controller'=>'surveygroups', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
  echo $this->element('survey_groups/ajax_survey_group_list', $params);
?>
