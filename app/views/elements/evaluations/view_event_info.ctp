<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="4" align="right"><a href="" onClick="window.close();"><?php __('[X] Close Window')?></a></td>
    </tr>
  <tr class="tableheader">
    <td colspan="4" align="center"><?php __('Evaluation Event Detail')?></td>
    </tr>
  <tr class="tablecell2">
    <td width="10%"><?php __('Group')?>:</td>
    <td width="25%"><?php echo $event['group_name'] ?>
    </td>
    <td width="10%"><?php __('Self-Evaluation')?>:</td>
    <td width="25%"><?php
            if ($event['Event']['self_eval']) {
              echo 'Yes';
             } else {
              echo 'No';
             }
            ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Event Name')?>:</td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php __('Due Date')?>:</td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Description')?>:&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
</table>