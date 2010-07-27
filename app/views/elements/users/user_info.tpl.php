<?php 
if(isset($user) || !empty($userId))
{
  if(isset($userId))
  {
    $user = $this->controller->framework->getUser($userId);
  }

  if (!isset($displayField)) $displayField = 'fullname';
?>

  <?php if($displayField == 'fullname'):?>
    <a href="<?php echo $this->webroot.$this->themeWeb.'framework/userInfoDisplay/'.$user['User']['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $user['User']['first_name'].' '.$user['User']['last_name'];?></a>
  <?php else:?> 
      <?php echo $user['User'][$displayField];?>
  <?php endif;?> 

<?php
}
