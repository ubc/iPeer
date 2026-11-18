  <?php if (isset($data['failed_students'])) : ?>
    <h3 id='summary-failed'><?php __('User(s) failed on creation:')?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['failed_students'], 'labelledBy' => 'summary-failed'));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) created successfully:', true);?>

  <?php if (isset($data['created_users'])) : ?>
    <h3 id='summary-created'><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['created_users'], 'labelledBy' => 'summary-created', 'showPasswords' => (isset($showPasswords) ? $showPasswords : true) ));?>
  <?php endif; ?>

  <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('User(s) modified successfully:', true) : __('User(s) updated successfully:', true);?>

  <?php if (isset($data['updated_users'])) : ?>
    <h3 id='summary-updated'><?php echo $msg?></h3>
    <?php echo $this->element('users/user_summary_list', array('data'=>$data['updated_users'], 'labelledBy' => 'summary-updated'));?>
  <?php endif; ?>

  <?php if (!empty($dataInstructors)) : ?>
      <p>
      <!-- instructors / TAs -->
      <?php if (isset($dataInstructors['failed_users']) && !empty($dataInstructors['failed_users'])) : ?>
        <h3 id='summary-staff-failed'><?php __('Instructor(s)/TA(s) failed to add to the course.  Please check their Primary Role:')?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['failed_users'], 'labelledBy' => 'summary-staff-failed'));?>
      <?php endif; ?>

      <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('Instructor(s)/TA(s) modified successfully:', true) : __('Instructor(s)/TA(s) created successfully:', true);?>

      <?php if (isset($dataInstructors['created_users'])) : ?>
        <h3 id='summary-staff-created'><?php echo $msg?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['created_users'], 'labelledBy' => 'summary-staff-created', 'showPasswords' => (isset($showPasswords) ? $showPasswords : true) ));?>
      <?php endif; ?>

      <?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? __('Instructor(s)/TA(s) modified successfully:', true) : __('Instructor(s)/TA(s) updated successfully:', true);?>

      <?php if (isset($dataInstructors['updated_users'])) : ?>
        <h3 id='summary-staff-updated'><?php echo $msg?></h3>
        <?php echo $this->element('users/user_summary_list', array('data'=>$dataInstructors['updated_users'], 'labelledBy' => 'summary-staff-updated'));?>
      <?php endif; ?>
  <?php endif; ?>

<p>
<?php
echo $html->link(__('Back to Course', true), "/courses/home/$courseId");
?>
</p>
