<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h3><?php __('Summary')?>
  (<?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Detail")?> )</h3>

<table class="standardtable">
<?php if (!empty($inCompleteMembers)) {
    echo $this->Html->tableHeaders(array(__('Have not submitted their evaluations', true)), null, array('class' => 'red'));
    $users = array();
    foreach ($inCompleteMembers as $row) {
        $user = $row['User'];
        $users[] = array($user['full_name'] . ($row['Role'][0]['id'] == 4 ? ' (TA)' : ' (student)'));
    }
    echo $this->Html->tableCells($users);
} ?>
</table>
<table class="standardtable">
<?php
if (!empty($notInGroup)) {
    echo $this->Html->tableHeaders(array(__('Left the group, but had submitted or were evaluated', true)), null, array('class' => 'blue'));
    $users = array();
    foreach ($notInGroup as $row) {
        $user = $row['User'];
        $users[] = array($user['full_name'] . ($row['Role'][0]['id'] == 4 ? ' (TA)' : ' (student)'));
    }
    echo $this->Html->tableCells($users);
}
?>
</table>

<!-- Show Evaluation Reuslts - Basic -->
<h3><?php __('Evaluation Results')?></h3>

<table class='standardtable'>
   <th><?php __('Student Name')?></th>
   <th><?php __('Total') ?>
       : (/<?php echo number_format($rubric['Rubric']['total_marks'], 2); ?>)</th>

<?php
$aveScoreSum = 0;
$scores = array();
array_pop($scoreRecords);
//This section will display the average scores their peers gave them for various criteria
foreach ($scoreRecords as $userId => $member) {
    $tmp = array();
    in_array($userId, Set::extract($notInGroup,'/User/id')) ? $class = array('class' => 'blue') : $class = array();
    $tmp[] = array($memberList[$userId], $class);

    if (isset($scoreRecords[$userId]['total'])) {
        $avgScore = $scoreRecords[$userId]['total'];
        $penalty = number_format(($penalties[$userId] / 100) * $avgScore, 2);
        $finalAvgScore = $avgScore - $penalty;
        $penalty > 0 ? $stringAddOn = ' - ' . "<font color=\"red\">" . $penalty . "</font> = " . number_format($finalAvgScore, 2) : $stringAddOn = '';
        $aveScoreSum += $finalAvgScore;
        $tmp[] = number_format($scoreRecords[$userId]['total'], 2) . $stringAddOn;
    } else {
        $tmp[]='-';
    }
    $scores[] = $tmp;
}
echo $this->Html->tableCells($scores);
?>

<!-- Display Average Scores -->
<tr class="tablesummary">
<td><b><?php echo __("Group Average: ", true) ?></b></td>
<td><b><?php echo (count($scoreRecords) > 0) ? number_format($aveScoreSum / count($scoreRecords), 2) : 0; ?></b></td>

<tr><td colspan="2"><?php echo $this->Evaluation->getReviewButton($event, 'Basic')?></td></tr>
</table>



