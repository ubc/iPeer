<?php if(isset($students)):?>
<?php foreach($students as $s): $user = $s['users']?>
  <option value="<?php echo $user['id'] ?>"><?php echo $user['full_name'] ?></option>
<?php endforeach;?>
<?php endif;?>
