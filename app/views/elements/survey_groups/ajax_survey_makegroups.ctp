<!-- elements::ajax_survey_makegroups end -->
<div id="ajax_update">
<?php echo $this->Form->create(false,
    array('id' => 'frm',
          'url' => array('action' => 'maketmgroups'),
          'inputDefaults' => array('div' => false,
                                   'before' => '<td width="200px">',
                                   'after' => '</td>',
                                   'between' => '</td><td>')))?>

  <?php echo $this->Form->input('event_id', array('type' => 'hidden', 'value' => $event['Event']['id']))?>
  <table class="standardtable">
    <tr><th colspan="2"><?php __('Team Making - Step One')?></th></tr>
    <tr>
        <td colspan="2">
            <?php echo $event['Course']['student_count'].__(' students were specified for this survey, ', true).$event['Event']['response_count'].__(' students responded', true); ?>
        </td>
    </tr>

    <tr>
        <td colspan="2"><?php __('Group Configuration')?>:
        <?php echo $this->Form->select('group_config', $group_list, null)?>
      </td>
    </tr>

    <?php if (empty($questions)): ?>
    <tr><td colspan="2">
       <?php echo __("There must be at least one question.", true); ?>
       </td>
    </tr>
    <?php endif;?>

    <?php foreach ($questions as $i => $q):?>
    <tr>
        <td width="50%" style="text-align: left">Question<?php echo $i+1;?>: <?php echo $q['prompt'];?></td>
        <td>
            <table>
            <tr>
                <td align="center"><?php echo $html->image('survey/correlate.gif',array('alt'=>'correlate')); ?></td>
                <?php for($i = -5; $i <= 5; $i++):?>
                    <td class="weight<?php echo $i+6?>"><input type="radio" name="weight[<?php echo $q['id'];?>]" value="<?php echo $i?>" <?php echo $i==0 ? 'checked' : ''?>></td>
                <?php endfor; ?>
                <td align="center"><?php echo $html->image('survey/differentiate.gif',array('alt'=>'differentiate')); ?></td>
            </tr>
            <tr><td><?php __('Gather<br />Similar')?></td><td colspan="11" align="center"><?php __('Ignore')?></td>
            <td align="center"><?php __('Gather<br />Dissimilar')?></td></tr>
            </table>
        </td>
    </tr>
   <?php endforeach;?>
    <tr>
      <td colspan="2">
        <div align="center">
            <?php __('Note: It may take up to 10mins to create groups.')?><br />
           <?php echo $this->Form->submit('Next',array('onClick'=>'Element.show(\'loading\');',
                                                       'disabled'=> empty($questions),
                                                       'div' => false));?>
           <input type="button" name="Cancel" value="Cancel" onClick="parent.location='javascript:history.go(-1)'">
         </div>
      </td>
    </tr>
  </table>
</form>
</div>
<!-- elements::ajax_survey_makegroups end -->
