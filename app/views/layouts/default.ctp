<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>iPeer - <?php echo $title_for_layout; ?></title>
  <!-- Needed to force IE back to standards mode when it ignores the doctype -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="Content-Language" content="en" />
  <!--link rel="shortcut icon" href="favicon.ico" type="image/x-icon"-->
  <?php
  // CSS files
  echo $html->css('datepicker');
  echo $html->css('jquery.dataTables');
  echo $html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/cupertino/jquery-ui.css');
  echo $html->css('https://fonts.googleapis.com/css?family=Lato:400,400italic,700');
  echo $html->css('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
  echo $html->css('ipeer');

  // Scripts
  // as prototype does not appear to be maintained anymore, we should
  // switch to jquery. Load jquery from Google.
  echo $html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
  echo $html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js');
  echo $html->script('jquery.dataTables.min');
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

<div class='containerOuter pagewidth'>
<!-- BANNER -->
<?php echo $this->element('global/banner'); ?>

<!-- NAVIGATION -->
<?php echo $this->element('global/navigation', array());?>

<!-- CONTENT -->
  <!-- TITLE BAR -->
  <h1 class='title'>
    <?php echo $html->image('layout/icon_ipeer_logo.gif',array('alt'=>'icon_ipeer_logo'))?>
    <?php if (isset($breadcrumb)): ?>
        <?php echo $breadcrumb->render($html); ?>
    <?php else: ?>
        <?php echo $title_for_layout;?>
    <?php endif; ?>
  </h1>
  <!-- ERRORS -->
  <?php echo $this->Session->flash(); ?>
  <?php echo $this->Session->flash('auth'); ?>
  <?php echo $this->Session->flash('good'); ?>
  <!-- ACTUAL PAGE -->
  <?php echo $content_for_layout; ?>
</div>

<!-- FOOTER -->
<?php echo $this->element('global/footer'); ?>

<!-- DEBUG -->
<?php echo $this->element('global/debug'); ?>

<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
</body>
</html>
