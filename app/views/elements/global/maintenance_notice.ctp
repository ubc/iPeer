<?php if (!empty($maintenanceNotice ?? '')): ?>
<div class="maintenance-notice">
    <?php echo h($maintenanceNotice); ?>
</div>
<?php endif; ?>
