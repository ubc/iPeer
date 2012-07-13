<?php echo $this->Form->create('User', array('id' => 'frm',
                                            'url' => array('action' => $this->action),
                                            'inputDefaults' => array('div' => false,
                                                                    'before' => '<td width="200px">',
                                                                    'after' => '</td>',
                                                                    'between' => '</td><td>')))?>
  <table width="95%" align="center">
    <tr class="tablecell2">
      <?php echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'readonly' => true, 'label' => __('Username', true)));?>
    </tr>
    
    <!-- First Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('first_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('First Name', true))) ?>
      <td id="first_name_msg" class="error"></td>
    </tr>

    <!-- Last Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('last_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Last Name', true)))?>
      <td id="last_name_msg" class="error"></td>
    </tr>

    <!-- Email -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('email', array('size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.', 'after' => '', 'label' => __('Email', true))) ?>
      <td id="email_msg" class="error"></td>
    </tr>

    <?php if($is_student) {?>
    <!-- Student Number -->
    <tr class="tablecell2 student_field">
      <?php echo $this->Form->input('student_no', array('size'=>'50', 'class'=>'validate none', 'label' => __('Student Number', true))) ?>
      <td id="student_no_msg" class="error"></td>
    </tr>
    <?php } else {
        if(!$is_student) {?>
    <!-- Title -->
    <tr class="tablecell2 nonstudent_field">
      <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Title', true))) ?>
      <td id="title_msg" class="error"></td>
    </tr>
    <?php }}?>
    <tr class="tablecell2">
      <td colspan="3"><hr align=left width=100%><h3><?php __('Change Password')?>:</h3></td>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('old_password', array('type' => 'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg0 Invalid_Password_Format.', 'label'=>__('Old Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg0" class="error"/>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('tmp_password', array('type'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg1 Invalid_Password_Format.', 'label'=>__('New Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg1" class="error"/>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('confirm_password', array('type'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg2 Invalid_Password_Format.', 'label'=>__('Confirm New Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg2" class="error"/>
    </tr>

    <tr class="tablecell2">
      <td colspan="3" align="center">
      <?php if ($viewPage==false): echo $this->Form->submit(__('Save', true));
        endif; ?></td>
    </tr>
  </table>
<?php $this->Form->end();?>