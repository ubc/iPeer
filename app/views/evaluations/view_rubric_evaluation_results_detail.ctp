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
</form>

<h3><?php __('Evaluation Results')?></h3>
<div id='rubric_result'>


<div id="accordion">
    <?php
    $groupAve = 0;
    array_pop($scoreRecords);
    foreach($scoreRecords as $userId => $row) {?>
        <div id="panel<?php echo $userId?>">
        <div id="panel<?php echo $userId?>Header" class="panelheader">
            <?php echo __('Evaluatee: ', true).$memberList[$userId];?>
        </div>
        <div style="height: 200px;text-align: center;" id="panel1Content" class="panelContent">
            <br><?php
                $scaled = $row['total'] * (1 - ($penalties[$userId] / 100));
                $deduction = number_format($row['total'] * ($penalties[$userId] / 100), 2);
                $percent = number_format($scaled/$rubric['Rubric']['total_marks']*100);

                echo __(" (Number of Evaluator(s): ",true).$row['evaluator_count'].")<br/>";
                echo __("Final Total: ",true).number_format($row['total'],2);
                $penalties[$userId] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".number_format($scaled, 2) :
                    $penaltyAddOn = '';
                echo $penaltyAddOn.' ('.$percent.'%)';
                // temporarily removed avgscorepercent
                /*if (isset($membersAry[$user['id']]['received_ave_score'])) {
                    $memberAvgScore = number_format($avgPerQues[$user['id']], 2);
                    $memberAvgScoreDeduction = number_format($avgPerQues[$user['id']] * $penalties[$user['id']] / 100, 2);
                    $memberAvgScoreScaled = number_format($avgPerQues[$user['id']] * (1 - ($penalties[$user['id']] / 100)), 2);
                    $memberAvgScorePercent = number_format($avgPerQues[$user['id']] * (1 - ($penalties[$user['id']] / 100)) * 100);
                } else {
                    $memberAvgScore = '-';
                    $memberAvgScorePercent = '-';
                }*/
                if ($scaled == $groupAve) {
                    echo "&nbsp;&nbsp;((".__("Same Mark as Group Average", true)." ))<br>";
                } else if ($scaled < $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font><br>";
                } else if ($scaled > $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font><br>";
                }
                // temporarily removed avgscorepercent
                /*echo __("Average Percentage Per Question: ", true);
                echo $memberAvgScore;
                $penalties[$user['id']] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$memberAvgScoreDeduction."</font> = ".$memberAvgScoreScaled :
                    $penaltyAddOn = '';
                echo $penaltyAddOn.' ('.$memberAvgScorePercent.'%)<br>';*/
                (isset($penalties[$user['id']]) && $penalties[$user['id']] > 0) ? $penaltyNotice = 'NOTE: <font color=\'red\'>'.$penalties[$user['id']].'% </font>Late Penalty<br>' :
                    $penaltyNotice = '<br>';
                echo $penaltyNotice;
                ?>
            <br><br>
        <table class="standardtable">
            <?php
            $multiplier = array_combine(Set::extract($rubric['RubricsCriteria'], '/criteria_num'), 
                Set::extract($rubric['RubricsCriteria'], '/multiplier'));
            echo $html->tableHeaders($this->Evaluation->getIndividualRubricHeader($rubric['RubricsCriteria']));
            //Retrieve the individual rubric detail
            if (isset($scoreRecords[$userId])) {
                $memberResult = $scoreRecords[$userId]['individual'];
                foreach ($memberResult AS $evaluator => $row) {
                    in_array($evaluator, Set::extract($notInGroup, '/User/id')) ? $class=' class="blue" ' : $class='   ';
                    echo "<tr><td".$class."width='15%'>".$memberList[$evaluator]."</td>";
                    $comment = array_pop($row);
                    foreach ($row as $num => $grade) {
                        echo '<td valign="middle"><br />';
                        //Points Detail
                        echo "<strong>".__('Points', true).": </strong>";
                        $lom = $grade["grade"];
                        $empty = $rubric["Rubric"]["lom_max"];
                        for ($v = 0; $v < $lom; $v++) {
                            echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
                            $empty--;
                        }
                        for ($t=0; $t < $empty; $t++) {
                            echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
                        }
                        echo "<br />";
                        //Grade Detail
                        echo "<strong>".__('Grade:', true)." </strong>";
                        echo $grade["grade"] . " / " . $multiplier[$num] . "<br />";
                        //Comments
                        echo "<br/><strong>".__('Comment:', true)." </strong>";
                        echo $grade["comment"];
                        echo "</td>";
                    }
                    echo "</tr>";
                    //General Comment
                    echo "<tr><td></td>";
                    $col = $rubric['Rubric']['criteria'] + 1;
                    echo "<td colspan=".$col."><strong>".__('General Comment:', true)." </strong><br>";
                    echo $comment;
                    echo "<br><br></td></tr>";
                }
            } ?>
    </table>
    <?php
        echo "<br>";
        //Grade Released
        if (isset($scoreRecords[$userId]['grade_released']) && $scoreRecords[$userId]['grade_released']) {?>

            <input type="button" name="UnreleaseGrades" value="<?php __('Unrelease Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$userId.';'.$event['GroupEvent']['id'].';0'; ?>'">
        <?php } else {?>
            <input type="button" name="ReleaseGrades" value="<?php __('Release Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$userId.';'.$event['GroupEvent']['id'].';1'; ?>'">
        <?php }

        //Comment Released
        if (isset($scoreRecords[$userId]['comment_released']) && $scoreRecords[$userId]['comment_released']) {?>
            <input type="button" name="UnreleaseComments" value="<?php __('Unrelease Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$userId.';'.$event['GroupEvent']['id'].';0'; ?>'">
        <?php } else { ?>
            <input type="button" name="ReleaseComments" value="<?php __('Release Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$userId.';'.$event['GroupEvent']['id'].';1'; ?>'">
        <?php } ?>
        
        <!-- /Auto Release Message-->
        <?php if ($event['Event']['auto_release']) {
            echo "<div id='autoRelease_msg' class='green'>";
            echo "<br>".__("Auto Release is ON, you do not need to manually release the grades and comments", true);
            echo "</div>";
        } ?>
    </div>
    </div>

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
</div>
