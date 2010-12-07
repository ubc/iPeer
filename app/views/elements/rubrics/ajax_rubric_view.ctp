<!-- elements::ajax_rubric_view start -->
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    	<tr class="tableheader" align="center">
		<td width=100 valign="top">Rubric View </td>
		<?php
		$LOM_num = $data['lom_max'];
		$criteria_num = $data['criteria'];
		$rubric_type = $data['template'];
		$zero_mark = $data['zero_mark'];
		isset($user)? $userId = $user['id'] : $userId = '';
		isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;

		// horizontal template type
		if( $rubric_type == "horizontal" ){
			//for loop to display the top header row with LOM comments
			for($i=1; $i<=$LOM_num; $i++){
				echo "<td>Level of Mastery $i:<br>".$data['lom_comment'.$i]."</td>";
			}
			//Comment for Evaluation Form
			if ($evaluate)
			{
			 echo "<td align='left'>Comments</td>";
			}
			echo "</tr>";

			//for loop to display the criteria rows
			for($i=1; $i<=$criteria_num; $i++){
				echo '<tr class="tablecell" align="center"><td class="tableheader2" valign="top">';
			  if (isset($evaluation)) {
			     echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$i.'" value="'.$evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'].'">';
			  } else {
          echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$i.'" value="1" size="4" >';
        }

				echo '<table border="0" width="95%" cellpadding="2"><tr><td>'.$i.': '.$data['criteria'.$i].'</td></tr><tr><td><i>'
					 .$data['criteria_weight_'.$i]." mark(s)</i></td></tr></table></td>";
				//for loop to display the criteria comment cells for each LOM
				for($j=1; $j<=$LOM_num; $j++){
					if( $zero_mark == "on" ){
						$mark_value = round( ($data['criteria_weight_'.$i]/($LOM_num-1)*($j-1)) , 2);
					}
					else{
						$mark_value = round( ($data['criteria_weight_'.$i]/$LOM_num*$j) , 2);            
					}
					echo "<td>".(!empty($data['criteria_comment_'.$i.'_'.$j]) ? "<font color=#000066><b>".$data['criteria_comment_'.$i.'_'.$j]."</b></font>":'')."<br><input name='".$userId."criteria_points_$i' type='radio' value='$mark_value' onClick=\"document.evalForm.selected_lom_".$userId."_".$i.".value=".$j.";\"";
					if (isset($evaluation)) {
					  if ($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'] == $j) echo " checked ";
					} else {
  					if ($j==1) echo " checked ";
  				}
					echo "/>";
					if (!$evaluate) {


					    echo "<br><br><font color=#FF6600>Points: ".$mark_value."</font>";
						}
					echo	"</td>";
				}
				//Comment for Evaluation Form
				if ($evaluate)
				{
				 echo "<td align='left'><textarea cols='20' rows='2' name='".$userId."comments[]'>";
				 	if (isset($evaluation)) {
					  echo $evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['criteria_comment'];
					}
				 echo "</textarea>";
				 echo "</td>";
				}
			}
				echo "</tr>";
		}
echo "<tr>";
		if (!$evaluate) {
  		echo '<td colspan="'.($LOM_num+1).'" align="right">Total Marks: '.$data['total_marks'].'</td>';
		}
		else{
  	    echo '<td colspan='.($LOM_num+2).' align="center" class="tableheader2">General Comments <br><textarea cols="80" rows="2" name="'.$userId.'gen_comment" >';
		    if (isset($evaluation)) {
  	  		echo $evaluation['EvaluationRubric']['general_comment'];
  	  	}
  	  	echo "</textarea></td>";
  	}
  	echo "</tr>";

		?>
  </table>
<!-- elements::ajax_rubric_preview end -->