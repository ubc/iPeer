<?php echo $this->Form->create(false,
                               array('id' => 'frm',
                                     'url' => array('action' => 'savegroups'),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '',
                                                              'after' => '',
                                                              'between' => '')))?>
<?php echo $this->Form->input('filename', array('type' => 'hidden', 'value' => (empty($filename) ? '' : $filename)))?>
<?php echo $this->Form->input('event_id', array('type' => 'hidden', 'value' => (empty($event_id) ? '' : $event_id)))?>

<div>
    <ul class="instructions">
        <li><?php __('Click on any user name to view their answers to this survey.')?></li>
        <li><?php __('To adjust weightings and create a new set of teams, just go ')?><a href="javascript:history.go(-1);"><?php __('back')?></a>.</li>
        <li><?php __('To edit these teams, first save them below.')?></li>
        <li><?php __('Note:')?></b> <?php __("Higher 'Match Score' is better.")?></li>
    </ul>
</div>

<table class="standardtable" style="border-collapse:collapse;border-top:hidden;">
<tr>
    <th>Team Name</th>
    <?php for ($j=0; $j < (count($scores[0])-2); $j++):?>
    <th width="40"><?php echo $this->Html->link('Q'.($j+1), '../evaluations/viewSurveySummary/'.$event_id)?></th>
    <?php endfor;?>
    <th><?php __('Match Score')?></th>
    <th colspan="400"><?php __('Team Members')?></th>
</tr>

<?php for ($i=0; $i < count($teams); $i++): $team = $teams[$i];?>
<tr style="border-top:solid #cccccc;">
    <td width="100">Team #<?php echo ($i+1);?></td>
    <?php for ($j=0; $j < (count($scores[0])-2); $j++):?>
        <?php echo isset($scores[$i]['q_'.$j])?'<td>'.$scores[$i]['q_'.$j].'</td>':'<td>-</td>';?>
    <?php endfor;?>
    <td width="50"><?php echo (isset($scores[$i]['percent'])? $scores[$i]['percent']:'-')?></td>
    <td style="text-align: left;">
    <?php foreach ($team as $member):?>
        <?php echo $this->Html->link($member['full_name'],
            '../evaluations/viewEvaluationResults/'.$event_id.'/'.$member['id'],
            array('target' => '_blank')
        )?>, &nbsp;
    <?php endforeach;?>
    </td>
</tr>
<?php endfor;?>

</table>

<div>
    <?php echo $this->Form->input('team_set_name', array('label' => __('Group Set Name: ', true)))?>
</div>

<div align="center">
    <?php echo $this->Form->submit(__('Save Groups', true), array('div' => false)) ?>
    <input type="button" name="Cancel" value="Cancel" onClick="javascript:history.go(-1);" />
</div>
<?php echo $this->Form->end(); ?>
