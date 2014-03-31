<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h2><?php __('Summary')?></h2>

<table class="standardtable">
<tr><th><?php __('Rating')?></th></tr>
<tr>
    <td>
    <?php
    if (isset($status)) {
        $gradeReleased = $status['autoRelease'] || $status['grade'];
        $commentReleased = $status['autoRelease'] || $status['comment'];
    } else {
        $gradeReleased = 0;
        $commentReleased = 0;
    }
    if ($gradeReleased) {
        $ave = count($evaluateeDetails) > 0 ? number_format(array_sum(Set::extract($evaluateeDetails, '/EvaluationRubric/score')) / count($evaluateeDetails), 2) : '0.00';
        $deduction = number_format($ave * $penalty / 100, 2);
        $finalAvg = number_format($ave * (100 - $penalty) / 100, 2);
        ($penalty > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.$deduction.'</font>'.
            ')'.'<font color=\'red\'>*</font>'.' = '.$finalAvg) : $stringAddOn = '';

        echo $ave.$stringAddOn;
        $penalty > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$penalty.
            '% late penalty.' : $penaltyNote = '';
        echo $penaltyNote;
    } else {
        echo __('Not Released', true);
    }
    ?>
    </td>
</tr>
</table>

<?php if ($event['Event']['enable_details']) { ?>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>

<div id="accordion">
    <!-- Panel of Evaluations Results -->
    <div id="panelResults" class="panelName">
        <div id="panelResultsHeader" class="panelheader">
            <?php echo __('Evaluation Results From Your Teammates. (Randomly Ordered)', true);?>
            <font color="red">
            <?php if ( !$gradeReleased && !$commentReleased) {
                echo __('Comments/Grades Not Released Yet.', true);
            } else if ( !$gradeReleased) {
                echo __('Grades Not Released Yet.', true);
            } else if ( !$commentReleased) {
                echo __('Comments Not Released Yet.', true);
            }
            ?>
            </font>
        </div>
        <div style="height: 200px;" id="panelResultsContent" class="panelContent">
            <?php
            $params = array('controller'=>'evaluations', 'rubric'=>$rubric, 'membersList'=>$membersList, 'details'=>$evaluateeDetails, 'penalty'=> $penalty, 'release'=>$status);
            echo $this->element('evaluations/student_view_rubric_details', $params);
            ?>
        </div>
    </div>
    <!-- Panel of Evaluations Reviews -->
    <div id="panelReviews" class="panelName">
        <div id="panelReviewsHeader" class="panelheader">
            <?php echo 'Review Evaluations From You.'?>
        </div>
        <div style="height: 200px;" id="panelReviewsContent" class="panelContent">
            <?php
            $status['review'] = 1;  // for student to review evaluations they gave others
            $params = array('controller'=>'evaluations', 'rubric'=>$rubric, 'membersList'=>$membersList, 'details'=>$evaluatorDetails, 'penalty'=>$penalty, 'status'=>$status);
            echo $this->element('evaluations/student_view_rubric_details', $params);
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    new Rico.Accordion( 'accordion',
            {panelHeight:500,
            hoverClass: 'mdHover',
            selectedClass: 'mdSelected',
            clickedClass: 'mdClicked',
            unselectedClass: 'panelheader'});

</script>
<?php } else {
    $grades = array();
    foreach ($evaluateeDetails as $details) {
        foreach ($details['EvaluationRubricDetail'] as $grade) {
            $grades[$grade['criteria_number']][] = $grade['grade'];
        }
    }
    foreach ($grades as $num => $marks) {
        $grades[$num] = $this->Evaluation->array_avg($marks);
    }
    echo "<br><table class='standardtable'>";
    echo "<tr>";
    echo "<th width=50%>".__('Criteria', true)."</th>";
    echo "<th width=50%>".__('Grade', true)."</th>";
    echo "</tr>";
    foreach ($rubric['RubricsCriteria'] as $ques) {
        echo "<tr>";
        echo "<td width=50%>".$ques['criteria']."</th>";
        echo "<td width=50%>".number_format($grades[$ques['criteria_num']], 2)."</td>";
        echo "</tr>";
    }
    echo "</table>";
} ?>
