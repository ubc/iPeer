<!-- elements::ajax_rubric_preview start -->
<?php echo $html->script('calculate_marks'); ?>
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    	<tr class="tableheader" align="center">
			<td valign="top" colspan="<?php echo $scale_default?>" width="90%"  align='left'><?php ('Section One')?>: &nbsp;<?php ('Lickert Scales')?></td>
		<?php
		$descriptor_des = array();//array('1'=>'Lowest','2'=>'','3'=>'Middle','4'=>'','5'=>'Highest');

		isset($data['questions'])? $questions = $data['questions'] : $questions = null;

		//for loop to display the top header row with LOM comments
		echo "<td width=\"10%\">Scale Weight</td>";
		echo "</tr>";
	  $pos = 1;

		//for loop to display the criteria rows
		for($i=1; $i<=$question_default; $i++){
		  //Get and set Mixeval Question
		  isset($questions[$pos])? $mixevalQuestion = $questions[$pos] : $mixevalQuestion = null;
		  if ($mixevalQuestion !=null) {
		    $questionDescriptors = $mixevalQuestion['descriptors'];
   		  //$this->controller->Output->br2nl($mixevalQuestion);
		    $descriptor_des = array();

		    foreach ($questionDescriptors as $row) {
		      $desc = $row['MixevalsQuestionDesc'];
          $this->controller->Output->br2nl($desc);
		      $descriptor_des[$desc['scale_level']] = $desc['descriptor'];
		    }
		  }
		  if (empty($total_mark)) $total_mark = $question_default;
		  else $total_mark = $data['Mixeval']['total_marks'];

		  if (!empty($mixevalQuestion['title']))
		    $area_atrb = array('style'=>'width:95%;', 'value'=>$mixevalQuestion['title']);
		  else
		    $area_atrb = array('style'=>'width:95%;');

			echo "<tr class=\"tablecell\" align=\"center\">";
			echo "<td class=\"tableheader2\" valign=\"top\" colspan=\"".$scale_default."\"><table border=\"0\" width=\"95%\" cellpadding=\"2\"><tr><td width=\"15%\">".__(Question , true). $pos.":</td><td width=\"85%\">"
				  .$html->areaTag('Mixeval/title'.$pos, '',2, $area_atrb)."<br>"
				  ."</td></tr></table></td><td/>";
			echo '</tr><tr class="tablecell" align="center">';

			//for loop to display the criteria comment cells for each LOM
			for($j=1; $j<=$scale_default; $j++){
				isset($mixevalQuestion['multiplier']) ? $multiplier = $mixevalQuestion['multiplier'] : $multiplier = 1;

				if( $zero_mark == "on" ){
					$mark_value = round( ($multiplier/($scale_default-1)*($j-1)) , 2);
				}
				else{
					$mark_value = round( ($multiplier/$scale_default*$j) , 2);
				}

				if (isset($descriptor_des[$j]) && !empty($descriptor_des[$j]))
				  $textArray = array('style'=>'width:95%;','value'=>$descriptor_des[$j]);
				else
				  $textArray = array('style'=>'width:95%;');
				echo '<td><table border="0" width="95%" cellpadding="2"><tr><td align="left">Descriptor</td></tr><tr><td>'.$html->areaTag('Mixeval/criteria_comment_'
					 .$i.'_'.$j,'',1, $textArray)."</td></tr>";
			  echo '<tr><td align="center"><input type="radio" name="criteria_radio_'.$i."_".$j.'" class="input" size="3" >'."</td></tr>";
			  echo '
			  <tr><td align="center">Mark: '.'<input type="text" name="criteria_mark_'.$i."_".$j.'" id="criteria_mark_'.$i."_".$j.'" class="input" size="3" readonly value="'.$mark_value.'">'."</td></tr>";
			  echo "</table></td>";
			}

			echo '<td>';
      echo $html->hidden('Mixeval/question_type'.$pos, array('value'=>'S'));
			echo '<select name="criteria_weight_'.$i.'" id="criteria_weight_'.$i.'" style="width:50px;" onchange="calculateMarks(\''.$scale_default.'\',\''.$question_default.'\',\''.$zero_mark.'\')">';
			for ($x = 1; $x <= 15; $x++) {
  			echo '<option value="'.$x.'" ';
  			if ($x == $multiplier) {
  			  echo 'selected';
  			}
  			echo ' >'.$x.'</option>';
  		}
			echo '</select>';
			echo '</td>';

			echo "</tr>";
			$pos++;
		}
		echo '<tr class="tableheader2">';
		echo '<td colspan="'.($scale_default).'" align="right">'.__('Total Marks', true).': </td>';
		echo '<td align="center"> <input type="text" name="data[Mixeval][total_marks]" id="total_marks" class="input" value="'.$total_mark.'" size="5" readonly></td>';
		echo "</tr>";
		?>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
  </table>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader" align="center">
			<td align="left"><?php __('Section Two')?>: &nbsp;<?php __('Comments (No weight on this section)')?></td>
		</tr>
 	<?php	//for loop to display the criteria rows
		for($i=1; $i<=$prefill_question_max; $i++){
		  		  //Get Mixeval Question
		  isset($questions[$pos])? $mixevalQuestion = $questions[$pos] : $mixevalQuestion = null;
		  $this->controller->Output->br2nl($mixevalQuestion);
  ?>
			<tr class="tablecell" align="center">
  			<td class="tableheader2" valign="top" colspan="<?php echo $scale_default?>">
  			  <table border="0" width="95%" cellpadding="2">
  			    <tr><td width="15%"><?php __('Question')?> <?php echo $pos?>:</td>
  			        <td width="85%" align="left"><?php __('Question Prompt')?>:
  			          <?php echo	$html->input('Mixeval/title'.$pos, array('style'=>'width:100%;', 'value'=>isset($mixevalQuestion['title'])? $mixevalQuestion['title'] : '')) ?> <br>
  			          <?php echo $html->hidden('Mixeval/question_type'.$pos, array('value'=>'T'));?>
  				      </td>
  				    </tr><tr>
                <td/>
                <td align="left"><?php __('Mandatory?')?>:
                  <?php
                   $checkRequired = 'checked';
                   $checkNo = '';
                   if (isset($mixevalQuestion['required']) && $mixevalQuestion['required']==0) {
                     $checkRequired = '';
                     $checkNo = 'checked';
                   }?>
          		    <input type="radio" name="data[Mixeval][text_require<?php echo $pos?>]" value="1" <?php echo $checkRequired?> > <?php __('Yes')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          		    <input type="radio" name="data[Mixeval][text_require<?php echo $pos?>]" value="0" <?php echo $checkNo ?> > <?php __('No')?><br>
                </td>
  				  </tr>
  			  </table>
  		 </td>
		  </tr>
		  <tr class="tablecell" align="center">
			  <td>
			    <table border="0" width="95%" cellpadding="2">
			      <tr>
			        <td colspan="2" align="left"><?php __('Instructions: (optional)')?></td>
            </tr>
            <tr><td colspan="2">
                  <?php
                  if (isset($mixevalQuestion['instructions']) && !empty($mixevalQuestion['instructions']))
                    $textArray = array('style'=>'width:100%;', 'value'=>$mixevalQuestion['instructions']);
                  else
                    $textArray = array('style'=>'width:100%;');
                  echo $html->areaTag('Mixeval/text_instruction'.$pos,'',2, $textArray); ?>
            </td></tr>
            <tr><td width="15%" align="left"><?php __("Student's Answer Option:")?> </td>
                <td width="85%" align="left">
                  <?php
                   $responseLickert = 'checked';
                   $responseText = '';
                   if (isset($mixevalQuestion['response_type']) && $mixevalQuestion['response_type']=='L') {
                   $responseLickert = '';
                   $responseText = 'checked';
                   }?>
          		    <input type="radio" name="data[Mixeval][response_type<?php echo $pos?>]" value="S" <?php echo $responseLickert?>  ><?php __('Single line of text input box')?><br>
          		    <input type="radio" name="data[Mixeval][response_type<?php echo $pos?>]" value="L" <?php echo $responseText?> > <?php __('Multiple lines of text input box')?><br>
                </td></tr>
			 </table></td>
			</tr>
<?php			$pos++;
		}
		?>
  <tr>
  		<td colspan="3" align="center">
<?php echo $html->hidden('Mixeval/total_question', array('value'=>$pos-1));?>
		<input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
  		  <?php if (empty($params['data']['Mixeval']['id'])) {
  		      echo $html->submit(__('Add Mix Evaluation', true));
  		    } else {
  		      echo $html->submit(__('Edit Mix Evaluation', true));
  		    }?>
		</td>
    </tr>
  </table>

  <?php //echo $html->hidden('Mixeval/name', array('value' => $data['Mixeval']['name']));?>
  <?php //echo $html->hidden('Mixeval/scale_max', array('value' => $data['Mixeval']['scale_max']));?>
  <?php //echo $html->hidden('Mixeval/prefill_question_max', array('value' => $data['Mixeval']['prefill_question_max']));?>
  <?php //echo $html->hidden('Mixeval/lickert_question_max', array('value' => $data['Mixeval']['lickert_question_max']));?>
  <?php //echo $html->hidden('Mixeval/availability', array('value' => $data['Mixeval']['availability']));?>
  <?php //echo $html->hidden('Mixeval/creator_id', array('value' => $data['Mixeval']['creator_id']));?>
<!-- elements::ajax_rubric_preview end -->
