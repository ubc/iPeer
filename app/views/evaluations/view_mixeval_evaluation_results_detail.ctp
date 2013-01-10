<?php
$color = array("#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$averagePerQuestion = array();
$numberQuestions = array();
$textQuestions = array();
foreach ($mixevalQuestion as $question) {
    if ($question['MixevalsQuestion']['question_type'] != 'S') {
        $textQuestions[] = $question;
    } else {
        $numberQuestions[] = $question;
    }
}
$memberList = Set::combine($event, 'Member.{n}.id', 'Member.{n}.full_name');

$summaryTableData = $this->Evaluation->getSummaryTable($memberList, $scoreRecords, $numberQuestions, $mixeval, $penalties);
$groupAvg = end(end($summaryTableData));

echo $html->script('ricobase');
echo $html->script('ricoeffects');
echo $html->script('ricoanimation');
echo $html->script('ricopanelcontainer');
echo $html->script('ricoaccordion');
?>

<div class="content-container">

<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>
<?php echo $this->element('evaluations/summary_info', array('controller'=>'evaluations', 'event'=>$event));?>

<!-- summary table -->
<table class="standardtable">
    <?php echo $html->tableHeaders($this->Evaluation->getSummaryTableHeader($mixeval['Mixeval']['total_marks'], $mixevalQuestion));?>
    <?php echo $html->tableCells($summaryTableData);?>
    <tr align="center">
        <td colspan="<?php echo (count($numberQuestions) + 2); ?>">
            <?php echo $this->Evaluation->getReviewButton($event, 'Detail')?>
        </td>
    </tr>
</table>
<!-- end of summary table -->

<h3><?php __('Evaluation Results')?></h3>

<div id="accordion">
    <?php foreach ($scoreRecords as $evaluteeId => $scores):?>
        <div id="panel<?php echo $evaluteeId?>">
            <div id="panel<?php echo $evaluteeId?>Header" class="panelheader">
                <?php echo __('Evaluatee', true).': '.$memberList[$evaluteeId]?>
            </div>
            <div style="height: 200px;text-align: center;" id="panel1Content" class="panelContent">
            <br><b><?php
            $deduction = number_format(array_sum($scores) * $penalties[$evaluteeId]/100, 2);
            $scaled = number_format(array_sum($scores) * (1 - $penalties[$evaluteeId]/100), 2);
            $percent = number_format($scaled/$mixeval['Mixeval']['total_marks'] * 100);
            $ave_deduction = number_format(array_avg($scores) * $penalties[$evaluteeId]/100, 2);
            $ave_scaled = number_format(array_avg($scores) * (1 - $penalties[$evaluteeId]/100), 2);
            echo __("Final Total", true).': '.number_format(array_sum($scores), 2);
            $penalties[$evaluteeId] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".$scaled :
                $penaltyAddOn = '';
            echo $penaltyAddOn.' ('.$percent.'%)';

            if (count($scores) > 0) {
                $memberAve = number_format(array_sum($scores), 2);
                $memberAvePercent = number_format($ave_scaled * 100);
            } else {
                $memberAve = '-';
                $memberAvePercent = '-';
            }
            $penalties[$evaluteeId] > 0 ? $ave_penaltyAddOn = ' - '."<font color=\"red\">".$ave_deduction."</font> = ".$ave_scaled :
                $ave_penaltyAddOn = '';
            $memberAverageAve = number_format(array_sum($scores), 2);
            if ($memberAverageAve == $groupAvg) {
                echo "&nbsp;&nbsp;<< ".__('Same Mark as Group Average', true)." >>";
            } else if ($memberAverageAve < $groupAvg) {
                echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font>";
            } else if ($memberAverageAve > $groupAvg) {
                echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font>";
            }
            ?> </b><br>
            <?php echo __("Average Percentage Per Question: ", true);
            echo $memberAve.$ave_penaltyAddOn;
            echo ' ('.$memberAvePercent .'%)';

            $penalties[$evaluteeId] > 0 ? $penaltyNotice = '<br>'.__('NOTE: ', true).'<font color=\'red\'>'.$penalties[$evaluteeId].
                '%</font>'.__(' Late Penalty', true) : $penaltyNotice = '';
            echo $penaltyNotice;
            ?>
            <br><br>

            <!-- Section One -->
            <table class="standardtable">
                <tr>
                    <td colspan="<?php echo count($numberQuestions)+1?>"><b> <?php __('Section One:')?> </b></td>
                </tr>
                <?php echo $this->Html->tableHeaders($this->Evaluation->getResultTableHeader($numberQuestions, __('Evaluator', true))) ?>
                <?php echo $this->Html->tableCells($this->Evaluation->getMixevalResultTable($evalResult[$evaluteeId], $memberList, $numberQuestions)) ?>
            </table>
            <br />

            <!-- Section Two -->
            <table class="standardtable">
                <tr>
                    <td colspan="<?php echo count($textQuestions)+1?>"><b><?php __('Section Two')?>:</b></td>
                </tr>
                <?php echo $this->Html->tableHeaders($this->Evaluation->getResultTableHeader($textQuestions, __('Evaluator', true))) ?>
                <?php echo $this->Html->tableCells($this->Evaluation->getMixevalResultTable($evalResult[$evaluteeId], $memberList, $textQuestions)) ?>
            </table>

            <br />
            <?php
            //Grade Released
            if (isset($evalResult[$evaluteeId][0]['EvaluationMixeval']['grade_release']) && $evalResult[$evaluteeId][0]['EvaluationMixeval']['grade_release']) {?>
                <input type="button" name="UnreleaseGrades" value="<?php __('Unrelease Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$evaluteeId.';'.$event['GroupEvent']['id'].';0'; ?>'">
            <?php } else {?>
                <input type="button" name="ReleaseGrades" value="<?php __('Release Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$evaluteeId.';'.$event['GroupEvent']['id'].';1'; ?>'">
            <?php }

            //Comment Released
            if (isset($evalResult[$evaluteeId][0]['EvaluationMixeval']['comment_release']) && $evalResult[$evaluteeId][0]['EvaluationMixeval']['comment_release']) {?>
                <input type="button" name="UnreleaseComments" value="<?php __('Unrelease Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$evaluteeId.';'.$event['GroupEvent']['id'].';0'; ?>'">
            <?php } else {?>
                <input type="button" name="ReleaseComments" value="<?php __('Release Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$evaluteeId.';'.$event['GroupEvent']['id'].';1'; ?>'">
            <?php } ?>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script type="text/javascript"> new Rico.Accordion( 'accordion',
            {panelHeight:500,
            hoverClass: 'mdHover',
            selectedClass: 'mdSelected',
            clickedClass: 'mdClicked',
            unselectedClass: 'panelheader'});

</script>
</div>
