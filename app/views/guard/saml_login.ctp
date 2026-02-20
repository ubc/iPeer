<?php
$notice_messages = array(
    'no_enrollment' => __('You are not enrolled in any courses. Please contact your instructor to add you to your course, and then try logging in again.', true),
    'no_account'    => __('You have logged in successfully, but an iPeer account has not yet been created for you. Please contact your instructor to add you to your course, and then try logging in again.', true),
    'inactive'      => __('Your account is currently inactive.', true),
);
$notice_message = isset($notice) ? ($notice_messages[$notice] ?? null) : null;
?>
<?php if ($notice_message): ?>
    <div class="message error-message">
        <?php echo $notice_message; ?>
    </div>
    <?php if (!empty($saml_logout_notice)): ?>
    <div class="message info-message">
        <?php echo $saml_logout_notice; ?>
    </div>
    <?php endif; ?>
    <div style="text-align: center; margin-top: 1.5em;">
        <a href="/public/saml/logout.php" class="button button-large"><?php __('Log Out') ?></a>
    </div>
<?php else: ?>
    <div style="text-align: center; margin-top: 1.5em;">
        <a href="/public/saml/auth.php" class="button button-large"><?php __('Sign In') ?></a>
    </div>
<?php endif; ?>
