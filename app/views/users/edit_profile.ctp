<h2>User Information</h2>
<?php 
$sysParams = ClassRegistry::init('sys_parameters')->find('first', array('conditions' => array('parameter_code' => 'system.student_number')));
echo $this->Form->create('User', array('id' => 'EditProfile', 'url' => array('action' => $this->action)));
echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'readonly' => true, 'label' => __('Username', true)));
echo $this->Form->input('first_name', array('size'=>'50', 'label' => __('First Name', true)));
echo $this->Form->input('last_name', array('size'=>'50', 'label' => __('Last Name', true)));
echo $this->Form->input('email', array('size'=>'50', 'after' => '', 'label' => __('Email', true)));
if ($is_student) {
// student number
$studentNumReadOnly = ($sysParams['sys_parameters']['parameter_value'] == 'true') ? 'false' : 'true';
echo $this->Form->input('student_no', array('size'=>'50', 'readonly' => $studentNumReadOnly, 'label' => __('Student Number', true)));
} else {
// title
echo $this->Form->input('title', array('size'=>'50', 'label' => __('Title', true)));
} ?>
<h2><?php __('Change Password')?></h2>
<?php
echo $this->Form->input('old_password', array('type'=>'password', 'size'=>'50', 'label'=>__('Old Password', true), 'disabled'=>$viewPage));
echo $this->Form->input('temp_password', array('type'=>'password', 'size'=>'50', 'label'=>__('New Password', true), 'disabled'=>$viewPage));
echo $this->Form->input('confirm_password', array('type'=>'password', 'size'=>'50', 'label'=>__('Confirm New Password', true), 'disabled'=>$viewPage));
?>

<br>
<?php if ($viewPage==false): echo $this->Form->submit(__('Save', true));
    endif; ?>
<?php $this->Form->end();?>
