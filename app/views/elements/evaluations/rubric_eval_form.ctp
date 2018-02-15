<script type="text/javascript">
var numQues = <?php echo count($data['RubricsCriteria']) ?>;
function saveButtonVal(userId) {
    var complete = true;
    for (var i=1; i <= numQues; i++) {
        var value = jQuery('input[name='+userId+'criteria_points_'+i+']:checked').val();
        if (value == null) {
            jQuery('#'+userId+'criteria'+i).attr('color', 'red');
            complete = false;
        } else {
            jQuery('#'+userId+'criteria'+i).removeAttr('color');
        }
    }
    if (complete) {
        jQuery('#'+userId+'likert').hide();
    } else {
        jQuery('#'+userId+'likert').show();
    }
    return complete;
}
var groupMembers = [<?php echo implode(",", Set::extract('/User/id', $groupMembers)); ?>];
jQuery( document ).ready(function() {
    jQuery("#submitForm").submit(function(e) {
        e.preventDefault();
        var thisForm = this;
        var usersToSubmit = [];

        for (var count = 0; count < groupMembers.length; count++) {
            var userId = groupMembers[count];
            var wasUpdated = jQuery("input[name='member_"+userId+"_updated']").val() == "1";
            if (!wasUpdated) {
                continue;
            }

            // check if form complete for user
            var complete = saveButtonVal(userId);

            if (complete) {
                usersToSubmit.push(userId); // if complete, submit form for this user
            } else {
                // else we need to warn user that they will lose data for incompelte forms

                var fullname = jQuery("#panel"+userId).data("fullname");
                var message = "<?php __('You have not completed all the questions for %fullname%. Would you like to discard changes?'); ?>";
                message = message.replace("%fullname%", fullname);
                if (!confirm(message)) {
                    return;
                }
            }
        }

        // update button text indicating user should wait
        jQuery(thisForm).find("input[type='submit']").attr("disabled", true).val("<?php echo __('Saving... Please wait.', true); ?>");

        if (usersToSubmit.length == 0) {
            thisForm.submit();
        } else {
            var ajaxCalls = [];
            var userEvalForm = jQuery('#evalForm');

            for (count = 0; count < usersToSubmit.length; count++) {
                var formData = userEvalForm.serialize();
                formData += "&" + usersToSubmit[count] + "=Submit";
                ajaxCalls.push(jQuery.post(userEvalForm.attr('action'), formData));
            }

            // when all the ajax calls are finished
            jQuery.when.apply(jQuery, ajaxCalls).then(function() {
                thisForm.submit();
            });
        }
    });
});
</script>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeEvaluation/'.$event['Event']['id'].'/'.$event['Group']['id']) ?>">
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
<input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>"/>
<input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>"/>
<input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>"/>
<input type="hidden" name="rubric_id" value="<?php echo $viewData['id']?>"/>
<input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $userId ?>"/>
<input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>

<table class="standardtable">
    <tr>
        <th colspan="4" align="center"><?php __('Evaluation Event Detail')?></th>
    </tr>
    <tr>
        <td width="10%"><?php __('Evaluator')?>:</td>
        <td width="25%"><?php echo $fullName ?></td>
        <td width="10%"><?php __('Evaluating')?>:</td>
        <td width="25%"><?php echo $event['Group']['group_name'] ?></td>
    </tr>
    <tr>
        <td><?php __('Event Name')?>:</td>
        <td><?php echo $event['Event']['title'] ?></td>
        <td><?php __('Due Date')?>:</td>
        <td><?php echo Toolkit::formatDate($event['Event']['due_date']) ?></td>
    </tr>
    <tr>
        <td><?php __('Description')?>:</td>
        <td colspan="3"><?php echo $event['Event']['description'] ?></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">
            <span class="instruction-icon"><?php __(' Instructions')?>:</span>
            <ul class="instructions">
            <li><?php __('Click <font color ="#FF6666"><i>EACH</i></font> of your peer\'s name to rate their performance.')?></li>
            <li><?php __('Enter Comments')?> (<?php echo $event['Event']['com_req']? '<font color="red">'.__('Required', true).'</font>' : __('Optional', true) ;?>).</li>
            <li><?php __('Press "Save This Section" to save the evaluation for each group member.')?></li>
            <li><?php __('Press "Submit to Complete the Evaluation" to submit your evaluation to all peers.')?> </li>
            <li><?php __('<i>NOTE:</i> You can click the "Submit to Complete the Evaluation" button only <font color ="#FF6666">AFTER</font> all evaluations are completed.')?></li>
            <?php $releaseEnd = !isset($event['Event']['release_date_end']) ? '<i>'.__("Evaluation's release end date", true).'</i>' : Toolkit::formatDate($event['Event']['release_date_end']); ?>
            <li><?php echo __('The evaluation can be repeatedly submitted until ', true).$releaseEnd.'.'?></li>
            </ul>

            <div style="text-align:left; margin-left:3em;"><a href="#" onClick="javascript:$('penalty').toggle();return false;">( <?php __('Show/Hide late penalty policy')?> )</a></div>
            <div id ="penalty" style ="border:1px solid red; margin: 0.5em 0 0 3em; width: 450px; padding:0.5em; color:darkred; display:none">
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
                    echo 'No penalty is specified for this evaluation.';
                }
                ?>
            </div>
        </td>
    </tr>
