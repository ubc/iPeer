<?php
$gradeReleased = isset($scoreRecords[$rdAuth->id])? $scoreRecords[$rdAuth->id]['grade_released']: 1;
$commentReleased = isset($scoreRecords[$rdAuth->id])? $scoreRecords[$rdAuth->id]['comment_released']: 1;
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

$pos = 1;
?>
<br/><br/>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader" align="center">
    <td width="100" valign="top" colspan="<?=($mixeval['Mixeval']["lickert_question_max"]+1)?>">Section One:</td>
  </tr>
	<tr class="tableheader" align="center">
    <td width="100" valign="top">Evaluatee</td>
    <?php
      for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        if (isset($mixevalQuestion[$i])) {
      		echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
      		echo $mixevalQuestion[$i]['title'];
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
  echo "<font color=\"red\">Comments/Grades Not Released Yet.</font></td></td>";
}
else if ($gradeReleased || $commentReleased) {
 //Retrieve the individual mixeval detail
 if (isset($evalResult[$userId])) {
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   }

   foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];

     $member = $membersAry[$memberMixeval['evaluatee']];
     echo "<tr class=\"tablecell2\">";
     if (isset($scoreRecords)) {
       echo "<td width='15%'>".$rdAuth->fullname."</td>";
     } else {
       echo "<td width='15%'>".$member['User']['last_name'].' '.$member['User']['first_name']."</td>";
     }

     $resultDetails = $memberMixeval['details'];

     //foreach ($resultDetails AS $detail) : $mixevalDet = $detail['EvaluationMixevalDetail'];
     for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail'];
        echo "<td valign=\"middle\">";
        echo "<br />";
        //Points Detail
        echo "<strong>Points: </strong>";
        if ($gradeReleased && isset($mixevalDet)) {
          //if (
        	$lom = $mixevalDet["selected_lom"];
        	$empty = $mixeval["Mixeval"]["scale_max"];
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
     //endforeach;
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
    <td width="100" valign="top" colspan="<?=($mixeval['Mixeval']["prefill_question_max"]+1)?>">Section Two:</td>
  </tr>
	<tr class="tableheader" align="center">
    <td width="100" valign="top">Evaluatee</td>
    <?php
      for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($mixevalQuestion[$i])) {
      		echo "<td><strong><font color=" . $color[ $i % sizeof($color) ] . ">" . ($i) . ". "  . "</font></strong>";
      		echo $mixevalQuestion[$i]['title'];
      		echo "</td>";
      	}
    	}
    ?>
  </tr>
 <?php
if (!$gradeReleased && !$commentReleased) {
  $cols = $mixeval['Mixeval']["prefill_question_max"]+1;
  echo "<tr class=\"tablecell2\" align=\"center\"><td colspan=".$cols.">";
  echo "<font color=\"red\">Comments/Grades Not Released Yet.</font></td></td>";
}else if ($gradeReleased || $commentReleased) {

 if (isset($evalResult[$userId])) {
   //Retrieve the individual mixeval detail
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   }
   foreach ($memberResult AS $row): $memberMixeval = $row['EvaluationMixeval'];

     $member = $membersAry[$memberMixeval['evaluatee']];
     echo "<tr class=\"tablecell2\">";
     if (isset($scoreRecords)) {
       echo "<td width='15%'>".$rdAuth->fullname."</td>";
     } else {
       echo "<td width='15%'>".$member['User']['last_name'].' '.$member['User']['first_name']."</td>";
     }

     $resultDetails = $memberMixeval['details'];
     //foreach ($resultDetails AS $detail) : $mixevalDet = $detail['EvaluationMixevalDetail'];
     for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($memberMixeval['details'][$i-1])) {
          $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail'];
          echo "<td valign=\"middle\">";

          //Comments
          echo "<br/><strong>Comment: </strong>";
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