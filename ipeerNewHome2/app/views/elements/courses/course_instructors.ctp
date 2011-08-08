<?php
$instructor_html_array = array();
foreach($instructors as $row){
  $instructor_html_array[] = $this->element('users/user_info', array('data' => $row));
}

echo empty($instructor_html_array) ? 'N/A' : implode(', ', $instructor_html_array);
?>
