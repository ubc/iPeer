<p><?php echo __('Hello ', true).$user_data['User']['full_name'].','; ?></p>
<p><?php echo __('Your iPeer password has been reset to the password below.', true); ?></p>
<ul><li><?php __('Username')?>: <?php echo $user_data['User']['username']?></li>
<li><?php __('Password')?>: <?php echo $user_data['User']['tmp_password']?></li></ul>