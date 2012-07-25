
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center"><?php __('Team Maker Survey Summary')?></td>
          </tr>
          <tr class="tablecell2">
            <td>
			<?php
			if( !empty($questions)):
			for ($i=1; $i <= count($questions); $i++): $question = $questions[$i]['Question'];
				echo "<br><table align=\"center\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
						<tr class=\"tablecell\">
						<td width=\"50\" colspan=\"8\"><b><font size=\"2\">Q: ".$question['number'].") &nbsp; ".$question['prompt']."</font></b>";
				echo "</td></tr>";

				// Multiple Choice Question
				if( $question['type'] == 'M'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
          echo '<table border="0">';
					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
						  $percent = $question['total_response'] != 0 ? round(($value['count']/$question['total_response'])*100): 0;
							echo "<tr><td width=\"250\">".$value['response']." </td><td width=\"30\"> ".$value['count']." </td><td> ".$percent."% </td><td> ".$html->image("evaluations/bar.php?per=".$percent,array('alt'=>$percent))."</td></tr>";
						endforeach;
					}

					echo "</table></td></tr>";
				}
				// Choose Any... Question
				elseif( $question['type'] == 'C'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
          echo '<table border="0">';
					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
						  $percent = $question['total_response'] != 0 ? round(($value['count']/$question['total_response'])*100): 0;
							echo "<tr><td width=\"250\">".$value['response']."</td><td width=\"30\"> ".$value['count']." </td><td> ".$percent."% </td><td> ".$html->image("evaluations/bar.php?per=".$percent,array('alt'=>$percent))."</td></tr>";
						endforeach;
					}

					echo "</table></td></tr>";
				}
				// Short Answer Question
				elseif( $question['type'] == 'S'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
					echo '<table>';
					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo "<tr valign=\"top\"><td width=\"250\">".$value['user_name']."</td><td width=\"15\"></td><td><i>".$value['response_text']."</i><td></tr>";
						endforeach;
					}

					echo "</table></td></tr>";
				}
				// Long Answer Question
				elseif( $question['type'] == 'L'){
					echo "<tr class=\"tablecell2\"><td colspan=\"8\">";
					echo '<table border="0">';
					if( !empty($question['Responses'])){
						foreach ($question['Responses'] as $index => $value):
							echo "<tr valign=\"top\"><td width=\"250\">".$value['user_name']."</td><td width=\"15\"></td><td><i>".$value['response_text']."</i><td></tr>";
						endforeach;
					}

					echo "</table></td></tr>";
				}

				echo "</table>";
			endfor;
			endif;
			?>

			</td>
          </tr>
      </table>
    </td>
  </tr>
</table>