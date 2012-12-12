<?php
    $gradeReleased = isset($scoreRecords[User::get('id')]['grade_released']) ?
        $scoreRecords[User::get('id')]['grade_released'] : 1;
    $commentReleased = isset($scoreRecords[User::get('id')]['comment_released']) ?
        $scoreRecords[User::get('id')]['comment_released'] : 1;
    $color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

    $pos = 1;
?>
<br/><br/>
<table class="standardtable">
	<tr>
    <td width="100" valign="top" colspan="<?php echo ($mixeval['Mixeval']["lickert_question_max"]+1)?>"><?php __('Section One')?>:</td>
    </tr>
	<tr>
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php
      for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        if (isset($mixevalQuestion[$i])) { ?>
      		<td>
      		<?php echo $i.' '.$mixevalQuestion[$i]['title'] ?>
      		</td>
      		<?php $pos++;
      	}
    	}
    ?>
    </tr>
 <?php
if (!$gradeReleased && !$commentReleased) {
    $cols = $mixeval['Mixeval']["lickert_question_max"]+1; ?>
    <tr><td colspan="<?php echo $cols ?>">
    <font color="red"><?php __('Comments/Grades Not Released Yet.', true) ?></font></td></tr>
<?php } else if ($gradeReleased || $commentReleased) {
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
        } ?>
        <tr id='details'>
        <?php if (isset($scoreRecords)) { ?>
        <td width='15%'><?php echo User::get('full_name') ?></td>
    <?php } else { ?>
        <td width='15%'><?php echo $member['User']['first_name'].' '.$member['User']['last_name']?></td>
    <?php }

    $resultDetails = $memberMixeval['details'];

    for ($i=1; $i<=$mixeval['Mixeval']["lickert_question_max"]; $i++) {
        $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail']; ?>
        <td valign="middle">
        <!-- Point Description Detail -->
        <?php if ($gradeReleased && isset($mixevalDet)) {
            if (isset($mixevalQuestion[$i-1]['Description'][$mixevalDet['selected_lom']-1]['descriptor'])) {
                echo $mixevalQuestion[$i-1]['Description'][$mixevalDet['selected_lom']-1]['descriptor'];
            } ?>
        <br />
        <?php } ?>

        <!-- Points Detail -->
        <strong>Points: </strong>
        <?php if ($gradeReleased && isset($mixevalDet)) {
            $lom = $mixevalDet['grade'];
        	$empty = $mixeval["Question"][$i-1]["multiplier"];
        	for ($v = 0; $v < $lom; $v++) {
        		echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
        		$empty--;
        	}
        	for ($t=0; $t < $empty; $t++) {
        		echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
        	} ?>
        	<br />
        <?php } else { ?>
        	n/a<br />
        <?php } ?>

        <!-- Grades Detail -->
        <strong>Grades: </strong>
        <?php if ($gradeReleased && isset($mixevalDet)) {
            echo number_format($lom, 2)." / ".number_format($mixeval["Question"][$i-1]["multiplier"], 2)."<br>";
        } else { ?>
        	n/a<br />
        <?php } ?>

        </td>
     <?php } ?>
     </tr>

     <?php endforeach;
  }
}
 ?>
</table>
<br/><br/>
<table class="standardtable">
	<tr>
    <td width="100" valign="top" colspan="<?php echo ($mixeval['Mixeval']["prefill_question_max"]+1)?>"><?php __('Section Two')?>:</td>
    </tr>
	<tr>
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php
      for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($mixevalQuestion[$i-1])) { ?>
      		<td>
      		<?php echo $i.' '.$mixevalQuestion[$i-1]['title'] ?>
      		</td>
      	<?php }
        }
    ?>
    </tr>
<?php
if (!$gradeReleased && !$commentReleased) {
  $cols = $mixeval['Mixeval']["prefill_question_max"]+1; ?>
  <tr><td colspan="<?php $cols ?>">
  <font color="red"><?php __('Comments/Grades Not Released Yet.', true) ?></font></td></tr>
<?php }else if ($gradeReleased || $commentReleased) {
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
     } ?>

     <tr id='details'>
     <?php if (isset($scoreRecords)) { ?>
       <td width='15%'><?php echo User::get('full_name') ?></td>
     <?php } else { ?>
       <td width='15%'><?php echo $member['User']['first_name'].' '.$member['User']['last_name']?></td>
     <?php }

     $resultDetails = $memberMixeval['details'];
     //foreach ($resultDetails AS $detail) : $mixevalDet = $detail['EvaluationMixevalDetail'];
     for ($i=$pos; $i<=$mixeval['Mixeval']["total_question"]; $i++) {
        if (isset($memberMixeval['details'][$i-1])) {
          $mixevalDet = $memberMixeval['details'][$i-1]['EvaluationMixevalDetail']; ?>
          <td valign="middle">

          <!-- Comments -->
          <br/><strong><?php echo __('Comment', true)?>: </strong>
          <?php if ($commentReleased && isset($mixevalDet)) {
          	echo $mixevalDet["question_comment"];
          } else { ?>
          	n/a<br />
          <?php } ?>
          <br />

          </td>
      <?php }
    } ?>
     </tr>

     <?php endforeach;
  }
}

 ?>
</table>
