<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $javascript->link('calendar1')?>
<?php echo $javascript->link('groups')?>
	  <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Event']['id'])?'add':'edit') ?>">
      <?php echo empty($params['data']['Event']['id']) ? null : $html->hidden('Event/id'); ?>
      <?php echo empty($params['data']['Event']['id']) ? $html->hidden('Event/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Event/updater_id', array('value'=>$rdAuth->id)); ?>
      <input type="hidden" name="assigned" id="assigned" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
  <td colspan="3" align="center"><?php echo empty($params['data']['Event']['id'])?'Add':'Edit' ?> Evaluation Event</td>
  </tr>
  <tr class="tablecell2">
  	<td id="newtitle_label">Event Title:&nbsp;<font color="red">*</font></td>
  	<td>
  	  <input type="text" name="newtitle" id="newtitle" style="width:85%;" class="validate required TEXT_FORMAT newtitle_msg Invalid_Event_Title_Format." value="<?php echo empty($params['data']['Event']['title'])? '' : $params['data']['Event']['title'] ?>" size="50">
      <?php echo $ajax->observeField('newtitle', array('update'=>'eventErr', 'url'=>"/events/checkDuplicateTitle", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?>
      <div id='eventErr' class="error">
          <?php
          $fieldValue = isset($this->params['form']['newtitle'])? $this->params['form']['newtitle'] : '';
          $params = array('controller'=>'events', 'data'=>null, 'fieldvalue'=>$fieldValue);
          echo $this->renderElement('events/ajax_title_validate', $params);
          ?>
           <?php echo $html->tagErrorMsg('Event/title', 'Title is required.')?>
           <?php echo $html->tagErrorMsg('Event/title_unique', 'Duplicate Title found. Please change the title of this event.')?>
      </div>
  	</td>
  	<td id="newtitle_msg" class="error" />
  </tr>
  <tr class="tablecell2">
    <td>Description:&nbsp;</td>
    <td><?php echo $html->textarea('Event/description', array('cols'=>'35', 'style'=>'width:85%;')) ?>  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Evaluation Format:&nbsp;<font color="red">*</font></td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
			<tr><td>
			<a title="Add Simple Evaluation" href="<?php echo $this->webroot.$this->themeWeb;?>simpleevaluations/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Add Simple Evaluation</a> &nbsp;|
			<a title="Add Rubric" href="<?php echo $this->webroot.$this->themeWeb;?>rubrics/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;Add Rubric</a>  &nbsp;|
			<a title="Add Mix Evaluation" href="<?php echo $this->webroot.$this->themeWeb;?>mixevals/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;Add Mix Evaluation</a>
			</td></tr>
      <tr>
          <td height="50" width="50%" align="left" valign="top" >
						<select name="data[Event][event_template_type_id]"
							onChange="new Ajax.Updater('template_table','<?php echo $this->webroot.$this->themeWeb?>events/eventTemplatesList/'+this.options[this.selectedIndex].value,
																				 {onLoading:function(request){Element.show('loading');},
																					onComplete:function(request){Element.hide('loading');},
																					asynchronous:true, evalScripts:true});  return false;">
						<?php
//print_r($this->webroot.$this->themeWeb);
						foreach($eventTypes as $row): $eventTemplateType = $row['EventTemplateType']; ?>
							<option value="<?php echo $eventTemplateType['id']?>"
							  <?php
							  if (!empty($params['data']['Event']['event_template_type_id']) && $params['data']['Event']['event_template_type_id'] == $eventTemplateType['id']) {
							       echo 'SELECTED';
							  }
							  ?>
							  ><?php echo $eventTemplateType['type_name']?></option>
						<?php endforeach; ?>
						</select>
						<br>
						<br>
						<div id='template_table'>
								<?php
                  $params = array('controller'=>'events', 'eventTemplates'=>$eventTemplates, 'default'=>$default, 'view'=>0);
                  echo $this->renderElement('events/ajax_event_template_list', $params);
								?>
						</div>
        </td>
       </tr>
     </table>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Allow Self-Evaluation?:</td>
    <td>
		    <input type="radio" name="data[Event][self_eval]" value="1"   > - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="data[Event][self_eval]" value="0" checked > - Disable<br>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Require Student Comments?: </td>
    <td>
		    <input type="radio" name="data[Event][com_req]" value="1"   > - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="data[Event][com_req]" value="0" checked > - No<br>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Due Date:&nbsp;<font color="red">*</font></td>
    <td><?php echo $html->input('Event/due_date', array('size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;
		    <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
       <?php echo $html->tagErrorMsg('Event/due_date', 'Please enter a valid date.')?>
		</td>
    <td>eg. YYYY-MM-DD HH:MM:SS (24 HOUR)</td>
  </tr>
  <tr class="tablecell2">
    <td>Release Date:&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%">FROM:</td>
				<td width="90%">
      		<?php echo $html->input('Event/release_date_begin', array('size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $html->tagErrorMsg('Event/release_date_begin', 'Please enter a valid date.')?>
      	</td>
      </tr>
      <tr>
      	<td width="10%">TO:</td>
      	<td width="90%">
      		<?php echo $html->input('Event/release_date_end', array('size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $html->tagErrorMsg('Event/release_date_end', 'Please enter a valid date.')?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td>Groups Assignment:&nbsp;</td>
    <td>
    <?php echo $this->renderElement("groups/group_list_chooser",
            array('all' => $unassignedGroups,
            'allName' =>  'Avaliable Groups', 'selectedName' => 'Participating Groups',
            'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));
        ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
  <?php echo $javascript->link('events')?>
    <td colspan="3" align="center"><?php echo $html->submit('Add Event', array('onclick' =>
        "return validateEventDates('EventReleaseDateBegin','EventReleaseDateEnd','EventDueDate');"));
        ?></td>
    </tr>
</table>

    </form>
	</td>
  </tr>
</table>
<script type="text/javascript">
<!--

// create calendar object(s) just after form tag closed
// specify form element as the only parameter (document.forms['formname'].elements['inputname']);
// note: you can have as many calendar objects as you need for your application

var cal1 = new calendar1(document.forms[0].elements['data[Event][due_date]']);
cal1.year_scroll = false;
cal1.time_comp = true;

var cal2 = new calendar1(document.forms[0].elements['data[Event][release_date_begin]']);
cal2.year_scroll = false;
cal2.time_comp = true;

var cal3 = new calendar1(document.forms[0].elements['data[Event][release_date_end]']);
cal3.year_scroll = false;
cal3.time_comp = true;

//-->
</script>
