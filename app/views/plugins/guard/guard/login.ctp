<div class="module module__login">
  <h2 class="module__title"><?php echo __('Log in to iPeer', true) ?></h2>
  <h3 class="module__subtitle">
    <svg class="w-12 h-12" id="emoji" viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
      <g id="line">
        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m40.13 62.42h-8.259a10.75 10.75 0 0 1-10.72-10.72v-25.31a10.75 10.75 0 0 1 10.72-10.72h8.259a10.75 10.75 0 0 1 10.72 10.72v25.31a10.75 10.75 0 0 1-10.72 10.72z"/>
        <circle cx="36" cy="30.51" r="8.902" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"/>
        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m36 15.1c0.5005-13.15-19.03-4.582-21.79-9.568"/>
      </g>
    </svg>
      <?php echo __('Enter your information', true) ?>
  </h3>
  <p class="text-base">Please log in using your <strong>UBC CWL</strong> (Campus-Wide Login). If you need help with
    logging in, please contact <a href="mailto:ipeer.support@ubc.ca">ipeer.support@ubc.ca</a>.</p>
    
    <?php echo isset($loginHeader) ? $loginHeader : '' ?>
  <!-- begin login form -->
    <?php echo $this->element('login_' . Inflector::underscore($auth_module_name), array('login_url', $login_url, 'is_logged_in' => $is_logged_in)) ?>
  <!-- end login form -->
    <?php echo isset($loginFooter) ? $loginFooter : '' ?>

</div>
<script type="text/javascript">
  jQuery('#GuardUsername').focus();
</script>
