<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>

<?php echo empty($mixeval['Evaluation']['id']) ? null : $html->hidden('Evaluation.id'); ?>
<form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeEvaluation') . '/'.$event['Event']['id'].'/'.$event['Group']['id']; ?>">

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
        <div id="accordion">
        <?php foreach($groupMembers as $row): $user = $row['User']; ?>
        <!-- MT move to view_mixeval_details -->
        <div id="panel<?php echo $user['id']?>">
            <!-- MT redo section -->
            <div id="panel<?php echo $user['id']?>Header" class="panelheader">
                <?php echo $user['full_name']?>
                <?php if (isset($user['Evaluation'])):?>
                <?php
                  // check if the evaluation comment is empty
                  // MT check whether required fields are filled
                  $requiredNeeded = false;
                  $evaluationDetails = $user['Evaluation']['EvaluationMixevalDetail'];
                  foreach ($evaluationDetails as $detailEval) {
                    $detail = $detailEval;
                    //debug($mixeval['MixevalQuestion']);
                    $type = $mixeval['MixevalQuestion'][$detail['question_number']-1]['MixevalQuestionType'];
                    if ($type['type'] != 'Likert' && empty($detail['question_comment'])) {
                        $requiredNeeded = true;
                        break;
                    }
                  }
                  $partial = '';
                  if($requiredNeeded) {
                    $partial = '<font color="red">'.__('Partially', true).' </font>';
                  }
                ?>
                  <font color="#259500"> ( <?php echo $partial?><?php __('Entered')?> )</font>
                <?php else:?>
                  <font color="#FF6666"> - <?php __('Incomplete')?> </font>
                <?php endif;?>
            </div>
            <!-- MT end section-->
            <div id="panel1Content" class="panelContent">
              <?php
              $params = array(  'controller'            => 'mixevals',
                                'zero_mark'             => $mixeval['Mixeval']['zero_mark'],
                                'total_mark'            => $mixeval['Mixeval']['total_marks'],
                                'questions'             => $questions,
                                'mixeval'               => $mixeval,
                                'event'                 => $event,
                                'user'                  => $user);


              echo $this->element('mixevals/view_mixeval_details', $params);
              ?>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        </td>
    </tr>
</table>
</form>

<table class="standardtable">
  <tr>
    <td colspan="4" align="center"><form name="submitForm" id="submitForm" method="POST" action="<?php echo $html->url('completeEvaluationMixeval') ?>">
  <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
  <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>"/>
  <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>"/>
  <input type="hidden" name="course_id" value="<?php echo $courseId ?>"/>
  <input type="hidden" name="mixeval_id" value="<?php echo $mixeval['Mixeval']['id']?>"/>
  <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo User::get('id')?>"/>
  <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
<center>
<?php
  $count = 0;
  foreach($groupMembers as $row) {
    $user = $row['User'];
    if (isset($user['Evaluation'])) {
      $count++;
    }
  }
    $mustCompleteUsers = ($count != $evaluateeCount);

    $requiredNeeded = false;
    // Check if any comment fields were left empty.
    if ($event['Event']['com_req']) {
        foreach($groupMembers as $row) {
            $user = $row['User'];

            if (empty($user['Evaluation'])) {
                $requiredNeeded = true;      // Not evaluated? Then we need comments for sure
                //echo "(Please complete evaluation for student $user[full_name])<br />";
            } else {
                if (isset($params['data']['questions'])) {
                    $evaluationDetails = $user['Evaluation']['EvaluationDetail'];
                    foreach ($evaluationDetails as $detailEval) {
                        $detail = $detailEval['EvaluationMixevalDetail'];
                        if ($params['data']['questions'][$detail['question_number']]['mixeval_question_type_id'] != '1' &&
                            '' === $detail['question_comment']) {
                            $requiredNeeded = true;      // A criteria comment is missing
                            //echo "Missing detail $detail[id] for user $user[id]<br />";
                            break;
                        } else {
                            //echo "OK detail $detail[id] ($detail[question_comment]) for user $user[id]<br />";
                        }
                    }
                }
            }
        }
    }
    if (!$mustCompleteUsers && !$requiredNeeded) {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('div'=>'submitMixeval'));
    } else {
        echo $form->submit(__('Submit to Complete the Evaluation', true), array('disabled'=>'true', 'div'=>'submitMixeval')); echo "<br />";
        echo $mustCompleteUsers ? "<div style='color: red'>".__("Please complete the questions for all group members, pressing 'Save This Section' button for each one.", true)."</div>" : "";
        echo $requiredNeeded ? "<div style='color: red'>".__("Please Enter all the comments for all the group members before submitting.", true)."</div>" : "";
    }

?>
</center>
</tr>
</table>

    <script type="text/javascript">
    new Rico.Accordion( 'accordion',
  {hoverClass: 'mdHover',
  selectedClass: 'mdSelected',
  clickedClass: 'mdClicked',
  unselectedClass: 'panelheader'});

    </script>
