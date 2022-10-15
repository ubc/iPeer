<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>iPeer - <?php echo $title_for_layout; ?></title>
  <!-- Needed to force IE back to standards mode when it ignores the doctype -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="Content-Language" content="en" />
  <link rel="shortcut icon" href="/img/favicon.png" type="image/png" />
  
  <?php if($isLoggedIn && $loggedInUserRole == '5'): ?>
    <?php //echo $this->Html->css('ipeer'); ?>
    <?php if(Configure::read('debug')): ?>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.ts"></script>
  <?php else: ?>
    <?php echo $this->Html->css('vue/main.css', TRUE); ?>
    <?php //echo $this->Html->script('vue/index.js', TRUE); ?>

    <!--<link rel="stylesheet" href="http://localhost:8080/css/vue/main.css">-->
    <script type="module" src="http://localhost:8080/js/vue/index.js"></script>
    <!---->
    <?php // echo $this->Html->script('http://localhost:8080/vue/assets/js/index.js', TRUE); ?>
  <?php endif; ?>
  <?php else: ?>
    <?php
    // CSS files
    echo $html->css('datepicker');
    echo $html->css('jquery.dataTables');
    echo $html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/cupertino/jquery-ui.css');
    echo $html->css('https://fonts.googleapis.com/css?family=Lato:400,400italic,700');
    echo $html->css('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
    echo $this->Html->css('ipeer');
  
    // Scripts
    // as prototype does not appear to be maintained anymore, we should
    // switch to jquery. Load jquery from Google.
    echo $html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    echo $html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js');
    echo $html->script('jquery.dataTables.min');
    echo $html->script('fnGetHiddenNodes');
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
  
    <?php if (!empty($trackingId) && !empty($trackingId['SysParameter']['parameter_value'])): ?>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $trackingId['SysParameter']['parameter_value']; ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
    
        gtag('config', '<?php echo $trackingId['SysParameter']['parameter_value']; ?>');
      </script>>
    <?php endif; ?>
  <?php endif; ?>
  <style>
    .containerOuter {
      border-radius: 10px;
      border: 1px solid #ccc;
      padding: 2em;
      background: white;
      box-shadow: 0 0 25px rgba(85, 85, 85, 0.28);
      min-width: 540px;
    }
    .pagewidth {
      min-width: 780px;
      max-width: 1000px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 0.5em;
    }
    /* FOOTER */
    #footer {
      font-size: 0.72em;
      padding-top: 0.5em;
      color: #333;
      text-align: center;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>
  <div class='containerOuter pagewidth'>
  
  <?php if($isLoggedIn && $loggedInUserRole == '5'): ?>
    <?php //echo $this->element('global/navigation', array());?>
    <div id="webapp"></div>
  <?php else: ?>
    <!-- BANNER -->
    <?php echo $this->element('global/banner', array('customLogo' => $customLogo)); ?>
    <!-- NAVIGATION -->
    <?php echo $this->element('global/navigation', array());?>
    <!-- CONTENT -->
    <!-- TITLE BAR -->
    <h1 class='title'>
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
  <?php endif; ?>
</div>

<!-- FOOTER -->
<?php echo $this->element('global/footer'); ?>

<!-- DEBUG -->
<?php // echo $this->element('global/debug'); ?>

<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
</body>
</html>
