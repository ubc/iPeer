<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>iPeer - <?php echo $title_for_layout;?></title>
    <!-- Force IE back to standards mode when it ignores the doctype -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en" />
    <link rel="shortcut icon" href="img/favicon.png" type="image/png" />
    <?php
    // CSS files
    echo $html->css('https://fonts.googleapis.com/css?family=Lato:400,400italic,700');
    echo $html->css('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');

//
    echo $html->css('ipeer');
    echo $html->css('install');

    // Scripts
    // as prototype does not appear to be maintained anymore, we should
    // switch to jquery. Load jquery from Google.
    echo $html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');

    // Custom View Include Files
    echo $scripts_for_layout;
    ?>
  </head>
<body>

<div class='containerOuter pagewidth'>
<!-- BANNER -->
<?php echo $this->element('global/banner'); ?>

<!-- CONTENT -->
  <!-- TITLE BAR -->
  <h1 class='title'>
    <?php echo $title_for_layout;?>
  </h1>
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

</body>
</html>
