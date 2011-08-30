
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
      <form name="frm" id="frm" method="POST" action="<?php echo $html->url('makeSurveyEvaluation') ?>">
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center"><?php __('Team Maker Survey')?></td>
          </tr>
          <tr class="tablecell2">
            <td>
			<?php
			if( !empty($questions)):
			for ($i=1; $i <= count($questions); $i++): 
                          $question = $questions[$i]['Question'];
                          $answer = $answers[$question['id']];
				echo "<br><table align=\"center\" width=\"95%\" cellspacing=\"0\" cellpadding=\"5\">
						<tr class=\"tablecell\">
						<td width=\"50\"><b><font size=\"2\">Q: ".$question['number']."</font></b><br>";
				echo "</td></tr>";

				// Multiple Choice Question
				if( $question['type'] == 'M'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
  						$checked = '';
              if($answer['SurveyInput']['question_id'] == $question['id'] && $answer['SurveyInput']['response_id'] == $value['id']) {
                $checked = 'checked';
              }
							echo "<input type=\"radio\" name=\"answer_".$question['number']."\" value=\"".$value['response']."_".$value['id']."\" ".$checked." disabled> ".$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Choose Any... Question
				elseif( $question['type'] == 'C'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";

					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
  						$checked = '';
              if($answer['SurveyInput']['question_id'] == $question['id'] && $answer['SurveyInput']['response_id'] == $value['id']) {
                $checked = 'checked';
              }
							echo "<input type=\"checkbox\" name=\"answer_".$question['number']."[]\" value=\"".$value['response']."_".$value['id']."\" ".$checked." disabled> ".$value['response']."<br>";
						endforeach;
					}

					echo "</td></tr>";
				}
				// Short Answer Question
				elseif( $question['type'] == 'S'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\"><input type=\"text\" name=\"answer_".$question['number']."\" style='width:55%;' value=\"".$answer['SurveyInput']['response_text']."\" disabled></input></td></tr>";
				}
				// Long Answer Question
				elseif( $question['type'] == 'L'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">".$question['prompt']."</td></tr>";
					echo "<tr class=\"tablecell2\"><td colspan=\"8\"><textarea name=\"answer_".$question['number']."\"  style='width:55%;' rows=\"3\" disabled>".$answer['SurveyInput']['response_text']."</textarea></td></tr>";
				}

				echo "</tr></table>";
			endfor;
			endif;
			?>
			</td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          </tr>
        </table></td>
  </tr></form>
</table>
