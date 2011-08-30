<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $html->script('ricobase')?>
<?php echo $html->script('ricoeffects')?>
<?php echo $html->script('ricoanimation')?>
<?php echo $html->script('ricopanelcontainer')?>
<?php echo $html->script('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeRubricEvaluation') ?>">
      <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
      <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
      <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>"/>
      <input type="hidden" name="course_id" value="<?php echo $courseId ?>"/>
      <input type="hidden" name="rubric_id" value="<?php echo $rubric['Rubric']['id']?>"/>
      <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $evaluatorId ?>"/>
      <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center"><?php __('Evaluation Event Detail')?></td>
    </tr>
  <tr class="tablecell2">
    <td width="10%"><?php __('Evaluator:')?></td>
    <td width="25%"><?php echo $firstName.' '.$lastName ?>
    </td>
    <td width="10%"><?php __('Evaluating:')?></td>
    <td width="25%"><?php echo $event['group_name'] ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Event Name:')?></td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php __('Due Date:')?></td>
    <td><?php if (isset($event['Event']['due_date'])) echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Description:')?>&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
    <b> <?php __('Instructions:')?></b><br>
      1. <?php __('Click <font color ="#FF6666"><i>EACH</i></font> of your peer\'s name to rate his/her performance.')?><br>
      2. <?php __('Enter Comments')?> <?php echo  $event['Event']['com_req']? '<font color="red">'.__('(Must)', true).'</font>' : __('(Optional)', true) ;?> .<br>
      3. <?php __('Press "Save This Section" or "Edit This Section" once to save the evaluation on individual peer.')?><br>
      4. <?php __('Press "Submit to Complete the Evaluation" to submit your evlauation to all peers.')?> <br>
      <?php __('<i>NOTE:</i> You can click the "Submit to Complete the Evaluation" button only <font color ="#FF6666">AFTER</font> all evaluations are completed.')?>
    </td>
  </tr>
</table>

 <div style="text-align:left; margin-left:3em;"><a href="#" onClick="javascript:$('penalty').toggle();return false;">( <?php __('Show/Hide late penalty policy')?> )</a></div>
    <div id ="penalty" style ="border:1px solid red; margin: 0.5em 0 0 3em; width: 450px; padding:0.5em; color:darkred; display:none">
    	   	
	<?php if(!empty($penalty)){
	  
	  if($penaltyType == -1){
      echo $penalty[0]['Penalty']['percent_penalty'].'% is deducted every day for '.$penaltyDays.' days. '.$penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';	    
	  }
	  
	  if($penaltyType == -2){
      foreach($penalty as $day){  
        $mult = ($day['Penalty']['days_late']>1)?'s':'';
        echo $day['Penalty']['days_late'].' day'.$mult.' late: '.$day['Penalty']['percent_penalty'].'% deduction. </br>'; 
      }
      echo $penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';	    
	  }	 
	 } else {
	   echo 'No penalty is specified for this evaluation.';	   
	 }
	
	?>    

  </div>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr>
		<td>
<div id="accordion">
	<?php $i = 0;
	foreach($groupMembers as $row): $user = $row['User'];
	?>
	<input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/>
		<div id="panel<?php echo $user['id']?>">
		  <div id="panel<?php echo $user['id']?>Header" class="panelheader">
		  	<?php
		  	echo $user['first_name'].' '.$user['last_name'];
		  	if (isset($row['User']['Evaluation'])) {
		  	  echo '<font color="#66FF33"> ( Saved )</font>';
		  	} else {
		  	  echo '<blink><font color="#FF6666"> - </font></blink>'.__('(click to expand)', true);
		  	}
		  	?>
		  </div>
		  <div style="height: 200px;" id="panel1Content" class="panelContent">
			 <br><?php __('Important! Comments are required in this evaluation.')?><br><br>

      <?php
      $params = array('controller'=>'rubrics', $viewData , 'evaluate'=>1, 'user'=>$user);
      echo $this->element('rubrics/ajax_rubric_view', $params);
      ?>
      <table align="center" >
        <tr class="tablecell2">
          <td align="center"><?php
            echo $form->submit('Save This Section', array('name'=>$user['id']));
            echo "<br />".__('Make sure you save this section before moving on to the other ones!', true)." <br /><br />";
            ?></td>
        </tr>
      </table>
		  </div>
		</div>

	<?php $i++; ?>
	<?php endforeach; ?>
</div>
		</td>
	</tr>
	</table>
</form>
<table align="center" bgcolor="#E5E5E5" width="95%">
  <tr class="tablecell2">
    <td colspan="4" align="center">
<form name="submitForm" id="submitForm" method="POST" action="<?php echo $html->url('completeEvaluationRubric') ?>">
  <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
  <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
  <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>"/>
  <input type="hidden" name="course_id" value="<?php echo $courseId?>"/>
  <input type="hidden" name="rubric_id" value="<?php echo $rubric['Rubric']['id']?>"/>
  <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $evaluatorId ?>"/>
  <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
  <?php
  $count = 0;
  foreach($groupMembers as $row) {
    $user = $row['User'];
    if (isset($user['Evaluation'])) {
      $count++;
    }
  }
    $mustCompleteUsers = ($count != $evaluateeCount);
    $commentsNeeded = false;
    // Check if any comment fields were left empty.
    if ($event['Event']['com_req'] && isset($data['questions'])) {
        foreach($groupMembers as $row) {
            $user = $row['User'];
            if (empty($user['Evaluation'])) {
                $commentsNeeded = true;      // Not evaluated? Then we need comments for sure
            } else {
                    $evaluation = $user['Evaluation']['EvaluationRubric'];
                    $evaluationDetails = $user['Evaluation']['EvaluationRubricDetail'];
                    foreach ($evaluationDetails as $detail)
                        if ($data['questions'][$detail['question_number']]['question_type'] !='S' &&   // if the questing in not a selection one.
                            empty($detail['criteria_comment'])) {
                            $commentsNeeded = true;      // A criteria comment is missing
                            break;
                        }
                if (empty($evaluation['general_comment'])) {
                    $commentsNeeded = true;   // General comment missing
                }
            }

            if ($commentsNeeded) {
                break; // avoid too much looping. If we need comments, that's it, we need comments!
            }

        }
    } else {
        $commentsNeeded = false;
    }
    
    
  if (!$mustCompleteUsers && !$commentsNeeded) {
    echo $form->submit(__('Submit to Complete the Evaluation', true), array('onClick' => "javascript:return confirm('".__('Once you submit the input, you cannot change them. Please review your input before submitting. Are you sure you want to submit?', true)."')"));
  }
  else {
    echo $form->submit(__('Submit to Complete the Evaluation', true), array('disabled'=>'true')); echo "<br />";
    echo $mustCompleteUsers ? "<div style='color: red'>".__("Please complete the questions for all group members, pressing 'Save This Section' button for each one.</div>", true) : "";
    echo $commentsNeeded ? "<div style='color: red'>".__('Please Enter all the comments for all the group members before submitting.</div>', true) : "";
  }




  ?>

    </form></td>
    </tr>
</table>
	<script type="text/javascript"> new Rico.Accordion( 'accordion',
								{panelHeight:500,
								 hoverClass: 'mdHover',
								 selectedClass: 'mdSelected',
								 clickedClass: 'mdClicked',
								 unselectedClass: 'panelheader',
								 onShowTab: 'panel6' });

	</script>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
	</td>
  </tr>
</table>
