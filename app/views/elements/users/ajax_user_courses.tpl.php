<?php
for ( $i=0; $i<$count; $i++) {
  echo '<select name="course_id'.($i+1).'" style="width:250px;margin: 2px;">';
  foreach($all_courses as $row) {   	
    $course = $row['Course']; 
    echo '<option value='.$course['id'].'>'.$course['course']." - ".$course['title']."</option>";
  }
  echo "</select><br>";
}
echo '<input type="hidden" name="data[Course][count]"  value="'.$count.'" />';
?>
