<?php if(isset($data)):?>
<?php $displayField = ClassRegistry::init('User')->displayField;?>

<?php if(!isset($with_link) || $with_link):?>
  <?php echo $this->Html->link($data[$displayField], '/framework/userInfoDisplay/'.$data['id'], array('onClick' =>'wopen(this.href, "popup", 650, 500); return false;'))?>
<?php else:?> 
  <?php echo $data[$displayField];?>
<?php endif;?> 

<?php endif;?>
