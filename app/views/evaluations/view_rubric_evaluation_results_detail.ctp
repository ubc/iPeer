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
    $params = array('controller'=>'evaluations', 'event'=>$event);
    echo $this->element('evaluations/view_event_info', $params);
    ?>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
      <b> Summary:</b>(
      <a href="<?php echo $this->webroot.$this->theme?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Basic"><?php __('Basic')?></a>
       |
      <a href="<?php echo $this->webroot.$this->theme?>evaluations/viewEvaluationResults/<?php echo $event['Event']['id']?>/<?php echo $event['group_id']?>/Detail" ><?php __('Detail')?></a>
        )
      <BR>
      <BR> <?php echo '<font size = "1" face = "arial" color = "red" >*Numerics in red denotes late submission penalty.</font>';?>
    </td>
  </tr>
	<?php $i = 0;
  if (!$allMembersCompleted) {?>
  <tr>
    <td colspan="3">
	      <font color="red"><?php __('These student(s) have yet to submit their evaluations:')?> <br>
	         <?php foreach($inCompletedMembers as $row): $user = $row['User']; ?>
	          &nbsp;-&nbsp; <?php echo $user['first_name'].' '.$user['last_name']?> <br>
	      <?php endforeach; ?>
      </font>
    </td>
  </tr>
<?php } ?>
</table>
<div id='rubric_result'>

<?php
$numerical_index = 1;  //use numbers instead of words; get users to refer to the legend
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$membersAry = array();  //used to format result
$groupAve = 0;
?>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader">
		<td valign="middle"><?php __('Student Name:')?></td>
    <?php
    	for ($i = 1; $i <= $rubric['Rubric']["criteria"]; $i++) {
    		echo "<td>";
    		echo '<strong><font color="' . $color[ $i % sizeof($color) ] . '">' . $numerical_index . ". </font></strong>";
    		echo "(" .$rubricCriteria[$i-1]['multiplier']. ")";
    		echo "</td>";
    		$numerical_index++;
    	}
    ?>
    <td> Total:( /<?php echo number_format($rubric['Rubric']['total_marks'], 2)?>)</td>
  </tr>
    <?php
    $aveScoreSum = 0;
    //This section will display the evaluatees' name
    //as display the average scores their peers gave them
    //for various criteria
    $questionSum = array_fill(0, $rubric['Rubric']['criteria'], 0);
    if ($groupMembers) {
      foreach ($groupMembers as $member) {
        $membersAry[$member['User']['id']] = $member;
      	echo '<tr class="tablecell2">';
      		echo '<td width="30%">' . $member['User']['first_name'] . ' ' . $member['User']['last_name'] . '</td>' . "\n";
        //if ($allMembersCompleted) {
        if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
      	    $avgScore = $memberScoreSummary[$member['User']['id']]['received_ave_score'];
      	    $penalty = ($penalties[$member['User']['id']] / 100) * $avgScore;
      	    $avgQuestionPenalty = $penalty/$rubric['Rubric']["criteria"];
      	//    $questionPenalty =
      		$questionIndex = 0; 
        	foreach ($scoreRecords[$member['User']['id']]['rubric_criteria_ave'] AS $criteriaAveIndex => $criteriaAveGrade) {
        	  $scaledQuestionGrade = $criteriaAveGrade - $avgQuestionPenalty;
        	  $questionSum[$questionIndex] += $scaledQuestionGrade;
        	  $questionIndex++;
        	  $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".$avgQuestionPenalty.
        	  								"</font> = ".number_format($scaledQuestionGrade, 2).'</td>' :
        	  				 $stringAddOn = '';
          	  echo '<td>' . number_format($criteriaAveGrade, 2).$stringAddOn;
          }
        } else {
        	for ($i = 1; $i <= $rubric['Rubric']["criteria"]; $i++) {
        		echo "<td>-</td>";
        	}
        }

      	//totals section
      	echo '<td width="30%">';
      	//if ($allMembersCompleted) {
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
      echo "<td><b>";
      echo __("Group Average: ", true);
      echo "</b></td>";
      $questionIndex = 0;      
      foreach ($scoreRecords['group_criteria_ave'] AS $groupAveIndex => $groupAveGrade) {
		echo '<td>' . number_format($questionSum[$questionIndex] / count($groupMembers), 2). "</td>";
      }
      echo "<td><b>";
      $groupAve = number_format($aveScoreSum / count($groupMembers), 2);
      echo $groupAve;
      echo ' ('.number_format($groupAve / $rubric['Rubric']['total_marks'] * 100) . '%)';
      
      echo "</b></td>";
    }		?>
	</tr>
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
				}
				else {
					echo "<input class=\"reviewed\" type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
				}
			?>
			</form></td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr>
		<td align="center">
<div id="accordion">
	<?php $i = 0;
	foreach($groupMembers as $row): $user = $row['User']; ?>
		<div id="panel<?php echo $user['id']?>">
		  <div id="panel<?php echo $user['id']?>Header" class="panelheader">
		  	<?php echo __('Evaluatee: ', true).$user['last_name'].' '.$user['first_name']?>
		  </div>
		  <div style="height: 200px;" id="panel1Content" class="panelContent">
			 <br><b><?php
                                  echo __("Sum: ",true).number_format($memberScoreSummary[$row['User']['id']]['received_total_score'],2);
                                  echo __(" (Number of Evaluator(s): ",true).count($evalResult[$user['id']]).")<br/>";
                                  echo __("Total: ", true);
                                  if (isset($membersAry[$user['id']]['received_ave_score'])) {
                                    //$memberTotalScore = number_format($membersAry[$user['id']]['received_ave_score'], 2);
                                    $memberTotalScore = $memberScoreSummary[$row['User']['id']]['received_ave_score'];
                                    $memberTotalScorePercent = number_format($membersAry[$user['id']]['received_ave_score_%']);
                                  }else {
                                    $memberTotalScore = '-';
                                    $memberTotalScorePercent = '-';
                                  }
                                  echo $memberTotalScore;
                                  echo '('.$memberTotalScorePercent.'%) ';
                                  if ($memberTotalScore == $groupAve) {
                                    echo "&nbsp;&nbsp;((".__("Same Mark as Group Average", true)." ))";
                                  } else if ($memberTotalScore < $groupAve) {
                                    echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font>";
                                  } else if ($memberTotalScore > $groupAve) {
                                    echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font>";
                                  }
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
            <!--td>Release Status</td-->
          </tr>
       <?php
         //Retrieve the individual rubric detail
         if (isset($evalResult[$user['id']])) {

           $memberResult = $evalResult[$user['id']];

           foreach ($memberResult AS $row): $memberRubric = $row['EvaluationRubric'];
             $evalutor = $membersAry[$memberRubric['evaluator']];
             echo "<tr class=\"tablecell2\">";
             echo "<td width='15%'>".$evalutor['User']['last_name'].' '.$evalutor['User']['first_name']."</td>";

             $resultDetails = $memberRubric['details'];
             foreach ($resultDetails AS $detail) : $rubDet = $detail['EvaluationRubricDetail'];
             	$i = 0;
                echo '<td valign="middle">';
                echo "<br />";
                //Points Detail
                echo "<strong>".__('Points').": </strong>";
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
             //echo "<td>";
             //echo "</td>";
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
       }
       ?>
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
       <?php }
       ?>
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

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
	</td>
  </tr>
</table>
