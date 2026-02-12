<?php if (!empty($isDisabled)): ?>
<div class="message error-message red"><?php echo __('This user account is disabled.', true); ?></div>
<?php endif; ?>
<?php echo $this->element('framework/view_user_detail', array('data' => $user));?>
