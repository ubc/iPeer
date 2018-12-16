<?php echo $this->element('evaltools/tools_menu', array());?>

<?php if ($reminder_enabled): ?>
  <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
<?php else: ?>
  <?php __('Email reminder feature is disabled in the system.') ?>
<?php endif; ?>
