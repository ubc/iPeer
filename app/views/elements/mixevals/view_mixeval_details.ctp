<!-- elements::ajax_rubric_preview start -->
<?php
$questions = isset($data['Question'])? $data['Question'] : array();
isset($user)? $userId = $user['id'] : $userId = '';
isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;
$pos = 1;
//for loop to display the criteria rows
$details = Set::combine($evaluation['EvaluationDetail'], '{n}.EvaluationMixevalDetail.question_number', '{n}.EvaluationMixevalDetail');
?>
<table class="standardtable" style="margin: 1em 0;">
    <tr align="center">
        <th align="left"><?php __('Section One: Lickert Scales')?></th>
        <?php echo !$evaluate ? '<td width="10%">'.__('Scale Weight', true).'</td>':''?>
    </tr>
    <?php foreach ($questions as $mixevalQuestion) {
    //Get and set Mixeval Question
    if ($mixevalQuestion !=null && $mixevalQuestion['MixevalsQuestion']["question_type"]=="S") {
        $questionDescriptors = $mixevalQuestion['Description'];
    }
    if ($mixevalQuestion['MixevalsQuestion']["question_type"]=="S") {

        echo '<tr><td style="text-align: left; padding-left: 1em;">';
        if (isset($evaluate)) {
            echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$mixevalQuestion['MixevalsQuestion']['question_num'].'"';
            echo 'value="'.(isset($details[$mixevalQuestion['MixevalsQuestion']['question_num']]['selected_lom']) ?
                $details[$mixevalQuestion['MixevalsQuestion']['question_num']]['selected_lom'] :
                '1') . '">';
        } else {
            echo '<input type="hidden" name="selected_lom_'.$userId.'_'.$mixevalQuestion['MixevalsQuestion']['question_num'].'" value="1" size="4" >';
        }

        echo $pos. ': &nbsp &nbsp '. $mixevalQuestion['MixevalsQuestion']['title'];
        echo '</td></tr>';
        echo $form->hidden('Mixeval.question_type'.$mixevalQuestion['MixevalsQuestion']['question_num'], array('value'=>'S'));

        //for loop to display the criteria comment cells for each LOM
        isset($mixevalQuestion['MixevalsQuestion']['multiplier']) ? $multiplier = $mixevalQuestion['MixevalsQuestion']['multiplier'] : $multiplier = 1;
        echo '<tr align="left"><td >';
        echo '<div style="margin: 1em">';
        for ($j=1; $j<=count($mixevalQuestion['Description']); $j++) {
            //isset($mixevalQuestion['multiplier']) ? $multiplier = $mixevalQuestion['multiplier'] : $multiplier = 1;

            if ( $zero_mark == "on" ) {
                $mark_value = round(($multiplier/(count($mixevalQuestion['Description'])-1)*($j-1)), 2);
            } else {
                $mark_value = round(($multiplier/count($mixevalQuestion['Description'])*$j), 2);
            }

            echo '<table border="0" width="20%" align="center" style ="display: inline-table;"><tr><td align="center" >';
            echo $mixevalQuestion['Description'][$j-1]['descriptor'].'&nbsp;';
            echo "</td></tr>";
            echo '<tr><td align="center">';
            echo '<input name="'.$userId.'criteria_points_'.$mixevalQuestion['MixevalsQuestion']['question_num'].'" type="radio" value="'.$mark_value.'"';
            echo 'onclick="document.evalForm.selected_lom_'.$userId.'_'.$mixevalQuestion['MixevalsQuestion']['question_num'].".value=".$j.'" ';
            if (isset($evaluation)) {
                if (isset($details[$mixevalQuestion['MixevalsQuestion']['question_num']]['selected_lom']) &&
                    $details[$mixevalQuestion['MixevalsQuestion']['question_num']]['selected_lom'] == $j) {
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
            echo "</table>";
            //     echo '</div>';
        }
        echo '<tr><td>';
        if (!$evaluate) {
            echo '<td>'.$multiplier.'</td>';
        }

        echo "</tr>";
        $pos++;
    }
    }
    if (!$evaluate) {
      echo '<tr>';
      echo '<td colspan="'.(count($question['Description'])+1).'" align="right">Total Marks: </td>';
      echo '<td align="center">'.$total_mark.'</td>';
      echo '</tr>';
    }
    ?>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
  </table>
<table class="standardtable" style="margin: 1em 0;">
    <tr>
        <th align="left"><?php __('Section Two')?>: &nbsp;<?php __('Comments (No Weight on this Section)')?></th>
    </tr>
    <?php foreach ($questions as $mixevalQuestion) {
        if ($mixevalQuestion['MixevalsQuestion']["question_type"] == "S") {
            continue;
        }
    ?>
      <tr align="center">
        <td valign="top" colspan="<?php echo $scale_default?>">
          <table border="0" width="95%" cellpadding="2">
            <tr><td width="5%"><?php echo $pos?>:</td>
                <td width="95%" style="text-align: left;"> Question Prompt:
                  <?php echo  $mixevalQuestion['MixevalsQuestion']['title'];
                   if ($evaluate) {
                      if (isset($mixevalQuestion['MixevalsQuestion']['required']) && $mixevalQuestion['MixevalsQuestion']['required']==1) {
                        echo '<font color="red"> * </font>';
                      }
                   } ?> <br>
                  <?php echo $form->hidden('Mixeval.question_type'.$mixevalQuestion['MixevalsQuestion']['question_num'], array('value'=>'T')); ?>
                </td>
              </tr>
              <?php if (!$evaluate) { ?>
              <tr>
                <td/>
                <td><?php __('Mandatory?')?>:
                  <?php $checkRequired = __('YES', true);
                   if (isset($mixevalQuestion['MixevalsQuestion']['required']) && $mixevalQuestion['MixevalsQuestion']['required']==0) {
                     $checkRequired = __('NO', true);
                   }
                   echo $checkRequired;
                   ?>
                </td>
            </tr>
          <?php }?>
          </table>
       </td>
      </tr>
      <tr align="center">
        <td cellpadding="2">
          <table border="0" width="95%" cellpadding="2">
             <tr><td colspan="2">
                  <?php echo $mixevalQuestion['MixevalsQuestion']['instructions'] ?>
            </td></tr>
            <?php if ($evaluate) :?>
            <tr><td colspan="2" style="text-align: left; padding-left: 2em;">
                  <?php if (isset($mixevalQuestion['MixevalsQuestion']['response_type']) && $mixevalQuestion['MixevalsQuestion']['response_type']=='L') {?>
                     <?php $text = isset($details[$mixevalQuestion['MixevalsQuestion']['question_num']]['question_comment']) ?
                        $details[$mixevalQuestion['MixevalsQuestion']['question_num']]['question_comment']:'';?>
                     <textarea name="response_text_<?php echo $userId?>_<?php echo $mixevalQuestion['MixevalsQuestion']['question_num']?>" cols="80" rows="10"><?php echo $text?></textarea>
                     <br /><?php __('Maximum 65535 characters.')?>
                  <?php } else { ?>
                     <input type="text" name="response_text_<?php echo $userId?>_<?php echo $mixevalQuestion['MixevalsQuestion']['question_num']?>" size="92" value="<?php
                      echo htmlentities($details[$mixevalQuestion['MixevalsQuestion']['question_num']]['question_comment'])?>">
                  <?php }?>
            </td></tr>
            <?php else: ?>
            <tr><td width="15%"><?php __('Respond Type?')?>: </td>
                <td width="85%" align="left">
                  <?php
                   $responseType = 'Single Text Input';
                   if (isset($mixevalQuestion['MixevalsQuestion']['response_type']) && $mixevalQuestion['MixevalsQuestion']['response_type']=='L') {
                       $responseType = __('Long Answer Text Input', true);
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
<?php echo $form->hidden('Mixeval.total_question', array('value'=>($pos-1)));?>
<?php if (!$evaluate) :?>
    <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
<?php endif; ?>
  </td>
  </tr>
  </table>
<!-- elements::ajax_rubric_preview end -->
