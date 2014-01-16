<p>Current Version: <?php echo $currentVersion?></p>
<p>Current Database Version: <?php echo $currentDbVersion?></p>
<?php if ($is_upgradable): ?>
<p>You are about to upgrade your iPeer instance. Please make sure you have <strong>BACKED UP</strong> your database and files before proceeding!</p>
<p>Upgrade may take some time. Please be patient.</p>
<p><button><?php echo $html->link('Confirm', 'step2'); ?></button></p>
<?php else: ?>
<p>Your instance does not need to be upgraded.</p>
<?php endif; ?>
