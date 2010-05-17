<form name="frm" id="frm" method="POST" action="<?php echo $html->url('addquestion') ?>">
<input type="hidden" name="survey_id" id="survey_id" value="<?php if (!empty($survey_id)) echo $survey_id; ?>" >
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center">
             Survey Summary
			     </td>
          </tr>
          <tr class="tablecell2">
            <td>
			<?php
      global $data;
			if( !empty($questions)):
			$count =0;
			foreach ($questions as $row): $question = $row['Question'];
			 $count++;
				echo '<br><table align="center" width="95%" border="0" cellspacing="0" cellpadding="5">
						<tr class="tablecell">
						<td width="50"><b><font size="2">Q: '.$count.'</font></b></td>';
			  if ($this->controller->rdAuth->id == $data['Survey']['creator_id'] || $this->controller->rdAuth->id == 1)
						echo '<td width="5"><a href="'.$this->webroot.$this->themeWeb."surveys/editquestion/".$question['id']."/".$survey_id.'">'.$html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit')).'</a> </td>
						<td width="35"><a href="'.$this->webroot.$this->themeWeb."surveys/editquestion/".$question['id']."/".$survey_id.'">Edit</a></td>
						<td width="5"><a href="'.$this->webroot.$this->themeWeb."surveys/removequestion/".$question['sq_id']."/".$survey_id."/".$question['id'].'">'.$html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'), 'Are you sure to delete question \"'. $question['prompt'] .'\"?').'</a> </td>
						<td width="25"><a href="'.$this->webroot.$this->themeWeb."surveys/removequestion/".$question['sq_id']."/".$survey_id."/".$question['id'].'" onClick=" return confirm(\'Are you sure to delete question &ldquo;'.$question['prompt'].'&rdquo;\');  ">Remove</a></td>
				<td align="right" valign="top">
				<table width="100" align="right" border="0" cellspacing="2" cellpadding="2">
				  <tr>
					<td width="5"><a href="'.$this->webroot.$this->themeWeb."surveys/questionssummary/".$survey_id.'/'.$question['id'].'/TOP ">'.$html->image('icons/top.gif',array('border'=>'0','alt'=>'Edit')).' </a></td>
					<td width="5"><a href="'.$this->webroot.$this->themeWeb."surveys/questionssummary/".$survey_id.'/'.$question['id'].'/UP ">'.$html->image('icons/up.gif',array('border'=>'0','alt'=>'Edit')).' </a></td>
					<td width="5"><a href="'.$this->webroot.$this->themeWeb."surveys/questionssummary/".$survey_id.'/'.$question['id'].'/DOWN ">'.$html->image('icons/down.gif',array('border'=>'0','alt'=>'Edit')).' </a></td>
					<td width="3"><a href="'.$this->webroot.$this->themeWeb."surveys/questionssummary/".$survey_id.'/'.$question['id'].'/BOTTOM ">'.$html->image('icons/bottom.gif',array('border'=>'0','alt'=>'Edit'))." </a></td>

				  </tr>
				</table>";

				echo "</td></tr>";

				// Multiple Choice Question
				if( $question['type'] == 'M'){
					echo '<tr class="tablecell2"><td colspan="8">'.$question['prompt']."</td></tr>";
					echo '<tr class="tablecell2"><td colspan="8">';

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo '<input type="radio" name="answer_'.$question['number']." value=".$value['id'].'" /> '.$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Choose Any... Question
				elseif( $question['type'] == 'C'){
					echo '<tr class="tablecell2"><td colspan="8">'.$question['prompt']."</td></tr>";
					echo '<tr class="tablecell2"><td colspan="8">';

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo '<input type="checkbox" name="answer_'.$question['number']." value=".$value['id'].'" /> '.$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Short Answer Question
				elseif( $question['type'] == 'S'){
					echo '<tr class="tablecell2"><td colspan="8">'.$question['prompt']."</td></tr>";
					echo '<tr class="tablecell2"><td colspan="8"><input type="text" name="answer_'.$question['number'].'" style="width:55%;" /></td></tr>';
				}
				// Long Answer Question
				elseif( $question['type'] == 'L'){
					echo '<tr class="tablecell2"><td colspan="8">'.$question['prompt']."</td></tr>";
					echo '<tr class="tablecell2"><td colspan="8"><textarea name="answer_'.$question['number'].'"  style="width:55%;" rows="3"></textarea></td></tr>';
				}

				echo "</table>";
			endforeach;
			endif;
			?>
			</td>
          </tr>
          <tr class="tablecell2">
            <td><div align="center"><?php if ($this->controller->rdAuth->id == $data['Survey']['creator_id'] || $this->controller->rdAuth->id == 1) echo $html->submit('Add Question') ?>
			<input type=button name="Finish" value="Finish" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller']; ?>'">
			</div></td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align=left><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align=right><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table></td>
  </tr>
</table></form>