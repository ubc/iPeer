<?php

	if(!empty($data)){
		$mixeval_name = $data['Mixeval']['name'];
		$scale_default = $data['Mixeval']['scale_max'];
		$prefill_question_max = $data['Mixeval']['prefill_question_max'];
		$question_default = $data['Mixeval']['lickert_question_max'];
		$mixeval_avail = $data['Mixeval']['availability'];
		$total_mark = isset($data['Mixeval']['total_marks']) ? $data['Mixeval']['total_marks'] : "";
		if(!empty($data['Mixeval']['zero_mark']))
			$zero_mark = $data['Mixeval']['zero_mark'];
		else
			$zero_mark='off';
	}
	else{
		$mixeval_name = '';
		$scale_default = 5;
		$question_default = 3;
		$prefill_question_max = 3;
		$mixeval_avail = 'public';
		$total_mark = 5;
		$zero_mark = 'off';
	}
	?>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Mixeval']['id'])?'add':'edit') ?>" onSubmit="return validate()">
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td><?php echo $javascript->link('showhide')?>
	<input type="hidden" name="required" id="required" value="mixeval_name" />
      <?php echo empty($mixeval_id) ? null : $html->hidden('Mixeval/id'); ?>
      <?php echo empty($mixeval_id) ? $html->hidden('Mixeval/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Mixeval/updater_id', array('value'=>$rdAuth->id)); ?>
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
	    <?php echo $html->hidden('Mixeval/user_id', array('value'=>$rdAuth->id)); ?>
      <?php echo empty($params['data']['Mixeval']['id'])?'Add':'Edit' ?> Mixed Evaluation
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="209" id="mixeval_name_label">Mixed Evaluation Name:<font color="red">*</font></td>
    <td width="301"><?php echo $html->input('Mixeval/name', array('size'=>'30','class'=>'validate required TEXT_FORMAT mixeval_name_msg Invalid_Text._At_Least_One_Word_Is_Required.','value'=>$mixeval_name, 'id'=>'mixeval_name')) ?></td>
    <td width="353" id="mixeval_name_msg" class="error" />
  </tr>

  <tr class="tablecell2">
    <td>Number of Lickert Questions:</td>
    <td>

        <?php echo "<b>&nbsp;&nbsp;$question_default&nbsp;&nbsp;</b>";?>
        <!-- disable editing the number of questions for now -->
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Level of Scale:&nbsp;&nbsp;
		<?php echo $html->selectTag('Mixeval/scale_max', array('2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10'), $scale_default, array('style'=>'width:50px;','id'=>'LOM',
                                    ),'',false) ?>
		</td>
    <td>Number of Lickert Question Aspects (Max 25) </td>
  </tr>
  <tr class="tablecell2">
    <td>Number of Pre-fill Text Questions:</td>
    <td><?php echo $html->selectTag('Mixeval/prefill_question_max', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10'), $prefill_question_max, array('style'=>'width:50px;','id'=>'LOM'),'',false) ?></td>
    <td>Number of Pre-fill Text Question Aspects (Max 10) </td>
  </tr>
  <tr class="tablecell2">
    <td>Mixed Evaluation Availability:<font color="red">*</font></td>
    <td><?php echo $html->selectTag('Mixeval/availability', array('public'=>'public','private'=>'private'), $mixeval_avail, array('style'=>'width:100px;'),'',false) ?></td>
    <td>Public Allows Mixeval Sharing Amongst Instructors </td>
  </tr>
  <tr class="tablecell2">
    <td>Zero Mark: </td>
    <td><?php echo $html->checkbox('Mixeval/zero_mark', array('size'=>'50','class'=>'self_enroll', 'id'=>'zero_mark',  'checked'=>$zero_mark)) ?></td>
    <td>No Marks Given for Level of Scale of 1</td>
  </tr>
  <tr class="tablecell2">
  		<td colspan="3" align="center">
        <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
		<?php
		if(!empty($data)){
            if (empty($params['data']['Mixeval']['id'])) {
                echo $html->submit('Add Mixed Evaluation');
            } else {
                echo $html->submit('Edit (and Update Format)');
            }
		} else {
            echo $html->submit('Next', array('Name'=>'preview'));
		}
		?>
		</td>
    </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
<br>
	</td>
  </tr>
</table>

<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Mixed Evaluation Preview </td>
	<td><div align="right"><a href="#rpreview" onclick="showhide('rpreview'); toggle(this);"><?php echo empty($data) ? '[+]' : '[-]'; ?></a></div></td>
  </tr>
</table>
<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;"' : 'style="display: block; background: #FFF;"'; ?>>
<br>
<?php

$data = $this->controller->MixevalHelper->compileViewData($data);

echo $javascript->link('calculate_marks');?>

    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    	<tr class="tableheader" align="center">
			<td valign="top" colspan="<?php echo $scale_default?>" width="90%"  align='left'>Section One: &nbsp;Lickert Scales</td>
		<?php
		$descriptor_des = array('1'=>'Lowest','2'=>'','3'=>'Middle','4'=>'','5'=>'Highest');

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
		    $questionDescriptors = isset($mixevalQuestion['descriptors']) ?  $mixevalQuestion['descriptors'] : "";
   		  //$this->controller->Output->br2nl($mixevalQuestion);
		    $descriptor_des = array();

		    foreach ($questionDescriptors as $row) {
		      $desc = $row['MixevalsQuestionDesc'];
          $this->controller->Output->br2nl($desc);
		      $descriptor_des[$desc['scale_level']] = $desc['descriptor'];
		    }
		  }
		  if (empty($total_mark)) $total_mark = $question_default;

		  if (!empty($mixevalQuestion['title']))
		    $area_atrb = array('style'=>'width:95%;', 'value'=>$mixevalQuestion['title']);
		  else
		    $area_atrb = array('style'=>'width:95%;');

			echo "<tr class=\"tablecell\" align=\"center\">";
			echo "<td class=\"tableheader2\" valign=\"top\" colspan=\"".$scale_default."\"><table border=\"0\" width=\"95%\" cellpadding=\"2\"><tr><td width=\"15%\">Question $pos:</td><td width=\"85%\">"
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

				if (!empty($descriptor_des[$j]))
				  $area_atrb = array('style'=>'width:95%;', 'value'=>$descriptor_des[$j]);
				else
				  $area_atrb = array('style'=>'width:95%;');
				echo '<td><table border="0" width="95%" cellpadding="2"><tr><td align="left">Descriptor</td></tr><tr><td>'.$html->areaTag('Mixeval/criteria_comment_'
					 .$i.'_'.$j,'',1, $area_atrb)."</td></tr>";
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
		echo '<td colspan="'.($scale_default).'" align="right">Total Marks: </td>';
		echo '<td align="center"> <input type="text" name="total_marks" id="total_marks" class="input" value="'.$total_mark.'" size="5" readonly></td>';
		echo "</tr>";
		?>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
  </table>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader" align="center">
			<td align="left">Section Two: &nbsp;Comments (No weight on this section)</td>
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
  			    <tr><td width="15%">Question <?php echo $pos?>:</td>
  			        <td width="85%" align="left"> Question Prompt:
  			          <?php echo	$html->input('Mixeval/title'.$pos, array('style'=>'width:100%;', 'value'=>isset($mixevalQuestion['title'])? $mixevalQuestion['title'] : '')) ?> <br>
  			          <?php echo $html->hidden('Mixeval/question_type'.$pos, array('value'=>'T'));?>
  				      </td>
  				    </tr><tr>
                <td/>
                <td align="left">Mandatory?:
                  <?php
                   $checkRequired = 'checked';
                   $checkNo = '';
                   if (isset($mixevalQuestion['required']) && $mixevalQuestion['required']==0) {
                     $checkRequired = '';
                     $checkNo = 'checked';
                   }?>
          		    <input type="radio" name="data[Mixeval][text_require<?php echo $pos?>]" value="1" <?php echo $checkRequired?> > Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          		    <input type="radio" name="data[Mixeval][text_require<?php echo $pos?>]" value="0" <?php echo $checkNo ?> > No<br>
                </td>
  				  </tr>
  			  </table>
  		 </td>
		  </tr>
		  <tr class="tablecell" align="center">
			  <td>
			    <table border="0" width="95%" cellpadding="2">
			      <tr>
			        <td colspan="2" align="left">Instructions: (optional)</td>
            </tr>
            <tr><td colspan="2">
                  <?php
                  if (isset($mixevalQuestion['instructions']) && !empty($mixevalQuestion['instructions']))
                    $textArray = array('style'=>'width:100%;', 'value'=>$mixevalQuestion['instructions']);
                  else
                    $textArray = array('style'=>'width:100%;');
                  echo $html->areaTag('Mixeval/text_instruction'.$pos,'',2, $textArray) ?>
            </td></tr>
            <tr><td width="15%" align="left">Student's Answer Option: </td>
                <td width="85%" align="left">
                  <?php
                   $responseLickert = 'checked';
                   $responseText = '';
                   if (isset($mixevalQuestion['response_type']) && $mixevalQuestion['response_type']=='L') {
                   $responseLickert = '';
                   $responseText = 'checked';
                   }?>
          		    <input type="radio" name="data[Mixeval][response_type<?php echo $pos?>]" value="S" <?php echo $responseLickert?>  > Single line of text input box<br>
          		    <input type="radio" name="data[Mixeval][response_type<?php echo $pos?>]" value="L" <?php echo $responseText?> > Multiple lines of text input box<br>
                </td></tr>
			 </table></td>
			</tr>
<?php			$pos++;
		}
		?>
  <tr>
  		<td colspan="3" align="center">
<?php echo $html->hidden('Mixeval/total_question', array('value'=>$pos));?>
		<input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
  		  <?php if (empty($params['data']['Mixeval']['id'])) {
  		      echo $html->submit('Add Mixed Evaluation');
  		    } else {
  		      echo $html->submit('Edit Mixed Evaluation');
  		    }?>

		</td>
    </tr>
  </table>
  </div>
</form>
