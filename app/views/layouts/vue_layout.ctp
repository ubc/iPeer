<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iPeer - <?php echo $title_for_layout; ?></title>
    <!-- Needed to force IE back to standards mode when it ignores the doctype -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="Content-Language" content="en"/>
    <link rel="shortcut icon" href="/img/favicon.png" type="image/png"/>
    
    <?php if ($isLoggedIn && $loggedInUserRole == '5'): ?>
        <?php echo $this->Html->css('vue/main.css', TRUE); ?>
        <script type="module" src="/js/vue/index.js"></script>
    <?php else: ?>
        <?php echo $this->Html->css('ipeer'); ?>
    <?php endif; ?>
</head>
<body>
<?php if ($isLoggedIn && $loggedInUserRole == '5'): ?>
    <div id="webapp" class="layout containerOuter pagewidth"></div>
<?php else: ?>
    <div class="containerOuter pagewidth">
        <?php echo $this->element('global/banner', array('customLogo' => $customLogo)); ?>
        <?php echo $this->element('global/navigation', array()); ?>
        <h1 class='title'>
            <?php if (isset($breadcrumb)): ?>
                <?php echo $breadcrumb->render($html); ?>
            <?php else: ?>
                <?php echo $title_for_layout; ?>
            <?php endif; ?>
        </h1>
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Session->flash('good'); ?>
        <?php echo $content_for_layout; ?>
    </div>
<?php endif; ?>
</body>
</html>
