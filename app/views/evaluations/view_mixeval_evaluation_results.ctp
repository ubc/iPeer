<?php
$result = array();

$aveScoreSum = 0;
//This section will display the evaluatees' name
//as display the average scores their peers gave them
//for various criteria
foreach ($groupMembersNoTutors as $member) {
    $score = 0;
    if (isset($memberScoreSummary[$member['User']['id']]['received_total_score'])) {
        $totalScore = $memberScoreSummary[$member['User']['id']]['received_total_score'];
        $penalty = number_format(($penalties[$member['User']['id']] / 100) * $totalScore, 2);
        $finalTotalScore = $totalScore - $penalty;
        $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".$penalty."</font> = ".number_format($finalTotalScore, 2) :
            $stringAddOn = '';
        $aveScoreSum += $finalTotalScore;
        $score = number_format($totalScore, 2).$stringAddOn;
    } else {
        $score = '-';
    }
    $result[$member['User']['first_name']." ".$member['User']['last_name']] = $score;
}
?>

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
        	        <li><?php echo $user['first_name']." ".$user['last_name'] . ($row['Role']['role_id']==4 ? ' (TA)' : ' (student)');?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<table class="eval-result full-size">
    <tr class="tableheader">
        <td valign="middle"><?php __('Student Name:')?></td>
        <td> <?php __('Total:')?>( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</td>
    </tr>

    <?php foreach($result as $name => $score):?>
    <tr><td><?php echo $name?></td><td><?php echo $score?></td></tr>
    <?php endforeach; ?>

    <tr class="tablesummary">
        <td><?php __("Group Average:");?></td>
        <td><?php echo ($allMembersCompleted ? number_format($aveScoreSum / count($groupMembers), 2) : '-')?></td>
    </tr>
    <?php if ($allMembersCompleted) {?>
    <tr class="tablecell2" align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
              <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
              <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
              <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
              <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>" />
              <input type="hidden" name="display_format" value="Basic" />

        <td colspan="<?php echo count($groupMembersNoTutors) +1; ?>">
    <?php if ($event['group_event_marked'] == "reviewed"): ?>
        <input class="reviewed" type="submit" name="mark_not_reviewed" value="<?php __('Mark Peer Evaluations as Not Reviewed')?>" />
    <?php else: ?>
        <input class="reviewed" type="submit" name="mark_reviewed" value="<?php __('Mark Peer Evaluations as Reviewed')?>" />";
    <?php endif; ?>
    </td>
    </form>
  </tr>
<?php } ?>
</table>
</div>

</div>
