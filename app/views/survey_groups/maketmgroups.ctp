<?php echo $this->Form->create(false, 
                               array('id' => 'frm',
                                     'url' => array('action' => 'savegroups'),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '',
                                                              'after' => '',
                                                              'between' => '')))?>
<?php echo $this->Form->input('filename', array('type' => 'hidden', 'value' => (empty($filename) ? '' : $filename)))?>
<?php echo $this->Form->input('survey_id', array('type' => 'hidden', 'value' => (empty($survey_id) ? '' : $survey_id)))?>

<table width="100%" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center"><?php __('Teams Summary')?></td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('Click on any user name to view their answers to this survey. To adjust weightings and create a new set of teams, just go ')?><a href="javascript:history.go(-1);"><?php __('back')?></a>.<br/><br/><?php __('To edit these teams, first save them below.')?><br/><br/><b><?php __('Note:')?></b> <?php __("Higher 'Match Score' is better.")?> <br/><br/>

            <table style="border-collapse:collapse;border-top:hidden;">
            <tr>
              <th>Team Name</th>
      			<?php for ($j=0; $j < (count($scores[0])-2); $j++):?>
    			    <th width="40"><?php echo $this->Html->link('Q'.($j+1), 'evaluations/viewSurveySummary/'.$survey_id, array('onClick' => "wopen(this.href, \'popup\', 650, 500); return false;"))?></th>
            <?php endfor;?>
      			  <th><?php __('Match Score')?></th>
              <th colspan="400"><?php __('Team Members')?></th>
            </tr>

            <?php for ($i=0; $i < count($teams); $i++):?>
      			  <?php $team = $teams[$i];?>
      			  <tr class="tablecell" style="border-top:solid #cccccc;">
      			    <td width="100">Team #<?php echo ($i+1);?></td>
                <?php for ($j=0; $j < (count($scores[0])-2); $j++):?>
      			      <?php echo isset($scores[$i]['q_'.$j])?'<td>'.$scores[$i]['q_'.$j].'</td>':'<td>-</td>';?>
                <?php endfor;?>
                <td width="50"><?php echo (isset($scores[$i]['percent'])? $scores[$i]['percent']:'-')?></td>
                <td>
                <?php for ($j=0; $j < count($team);$j++):?>
                <?php echo $this->Html->link($team['member_'.$j]['student_no'],
                                  'evaluations/viewEvaluationResults/'.$event_id.'/'.$team['member_'.$j]['id'],
                                  array('onClick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>
                <?php endfor;?>
                </td>
      			  </tr>
      			<?php endfor;?>
      			</table>
      			</td>
          </tr>

          <tr class="tablecell2"><td><?php echo $this->Form->input('team_set_name', array('label' => __('Group Set Name: ', true)))?></td></tr>

          <tr class="tablecell2">
            <td><div align="center">
              <?php echo $this->Form->submit(__('Save Groups', true), array('div' => false)) ?> 
              <input type="button" name="Cancel" value="Cancel" onClick="javascript:history.go(-1);" />
	      		</div></td>
          </tr>
      </table>

      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
    </td>
  </tr>
</table></form>
