  <?php if (isset($data['failed_students'])) : ?>
    <h3><?php __('User(s) failed on creation:')?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['failed_students']));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) created successfully:', true);?>

  <?php if (isset($data['created_students'])) : ?>
    <h3><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['created_students'], 'showPasswords' => (isset($showPasswords) ? $showPasswords : true) ));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) updated successfully:', true);?>

  <?php if (isset($data['updated_students'])) : ?>
    <h3><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['updated_students']));?>
  <?php endif; ?>

<p>
<?php
echo $html->link(__('Back to Course', true), "/courses/home/$courseId");
?>
</p>
