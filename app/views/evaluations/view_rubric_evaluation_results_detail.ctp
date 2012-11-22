<?php echo $html->script('ricobase');
echo $html->script('ricoeffects');
echo $html->script('ricoanimation');
echo $html->script('ricopanelcontainer');
echo $html->script('ricoaccordion');
?>
<div class="content-container">
<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<div class="event-summary">
    <span class="instruction-icon"><?php __('Summary:')?> ( <?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['group_id']."/Detail")?> )</span>
    <font size = "1" face = "arial" color = "red" >*Numerics in red denotes late submission penalty.</font>
    <?php if (!$allMembersCompleted): ?>
        <div class="incompleted">
          <?php __('These people have not yet submit their evaluations:')?>
            <ul>
                <?php foreach($inCompletedMembers as $row): $user = $row['User']; ?>
                    <li><?php echo $user['full_name'] . ($row['Role']['role_id']==4 ? ' (TA)' : ' (student)');?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div id='rubric_result'>

<?php
$rowspan = count($groupMembersNoTutors) + 3;
$numerical_index = 1;  //use numbers instead of words; get users to refer to the legend
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$membersAry = array();  //used to format result (students)
$withTutorsAry = array(); //used to format result (students,tutors)
$groupAve = 0;
$groupAverage = array_fill(1, $rubric['Rubric']['criteria'], 0);
?>
<!-- summary table -->
<?php echo '<table width="100%" border="0" align="center" cellpadding="4" cellspacing="2" class="outer-table">'; ?>
    <tr>
        <td width="25%" valign="middle" class="result-header-td"><?php __('Student Name:')?></td>
		<?php echo '<td width="75%" rowspan="'.$rowspan.'" class="inner-table-cell"><div class="scrollbar"><table class="inner-table"><tr class="result-header-td">';
            for ($i = 1; $i <= $rubric['Rubric']["criteria"]; $i++) {
                echo "<td width='200' class='inner-table-cell'>";
                echo '<strong><font color="' . $color[ $i % sizeof($color) ] . '">' . $numerical_index . ". </font></strong>";
                echo "(" .$rubricCriteria[$i-1]['multiplier']. ")";
                echo "</td>";
                $numerical_index++;
            }

        echo '<td width="250" class="inner-table-cell">'.__("Total:( /", true).number_format($rubric['Rubric']['total_marks'], 2).')' ?></td>
    </tr>
    <?php
    $aveScoreSum = 0;
    //This section will display the evaluatees' name
    //as display the average scores their peers gave them
    //for various criteria
    $questionSum = array_fill(0, $rubric['Rubric']['criteria'], 0);
    if ($groupMembersNoTutors) {
        foreach ($groupMembersNoTutors as $member) {
            $membersAry[$member['User']['id']] = $member;
            echo '<tr class="result-cell">';
            if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
                $avgScore = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
                $penalty = number_format(($penalties[$member['User']['id']] / 100) * $avgScore, 2);
                $penalty_percent = $penalties[$member['User']['id']] / 100;
                $questionIndex = 0;
                foreach ($scoreRecords[$member['User']['id']]['rubric_criteria_ave'] AS $criteriaAveIndex => $criteriaAveGrade) {
                    $scaledQuestionGrade = $criteriaAveGrade * (1 - $penalty_percent);
                    $groupAverage[$criteriaAveIndex] += $scaledQuestionGrade;
                    $deduction = $criteriaAveGrade * $penalty_percent;
                    $questionSum[$questionIndex] += $scaledQuestionGrade;
                    $questionIndex++;
                    $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".number_format($deduction, 2).
                        "</font> = ".number_format($scaledQuestionGrade, 2).'</td>' :
                        $stringAddOn = '';
                    echo '<td class="result-cell">' . number_format($criteriaAveGrade, 2).$stringAddOn;
                }
            } else {
                for ($i = 1; $i <= $rubric['Rubric']["criteria"]; $i++) {
                    echo "<td class='result-cell'>-</td>";
                }
            }
		// for calculating average percentage per question (ratio)
        $ratio = 0;
        for ($i = 0; $i < $rubric['Rubric']["criteria"]; $i++) {
            if (!empty($scoreRecords[$member['User']['id']]['rubric_criteria_ave']))
                $ratio += $scoreRecords[$member['User']['id']]['rubric_criteria_ave'][$i+1] / $rubricCriteria[$i]['multiplier'];
        }
        $avgPerQues[$member['User']['id']] = $ratio /  $rubric['Rubric']['criteria'];
        //totals section
        echo '<td class="total-cell">';
        if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
            $finalAvgScore = $avgScore - $penalty;
            $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".$penalty."</font> = ".number_format($finalAvgScore, 2) :
                $stringAddOn = '';
            $aveScoreSum += $finalAvgScore;
            echo number_format($avgScore, 2).$stringAddOn;
            $receviedAvePercent = $finalAvgScore / $rubric['Rubric']['total_marks'] * 100;
            echo ' ('.number_format($receviedAvePercent) . '%)';
            $membersAry[$member['User']['id']]['received_ave_score'] = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
            $membersAry[$member['User']['id']]['received_ave_score_%'] = $receviedAvePercent;
        } else {
            echo '-';
        }
        echo "</td>";
        echo "</tr>";
        //end scores
        }

        //averages
        echo '<tr class="tablesummary">';
        $questionIndex = 0;
        foreach ($groupAverage AS $sum) {
            echo '<td class="total-cell">' . number_format($sum / count($groupMembersNoTutors), 2). "</td>";
        }
        echo "<td><b>";
        $groupAve = number_format($aveScoreSum / count($groupMembersNoTutors), 2);
        echo $groupAve;
        echo ' ('.number_format($groupAve / $rubric['Rubric']['total_marks'] * 100) . '%)';

        echo "</b></td>";
    } ?>
    </tr></table></td></tr>
    <?php
    if ($groupMembers) {
        foreach ($groupMembers as $member) {
            $withTutorsAry[$member['User']['id']]['first_name'] = $member['User']['first_name'];
            $withTutorsAry[$member['User']['id']]['last_name'] = $member['User']['last_name'];
        }
    }
    if ($groupMembersNoTutors) {
        foreach ($groupMembersNoTutors as $member) {
            echo '<tr class="tablecell2" cellpadding="4" cellspacing="2" >';
            $membersAry[$member['User']['id']]['member'] = $member;
            echo '<td width="25%" class="group-members">' . $member['User']['full_name'] . '</td></tr>' . "\n";
        }
    }
    echo '<tr class="tablesummary"><td class="group-members"><b>';
    echo __("Group Average: ", true);
    echo "</b></td></tr><tr><td> </td></tr>";
    ?>
    <tr><td>  </td></tr>
    <tr class="tablecell2" align="center"><td colspan="<?php echo $rubric['Rubric']["criteria"] +2; ?>">
        <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
            <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
            <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>" />
            <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>" />
            <input type="hidden" name="display_format" value="Detail" />

      	<?php
            if ($event['group_event_marked'] == "reviewed") {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_not_reviewed\" value=\" ".__('Mark Peer Evaluations as Not Reviewed', true)."\" />";
            } else {
                echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
            }
        ?>
        </form></td>
    </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr>
        <td align="center">
