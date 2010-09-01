<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $javascript->link('ricobase')?>
<?php echo $javascript->link('ricoeffects')?>
<?php echo $javascript->link('ricoanimation')?>
<?php echo $javascript->link('ricopanelcontainer')?>
<?php echo $javascript->link('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<!-- Render Event Info table -->
<?php
$params = array('controller'=>'evaluations', 'event'=>$event);
echo $this->renderElement('evaluations/view_event_info', $params);
?>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
      <b> Summary:</b>(
      <a href="<?php echo $this->webroot.$this->themeWeb?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Basic">Basic</a>
       |
      <a href="<?php echo $this->webroot.$this->themeWeb?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Detail" >Detail</a>
        )
    </td>
  </tr>
	<?php $i = 0;
  if (!$allMembersCompleted) {?>
  <tr>
    <td colspan="3">
	      <font color="red">These student(s) have yet to submit their evaluations: <br>
	         <?php foreach($inCompletedMembers as $row): $user = $row['User']; ?>
	          &nbsp;-&nbsp; <?php echo $user['last_name'].' '.$user['first_name']?> <br>
	      <?php endforeach; ?>
      </font>
    </td>
  </tr>
<?php } ?>
</table>
<div id='mixeval_result'>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader">
		<td valign="middle">Student Name:</td>
    <td> Total:( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</td>
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
          $aveScoreSum += $memberScoreSummary[$member['User']['id']]['received_ave_score'];
       		echo number_format($memberScoreSummary[$member['User']['id']]['received_ave_score'], 2);
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
      echo "Group Average: ";
      echo "</b></td>";
      echo "<td><b>";
      if ( $allMembersCompleted ) {
      	echo number_format($aveScoreSum / count($groupMembers), 2);
      } else {
      	echo '-';
      }
      echo "</b></td>";
    }		?>
	</tr>
	<?php if ($allMembersCompleted) {?>
  <tr class="tablecell2" align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
			  <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
			  <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
			  <input type="hidden" name="course_id" value="<?php echo $rdAuth->courseId?>" />
			  <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>" />
			  <input type="hidden" name="display_format" value="Basic" />

      	<td colspan="<?php echo count($groupMembers) +1; ?>">
      	<?php
  				if ($event['group_event_marked'] == "reviewed") {
  					echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_not_reviewed\" value=\"Mark Peer Evaluations as Not Reviewed\" />";
  				}
  				else {
  					echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\"Mark Peer Evaluations as Reviewed\" />";
  				}
  			?></td>
			</form>
  </tr>
<?php } ?>
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
