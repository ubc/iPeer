<?php
    $gradeReleased = isset($scoreRecords[$currentUser['id']]['grade_released']) ?
        $scoreRecords[$currentUser['id']]['grade_released'] : 1;
    $commentReleased = isset($scoreRecords[$currentUser['id']]['comment_released']) ?
        $scoreRecords[$currentUser['id']]['comment_released'] : 1;
    $color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

    $pos = 1;
?>
<br/><br/>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader" align="center">
    <td width="100" valign="top" colspan="<?php echo ($mixeval['Mixeval']["lickert_question_max"]+1)?>"><?php __('Section One')?>:</td>
  </tr>
	<tr class="tableheader" align="center">
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php
      for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        if (isset($mixevalQuestion[$i-1])) {
      		echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
      		echo $mixevalQuestion[$i-1]['title'];
      		echo "</td>";
      		$pos++;
      	}
    	}
    ?>
  </tr>
 <?php
if (!$gradeReleased && !$commentReleased) {
  $cols = $mixeval['Mixeval']["lickert_question_max"]+1;
  echo "<tr class=\"tablecell2\" align=\"center\"><td colspan=".$cols.">";
  echo "<font color=\"red\">".__('Comments/Grades Not Released Yet.', true)."</font></td></td>";
}
else if ($gradeReleased || $commentReleased) {
 //Retrieve the individual mixeval detail
 if (isset($evalResult[$userId])) {
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   }
   foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];

     if ($scoreRecords == null) { // renders self evaluation
     	$member = $membersAry[$memberMixeval['evaluatee']];
     	
     } else { // renders evaluations from peers
        $member = $membersAry[$memberMixeval['evaluator']];
     }

     echo "<tr class=\"tablecell2\">";
     if (isset($scoreRecords)) {
       echo "<td width='15%'>".$currentUser['full_name']."</td>";
     } else {
       echo "<td width='15%'>".$member['User']['first_name'].' '.$member['User']['last_name']."</td>";
     }

     $resultDetails = $memberMixeval['details'];

     //foreach ($resultDetails AS $detail) : $mixevalDet = $detail['EvaluationMixevalDetail'];
     for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail'];
        echo "<td valign=\"middle\">";
        //Point Description Detail
        if (isset($mixevalQuestion[$i-1]['Description'][$mixevalDet['selected_lom']-1]['descriptor'])) {
          echo $mixevalQuestion[$i-1]['Description'][$mixevalDet['selected_lom']-1]['descriptor'];
        }
        echo "<br />";
        
        //Points Detail
        echo "<strong>Points: </strong>";
        if ($gradeReleased && isset($mixevalDet)) {
          //if (
        	//$lom = $mixeval["Question"][$i-1]["multiplier"]/ $mixevalDet["selected_lom"];
                $lom = $mixevalDet['grade'];
        	$empty = $mixeval["Question"][$i-1]["multiplier"]; 
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

        echo "</td>";
     }
     echo "</tr>";

     endforeach;
  }
}
 ?>
</table>
<br/><br/>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader" align="center">
    <td width="100" valign="top" colspan="<?php echo ($mixeval['Mixeval']["prefill_question_max"]+1)?>"><?php __('Section Two')?>:</td>
  </tr>
	<tr class="tableheader" align="center">
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php
      for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($mixevalQuestion[$i-1])) {
      		echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
      		echo $mixevalQuestion[$i-1]['title'];
      		echo "</td>";
      	}
    	}
    ?>
  </tr>
 <?php
if (!$gradeReleased && !$commentReleased) {
  $cols = $mixeval['Mixeval']["prefill_question_max"]+1;
  echo "<tr class=\"tablecell2\" align=\"center\"><td colspan=".$cols.">";
  echo "<font color=\"red\">".__('Comments/Grades Not Released Yet.', true)."</font></td></td>";
}else if ($gradeReleased || $commentReleased) {

 if (isset($evalResult[$userId])) {
   //Retrieve the individual mixeval detail
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   }
   foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];

     if ($scoreRecords == null)  {
        $member = $membersAry[$memberMixeval['evaluatee']];
     } else {
        $member = $membersAry[$memberMixeval['evaluator']];
     }

     echo "<tr class=\"tablecell2\">";
     if (isset($scoreRecords)) {
       echo "<td width='15%'>".$currentUser['full_name']."</td>";
     } else {
       echo "<td width='15%'>".$member['User']['first_name'].' '.$member['User']['last_name']."</td>";
     }

     $resultDetails = $memberMixeval['details'];
     //foreach ($resultDetails AS $detail) : $mixevalDet = $detail['EvaluationMixevalDetail'];
     for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($memberMixeval['details'][$i-1])) {
          $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail'];
          echo "<td valign=\"middle\">";

          //Comments
          echo "<br/><strong>".__('Comment').": </strong>";
          if ($commentReleased && isset($mixevalDet)) {
          	echo $mixevalDet["question_comment"];
          } else {
          	echo "n/a<br />";
          }
          echo "<br />";

          echo "</td>";
       //endforeach;
      }
    }
     echo "</tr>";

     endforeach;
  }
}

 ?>
</table>
