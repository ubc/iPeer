<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
    <div id="pop_container" class="containerOuter">
        <!-- BANNER -->
        <div id="bannerSmall" class="banner">
        <?php 
        echo $html->image('layout/small_header.gif', 
            array('alt' => 'iPeer Logo'));
        ?>
        </div>

        <!-- TITLE BAR -->
        <h1 class='title'><?php echo $title_for_layout;?></h1>

        <!-- ERRORS -->
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Session->flash('good'); ?>

        <!-- ACTUAL PAGE -->
        <?php echo $content_for_layout;?>
    </div>
</body>
</html>
