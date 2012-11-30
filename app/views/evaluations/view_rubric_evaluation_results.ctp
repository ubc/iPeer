<div class="content-container">
<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<div class="event-summary">
    <span class="instruction-icon"><?php __('Summary:')?> ( <?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Detail")?> )</span>
    <font size = "1" face = "arial" color = "red" >*Numerics in red denotes late submission penalty.</font>
    <?php if (!$allMembersCompleted): ?>
        <div class="incompleted">
          <?php __('These people have not yet submit their evaluations:')?>
            <ul>
                <?php foreach($inCompletedMembers as $row): $user = $row['User']; ?>
                    <li><?php echo $user['first_name']." ".$user['last_name']. ($row['Role']['role_id']==4 ? ' (TA)' : ' (student)');?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<table class="full-size">
    <tr class="tableheader">
        <td valign="middle"><?php __('Student Name:')?></td>
    <td><?php __('Total:')?>( /<?php echo number_format($rubric['Rubric']['total_marks'], 2)?>)</td>
  </tr>
<?php
    $aveScoreSum = 0;
    //This section will display the evaluatees' name
    //as display the average scores their peers gave them
    //for various criteria
    if ($groupMembersNoTutors) {
      foreach ($groupMembersNoTutors as $member) {
        echo '<tr class="tablecell2">';
      	echo '<td width="70%">' . $member['User']['first_name']." ".$member['User']['last_name'] . '</td>' . "\n";
      	//totals section
      	echo '<td width="30%">';
      	//if ($allMembersCompleted) {
      	if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
      	  $avgScore = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
		  $penalty = number_format(($penalties[$member['User']['id']] / 100) * $avgScore, 2);
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
      echo number_format($aveScoreSum / count($groupMembersNoTutors), 2);

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
              } else {
                  echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
              }
            ?></td>
            </form>
  </tr>
  <?php }?>
</table>
</div>
