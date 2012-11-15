<table class='event-detail full-size'>
  <tr class="tableheader"><td colspan="4" align="center"><?php __('Evaluation Event Detail')?></td></tr>
  <tr>
    <td width="15%"><?php __('Group')?>:</td>
    <td width="35%"><?php echo $event['group_name'] ?></td>
    <td width="15%"><?php __('Self-Evaluation')?>:</td>
    <td><?php echo ($event['Event']['self_eval']) ? 'Yes' : 'No'?></td>
  </tr>
  <tr>
    <td><?php __('Event Name')?>:</td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php __('Due Date')?>:</td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr>
    <td><?php __('Description')?>:&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
</table>
