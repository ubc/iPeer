<?php
$rowspan = count($groupMembersNoTutors) + 3;
$numerical_index = 1;  //use numbers instead of words; get users to refer to the legend
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
$membersAry = array();  //used to format result (students)
$withTutorsAry = array(); //used to format result (students,tutors)
$groupAve = 0;
$groupAverage = array_fill(1, $mixeval['Mixeval']['lickert_question_max'], 0);
$averagePerQuestion = array();

echo $html->script('ricobase');
echo $html->script('ricoeffects');
echo $html->script('ricoanimation');
echo $html->script('ricopanelcontainer');
echo $html->script('ricoaccordion');
?>

<div class="content-container">

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
                    <li><?php echo $user['first_name']." ".$user['last_name'] . ($row['Role']['role_id']==4 ? ' (TA)' : ' (student)');?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<!-- summary table -->
<table width="100%" align="center" class="outer-table">
    <tr>
        <td width="25%" valign="middle" class="result-header-td">Student Name:</td>
        <td width="75%" rowspan="<?php echo $rowspan?>" class="inner-table-cell"><div class="scrollbar">
            <table class="inner-table"><tr class="result-header-td">
            <?php for ($i = 1; $i <= $mixeval['Mixeval']["lickert_question_max"]; $i++):?>
                <td width='200' class='inner-table-cell'>
                    <font color="<?php echo $color[ $numerical_index % sizeof($color) ]?>"><?php echo $numerical_index ?></font>(/<?php echo $mixevalQuestion[$i]['multiplier'];?>)
                </td>
                <?php $numerical_index++;?>
            <?php endfor; ?>
        <td width="250" class="inner-table-cell">
            <?php echo __("Total:( /", true).number_format($mixeval['Mixeval']['total_marks'], 2)?>)
        </td>
    </tr>
            <?php  if ($groupMembersNoTutors) {
                foreach ($groupMembersNoTutors as $member) {
                    $aveScoreSum = 0;
                    echo "<tr class='result-cell'>";
                    if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
      	                $totalScore = $memberScoreSummary[$member['User']['id']]['received_total_score'];
      	                $penalty = ($penalties[$member['User']['id']] / 100) * $totalScore;
      		            $questionIndex = 0;
      		            $avgPerQuestion = 0;
        	            for ($j = 1; $j <= $mixeval['Mixeval']["lickert_question_max"]; $j++) {
                            if (!empty($scoreRecords[$member['User']['id']]['mixeval_question_ave'])) {
                                $criteriaAveGrade = $scoreRecords[$member['User']['id']]['mixeval_question_ave'][$j];
                            } else {
                                $criteriaAveGrade = 0;
                            }
        	                $scaledQuestionGrade = $criteriaAveGrade * (1 - $penalties[$member['User']['id']] / 100);
        	                $questionPenalty = $criteriaAveGrade * $penalties[$member['User']['id']] / 100;
    	                    // for adding up the average percentage per question
    	                    $avgPerQuestion += $criteriaAveGrade / $mixevalQuestion[$j]['multiplier'];
        	                $questionIndex++;
        	                $penalty > 0 ? $stringAddOn = ' - '."<font color=\"red\">".$questionPenalty.
        	  								"</font> = ".number_format($scaledQuestionGrade, 2).'</td>' :
        	  				      $stringAddOn = '</td>';
			                $aveScoreSum += $criteriaAveGrade;
			                echo '<td class="result-cell">' . number_format($criteriaAveGrade, 2).$stringAddOn;
			                $groupAverage[$j] += $scaledQuestionGrade;
                        }
                        // for calculating the average percentage per question
                        $averagePerQuestion[$member['User']['id']] = $avgPerQuestion / $mixeval['Mixeval']['lickert_question_max'];
                    } else {
        	            for ($i = 1; $i <= $mixeval['Mixeval']["lickert_question_max"]; $i++) {
        		            echo "<td class='result-cell'>-</td>";
        	            }
        	        }
        	        echo '<td class="total-cell">';
                    if (isset($memberScoreSummary[$member['User']['id']]['received_ave_score'])) {
        	            $finalAvgScore = $aveScoreSum - $penalty;
        	            $penalty > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".number_format($penalty, 2).
        	  								"</font> = ".number_format($finalAvgScore, 2) :
        	  				 $penaltyAddOn = '';
      		            echo number_format($aveScoreSum, 2).$penaltyAddOn;

      		            $receviedAvePercent = ($memberScoreSummary[$member['User']['id']]['received_total_score'] - $penalty)
      		                / $mixeval['Mixeval']['total_marks'] * 100;

                        echo ' ('.number_format($receviedAvePercent) . '%)';
                        $membersAry[$member['User']['id']]['received_total_score'] = $memberScoreSummary[$member['User']['id']]['received_total_score'];
                        $membersAry[$member['User']['id']]['received_count'] = $memberScoreSummary[$member['User']['id']]['received_count'];
      		            $membersAry[$member['User']['id']]['received_ave_score'] = $averagePerQuestion[$member['User']['id']];
      		            //$membersAry[$member['User']['id']]['received_ave_score_%'] = $averagePerQuestion[$member['User']['id']];
      	            } else {
      		            echo '-';
      	            }
                    echo "</td>";
      	            echo "</tr>";
                }
            }
            for ($j = 1; $j <= $mixeval['Mixeval']['lickert_question_max']; $j++) {
                $scoreRecords['group_question_ave'][$j-1] = $groupAverage[$j] / count($groupMembersNoTutors);
            }
            ?> <tr class="tablesummary"> <?php
            $groupAve = 0;
            for ($j = 1; $j <= $mixeval['Mixeval']["lickert_question_max"]; $j++) {
                echo "<td class='total-cell'>";
                if(isset($scoreRecords['group_question_ave'][$j-1])){
                    $groupAveGrade = $scoreRecords['group_question_ave'][$j-1];
                    $groupAve += $scoreRecords['group_question_ave'][$j-1];
                    echo number_format($groupAveGrade, 2);
                }
                echo "</td>";
            }
            echo "<td><b>";
      	    echo number_format($groupAve, 2);
      	    echo ' ('.number_format($groupAve / $mixeval['Mixeval']['total_marks'] * 100) . '%)';
            echo "</b></td>"; ?>
	        </tr>
        </table></div></td>
    </tr>
    <?php
    if ($groupMembersNoTutors) {
        foreach ($groupMembersNoTutors as $member) {
            echo '<tr class="tablecell2" cellpadding="4" cellspacing="2" >';
            $membersAry[$member['User']['id']]['member'] = $member;
            echo '<td width="25%" class="group-members">' . $member['User']['first_name']." ".$member['User']['last_name'] . '</td></tr>' . "\n";
        }
    }
    if ($groupMembers) {
        foreach ($groupMembers as $member) {
            $withTutorsAry[$member['User']['id']]['member'] = $member;
        }
    }

    echo '<tr class="tablesummary"><td class="group-members"><b>';
    echo __("Group Average: ", true);
    echo "</b></td></tr><tr><td>  </td></tr>";
    ?>