</table>

<table class="standardtable">
    <tr>
        <td>
        <div id="accordion">
        <?php foreach($groupMembers as $row): $user = $row['User'];?>
            <input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/>
            <div id="panel<?php echo $user['id']?>" class="panelName" data-fullname="<?php echo $user['first_name'].' '.$user['last_name'];?>">
                <div id="panel<?php echo $user['id']?>Header" class="panelheader">
                <?php echo $user['first_name'].' '.$user['last_name'];?>
                <?php if (isset($row['User']['Evaluation'])): ?>
                    <font color="#259500"> ( Saved )</font>
                <?php else: ?>
                    <blink><font color="#FF6666"> - </font></blink><?php __('(click to expand)')?>
                <?php endif; ?>
                </div>
                <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <br>
                    <?php
                    $params = array('controller'=>'rubrics', $viewData , 'evaluate'=>1, 'user'=>$user, 'event'=>$event);
                    echo $this->element('rubrics/ajax_rubric_view', $params);
                    ?>
                    <table align="center" width=100% >
                    <tr>
                        <td align="center">
                            <?php if (!isset($preview)): ?>
                                <?php echo $form->submit(__('Save This Section', true), array('name' => $user['id'], 'div' => 'saveThisSection')); ?>
                            <?php else: ?>
                                <?php echo $form->submit(__('Save This Section', true), array('disabled' => true, 'div' => 'saveThisSection')); ?>
                                <div style='color: red'><?php __('This is a preview. All submissions are disabled.')?></div>
                            <?php endif; ?>
                            <div style='color: red' id='<?php echo $user['id']?>likert'><?php __('Please complete all the questions marked red before saving.')?></div>
                            <div style='color: red'><?php __('Make sure you save this section before moving on to the other ones!')?></div>
                        </td>
                    </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        </td>
    </tr>
</table>
</form>
<table class="standardtable">
    <tr><td colspan="4" align="center">
<form name="submitForm" id="submitForm" method="POST" action="<?php echo $html->url('completeEvaluationRubric') ?>">
    <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
    <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>"/>
    <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>"/>
    <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>"/>
    <input type="hidden" name="rubric_id" value="<?php echo $viewData['id']?>"/>
    <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo User::get('id')?>"/>
    <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
    <?php
    if ( !$comReq && !isset($preview)) {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('div'=>'submitComplete'));
    } else {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('disabled'=>'true','div'=>'submitComplete')); echo "<br />";
        echo isset($preview) ? "<div style='color: red'>".__('This is a preview. All submissions are disabled.', true).'</div>' : "";
        echo $comReq ? "<div style='color: red'>".__('Please enter all the comments for all the group members before submitting.', true).'</div>' : "";
    }
    echo !$allDone ? "<div style='color: red'>".__("You haven't completed evaluations for all your teammates. You can submit now and modify your submission by the due date.", true).'</div>' : "";
    ?>
</form></td></tr>
</table>
<script type="text/javascript">
new Rico.Accordion( 'accordion',
    {panelHeight: 600,
    hoverClass: 'mdHover',
    selectedClass: 'mdSelected',
    clickedClass: 'mdClicked',
    unselectedClass: 'panelheader',
    initHideAll: true});

var userIds = [<?php echo $userIds ?>];
jQuery.each(userIds, function(index, userId) {
    jQuery('#'+userId+'likert').hide();
});
</script>
