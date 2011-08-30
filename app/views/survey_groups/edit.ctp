<?php echo $this->Form->create(false, 
                               array('id' => 'frm',
                                     'url' => array('action' => 'changegroupset'),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td width="200px">',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>
<?php echo $this->Form->input('survey_id', array('type' => 'hidden', 'value' => $survey_id))?>
<?php echo $this->Form->input('group_set_id', array('type' => 'hidden', 'value' => $group_set_id))?>
<?php echo $html->image('layout/corner_bot_left.gif',array('style'=>'display:none;','alt'=>'corner_bot_left','onload'=>'Element.show(\'loading\')'))?>
<table width="100%" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="85%" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center"><?php __('Edit Survey Group Set')?></td>
          </tr>
          <tr class="tablecell2">
            <td>
            <table width="100%" style="border-collapse:collapse;border-top:hidden;">
      			<?php if (!empty($data)):?> 
      			  <?php $responses = isset($responses['Responses']) ? $responses['Responses']:null;
      			  $count=0;?>
        			<?php foreach ($data['SurveyGroup'] as $i => $survey_group):?>
        			  <tr class="tablecell2" style="border-top:solid #cccccc;">
                  <td class="header_link"><b><?php echo $this->Html->link('team '.($i+1), 'evaluations/viewSurveyGroupEvaluationResults/'.$survey_id.';'.$survey_group['id'], array('onclick' => "wopen(this.href, \'popup\', 650, 500); return false;"))?></b></td>
        			  <?php if (isset($score[$i])):?>
                  <td style="color:#339977;"><i><b><?php __('match score')?>: <?php echo $score[$i]['percent']?></b></i></td>
        			  <?php else:?>
        			    <td></td>
                <?php endif;?>
        			    <td></td>
                  <td align="center" style="padding:0;">
                  <?php if ($i == 0):?> 
                    <?php echo $this->Form->select('question', 
                                                   $questions,
                                                   $selected_question,
                                                   array('onChange' => 'window.location=\''.$this->Html->url('edit').'/'.$group_set_id.'/\'+this.options[this.selectedIndex].value;',
                                                         'empty' => __('Show reponses for question:', true)));?>
                  <?php endif;?>
                  </td>
        			  </tr>
        			  <?php foreach ($survey_group['Member'] as $j => $member):?>
        			    <tr class="tablecell2">
        			      <td width="80">
                      <?php if (!empty($input[$member['id']])):?>
           			      <?php echo $this->Html->link($member['student_no'], 
                                                   'evaluations/viewEvaluationResults/'.$event_id.'/'.$member['id'], 
                                                   array('onClick' => "wopen(this.href, \'popup\', 650, 500); return false;"))?></td>
                      <?php else:?>
                      <?php echo $member['student_no']?>
                      <?php endif;?>
                    </td>

                    <td width="30%"><?php echo $member['full_name']?></td>

                    <td width="100">
        			    <?php 
                  $options = array();
                  for ($k=0; $k < count($data['SurveyGroup']); $k++) {
        			      if ($k == $i) continue;
        			      $options[$member['id'].'_'.$survey_group['id'].'_'.$data['SurveyGroup'][$k]['id']] = $k+1;
        			    }?>

                  <?php echo $this->Form->select('move][', $options, null, // name hacking for multiple dimension array
                                                 array('empty' => __('Move to ...', true)))?>

                  <?php
                  //student responses
                  $response = isset($inputs[$member['id']]) ? $inputs[$member['id']]:'';?>
                  <?php if (!empty($response)&&is_array($response)):?>
                    <td>
                    <?php if (in_array($response['type'],array('M','C'))) {
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
                    ?>

                    </td>
                  <?php endif;?>
        			    </tr>
        			  <?php endforeach;?>
        		<?php endforeach;?>	
          <?php endif;?>
      			</table>
      			</td>
          </tr>
          <tr class="tablecell2">
            <td><div align="center"><?php echo $this->Form->submit(__('Save Groups', true), array('div' => false)) ?>
			<input type=button name="Cancel" value="<?php __('Cancel')?>" onClick="javascript:history.go(-1);" />
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
</table>
<?php echo $this->Form->end()?>
