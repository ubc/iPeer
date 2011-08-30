<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">

  <tr class="tableheader">
    <td colspan="4" align="center"><?php __('Evaluation Result Detail')?></td>
    </tr>
  <tr class="tablecell2">
    <td width="10%"><?php __('Evaluated By')?>:</td>
    <td width="25%"><?php echo $event['group_name'] ?></td>
    <td width="10%"><?php __('Self-Evaluation')?>:</td>
    <td width="25%"><?php
      if ($event['Event']['self_eval']) {
        echo __('Yes', true);
       } else {
        echo __('No', true);
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
 <?php if (isset($groupAve)) {?>
  <tr class="tablecell2">
    <td><?php __('Rating')?>: &nbsp;</td>
    <td colspan="3"><?php
      if ($gradeReleaseStatus) {
      	$finalAvg = $aveScore - $ratingPenalty;
      	($ratingPenalty > 0)?($stringAddOn = ' - '.'('."<font color=\"red\">".$ratingPenalty."</font>".')'."<font color=\"red\">*</font>".' = '.number_format($finalAvg, 2)):
      	                    $stringAddOn = '';
      						 
        echo number_format($aveScore, 2).$stringAddOn;
        echo "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ( )"."<font color=\"red\">*</font>"." : Denotes late penalty.";
      } else {
        echo __('Not Released', true);
      } ?></td>
  </tr>

  <tr class="tablecell2">
    <td><?php __('Group Average')?>:&nbsp;</td>
    <td colspan="3"><?php
      if ($gradeReleaseStatus) {
        echo number_format($groupAve, 2);
      } else {
        echo __('Not Released', true);
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