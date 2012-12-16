<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h2><?php __('Summary')?></h2>

<table class="standardtable">
<tr><th><?php __('Rating')?></th></tr>
<tr>
    <td>
    <?php
    isset($scoreRecords[User::get('id')]['grade_released'])? $gradeReleaseStatus = $scoreRecords[User::get('id')]['grade_released'] : $gradeReleaseStatus = array();
    if ($gradeReleaseStatus) {
            $finalAvg = $memberScoreSummary[User::get('id')]['received_ave_score'] - $ratingPenalty;
            ($ratingPenalty > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.$ratingPenalty.'</font>'.
                ')'.'<font color=\'red\'>*</font>'.' = '.number_format($finalAvg, 2)) : $stringAddOn = '';

            echo number_format($memberScoreSummary[User::get('id')]['received_ave_score'], 2).$stringAddOn;
            $ratingPenalty > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$penalty.
                '% late penalty.' : $penaltyNote = '';
            echo $penaltyNote;
        } else {
            echo __('Not Released', true);
        }
    ?>
    </td>
</tr>
</table>

<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>

<?php
$numerical_index = 1;  //use numbers instead of words; get users to refer to the legend
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$membersAry = array();  //used to format result
$groupAve = 0;
if (isset($scoreRecords[User::get('id')])) {
    $gradeReleased = $scoreRecords[User::get('id')]['grade_released'];
    $commentReleased = $scoreRecords[User::get('id')]['comment_released'];
} else {
    $gradeReleased = 0;
    $commentReleased = 0;
}
?>
			 <!--br>Total: <?php /*$memberAve = number_format($membersAry[$user['id']]['received_ave_score'], 2);
			                  echo number_format($membersAry[$user['id']]['received_ave_score'], 2);
			                  echo '('.number_format($membersAry[$member['User']['id']]['received_ave_score_%']) .'%)';
			                  if ($memberAve == $groupAve) {
			                    echo "&nbsp;&nbsp;<< Same Mark as Group Average >>";
			                  } else if ($memberAve < $groupAve) {
			                    echo "&nbsp;&nbsp;<font color='#FF6666'><< Below Group Average >></font>";
			                  } else if ($memberAve > $groupAve) {
			                    echo "&nbsp;&nbsp;<font color='#000099'><< Above Group Average >></font>";
			                  }*/
			                  ?>
			        <br><br-->

<div id="accordion">
    <!-- Panel of Evaluations Results -->
    <div id="panelResults">
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
            $params = array('controller'=>'evaluations', 'rubric'=>$rubric, 'rubricCriteria'=>$rubricCriteria, 'membersAry'=>$groupMembers, 'evalResult'=>$evalResult, 'userId'=>User::get('id'), 'scoreRecords'=>$scoreRecords);
            echo $this->element('evaluations/student_view_rubric_details', $params);
            ?>
        </div>
    </div>
    <!-- Panel of Evaluations Reviews -->
    <div id="panelReviews">
        <div id="panelReviewsHeader" class="panelheader">
            <?php echo 'Review Evaluations From You.'?>
        </div>
        <div style="height: 200px;" id="panelReviewsContent" class="panelContent">
            <?php
            $params = array('controller'=>'evaluations', 'rubric'=>$rubric, 'rubricCriteria'=>$rubricCriteria, 'membersAry'=>$groupMembers, 'evalResult'=>$reviewEvaluations, 'userId'=>User::get('id'), 'scoreRecords'=>$scoreRecords, 'isReview' => true);
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
