<div class='MixevalForm'>
<center><h2><?php echo $event['Event']['title'].' - '.$event['Group']['group_name']?></h2></center>
<?php if (!empty($event['Event']['description'])) { ?>
<h2><?php echo __('Description', true) ?></h2>
<div id='description'><?php echo $event['Event']['description'] ?></div>
<?php } ?>
<h2><?php __('Notes') ?></h2>
<ul>
    <?php $due = Toolkit::formatDate($event['Event']['due_date'])?>
    <li><?php printf(__('The evaluation is due on %s.', true), $due) ?></li>
    <li><?php __('To resubmit an evaluation, all required questions must be answered.') ?></li>
    <?php $releaseEnd = Toolkit::formatDate($event['Event']['release_date_end']); ?>
    <li><?php __('The evaluation can be repeatedly submitted until ').$releaseEnd.'.'?></li>
    <li><?php echo $html->tag('span', '*', array('class' => 'required orangered')).__(' marks required questions.', true)?></li>
    <li><a href="#" onClick="javascript:$('penalty').toggle(); return false;">
        <?php __('Show/Hide Late Penalty Policy') ?></a>
        <div id='penalty' style="display:none">
            <?php if (!empty($penalty)) {
                $notFirst = false;
                foreach ($penalty as $day) {
                    $pen = $day['Penalty'];
                    if ($notFirst) {
                        echo sprintf(__('Then until %d day(s) after the due date, %s will be deducted.', true), $pen['days_late'], $pen['percent_penalty'].'%').'<br>';
                    } else {
                        echo sprintf(__('From the due date to %d days(s) late, %s will be deducted.', true), $pen['days_late'], $pen['percent_penalty'].'%').'</br>';
                        $notFirst = true;
                    }
                }
                echo sprintf(__('%s is deducted afterwards.', true), $penaltyFinal['Penalty']['percent_penalty'].'%');
            } else {
                echo __('No penalty is specified for this evaluation.', true);
            } ?>
        </div>
    </li>
</ul>

<table>
    <tr>
        <td>
        <?php echo $this->Form->create('EvaluationMixeval', array(
            'url' => 'makeEvaluation/'.$event['Event']['id'].'/'.$event['Group']['id'].'/'.(empty($studentId) ? '' : $studentId)));?>
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
        function sort_questions($array, $key) {
            foreach ($array as $k => $v) {
                $question[] = strtolower($v['MixevalQuestion'][$key]);
            }
            asort($question);
            foreach ($question as $k => $v) {
                $q[] = $array[$k];
            }
            return $q;
        }
        $sorted_questions = sort_questions($questions, 'question_num');

        if ($mixeval['Mixeval']['peer_question'] > 0 ) {
            foreach($groupMembers as $row): $user = $row['User']; ?>
                <center><h2><?php echo $user['full_name']?></h2></center>
                <?php
                $params = array('controller'            => 'mixevals',
                                'zero_mark'             => $mixeval['Mixeval']['zero_mark'],
                                'questions'             => $sorted_questions,
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

        <?php if ($mixeval['Mixeval']['self_eval'] > 0 && $event['Event']['self_eval'] && $enrol > 0) { ?>
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
