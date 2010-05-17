<?php
  $params = array('controller'=>'surveygroups', 'paging'=>!empty($paging)? $paging: null);
  echo $this->renderElement('survey_groups/ajax_survey_makegroups', $params);
?>