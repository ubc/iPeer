<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $javascript->link('ricobase')?>
<?php echo $javascript->link('ricoeffects')?>
<?php echo $javascript->link('ricoanimation')?>
<?php echo $javascript->link('ricopanelcontainer')?>
<?php echo $javascript->link('ricoaccordion')?>
	<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeMixevalEvaluation'); echo '/'.$event['Event']['id'].';'.$event['group_id']; ?>">
      <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
      <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
      <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>"/>
      <input type="hidden" name="course_id" value="<?php echo $rdAuth->courseId?>"/>
      <input type="hidden" name="mixeval_id" value="<?php echo $data['Mixeval']['id']?>"/>
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
      1. Click your peer's name to rate his/her performance.<br>
      2. Enter Comments <?php echo  $event['Event']['com_req']? '<font color="red"> (Must) </font>' : '(Optional)' ;?> .<br>
      3. Press "Save This Section" or "Edit This Section" once to save the evaluation on individual peer.<br>
      4. Press "Submit to Complete the Evaluation" to submit your evlauation to all peers. <br>
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
		  	  echo '<font color="#66FF33"> ( Completed )</font>';
		  	} else {
		  	  echo '<font color="#FF6666"> - Incompleted </font>';
		  	}
		  	?>
		  </div>
		  <div style="height: 200px;" id="panel1Content" class="panelContent">
			 <br><br>

      <?php

      $params = array('controller'=>'mixevals','data'=>$this->controller->MixevalHelper->compileViewData($data), 'scale_default'=>$data['Mixeval']['scale_max'], 'question_default'=>$data['Mixeval']['lickert_question_max'], 'prefill_question_max'=>$data['Mixeval']['prefill_question_max'], 'zero_mark'=>$data['Mixeval']['zero_mark'], 'total_mark'=>$data['Mixeval']['total_marks'], 'evaluate'=>1, 'user'=>$user);
      echo $this->renderElement('mixevals/view_mixeval_details', $params);
      ?>
      <table align="center" >
        <tr class="tablecell2">
          <td align="center"><?php
            if (isset($user['Evaluation'])) {
              echo $html->submit('Edit This Section (Click this button to save now or you may lose your input)', array('name'=>$user['id']));
            } else {
              echo $html->submit('Save This Section (Click this button to save now or you may lose your input)', array('name'=>$user['id']));
            }

            ?></td>
        </tr>
      </table>
		  </div>
		</div>

	<?php $i++;?>
	<?php endforeach; ?>
</div>
		</td>
	</tr>
</table>
</form>

<table width="95%" align="center" bgcolor="#E5E5E5">
  <tr class="tablecell2">
    <td colspan="4" align="center"><form name="submitForm" id="submitForm" method="POST" action="<?php echo $html->url('completeEvaluationMixeval') ?>">
  <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
  <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
  <input type="hidden" name="group_event_id" value="<?php echo $event['group_event_id']?>"/>
  <input type="hidden" name="course_id" value="<?php echo $rdAuth->courseId?>"/>
  <input type="hidden" name="mixeval_id" value="<?php echo $data['Mixeval']['id']?>"/>
  <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $rdAuth->id?>"/>
  <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/><?php echo $html->submit('Submit to Complete the Evaluation') ?></form></td>
    </tr>
</table>
	<script type="text/javascript"> new Rico.Accordion( 'accordion',
								{panelHeight:800,
								 hoverClass: 'mdHover',
								 selectedClass: 'mdSelected',
								 clickedClass: 'mdClicked',
								 unselectedClass: 'panelheader'});

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
