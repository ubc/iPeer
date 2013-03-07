<?php echo empty($mixeval['Evaluation']['id']) ? null : $html->hidden('Evaluation.id'); ?>
<table class="standardtable">
    <tr><th colspan="4" align="center"><?php __('Evaluation Event Detail')?></th></tr>
    <tr>
        <td width="10%"><?php __('Evaluator:')?></td>
        <td width="25%"><?php echo User::get('full_name')?></td>
        <td width="10%"><?php __('Evaluating:')?></td>
	    <td width="25%"><?php echo $event['Group']['group_name']; ?></td>
    </tr>
    <tr>
        <td><?php __('Event Name:')?></td>
        <td><?php echo $event['Event']['title'] ?></td>
        <td><?php __('Due Date:')?></td>
        <td><?php if (isset($event['Event']['due_date'])) echo Toolkit::formatDate($event['Event']['due_date']) ?></td>
    </tr>
    <tr>
        <td><?php __('Description:')?></td>
        <td colspan="3"><?php echo $event['Event']['description'] ?></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">
            <span class="instruction-icon"><?php __(' Instructions')?>:</span>
            <ul class="instructions">
            <li><?php __("Click your peer's name to rate his/her performance.")?></li>
            <!-- MT required fields *'ed -->
            <li><?php __('Enter Comments')?> <?php echo $event['Event']['com_req']? '<font color="red">'.__('(Must)', true). '</font>' : __('(Optional)', true) ;?>.</li>
            <li><?php __('Press "Save This Section" or "Edit This Section" once to save the evaluation on individual peer.')?></li>
            <li><?php __('Press "Submit to Complete the Evaluation" to submit your evaluation to all peers.')?> </li>
            </ul>

    <div style="text-align:left; margin-left:3em;"><a href="#" onClick="javascript:$('penalty').toggle();return false;">( <?php __('Show/Hide late penalty policy')?> )</a></div>
    <div id ="penalty" style ="border:1px solid red; margin-left: 3em; margin-top:0.5em; width: 450px; padding:0.5em; color:darkred; display:none">

<?php
if (!empty($penalty)) {
    foreach ($penalty as $day) {
        $mult = ($day['Penalty']['days_late']>1)?'s':'';
        echo $day['Penalty']['days_late'].' day'.$mult.' late: '.$day['Penalty']['percent_penalty'].'% deduction. </br>';
    }
    echo $penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';
} else {
    echo 'No penalty is specified for this evaluation.';
}
?>
  </div>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td>
        <?php echo $this->Form->create('EvaluationMixeval', array(
            'url' => $html->url('makeEvaluation') . '/'.$event['Event']['id'].'/'.$event['Group']['id'])); ?>
        <?php echo "<input type='hidden' name=data[data][submitter_id] value='".User::get('id')."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][event_id] value='".$event['Event']['id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][template_id] value='".$event['Event']['template_id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][grp_event_id] value='".$event['GroupEvent']['id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][members] value='".count($groupMembers)."'/>"; ?>
        <?php foreach($groupMembers as $row): $user = $row['User']; ?>
            <center><h2><?php echo $user['full_name']?></h2></center>
            <?php
            $params = array(  'controller'            => 'mixevals',
                            'zero_mark'             => $mixeval['Mixeval']['zero_mark'],
                            'total_mark'            => $mixeval['Mixeval']['total_marks'],
                            'questions'             => $questions,
                            'mixeval'               => $mixeval,
                            'event'                 => $event,
                            'user'                  => $user);


            echo $this->element('mixevals/view_mixeval_details', $params);
            ?><br>
        <?php endforeach; ?>
        <center><?php echo $form->submit(__('Submit the Evaluation', true), array('div' => 'editSection', 'id' => 'submit')); ?></center>
        <?php echo $form->end(); ?>
        </td>
    </tr>
</table>

<?php if (!empty($sub)) { ?>
<script type="text/javascript">
jQuery("#submit").click(function() {
    if (!validate()) {
        alert('Please fill in all required questions before resubmitting the evaluation.');
        return false;
    }
});

function validate() {
    var empty = false;
    jQuery(".must").each(function() {
        var type = jQuery(this).attr('type');
        if (type == 'radio') {
            var name = jQuery(this).attr('name');
            if (!jQuery("input[name='" + name + "']:checked").val()) {
                empty = true;
            }
        } else {
            if(jQuery(this).val() == '') {
                empty = true;
            }
        }
    });
    if (empty) {
        return false;
    } else {
        return true;
    }
}
</script>
<?php } ?>

