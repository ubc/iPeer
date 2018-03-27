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
  
  // check that a google tag manager container id is given
  $gtmHead = null;
  $gtmBody = null;
  if (!empty($gtmContainerId) && !empty($gtmContainerId['SysParameter']['parameter_value'])) {
      $gtmContainerId = $gtmContainerId['SysParameter']['parameter_value'];
      
      $gtmHead = "
        <!-- Google Tag Manager -->
          <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','$gtmContainerId');</script>
        <!-- End Google Tag Manager -->
        ";
      $gtmBody = '
          <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$gtmContainerId.'"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
          <!-- End Google Tag Manager (noscript) -->
        ';
  }
  
  echo $gtmHead;
  ?>
</head>
  
<body>
  <?php echo $gtmBody; ?>
<div class='containerOuter pagewidth'>
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
</div>

<!-- FOOTER -->
<?php echo $this->element('global/footer'); ?>

<!-- DEBUG -->
<?php echo $this->element('global/debug'); ?>

<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
<?php
// check that a google analytics tracking id is given
if (!empty($trackingId)) {
    $trackingId = $trackingId['SysParameter']['parameter_value'];
    // check whether a domain is given
    if (empty($domain) || empty($domain['SysParameter']['parameter_value'])) {
        // domain is not given - Classic Google Analytics
        echo "<script type='text/javascript'>
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '".$trackingId."']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>";
    } else {
        $domain = $domain['SysParameter']['parameter_value'];
        // domain is given - Beta Google Analytics
        echo "<script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', '".$trackingId."', '".$domain."');
            ga('send', 'pageview');
        </script>";
    }
}
?>
</body>
</html>