<div id="accordion">
    <?php $i = 0;
    foreach($groupMembersNoTutors as $row):
        $user = $row['User']; ?>
        <div id="panel<?php echo $user['id']?>">
        <div id="panel<?php echo $user['id']?>Header" class="panelheader">
            <?php echo __('Evaluatee: ', true).$user['full_name']?>
        </div>
        <div style="height: 200px;" id="panel1Content" class="panelContent">
            <br><b><?php
                $scaled = $membersAry[$user['id']]['received_ave_score'] * (1 - ($penalties[$user['id']] / 100));
                $deduction = number_format($membersAry[$user['id']]['received_ave_score'] * ($penalties[$user['id']] / 100), 2);
                $percent = number_format($membersAry[$user['id']]['received_ave_score_%']);

                echo __(" (Number of Evaluator(s): ",true).count($evalResult[$user['id']]).")<br/>";
                echo __("Final Total: ",true).number_format($memberScoreSummary[$row['User']['id']]['received_ave_score'],2);
                $penalties[$user['id']] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".number_format($scaled, 2) :
                    $penaltyAddOn = '';
                echo $penaltyAddOn.' ('.$percent.'%)';
                if (isset($membersAry[$user['id']]['received_ave_score'])) {
                    $memberAvgScore = number_format($avgPerQues[$user['id']], 2);
                    $memberAvgScoreDeduction = number_format($avgPerQues[$user['id']] * $penalties[$user['id']] / 100, 2);
                    $memberAvgScoreScaled = number_format($avgPerQues[$user['id']] * (1 - ($penalties[$user['id']] / 100)), 2);
                    $memberAvgScorePercent = number_format($avgPerQues[$user['id']] * (1 - ($penalties[$user['id']] / 100)) * 100);
                } else {
                    $memberAvgScore = '-';
                    $memberAvgScorePercent = '-';
                }
                if ($scaled == $groupAve) {
                    echo "&nbsp;&nbsp;((".__("Same Mark as Group Average", true)." ))<br>";
                } else if ($scaled < $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font><br>";
                } else if ($scaled > $groupAve) {
                    echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font><br>";
                }
                echo __("Average Percentage Per Question: ", true);
                echo $memberAvgScore;
                $penalties[$user['id']] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$memberAvgScoreDeduction."</font> = ".$memberAvgScoreScaled :
                    $penaltyAddOn = '';
                echo $penaltyAddOn.' ('.$memberAvgScorePercent.'%)<br>';
                $penalties[$user['id']] > 0 ? $penaltyNotice = 'NOTE: <font color=\'red\'>'.$penalties[$user['id']].'% </font>Late Penalty<br>' :
                    $penaltyNotice = '<br>';
                echo $penaltyNotice;
                ?> </b>
            <br><br>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
            <tr class="tableheader" align="center">
                <td width="100" valign="top"><?php __('Evaluator')?></td>
                <?php
                for ($i=1; $i<=$rubric['Rubric']["criteria"]; $i++) {
                    echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
                    echo $rubricCriteria[$i-1]['criteria'];
                    echo "</td>";
                }
                ?>
            </tr>
            <?php
            //Retrieve the individual rubric detail
            if (isset($evalResult[$user['id']])) {

                $memberResult = $evalResult[$user['id']];

                foreach ($memberResult AS $row): $memberRubric = $row['EvaluationRubric'];
                    $evalutor = $withTutorsAry[$memberRubric['evaluator']];
                    echo "<tr class=\"tablecell2\">";
                    echo "<td width='15%'>".$evalutor['full_name'] ."</td>";

                    $resultDetails = $memberRubric['details'];
                    foreach ($resultDetails AS $detail) : $rubDet = $detail['EvaluationRubricDetail'];
                    $i = 0;
                    echo '<td valign="middle">';
                    echo "<br />";
                    //Points Detail
                    echo "<strong>".__('Points', true).": </strong>";
                    if (isset($rubDet)) {
                        $lom = $rubDet["selected_lom"];
                        $empty = $rubric["Rubric"]["lom_max"];
                        for ($v = 0; $v < $lom; $v++) {
                            echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
                            $empty--;
                        }
                        for ($t=0; $t < $empty; $t++) {
                            echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
                        }
                        echo "<br />";
                    } else {
                        echo "n/a<br />";
                    }
                //Grade Detail
                echo "<strong>".__('Grade:', true)." </strong>";
                if (isset($rubDet)) {
                    echo $rubDet["grade"] . " / " . $rubricCriteria[$i]['multiplier'] . "<br />";
                    $i++;
                } else {
                    echo "n/a<br />";
                }
                //Comments
                echo "<br/><strong>".__('Comment:', true)." </strong>";
                if (isset($rubDet)) {
                    echo $rubDet["criteria_comment"];
                } else {
                    echo "n/a<br />";
                }
                echo "</td>";
            endforeach;

            echo "</tr>";
            //General Comment
            echo "<tr class=\"tablecell2\">";
            echo "<td></td>";
            $col = $rubric['Rubric']['criteria'] + 1;
            echo "<td colspan=".$col.">";
            echo "<strong>".__('General Comment:', true)." </strong><br>";
            echo $memberRubric['general_comment'];
            echo "<br><br></td>";
            echo "</tr>";
        endforeach;
        } ?>
    </table>
    <?php
        echo "<br>";
        //Grade Released
        if (isset($scoreRecords[$user['id']]['grade_released']) && $scoreRecords[$user['id']]['grade_released']) {?>

            <input type="button" name="UnreleaseGrades" value="<?php __('Unrelease Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['group_id'].';'.$user['id'].';'.$event['group_event_id'].';0'; ?>'">
        <?php } else {?>
            <input type="button" name="ReleaseGrades" value="<?php __('Release Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['group_id'].';'.$user['id'].';'.$event['group_event_id'].';1'; ?>'">
        <?php }

        //Comment Released
        if (isset($scoreRecords[$user['id']]['comment_released']) && $scoreRecords[$user['id']]['comment_released']) {?>
            <input type="button" name="UnreleaseComments" value="<?php __('Unrelease Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['group_id'].';'.$user['id'].';'.$event['group_event_id'].';0'; ?>'">
        <?php } else { ?>
            <input type="button" name="ReleaseComments" value="<?php __('Release Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['group_id'].';'.$user['id'].';'.$event['group_event_id'].';1'; ?>'">
        <?php } ?>
    </div>
    </div>

    <?php $i++;?>
    <?php endforeach; ?>
</div>
    </td>
    </tr>
</table>

<script type="text/javascript"> new Rico.Accordion( 'accordion',
                                    {panelHeight:500,
                                    hoverClass: 'mdHover',
                                    selectedClass: 'mdSelected',
                                    clickedClass: 'mdClicked',
                                    unselectedClass: 'panelheader'});
</script>
</div>
</div>
