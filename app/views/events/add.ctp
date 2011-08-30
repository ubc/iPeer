<?php echo $html->script('penalty')?>
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
  <td colspan="3" align="center"><?php echo empty($params['data']['Event']['id'])?__('Add'):__('Edit') ?><?php __(' Evaluation Event')?></td>
  </tr>
  <tr class="tablecell2">
  	<td id="newtitle_label"><?php __('Event Title')?>:&nbsp;<font color="red">*</font></td>
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
           <?php echo $form->error('Event.title', __('Title is required.', true))?>
           <?php echo $form->error('Event.title_unique', __('Duplicate Title found. Please change the title of this event.', true))?>
      </div>
  	</td>
  	<td id="newtitle_msg" class="error" />
  </tr>
  <tr class="tablecell2">
    <td><?php __('Description:')?>&nbsp;</td>
    <td><?php echo $form->textarea('Event.description', array('cols'=>'35', 'style'=>'width:85%;')) ?>  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Evaluation Format:')?>&nbsp;<font color="red">*</font></td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
			<tr><td>
			<a title="<?php __("Add Simple Evaluation")?>" href="<?php echo $this->webroot.$this->theme;?>simpleevaluations/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;<?php __('Add Simple Evaluation')?></a> &nbsp;|
			<a title="<?php __('Add Rubric')?>" href="<?php echo $this->webroot.$this->theme;?>rubrics/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;<?php __('Add Rubric')?></a>  &nbsp;|
			<a title="<?php __('Add Mix Evaluation')?>" href="<?php echo $this->webroot.$this->theme;?>mixevals/add/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;" >&nbsp;<?php __('Add Mix Evaluation')?></a>
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
    <td><?php __('Allow Self-Evaluation?:')?></td>
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
    <td><?php __('Require Student Comments?:')?> </td>
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
    <td><?php __('Due Date')?>:&nbsp;<font color="red">*</font></td>
    <td><?php echo $form->input('Event.due_date', array('div'=>false, 'label'=>false, 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;
		    <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
       <?php echo $form->error('Event.due_date', __('Please enter a valid date.', true))?>
		</td>
    <td><?php __('eg. YYYY-MM-DD HH:MM:SS (24 HOUR)')?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Evaluation Release Date:')?>&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="90%">
      	  <?php echo $form->input('Event.release_date_begin', array('div'=>false, 'label'=>'From :' ,'type'=>'text',  'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.release_date_begin', __('Please enter a valid date.', true))?>
      	</td>
      </tr>
      <tr>
      	<td width="90%">
      		<?php echo $form->input('Event.release_date_end', array('div'=>false, 'label'=> __('To :', true), 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.release_date_end', __('Please enter a valid date.', true))?>
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
				<td width="90%">
      	  <?php echo $form->input('Event.result_release_date_begin', array('div'=>false, 'label'=>'From :' ,'type'=>'text',  'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal4.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.result_release_date_begin', __('Please enter a valid date.', true))?>
      	</td>
      </tr>
      <tr>
      	<td width="90%">
      		<?php echo $form->input('Event.result_release_date_end', array('div'=>false, 'label'=> __('To :', true), 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal5.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
          <?php echo $form->error('Event.result_release_date_end', __('Please enter a valid date.', true))?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
    <tr class="tablecell2">
  <td><?php __('Late Penalty:')?></td>
  <td style="width: 600px">
    <?php

      echo $form->input('Event.penalty', array(
           'type' => 'radio',
           'options' => array('1' => ' - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - No'),
           'default' => 0,
           'legend' => false,
      'format' => array('input'), 
       'onChange'=>"javascript:$('penalty').toggle();return false;"
        ));
      ?>
     <div id = 'penalty' style ='width:550px; display:none;'>
            <?php echo $form->hidden('PenaltySetup.type', array('value' => 'simple', 'div' => false, 'format' => array('input'),  'label' => false));?>
     <div id="simplePenalty" width="400px">
         
              
    	 <div style="text-align:right"><a href="#" onClick="javascript:$('PenaltySetupType').value = 'advanced'; displayPenalty();
        	  return false;">( <?php __('Use Advanced Penalty Scale')?> )</a>
    	 </div>
 
       <div class = 'penaltyInput'>
       		<label class = "penaltyLabel">Deduct</label> <?php echo $form->input('PenaltySetup.percentagePerDay', array(
            'div'=>false, 'format' => array('input'), 'onBlur' => 'maximum(this)', 'label' => false, 'type'=>'text', 'size' => 5, 'value' =>  20))?> % per day
       <span class = "warning"></span>
       </div>
       <div class = 'penaltyInput'>
       		<label class = "penaltyLabel">For</label> <?php echo $form->input('PenaltySetup.numberOfDays', array(
            'div'=>false, 'onBlur' =>'calculateDays()', 'format' => array('input'),  'label' => false, 'type'=>'text', 'size' => 5, 'value' => 2))?> days
			 <span class = "warning"></span>
			 </div>
			 
       </div>
       <div  id = 'penaltyAdvanced' style="display:none; margin-bottom: 1em;">
        <div style="text-align:right"><a href="#" onClick="javascript:$('PenaltySetupType').value = 'simple'; displayPenalty(); return false;">( <?php __('Use Simple Penalty Scale')?> )</a>
      
      </div>
       <table  id = 'penaltyTable'>
     
       <tr>
       <td width="65px" height="20px">Days late</td>
       <td>Penalty %</td>
       <td></td>
       </tr>
       </table>
       <a onClick = "addDay()" class="text-button" style="margin-left: .5em">Add</a>
       <a onClick = "removeDay()" class="text-button" style="margin-left: 3.8em">Remove</a>

       </div>
  	 
  	 <div class = 'penaltyInput'>
  	    <label class = "penaltyLabel">Deduct</label> <?php echo $form->input('PenaltySetup.penaltyAfter', array(
            'div'=>false, 'format' => array('input'), 'label' => false, 'type'=>'text', 'size' => 5, 'style' => 'width:50px;', 'onBlur' => 'maximum(this); minimum()', 'value' =>  100))?> % afterwards
  	 <span class = "warning"></span>
  	 </div>
  	 </div>
  </td>
  <td>

 </td>
  </tr>  
  </tr>  
  <tr class="tablecell2">
    <td><?php __('Groups Assignment:')?>&nbsp;</td>
    <td>
    <?php echo $this->element("groups/group_list_chooser",
            array('all' => $unassignedGroups, 'assigned'=>'',
            'allName' =>  __('Avaliable Groups', true), 'selectedName' => __('Participating Groups', true),
            'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));
        ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
  <?php echo $html->script('events')?>

<td colspan="3" align="center"><?php echo $this->Form->submit(ucfirst($this->action).__(' Event', true), array('div' => false,
  											'onClick' => "processSubmit(document.getElementById('selected_groups')); return validate();")) ?></td>
    </tr>
</table>

    </form>
	</td>
  </tr>
</table>

<style type="text/css">
#penalty{width: 500px; padding: 0 0 1em 1em; }
#penalty a{color: #fa7e04;}
#penalty a:hover{color: #333;}
.percentage{width:50px;}
.penaltyLabel{width:70px; float:left; margin-top:0.2em}
div.penaltyInput{padding:.2em;}
.numberOfDays{ padding-left:1em;}
.warning{float: right; margin-top:0.25em; width: 370px; color:red;}
td.warning{padding-left:2em;}
</style>
<script type="text/javascript">


Event.observe(window, 'load', function(){

	addDay(20);
	addDay(40);

});

function validate() {
	if (!validateEventDates('EventReleaseDateBegin','EventReleaseDateEnd','EventDueDate','EventResultReleaseDateBegin','EventResultReleaseDateEnd')){return false;}
	if (!validatePenalty()) {return false;}
}

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

var cal4 = new calendar1(document.forms[0].elements['data[Event][result_release_date_begin]']);
cal4.year_scroll = false;
cal4.time_comp = true;

var cal5 = new calendar1(document.forms[0].elements['data[Event][result_release_date_end]']);
cal5.year_scroll = false;
cal5.time_comp = true;

</script>
