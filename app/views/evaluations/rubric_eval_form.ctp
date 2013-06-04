<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeEvaluation/'.$event['Event']['id'].'/'.$event['Group']['id']) ?>">
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
<input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>"/>
<input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>"/>
<input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>"/>
<input type="hidden" name="rubric_id" value="<?php echo $viewData['id']?>"/>
<input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo User::get('id')?>"/>
<input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
<table class="standardtable">
    <tr><th colspan="4" align="center"><?php __('Evaluation Event Detail')?></th></tr>
    <tr>
        <td width="10%"><?php __('Evaluator:')?></td>
        <td width="25%"><?php echo User::get('full_name') ?></td>
        <td width="10%"><?php __('Evaluating:')?></td>
        <td width="25%"><?php echo $event['Group']['group_name'] ?></td>
    </tr>
    <tr>
        <td><?php __('Event Name:')?></td>
        <td><?php echo $event['Event']['title'] ?></td>
        <td><?php __('Due Date:')?></td>
        <td><?php if (isset($event['Event']['due_date'])) echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
    </tr>
    <tr>
        <td><?php __('Description:')?></td>
        <td colspan="3"><?php echo $event['Event']['description'] ?></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">
            <span class="instruction-icon"><?php __(' Instructions')?>:</span>
            <ul class="instructions">
            <li><?php __('Click <font color ="#FF6666"><i>EACH</i></font> of your peer\'s name to rate his/her performance.')?></li>
            <li><?php __('Enter Comments')?> (<?php echo $event['Event']['com_req']? '<font color="red">'.__('Must', true).'</font>' : __('Optional', true) ;?>).</li>
            <li><?php __('Press "Save This Section" or "Edit This Section" once to save the evaluation on individual peer.')?></li>
            <li><?php __('Press "Submit to Complete the Evaluation" to submit your evaluation to all peers.')?> </li>
            <li><?php __('<i>NOTE:</i> You can click the "Submit to Complete the Evaluation" button only <font color ="#FF6666">AFTER</font> all evaluations are completed.')?></li>
            <?php $releaseEnd = date('l, F j, Y g:i a', strtotime($event['Event']['release_date_end'])); ?>
            <li><?php echo _t('The evaluation can be repeatedly submitted until ').$releaseEnd.'.'?></li>
            </ul>

            <div style="text-align:left; margin-left:3em;"><a href="#" onClick="javascript:$('penalty').toggle();return false;">( <?php __('Show/Hide late penalty policy')?> )</a></div>
                <div id ="penalty" style ="border:1px solid red; margin: 0.5em 0 0 3em; width: 450px; padding:0.5em; color:darkred; display:none">
                <?php if(!empty($penalty)){
                    foreach($penalty as $day){
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

<table class="standardtable">
    <tr>
        <td>
        <div id="accordion">
        <?php foreach($groupMembers as $row): $user = $row['User'];?>
            <input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/>
            <div id="panel<?php echo $user['id']?>" class="panelName">
                <div id="panel<?php echo $user['id']?>Header" class="panelheader">
                <?php echo $user['first_name'].' '.$user['last_name'];?>
                <?php if (isset($row['User']['Evaluation'])): ?>
                    <font color="#259500"> ( Saved )</font>
                <?php else: ?>
                    <blink><font color="#FF6666"> - </font></blink><?php __('(click to expand)')?>
                <?php endif; ?>
                </div>
                <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <?php if ($event['Event']['com_req']) { ?>
                    <br><?php __('Important! Comments are required in this evaluation.')?>
                    <?php } ?>
                    <br>
                    <?php
                    $params = array('controller'=>'rubrics', $viewData , 'evaluate'=>1, 'user'=>$user);
                    echo $this->element('rubrics/ajax_rubric_view', $params);
                    ?>
                    <table align="center" width=100% >
                    <tr>
                        <td align="center">
                        <?php echo $form->submit('Save This Section', array('name'=>$user['id'], 'div'=>'saveThisSection'));
                        echo "<br />".__('Make sure you save this section before moving on to the other ones!', true)." <br /><br />";
                        ?>
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
    if ($allDone && !$comReq) {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('div'=>'submitComplete'));
    } else {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('disabled'=>'true','div'=>'submitComplete')); echo "<br />";
        echo !$allDone ? "<div style='color: red'>".__("Please complete the questions for all group members, pressing 'Save This Section' button for each one.</div>", true) : "";
        echo $comReq ? "<div style='color: red'>".__('Please enter all the comments for all the group members before submitting.</div>', true) : "";
    }
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
        onShowTab: 'panel6' });
</script>
