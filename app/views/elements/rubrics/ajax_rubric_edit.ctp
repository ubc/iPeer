<!-- elements::ajax_rubric_preview start -->
<?php echo $html->script('calculate_marks')?>
<?php
$LOM_num = $data['Rubric']['lom_max'];
$criteria_num = $data['Rubric']['criteria'];
$rubric_type = $data['Rubric']['template'];
$zero_mark = $data['Rubric']['zero_mark'];
$default_options = array('type' => 'text',
                         'cols' => 20,
                         'rows' => 3,
                         'style' => 'width: 90%;',
                         'label' => false,
                         'before' => '',
                         'after' => '',
                         'between' => '',
                         'readonly' => $readonly,
                        );
?>

<table class="form-table">
<tr class="tableheader" align="center">
  <td valign="top"><?php __('Rubric Preview')?></td>

	<?php for($i = 0; $i < $LOM_num; $i++): ?>
	<td align="left">
    <div><?php __('Level of Mastery')?> <?php echo $i+1?></div>
    <div><?php echo $this->Form->input('RubricsLom.'.$i.'.lom_comment', $default_options)?></div>
    <?php echo $this->Form->input('RubricsLom.'.$i.'.id', array('type' => 'hidden'))?>
    <?php echo $this->Form->input('RubricsLom.'.$i.'.lom_num', array('type' => 'hidden', 'value' => $i+1))?>
  </td>
	<?php endfor;?>

	<td><?php __('Criteria Weight')?></td>
</tr>

<!-- // horizontal template type -->
<?php if( $rubric_type == "horizontal" ): ?>
<!-- //for loop to display the criteria rows -->
<?php for($i = 0; $i < $criteria_num; $i++): ?>
<tr class="tablecell" align="center">
	<td class="tableheader2" valign="top" align="left">
    <?php if(!$readonly):?><div><?php __('Criteria')?> <?php echo $i+1?></div><?php endif;?>
    <div><?php echo $this->Form->input('RubricsCriteria.'.$i.'.criteria', $default_options)?>
         <?php echo $this->Form->input('RubricsCriteria.'.$i.'.id', array('type' => 'hidden'))?>
         <?php echo $this->Form->input('RubricsCriteria.'.$i.'.criteria_num', array('type' => 'hidden', 'value' => $i+1))?>
    </div>
  </td>

	<?php for($j=0; $j<$LOM_num; $j++):?>
	<td align="left">
    <?php if(!$readonly):?><div><?php __('Specific Comment')?></div><?php endif;?>
    <div><?php echo $this->Form->input('RubricsCriteria.'.$i.'.RubricsCriteriaComment.'.$j.'.criteria_comment', $default_options)?></div>
    <?php echo $this->Form->input('RubricsCriteria.'.$i.'.RubricsCriteriaComment.'.$j.'.id', array('type' => 'hidden'))?>
    <div>Mark: <?php echo $this->Form->text('criteria_mark_'.$i.'_'.$j, array('size' =>3,
                                                                              'readonly' => true,
                                                                              'id' => 'RubricsCriteriaMark'.$i.$j,
                                                                             ))?></div>
  </td>
	<?php endfor;?>


  <?php echo $this->Form->input('RubricsCriteria.'.$i.'.multiplier', 
                                array('options' => array_combine(range(1,15), range(1,15)),
                                      'style' => 'width:50px;',
                                      'onChange' => 'calculateMarks('.$LOM_num.','.$criteria_num.','.$zero_mark.', "RubricsCriteria");',
                                      'label' => false,
                                      'before' => '',
                                      'after' => '',
                                      'disabled' => $readonly,
                                     ))?>

</tr>
<?php endfor; ?>
<?php endif; ?>

<tr class="tableheader2">
	<td colspan="<?php echo $LOM_num+1?>" align=right><?php __('Total Marks')?>: </td>
 	<td><input type="text" name="total_marks" id="total" class="input" size="5" readonly></td>
</tr>
</table>

<script type="text/javascript">
  calculateMarks("<?php echo $LOM_num?>", "<?php echo $criteria_num?>", "<?php echo $zero_mark?>", "RubricsCriteria");  
</script>
<!-- elements::ajax_rubric_preview end -->
