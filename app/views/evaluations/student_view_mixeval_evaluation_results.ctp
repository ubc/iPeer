<h2><?php __('Evaluation Result Detail')?></h2>
<!-- Event Details Table -->

<table class="standardtable">
<tr>
    <th><?php __('Event Name')?></th>
    <th><?php __('Evaluated By')?></th>
    <th><?php __('Due Date')?></th>
    <th><?php __('Self-Evaluation')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php echo $event['group_name'] ?></td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
    <td><?php echo ($event['Event']['self_eval']) ? 'Yes' : 'No' ?></td>
</tr>
</table>

<table class="standardtable">
<tr>
    <th><?php __('Description')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['description'] ?></td>
</tr>
</table>

<h2><?php __('Summary')?></h2>
<table class="standardtable">
<tr>
    <th><?php __('Rating')?></th>
</tr>
<tr>
    <td>
    <?php 
    isset($scoreRecords[$currentUser['id']]['grade_released'])? $gradeReleaseStatus = $scoreRecords[$currentUser['id']]['grade_released'] : $gradeReleaseStatus = array();
    if ($gradeReleaseStatus) {
            $finalAvg = $memberScoreSummary[$currentUser['id']]['received_ave_score'] - number_format($avePenalty, 2);
            (number_format($avePenalty, 2) > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.number_format($avePenalty, 2).'</font>'.
                ')'.'<font color=\'red\'>*</font>'.' = '.number_format($finalAvg, 2)) : $stringAddOn = '';
                
            echo number_format($memberScoreSummary[$currentUser['id']]['received_ave_score'], 2).$stringAddOn;
            number_format($avePenalty, 2) > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$studentResult['penalty'].
                '% late penalty.' : $penaltyNote = '';
            echo $penaltyNote;
        } else {
            echo __('Not Released', true);
        }
    ?>
    </td>
</tr>
</table>

<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
	<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <!-- Render Event Info table -->
	  <?php
	  if (isset($memberScoreSummary[$currentUser['id']])) {
	    $receviedAvePercent = $memberScoreSummary[$currentUser['id']]['received_ave_score'] / $mixeval['Mixeval']['total_marks'] * 100;
	    $releaseStatus = $scoreRecords[$currentUser['id']]['grade_released'];
	  } else {
  	  $receviedAvePercent = 0;
  	  $releaseStatus = array();
  	}
    ?>
<div id='mixeval_result'>

<?php
$numerical_index = 1;  //use numbers instead of words; get users to refer to the legend
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$membersAry = array();  //used to format result
$groupAve = 0;

//unset($scoreRecords[$rdAuth->id]);

$gradeReleased = !empty($scoreRecords[$currentUser['id']]['grade_released']) ?
        $scoreRecords[$currentUser['id']]['grade_released'] :
        "No Grades Released";
$commentReleased = !empty($scoreRecords[$currentUser['id']]['comment_released']) ?
        $scoreRecords[$currentUser['id']]['comment_released'] :
        "No Comments Released";

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

<table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr>
		<td>
<div id="accordion">
    <!-- Panel of Evaluations Results -->
		<div id="panelResults">
		  <div id="panelResultsHeader" class="panelheader">
		  	<?php echo __('Evaluation Results From Your Teammates. (Randomly Ordered)       ', true);
		  	if ( !$gradeReleased && !$commentReleased) {
          echo '<font color="red">'.__('Comments/Grades Not Released Yet.', true).'</font>';
		  	}	else if ( !$gradeReleased) {
		  	  echo '<font color="red">'.__('Grades Not Released Yet.', true).'</font>';
        }	else if ( !$commentReleased) {
		  	  echo '<font color="red">'.__('Comments Not Released Yet.', true).'</font>';
        }
?>
		  </div>
		  <div style="height: 200px;" id="panelResultsContent" class="panelContent">
  	  <?php
    $params = array('controller'=>'evaluations', 'mixeval'=>$mixeval, 'mixevalQuestion'=>$mixevalQuestion, 'membersAry'=>$groupMembers, 'evalResult'=>$evalResult, 'userId'=>$currentUser['id'], 'scoreRecords'=>$scoreRecords);
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
    $params = array('controller'=>'evaluations', 'mixeval'=>$mixeval, 'mixevalQuestion'=>$mixevalQuestion, 'membersAry'=>$groupMembers, 'evalResult'=>$reviewEvaluations, 'userId'=>$currentUser['id'], 'scoreRecords'=>null);
    echo $this->element('evaluations/student_view_mixeval_details', $params);
    ?>
		  </div>
		</div>
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

	</td>
  </tr>
</table>
