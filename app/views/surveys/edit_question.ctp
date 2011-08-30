<form name="frm" id="frm" method="POST" action="<?php echo $html->url('editquestion') ?>">
<input type="hidden" name="question_id" id="question_id" value=<?php echo $question_id; ?> >
<input type="hidden" name="survey_id" id="survey_id" value="<?php echo $survey_id; ?>" >
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="3" align="center">
                <?php __('Edit Survey Question')?>
           </td>
          </tr>
          <tr class="tablecell2">
            <td width="19%"><?php __('Prompt')?>: <font color="red">*</font></td>
            <td width="48%"><?php echo $html->input('Question/prompt', array('size'=>'50','class'=>'input', 'style'=>'width:85%;')) ?></td>
            <td width="33%"><?php __('E.g. What grade do you expect to earn in this class?')?></td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('Master Question?')?></td>
            <td>
			<select name="master" style="width:85%;">
		  	<option value="yes" <?php if($params['data']['Question']['master']=='yes') echo "selected";?>><?php __('Yes')?></option>
			<option value="no" <?php if($params['data']['Question']['master']=='no') echo "selected";?>><?php __('No')?></option>

		  	</select>
			</td>
            <td> <?php __('Master question can be used as a template of a new question.')?> </td>
          </tr>
          <tr class="tablecell2">
            <td valign="top"><?php __('Question Type')?>:  <font color="red">*</font></td>
            <td>
			<input type="radio" name="type" id="type" value="M" <?php if($params['data']['Question']['type']=='M') echo "checked";?>><?php __('Multiple Choice (Single Answer)')?></input><br>
        	<input type="radio" name="type" id="type" value="C" <?php if($params['data']['Question']['type']=='C') echo "checked";?>><?php __('Choose Any Of... (Multiple Answers)')?></input><br>
			<input type="radio" name="type" id="type" value="S" <?php if($params['data']['Question']['type']=='S') echo "checked";?>><?php __('Single Line Text Input')?></input><br>
			<input type="radio" name="type" id="type" value="L" <?php if($params['data']['Question']['type']=='L') echo "checked";?>><?php __('Long Answer Text Input')?></input><br>
			</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td valign="top"><?php __('Possible Question Answers')?>: <font color="red">*</font></td>
            <td>
			<div id="adddelanswers">
			<?php
			$params = array('controller'=>'surveys', 'count'=>sizeof($responses), 'responses'=>$responses);
			echo $this->element('surveys/ajax_survey_answers', $params);
			?>
			</div>
			<input type="hidden" name="add" id="add" value="<?php echo sizeof($responses);?>">
			<?php echo $ajax->observeField('add', array('update'=>'adddelanswers', 'url'=>"/surveys/adddelquestion/".$question_id, 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?>

			</td>
            <td valign="top"><?php __("'Multiple Choice' and 'Choose Any Of...' Questions Only")?><br>
              <br>
              <?php __('Do not include an option for "I choose not to answer this question." It will be inserted automatically.')?>               <br>
              <br>
			<a href=# onclick="document.frm.add.value = parseInt(document.frm.add.value)+1;"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Answer', 'align'=>'middle', 'border'=>'0')); ?> - <?php __('Add Answer')?></a>
			<br>
			<a href=# onclick="document.frm.add.value = parseInt(document.frm.add.value)-1;"><?php echo $html->image('icons/delete.gif', array('alt'=>'Remove Answer', 'align'=>'middle', 'border'=>'0')); ?> - <?php __('Remove Answer')?></a></td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3">
			  <div align="center"><?php echo $html->submit(__('Update Question', true)) ?>
			  <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
			  <br>
              </div></td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table></td>
  </tr></form>
</table>
