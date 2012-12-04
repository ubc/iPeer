<h3><?php __('Evaluation Event Detail')?></h3>
<!-- Event Details Table -->
<table class="standardtable">
<tr>
    <th><?php __('Event Name')?></th>
    <th><?php __('Group')?></th>
    <th><?php __('Due Date')?></th>
    <th><?php __('Self-Evaluation')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php echo $event['group_name'] ?></td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
    <td><?php echo ($event['Event']['self_eval']) ? 'Yes' : 'No'?></td>
</tr>
</table>

<table class="standardtable">
<tr>
    <th><?php __('Description')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['description'] ?></td>
</tr>
</table>

<h3><?php __('Summary: ')?>
 (<?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Detail")?> )</h3>
    
<!-- Users who haven't done the evaluation yet table -->
<table class="standardtable">
<?php
if (!$allMembersCompleted) {
    echo $html -> tableHeaders(array(__('These people have not yet submitted their evaluations', true)), null, array('class' => 'red'));
    $incompletedMembersArr = array();
    $users = array();
    foreach ($inCompletedMembers as $row) {
        $user = $row['User'];
        array_push($incompletedMembersArr, $user['first_name'] . " " . $user['last_name']);
        $users[] = array($user['first_name'] . " " . $user['last_name'] . ($row['Role']['role_id'] == 4 ? ' (TA)' : ' (student)'));
    }
    echo $html -> tableCells($users);
}
?>
</table>

<!-- Show Evaluation Reuslts - Basic -->
 <h3><?php __('Evaluation Results')?></h3>
 
<table class='standardtable'>
   
   <th><?php __('Student Name')?></th>
   <th><?php __('Total: (/') ?> 
       <?php echo number_format($rubric['Rubric']['total_marks'], 2) . ")"; ?></th>
    
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

    //Display Average Scores
    echo '<tr class="tablesummary"><td><b>';
    echo __("Group Average: ", true);
    echo '</b></td>';
    echo '<td><b>';
    echo number_format($aveScoreSum / count($groupMembersNoTutors), 2);
    echo '</b></td>';
    ?>
    
    <?php if ($allMembersCompleted) {?>
    <tr class="tablecell2" align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
              <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
              <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
              <input type="hidden" name="course_id" value="<?php echo $courseId; ?>" />
              <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>" />
              <input type="hidden" name="display_format" value="<?php __('Basic')?>" />

              <td colspan="<?php echo count($groupMembers) + 1; ?>"><?php
            if ($event['group_event_marked'] == "reviewed") {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_not_reviewed\" value=\" " . __('Mark Peer Evaluations as Not Reviewed', true) . "\" />";
            } else {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" " . __('Mark Peer Evaluations as Reviewed', true) . "\" />";
            }
            ?></td>
            </form>
  </tr>
  <?php } ?>          
</table>



