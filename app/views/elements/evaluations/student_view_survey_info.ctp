<form name="frm" id="frm" method="POST" action="<?php echo $html->url('makeEvaluation/'.$event['Event']['id']) ?>">
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
<input type="hidden" name="survey_id" id="survey_id" value="<?php if (!empty($survey_id)) echo $survey_id; ?>" />
<input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>"/>
<input type="hidden" name="data[Evaluation][surveyee_id]" value="<?php echo $this->Auth->user('id')?>"/>
<input type="hidden" name="question_count" value="<?php echo count($questions)?>"/>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center"><?php __('Team Maker Survey')?></td>
          </tr>
          <tr class="tablecell2">
            <td>
			<?php
			if( !empty($questions)):
			foreach ($questions as $row): $question = $row['Question'];
				echo "<br><table align=\"center\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
						<tr class=\"tablecell\">
						<td width=\"50\"><b><font size=\"2\">Q: ".$question['number']."</font></b><br>";
				echo "</td></tr>";

				// Multiple Choice Question
				if( $question['type'] == 'M'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
					echo "<input type=\"hidden\" name=\"question_id".$question['number']."\" value=\"".$question['id']."\"/>";

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo "<input type=\"radio\" name=\"answer_".$question['number']."\" value=\"".$value['response']."_".$value['id']."\" > ".$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Choose Any... Question
				elseif( $question['type'] == 'C'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
					echo "<input type=\"hidden\" name=\"question_id".$question['number']."\" value=\"".$question['id']."\"/>";

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo "<input type=\"checkbox\" name=\"answer_".$question['number']."\" value=\"".$value['response']."_".$value['id']."\" > ".$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Short Answer Question
				elseif( $question['type'] == 'S'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\"><input type=\"text\" name=\"answer_".$question['number']."\" style='width:55%;'></td></tr>";
					echo "<input type=\"hidden\" name=\"question_id".$question['number']."\" value=\"".$question['id']."\"/>";
				}
				// Long Answer Question
				elseif( $question['type'] == 'L'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\"><textarea name=\"answer_".$question['number']."\"  style='width:55%;' rows=\"3\"></textarea></td></tr>";
					echo "<input type=\"hidden\" name=\"question_id".$question['number']."\" value=\"".$question['id']."\"/>";
				}

				echo "</tr></table>";
			endforeach;
			endif;
			?>
			</td>
          </tr>
          <tr class="tablecell2">
            <td><div align="center"><?php echo $html->submit(__('Submit', true)) ?>
			</div></td>
          </tr>
      </table>
    </td>
  </tr></form>
</table>
