<?php 
echo $html->script('ricobase');
echo $html->script('ricoeffects');
echo $html->script('ricoanimation');
echo $html->script('ricopanelcontainer');
echo $html->script('ricoaccordion');
?>
<div class="content-container">
<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>
<br>

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

<!-- summary table -->
<form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
    <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
    <input type="hidden" name="group_id" value="<?php echo $event['Group']['id'] ?>" />
    <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
    <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
    <input type="hidden" name="display_format" value="Detail" />
<br>
<?php if (!empty($scoreRecords)) { ?>
<table class="standardtable">
    <?php echo $html->tableHeaders($this->Evaluation->getRubricSummaryTableHeader($rubric['Rubric']['total_marks'], $rubric['RubricsCriteria']));?>
    <?php echo $html->tableCells($this->Evaluation->getRubricSummaryTable($memberList, Set::extract($notInGroup, '/User/id'), $scoreRecords, $penalties, $rubric['Rubric']['total_marks'])); ?>
    <tr align="center"><td colspan="<?php echo $rubric['Rubric']['criteria']+2; ?>">
        <?php
            if ($event['GroupEvent']['marked'] == "reviewed") {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_not_reviewed\" value=\" ".__('Mark Peer Evaluations as Not Reviewed', true)."\" />";
            } else {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
            }
        ?>
    </td></tr>
</table>
<?php } ?>
</form>

<?php if ($viewReleaseBtns) { ?>
<h3><?php echo __('Evaluation Results Release Status', true) ?></h3>
<table class="standardtable">
    <?php 
    echo $html->tableHeaders($this->Evaluation->getReleaseStatusTableHeader()); 
    echo $html->tableCells($this->Evaluation->getReleaseStatusTableButtons($memberList, Set::extract($notInGroup, '/User/id'), $grpEventId, $scoreRecords));
    ?>
</table>
<?php } ?>

<h3><?php echo __('Evaluation Results', true) ?></h3>
<div id='rubric_result'>

<?php if (!empty($scoreRecords)) { ?>
<div id="accordion">
    <?php
    $groupAve = 0;
    array_pop($scoreRecords);
    foreach ($scoreRecords as $userId => $row) {
        $scaled[$userId] = $row['total'] * (1 - ($penalties[$userId] / 100));
    }
    $groupAve = array_sum($scaled) / count($scaled);
    foreach($scoreRecords as $userId => $row) {?>
        <div id="panel<?php echo $userId?>" class="panelName">
        <div id="panel<?php echo $userId?>Header" class="panelheader">
            <?php echo __('Evaluatee: ', true).$memberList[$userId];?>
        </div>
        <div style="height: 200px;text-align: center;" id="panel1Content" class="panelContent">
            <br><?php
                $deduction = number_format($row['total'] * ($penalties[$userId] / 100), 2);
                $percent = number_format($scaled[$userId]/$rubric['Rubric']['total_marks']*100);

                echo __(" (Number of Evaluator(s): ",true).$row['evaluator_count'].")<br/>";
                echo __("Final Total: ",true).number_format($row['total'],2);
                $penalties[$userId] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".number_format($scaled[$userId], 2) :
                    $penaltyAddOn = '';
                echo $penaltyAddOn.' ('.$percent.'%)';
                if ($scaled[$userId] == $groupAve) {
                    echo "&nbsp;&nbsp;((".__("Same Mark as Group Average", true)." ))<br>";
                } else if ($scaled[$userId] < $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font><br>";
                } else if ($scaled[$userId] > $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font><br>";
                }
                (isset($penalties[$userId]) && $penalties[$userId] > 0) ? $penaltyNotice = 'NOTE: <font color=\'red\'>'.$penalties[$userId].'% </font>Late Penalty<br>' :
                    $penaltyNotice = '<br>';
                echo $penaltyNotice;
                ?>
            <br><br>
            <?php
            $multiplier = array_combine(Set::extract($rubric['RubricsCriteria'], '/criteria_num'), 
                Set::extract($rubric['RubricsCriteria'], '/multiplier'));
            $headers = $this->Evaluation->getIndividualRubricHeader($rubric['RubricsCriteria']);
            $params = array(
                'evaluatee' => $userId,
                'multiplier' => $multiplier,
                'headers' => $headers,
                'result' => $scoreRecords[$userId]['individual'],
                'notInGroup' => Set::extract($notInGroup, '/User/id'),
                'memberList' => $memberList,
                'rubric' => $rubric['Rubric'],
                'viewReleaseBtns' => $viewReleaseBtns,
            );
            echo $this->element('evaluations/rubric_details', $params); 
            ?>
    </div>
    </div>

    <?php } ?>
</div>
<?php } else { ?>
<h4><?php echo __('No submissions have been made.', true); ?></h4>
<?php } ?>

<script type="text/javascript"> new Rico.Accordion( 'accordion',
                                    {panelHeight:500,
                                    hoverClass: 'mdHover',
                                    selectedClass: 'mdSelected',
                                    clickedClass: 'mdClicked',
                                    unselectedClass: 'panelheader'});
</script>
</div>
</div>
