<!-- elements::ajax_survey_makegroups end -->
<div id="ajax_update">
<?php echo $this->Form->create(false, 
                               array('id' => 'frm',
                                     'url' => array('action' => 'maketmgroups'),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td width="200px">',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>

  <?php echo $this->Form->input('survey_id', array('type' => 'hidden', 'value' => $data['Survey']['id']))?>
  <table width="65%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td align="center"><?php __('Team Making - Step One')?></td>
    </tr>
    <tr class="tablecell2">
      <td>
  			<?php echo $data['Course']['student_count']?><?php __(' students were specified for this survey,')?> <?php echo $data['Event'][0]['response_count']?> <?php __('students responded')?><br />
			</td>
		</tr>
		<tr class="tablecell2">
			<td><?php __('Group Configuration')?>:
        <?php echo $this->Form->select('group_config', $group_list, null)?>
      </td>
    </tr><?php if (!isset($data['Question'])||count($data['Question'])<1): ?>
    <tr class="tablecell2"><td>
       <?php echo __("There must be at least one question.", true); ?>
       </td>
    </tr>
    <?php endif;?>

    <?php foreach ($data['Question'] as $i => $q):?>
      <?php if ($q['type'] == 'M' || $q['type'] == 'C'): ?>
    <tr class="tablecell2">
    	<td width="40%" colspan="2"><b>Question<?php echo $i+1;?>: <?php echo $q['prompt'];?></b><br />
      <table>
        <tr>
          <td align="center">
          <?php echo $html->image('survey/correlate.gif',array('alt'=>'correlate')); ?>
          <td bgcolor="#00ff00"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="-5"></td>
          <td bgcolor="#30ff30"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="-4"></td>
          <td bgcolor="#60ff60"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="-3"></td>
          <td bgcolor="#90ff90"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="-2"></td>
          <td bgcolor="#c0ffc0"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="-1"></td>
          <td bgcolor="#ffffff"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="0" checked></td>
          <td bgcolor="#ffcfcf"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="1"></td>
          <td bgcolor="#ff9f9f"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="2"></td>
          <td bgcolor="#ff6f6f"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="3"></td>
          <td bgcolor="#ff3f3f"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="4"></td>
          <td bgcolor="#ff0f0f"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="5"></td>
          <td align="center"><?php echo $html->image('survey/differentiate.gif',array('alt'=>'differentiate')); ?></td>
        </tr>
        <tr><td><?php __('Gather<br />Similar')?></td><td colspan="11" align="center"><?php __('Ignore')?></td>
          <td align="center"><?php __('Gather<br />Dissimilar')?></td></tr>
      </table>
      </td>
    </tr>
     <?php endif;?>
   <?php endforeach;?>
    <tr class="tablecell2"><td><b><?php __('Note: It may take up to 10mins to create groups.')?></b></td></tr>
    <tr class="tablecell2">
      <td>
        <div align="center">
           <?php echo $this->Form->submit('Next',array('onClick'=>'Element.show(\'loading\');',
                                                       'disabled'=>(!isset($data['Question'])||count($data['Question'])<1),
                                                       'div' => false));?>
           <input type="button" name="Cancel" value="Cancel" onClick="parent.location='javascript:history.go(-1)'">
         </div>
      </td>
    </tr>
  </table>
  <table width="65%" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
    <tr>
      <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
    </tr>
  </table>
</form>
</div>
<!-- elements::ajax_survey_makegroups end -->
