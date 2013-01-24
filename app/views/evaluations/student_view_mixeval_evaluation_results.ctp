<?php
$gradeReleased = array_sum(Set::extract($evalResult[User::get('id')], '/EvaluationMixeval/grade_release'));
$commentReleased = array_sum(Set::extract($evalResult[User::get('id')], '/EvaluationMixeval/comment_release'));
?>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>

<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h2><?php __('Summary')?></h2>
<table class="standardtable">
<tr>
    <th><?php __('Rating')?></th>
</tr>
<tr>
    <td>
    <?php
    if ($gradeReleased) {
        if (isset($memberScoreSummary[User::get('id')])) {
            $receivedAvePercent = number_format($memberScoreSummary[User::get('id')]['received_ave_score']/$mixeval['Mixeval']['total_marks'] * 100);
            $receivedAvePercent = $receivedAvePercent * (100 - $penalty)/100;
        } else {
            $receivedAvePercent = 0;
        }
        $finalAvg = $memberScoreSummary[User::get('id')]['received_ave_score'] - number_format($avePenalty, 2);
        (number_format($avePenalty, 2) > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.number_format($avePenalty, 2).'</font>'.
            ')'.'<font color=\'red\'>*</font>'.' = '.number_format($finalAvg, 2)) : $stringAddOn = '';

        echo number_format($memberScoreSummary[User::get('id')]['received_ave_score'], 2).$stringAddOn;
        number_format($avePenalty, 2) > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$penalty.
            '% late penalty. ' : $penaltyNote = '';
        echo $penaltyNote;
        echo ' ('.number_format($receivedAvePercent).'%)';
    } else {
        echo __('Not Released', true);
    }
    ?>
    </td>
</tr>
</table>

<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<div id='mixeval_result'>

<div id="accordion">
    <!-- Panel of Evaluations Results -->
    <div id="panelResults">
        <div id="panelResultsHeader" class="panelheader">
            <?php echo __('Evaluation Results From Your Teammates. (Randomly Ordered) ', true);
                if ( !$gradeReleased && !$commentReleased) {
                    echo '<font color="red">'.__('Comments/Grades Not Released Yet.', true).'</font>';
                } else if ( !$gradeReleased) {
                    echo '<font color="red">'.__('Grades Not Released Yet.', true).'</font>';
                } else if ( !$commentReleased) {
                    echo '<font color="red">'.__('Comments Not Released Yet.', true).'</font>';
                }
            ?>
        </div>
        <div style="height: 200px;text-align: center;" id="panelResultsContent" class="panelContent">
            <?php
            $params = array('controller'=>'evaluations', 'mixeval'=>$mixeval, 'evalResult'=>$evalResult[User::get('id')], 'tableType'=>User::get('full_name'));
            echo $this->element('evaluations/student_view_mixeval_details', $params);
            ?>
        </div>
    </div>

    <!-- Panel of Evaluations Reviews -->
    <div id="panelReviews">
        <div id="panelReviewsHeader" class="panelheader">
            <?php echo __('Review Evaluations From You.', true)?>
        </div>
        <div style="height: 200px;" id="panelReviewsContent" class="panelContent">
            <?php
            $params = array('controller'=>'evaluations', 'mixevalQuestion'=>$mixeval, 'evalResult'=>$reviewEvaluations[User::get('id')], 'tableType'=>'evaluatee');
            echo $this->element('evaluations/student_view_mixeval_details', $params);
            ?>
        </div>
    </div>
</div>
</div>

<script type="text/javascript"> new Rico.Accordion( 'accordion',
        {panelHeight:500,
            hoverClass: 'mdHover',
            selectedClass: 'mdSelected',
            clickedClass: 'mdClicked',
            unselectedClass: 'panelheader'});

</script>