<!-- end of summary table -->
<tr><td>  </td></tr>    <!-- adding space between the submit button and the table -->
    <tr class="tablecell2" align="center"><td colspan="<?php echo ($mixeval['Mixeval']["lickert_question_max"] +2); ?>">
        <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
            <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
            <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>" />
            <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
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
                    <?php echo 'Evaluatee: '.$user['first_name']." ".$user['last_name']?>
                    </div>
                    <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <br><b><?php
                        $deduction = number_format($membersAry[$user['id']]['received_total_score'] * $penalties[$user['id']]/100, 2);
                        $scaled = number_format($membersAry[$user['id']]['received_total_score'] * (1 - $penalties[$user['id']]/100), 2);
                        $percent = number_format($scaled/$mixeval['Mixeval']['total_marks'] * 100);
                        $ave_deduction = number_format($membersAry[$user['id']]['received_ave_score'] * $penalties[$user['id']]/100, 2);
                        $ave_scaled = number_format($membersAry[$user['id']]['received_ave_score'] * (1 - $penalties[$user['id']]/100), 2);
                        echo __("(Number of Evaluator(s): ",true).$membersAry[$user['id']]['received_count'].")<br/>";
                        echo __("Final Total: ",true).number_format($membersAry[$user['id']]['received_total_score'], 2);
                        $penalties[$user['id']] > 0 ? $penaltyAddOn = ' - '."<font color=\"red\">".$deduction."</font> = ".$scaled :
                            $penaltyAddOn = '';
                        echo $penaltyAddOn.' ('.$percent.'%)';

                        if (isset($membersAry[$user['id']]['received_ave_score'])) {
                            $memberAve = number_format($membersAry[$user['id']]['received_ave_score'], 2);
                            $memberAvePercent = number_format($ave_scaled * 100);
                        } else {
                            $memberAve = '-';
                            $memberAvePercent = '-';
                        }
                        $penalties[$user['id']] > 0 ? $ave_penaltyAddOn = ' - '."<font color=\"red\">".$ave_deduction."</font> = ".$ave_scaled :
                            $ave_penaltyAddOn = '';
                        $memberAverageAve = number_format($membersAry[$user['id']]['received_total_score'], 2);
                        if ($memberAverageAve == $groupAve) {
                            echo "&nbsp;&nbsp;<< ".__('Same Mark as Group Average', true)." >>";
                        } else if ($memberAverageAve < $groupAve) {
                            echo "&nbsp;&nbsp;<font color='#cc0033'><< ".__('Below Group Average', true)." >></font>";
                        } else if ($memberAverageAve > $groupAve) {
                            echo "&nbsp;&nbsp;<font color='#000099'><< ".__('Above Group Average', true)." >></font>";
                        }
                        ?> </b><br>
                        <?php echo __("Average Percentage Per Question: ", true);
                        echo $memberAve.$ave_penaltyAddOn;
                        echo ' ('.$memberAvePercent .'%)';

                        $penalties[$user['id']] > 0 ? $penaltyNotice = '<br>'.__('NOTE: ', true).'<font color=\'red\'>'.$penalties[$user['id']].
                            '%</font>'.__(' Late Penalty', true) : $penaltyNotice = '';
                        echo $penaltyNotice;
                        ?>
			            <br><br>
                <!-- Section One -->
                <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
                    <tr>
                        <td colspan="<?php echo $mixeval['Mixeval']["lickert_question_max"] ?>"><b> <?php __('Section One:')?> </b></td>
                    </tr>
                    <tr class="tableheader" align="center">
                        <td width="100" valign="top"><?php __('Evaluator')?></td>
                        <?php
                            for ($i=0; $i<$mixeval['Mixeval']["lickert_question_max"]; $i++) {
                                echo "<td><strong><font color=" . $color[ ($i+1) % sizeof($color) ] . ">" . ($i+1) . ". "  . "</font></strong>";
                                echo $mixevalQuestion[$i+1]['title'];
                                echo "</td>";
                            }
                        ?>
                    </tr>
                    <?php
                    //Retrieve the individual mixeval detail
                    if (isset($evalResult[$user['id']])) {
                        $memberResult = $evalResult[$user['id']];
                            foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];
                                $evalutor = $withTutorsAry[$memberMixeval['evaluator']];
                                echo "<tr class=\"tablecell2\">";
                                echo "<td width='15%'>".$evalutor['member']['User']['first_name']." ".$evalutor['member']['User']['last_name']."</td>";
                                $width = 85 / $mixeval['Mixeval']['lickert_question_max'];
                                $resultDetails = $memberMixeval['details'];
                                for ($j = 1; $j <= $mixeval['Mixeval']["lickert_question_max"]; $j++) {
                                    $rubDet = $resultDetails[$j-1]['EvaluationMixevalDetail'];
                                    echo '<td valign="middle" width="'.$width.'%">';
                                    //Point Description Detail
                                    if (isset($mixevalQuestion[$j-1]['Description'][$rubDet['selected_lom']-1]['descriptor'])) {
                                        echo $mixevalQuestion[$j-1]['Description'][$rubDet['selected_lom']-1]['descriptor'];
                                    }
                                    echo "<br />";

                                    //Points Detail
                                    echo "<strong>".__('Points:', true)."</strong>";
                                    if (isset($rubDet)) {
                                        $lom = $rubDet["grade"];
                                        $empty = $mixevalQuestion[$i-1]['multiplier'];
                                        for ($v = 0; $v < $lom; $v++) {
                                            echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
                                            $empty--;
                                        }
                                        for ($t=0; $t < $empty; $t++) {
                                            echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'cicle_empty'));
                                        }
                                        echo "<br />";
                                    } else {
                                        echo "n/a<br />";
                                    }

                                    //Grade Detail
                                    echo "<strong>".__('Grade', true).": </strong>";
                                    if (isset($rubDet)) {
                                        echo $rubDet["grade"] . " / " . $mixevalQuestion[$j]['multiplier'] . "<br />";
                                    } else {
                                        echo "n/a<br />";
                                    }

                                    echo "<br /><br /></td>";
                                }
                            echo "</tr>";

                        endforeach;
                    } ?>
            </table>
            <!-- Section Two -->
            <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
                <tr>
                    <td colspan="<?php echo $mixeval['Mixeval']["total_question"] ?>"><b> Section Two: </b></td>
                </tr>
                <tr class="tableheader" align="center">
                    <td width="100" valign="top"><?php __('Evaluator')?></td>
                    <?php
                    for ($i=$numerical_index; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
                        if (isset($mixevalQuestion[$i-1])) {
                            echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
                            echo $mixevalQuestion[$i-1]['title'];
                            echo "</td>";
                        }
                    }
                    ?>
                </tr>
                <?php
                //Retrieve the individual mixeval detail
                if (isset($evalResult[$user['id']])) {
                    $memberResult = $evalResult[$user['id']];
                    foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];
                        $evalutor = $withTutorsAry[$memberMixeval['evaluator']];
                        echo "<tr class=\"tablecell2\">";
                        echo "<td width='15%'>".$evalutor['member']['User']['first_name']." ".$evalutor['member']['User']['last_name']."</td>";
                        $width = 85 / ($mixeval['Mixeval']["total_question"] - $mixeval['Mixeval']["lickert_question_max"]);
                        $resultDetails = $memberMixeval['details'];
                        for ($j = $numerical_index; $j <= $mixeval['Mixeval']["total_question"]; $j++) {
                            if (isset($resultDetails[$j-1])) {
                                $rubDet = $resultDetails[$j-1]['EvaluationMixevalDetail'];
                                echo '<td valign="middle" width="'.$width.'%">';
                                //Comments
                                echo "<strong>".__('Comment', true).": </strong>";
                                if (isset($rubDet)) {
                                    echo $rubDet["question_comment"];
                                } else {
                                    echo "n/a";
                                }

                                echo "<br /><br /></td>";
                            }
                        }
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
            <?php } else {?>
                <input type="button" name="ReleaseComments" value="<?php __('Release Comments')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markCommentRelease/'.$event['Event']['id'].';'.$event['group_id'].';'.$user['id'].';'.$event['group_event_id'].';1'; ?>'">
            <?php } ?>
        </div>
    </div>

    <?php $i++;?>
<?php endforeach; ?>
</div></td></tr></table>
	<script type="text/javascript"> new Rico.Accordion( 'accordion',
								{panelHeight:500,
								 hoverClass: 'mdHover',
								 selectedClass: 'mdSelected',
								 clickedClass: 'mdClicked',
								 unselectedClass: 'panelheader'});

	</script>
</div>
