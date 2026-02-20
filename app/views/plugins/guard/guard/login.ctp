<?php if (($notice ?? null) === 'inactive'): ?>
<div class="message error-message">
    <?php __('Your account is currently inactive.') ?>
</div>
<?php elseif (($notice ?? null) === 'no_enrollment'): ?>
<div class="message error-message">
    <?php __('You are not enrolled in any courses. Please contact your instructor to add you to your course, and then try logging in again.') ?>
</div>
<?php endif; ?>
<div align="center" class="login">
    <h4><?php echo __('iPeer Login',true) ?></h4>
    <?php echo isset($loginHeader) ? $loginHeader : ''?>
    <!-- begin login form -->
    <?php echo $this->element('login_' . Inflector::underscore($auth_module_name), array('login_url', $login_url, 'is_logged_in' => $is_logged_in))?>
    <!-- end login form -->
    <?php echo isset($loginFooter) ? $loginFooter : ''?>
</div>

<script type="text/javascript">
jQuery('#GuardUsername').focus();
</script>
