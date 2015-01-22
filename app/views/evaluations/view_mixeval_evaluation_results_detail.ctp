<?php
$averagePerQuestion = array();
$numberQuestions = array();

foreach ($mixeval['MixevalQuestion'] as $question) {
    if ($question['mixeval_question_type_id'] == '1' || $question['mixeval_question_type_id'] == '4') {
        $numberQuestions[] = $question;
    }
}

$summaryTableData = $this->Evaluation->getSummaryTable($memberList, $mixevalDetails, $numberQuestions, $mixeval, $penalty, $notInGroup);
$groupAvg = end(end($summaryTableData));

echo $html->script('ricobase');
echo $html->script('ricoeffects');
echo $html->script('ricoanimation');
echo $html->script('ricopanelcontainer');
echo $html->script('ricoaccordion');

?>

<div class="content-container">

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
    $notInGroup = Set::extract('/User/id', $notInGroup);
}
?>
</table>
<br>
<!-- summary table -->
<table class="standardtable">
    <?php echo $html->tableHeaders($this->Evaluation->getSummaryTableHeader($mixeval['Mixeval']['total_marks'], $mixeval['MixevalQuestion']));?>
    <?php echo $html->tableCells(array_values($summaryTableData));?>
    <tr align="center">
        <td colspan="<?php echo (count($numberQuestions) + 2); ?>">
            <?php echo $this->Evaluation->getReviewButton($event, 'Detail')?>
        </td>
    </tr>
</table>
<!-- end of summary table -->

<?php if ($viewReleaseBtns) { ?>
<h3><?php echo __('Evaluation Results Release Status', true) ?></h3>
<table class="standardtable">
    <?php 
    echo $html->tableHeaders($this->Evaluation->getReleaseStatusTableHeader()); 
    echo $html->tableCells($this->Evaluation->getReleaseStatusTableButtons($memberList, Set::extract($notInGroup, '/User/id'), $grpEventId, $status));
    ?>
</table>
<?php } ?>

<h3><?php __('Evaluation Results')?></h3>

<div id="accordion">
    <?php if ($mixeval['Mixeval']['self_eval'] > 0 && $event['Event']['self_eval']) { ?>
    <div id="panelSelf" class="panelName">
        <div id="panelSelfHeader" class="panelheader">
            <?php echo __('Self-Evaluation', true)?>
        </div>
        <div style="height: 200px;text-align: center;" id="panel1Content" class="panelContent">
            <div id='mixeval_result'>
            <?php
            $zero_mark = $mixeval['Mixeval']['zero_mark'];
            $params = array('controller'=>'evaluations', 'questions'=>$groupByQues, 'zero_mark'=>$zero_mark,
                'gradeReleased'=>1, 'commentReleased'=>1, 'details'=>1, 'evaluatee'=>0, 
                'names'=>$memberList, 'notInGroup'=>$notInGroup, 'peer_eval' => 0, 'title' => 'Questions',
                'instructorMode' => 1);
            echo $this->element('evaluations/mixeval_details', $params);
            ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if ($mixeval['Mixeval']['peer_question'] > 0) { ?>
    <?php foreach ($mixevalDetails as $evaluteeId => $scores):?>
        <div id="panel<?php echo $evaluteeId?>" class="panelName">
            <div id="panel<?php echo $evaluteeId?>Header" class="panelheader">
                <?php echo __('Evaluatee', true).': '.$memberList[$evaluteeId]?>
            </div>
            <div style="height: 200px;text-align: center;" id="panel1Content" class="panelContent">
            <br><b><?php
            $scores = array_intersect_key($scores, $required);
            $deduction = number_format(array_sum($scores) * $penalty[$evaluteeId]/100, 2);
            $scaled = number_format(array_sum($scores) * (1 - $penalty[$evaluteeId]/100), 2);
            $percent = number_format($scaled/$mixeval['Mixeval']['total_marks'] * 100);
            $ave_deduction = number_format($this->Evaluation->array_avg($scores) * $penalty[$evaluteeId]/100, 2);
            $ave_scaled = number_format($this->Evaluation->array_avg($scores) * (1 - $penalty[$evaluteeId]/100), 2);
            $ave_marks = number_format($this->Evaluation->array_avg($scores), 2);
            echo __("Final Total", true).': '.number_format(array_sum($scores), 2);
            $penalty[$evaluteeId] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".$scaled :
                $penaltyAddOn = '';
            echo $penaltyAddOn.' ('.$percent.'%)';

            if (count($scores) > 0) {
                $memberAve = number_format(array_sum($scores), 2);
                $memberAvePercent = number_format($ave_scaled * 100);
            } else {
                $memberAve = '-';
                $memberAvePercent = '-';
            }
            $penalty[$evaluteeId] > 0 ? $ave_penaltyAddOn = ' - '."<font color=\"red\">".$ave_deduction."</font> = ".$ave_scaled :
                $ave_penaltyAddOn = '';
            $memberAverageAve = number_format(array_sum($scores), 2) * (100-$penalty[$evaluteeId])/100;
            if ($memberAverageAve == $groupAvg) {
                echo "&nbsp;&nbsp;<< ".__('Same Mark as Group Average', true)." >>";
            } else if ($memberAverageAve < $groupAvg) {
                echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font>";
            } else if ($memberAverageAve > $groupAvg) {
                echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font>";
            }
            ?> </b><br>
            <?php //echo __("Average Percentage Per Question: ", true);
            //echo $ave_marks.$ave_penaltyAddOn;
            //echo ' ('.$memberAvePercent .'%)';

            $penalty[$evaluteeId] > 0 ? $penaltyNotice = __('NOTE: ', true).'<font color=\'red\'>'.$penalty[$evaluteeId].
                '%</font>'.__(' Late Penalty', true) : $penaltyNotice = '';
            echo $penaltyNotice;
            ?>

            <?php
            $questions = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}');
            $zero_mark = $mixeval['Mixeval']['zero_mark'];
            foreach ($evalResult[$evaluteeId] as $eval) {
                $evaluator = $eval['EvaluationMixeval']['evaluator'];
                foreach ($eval['EvaluationMixevalDetail'] as $detail) {
                    $detail['evaluator'] = $evaluator;
                    $questions[$detail['question_number']]['Submissions'][] = $detail;
                }
            }

            $params = array('controller'=>'evaluations', 'questions'=>$questions, 'zero_mark'=>$zero_mark,
                'gradeReleased'=>1, 'commentReleased'=>1, 'details'=>1, 'evaluatee'=>$evaluteeId, 
                'names'=>$memberList, 'notInGroup'=>$notInGroup, 'peer_eval' => 1, 'title' => __('Questions', true),
                'instructorMode' => 1, 'event' => $event);
            echo $this->element('evaluations/mixeval_details', $params);
            ?>       
        </div>
    </div>
<?php endforeach; ?>
<?php } ?>
</div>
<script type="text/javascript"> new Rico.Accordion( 'accordion',
            {panelHeight:500,
            hoverClass: 'mdHover',
            selectedClass: 'mdSelected',
            clickedClass: 'mdClicked',
            unselectedClass: 'panelheader'});
</script>
</div>
