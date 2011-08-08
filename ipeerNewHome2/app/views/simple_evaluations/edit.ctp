<?php $readonly = isset($readonly) ? $readonly : false;?>
<?php if($this->action == 'copy') $this->action = 'add'?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $this->Form->create('SimpleEvaluation',
                                   array('id' => 'frm',
                                         'url' => array('controller' => 'simpleevaluations', 'action' => $this->action),
                                         'inputDefaults' => array('div' => false,
                                                                  'before' => '<td width="200px">',
                                                                  'after' => '</td>',
                                                                  'between' => '</td><td>')))?>
      <input type="hidden" name="required" id="required" value="SimpleEvaluationName SimpleEvaluationPointPerMember" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php echo ucfirst($this->action)?> Simple Evaluation</td>
        </tr>

        <!-- Evaluation Name -->
        <tr class="tablecell2">
          <?php echo $this->Form->input('name', array('size'=>'80', 'class'=>'validate required TEXT_FORMAT name_msg Invalid_name.', 
                                                          'error' => array('unique' => __('Duplicate name found. Please change the name.', true)),
                                                          'readonly' => $readonly));?>
          <?php echo $readonly ? '' : $ajax->observeField('name', array('update'=>'nameErr', 'url'=>'checkDuplicateTitle/', 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")); ?>
          <td width="255" id="name_msg" class="error" ><div id='usernameErr' class="error"></div></td>
        </tr>

        <!-- Description -->
        <tr class="tablecell2">
          <?php echo $this->Form->input('description', array('cols'=>60, 'rows' => 10, 'class'=>'validate none none', 'readonly' => $readonly)) ?>
          <td id="description_msg" class="error">&nbsp;</td>
        </tr>


        <!-- Base Point Per Member -->
        <tr class="tablecell2">
            <?php echo $this->Form->input('point_per_member', array('size'=>'5', 'class'=>'validate required NUMERIC_FORMAT point_per_member_msg Invalid_Number_Value.',
                                                              'readonly' => $readonly,
                                                              'onChange' => 'return ensureEntriesValid();')) ?>
            <td id="point_per_member_msg" class="error">&nbsp;</td>
        </tr>

        <tr class="tablecell2">
          <td colspan="3" align="center">
      	<input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php echo $this->Form->submit('Save',  array('div' => false, 'onclick' => 'return validate();')); ?>
      	</td>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
<?php echo $this->Form->end();?>
</td>
</tr>
</table>


<script>
// Ensure that the entries are valid
function ensureEntriesValid() {
  var bppm = $("SimpleEvaluationPointPerMember");
  if (bppm.value > 0) {
    return true;
  }

  alert ("Base points per member *must be* at least 1 point.\nHowever, at least 10 is recommended.");
  bppm.value = 10; bppm.focus();bppm.select();
  return false;
}
</script>
