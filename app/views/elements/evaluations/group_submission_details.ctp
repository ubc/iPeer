<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('reReleaseEvaluation') ?>">
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="4" align="center">Group Details
        <input type="hidden" name="event_id" value="<?php echo $eventId?>" />
        <input type="hidden" name="group_id" value="<?php echo $group['Group']['id']?>" />
        <input type="hidden" name="group_event_id" value="<?php echo $groupEventId?>" /></td>
          </tr>
        <tr class="tablecell2">
          <td width="15%"><?php __('Group Number')?>:</td>
          <td width="20%"><?php echo $group['Group']['group_num']?></td>
          <td width="15%"><?php __('Group Name')?>:</td>
          <td width="20%"><?php echo $group['Group']['group_name']?></td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
          </tr>
      </table>

    <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2" bgcolor="#FFFFFF">
      <tr class="tableheader">
  	    <th width="40%"><?php __('Group Member')?></th>
  	    <th width="15%"><?php __('Date Submitted')?></th>
  	    <th width="20%"><?php __('Late By')?></th>
  	    <th width="15%"><?php __('RE-release Evaluation?')?></th>
	    </tr>
      <?php $i = '0';  $enable = false;?>
	    <?php foreach($data as $row): $member = $row['Member']; ?>
    	  <tr class="tablecell">
            <td><?php echo $member['first_name'].' '.$member['last_name'] ?></td>
            <td align="center"><?php
              if ($row['users']['submitted']) {
                echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($row['users']['date_submitted'])));
              }else {
                echo __("(not submitted)", true);
              } ?></td>
            <td align="center"><?php
              if (isset($row['users']['time_diff'])) {
                echo $row['users']['time_diff']. __(' day(s)', true);
              }else {
                echo $row['users']['submitted'] ?  __("(on time)", true) : "---";
              } ?></td>
            <td align="center"><?php
              if ($row['users']['submitted']) {
        				echo "<input type=\"checkbox\" name=\"release_member[]\" value=\"" . $member['id'] . "\" />";
        				$enable = true;
        			}
        			else {
        				echo "<input type=\"checkbox\" disabled />";
        			}
              ?></td>
          </tr>
    	  <?php $i++;?>
        <?php endforeach; ?>
        <tr class="tablecell">
          <td colspan="4" align="center"><?php
            if ($enable) {
             echo $form->submit(__('Re-release Selected Evaluation', true));
            } else {
             echo $form->submit(__('Re-release Selected Evaluation', true), array('disabled'=>1));
            } ?></td>
        </tr>
    </table>

    </form>
	</td>
  </tr>
</table>
