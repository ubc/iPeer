<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $javascript->link('ricobase')?>
<?php echo $javascript->link('ricoeffects')?>
<?php echo $javascript->link('ricoanimation')?>
<?php echo $javascript->link('ricopanelcontainer')?>
<?php echo $javascript->link('ricoaccordion')?>
<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeRubricEvaluation') ?>">
      <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
      <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
      <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>"/>
      <input type="hidden" name="course_id" value="<?php echo $rdAuth->courseId?>"/>
      <input type="hidden" name="rubric_id" value="<?php echo $rubric['Rubric']['id']?>"/>
      <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $rdAuth->id?>"/>
      <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center">Evaluation Event Detail</td>
    </tr>
  <tr class="tablecell2">
    <td width="10%">Evaluator:</td>
    <td width="25%"><?php echo $rdAuth->fullname ?>
    </td>
    <td width="10%">Evaluating:</td>
    <td width="25%"><?php echo $event['group_name'] ?></td>
  </tr>
  <tr class="tablecell2">
    <td>Event Name:</td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td>Due Date:</td>
    <td><?php if (isset($event['Event']['due_date'])) echo $this->controller->Output->formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td>Description:&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
    <b> Instructions:</b><br>
      1. Click <font color ="#FF6666"><i>EACH</i></font> of your peer's name to rate his/her performance.<br>
      2. Enter Comments <?php echo  $event['Event']['com_req']? '<font color="red"> (Must) </font>' : '(Optional)' ;?> .<br>
      3. Press "Save This Section" or "Edit This Section" once to save the evaluation on individual peer.<br>
      4. Press "Submit to Complete the Evaluation" to submit your evlauation to all peers. <br>
      <i>NOTE:</i> You can click the "Submit to Complete the Evaluation" button only <font color ="#FF6666">AFTER</font> all evaluations are completed.
    </td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr>
		<td>
<div id="accordion">
	<?php $i = 0;
	foreach($groupMembers as $row): $user = $row['User']; ?>
	<input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/>
		<div id="panel<?php echo $user['id']?>">
		  <div id="panel<?php echo $user['id']?>Header" class="panelheader">
		  	<?php
		  	echo $user['last_name'].' '.$user['first_name'];
		  	if (isset($user['Evaluation'])) {
		  	  echo '<font color="#66FF33"> ( Saved )</font>';
		  	} else {
		  	  echo '<blink><font color="#FF6666"> - </font></blink>  (click to expand)';
		  	}
		  	?>
		  </div>
		  <div style="height: 200px;" id="panel1Content" class="panelContent">
			 <br>Important! Comments are required in this evaluation.<br><br>

      <?php
      $params = array('controller'=>'rubrics','data'=>$this->controller->RubricHelper->compileViewData($rubric), 'evaluate'=>1, 'user'=>$user);
      echo $this->renderElement('rubrics/ajax_rubric_view', $params);
      ?>
      <table align="center" >
        <tr class="tablecell2">
          <td align="center"><?php
            echo $html->submit('Save This Section', array('name'=>$user['id']));
            echo "<br />Make sure you save this section before moving on to the other ones! <br /><br />";
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
  <input type="hidden" name="course_id" value="<?php echo $rdAuth->courseId?>"/>
  <input type="hidden" name="rubric_id" value="<?php echo $rubric['Rubric']['id']?>"/>
  <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $rdAuth->id?>"/>
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
    echo $html->submit('Submit to Complete the Evaluation');
  }
  else {
    echo $html->submit('Submit to Complete the Evaluation', array('disabled'=>'true')); echo "<br />";
    echo $mustCompleteUsers ? "<div style='color: red'>Please complete the questions for all group members, pressing 'Save This Section' button for each one.</div>" : "";
    echo $commentsNeeded ? "<div style='color: red'>Please Enter all the comments for all the group members before submitting.</div>" : "";
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
