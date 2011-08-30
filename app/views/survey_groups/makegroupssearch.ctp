<?php
  $params = array('controller'=>'surveygroups', 'paging'=>!empty($paging)? $paging: null);
  echo $this->element('survey_groups/ajax_survey_makegroups', $params);
?>
