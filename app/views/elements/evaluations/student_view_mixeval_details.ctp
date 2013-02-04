<?php
$numberQuestions = array();
$textQuestions = array();
foreach ($mixeval['Question'] as $question) {
    if ($question['mixeval_question_type_id'] != '1') {
        $textQuestions[] = $question;
    } else {
        $numberQuestions[] = $question;
    }
}

$showGrades = array_sum(Set::extract($evalResult, '/EvaluationMixeval/grade_release')) ||
    $tableType == 'evaluator' || $tableType == 'evaluatee';
$showComments = array_sum(Set::extract($evalResult, '/EvaluationMixeval/comment_release')) ||
    $tableType == 'evaluator' || $tableType == 'evaluatee';
?>

<table class="standardtable" style="margin-top: 2em;">
    <tr>
        <td colspan="<?php echo count($numberQuestions)+1?>"><b> <?php __('Section One:')?> </b></td>
    </tr>
    <?php echo $this->Html->tableHeaders($this->Evaluation->getResultTableHeader($numberQuestions, __('Person Being Evaluated', true))) ?>
    <?php echo $showGrades ? $this->Html->tableCells($this->Evaluation->getMixevalResultTable($evalResult, $memberList, $numberQuestions, array(), $tableType)) : '' ?>
</table>
<?php if (!$showGrades): ?>
<div style="text-align: center;color: red;">Grades Not Released Yet.</div>
<?php endif;?>

<!-- Section Two -->
<table class="standardtable" style="margin-top: 2em;">
    <tr>
        <td colspan="<?php echo count($textQuestions)+1?>"><b><?php __('Section Two')?>:</b></td>
    </tr>
    <?php echo $this->Html->tableHeaders($this->Evaluation->getResultTableHeader($textQuestions, __('Person Being Evaluated', true))) ?>
    <?php echo $showComments ? $this->Html->tableCells($this->Evaluation->getMixevalResultTable($evalResult, $memberList, $textQuestions, array(), $tableType)) : '' ?>
</table>
<?php if (!$showComments): ?>
<div style="text-align: center;color: red;">Comments Not Released Yet.</div>
<?php endif;?>
