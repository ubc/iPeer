<?php 
$gradeReleased = isset($currentUser['id'])? $scoreRecords['group_criteria_ave']: 1;
$commentReleased = isset($scoreRecords[$currentUser['id']])? $scoreRecords[$currentUser['id']]['comment_released']: 1;
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

?>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader" align="center">
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php
      for ($i=1; $i<=$rubric['Rubric']["criteria"]; $i++) {
    		echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
    		echo $rubricCriteria[0]['criteria'];
    		echo "</td>";
    	}
    ?>
  </tr>
 <?php
if (!$gradeReleased && !$commentReleased) {
  $cols = $rubric['Rubric']["criteria"]+1;
  echo "<tr class=\"tablecell2\" align=\"center\"><td colspan=".$cols.">";
  echo "<font color=\"red\">".__('Comments/Grades Not Released Yet.')."</font></td></td>";
}else if ($gradeReleased || $commentReleased) {
  if (isset($evalResult[$userId])) {
   //Retrieve the individual rubric detail
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   } 
   foreach ($memberResult AS $row):
     $memberRubric = $row['EvaluationRubric'];
     echo "<tr class=\"tablecell2\">";
     if ($currentUser['id']!=$row['EvaluationRubric']['creator_id']) {
       echo "<td width='15%'>".$currentUser['full_name']."</td>";
     } else {
        $member = $membersAry[$memberRubric['evaluatee']];
        echo "<td width='15%'>".$member['User']['first_name'].' '.$member['User']['last_name']."</td>";
     }

     $resultDetails = $memberRubric['details'];
     foreach ($resultDetails AS $detail) : $rubDet = $detail['EvaluationRubricDetail'];
        echo "<td valign=\"middle\">";
        echo "<br />";
        //Points Detail
        echo "<strong>Points: </strong>";
        if ($gradeReleased && isset($rubDet)) {
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
        /*echo "<strong>Grade: </strong>";
        if ($gradeReleased && isset($rubDet)) {
          echo $rubDet["grade"] . " / " . $rubricCriteria['criteria_weight_'.$rubDet['criteria_number']] . "<br />";
        } else {
        	echo "n/a<br />";
        }*/
        //Comments
        echo "<br/><strong>".__('Comment', true).": </strong>";
        if ($commentReleased && isset($rubDet)) {
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
     echo "<strong>".__('General Comment').": </strong><br>";
     if ($commentReleased) {
       echo $memberRubric['general_comment'];
     }else {
      	echo "n/a<br />";
     }
     echo "<br><br></td>";
     echo "</tr>";
         endforeach;
    }
}
 ?>
</table>