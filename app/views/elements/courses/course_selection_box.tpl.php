<?php

    if (isset($view) && $view) {
    //For view page; display the name only
    echo $coursesList[$courseId]['course'];
    } else {
    if (empty($disabled)) $disabled='';

    //For Edit or Add pages; shows the selection box
    echo '<select name="course_id" id="course_id" '.$disabled.'>';

    if (isset($defaultOpt) && $defaultOpt == '1') {
        echo '<option value="-1" SELECTED >No Course Selected</option>';
    } else if (isset($defaultOpt) && $defaultOpt == 'A') {
        echo '<option value="A" SELECTED > --- All Courses --- </option>';
    }

        foreach ($coursesList as $course) {
            // Mark this course as selected if it's the presend course
            $isSelected = (isset($courseId) && ($courseId == $course['id'])) ? "selected" : "";
            echo "<option value='$course[id]' $isSelected >$course[course]</option>";
        }

    echo '</select>';
    }
?>
