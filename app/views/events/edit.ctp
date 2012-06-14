<?php echo $html->script('calendar1')?>
<?php echo $html->script('groups')?>
    <?php echo $html->script('penalty')?>
<?php $readonly = isset($readonly) ? $readonly : false;?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $this->Form->create('Event', 
                                   array('id' => 'frm',
                                         'url' => array('action' => $this->action),
                                         'inputDefaults' => array('div' => false,
                                                                  'before' => '<td width="200px">',
                                                                  'after' => '</td>',
                                                                  'between' => '</td><td>')))?>
<?php 
echo $this->Form->input('id', array('type' => 'hidden'))?>
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td colspan="3" align="center"><?php echo ucfirst($this->action)?><?php __(' Evaluation Event')?></td>
    </tr>

    <tr class="tablecell2">
      <td width="150" id="course_label"><?php __('Course:')?></td>
      <td width="405">

      <?php
        //echo $this->element('courses/course_selection_box', array('coursesList'=>$coursesList, 'courseId' => $course_id, 'view' => false));
        echo $this->Form->input('course_id', array(
            'options' => $courses,
            'default' => $event['Event']['course_id'],
            'format' => array('input')
        )); 
      ?>

      </td>
      <td width="243" id="course_msg" class="error"/>
    </tr>
    <tr class="tablecell2">
    <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'input', 'label' => __('Title:', true),
                                                 'readonly' => $readonly)) ?>
                                                 
                                                 <td></td>
     <?php /*?>                                            
<!--    	<td id="newtitle_label">Event Title:&nbsp;<font color="red">*</font></td>
    	<td>
    	
    	  <input type="text" name="newtitle" id="newtitle" style="width:85%;" class="validate required TEXT_FORMAT newtitle_msg Invalid_Event_Title_Format." value="<?php echo empty($event['Event']['title'])? '' : $event['Event']['title'] ?>" >
        <?php echo $ajax->observeField('newtitle', array('update'=>'eventErr', 'url'=>"/events/checkDuplicateTitle", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?>
        <div id='eventErr' class="error">
            <?php $params = array('controller'=>'events', 'data'=>null, 'fieldvalue'=>$event['Event']['title']);
                  echo $this->element('events/ajax_title_validate', $params);
            ?>
        </div>
    	</td>
    	<td id="newtitle_msg" class="error" />-->
    	  <?php */?> 
    </tr>

    <tr class="tablecell2">
    <?php echo $this->Form->input('description', array('class'=>'input', 'cols'=>'35', 'style'=>'width:85%;',
                                                 'readonly' => $readonly, 'label' => __('Description:', true))) ?>
    <td></td>
    </tr>

    <tr class="tablecell2">
    <td><?php __('Evaluation Format')?>:&nbsp;<font color="red">*</font></td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
			<tr><td>
			<?php echo $this->Html->link(__('Add Simple Evaluation', true), '/simpleevaluations/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>&nbsp;|
			<?php echo $this->Html->link(__('Add Rubric', true), '/rubrics/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>&nbsp;|
			<?php echo $this->Html->link(__('Add Mix Evaluation', true), '/mixevals/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>
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
                  'escape' => false,
                  'format' => array('input')

              ));
            ?>
            <br>
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
    <td><?php __('Allow Self-Evaluation?')?>:</td>
    <td>
    <?php
      echo $form->input('Event.self_eval', array(
           'type' => 'radio',
           'options' => array('1' => ' - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - Disable'),
           'default' => $event['Event']['self_eval'],
           'legend' => false,
           'format' => array('input')
      ));
    ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Require Student Comments?')?>: </td>
    <td>
    <?php
      echo $form->input('Event.com_req', array(
         'type' => 'radio',
         'options' => array('1' => ' - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - No'),
         'default' => $event['Event']['com_req'],
         'legend' => false,
         'format' => array('input')
      ));
    ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
   
    <?php

    echo $form->input('Event.due_date', array('size'=>'50','class'=>'input', 'style'=>'width:75%;', 'type'=>'text', 'label'=>__('Due Date', true).':<font color="red">*</font>', 'value'=>$this->data['Event']['due_date'], 'after'=>'')) ?>&nbsp;&nbsp;
    <?php // echo $form->input('Search.due_date_begin', array('size'=>'50','class'=>'input',  'label'=> false, 'style'=>'width:75%;','value'=>(isset($sticky['due_date_begin']))? $sticky['due_date_begin']:''))
    ?>
    <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
    <?php echo $form->error('Event.due_date', __('Please enter a valid date.', true))?>  	
    <td><?php __('eg. YYYY-MM-DD HH:MM:SS (24 HOUR)')?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Evaluation Release Date')?>:&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%"><?php __('FROM:')?></td>
				<td width="90%">
      		<?php echo $form->input('Event.release_date_begin', array('size'=>'50','class'=>'input', 'format'=>array('input'), 'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['release_date_begin'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
                <?php echo $form->error('Event.release_date_begin', __('Please enter a valid date.', true))?>
      	</td>
      </tr>
      <tr>
      	<td width="10%"><?php __('TO:')?></td>
      	<td width="90%">
      		<?php echo $form->input('Event.release_date_end', array('size'=>'50','class'=>'input', 'format'=>array('input'),  'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['release_date_end'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
                <?php echo $form->error('Event.release_date_end', __('Please enter a valid date.', true))?>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Result Release Date')?>:&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%"><?php __('FROM:')?></td>
				<td width="90%">
      		<?php echo $form->input('Event.result_release_date_begin', array('size'=>'50','class'=>'input', 'format'=>array('input'), 'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['result_release_date_begin'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal4.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
                <?php echo $form->error('Event.result_release_date_begin', __('Please enter a valid date.', true))?>
        </td>
      </tr>
      <tr>
      	<td width="10%"><?php __('TO:')?></td>
      	<td width="90%">
      		<?php echo $form->input('Event.result_release_date_end', array('size'=>'50','class'=>'input', 'format'=>array('input'),  'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['result_release_date_end'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal5.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
                <?php echo $form->error('Event.result_release_date_end', __('Please enter a valid date.', true))?>
        </td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  
  <tr class="tablecell2">
  <td><?php __('Late Penalty:')?></td>
  <td>
    <?php

      echo $form->input('Event.penalty', array(
           'type' => 'radio',
           'options' => array('1' => ' - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - No'),
           'default' => (bool)$penaltyDays,
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
            'div'=>false, 'format' => array('input'), 'onBlur' => 'maximum(this)', 'label' => false, 'type'=>'text', 'size' => 5, 'value' =>  isset($penaltySetup['percentagePerDay'])?$penaltySetup['percentagePerDay']:'20'))?> % per day
       <span class = "warning"></span>
       </div>
       <div class = 'penaltyInput'>
       		<label class = "penaltyLabel">For</label> <?php echo $form->input('PenaltySetup.numberOfDays', array(
            'div'=>false, 'onBlur' =>'calculateDays()', 'format' => array('input'),  'label' => false, 'type'=>'text', 'size' => 5, 'value' =>  (isset($penaltyDays) && ($penaltyDays > 0))?$penaltyDays:'2'))?> days
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
            'div'=>false, 'format' => array('input'), 'label' => false, 'type'=>'text', 'size' => 5, 'style' => 'width:50px;', 'onBlur' => 'maximum(this); minimum()', 'value' =>  isset($penaltySetup['penaltyAfter'])?$penaltySetup['penaltyAfter']:'100'))?> % afterwards
  	 <span class = "warning"></span>
  	 </div>
  	 </div>
  </td>
  <td>

 </td>
  </tr>  
  
  <tr class="tablecell2">
    <td><?php __('Groups Assignment:')?>&nbsp;</td>
    <td>
    
        <?php
        echo $this->element("groups/group_list_chooser",
            array('all' => $unassignedGroups,   'assigned'  =>$assignedGroups,
            'allName' =>  __('Avaliable Groups', true), 'selectedName' => __('Participating Groups', true)
          ));
        ?>

    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <?php echo $html->script('events')?>
    <td colspan="3" align="center"><?php echo $form->submit(__('Edit Event', true), array('onclick' =>
        "processSubmit(document.getElementById('selected_groups')); return validate(); ")); ?></td>
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
var penalty = <?php echo (!empty($penalty))?json_encode($penalty):'new Array(0)'?>;
	var perc = '';
	var k = 0;

	for(k = 0; k < <?php echo $penaltyDays;?>; k++) {
			if (penalty[i]['Penalty']["days_late"]>0){
				perc = penalty[i]['Penalty']["percent_penalty"];
				addDay(perc);
			}
		}
	
 	$('PenaltySetupType').value = '<?php echo isset($penaltyType)?$penaltyType:'simple';?>'; 
	var display = <?php echo $penaltyDays;?>;
	if(display) {
		$('penalty').show();
	}else{
		$('penalty').hide();
	}
 	if(display == 0){
 		 
		addDay(20);
		addDay(40);
 	 	}
 	});
	
 	displayPenalty();

function validate() {
	//if (!validateEventDates('EventReleaseDateBegin','EventReleaseDateEnd','EventDueDate','EventResultReleaseDateBegin','EventResultReleaseDateEnd')){return false;}
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
