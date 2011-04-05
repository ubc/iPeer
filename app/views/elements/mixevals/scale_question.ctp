<table>
  <tr>
    <td colspan="<?php echo ($scale_default+1)?>">
      <?php echo $this->Form->input('Question.'.$i.'.title', array_merge($default_options, array('class' => 'question-title')))?>
    </td>
  </tr>

  <tr>
    <?php foreach($q['Description'] as $j => $desc):?>
      <td>Descriptor:</td>
    <?php endforeach;?>
		  <td><span>Add Descriptor</span></td>
  </tr>

  <tr>
    <?php foreach($q['Description'] as $j => $desc):?>
      <td>
        <?php echo $this->Form->input('Question.'.$i.'.Description.'.$j.'.descriptor', array_merge($default_options, array('class' => 'question-descriptor')))?>
        <?php echo $this->Form->input('Question.'.$i.'.Description.'.$j.'.id', array('type' => 'hidden'))?>
      </td>
    <?php endforeach;?>
      <td>&nbsp;</td>
  </tr>

  <tr>
    <?php foreach($q['Description'] as $j => $desc):?>
      <td>
        Mark: <?php echo $this->Form->text('criteria_mark_'.$i.'_'.$j, array('size' =>3,
                                                                             'readonly' => true,
                                                                             'id' => 'QuestionMark'.$i.$j,
                                                                             'class' => 'criteria-mark',
                                                                            ))?>
      </td>
    <?php endforeach;?>
      <td>Scale Weight: 
    <?php echo $this->Form->input('Question.'.$i.'.multiplier', 
                                  array('options' => array_combine(range(1,15), range(1,15)),
                                        'class' => 'multiplier',
                                        'label' => false,
                                        'before' => '',
                                        'after' => '',
                                        'between' => '',
                                        'disabled' => $readonly,
                                       ))?>
      </td>
  </tr>
</table>
