<body onunload="window.opener.document.getElementById('eval_dropdown').onchange()">
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<?php echo $html->script('calendar1')?>
<?php echo $html->script('groups')?>
	  <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Event']['id'])?'add':'edit') ?>">
      <?php echo empty($params['data']['Event']['id']) ? null : $html->hidden('Event.id'); ?>
      <?php echo empty($params['data']['Event']['id']) ? $form->hidden('Event.creator_id', array('value'=>$currentUser['id'])) : $form->hidden('Event.updater_id', array('value'=>$currentUser['id'])); ?>
      <input type="hidden" name="assigned" id="assigned" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
  <td colspan="3" align="center"><?php echo empty($params['data']['Event']['id'])?'Add':'Edit' ?> Evaluation Event</td>
  </tr>
  <tr class="tablecell2">
  	<td id="newtitle_label">Event Title:&nbsp;<font color="red">*</font></td>
  	<td>  	  
      <?php
        echo $form->input('Event.title',array(
            'type' => 'text',
            'name' => "newtitle",
            'id' => 'newtitle',
            'style' => 'width:85%',
            'class' => "validate required TEXT_FORMAT newtitle_msg Invalid_Event_Title_Format.",
            'size' => '50',
            'label' => false
        ));
        echo $ajax->observeField('newtitle', array('update'=>'eventErr', 'url'=>"/events/checkDuplicateTitle", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();"));
      ?>
      <div id='eventErr' class="error">
          <?php
          $fieldValue = isset($this->params['form']['newtitle'])? $this->params['form']['newtitle'] : '';
          $params = array('controller'=>'events', 'data'=>null, 'fieldvalue'=>$fieldValue);
          echo $this->element('events/ajax_title_validate', $params);
          ?>
           <?php echo $form->error('Event.title', 'Title is required.')?>
           <?php echo $form->error('Event.title_unique', 'Duplicate Title found. Please change the title of this event.')?>
      </div>
  	</td>
  	<td id="newtitle_msg" class="error" />
  </tr>
  <tr class="tablecell2">
    <td>Description:&nbsp;</td>
    <td><?php echo $form->textarea('Event.description', array('cols'=>'35', 'style'=>'width:85%;')) ?>  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Evaluation Format:&nbsp;<font color="red">*</font></td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
			<tr><td>
			<a title="Add Simple Evaluation" href="<?php echo $this->webroot.$this->theme;?>simpleevaluations/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Add Simple Evaluation</a> &nbsp;|
			<a title="Add Rubric" href="<?php echo $this->webroot.$this->theme;?>rubrics/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;Add Rubric</a>  &nbsp;|
			<a title="Add Mix Evaluation" href="<?php echo $this->webroot.$this->theme;?>mixevals/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;Add Mix Evaluation</a>
			</td></tr>
      <tr>
          <td height="50" width="50%" align="left" valign="top" >            
            <?php
              echo $this->Form->input('Event.event_template_type_id', array(
                  'type' => 'select',
                  'id' => 'eval_dropdown',
                  'label' => false,
                  'options' => $eventTypes,
                  'onChange' => "new Ajax.Updater('template_table','".
                    $this->webroot.$this->theme."events/eventTemplatesList/'+this.options[this.selectedIndex].value,
                     {onLoading:function(request){Element.show('loading');},
                      onComplete:function(request){Element.hide('loading');},
                      asynchronous:true, evalScripts:true});  return false;",
                  'escape'=>false

              ));
            ?>
            <br>
            <div id='template_table'>
            <?php
              $params = array('controller'=>'events', 'eventTemplates'=>$eventTemplates, 'default'=>$default, 'view'=>0);
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
    <td>Allow Self-Evaluation?:</td>
    <td>
      <?php
        echo $form->input('Event.self_eval', array(
           'type' => 'radio',
           'options' => array('1' => ' - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - Disable'),
           'default' => '0',
           'legend' => false
        ));
      ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Require Student Comments?: </td>
    <td>
      <?php
        echo $form->input('Event.com_req', array(
           'type' => 'radio',
           'options' => array('1' => ' - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - No'),
           'default' => '0',
           'legend' => false
        ));
      ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Due Date:&nbsp;<font color="red">*</font></td>
      <td>
        <table>
          <tr>
           <?php echo $form->input('Event.due_date', array('div'=>false, 'label'=> 'Due Date :', 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;
		     <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
           <?php echo $form->error('Event.due_date', 'Please enter a valid date.')?>
          </tr>
          <tr>
             <a title="Add Rubric" href="<?php echo $this->webroot.$this->theme;?>home/index" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;Add Rubric</a>  &nbsp;
          </tr>
        </table>
	  </td>
    <td>eg. YYYY-MM-DD HH:MM:SS (24 HOUR)</td>
  </tr>
  <tr class="tablecell2">
    <td>Release Date:&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="90%">
      	  <?php echo $form->input('Event.release_date_begin', array('div'=>false, 'label'=>'From :' ,'type'=>'text',  'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.release_date_begin', 'Please enter a valid date.')?>
      	</td>
      </tr>
      <tr>
      	<td width="90%">
      		<?php echo $form->input('Event.release_date_end', array('div'=>false, 'label'=> 'To :', 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.release_date_end', 'Please enter a valid date.')?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td>Groups Assignment:&nbsp;</td>
    <td>
    <?php echo $this->element("groups/group_list_chooser",
            array('all' => $unassignedGroups, 'assigned'=>'',
            'allName' =>  'Avaliable Groups', 'selectedName' => 'Participating Groups',
            'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));
        ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
  <?php echo $html->script('events')?>

<td colspan="3" align="center"><?php echo $this->Form->submit(ucfirst($this->action).' Event', array('div' => false,
  											'onClick' => "processSubmit(document.getElementById('selected_groups'))")) ?></td>
    </tr>
</table>

    </form>
	</td>
  </tr>
</table>
<script type="text/javascript">

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

</script>
