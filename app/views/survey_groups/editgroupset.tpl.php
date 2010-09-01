<form name="frm" id="frm" method="POST" action="<?php echo $html->url('changegroupset') ?>">
<input name="survey_id" id="survey_id" type="hidden" value="<?php echo $survey_id?>">
<input name="group_set_id" id="group_set_id" type="hidden" value="<?php echo $group_set_id?>">
<?php echo $html->image('layout/corner_bot_left.gif',array('style'=>'display:none;','alt'=>'corner_bot_left','onload'=>'Element.show(\'loading\')'))?>
<table width="100%" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="85%" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center">Edit Survey Group Set</td>
          </tr>
          <tr class="tablecell2">
            <td>
            <table width="100%" style="border-collapse:collapse;border-top:hidden;">
      			<?php
      			if (!empty($data)) {
      			  $responses = isset($responses['Responses']) ? $responses['Responses']:null;
      			  $count=0;
        			for ($i=0; $i < count($data); $i++) {
        			  echo '<tr class="tablecell2" style="border-top:solid #cccccc;"><td class="header_link"><b><a  href="'.$this->webroot.$this->themeWeb.'evaluations/viewSurveyGroupEvaluationResults/'.$survey_id.';'.$data[$i]['id'].'" onclick="wopen(this.href, \'popup\', 650, 500); return false;">team '.($i+1).'</a></b></td>';
        			  if (isset($score[$i]))
        			    echo '<td style="color:#339977;"><i><b>match score: '.$score[$i]['percent'].'</b></i></td>';
        			  else
        			    echo '<td></td>';
        			  echo '<td></td>';
        			  if ($i == 0) {
        			    //question select drop down
                  echo '<td align="center" style="padding:0;"><select name="question" onChange="window.location=\''.$html->url('editgroupset').'/'.$group_set_id.';\'+this.options[this.selectedIndex].value;">';
                  echo '<option value="-1">Show reponses for question:</option>';
                  for ($j=1; $j <= count($questions); $j++) {
                    $selected = '';
                    if (!empty($question_id)&&$questions[$j]['Question']['id']==$question_id)
                      $selected = 'selected';
                    echo '<option value="'.$questions[$j]['Question']['id'].'" '.$selected.'>'.$j.'. '.$questions[$j]['Question']['prompt'].'</option>';
                  }
          			  echo '</select></td>';
        			  }
        			  echo '</tr>'."\n";
        			  for ($j=0; $j < count($data[$i]['members']); $j++) {
        			    $user_id = $data[$i]['members'][$j]['id'];
        			    $user_name = $data[$i]['members'][$j]['name'];
        			    $student_id = $data[$i]['members'][$j]['student_id'];
        			    echo '<tr class="tablecell2">';

        			    //student id column
        			    if (!empty($data[$i]['members'][$j]['response']))
           			    echo '<td width="80"><a href="'.$this->webroot.$this->themeWeb.'evaluations/viewEvaluationResults/'.$event_id.'/'.$user_id.'" onclick="wopen(this.href, \'popup\', 650, 500); return false;">'.$student_id.'</a></td>';
           			  else
           			    echo '<td width="80">'.$student_id.'</td>';
           			  //student name column
           			  echo '<td width="30%">'.$user_name.'</td>';
        			    //make drop down 'move member' box
        			    echo '<td width="100"><select name="move['.$count.']">';
        			    $count++;
        			    echo '<option value="-1">Move to...</option>';
        			    for ($k=0; $k < count($data); $k++) {
        			      if ($k == $i) continue;
        			      echo '<option value="'.$user_id.'_'.($i+1).'_'.($k+1).'">'.($k+1).'</option>';
        			    }
                  echo '</select></td>';

                  //student responses
                  $response = isset($data[$i]['members'][$j]['response']) ? $data[$i]['members'][$j]['response']:'';
                  if (!empty($response)&&is_array($response)) {
                    echo '<td>';
                    if (in_array($response['type'],array('M','C'))) {
                      if ($response['type']=='C') {
                        for ($k=0; $k < count($responses); $k++) {
              						$checked = '';
              					  if (in_array($responses['response_'.$k]['id'],$response['id'])) $checked = 'checked';
            							echo "<input type=\"checkbox\" name=\"answer_".$k."\" ".$checked." disabled /> ".$responses['response_'.$k]['response'].'   ';
                        }
                      } else {
                        for ($k=0; $k < count($responses); $k++) {
              						$checked = '';
              					  if (in_array($responses['response_'.$k]['id'],$response['id'])) $checked = 'checked';
            							echo "<input type=\"radio\" ".$checked." disabled /> ".$responses['response_'.$k]['response'].'   ';
                        }
                      }
                    } else {
                      echo $response[0]['response_text'].' ';
                    }


                    echo '</td>';
                  }
        			    echo '</tr>'."\n";
        			  }
        			}
      			}
      			?>
      			</table>
      			</td>
          </tr>
          <tr class="tablecell2">
            <td><div align="center"><?php echo $html->submit('Save Groups') ?>
			<input type=button name="Cancel" value="Cancel" onClick="javascript:history.go(-1);" />
			</div></td>
          </tr>
      </table>
        <table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right','onload'=>'Element.hide(\'loading\')'))?></td>
          </tr>
        </table></td>
  </tr>
</table></form>
