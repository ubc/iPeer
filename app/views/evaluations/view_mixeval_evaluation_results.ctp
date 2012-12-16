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
<?php echo $this->element('evaluations/summary_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h3><?php __('Evaluation Results')?></h3>
<table class="standardtable">
    <tr>
        <th valign="middle"><?php __('Student Name:')?></th>
        <th> <?php __('Total:')?>( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</th>
    </tr>

    <?php foreach($result as $name => $score):?>
    <tr><td><?php echo $name?></td><td><?php echo $score?></td></tr>
    <?php endforeach; ?>

    <tr class="tablesummary">
        <td><?php __("Group Average:");?></td>
        <td><?php echo ($allMembersCompleted ? number_format($aveScoreSum / count($groupMembers), 2) : '-')?></td>
    </tr>
    <?php if ($allMembersCompleted): ?>
    <tr align="center">
      <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
              <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
              <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
              <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
              <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
              <input type="hidden" name="display_format" value="Basic" />

        <td colspan="<?php echo count($groupMembersNoTutors) +1; ?>">
    <?php if ($event['group_event_marked'] == "reviewed"): ?>
        <input class="reviewed" type="submit" name="mark_not_reviewed" value="<?php __('Mark Peer Evaluations as Not Reviewed')?>" />
    <?php else: ?>
        <input class="reviewed" type="submit" name="mark_reviewed" value="<?php __('Mark Peer Evaluations as Reviewed')?>" />
    <?php endif; ?>
        </td>
      </form>
    </tr>
    <?php endif; ?>
</table>
</div>

</div>
