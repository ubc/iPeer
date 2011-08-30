<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<!-- Render Event Info table -->
<?php
$params = array('controller'=>'evaluations', 'event'=>$event);
echo $this->element('evaluations/view_event_info', $params);
?>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
      <b> Summary:</b>(
      <a href="<?php echo $this->webroot.$this->theme?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Basic"><?php __('Basic')?></a>
       |
      <a href="<?php echo $this->webroot.$this->theme?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Detail" ><?php __('Detail')?></a>
        )
      <BR>
      <BR> <?php echo '<font size = "1" face = "arial" color = "red" >*Numerics in red denotes late submission penalty.</font>';?>
    </td>
  </tr>
	<?php $i = 0;
  if (!$allMembersCompleted) {?>
  <tr>
    <td colspan="3">
	      <font color="red"><?php __('These student(s) have yet to submit their evaluations:')?> <br>
	         <?php foreach($inCompletedMembers as $row): $user = $row['User']; ?>
	          &nbsp;-&nbsp; <?php echo $user['first_name'].' '.$user['last_name']?> <br>
	      <?php endforeach; ?>
      </font>
    </td>
  </tr>
<?php } ?>
</table>
<div id='rubric_result'>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader">
		<td valign="middle"><?php __('Student Name:')?></td>
    <td><?php __('Total:')?>( /<?php echo number_format($rubric['Rubric']['total_marks'], 2)?>)</td>
  </tr>
<?php
    $aveScoreSum = 0;
    //This section will display the evaluatees' name
    //as display the average scores their peers gave them
    //for various criteria
    if ($groupMembers) {
      foreach ($groupMembers as $member) {
      	echo '<tr class="tablecell2">';
      	echo '<td width="70%">' . $member['User']['first_name'] . ' ' . $member['User']['last_name'] . '</td>' . "\n";
      	//totals section
      	echo '<td width="30%">';
      	//if ($allMembersCompleted) {
      	if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
      	  $avgScore = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
		  $penalty = ($penalties[$member['User']['id']] / 100) * $avgScore;
		  $finalAvgScore = $avgScore - $penalty;
		  $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".$penalty."</font> = ".number_format($finalAvgScore, 2) :
		  				 $stringAddOn = '';
          $aveScoreSum += $finalAvgScore;
       	  echo number_format($memberScoreSummary[$member['User']['id']]['received_ave_score'], 2).$stringAddOn;
      	} else {
      		echo '-';
      	}
      	echo "</td>";
      	echo "</tr>";
      	//end scores
      }

      //averages
      echo '<tr class="tablesummary">';
      echo "<td><b>";
      echo __("Group Average: ", true);
      echo "</b></td>";
      echo "<td><b>";
      echo number_format($aveScoreSum / count($groupMembers), 2);
      
      echo "</b></td>";
    }		?>
	</tr>
	<?php if ($allMembersCompleted) {?>
  <tr class="tablecell2" align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
			  <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
			  <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
			  <input type="hidden" name="course_id" value="<?php echo $courseId; ?>" />
			  <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>" />
			  <input type="hidden" name="display_format" value="<?php __('Basic')?>" />

      	<td colspan="<?php echo count($groupMembers) +1; ?>"><?php
				if ($event['group_event_marked'] == "reviewed") {
					echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_not_reviewed\" value=\" ".__('Mark Peer Evaluations as Not Reviewed', true)."\" />";
				}
				else {
					echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
				}
			?></td>
			</form>
  </tr>
  <?php }?>
</table>
</div>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
	</td>
  </tr>
</table>
