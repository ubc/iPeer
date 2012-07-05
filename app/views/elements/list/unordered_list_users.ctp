<?php
$instructor_html_array = array();
foreach($instructors as $row){
  $instructor_html_array[] = 
    '<li>'.
    $this->element(
      'users/user_info', 
      array('data' => $row)
    ). 
    '</li>';
}

echo empty($instructor_html_array) ? 
  'None' : 
  '<ul>'.implode($instructor_html_array).'</ul>';
?>
