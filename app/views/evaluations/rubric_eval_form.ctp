<script type="text/javascript">
var numQues = <?php echo count($data['RubricsCriteria']) ?>;
var numUsers = <?php echo count($groupMembers) ?>;

function saveButtonVal(userId, viewMode) {
    var complete = true;

    if(viewMode == 0){
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
    else if(viewMode == 1){
        var criteriaId = userId;
        for (var i=0; i < numUsers; i++) {
            var groupMembers = <?php echo json_encode($groupMembers); ?>;
            var user = groupMembers[i]['User']['id'];
            var value = jQuery('input[name='+user+'criteria_points_'+criteriaId+']:checked').val();
            if (value == null) {
                jQuery('#'+user+'criteria'+criteriaId).attr('color', 'red');
                complete = false;
            } else {
                jQuery('#'+user+'criteria'+criteriaId).removeAttr('color');
            }
        }
        if (complete) {
            jQuery('#'+criteriaId+'likert').hide();
        } else {
            jQuery('#'+criteriaId+'likert').show();
        }
        return complete;
    }
}
</script>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
<?php $studentId = User::get('id');?>
<form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeEvaluation/'.$event['Event']['id'].'/'.$event['Group']['id'].'/'.$studentId) ?>">
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
            <li><?php __('Enter Comments')?> (<?php echo $event['Event']['com_req']? '<font color="red">'.__('Required', true).'</font>' : __('Optional', true) ;?>).</li>
            <li><?php __('Press "Save This Section" to save the evaluation for each group member.')?></li>
            <li><?php __('Press "Submit to Complete the Evaluation" to submit your evaluation to all peers.')?> </li>
            <li><?php __('<i>NOTE:</i> You can click the "Submit to Complete the Evaluation" button only <font color ="#FF6666">AFTER</font> all evaluations are completed.')?></li>
            <?php $releaseEnd = date('l, F j, Y g:i a', strtotime($event['Event']['release_date_end'])); ?>
            <li><?php printf(__('The evaluation can be repeatedly submitted until %s.', true), $releaseEnd)?></li>
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

<?php
    if($data['Rubric']['view_mode'] == 'criteria') {
        $viewMode = 1;
    }
    else {
        $viewMode = 0;
    }
?>
<?php if($viewMode == 0): ?>
<!-- Group rubric by student -->
<table class="standardtable" id="view-mode-student">
    <?php $viewMode = 0; ?>
    <tr>
        <td>
        <div id="accordion">
        <?php foreach($groupMembers as $row): $user = $row['User'];?>
            <input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/>
            <div id="panel<?php echo $user['id']?>" class="panelName">
                <div id="panel<?php echo $user['id']?>Header" class="panelheader">
                <?php echo $user['first_name'].' '.$user['last_name'];?>
                <?php
                    $allSaved = 0;
                    if(isset($user['Evaluation']['EvaluationDetail'])){
                        $savedCriteria = $totalCriteria = 0;
                        $totalCriteria = count($data['RubricsCriteria']);
                        foreach($user['Evaluation']['EvaluationDetail'] as $evalDetail){
                            if($evalDetail['EvaluationRubric']['evaluatee'] == $user['id']){
                                $savedCriteria++;
                            };
                        }
                        if($totalCriteria == $savedCriteria){
                            $allSaved = 1;
                        }
                    }
                ?>
                <?php if ($allSaved == 1): ?>
                    <font color="#259500"> ( Saved )</font>
                <?php else: ?>
                    <blink><font color="#FF6666"> - </font></blink><?php __('(click to expand)')?>
                <?php endif; ?>
                </div>
                <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <br>
                    <?php
                    $params = array('controller'=>'rubrics', $viewData , 'evaluate'=>1, 'user'=>$user, 'event'=>$event, 'viewMode'=>$viewMode);
                    echo $this->element('rubrics/ajax_rubric_view', $params);
                    ?>
                    <table align="center" width=100% >
                    <tr>
                        <td align="center">
                        <?php echo $form->submit('Save This Section', array('name'=>$user['id'], 'div'=>'saveThisSection'));
                        echo "<br><div style='color: red' id='".$user['id']."likert'>".__('Please complete all the questions marked red before saving.</div>', true);
                        echo __('Make sure you save this section before moving on to the other ones!', true)." <br /><br />";
                        ?>
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

<?php elseif($viewMode == 1): ?>
<!-- Group rubric by criteria -->
<table class="standardtable" id="view-mode-criteria">
<?php $viewMode = 1; ?>
<?php $totalSaved = 0; ?>
    <tr>
        <td>
        <div id="accordion">
        <?php foreach($data['RubricsCriteria'] as $row):?>
            <input type="hidden" name="criteriaIDs[]" value="<?php echo $row['id']?>"/>
            <div id="panel<?php echo $row['id']?>" class="panelName">
                <div id="panel<?php echo $row['id']?>Header" class="panelheader">
                <?php echo $row['criteria'];?>
                <?php
                    $allSaved = 0;
                    $savedUsers = $totalUsers = 0;
                    $totalUsers = count($groupMembers);
                    foreach($groupMembers as $users){
                        if(isset($users['User']['Evaluation']['EvaluationRubricDetail'])){
                            foreach($users['User']['Evaluation']['EvaluationRubricDetail'] as $evalDetail){
                                if($evalDetail['criteria_number'] == $row['criteria_num']){
                                    $savedUsers++;
                                };
                            }
                        }
                    }
                    if($totalUsers == $savedUsers){
                        $allSaved = 1;
                        $totalSaved++;
                    }
                ?>
                <?php if ($allSaved == 1): ?>
                    <font color="#259500"> ( Saved )</font>
                <?php else: ?>
                    <blink><font color="#FF6666"> - </font></blink><?php __('(click to expand)')?>
                <?php endif; ?>
                </div>
                <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <br>
                    <?php
                    $params = array('controller'=>'rubrics', $viewData , 'evaluate'=>1, 'criteria'=>$row, 'event'=>$event, 'viewMode'=>$viewMode);
                    echo $this->element('rubrics/ajax_rubric_view', $params);
                    ?>
                    <table align="center" width=100% >
                    <tr>
                        <td align="center">
                        <?php echo $form->submit('Save This Section', array('name'=>$row['criteria_num'], 'div'=>'saveThisSection'));
                        echo "<br><div style='color: red' id='".$row['criteria_num']."likert'>".__('Please complete all the questions marked red before saving.</div>', true);
                        echo __('Make sure you save this section before moving on to the other ones!', true)." <br /><br />";
                        ?>
                        </td>
                        <script>jQuery("#<?php echo $row['criteria_num'].'likert';?>").hide();</script>
                    </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
            <div id="panel<?php echo $row['criteria_num']+1?>" class="panelName">
                <div id="panel<?php echo $row['criteria_num']+1?>Header" class="panelheader">
                    <?php echo "General Comments"?>
                    <?php
                        $allSaved = 1;
                        $savedUsers = $totalUsers = 0;
                        $totalUsers = count($groupMembers);
                        foreach($groupMembers as $users){
                            if(isset($users['User']['Evaluation']['EvaluationRubric']['comment'])){
                                if(!empty($users['User']['Evaluation']['EvaluationRubric']['comment'])){
                                        $savedUsers++;
                                }
                            }
                        }
                        if($totalUsers != $savedUsers){
                            $allSaved = 0;
                        }
                    ?>
                    <?php if ($allSaved == 1): ?>
                        <font color="#259500"> ( Saved )</font>
                    <?php else: ?>
                        <blink><font color="#FF6666"> - </font></blink><?php __('(click to expand)')?>
                    <?php endif; ?>
                </div>
                <div style="height: 200px;" id="panel1Content" class="panelContent">
                    <br>
                    <table align="center" width=100% >
                    <?php foreach($groupMembers as $index): $user = $index['User'];?>
                        <tr>
                            <th><?php echo $user['first_name'].' '.$user['last_name']; ?></th>
                            <td align="center">
                                <textarea cols="80" rows="2" name="<?php echo $user['id']?>gen_comment" ><?php echo (isset($user['Evaluation']) ? $user['Evaluation']['EvaluationRubric']['comment'] : '')?></textarea>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td align="center" colspan="2">
                        <?php
                        $value = $row['criteria_num'] + 1;
                        if($totalSaved == count($data['RubricsCriteria'])){
                            echo $form->submit('Save This Section', array('name'=>$value, 'div'=>'saveThisSection'));
                        }
                        else{
                            echo $form->submit('Save This Section', array('name'=>$value, 'disabled'=>true, 'div'=>'saveThisSection'));
                            echo "<br><div style='color: red'>".__('This section will be available once the previous sections are completed.</div>', true);
                        }
                        echo "<br><div style='color: red' id='". $value ."likert'>".__('Please complete all the questions marked red before saving.</div>', true);
                        echo "<br /><br />";
                        ?>
                        </td>
                        <script>jQuery("#<?php echo $value.'likert';?>").hide();</script>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
        </td>
    </tr>
</table>
<?php endif; ?>

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
    <input type="hidden" name="student_id" value="<?php echo $studentId?>"/>
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
    unselectedClass: 'panelheader'});
var userIds = [<?php echo $userIds ?>];
jQuery.each(userIds, function(index, userId) {
    jQuery('#'+userId+'likert').hide();
});
</script>
