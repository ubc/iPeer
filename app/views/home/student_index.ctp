<?php
$surveyEvents = Set::extract($upcoming, '/Event[event_template_type_id=3]/..');
$evalEvents = Set::extract($upcoming, '/Event[event_template_type_id!=3]/..');
?>

<h3>Peer Evaluations Due</h3>
<div>
    <table class='standardtable'>
    <tr>
       <th width='30%'>Event</th>
       <th width='10%'>Group</th>
       <th width='20%'>Course</th>
       <th width='20%'>Due Date</th>
       <th width='20%'>Due In/Late By (red)</th>
    </tr>

<?php foreach ($evalEvents as $event): ?>
<tr>
    <td><?php echo $html->link($event['Event']['title'], '/evaluations/makeEvaluation/'.$event['Event']['id'].'/'.$event['Group']['id'])?></td>
    <td><?php echo $event['Group']['group_name']?></td>
    <td><?php echo $event['Course']['course']?></td>
    <td><?php echo Toolkit::formatDate($event['Event']['due_date'])?></td>
    <td><font color="<?php echo $event['Event']['due_in']>0 ? '' : 'red'?>">
        <?php echo abs($event['Event']['due_in'])?>
        <?php echo ($event['Event']['due_in']<0 && isset($event['Penalty']['id'])) ? $event['Penalty']['percent_penalty'] . '% penalty' : ''?>
    </font></td>
</tr>
<?php endforeach; ?>
<?php if (empty($evalEvents)):?>
    <tr><td colspan="5" align="center"><b> No peer evaluations due at this time </b></td></tr>
<?php endif; ?>
    </table>
</div>

<h3>Surveys Due</h3>
<div>
    <table class='standardtable'>
    <tr>
       <th>Event</th>
       <th width='20%'>Course</th>
       <th width='20%'>Due Date</th>
       <th width='20%'>Due In/Late By (red)</th>
    </tr>
<?php foreach ($surveyEvents as $event): ?>
<tr>
    <td><?php echo $html->link($event['Event']['title'], '/evaluations/makeEvaluation/'.$event['Event']['id'])?></td>
    <td><?php echo $event['Course']['course']?></td>
    <td><?php echo Toolkit::formatDate($event['Event']['due_date'])?></td>
    <td><font color="<?php echo $event['Event']['due_in']>0 ? '' : 'red'?>"><?php echo abs($event['Event']['due_in'])?></font></td>
</tr>
<?php endforeach; ?>
<?php if (empty($surveyEvents)):?>
    <tr><td colspan="5" align="center"><b> No survey due at this time </b></td></tr>
<?php endif; ?>
    </table>
</div>

<h3>Peer Evaluations/Surveys Submitted or Overdue</h3>

<div>
    <table class='standardtable'>
    <tr>
        <th width='30%'>Event</th>
        <th width='10%'>Group</th>
        <th width='20%'>Course</th>
        <th width='20%'>Due Date</th>
        <th width='20%'>Date Submitted</th>
    </tr>
<?php foreach ($submitted as $event): ?>
<tr>
    <td>
    <?php if (((isset($event['Event']['is_result_released']) && $event['Event']['is_result_released']) ||
            !isset($event['Event']['is_result_released'])) && User::hasPermission('functions/viewstudentresults') && isset($event['EvaluationSubmission']['date_submitted'])):?>
        <?php $id = $event['Event']['event_template_type_id'] == 3 ? '' : '/'.$event['Group']['id']?>
        <?php echo $html->link($event['Event']['title'], '/evaluations/studentViewEvaluationResult/'.$event['Event']['id'].$id)?>
    <?php else: ?>
        <?php echo $event['Event']['title'] ?>
    <?php endif; ?>
    </td>
    <td><?php echo isset($event['Group']['group_name']) ? $event['Group']['group_name'] : "--"?></td>
    <td><?php echo $event['Course']['course']?></td>
    <td><?php echo Toolkit::formatDate($event['Event']['due_date'])?></td>
    <td><?php echo isset($event['EvaluationSubmission']['date_submitted']) ? Toolkit::formatDate($event['EvaluationSubmission']['date_submitted']):'--'?></td>
</tr>
<?php endforeach; ?>
<?php if (empty($submitted)):?>
    <tr><td colspan="5" align="center"><b>No evaluations or survey submitted at this time</b></td></tr>
<?php endif; ?>
    </table>
</div>
