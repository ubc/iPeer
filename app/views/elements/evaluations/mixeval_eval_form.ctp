<div class='MixevalForm'>
<center><h2><?php echo $event['Event']['title'].' - '.$event['Group']['group_name']?></h2></center>
<?php if (!empty($event['Event']['description'])) { ?>
<h2><?php echo _t('Description') ?></h2>
<div id='description'><?php echo _t($event['Event']['description']) ?></div>
<?php } ?>
<h2><?php echo _t('Notes') ?></h2>
<ul>
    <?php $due = Toolkit::formatDate($event['Event']['due_date'])?>
    <li><?php echo _t('The evaluation is due on ').$due.'.' ?></li>
    <li><?php echo _t('To resubmit an evaluation, all required questions must be answered.') ?></li>
    <?php $releaseEnd = Toolkit::formatDate($event['Event']['release_date_end']); ?>
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
        <input type="hidden" name="data[data][submitter_id]" value="<?php echo $userId ?>"/>
        <input type="hidden" name="data[data][event_id]" value="<?php echo $event['Event']['id']?>"/>
        <input type="hidden" name="data[data][template_id]" value="<?php echo $event['Event']['template_id']?>"/>
        <input type="hidden" name="data[data][grp_event_id]" value="<?php echo $event['GroupEvent']['id']?>"/>
        <input type="hidden" name="data[data][members]" value="<?php echo $members ?>"/>
        <?php  $evaluatee_count = count($groupMembers);
               $total_marksTbl= 10*$evaluatee_count; //10 is encoded as a constant for TBL evaluation types in order to avoid adding marks for Likert
        ?>

        <h1 id="peer-title" class="title"><?php __("Peer Evaluation Questions")?></h1>

        <?php
        if ($mixeval['Mixeval']['peer_question'] > 0 ) {
            foreach($groupMembers as $row): $user = $row['User']; ?>
                <center><h2><?php echo $user['full_name']?></h2></center>
                <?php
                $params = array('controller'            => 'mixevals',
                                'zero_mark'             => $mixeval['Mixeval']['zero_mark'],
                                'questions'             => $questions,
                                'event'                 => $event,
                                'user'                  => $user,
                                'evaluatee_count'       => $evaluatee_count,
                                'self_eval'             => 0,
                                'eval'                  => 'Evaluation'
                                );
                echo $this->element('mixevals/view_mixeval_details', $params);
                ?><br>
            <?php endforeach;
        }?>

        <?php if ($mixeval['Mixeval']['self_eval'] > 0 && $enrol > 0) { ?>
        <h1 id="self-title" class="title"><?php __("Self-Evaluation Questions")?></h1>

        <?php
            $params['self_eval'] = 1;
            $params['eval'] = 'Self-Evaluation';
            $params['user'] = array('id' => $userId);
            $params['self'] = $self;
            echo $this->element('mixevals/view_mixeval_details', $params);
        }
        ?><br>
        <center><?php echo $form->submit(__('Submit the Evaluation', true), array('div' => 'editSection', 'id' => 'submit', 'disabled' => isset($preview) ? 'true':'false')); ?></center>
        <?php echo $form->end(); ?>
        </td>
    </tr>
</table>

<?php if(empty($sub)) { ?>
<script type="text/javascript">
   jQuery('#submit').click(function() {
   if(!validateTotal()){
      var alertText = '<?php printf(__('Please make sure that the total of the grades in the drop-downs equals %s and then press "Submit" again.', true), $total_marksTbl) ?>';
      alert(alertText);
      return false;
    }
    });
</script>
<?php } ?>

<script type="text/javascript">
  function validateTotal(){
    var total = 0;
    var tbl_Exists = false;
    jQuery(".must").each(function() {
        if(jQuery(this).attr('id') == 'EvaluationMixevalDropdown'){
            tbl_Exists = true;
            total = parseInt(total,10) + parseInt(jQuery("option:selected",this).val(),10);
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

<?php if (!empty($sub)) { ?>
<script type="text/javascript">
jQuery("#submit").click(function() {
    if(!validateTotal()){
        var alertText = '<?php printf(__('Please make sure that the total of the grades in the drop-downs equals %s and then press "Submit" again.', true), $total_marksTbl) ?>';
        alert(alertText);
        return false;
    }
    if (!validate()) {
        alert("<?php __('Please fill in all required questions before resubmitting the evaluation.')?>");
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

</script>
<?php } ?>
</div>
