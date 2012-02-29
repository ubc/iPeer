<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>iPeer - <?php echo $title_for_layout; ?></title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="Content-Language" content="en" />
  <!--link rel="shortcut icon" href="favicon.ico" type="image/x-icon"-->
  <?php
  // CSS files
  echo $html->css('ipeer');
  echo $html->css('datepicker');

  // Scripts 
  // as prototype does not appear to be maintained anymore, we should
  // switch to jquery. Load jquery from Google.
  echo $html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
  ?>
  <script type='text/javascript'>
  jQuery.noConflict(); // prevent conflicts with prototype
  </script>
  <?php
  echo $html->script('prototype');
  echo $html->script('ipeer');
  echo $html->script('showhide');
  // AJAX Include Files
  echo $html->script('scriptaculous');
  echo $html->script('zebra_tables');
  // Validation Include Files
  echo $html->script('validate');
  echo $html->script('submitvalidate');

  // Custom View Include Files
  echo $scripts_for_layout; 
  ?>
</head>
<body>

<div id="containerOuter" class='pagewidth'>
<!-- BANNER -->
<?php echo $this->element('global/banner'); ?>

<!-- NAVIGATION -->
<?php 
if (isset($access))
{
  echo $this->element('global/navigation', array('access' => $access)); 
}
?>

<!-- CONTENT -->
  <!-- TITLE BAR -->
  <div class='title'>
    <span class='text'>
      <?php echo $html->image('layout/icon_ipeer_logo.gif',array('alt'=>'icon_ipeer_logo'))?>
      <?php echo $title_for_layout;?>
    </span>
  </div>
  <!-- ERRORS -->
  <?php echo $this->Session->flash(); ?>
  <?php echo $this->Session->flash('auth'); ?>
  <?php echo $this->Session->flash('email'); ?>
  <!-- ACTUAL PAGE -->
  <?php echo $content_for_layout; ?>
</div>

<!-- FOOTER -->
<?php echo $this->element('global/footer'); ?>

<!-- DEBUG -->
<?php echo $this->element('global/debug'); ?>

<?php if (User::isLoggedIn()) :?>
<?php echo $html->image('layout/icon_edit.gif',array('alt'=>'icon_edit'))?> 
<?php echo $this->Html->link('Edit Profile ('.User::get('full_name').')', '/users/editProfile', array('class' => 'miniLinks')); ?>
<?php echo $html->image('layout/icon_arrow.gif',array('alt'=>'icon_arrow'))?> 
<?php echo $this->Html->link('Logout', Router::url('/logout', true), array('class' => 'miniLinks'))?>
<?php endif; ?>


<div id="wrap">
  <div class="box">
    <div class="bi">
      <div class="bt"><div>&nbsp;</div></div>

      <div class="content-wrap">
      <div class='title'>
        <span class='text'><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?>
          <?php echo $title_for_layout;?> </span>
        <span class="controls">
        <?php if (!empty($this->params) && 
                  'install' != $this->params['controller'] &&
                  'upgrade' != $this->params['controller'] &&
                  'loginout' != $this->params['controller'] &&
                  User::get('role') != 'S'): ?>

            <a href="<?php echo $this->webroot . $this->theme?>framework/tutIndex" onclick="wopen(this.href, \'popup\', 800, 600); return false;">
              <?php echo $html->image('layout/button_ipeer_tutorial.gif', array('border'=>'0','alt'=>'button_ipeer_tutorial'))?></a>
         <?php endif; ?> 
         </span>
      </div>

        <?php if (!empty($errmsg)): ?>
        <div align="center" class="title2"><font color="red"><?php echo $errmsg; ?></font></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
        <div align="center" class="title2"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($session->check('Message.flash')): echo $session->flash(); endif;?>
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Session->flash('email'); ?>
        <?php echo $this->Session->flash('auth'); ?>
        <div align="left" id="loading"><?php echo $html->image('spinner.gif',array('alt'=>'spinner'))?></div>
        <div id="content"><?php echo $content_for_layout;?></div>
      <h1 align="center"><span class="footer"><?php __('Powered by iPeer and TeamMaker - Created by UBC and Rose-Hulman')?></span></h1>
      </div>

      <div class="bb"><div>&nbsp;</div></div>
    </div>
  </div>
</div>

<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
</body>
</html>
