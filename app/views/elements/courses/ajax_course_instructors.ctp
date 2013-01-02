<?php for ( $i=0; $i<$count; $i++):?>
  <?php $is_empty_set = false; ?>
	<select name="instructor_id<?php echo $i+1?>" style="width:250px;margin: 2px;">

  <?php foreach($instructor as $row):?>
    <?php  $user = $row['users']?>
    <?php if(isset($empty) && $empty && !$is_empty_set):?>
      <option value=''></option>
      <?php $is_empty_set = true;?>
    <?php endif;?>
    <option value='<?php echo $user['id']?>'><?php echo $user['full_name']?></option>
	<?php endforeach;?>
  </select><br>

<input type="hidden" name="data[Course][count]"  value="<?php echo $count?>" />
<?php endfor;?>
