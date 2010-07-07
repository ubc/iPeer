<!-- elements::ajax_rubric_preview start -->
<?php echo $javascript->link('calculate_marks')?>
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    	<tr class="tableheader" align="center">
			<td valign="top">Rubric Preview</td>
		<?php
		$LOM_num = $data['lom_max'];
		$criteria_num = $data['criteria'];
		$rubric_type = $data['template'];
		$zero_mark = $data['zero_mark'];
		$total_marks = 0;


		//for loop to display the top header row with LOM comments
		for($i=1; $i<=$LOM_num; $i++){
			echo "<td>LOM General Comment $i<br>".$html->areaTag('Rubric/lom_comment'.$i,'',2, array('style'=>'width:90%;','value'=>$data['lom_comment'.$i]))."</td>";
		}
		echo "<td>Criteria Weight</td>";
		echo "</tr>";

		// horizontal template type
		if( $rubric_type == "horizontal" ){
			//for loop to display the criteria rows
			for($i=1; $i<=$criteria_num; $i++){
				echo '<tr class="tablecell" align="center">';
				echo '<td class="tableheader2" valign="top"><table border="0" width="95%" cellpadding="2"><tr><td>Criteria '.$i.'</td></tr><tr><td>'
					  .$html->areaTag('Rubric/criteria'.$i,15,2, array('value'=>$data['criteria'.$i]))."<br>"
					  ."</td></tr></table></td>";

				//for loop to display the criteria comment cells for each LOM
				for($j=1; $j<=$LOM_num; $j++){
					if( $zero_mark == "on" ){
						$mark_value = round( ($data['criteria_weight_'.$i]/($LOM_num-1)*($j-1)) , 2);
					}
					else{
						$mark_value = round( ($data['criteria_weight_'.$i]/$LOM_num*$j) , 2);
					}
					if (!empty($data['criteria_comment_'.$i.'_'.$j]))
					  $area_atrb = array('style'=>'width:100%;', 'value'=>$data['criteria_comment_'.$i.'_'.$j]);
					else
					  $area_atrb = array('style'=>'width:100%;');
					echo '<td><table border="0" width="95%" cellpadding="2"><tr><td>Specific Comment</td></tr><tr><td>'.$html->areaTag('Rubric/criteria_comment_'.$i.'_'.$j,'',2, $area_atrb)."</td></tr><tr><td>Mark: ".'<input type="text" name="data[Rubric][criteria_mark_'.$i."_".$j.']" class="input" size="3" readonly value="'.$mark_value.'">'."</td></tr></table></td>";
				}

				echo '<td>';
				echo '<select name="data[Rubric][criteria_weight_'.$i.']" style="width:50px;" onchange="calculateMarks(\''.$LOM_num.'\',\''.$criteria_num.'\',\''.$zero_mark.'\')">';
				echo '<option value="1" selected >1</option>';
				echo '<option value="1"'; if( $data['criteria_weight_'.$i] == 1 ) echo ' selected '; echo '>1</option>';
				echo '<option value="2"'; if( $data['criteria_weight_'.$i] == 2 ) echo ' selected '; echo '>2</option>';
				echo '<option value="3"'; if( $data['criteria_weight_'.$i] == 3 ) echo ' selected '; echo '>3</option>';
				echo '<option value="4"'; if( $data['criteria_weight_'.$i] == 4 ) echo ' selected '; echo '>4</option>';
				echo '<option value="5"'; if( $data['criteria_weight_'.$i] == 5 ) echo ' selected '; echo '>5</option>';
				echo '<option value="6"'; if( $data['criteria_weight_'.$i] == 6 ) echo ' selected '; echo '>6</option>';
				echo '<option value="7"'; if( $data['criteria_weight_'.$i] == 7 ) echo ' selected '; echo '>7</option>';
				echo '<option value="8"'; if( $data['criteria_weight_'.$i] == 8 ) echo ' selected '; echo '>8</option>';
				echo '<option value="9"'; if( $data['criteria_weight_'.$i] == 9 ) echo ' selected '; echo '>9</option>';
				echo '<option value="10"'; if( $data['criteria_weight_'.$i] == 10 ) echo ' selected '; echo '>10</option>';
				echo '<option value="11"'; if( $data['criteria_weight_'.$i] == 11 ) echo ' selected '; echo '>11</option>';
				echo '<option value="12"'; if( $data['criteria_weight_'.$i] == 12 ) echo ' selected '; echo '>12</option>';
				echo '<option value="13"'; if( $data['criteria_weight_'.$i] == 13 ) echo ' selected '; echo '>13</option>';
				echo '<option value="14"'; if( $data['criteria_weight_'.$i] == 14 ) echo ' selected '; echo '>14</option>';
				echo '<option value="15"'; if( $data['criteria_weight_'.$i] == 15 ) echo ' selected '; echo '>15</option>';
				echo '</select>';
				echo '</td>';

				echo "</tr>";
				$total_marks += $data['criteria_weight_'.$i];
			}
		}

		echo '<tr class="tableheader2">';
		echo '<td colspan="'.($LOM_num+1).'" align=right>Total Marks: </td>';
		echo '<td> <input type="text" name="total_marks" id="total" class="input" value='.$total_marks.' size="5" readonly></td>';
		echo "</tr>";
		?>
  </table>
<!-- elements::ajax_rubric_preview end -->