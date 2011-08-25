<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
      <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a name="evaldue" /><?php __('Peer Evaluations Due')?></td>
          <td><div align="right"><!--a href="#evaldue" onClick="showhide('evaldue'); toggle(this);">[-]</a--></div></td>
        </tr>
      </table>
      <div style="background: #FFF;"> <br />
      <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
		  <tr class="tableheader">
			<td width="40%"><?php __('Event')?></td>
			<td width="20%"><?php __('Course')?></td>
			<td width="20%"><?php __('Due Date')?> </td>
			<td width="20%"><?php __('Due In/Late By (red)')?> </td>
		  </tr>	  
  	<?php $i = '0';$currentDate = strtotime('NOW');?>
	  <?php
	  foreach($data as $row): isset($row['comingEvent'])? $comingUpEvent = $row['comingEvent']: $comingUpEvent = null;
	 if(!empty($row['comingEvent']['Event']['release_date_end'])) { 
	 	$releaseEndDate = strtotime( $row['comingEvent']['Event']['release_date_end']);}  
	    if (isset($comingUpEvent['Event']['id']) && $currentDate<=$releaseEndDate && !isset($row['eventSubmitted'])) {?>
		  <tr class="tablecell">
			<td>
			  <?php if ($comingUpEvent['Event']['event_template_type_id'] == 1):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/makeSimpleEvaluation/<?php echo $comingUpEvent['Event']['id']?>;<?php echo $comingUpEvent['Event']['group_id']?>"><?php echo $comingUpEvent['Event']['title'] ?>&nbsp;</a>
			  <?php endif;?>
			  <?php if ($comingUpEvent['Event']['event_template_type_id'] == 2):?>
  			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/makeRubricEvaluation/<?php echo $comingUpEvent['Event']['id']?>;<?php echo $comingUpEvent['Event']['group_id']?>"><?php echo $comingUpEvent['Event']['title'] ?>&nbsp;</a>
			  <?php endif;?>
			  <?php if ($comingUpEvent['Event']['event_template_type_id'] == 3):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/makeSurveyEvaluation/<?php echo $comingUpEvent['Event']['id']?>"><?php echo $comingUpEvent['Event']['title'] ?>&nbsp;</a>
			  <?php endif;?>
			  <?php if ($comingUpEvent['Event']['event_template_type_id'] == 4):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/makeMixevalEvaluation/<?php echo $comingUpEvent['Event']['id']?>;<?php echo $comingUpEvent['Event']['group_id']?>"><?php echo $comingUpEvent['Event']['title'] ?>&nbsp;</a>
			  <?php endif;?>
			</td>
			<td><?php echo $comingUpEvent['Event']['course'] ?>&nbsp;</td>
			<td><?php 
				$dueDate=$row['comingEvent']['Event']['due_date'];
				$timeStamp = strtotime($dueDate);
				echo $this->requestAction('home/formatDate/'.$timeStamp); ?>&nbsp;</td>
			<td><?php
			  if ($comingUpEvent['Event']['is_late']) {
			     echo "<font color=\"red\"><b>" . $comingUpEvent['Event']["days_to_due"] . "</b>".__(' day(s)', true)."!  ";
			   if (isset($comingUpEvent['Event']['penalty'])){
			     echo ($comingUpEvent['Event']['penalty'].'% penalty');
			  }
			  echo "</font>";
			  } else {
			     echo "<b>".$comingUpEvent['Event']['days_to_due']."</b>".__(' day(s)', true);
			  		if ($comingUpEvent['Event']['days_to_due'] <= 2) {
    				print "<b>!</b>";
    			}
			  }
			  ?>&nbsp;</td>
		  </tr>
	  <?php $i++; } ?>
	  <?php endforeach; ?>
	  <?php if ($i == 0):
	          print "<tr class=\"tablecell\"><td colspan=\"4\" align=\"center\"><b>".__('No peer evaluations due at this time')."</b></td></tr>";
	        endif; ?>
		</table>
		<table width="95%"  border="0" cellpadding="0" align="center" cellspacing="0" bgcolor="#E5E5E5">
		  <tr>
			<td><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
			<td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
		  </tr>
		</table>
		<br>
      </div>
        <table width="100%" class="title" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a name="evalsub" /><?php __('Peer Evaluations Submitted')?></td>
            <td><div align="right"> <!--a href="#evalsub" onClick="showhide('evalsub'); toggle(this);">[-]</a--></div></td>
          </tr>
        </table>
      <div style="background: #FFF;"> <br>
      <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
		  <tr class="tableheader">
			<td width="40%"><?php __('Event')?></td>
			<td width="10%"><?php __('Course')?></td>
                        <td width="10%"><?php __('Result')?></td>
			<td width="20%"><?php __('Due Date')?></td>
			<td width="20%"><?php __('Date Submitted')?></td>
		  </tr>
  	<?php $i = 0;?>
	  <?php
	  foreach($data as $row): isset($row['eventSubmitted'])? $eventSubmitted = $row['eventSubmitted']: $eventSubmitted =null;
            //Display if event is submitted, before result release end date and not survey
	    if (isset($eventSubmitted['Event']['id'])&&$currentDate<strtotime($eventSubmitted['Event']['release_date_end'])&&$eventSubmitted['Event']['event_template_type_id'] != 3) {
              //Condition to check; if after result release begin date, display link to result view page: else just display event title.
              $isResultReleased = ($currentDate>=strtotime($eventSubmitted['Event']['release_date_begin']))
          ?>
		  <tr class="tablecell">
			<td>
			  <?php if ($eventSubmitted['Event']['event_template_type_id'] == 1): 
                            if ($isResultReleased):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/studentViewEvaluationResult/<?php echo $eventSubmitted['Event']['id']?>;<?php echo $eventSubmitted['Event']['group_id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $eventSubmitted['Event']['title'] ?>&nbsp;</a>
			  <?php
                            else:
                              echo $eventSubmitted['Event']['title'];
                          endif;endif;?>
			  <?php if ($eventSubmitted['Event']['event_template_type_id'] == 2):
                            if ($isResultReleased):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/studentViewEvaluationResult/<?php echo $eventSubmitted['Event']['id']?>;<?php echo $eventSubmitted['Event']['group_id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $eventSubmitted['Event']['title'] ?>&nbsp;</a>
			  <?php
                            else:
                              echo $eventSubmitted['Event']['title'];
                          endif; endif;?>
			  <?php if ($eventSubmitted['Event']['event_template_type_id'] == 3):
                            if ($isResultReleased):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/makeSurveyEvaluation/<?php echo $eventSubmitted['Event']['id']?>"><?php echo $eventSubmitted['Event']['title'] ?>&nbsp;</a>
			  <?php
                            else:
                              echo $eventSubmitted['Event']['title'];
                          endif; endif;?>
			  <?php if ($eventSubmitted['Event']['event_template_type_id'] == 4):
                            if ($isResultReleased):?>
			  <a href="<?php echo $this->webroot.$this->theme?>evaluations/studentViewEvaluationResult/<?php echo $eventSubmitted['Event']['id']?>;<?php echo $eventSubmitted['Event']['group_id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $eventSubmitted['Event']['title'] ?>&nbsp;</a>
			  <?php
                            else:
                              echo $eventSubmitted['Event']['title'];
                          endif; endif;?>
			</td>
			<td><?php echo $eventSubmitted['Event']['course'] ?>&nbsp;</td>
                        <td><?php $isResultReleased ? __('Released'): __('Not Released'); ?></td>
			<td><?php 
				$due_date = $eventSubmitted['Event']['due_date'];
				$timeStamp = strtotime($due_date);
				echo $this->requestAction('home/formatDate/'.$timeStamp); ?>&nbsp;</td>
			<td><?php 
				$submit_date = $eventSubmitted['Event']['date_submitted'];
				$timeStamp = strtotime($submit_date);
				echo $this->requestAction('home/formatDate/'.$timeStamp); ?>&nbsp;</td>
		  </tr>
	  <?php $i++;} ?>
	  <?php endforeach; ?>
	  <?php if ($i == 0):?>
	          <tr class=\"tablecell\"><td colspan=\"4\" align=\"center\"><b><?php __('No peer evaluations submitted.')?></b></td></tr>
	  <?php endif;?>
		</table>
		<table width="95%"  border="0" cellpadding="0" align="center" cellspacing="0" bgcolor="#E5E5E5">
		  <tr>
			<td><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
			<td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
		  </tr>
		</table>
		<br>
	  </div>
	  </td>
  </tr>
</table>