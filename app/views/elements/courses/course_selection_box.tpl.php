<?php if (isset($view) && $view):?>
  <?php //For view page; display the name only
  echo $coursesList[$courseId]['course'];?>
<?php else:?>
  <!-- //For Edit or Add pages; shows the selection box -->
  <select name="course_id" id="<?php echo isset($id_prefix) ? $id_prefix.'_' : ''?>course_id" <?php echo empty($disabled)?'':$disabled;?>>

  <?php if (isset($defaultOpt) && $defaultOpt == '1'): ?>
    <option value="-1" SELECTED >No Course Selected</option>
  <?php elseif (isset($defaultOpt) && $defaultOpt == 'A'): ?>
    <option value="A" SELECTED > --- All Courses --- </option>
  <?php endif;?>

  <?php if (isset($empty) && true == $empty):?>
    <option value=''></option>
  <?php endif;?>

  <?php foreach ($coursesList as $course):?>
    <!-- // Mark this course as selected if it's the presend course -->
    <option value='<?php echo $course['id']?>' <?php echo (isset($courseId) && ($courseId == $course['id'])) ? "selected" : ""?>><?php echo $course['course']?></option>
  <?php endforeach;?>

  </select>
<?php endif;?>
