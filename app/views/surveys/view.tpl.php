<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="2" align="center">
                View Survey
            </td>
          </tr>
          <tr class="tablecell2">
            <td width="265">Survey Title:&nbsp;</td>
            <td><?php echo $data['Survey']['name']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td>Assigned Course:&nbsp;</td>
            <td><?php
				echo '<a href=../../courses/view/'.$data['Survey']['course_id'].'>'
					  .$data['Survey']['course_name'].'</a>';
				?></td>
          </tr>
          <tr class="tablecell2">
            <td>Creator:</td>
            <td><?php
				echo '<a href=../../users/view/'.$data['Survey']['user_id'].'>'
					  .$data['Survey']['user_name'].'</a>';
				?></td>
          </tr>
          <tr class="tablecell2">
            <td>Due Date:</td>
            <td><?php echo $data['Survey']['due_date']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td valign="top">Release Date:</td>
            <td>
			<table width="294" border="0" cellspacing="2" cellpadding="4">
            <tr>
              <td width="48" valign="top">From:</td>
              <td width="224"><?php echo $data['Survey']['release_date_begin'] ?></td>
            </tr>
            <tr>
              <td valign="top">To:</td>
              <td><?php echo $data['Survey']['release_date_end'] ?></td>
            </tr>
          </table>
			</td>
          </tr>
          <tr class="tablecell2">
            <td>Created:</td>
            <td><?php echo $data['Survey']['created']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td>Modified:</td>
            <td><?php echo $data['Survey']['modified']; ?></td>
          </tr>
          <tr class="tablecell2">
            <td colspan="2" align="center"><input type="button" name="Back" value="Back" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller']; ?>'"></td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table></td>
  </tr>
</table>
