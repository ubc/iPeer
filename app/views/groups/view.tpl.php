<?php
//$a=print_r($group_data,true);
//echo "<pre>$a</a>";
?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="2" align="center">
                View Group
            </td>
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
				<?php
				echo '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
				foreach($group_data as $row): $user = $row['users'];
					echo '<tr>';
					echo '<td width="15">'.$html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email')).'</td><td>';
					echo '<a href="../../users/view/'.$user['id'].'">'.$user['last_name'].', '.$user['first_name'].'</a><br>';
					echo '</td></tr>';
				endforeach;
				echo '</table>';
				?>
			</td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table>      </form></td>
  </tr>
</table>
