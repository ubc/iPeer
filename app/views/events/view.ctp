<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
  <?php echo $html->script('groups')?>
	  <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($event['Event']['id'])?'add':'edit') ?>">
      <?php echo empty($event['Event']['id']) ? null : $this->Form->hidden('id'); ?>

      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center"><?php __('View Evaluation Event')?></td>
    </tr>
    <tr class="tablecell2">
    	<td width="150" id="course_label"><?php __('Course:')?></td>
    	<td width="405">
      <?php
        $params = array('controller'=>'courses', 'coursesList'=>$coursesList, 'courseId'=>$course_id, 'view'=>1);
        echo $this->element('courses/course_selection_box', $params);
      ?>
			</td>
    	<td width="243" id="course_msg" class="error"/>
    </tr>
    <tr class="tablecell2">
    	<td id="newtitle_label"><?php __('Event Title:')?>&nbsp;</td>
    	<td>
    	  <?php echo $event['Event']['title']; ?>
    	</td>
    	<td id="newtitle_msg" class="error" />
    </tr>
  <tr class="tablecell2">
    <td><?php __('Description:')?>&nbsp;</td>
    <td><?php echo $event['Event']['description']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Evaluation Format:')?>&nbsp;</td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
      <tr>
          <td width="50%" align="left" valign="top" >
						<?php
						foreach($eventTypes as $row):
						   $eventTemplateType = $row['EventTemplateType'];
							 if (!empty($event['Event']['event_template_type_id']) && $event['Event']['event_template_type_id'] == $eventTemplateType['id']) {
							    echo $eventTemplateType['type_name'];
							 }
						endforeach; ?>
						<br>
						<br>
						<div id='template_table'>
								<?php
								$params = array('controller'=>'events', 'eventTemplates'=>$eventTemplates, 'default'=>$default, 'model'=>$model, 'templateID'=>$event['Event']['template_id'], 'view'=>1);
								echo $this->element('events/ajax_event_template_list', $params);
								?>
						</div>
        </td>
       </tr>
     </table>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Allow Self-Evaluation?:')?></td>
    <td>
      <?php echo $event['Event']['self_eval']==1? 'Enable' : 'Disable'; ?>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Require Student Comments?:')?> </td>
    <td>
      <?php echo $event['Event']['com_req']==1? 'Yes' : 'No'; ?>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Due Date:')?>&nbsp;</td>
    <td><?php echo Toolkit::formatDate($event['Event']['due_date']) ?>
		</td>
    <td></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Evaluation Release Date:')?>&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%"><?php __('FROM:')?></td>
				<td width="90%">
				  <?php echo Toolkit::formatDate($event['Event']['release_date_begin']) ?>
      	</td>
      </tr>
      <tr>
      	<td width="10%"><?php __('TO:')?></td>
      	<td width="90%">
				  <?php echo Toolkit::formatDate($event['Event']['release_date_end']) ?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Result Release Date:')?>&nbsp;<font color="red">*</font></td>
  	<td id="result_release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%"><?php __('FROM:')?></td>
				<td width="90%">
				  <?php echo Toolkit::formatDate($event['Event']['result_release_date_begin']) ?>
      	</td>
      </tr>
      <tr>
      	<td width="10%"><?php __('TO:')?></td>
      	<td width="90%">
				  <?php echo Toolkit::formatDate($event['Event']['result_release_date_end']) ?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td valign="top"><?php __('Groups Assignment:')?>&nbsp;</td>
    <td>
      <?php
        $params = array('controller'=>'events', 'data'=>$event['Group'], 'event_id' => $event_id, 'popup' => 'y');
        echo $this->element('events/event_groups_detail', $params);
      ?>
		</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><?php
        echo $html->link(__('Edit this Event', true), '/events/edit/'.$event['Event']['id']); ?> | <?php echo $html->link(__('Back to Event Listing', true), '/events/index/'.$course_id); ?></td>
      </tr></table>
    </td>
    <td align="right" width="55%"></td>
  </tr>
</table>
    </form>
	</td>
  </tr>
</table>

