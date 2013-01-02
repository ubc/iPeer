<?php if ($upgrade_success): ?>
<p>The iPeer database has been upgraded to the latest version. If you are logged in, please logout and re-login. </p>
  <p><button><?php echo $html->link('Finish', '/')?></button></p>
<?php else: ?>
  <p>The database upgrade failed. Please correct the error and try again.</p>
<?php endif; ?>
