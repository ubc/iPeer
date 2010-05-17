<form name="frm" id="frm" method="POST" action="<?php echo $html->url('addquestion') ?>">
<input type="hidden" name="survey_id" id="survey_id" value="<?php echo $this->params['form']['survey_id']; ?>">
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="3" align="center">
                Add Question to Survey
            </td>
          </tr>
          <tr class="tablecell2">
            <td width="19%">Load Existing Question:</td>
            <td width="48%">
			<select name="questions" style="width:70%;">
		  	<option value=>(Select Question to Load Its Details)</option>
			<?php
				for($i=0; $i<sizeof($templates); $i++)
				{
					echo "<option value='".$templates[$i]['Question']['id']."'>".$templates[$i]['Question']['prompt']."</option>";
				}
			?>
		  	</select>
			<?php echo $html->submit('Load Question', array('Name'=>'loadq')) ?>
			</td>
            <td width="33%">Select from the list to load an existing question as your question template.</td>
          </tr>
          <tr class="tablecell2">
            <td>Question: <font color="red">*</font></td>
            <td><?php echo $html->input('Question/prompt', array('size'=>'50','class'=>'input', 'style'=>'width:85%;')) ?></td>
            <td>E.g. What grade do you expect to earn in this class?</td>
          </tr>
          <tr class="tablecell2">
            <td>Master Question?</td>
            <td>
			<select name="master" style="width:85%;">
		  	<option value="yes" <?php if (isset($type)) echo ''; else echo 'selected'; ?>>Yes</option>
			<option value="no" <?php if (isset($type)) echo 'selected'; else echo ''; ?>>No</option>

		  	</select>
			</td>
            <td> Master question can be used as a template of a new question. </td>
          </tr>
          <tr class="tablecell2">
            <td valign="top">Question Type:  <font color="red">*</font></td>
            <td>
			<input type="radio" name="type" value="M" <?php if(empty($type) || $type == "M") echo "checked";?> />Multiple Choice (Single Answer)<br>
        	<input type="radio" name="type" value="C" <?php if(!empty($type) && $type == "C") echo "checked";?> />Choose Any Of... (Multiple Answers)<br>
			<input type="radio" name="type" value="S" <?php if(!empty($type) && $type == "S") echo "checked";?> />Single Line Text Input<br>
			<input type="radio" name="type" value="L" <?php if(!empty($type) && $type == "L") echo "checked";?> />Long Answer Text Input<br>
			</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td valign="top">Possbile Question Answers: <font color="red">*</font></td>
            <td>
			<div id="adddelanswers">
			<?php
			if(empty($count)) $count=4;
			$params = array('controller'=>'surveys', 'count'=>$count);
			echo $this->renderElement('surveys/ajax_survey_answers', $params);
			?>
			</div>
			<input type="hidden" name="add" id="add" value="4">
			<?php echo $ajax->observeField('add', array('update'=>'adddelanswers', 'url'=>"/surveys/adddelquestion", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?>

			</td>
            <td valign="top">'Multiple Choice' and 'Choose Any Of...' Questions Only<br>
              <br>
              Do not include an option for "I choose not to answer this question." Iit will be inserted automatically.               <br>
              <br>
			<a href=# onclick="document.frm.add.value = parseInt(document.frm.add.value)+1;"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Answer', 'align'=>'middle', 'border'=>'0')); ?> - Add Answer</a>
			<br>
			<a href=# onclick="document.frm.add.value = parseInt(document.frm.add.value)-1;"><?php echo $html->image('icons/delete.gif', array('alt'=>'Remove Answer', 'align'=>'middle', 'border'=>'0')); ?> - Remove Answer</a></td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3">
			  <div align="center">
			  <input type="button" name="Back" value="Back" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller'].'/questionssummary/'.$survey_id; ?>'"> <?php echo $html->submit('Add Question') ?>
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
  </tr>
</table></form>
