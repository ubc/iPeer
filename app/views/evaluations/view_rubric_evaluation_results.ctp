<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>
<?php echo $this->element('evaluations/summary_info', array('controller'=>'evaluations', 'event'=>$event));?>

<!-- Show Evaluation Reuslts - Basic -->
<h3><?php __('Evaluation Results')?></h3>

<table class='standardtable'>
   <th><?php __('Student Name')?></th>
   <th><?php __('Total') ?>
       : (/<?php echo number_format($rubric['Rubric']['total_marks'], 2); ?>)</th>

<?php
$aveScoreSum = 0;
//This section will display the evaluatees' name
//as display the average scores their peers gave them
//for various criteria
if ($groupMembersNoTutors) {
    foreach ($groupMembersNoTutors as $member) {
        //Name Section
        echo '<tr><td width="70%">' . $member['User']['first_name'] . " " . $member['User']['last_name'] . '</td>';

        //Scores Section
        if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
            $avgScore = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
            $penalty = number_format(($penalties[$member['User']['id']] / 100) * $avgScore, 2);
            $finalAvgScore = $avgScore - $penalty;
            $penalty > 0 ? $stringAddOn = ' - ' . "<font color=\"red\">" . $penalty . "</font> = " . number_format($finalAvgScore, 2) : $stringAddOn = '';
            $aveScoreSum += $finalAvgScore;
            echo '<td width="30%">' . number_format($memberScoreSummary[$member['User']['id']]['received_ave_score'], 2) . $stringAddOn . '</td></tr>';
        } else {
            echo '<td width="30%">-</td></tr>';
        }
    }
}
?>

    <!-- Display Average Scores -->
    <tr class="tablesummary">
    <td><b>
    <?php echo __("Group Average: ", true) ?>
    </b></td>
    <td><b>
    <?php echo number_format($aveScoreSum / count($groupMembersNoTutors), 2); ?>
    </b></td>


    <?php if ($allMembersCompleted) :?>
    <tr class="tablecell2" align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
              <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
              <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
              <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']; ?>" />
              <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
              <input type="hidden" name="display_format" value="<?php __('Basic')?>" />

              <td colspan="<?php echo count($groupMembers) + 1; ?>">
           <?php  if ($event['GroupEvent']['marked'] == "reviewed"): ?>
                 <input class="reviewed" type="submit" name="mark_not_reviewed" value="<?php __('Mark Peer Evaluations as Not Reviewed')?>" />
            <?php else: ?>
                  <input class="reviewed" type="submit" name="mark_reviewed" value="<?php __('Mark Peer Evaluations as Reviewed')?>" />
            </td>
            </form>
  </tr>
  <?php endif; endif; ?>
</table>



