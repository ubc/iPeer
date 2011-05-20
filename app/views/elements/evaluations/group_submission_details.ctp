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
          <td width="15%">Group Number:</td>
          <td width="20%"><?php echo $group['Group']['group_num']?></td>
          <td width="15%">Group Name:</td>
          <td width="20%"><?php echo $group['Group']['group_name']?></td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
          </tr>
      </table>

    <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2" bgcolor="#FFFFFF">
      <tr class="tableheader">
  	    <th width="40%">Group Member</th>
  	    <th width="15%">Date Submitted</th>
  	    <th width="20%">Late By</th>
  	    <th width="15%">RE-release Evaluation?</th>
	    </tr>
      <?php $i = '0';  $enable = false;?>
	    <?php foreach($data as $row): $member = $row['users']; ?>
    	  <tr class="tablecell">
            <td><?php echo $member['first_name'].' '.$member['last_name'] ?></td>
            <td align="center"><?php
              if ($member['submitted']) {
                echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($member['date_submitted'])));
              }else {
                echo "(not submitted)";
              } ?></td>
            <td align="center"><?php
              if (isset($member['time_diff'])) {
                echo $member['time_diff']. ' day(s)';
              }else {
                echo $member['submitted'] ?  "(on time)" : "---";
              } ?></td>
            <td align="center"><?php
              if ($member['submitted']) {
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
             echo $html->submit('Re-release Selected Evaluation');
            } else {
             echo $html->submit('Re-release Selected Evaluation', array('disabled'=>1));
            } ?></td>
        </tr>
    </table>

    </form>
	</td>
  </tr>
</table>
