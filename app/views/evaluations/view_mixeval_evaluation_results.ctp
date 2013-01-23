<?php
$numberQuesetions = array();
foreach ($mixeval['Question'] as $question) {
    if ($question['question_type'] == 'S') {
        $numberQuestions[] = $question;
    }
}
$table = $this->Evaluation->getSummaryTable($memberList, $mixevalDetails, $numberQuestions, $mixeval, $penalty, $notInGroup);
?>

<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h3><?php __('Summary')?>
  (<?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Detail")?> )</h3>

<table class="standardtable">
<?php if (!empty($inCompleteMembers)) {
    echo $this->Html->tableHeaders(array(__('Have not submitted their evaluations', true)), null, array('class' => 'red'));
    $incompletedMembersArr = array();
    $users = array();
    foreach ($inCompletedMembers as $row) {
        $user = $row['User'];
        array_push($incompletedMembersArr, $user['first_name'] . " " . $user['last_name']);
        $users[] = array($user['first_name'] . " " . $user['last_name'] . ($row['Role'][0]['id'] == 4 ? ' (TA)' : ' (student)'));
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
        $users[] = $memberList[$row];
    }
    echo $this->Html->tableCells($users);
}
?>
</table>

<h3><?php __('Evaluation Results')?></h3>
<table class="standardtable">
    <tr>
        <th valign="middle"><?php __('Student Name:')?></th>
        <th> <?php __('Total:')?>( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</th>
    </tr>

    <?php foreach ($table as $userId => $row):?>
    <tr><td><?php echo $row[0]?></td><td><?php echo end($row)?></td></tr>
    <?php endforeach; ?>

    <tr><td colspan="2"><?php echo $this->Evaluation->getReviewButton($event, 'Basic')?></td></tr>
</table>
