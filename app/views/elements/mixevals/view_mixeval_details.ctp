<!-- elements::ajax_rubric_preview start -->
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
      <tr class="tableheader" align="center">
      <td valign="top" width="75%" align='left'>Section One: &nbsp;Lickert Scales</td>
    <?php
    $descriptor_des = array('1'=>'Lowest','2'=>'','3'=>'Middle','4'=>'','5'=>'Highest');

    isset($data['questions'])? $questions = $data['questions'] : $questions = null;
    isset($user)? $userId = $user['id'] : $userId = '';
 		isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;

    echo "<td width=\"50%\" colspan=\"".$scale_default."\">Scale</td>";

    //for loop to display the top header row with LOM comments
    if (!$evaluate) {
      echo "<td width=\"10%\">Scale Weight</td>";
    }
    echo "</tr>";
    $pos = 1;
    //for loop to display the criteria rows
    for($i=1; $i<=$question_default; $i++){
      //Get and set Mixeval Question
      isset($questions[$pos])? $mixevalQuestion = $questions[$pos] : $mixevalQuestion = null;
      if ($mixevalQuestion !=null) {
        $questionDescriptors = $mixevalQuestion['instructions'];
        $descriptor_des = array();
		
        foreach ($questionDescriptors as $row) {
          $desc = $row['MixevalsQuestionDesc'];
          $descriptor_des[$desc['scale_level']] = $desc['descriptor'];
        }
        
        
      }

      echo '<tr class="tablecell" align="center">';
      echo '<td class="tableheader2" valign="top" width="50%">';
		  if (isset($evaluate)) {
		     echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$i.'"';
		     echo 'value="'.(isset($evaluation['EvaluationDetail'][$i-1]['EvaluationMixevalDetail']['selected_lom']) ?
                    $evaluation['EvaluationDetail'][$i-1]['EvaluationMixevalDetail']['selected_lom'] :
                    '1') . '">';
		  } else {
        echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$i.'" value="1" size="4" >';
      }

      echo '<table align="left" border="0" width="70%" cellpadding="2">';
      echo '<tr><td width="5%">'.$pos.':</td><td width="30%">';
      echo $mixevalQuestion['title']."<br></td></tr></table>";
      echo $form->hidden('Mixeval/question_type'.$pos, array('value'=>'S'))."</td>";
        //for loop to display the criteria comment cells for each LOM
        isset($mixevalQuestion['multiplier']) ? $multiplier = $mixevalQuestion['multiplier'] : $multiplier = 1;
        for($j=1; $j<=$scale_default; $j++){
        	//isset($mixevalQuestion['multiplier']) ? $multiplier = $mixevalQuestion['multiplier'] : $multiplier = 1;

            if( $zero_mark == "on" ) {
                $mark_value = round( ($multiplier/($scale_default-1)*($j-1)) , 2);
            } else {
                $mark_value = round( ($multiplier/$scale_default*$j) , 2);
            }
            echo '<td width="'.round(50/$scale_default).'%" valign="bottom">';
            echo '<table border="0" width="100%" cellpadding="2"><tr align="center"><td width="100">';
            echo $descriptor_des[$j].'&nbsp;';
            echo "</td></tr>";
            echo '<tr><td align="center" width="100">';
            echo '<input name="'.$userId.'criteria_points_'.$i.'" type="radio" value="'.$mark_value.'"';
            echo 'onclick="document.evalForm.selected_lom_'.$userId.'_'.$i.".value=".$j.'" ';

            if (isset($evaluation)) {
                if (isset($evaluation['EvaluationDetail'][$i-1]['EvaluationMixevalDetail']['selected_lom']) &&
                    $evaluation['EvaluationDetail'][$i-1]['EvaluationMixevalDetail']['selected_lom'] == $j) {
                        echo " checked ";
                }
            } else {
                if ($j==1) {
                    echo " checked ";
                }
            }
            echo "/></td></tr>";
            if (!$evaluate) {
                echo '<tr><td align="center" width="20%">Mark: '.$mark_value.'</td></tr>';
            }
            echo "</table></td>";
        }

      if (!$evaluate) {
        echo '<td>'.$multiplier.'</td>';
      }
      //Scale weight
//      if (!$evaluate) {
//       echo "<td/>";
//      }
      echo "</tr>";

      $pos++;
    }
    if (!$evaluate) {
      echo '<tr class="tableheader2">';
      echo '<td colspan="'.($scale_default+1).'" align="right">Total Marks: </td>';
      echo '<td align="center">'.$total_mark.'</td>';
      echo '</tr>';
    }
    ?>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
  </table>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader" align="center">
      <td align="left">Section Two: &nbsp;Comments (No Weight on this Section)</td>
      <?php $descriptor_des = array('1'=>'Lowest','2'=>'','3'=>'Middle','4'=>'','5'=>'Highest'); ?>
    </tr>
  <?php //for loop to display the criteria rows
    for($i=1; $i<=$prefill_question_max; $i++){
            //Get Mixeval Question
      isset($questions[$pos])? $mixevalQuestion = $questions[$pos] : $mixevalQuestion = null;

  ?>
      <tr class="tablecell" align="center">
        <td class="tableheader2" valign="top" colspan="<?php echo $scale_default?>">
          <table border="0" width="95%" cellpadding="2">
            <tr><td width="5%"><?php echo $pos?>:</td>
                <td width="95%" align="left"> Question Prompt:
                  <?php echo  $mixevalQuestion['title'];
                   if ($evaluate) {
                      if (isset($mixevalQuestion['required']) && $mixevalQuestion['required']==1) {
                        echo '<font color="red"> * </font>';
                      }
                   } ?> <br>
                  <?php echo $form->hidden('Mixeval/question_type'.$pos, array('value'=>'T')); ?>
                </td>
              </tr>
              <?php if (!$evaluate) { ?>
              <tr>
                <td/>
                <td>Mandatory?:
                  <?php $checkRequired = 'YES';
                   if (isset($mixevalQuestion['required']) && $mixevalQuestion['required']==0) {
                     $checkRequired = 'NO';
                   }
                   echo $checkRequired;
                   ?>
                </td>
            </tr>
          <?php }?>
          </table>
       </td>
      </tr>
      <tr class="tablecell" align="center">
        <td>
          <table border="0" width="95%" cellpadding="2">
            <tr>
              <td colspan="2">Instructions:</td>
            </tr>
            <tr><td colspan="2">
                  <?php echo $mixevalQuestion['instructions'] ?>
            </td></tr>
            <?php if ($evaluate) :?>
            <tr><td colspan="2" align='left'>
                  <?php
                   if (isset($mixevalQuestion['response_type']) && $mixevalQuestion['response_type']=='L') {?>
                     <?php
                     $text = isset($evaluation['EvaluationDetail'][$pos-1]['EvaluationMixevalDetail']['question_comment'])? $evaluation['EvaluationDetail'][$pos-1]['EvaluationMixevalDetail']['question_comment']:'';
                     $Output->br2nl($text);
                     //echo $text;
                      ?>
                     <textarea name="response_text_<?php echo $userId?>_<?php echo $mixevalQuestion['question_num']?>" cols="80" rows="15"><?php echo $text?></textarea>
                     <br />Maximum 65535 characters.
                   <?php } else { ?>
                     <input type="text" name="response_text_<?php echo $userId?>_<?php echo $mixevalQuestion['question_num']?>" size="80" value="<?php echo htmlentities($evaluation['EvaluationDetail'][$pos-1]['EvaluationMixevalDetail']['question_comment'])?>"/>
                   <?php }?>

                </td></tr>
            <?php else: ?>
            <tr><td width="15%">Respond Type?: </td>
                <td width="85%" align="left">
                  <?php
                   $responseType = 'Single Text Input';
                   if (isset($mixevalQuestion['response_type']) && $mixevalQuestion['response_type']=='L') {
                       $responseType = 'Long Answer Text Input';
                   }
                   echo $responseType;?>
                </td></tr>
            <?php endif; ?>
       </table></td>
      </tr>
<?php     $pos++;
    }
    ?>
  <tr>
    <td colspan="3" align="center">
<?php echo $form->hidden('Mixeval/total_question', array('value'=>$pos));?>
<?php if (!$evaluate) :?>
    <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
<?php endif; ?>
  </td>
  </tr>
  </table>
<!-- elements::ajax_rubric_preview end -->
