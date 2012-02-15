<!-- elements::ajax_rubric_view start -->
<?php
$LOM_num = $data['Rubric']['lom_max'];
$criteria_num = $data['Rubric']['criteria'];
$rubric_type = $data['Rubric']['template'];
$zero_mark = $data['Rubric']['zero_mark'];
isset($user)? $userId = $user['id'] : $userId = '';
isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;
?>
<table class="form-table">
  <tr class="tableheader" align="center">
		<td width=100 valign="top"><?php __('Rubric View')?></td>
		<!-- // horizontal template type -->
		<?php if( $rubric_type == "horizontal" ):?>
      <?php foreach($data['RubricsLom'] as $lom): ?>
        <td><?php __('Level of Mastery')?> <?php echo $lom['lom_num']?>:<br><?php echo $lom['lom_comment']?>
      <?php endforeach ?>
			<!-- //Comment for Evaluation Form -->
			<?php if ($evaluate):?>
        <td align='left'><?php __('Comments')?></td>
			<?php endif ?>
  </tr>

  <?php foreach($data['RubricsCriteria'] as $criteria): $i = $criteria['criteria_num']; ?>
	<tr class="tablecell" align="center">
    <td class="tableheader2" valign="top">
		<?php if (isset($evaluation)) :?>
		  <input type="hidden" name="selected_lom_<?php echo $userId.'_'.$i?>" value="<?php echo $evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom']?>">
		<?php else: ?>
      <input type="hidden" name="selected_lom_<?php echo $userId.'_'.$i?>" value="1" size="4" >
    <?php endif ?>

			<table border="0" width="95%" cellpadding="2">
        <tr><td><?php echo $i.': '.$criteria['criteria']?></td></tr>
        <tr><td><i><?php echo $criteria['multiplier']?><?php __(' mark(s)')?></i></td></tr>
      </table>
    </td>

    <?php foreach($data['RubricsLom'] as $lom): ?>
    <?php $mark_value = round( ($criteria['multiplier']/(count($data['RubricsLom']) - ('on' == $zero_mark ? 1 : 0))*($lom['lom_num'] - ('on' == $zero_mark ? 1 : 0))) , 2);?>
		<td>
      <div class="rubric-comment"><?php echo (!empty($criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment']) ? $criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment'] : '')?></div>
  		<?php 
      $check = '';
      if (isset($evaluation)) {
        if ($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'] == $lom['lom_num']) $check = "checked";
      } else {
        if ($lom['lom_num'] == 1) $check = "checked";
      }?>
      <div>
      <input name="<?php echo $userId.'criteria_points_'.$i?>" type="radio" value="<?php echo $mark_value?>" 
             onClick="document.evalForm.selected_lom_<?php echo $userId."_".$i?>.value=<?php echo $lom['lom_num']?>;" <?php echo $check?>/></div>

      <?php if (!$evaluate): ?>
        <div class="rubric-mark"><?php __('Mark')?>: <?php echo $mark_value?></div>
      <?php endif; ?>
    </td>
    <?php endforeach ?>

    <?php if ($evaluate): ?>
    <td align="left">
      <textarea cols="20" rows="2" name="<?php echo $userId?>comments[]">
      <?php echo (isset($evaluation) ? $evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['criteria_comment'] : '')?>
      </textarea>
    </td>
		<?php endif;?>	
  <?php endforeach;?>	
			
	</tr>
  <?php endif; ?>
  <tr>
	<?php if (!$evaluate): ?>
 		<td colspan="<?php echo $LOM_num+1?>" align="right"><?php __('Total Marks')?>: <?php echo $data['Rubric']['total_marks']?></td>
	<?php else: ?>
    <td colspan="<?php echo $LOM_num+2?>" align="center" class="tableheader2"><?php __('General Comments')?><br>
    <textarea cols="80" rows="2" name="<?php echo $userId?>gen_comment" >
      <?php echo (isset($evaluation) ? $evaluation['EvaluationRubric']['general_comment'] : '')?>
  	</textarea></td>
  <?php endif;?>	
  </tr>
</table>
<!-- elements::ajax_rubric_preview end -->
