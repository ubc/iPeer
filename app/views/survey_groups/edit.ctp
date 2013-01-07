<?php echo $this->Form->create(
    false,
    array(
        'id' => 'frm',
        'url' => array('action' => 'changegroupset'),
        'inputDefaults' => array('div' => false,
        'before' => '<td width="200px">',
        'after' => '</td>',
        'between' => '</td><td>')))?>
<?php echo $this->Form->input('survey_id', array('type' => 'hidden', 'value' => $survey_id))?>
<?php echo $this->Form->input('group_set_id', array('type' => 'hidden', 'value' => $group_set_id))?>
<table class="standardtable">
    <tr><th><?php __('Edit Survey Group Set')?></th></tr>
    <tr>
        <td>
            <table width="100%" style="border-collapse:collapse;border-top:hidden;">
            <?php if (!empty($data)):?>
            <?php $responses = isset($responses['Responses']) ? $responses['Responses']:null;
                $count=0;?>
                <?php foreach ($data['SurveyGroup'] as $i => $survey_group):?>
                <tr style="border-top:solid #cccccc;">
                    <td class="header_link"><?php echo 'Team '.($i+1)?></td>
                    <td style="color:#339977;">
                        <i><?php echo isset($score[$i]['percent']) ? __('match score', true).': '.$score[$i]['percent'] : ''?></i>
                    </td>
                    <td></td>
                    <td align="center" style="padding:0;">
                    <?php if ($i == 0):?>
                    <?php echo $this->Form->select('question',
                        $questions,
                        $selected_question,
                        array('onChange' => 'window.location=\''.$this->Html->url('edit').'/'.$group_set_id.'/\'+this.options[this.selectedIndex].value;',
                            'empty' => __('Show responses for question:', true)));?>
                    <?php endif;?>
                    </td>
                </tr>

                <?php foreach ($survey_group['Member'] as $j => $member):?>
                <tr>
                    <td width="80">
                    <?php if (!empty($input[$member['id']])):?>
                        <?php echo $this->Html->link($member['student_no'],
                            'evaluations/viewEvaluationResults/'.$event_id.'/'.$member['id'])?></td>
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
                        }
                    ?>

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
              echo $response['response_text'].' ';
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
  <tr>
    <td><div align="center"><?php echo $this->Form->submit(__('Save Groups', true), array('div' => false)) ?>
    <input type=button name="Cancel" value="<?php __('Cancel')?>" onClick="javascript:history.go(-1);" />
    </div></td>
  </tr>
</table>
<?php echo $this->Form->end()?>
