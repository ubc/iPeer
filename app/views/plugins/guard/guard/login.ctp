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
