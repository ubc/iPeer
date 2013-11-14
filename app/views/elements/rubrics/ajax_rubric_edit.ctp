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
                         'style' => 'width: 83%;',
                         'label' => false,
                         'before' => '',
                         'after' => '',
                         'between' => '',
                         'readonly' => $readonly,
                        );
?>

<table class="standardtable">
<tr>
    <th valign="top"><?php __('Rubric Preview')?></th>
    <?php for($i = 0; $i < $LOM_num; $i++): ?>
    <th align="left">
        <div><?php __('Level of Mastery')?> <?php echo $i+1?></div>
        <div><?php echo $this->Form->input('RubricsLom.'.$i.'.lom_comment', $default_options)?></div>
        <?php echo $this->Form->input('RubricsLom.'.$i.'.id', array('type' => 'hidden'))?>
        <?php echo $this->Form->input('RubricsLom.'.$i.'.lom_num', array('type' => 'hidden', 'value' => $i+1))?>
    </th>
    <?php endfor;?>
    <th><?php __('Criteria Weight')?></th>
</tr>

<!-- // horizontal template type -->
<?php if( $rubric_type == "horizontal" ): ?>
<!-- //for loop to display the criteria rows -->
<?php for($i = 0; $i < $criteria_num; $i++): ?>
<tr>
    <th>
    <?php if(!$readonly):?><div><?php __('Criteria')?> <?php echo $i+1?></div><?php endif;?>
    <div><?php echo $this->Form->input('RubricsCriteria.'.$i.'.criteria', $default_options)?>
         <?php echo $this->Form->input('RubricsCriteria.'.$i.'.id', array('type' => 'hidden'))?>
         <?php echo $this->Form->input('RubricsCriteria.'.$i.'.criteria_num', array('type' => 'hidden', 'value' => $i+1))?>
         <?php $helptext = "Selecting this will enable the marks to be visible/hidden from the user."?>
         <div title = "<?php echo $helptext ?>"><?php __('Show Marks')?></div>
         <?php echo $this->Form->input('RubricsCriteria.'.$i.'.show_marks', array('label' => '', 'type' => 'checkbox', 'title' => $helptext))?>
    </div>
    </th>

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

    <td style="vertical-align: middle;">
  <?php echo $this->Form->input('RubricsCriteria.'.$i.'.multiplier',
                                array('options' => array_combine(range(1,15), range(1,15)),
                                      'style' => 'width:50px;',
                                      'onChange' => 'calculateMarks('.$LOM_num.','.$criteria_num.','.$zero_mark.', "RubricsCriteria");',
                                      'label' => false,
                                      'before' => '',
                                      'after' => '',
                                      'div' => false,
                                      'disabled' => $readonly,
                                     ))?>

    </td>
</tr>
<?php endfor; ?>
<?php endif; ?>

<tr>
    <td colspan="<?php echo $LOM_num+1?>" style="text-align: right;"><?php __('Total Marks')?>: </td>
    <td><input type="text" name="total_marks" id="total" class="input" size="5" readonly></td>
</tr>
</table>

<script type="text/javascript">
  calculateMarks("<?php echo $LOM_num?>", "<?php echo $criteria_num?>", "<?php echo $zero_mark?>", "RubricsCriteria");
</script>
<!-- elements::ajax_rubric_preview end -->
