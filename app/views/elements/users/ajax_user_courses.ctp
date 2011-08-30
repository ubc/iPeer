<select name="course_ids[<?php echo $count?>]" class="course-selector">
  <?php foreach($all_courses as $row):?>
    <option value='<?php echo $row['Course']['id']?>'><?php echo $row['Course']['course']." - ".$row['Course']['title']?></option>
  <?php endforeach;?>
</select>
