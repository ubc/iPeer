<table width="102%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td><form name="frm" id="frm" method="POST" action="<?php echo $html->url('editProfile') ?>" onSubmit="return validate()">
  <?php echo empty($params['data']['User']['id']) ? null : $html->hidden('User/id'); ?> <?php echo empty($params['data']['User']['role']) ? null: $html->hidden('User/role'); ?>
  <input type="hidden" name="required" id="required" value="last_name first_name" />
  <?php echo empty($params['data']['User']['id']) ? $html->hidden('User/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('User/updater_id', array('value'=>$rdAuth->id)); ?>
  <table width="95%" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td colspan="3"><div align="center"><b><?php echo empty($params['data']['User']['id'])?'Add':'Edit' ?> Profile  </b></div></td>
	  </tr>
<?php if (isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL) { ?>
    <!-- For CWL customization, no need to display username -->
<?php } else {?>
    <tr class="tablecell2">
      <td width="185" id="newuser_label">Username:*</td>

      <td width="502"><?php echo $html->hidden('User/username'); ?>
        <?php echo "<input type='text' size='50' disabled='true' value='" . $params['data']['User']['username'] . "'>"; ?>

        </td>
      <td></td>
    </tr>
<?php } ?>
    <tr class="tablecell2">
      <td width="185" id="last_name_label">Last Name:</td>
      <td width="502"><?php echo $html->input('User/last_name', array('id'=>'last_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'disabled'=>$viewPage))?></td>
      <td width="187" id="last_name_msg" class="error"/>
    </tr>
    <tr class="tablecell2">
      <td width="185" id="first_name_label">First Name:</td>
      <td><?php echo $html->input('User/first_name', array('id'=>'first_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'disabled'=>$viewPage)) ?> </td>
      <td width="187" id="first_name_msg" class="error"/>
    </tr>
    <?php if ($params['data']['User']['role'] == 'S')  :?>
    <tr class="tablecell2">
      <td width="185" id="student_no_label">Student No.:</td>
<?php if (isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL) { ?>
      <td><?php echo $html->input('User/student_no', array('id'=>'student_no', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT student_no_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'disabled'=>true)) ?> </td>
<?php } else {?>
      <td><?php echo $html->input('User/student_no', array('id'=>'student_no', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT student_no_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'disabled'=>$viewPage)) ?> </td>
<?php } ?>

      <td width="187" id="student_no_msg" class="error"/>
    </tr>
    <?php else: ?>
    <tr class="tablecell2">
      <td width="185" id="title_label">Title:</td>
      <td><?php echo $html->input('User/title', array('id'=>'title', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'disabled'=>$viewPage)) ?> </td>
      <td width="187" id="title_msg" class="error"/>
    </tr>
    <?php endif;?>
    <tr class="tablecell2">
      <td width="185" id="email_label">Email:</td>
      <td><?php echo $html->input('User/email', array('id'=>'email', 'size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.', 'disabled'=>$viewPage)) ?> </td>
      <td width="187" id="email_msg" class="error"/>
    </tr>
<?php if (($params['data']['User']['role'] == 'S'))  {
         if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) :?>
            <tr class="tablecell2">
              <td colspan="2"><b> Change Password: </b></td>
            </tr>
            <tr class="tablecell2">
              <td width="185" id="password_label">Password:</td>
              <td><?php echo $html->password('User/password', array('id'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT password_msg Invalid_Password_Format.', 'value'=>'', 'disabled'=>$viewPage)) ?> </td>
              <td width="187" id="password_msg" class="error"/>
            </tr>
<?php    endif; ?>
<?php  } else { ?>
            <tr class="tablecell2">
              <td colspan="3"><b> Change Password: </b></td>
            </tr>
            <tr class="tablecell2">
              <td width="185" id="password_label">Password:</td>
              <td><?php echo $html->password('User/password', array('id'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT password_msg Invalid_Password_Format.', 'value'=>'', 'disabled'=>$viewPage)) ?> </td>
              <td width="187" id="password_msg" class="error"/>
            </tr>
<?php  }  ?>


    <tr class="tablecell2">
      <td colspan="3" align="center"><?php if ($viewPage=='false'):?>
        <?php echo $html->submit('Save') ?>
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
  </form></td>
</tr>
</table>
