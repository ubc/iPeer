<?php
$gradeReleased = isset($scoreRecords[$currentUser['id']])? $scoreRecords[$currentUser['id']]['grade_released']: 1;
$commentReleased = isset($scoreRecords[$currentUser['id']])? $scoreRecords[$currentUser['id']]['comment_released']: 1;
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
?>
<table class="standardtable">
	<tr>
    <td width="100" valign="top"><?php __('Person Being Evaluated')?></td>
    <?php foreach ($rubricCriteria as $criteria) { ?>
    	<td><?php echo $criteria['criteria']?></td>
    <?php } ?>
    </tr>
<?php if (!$gradeReleased && !$commentReleased) {
  $cols = $rubric['Rubric']["criteria"]+1; ?>
  <tr id='details' align="center"><td colspan="<?php echo $cols ?>">
  <font color="red"><?php __('Comments/Grades Not Released Yet.') ?></font></td></tr>
<?php } else if ($gradeReleased || $commentReleased) {
  if (isset($evalResult[$userId])) {
    //Retrieve the individual rubric detail
   $memberResult = $evalResult[$userId];
   if (isset($scoreRecords)) {
     shuffle($memberResult);
   }
   foreach ($memberResult AS $row):
     $memberRubric = $row['EvaluationRubric']; ?>
     <tr id='details'>
     <?php if ($currentUser['id']!=$row['EvaluationRubric']['creator_id']) { ?>
        <td width='15%'><?php echo $currentUser['full_name'] ?></td>
     <?php } else { 
        $member = $membersAry[$memberRubric['evaluatee']]; ?>
        <td width='15%'><?php echo $member['User']['first_name'].' '.$member['User']['last_name'] ?></td>
     <?php }

     $resultDetails = $memberRubric['details'];
     foreach ($resultDetails AS $detail) : $rubDet = $detail['EvaluationRubricDetail']; ?>
        <td valign=\"middle\"><br />
        <!-- Points Detail -->
        <strong><?php echo __('Points', true) ?>: </strong>
        <? if ($gradeReleased && isset($rubDet)) {
        		$lom = $rubDet["selected_lom"];
        	$empty = $rubric["Rubric"]["lom_max"];
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
        <?php } 

        //Grade Detail
        /*echo "<strong>Grade: </strong>";
        if ($gradeReleased && isset($rubDet)) {
          echo $rubDet["grade"] . " / " . $rubricCriteria['criteria_weight_'.$rubDet['criteria_number']] . "<br />";
        } else {
        	echo "n/a<br />";
        }*/
        //Comments 
        ?>
        <br/><strong><?php echo __('Comment', true) ?>: </strong>
        <?php if ($commentReleased && isset($rubDet)) {
        	echo $rubDet["criteria_comment"];
        } else { ?>
        	n/a<br />
        <?php } ?>

        </td>
     <?php endforeach; ?>
     </tr>
     <!-- General Comment -->
     <tr id='details'>
     <td></td> 
     <?php $col = $rubric['Rubric']['criteria'] + 1; ?>
     <td colspan="<?php echo $col ?>">
     <strong><?php __('General Comment') ?>: </strong><br>
     <?php if ($commentReleased) {
       echo $memberRubric['general_comment'];
     }else { ?>
      	n/a<br />
     <?php } ?>
     <br><br></td>
     </tr>
    <?php endforeach;
    }
}
 ?>
</table>
