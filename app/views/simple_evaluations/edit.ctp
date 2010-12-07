<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
  <?php echo $this->Form->create('SimpleEvaluation', 
                                 array('id' => 'frm',
                                       'url' => array('controller' => 'simpleevaluations', 'action' => $this->action),
                                       'onSubmit' => 'return validate();',
                                       'inputDefaults' => array('div' => false,
                                                                'before' => '<td width="200px">',
                                                                'after' => '</td>',
                                                                'between' => '</td><td>')))?>
      <input type="hidden" name="required" id="required" value="point_per_member" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php echo empty($this->data['SimpleEvaluation']['id'])?'Add':'Edit' ?> Simple Evaluation</td>
        </tr>
        <tr class="tablecell2">
        <?php echo $this->Form->input('name', array('label' => 'Evaluation Name<font color="red">*</font>',
                                                    'class' => 'validate required TEXT_FORMAT name_msg Invalid_Name.',
                                                    'size' => 50))?>
            <div id='titleErr' class="error">
                <?php
                /*$fieldValue = isset($this->data['SimpleEvaluation']['name'])? $this->data['SimpleEvaluation']['name'] : '';
                $params = array('controller'=>'simpleevaluations', 'data'=>null, 'fieldvalue'=>$fieldValue);
                echo $this->element('simple_evaluations/ajax_title_validate', $params);*/
                ?>
            </div>
          </td>
          <td width="243" id="name_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
        	<?php echo $this->Form->input('description', array('cols'=>'50', 'class'=>'validate none none'))?>
        	<td id="description_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
        	<?php echo $this->Form->input('point_per_member', array('label' => 'Base Point Per Member:<font color="red">*</font>', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT point_per_member_msg Invalid_Number_Value.')) ?>
        	<td id="point_per_member_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
          <td colspan="3" align="center">
          <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
          <script>
                // Ensure that the entries are valid
                function ensureEntriesValid() {
                    var bppm = $("point_per_member");
                    if (bppm.value > 0) {
                        return true;
                    } else {
                        alert ("Base points per member *must be* at least 1 point.\nHowever, at least 10 is recommended.");
                        bppm.value = 10; bppm.focus();bppm.select();
                        return false;
                    }
                }
          </script>
          <?php echo $this->Form->submit('Save', array('onclick' => 'return ensureEntriesValid();',
                                                       'div' => false)); ?>

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
