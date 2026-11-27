<h2>User Information</h2>
<?php
if (!$canEdit) {
    echo '<p>'.__('You cannot change your profile here. If a detail is wrong, ask your instructor or an administrator.', true).'</p>';
}
$readonly = !$canEdit;
echo $this->Form->create('User', array('id' => 'EditProfile', 'url' => array('action' => $this->action)));
echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'readonly' => true, 'label' => __('Username', true)));
echo $this->Form->input('first_name', array('size'=>'50', 'readonly' => $readonly, 'label' => __('First Name', true)));
echo $this->Form->input('last_name', array('size'=>'50', 'readonly' => $readonly, 'label' => __('Last Name', true)));
echo $this->Form->input('email', array('size'=>'50', 'after' => '', 'readonly' => $readonly, 'label' => __('Email', true)));
if ($is_student) {
    echo $this->Form->input('student_no', array('size'=>'50', 'readonly' => !$studentNumberEditable, 'label' => __('Student Number', true)));
} else {
    echo $this->Form->input('title', array('size'=>'50', 'readonly' => $readonly, 'label' => __('Title', true)));
}
if ($canEdit && ($localPasswordsEnabled ?? true)): ?>
<h2><?php __('Change Password')?></h2>
<?php
echo $this->Form->input('old_password', array('type'=>'password', 'size'=>'50', 'label'=>__('Old Password', true)));
echo $this->Form->input('temp_password', array('type'=>'password', 'size'=>'50', 'label'=>__('New Password', true)));
echo $this->Form->input('confirm_password', array('type'=>'password', 'size'=>'50', 'label'=>__('Confirm New Password', true)));
endif;
?>

<br>
<?php
if ($canEdit) {
    echo $this->Form->submit(__('Save', true));
}
echo $this->Form->end();
?>
