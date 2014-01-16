<?php
$numberQuesetions = array();
foreach ($mixeval['MixevalQuestion'] as $question) {
    if ($question['mixeval_question_type_id'] == '1' || $question['mixeval_question_type_id'] == '4') {
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

<?php
$grades = array();
foreach ($groupByQues as $ques) {
    if ($ques['self_eval'] && $ques['MixevalQuestionType']['type'] == 'Likert') {
        $grades[$ques['question_num']] = Set::extract('/Submissions/grade', $ques);
    }
}
if (!empty($grades)) { ?>
    <h3><?php echo __('Self-Evaluation', true) ?></h3>
    <table class="standardtable">
        <tr>
            <th><?php echo __('Question', true) ?></th>
            <th><?php echo __('Average Grade Per Question', true) ?></th>
        </tr>
        <?php foreach ($grades as $num => $grade) { ?>
            <tr><td><?php echo $groupByQues[$num]['title'] ?></td>
            <?php $avg = count($grade) ? array_sum($grade) / count($grade) : 0; ?>
            <td><?php echo number_format($avg, 2) .
                ' / '. $groupByQues[$num]['multiplier']?></td></tr>
        <?php } ?>
    </table>
<?php } ?>

<h3><?php __('Evaluation Results')?></h3>
<table class="standardtable">
    <tr>
        <th><?php __('Student Name:')?></th>
        <th> <?php __('Total:')?>( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</th>
    </tr>

    <?php 
    $users = array();
    foreach ($table as $row) {
        $users[] = array($row[0], end($row));
    } 
    echo $this->Html->tableCells($users);
    ?>

    <tr><td colspan="2"><?php echo $this->Evaluation->getReviewButton($event, 'Basic')?></td></tr>
</table>
