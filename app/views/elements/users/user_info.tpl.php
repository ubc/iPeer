<?php
if (!empty($userId)){
  $data = $this->controller->framework->getUser($userId);

  if (!isset($displayField)) $displayField = 'fullname';

  if (!empty($displayField)) {
    if ($displayField == 'fullname') { ?>
      <a href="<?php echo $this->webroot.$this->themeWeb.'framework/userInfoDisplay/'.$data['User']['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></a>

  <?php   } else {
      echo $data['User']['$displayField'];
    }
  }

}
?>