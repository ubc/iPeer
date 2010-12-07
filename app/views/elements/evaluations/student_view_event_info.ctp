<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">

  <tr class="tableheader">
    <td colspan="4" align="center">Evaluation Result Detail</td>
    </tr>
  <tr class="tablecell2">
    <td width="10%">Evaluated By:</td>
    <td width="25%"><?php echo $event['group_name'] ?></td>
    <td width="10%">Self-Evaluation:</td>
    <td width="25%"><?php
      if ($event['Event']['self_eval']) {
        echo 'Yes';
       } else {
        echo 'No';
       }
      ?></td>
  </tr>
  <tr class="tablecell2">
    <td>Event Name:</td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td>Due Date:</td>
    <td><?php echo $this->controller->Output->formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td>Description:&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
 <?php if (isset($groupAve)) {?>
  <tr class="tablecell2">
    <td>Rating:&nbsp;</td>
    <td colspan="3"><?php
      if ($gradeReleaseStatus) {
        echo number_format($aveScore, 2);
      } else {
        echo 'Not Released';
      } ?></td>
  </tr>

  <tr class="tablecell2">
    <td>Group Average:&nbsp;</td>
    <td colspan="3"><?php
      if ($gradeReleaseStatus) {
        echo number_format($groupAve, 2);
      } else {
        echo 'Not Released';
      }?></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="3" align="center"><?php
      if ($gradeReleaseStatus) {

      } else {
        echo '&nbsp;';
      }?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
</table>