<?php

if (isset($view) && $view) {
  //For view page; display the name only
  echo $courseList[$courseId]['course'];
}
else {
  if (empty($disabled)) $disabled='';

  //For Edit or Add pages; shows the selection box
  echo '<select name="course_id" '.$disabled.'>';

  if (isset($defaultOpt) && $defaultOpt == '1') {
    echo '<option value="-1" SELECTED >No Course Selected</option>';
  } else if (isset($defaultOpt) && $defaultOpt == 'A') {
    echo '<option value="A" SELECTED > --- All Courses --- </option>';
  }

  foreach($courseList as $index => $value) {
    $sticky = isset($sticky_course_id) && $sticky_course_id == $value['id'] ? 'selected':'';
    if (!isset($courseId)) {
      echo '<option value="'.$courseList[$index]['id'].'" '.$sticky.'>'.$value['course'].'</option>';
    } else {
      if ($courseId == $index){
        echo '<option value="'.$index.'" '.$sticky.'>'.$value['course'].'</option>';
      }
    }
  }
  echo '</select>';

}?>