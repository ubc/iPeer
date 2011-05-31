<?php echo $html->script('calendar1')?>
<?php echo $html->script('groups')?>
<?php $readonly = isset($readonly) ? $readonly : false;?>

<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $this->Form->create('Event', 
                                   array('id' => 'frm',
                                         'url' => array('action' => $this->action.'/'.$event_id),
                                         'inputDefaults' => array('div' => false,
                                                                  'before' => '<td width="200px">',
                                                                  'after' => '</td>',
                                                                  'between' => '</td><td>')))?>
<?php 
echo $this->Form->input('id', array('type' => 'hidden'))?>
    <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td colspan="3" align="center"><?php echo ucfirst($this->action)?> Evaluation Event</td>
    </tr>

    <tr class="tablecell2">
    	<td width="150" id="course_label">Course:</td>
    	<td width="405">
    	
      <?php 
       echo $this->element('courses/course_selection_box', array('coursesList'=>$coursesList, 'courseId' => $course_id, 'view' => false));?>
		
			</td>
    	<td width="243" id="course_msg" class="error"/>
    </tr>
    <tr class="tablecell2">
    <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'input',
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
                                                 'readonly' => $readonly)) ?>
    <td></td>
    </tr>

    <tr class="tablecell2">
    <td>Evaluation Format:&nbsp;<font color="red">*</font></td>
    <td>
      <table border="0" align="left" cellpadding="4" cellspacing="2">
			<tr><td>
			<?php echo $this->Html->link('Add Simple Evaluation', '/simpleevaluations/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>&nbsp;|
			<?php echo $this->Html->link('Add Rubric', '/rubrics/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>&nbsp;|
			<?php echo $this->Html->link('Add Mix Evaluation', '/mixevals/add/pop_up', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"))?>
      </td></tr>

     <tr>
          <td height="50" width="50%" align="left" valign="top" >
						<select name="data[Event][event_template_type_id]" id="eval_dropdown"
							onChange="new Ajax.Updater('template_table','<?php echo $this->webroot.$this->theme?>events/eventTemplatesList/'+this.options[this.selectedIndex].value,
																				 {onLoading:function(request){Element.show('loading');},
																					onComplete:function(request){Element.hide('loading');},
																					asynchronous:true, evalScripts:true});  return false;">
						<?php
//print_r($this->webroot.$this->theme);
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
      if ($event['Event']['self_eval'] == 1) {
		    echo '<input type="radio" name="data[Event][self_eval]" value="1" CHECKED> - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="data[Event][self_eval]" value="0"> - Disable<br>';
      }
      else {
        echo '<input type="radio" name="data[Event][self_eval]" value="1"> - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="data[Event][self_eval]" value="0" CHECKED> - Disable<br>';
      }
    ?>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Require Student Comments?: </td>
    <td>
    <?php
      if ($event['Event']['com_req'] == 1) {
		    echo '<input type="radio" name="data[Event][com_req]" value="1" CHECKED> - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="data[Event][com_req]" value="0"> - No<br>';
      }
      else {
        echo '<input type="radio" name="data[Event][com_req]" value="1"> - Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="data[Event][com_req]" value="0" CHECKED> - No<br>';
      }
    ?>
	  </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
   
    <?php

    echo $form->input('Event.due_date', array('size'=>'50','class'=>'input', 'style'=>'width:75%;', 'type'=>'text', 'label'=>'Due Date:<font color="red">*</font>', 'value'=>$this->data['Event']['due_date'], 'after'=>'')) ?>&nbsp;&nbsp;
    
    
                        <?php // echo $form->input('Search.due_date_begin', array('size'=>'50','class'=>'input',  'label'=> false, 'style'=>'width:75%;','value'=>(isset($sticky['due_date_begin']))? $sticky['due_date_begin']:'')) 
                        ?>
    
    
		    <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
	
   
   
   
   	
    <td>eg. YYYY-MM-DD HH:MM:SS (24 HOUR)</td>
  </tr>
  <tr class="tablecell2">
    <td>Release Date:&nbsp;<font color="red">*</font></td>
  	<td id="release_date_begin">
  	  <table width="100%"><tr align="left">
				<td width="10%">FROM:</td>
				<td width="90%">
      		<?php echo $form->input('Event.release_date_begin', array('size'=>'50','class'=>'input', 'format'=>array('input'), 'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['release_date_begin'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
      	</td>
      </tr>
      <tr>
      	<td width="10%">TO:</td>
      	<td width="90%">
      		<?php echo $form->input('Event.release_date_end', array('size'=>'50','class'=>'input', 'format'=>array('input'),  'type'=>'text','style'=>'width:75%;', 'value'=>$this->data['Event']['release_date_end'])) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
      	</td>
  	  </tr></table>
  	</td>
  	<td>
  	</td>
  </tr>
  <tr class="tablecell2">
    <td>Groups Assignment:&nbsp;</td>
    <td>
    
        <?php
 	 
        echo $this->element("groups/group_list_chooser",
            array('all' => $unassignedGroups, 
            'allName' =>  'Avaliable Groups', 'selectedName' => 'Participating Groups'
          ));
        ?>

    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <?php echo $html->script('events')?>
    <td colspan="3" align="center"><?php echo $form->submit('Edit Event', array('onclick' =>
        "processSubmit(document.getElementById('selected_groups')); return validateEventDates('EventReleaseDateBegin','EventReleaseDateEnd','EventDueDate'); ")); ?></td>
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
</script>