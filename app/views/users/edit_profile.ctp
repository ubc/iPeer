<table width="102%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
    <?php echo $this->Form->create('User', 
                                   array('id' => 'frm',
                                         'url' => array('action' => $this->action),
                                         'inputDefaults' => array('div' => false,
                                                                  'before' => '<td width="200px">',
                                                                  'after' => '</td>',
                                                                  'between' => '</td><td>')))?>

  <table width="95%" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td colspan="3"><div align="center"><b><?php echo empty($data['User']['id'])?'Add':'Edit' ?><?php __(' Profile')?>  </b></div></td>
	  </tr>
    <tr class="tablecell2">
    <?php echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT username_msg Invalid_Text._At_Least_One_Word_Is_Required.', 
                                                    'error' => array('unique' => __('Duplicate Username found. Please change the username.', true)),
                                                    'readonly' => true, 'label' => __('Username', true)));?>
      <td></td>
    </tr>
<?php //} ?>
    <!-- First Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('first_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('First Name', true))) ?>
      <td id="first_name_msg" class="error">&nbsp;</td>
    </tr>

    <!-- Last Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('last_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Last Name', true)))?>
      <td id="last_name_msg" class="error">&nbsp;</td>
    </tr>

    <!-- Email  -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('email', array('size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.', 'after' => '', 'label' => __('Email', true))) ?>
      <td id="email_msg" class="error">&nbsp;</td>
    </tr>

    <?php if($has_title):?>
    <!-- Title  -->
    <tr class="tablecell2 nonstudent_field">
      <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Title', true))) ?>
      <td id="title_msg" class="error">&nbsp;</td>
    </tr>
    <?php endif;?>

    <?php if($is_student):?>
    <!-- student no-->
    <tr class="tablecell2 student_field">
      <?php echo $this->Form->input('student_no', array('size'=>'50', 'class'=>'validate none', 'label' => __('Student No', true))) ?>
      <td id="student_no_msg" class="error">&nbsp;</td>
    </tr>
    <?php endif;?>
 <?php echo $this->Form->hidden('role', array('value'=>$data['User']['role'])); ?>
     
    <?php //if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) :?>
            <tr class="tablecell2">
              <td colspan="3"><b><?php __(' Change Password')?>: </b></td>
            </tr>
            <tr class="tablecell2">
              <td width="185" id="password_label"><?php __('Old Password')?>:</td>
              <td><?php echo $this->Form->password('old_password', array('id'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT password_msg Invalid_Password_Format.', 'value'=>'', 'disabled'=>$viewPage)) ?> </td>
              <td width="187" id="password_msg" class="error"/>
            </tr>
            <tr class="tablecell2">
              <td width="185" id="password_label"><?php __('New Password')?>:</td>
              <td><?php echo $this->Form->password('tmp_password', array('id'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT password_msg Invalid_Password_Format.', 'value'=>'', 'disabled'=>$viewPage)) ?> </td>
              <td width="187" id="password_msg" class="error"/>
            </tr>
            <tr class="tablecell2">
              <td width="185" id="password_label"><?php __('Confirm New Password')?>:</td>
              <td><?php echo $this->Form->password('confirm_password', array('id'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT password_msg Invalid_Password_Format.', 'value'=>'', 'disabled'=>$viewPage)) ?> </td>
              <td width="187" id="password_msg" class="error"/>
            </tr>
    <?php //endif;?>

    <tr class="tablecell2">
      <td colspan="3" align="center">
      <?php if ($viewPage==false):?>
        <?php echo $this->Form->submit(__('Save', true)) ?>
      <?php endif; ?>
        <div align="center"></div>
        <div align="center"></div></td>
      </tr>
  </table>
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
    <tr>
      <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
    </tr>
  </table>
<?php $this->Form->end();?>
  </td>
</tr>
</table>
