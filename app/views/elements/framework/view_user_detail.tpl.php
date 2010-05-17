<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
  <h4><?php echo empty($params['data']['User']['id']) ? null : $html->hidden('User/id'); ?> <?php echo empty($params['data']['User']['role']) ? null: $html->hidden('User/role'); ?>    </h4>
  <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['User']['id'])?'add':'edit') ?>">
    <table width="95%" align="center" cellpadding="4" cellspacing="2">
      <tr class="tableheader">
        <td colspan="4" align="center">View User </td>
      </tr>
      <tr class="tablecell2">
        <td width="30%" id="username_label">Username:</td>
        <td width="70%" align="left" colspan="3"><?php echo $data['User']['username']; ?></td>
      </tr>
      <tr class="tablecell2">
        <td id="last_name_label">Last Name:</td>
        <td align="left" colspan="3"><?php echo $data['User']['last_name']; ?> </td>
      </tr>
      <tr class="tablecell2">
        <td id="first_name_label">First Name:</td>
        <td align="left" colspan="3"><?php echo $data['User']['first_name']; ?> </td>
      </tr>
      <?php if ($data['User']['role'] == 'S'): ?>
      <tr class="tablecell2">
        <td id="student_no_label">Student No.:</td>
        <td align="left" colspan="3"><?php echo $data['User']['student_no']; ?> </td>
      </tr>
      <?php else: ?>
      <tr class="tablecell2">
        <td id="title_label">Title:</td>
        <td align="left" colspan="3"><?php echo $data['User']['title']; ?> </td>
      </tr>
      <?php endif;?>
      <tr class="tablecell2">
        <td id="email_label">Email:</td>
        <td align="left" colspan="3"><?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email'));?>
          <a href="mailto:<?php echo $data['User']['email']; ?>"><?php echo $data['User']['email']; ?></a>
           </td>
      </tr>
      <tr class="tablecell2">
        <td>&nbsp;</td>
        <td align="left" colspan="3">&nbsp;</td>
      </tr>
      <tr class="tablecell2">
        <td id="creator_label">Creator:</td>
        <td align="left"><?php
        $params = array('controller'=>'courses', 'userId'=>$data['User']['creator_id']);
        echo $this->renderElement('users/user_info', $params);
        ?></td>
        <td id="updater_label">Updater:</td>
        <td align="left"><?php
        $params = array('controller'=>'courses', 'userId'=>$data['User']['updater_id']);
        echo $this->renderElement('users/user_info', $params);
        ?></td>
      </tr>
      <tr class="tablecell2">
        <td id="created_label">Created:</td>
        <td align="left"><?php echo $data['User']['created']; ?></td>
        <td id="updated_label">Updated:</td>
        <td align="left"><?php echo $data['User']['modified']; ?></td>
      </tr>
      <tr class="tablecell2">
        <td colspan="4" align="center">
		    <input type="button" name="Close" value="Close" onClick="javascript:window.close();"></td>
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
