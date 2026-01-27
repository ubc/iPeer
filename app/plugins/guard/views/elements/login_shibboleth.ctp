<?php if(!$is_logged_in):?>
    <a href="<?php echo $login_url?>">
      <?php if(isset($params['loginImageButton']) && !empty($params['loginImageButton'])):?>
        <img src="<?php echo $params['loginImageButton']?>" alt="<?php echo $params['loginTextButton']?>" />
      <?php else: ?>
        <?php echo $params['loginTextButton']?>
      <?php endif; ?>
    </a>
<?php else: ?>
    <?php echo $html->link('Logout', Router::url(array('plugin' => 'guard', 'controller' => 'guard', 'action' => 'logout'), true))?>
<?php endif;?>
