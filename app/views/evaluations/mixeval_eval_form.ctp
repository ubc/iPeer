<div class='MixevalForm'>
<center><h2><?php echo $event['Event']['title'].' - '.$event['Group']['group_name']?></h2></center>
<?php if (!empty($event['Event']['description'])) { ?>
<h2><?php echo _t('Description') ?></h2>
<div id='description'><?php echo _t($event['Event']['description']) ?></div>
<?php } ?>
<h2><?php echo _t('Notes') ?></h2>
<ul>
    <?php $due = date('l, F j, Y g:i a', strtotime($event['Event']['due_date']))?>
    <li><?php echo _t('The evaluation is due on ').$due.'.' ?></li>
    <li><?php echo _t('To resubmit a evaluation, all required questions must be answered.') ?></li>
    <?php $releaseEnd = date('l, F j, Y g:i a', strtotime($event['Event']['release_date_end'])); ?>
    <li><?php echo _t('The evaluation can be repeatedly submitted until ').$releaseEnd.'.'?></li>
    <li><?php echo $html->tag('span', '*', array('class' => 'required orangered'))._t(' marks required questions.')?></li>
    <li><a href="#" onClick="javascript:$('penalty').toggle(); return false;">
        <?php echo _t('Show/Hide Late Penalty Policy') ?></a>
        <div id='penalty' style="display:none">
            <?php if (!empty($penalty)) {
                foreach ($penalty as $day) {
                    $mult = ($day['Penalty']['days_late']>1) ? 's' : '';
                    echo $day['Penalty']['days_late'].' day'.$mult.' late: '.
                        $day['Penalty']['percent_penalty'].'% deduction. </br>';
                }
                echo $penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';
            } else {
                echo _t('No penalty is specified for this evaluation.');
            } ?>
        </div>
    </li>
</ul>

<table>
    <tr>
        <td>
        <?php echo $this->Form->create('EvaluationMixeval', array(
            'url' => $html->url('makeEvaluation') . '/'.$event['Event']['id'].'/'.$event['Group']['id']));?>
        <?php echo "<input type='hidden' name=data[data][submitter_id] value='".User::get('id')."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][event_id] value='".$event['Event']['id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][template_id] value='".$event['Event']['template_id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][grp_event_id] value='".$event['GroupEvent']['id']."'/>"; ?>
        <?php echo "<input type='hidden' name=data[data][members] value='".$members."'/>"; ?>
        <?php foreach($groupMembers as $row): $user = $row['User']; ?>
            <center><h2><?php echo $user['full_name']?></h2></center>
            <?php
            $evaluatee_count = count($groupMembers);
            $total_marksTbl= $mixeval['Mixeval']['total_marks']*$evaluatee_count;
            $params = array(  'controller'            => 'mixevals',
                            'zero_mark'             => $mixeval['Mixeval']['zero_mark'],
                            'questions'             => $questions,
                            'event'                 => $event,
                            'user'                  => $user,
                            'evaluatee_count'       => $evaluatee_count
                            );
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
   if(!validateTotal()){
      var alertText = 'Please make sure that the Total of the grades you selected equals ' + <?php echo $total_marksTbl; ?> + ' and then resubmit.';
      alert(alertText);
      return false;
    }
    if (!validate()) {
        alert('Please fill in all required questions before resubmitting the evaluation.');
        return false;
    }
    
});

function validate() {
    var empty = false;
    jQuery(".must").each(function() {
        var type = jQuery(this).attr('type');
        var name = jQuery(this).attr('name');
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

function validateTotal(){
    var total = 0;
    var tbl_Exists = false;
    jQuery(".must").each(function() {
        if(jQuery(this).attr('id') == 'EvaluationMixevalDropdown'){
            tbl_Exists = true;
            total = total + jQuery("option:selected",this).val();
        }
    });
    var total_marksTbl = <?php echo $total_marksTbl ?>;
    var total_bool = total != total_marksTbl;
    if(tbl_Exists && total_bool){
        return false;
    } else {
        return true;
    }
}
</script>
<?php } ?>
</div>
