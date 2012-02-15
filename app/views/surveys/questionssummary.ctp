<?php echo $this->Form->create('Course', 
                               array('id' => 'frm',
                                     'url' => array('action' => 'addQuestion'),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td width="200px">',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>

<input type="hidden" name="survey_id" id="survey_id" value="<?php if (!empty($survey_id)) echo $survey_id; ?>" >
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center">
             <?php __('Survey Summary')?>
			     </td>
          </tr>
          <tr class="tablecell2">
            <td>
			<?php if( !empty($questions)):?>
			<?php $count =1;?>

			<?php foreach ($questions as $row): $question = $row['Question'];?>
       <table align="center" width="95%" border="0" cellspacing="0" cellpadding="5">
       <tr class="tablecell">
       <td width="50"><b><font size="2"><?php __('Q:')?> <?php echo $count++?></font></b></td>
			  <?php if ($is_editable):?>
						<td width="50"><?php echo $this->Html->link($html->image('icons/edit.gif',array('border'=>'0','alt'=>__('Edit', true), 'valign' => 'middle')).__(' Edit', true),
                                                       'editQuestion/'.$question['id'].'/'.$survey_id,
                                                       array('escape' => false))?>
						<td width="60"><?php echo $this->Html->link($html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete', 'valign' => 'middle')).__(' Delete', true),
                                                       'removeQuestion/'.$survey_id.'/'.$question['id'],
                                                       array('escape' => false),
                                                       __('Are you sure to delete question &ldquo;', true).$question['prompt'].'&rdquo;?')?>
				<td align="right" valign="top">
				<table width="100" align="right" border="0" cellspacing="2" cellpadding="2">
				  <tr>
          <td width="5"><?php echo $this->Html->link($html->image('icons/top.gif',array('border'=>'0','alt'=>__('Top', true))),
                                                     'moveQuestion/'.$survey_id.'/'.$question['id'].'/TOP',
                                                     array('escape' => false))?>
          <td width="5"><?php echo $this->Html->link($html->image('icons/up.gif',array('border'=>'0','alt'=>__('Up', true))),
                                                     'moveQuestion/'.$survey_id.'/'.$question['id'].'/UP',
                                                     array('escape' => false))?>
          <td width="5"><?php echo $this->Html->link($html->image('icons/down.gif',array('border'=>'0','alt'=>__('Down', true))),
                                                     'moveQuestion/'.$survey_id.'/'.$question['id'].'/DOWN',
                                                     array('escape' => false))?>
          <td width="5"><?php echo $this->Html->link($html->image('icons/bottom.gif',array('border'=>'0','alt'=>__('Bottom', true))),
                                                     'moveQuestion/'.$survey_id.'/'.$question['id'].'/BOTTOM',
                                                     array('escape' => false))?>
				  </tr>
				</table>
				</td>
        <?php endif;?>
        </tr>

				<!-- Multiple Choice Question-->
				<?php if( $question['type'] == 'M'):?>
					<tr class="tablecell2"><td colspan="8"><?php echo $question['prompt']?></td></tr>
					<tr class="tablecell2"><td colspan="8">

					<?php if( !empty($row['Response'])):?>
						<?php foreach ($row['Response'] as $index => $value):?>
							<input type="radio" name="answer_<?php echo $row['SurveyQuestion']['number']?>" value="<?php echo $value['id']?>" /><?php echo $value['response']?><br>
						<?php endforeach;?>
					<?php endif;?>

					</td></tr>

				<!-- Choose Any... Question -->
				<?php elseif( $question['type'] == 'C'):?>
					<tr class="tablecell2"><td colspan="8"><?php echo $question['prompt']?></td></tr>
					<tr class="tablecell2"><td colspan="8">

					<?php if( !empty($row['Response'])):?>
						<?php foreach ($row['Response'] as $index => $value):?>
							<input type="checkbox" name="answer_<?php echo $row['SurveyQuestion']['number']?>" value="<?php echo $value['id']?>" /><?php echo $value['response']?><br>
						<?php endforeach;?>
					<?php endif;?>

					</td></tr>

				<!-- Short Answer Question -->
				<?php elseif( $question['type'] == 'S'):?>
					<tr class="tablecell2"><td colspan="8"><?php echo $question['prompt']?></td></tr>
					<tr class="tablecell2"><td colspan="8"><input type="text" name="answer_<?php echo $row['SurveyQuestion']['number']?>" style="width:55%;" /></td></tr>
				<!--  Long Answer Question -->
				<?php elseif( $question['type'] == 'L'):?>
					<tr class="tablecell2"><td colspan="8"><?php echo $question['prompt']?></td></tr>
					<tr class="tablecell2"><td colspan="8"><textarea name="answer_<?php echo $row['SurveyQuestion']['number']?>"  style="width:55%;" rows="3"></textarea></td></tr>
				<?php endif;?>

				</table>
			<?php endforeach;?>
			<?php endif;?>
			</td>
          </tr>
          <tr class="tablecell2">
            <td>
            <div align="center"><?php if ($is_editable) echo $this->Html->link(__('Add Questions', true), 'addQuestion/'.$survey_id, array('class' => 'button'))?>
            <?php echo $this->Html->link(__('Finish', true), 'index')?></div>
            </td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align=left><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align=right><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table></td>
  </tr>
</table>
<?php $this->Form->end()?>
