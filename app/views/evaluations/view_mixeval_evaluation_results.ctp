<?php
$textQuestions = array();
foreach ($mixevalQuestion as $question) {
    if ($question['MixevalsQuestion']['question_type'] != 'S') {
        $textQuestions[] = $question;
    } else {
        $numberQuestions[] = $question;
    }
}
$memberList = Set::combine($event, 'Member.{n}.id', 'Member.{n}.full_name');
$table = $this->Evaluation->getSummaryTable($memberList, $scoreRecords, $numberQuestions, $mixeval, $penalties);
?>

<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>
<?php echo $this->element('evaluations/summary_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h3><?php __('Evaluation Results')?></h3>
<table class="standardtable">
    <tr>
        <th valign="middle"><?php __('Student Name:')?></th>
        <th> <?php __('Total:')?>( /<?php echo number_format($mixeval['Mixeval']['total_marks'], 2)?>)</th>
    </tr>

    <?php foreach ($table as $row):?>
    <tr><td><?php echo $row[0]?></td><td><?php echo end($row)?></td></tr>
    <?php endforeach; ?>

    <tr><td colspan="2"><?php echo $this->Evaluation->getReviewButton($event, 'Basic')?></td></tr>
</table>
