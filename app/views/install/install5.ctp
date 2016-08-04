<div class='install'>
  <h3>iPeer Installation Complete!</h3>
  <p>
    Try logging in with your superadmin account:
    <?php echo $this->Html->link('iPeer Home', '/home'); ?>
  </p>
  <h4>Installation Notes</h4>
  <p>
    <span class='red'><?php __('Important!')?></span>
    <?php printf(__('For security reasons, please set the configuration directory (%s) back to read only.', true), CONFIGS)?>
  </p>
  <p>
  <?php __("If you opted to install with example data, you can login to the example user accounts with the password 'ipeeripeer'")?>
  </p>
</div>
