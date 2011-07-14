<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="3" align="center">View Email</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">To:&nbsp;</td>
            <td><?php
              $last_item = end($data['User']);
              foreach($data['User'] as $user){
                $user = $user['User'];
                echo $html->link($user['full_name'], '/users/view/'.$user['id'], array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"));
                if($user != $last_item['User'])
                  echo ', ';
              }
            ?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">Subject:&nbsp;</td>
            <td><?php echo $data['EmailSchedule']['subject'];?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">Scheduled on:&nbsp;</td>
            <td><?php echo $data['EmailSchedule']['date'];?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">Sent? :&nbsp;</td>
            <td><?php echo $data['EmailSchedule']['sent'] == 1 ?
                     $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
                     $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">Content:&nbsp;</td>
            <td><?php echo $form->textarea('Email.content', array(
                  'id' => 'email_content',
                  'lable' => "Content: ",
                  'value' => $data['EmailSchedule']['content'],
                  'cols' => '60',
                  'rows' => '15',
                  'escape' => false,
                  'readonly' => true
                ));?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="15%">Created:&nbsp;</td>
            <td><?php echo $data['EmailSchedule']['created'];?>
            </td>
            <td>&nbsp;</td>
          </tr>
        </table>
    </td>
  </tr>
</table>