<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
  <form name="frm" id="frm" method="post" action="<?php echo $html->url(empty($data['User']['id'])?'add':'edit') ?>">
    <table width="95%" align="center" cellpadding="4" cellspacing="2">
      <tr class="tableheader">
        <td colspan="4" align="center"><?php __('View User')?> </td>
      </tr>
      <tr class="tablecell2">
        <td width="12%" id="username_label"><?php __('Username')?>:</td>
        <td width="38%" ><?php echo $data['User']['username']; ?></td>
        <td width="12%" id="username_label"> <?php __('Role')?>:
        <td width="38%" > <?php
            $role = $data['User']['role'];
            switch ($role) {
                case "S" : echo __("Student", true); break;
                case "I" : echo __("Instructor", true); break;
                case "A" : echo __("Admin", true); break;
                default  : echo __("Unknown User Role ",true). $role;
            }
            ?></td>
      </tr>
      <tr class="tablecell2">
        <td id="last_name_label"><?php __('Last Name')?>:</td>
        <td align="left" colspan="3"><?php echo $data['User']['last_name']; ?> </td>
      </tr>
      <tr class="tablecell2">
        <td id="first_name_label"><?php __('First Name')?>:</td>
        <td align="left" colspan="3"><?php echo $data['User']['first_name']; ?> </td>
      </tr>
      <?php if ($data['User']['role'] == 'S'): ?>
      <tr class="tablecell2">
        <td id="student_no_label"><?php __('Student No.')?>:</td>
        <td align="left" colspan="3"><?php echo $data['User']['student_no']; ?> </td>
      </tr>
      <?php else: ?>
      <tr class="tablecell2">
        <td id="title_label"><?php __('Title')?>:</td>
        <td align="left" colspan="3"><?php echo $data['User']['title']; ?> </td>
      </tr>
      <?php endif;?>
      <tr class="tablecell2">
        <td id="email_label"><?php __('Email')?>:</td>
        <td align="left" colspan="3">
          <a href="mailto:<?php if(!empty($data['User']['email'])) echo $data['User']['email']; ?>">
          <?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email'));?></a>
          <?php echo $data['User']['email']; ?>
        </td>
      </tr>
      <tr class="tablecell2">
        <td id="creator_label"><?php __('Creator')?>:</td>
        <td align="left"><?php echo $data['User']['creator']//$this->element('users/user_info', array('data'=>$data['User']['creator_id']));?></td>
        <td id="updater_label"><?php __('Updater:')?></td>
        <td align="left"><?php echo $data['User']['updater']//$this->element('users/user_info', array('data'=>$data['User']['updater_id']));?></td>
      </tr>
      <tr class="tablecell2">
        <td id="created_label"><?php __('Create Date')?>:</td>
        <td align="left"><?php echo $data['User']['created']; ?></td>
        <td id="updated_label"><?php __('Update Date')?>:</td>
        <td align="left"><?php echo $data['User']['modified']; ?></td>
      </tr>
      <tr class="tablecell2">
        <td colspan="4" align="center">
        <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();"></td>
        </tr>
    </table>
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
      </tr>
    </table>
  </form>
  </td>
</tr>
</table>
