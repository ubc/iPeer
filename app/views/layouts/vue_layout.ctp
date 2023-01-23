<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" href="/img/favicon.png" type="image/png"/>
  <title>iPeer - <?php echo $title_for_layout; ?></title>
    
    <?php
    echo $this->Html->css('https://fonts.googleapis.com/css?family=Lato:400,400italic,700');
    echo $this->Html->css('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
    ?>
    <?php echo $this->Html->css('ipeer'); ?>

  <link rel="stylesheet" type="text/css" href="<?php Router::url(null, false); ?>vue/assets/css/main.css"/>
  <script type="text/javascript" defer
          src="<?php Router::url(null, false); ?>vue/assets/js/chunk-vendors.js"></script>
  <script type="text/javascript" defer
          src="<?php Router::url(null, false); ?>vue/assets/js/chunk-common.js"></script>
  <script type="text/javascript" defer src="<?php Router::url(null, false); ?>vue/assets/js/main.js"></script>
</head>
<body>
<noscript>
  <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled.
    Please enable it to continue.</strong>
</noscript>
<div class="layout containerOuter pagewidth">
  <!-- planning on keeping the global banner in vue_layout and _remove the click event off the logo -->
    <?php echo $this->element('global/banner', array('customLogo' => $customLogo)); ?>
  <!-- the vue_navigation will be teleported to replace the global navigation for reactivity -->
    <?php // echo $this->element('global/navigation', array()); ?>
  <main class="main">
    <!-- TBD if cake flash messaging is useful -->
      <?php echo $this->Session->flash('auth'); ?>
    <!-- this where the landing page will be rendered -->
      <?php echo $content_for_layout; ?>
  </main>
</div>
<!-- the global footer remains the same since its a static content -->
<?php echo $this->element('global/footer'); ?>
</body>
</html>
