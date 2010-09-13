<?php
//$a=print_r($group_data,true);
//echo "<pre>$a</a>";
?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="2" align="center">View Group</td>
          </tr>
          <tr class="tablecell2">
            <td width="265">Group Number:&nbsp;</td>
            <td><?php echo $data['Group']['group_num']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td>Group Name:&nbsp;</td>
            <td><?php echo $data['Group']['group_name']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td>Status:</td>
            <td><?php if( $data['Group']['record_status'] == "A" ) echo "Active"; else echo "Inactive"; ?></td>
          </tr>
          <tr class="tablecell2">
            <td valign="top">Members:</td>
            <td>
              <table width="100%" border="0" cellspacing="2" cellpadding="2">
              <?php if (!empty($group_data)) :
                    foreach($group_data as $row): $user = $row['users']?>
                  <tr>
                  <td width="15"><?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email'))?></td>
                  <td><a href="../../users/view/<?php echo $user['id']?>"><?php echo $user['last_name'].', '.$user['first_name']?></a><br></td>
                 </tr>
				      <?php endforeach;
                            else: echo "No members in this group.";
                            endif;?>
				      </table>
			      </td>
          </tr>
        </table>

        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table>

        <table width="95%"  border="0" cellspacing="2" cellpadding="4">
          <tr>
            <td width="45%">
              <table width="403" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td colspan="2"><?php echo $html->linkTo('Edit this Group', '/groups/edit/'.$data['Group']['id']); ?> | <?php echo $html->linkTo('Back to Group Listing', '/groups/index/'.$rdAuth->courseId); ?></td>
              </tr>
              </table>
            </td>
            <td align="right" width="55%"></td>
          </tr>
        </table>

    </td>
  </tr>
</table>
