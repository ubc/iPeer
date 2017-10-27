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

<div class="oauth">
<?php if (User::hasPermission('controllers/Oauthclients')): ?>
<h2>OAuths</h2>
<!-- OAuth Client Credentials -->
<h3><?php __('OAuth Client Credentials')?>:</h3>
<?php if (count($clients) == 0 || User::hasPermission('controllers/oauthclients')) { ?>
    <?php echo $html->link(__('Add Client Credential', true), '/oauthclients/add', array('id' => 'add', 'class' => 'add-button')); ?>
<?php } ?>
<?php if (count($clients) > 0) { ?>
    <?php foreach ($clients as $key => $client) { ?>
        <p><label id=key><?php echo __('Key').': '.$client['OauthClient']['key'];?></label>
        <label id=secret><?php echo __('Secret').': '.$client['OauthClient']['secret'];?></label>
        <?php echo $this->Form->select('OauthClient.'.$key.'.enabled', $enabled, $client['OauthClient']['enabled'], array('empty' => false)); ?>
        <?php echo $this->Form->input('OauthClient.'.$key.'.id', array('value' => $client['OauthClient']['id'])); ?>
        <?php echo $html->link('X', '/oauthclients/delete/'.$client['OauthClient']['id'], array('id' => 'delete')); ?></p>
        <label id=comment><?php echo __('&nbsp;Comment: ').$client['OauthClient']['comment'];?></label>
    <?php } ?>
<?php } ?>
<?php endif; ?>
<?php if (User::hasPermission('controllers/Oauthtokens')): ?>
<!-- OAuth Token Credentials -->
<h3><?php __('OAuth Token Credentials')?>:</h3>
<?php echo $html->link(__('Add Token Credential', true), '/oauthtokens/add', array('id' => 'add', 'class' => 'add-button')); ?>
<?php if (count($tokens) > 0) { ?>
    <?php foreach ($tokens as $index => $token) { ?>
        <p><label id=key><?php echo __('Key').': '.$token['OauthToken']['key'];?></label>
        <label id=secret><?php echo __('Secret').': '.$token['OauthToken']['secret'];?></label>
        <?php echo __('Expires').': '.$token['OauthToken']['expires'];?>
        <?php echo $this->Form->select('OauthToken.'.$index.'.enabled', $enabled, $token['OauthToken']['enabled'], array('empty' => false)); ?>
        <?php echo $this->Form->input('OauthToken.'.$index.'.id', array('value' => $token['OauthToken']['id'])); ?>
        <?php echo $html->link('X', '/oauthtokens/delete/'.$token['OauthToken']['id'], array('id' => 'delete')); ?></p>
        <label id=comment><?php echo __('&nbsp;Comment: ').$token['OauthToken']['comment'];?></label>
    <?php } ?>
<?php } ?>
<?php endif;?>
</div>
<br>
<?php if ($viewPage==false): echo $this->Form->submit(__('Save', true));
    endif; ?>
<?php $this->Form->end();?>
