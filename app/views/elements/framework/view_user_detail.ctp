<table class="userViewTable">
  <tr class="tablecell2">
    <td width="17%" id="username_label"><?php __('Username')?>:</td>
    <td width="33%" ><?php echo $data['User']['username']; ?></td>
    <td width="17%" id="username_label"> <?php __('Role')?>:
    <td width="33%" > <?php
        $role = $data['Role']['0']['name'];
        echo ucfirst($role);
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
    <td align="left"><?php echo $data['User']['creator'];?></td>
    <td id="updater_label"><?php __('Updater:')?></td>
    <td align="left"><?php echo $data['User']['updater'];?></td>
  </tr>
  <tr class="tablecell2">
    <td id="created_label"><?php __('Create Date')?>:</td>
    <td align="left"><?php echo $data['User']['created']; ?></td>
    <td id="updated_label"><?php __('Update Date')?>:</td>
    <td align="left"><?php echo $data['User']['modified']; ?></td>
  </tr>
  <tr class="tablecell2">
    <td colspan="4" align="center" class="whitebg">
    <form name="frm" id="frm" method="post" action="#">
      <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
    </form>
    </td>
  </tr>
</table>
