<div class="content-container">

  <?php if (isset($data['failed_students'])) : ?>
    <div class="list-title"><?php __('User(s) failed on creation:')?></div>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['failed_students']));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) created successfully:', true);?>

  <?php if (isset($data['created_students'])) : ?>
    <div class="list-title"><?php echo $msg?></div>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['created_students'], 'showPasswords' => true));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) updated successfully:', true);?>

  <?php if (isset($data['updated_students'])) : ?>
    <div class="list-title"><?php echo $msg?></div>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['updated_students']));?>
  <?php endif; ?>

  <div class="button-row">
<?php 
echo $html->link(__('Back to User Add', true), '/users/add/');
echo " | ";
echo $html->link(__('Back to Student Import', true), "/users/import/$courseId");
echo " | ";
echo $html->link(__('Back to User Listing', true), '/users/index/');
echo " | ";
echo $html->link(__('Back to Course', true), "/courses/home/$courseId");
?>
  </div>

</div>
