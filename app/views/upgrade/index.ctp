<?php if ($is_upgradable): ?>
<p>You are about to upgrade your iPeer instance. Please make sure you have <strong>BACKED UP</strong> your database and files before proceeding!</p>
<p>Upgrade may take some time. Please be patent.</p>
<p><?php echo $html->link('Confirm', 'step2', array('class' => 'text-button')); ?></p>
<?php else: ?>
<p>Your instance do not need to be upgraded.</p>
<?php endif; ?>
