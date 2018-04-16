  <?php if (isset($data['failed_students'])) : ?>
    <h3><?php __('User(s) failed on creation:')?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['failed_students']));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) created successfully:', true);?>

  <?php if (isset($data['created_users'])) : ?>
    <h3><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['created_users'], 'showPasswords' => (isset($showPasswords) ? $showPasswords : true) ));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) updated successfully:', true);?>

  <?php if (isset($data['updated_users'])) : ?>
    <h3><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['updated_users']));?>
  <?php endif; ?>

  <?php if (!empty($dataInstructors)) : ?>
      <p>
      <!-- instructors / TAs -->
      <?php if (isset($dataInstructors['failed_users']) && !empty($dataInstructors['failed_users'])) : ?>
        <h3><?php __('Instructor(s)/TA(s) failed to add to the course.  Please check their Primary Role:')?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['failed_users']));?>
      <?php endif; ?>

      <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('Instructor(s)/TA(s) modified successfully:', true) : __('Instructor(s)/TA(s) created successfully:', true);?>

      <?php if (isset($dataInstructors['created_users'])) : ?>
        <h3><?php echo $msg?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['created_users'], 'showPasswords' => (isset($showPasswords) ? $showPasswords : true) ));?>
      <?php endif; ?>

      <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('Instructor(s)/TA(s) modified successfully:', true) : __('Instructor(s)/TA(s) updated successfully:', true);?>

      <?php if (isset($dataInstructors['updated_users'])) : ?>
        <h3><?php echo $msg?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['updated_users']));?>
      <?php endif; ?>
  <?php endif; ?>

<p>
<?php
echo $html->link(__('Back to Course', true), "/courses/home/$courseId");
?>
</p>
