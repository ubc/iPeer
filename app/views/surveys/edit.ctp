<?php $readonly = isset($readonly) ? $readonly : false;
      $action = ($this->action == 'copy' ? 'add' : $this->action);
?>
<!--<?php //echo $html->script('events'); // For vallidation of dates?>-->
<?php echo $html->script('calendar1')?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
  <td>
  <?php echo $this->Form->create('Survey', 
                                 array('id' => 'frm',
                                       'url' => array('action' => $action),
                                       'inputDefaults' => array('div' => false,
                                                                'before' => '<td width="150px">',
                                                                'after' => '</td>',
                                                                'between' => '</td><td>')))?>
  <?php echo $this->Form->input('id', array('type' => 'hidden'));?>

	  <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php echo ucfirst($this->action)?> <?php __('Survey')?> </td>
          </tr>
        <tr class="tablecell2">
        <?php echo $this->Form->input('name', array('size'=>'50', 'class'=>'input',
                                                    'readonly' => $readonly)) ?>
            <div id="surveyErr">
              <?php
              /*$fieldValue = isset($this->params['form']['name'])? $this->params['form']['name'] : '';
              $params = array('controller'=>'surveys', 'data'=>null, 'fieldvalue'=>$fieldValue);
            	echo $this->element('surveys/ajax_survey_validate', $params);*/
              ?>
            </div>
            <?php //echo $ajax->observeField('name', array('update'=>'surveyErr', 'url'=>"/surveys/checkDuplicateName", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?> </td>
          <td width="35%"> <?php __('i.e. "CS100 Experience"')?> </td>
        </tr>

        <?php if('add' == $this->action):?>
        <tr class="tablecell2">
        <?php echo $this->Form->input('template_id', array('empty' => __('(No Template)', true)))?>
        <td>&nbsp;</td>
        </tr>
        <?php elseif('copy' == $this->action):?>
        <?php echo $this->Form->input('template_id', array('type' => 'hidden',
                                                           'value' => $template_id))?>
        <?php endif;?>

        <tr class="tablecell2">
        <?php echo $this->Form->input('course_id', array('label' => __('Assigned Course:', true).'<font color="red">*</font>'));?>
          <td>&nbsp;</td>
        </tr>
        <tr class="tablecell2">
        <td><?php echo __('Due Date', true).':<font color="red">*</font>'?></td>
        <td><?php echo $form->input('Survey.due_date', array('div'=>false, 
        														'label'=>__('From :', true),
        														'type'=>'text',
        	  													'size'=>'50',
        														'format'=>array('input'),
        	  													'class'=>'input',
        	 													'style'=>'width:55%;')) ?>&nbsp;&nbsp;&nbsp;
				<a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');">
				<?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>
          <!--<td><?php //echo $html->input('Survey/due_date', array('size'=>'25','class'=>'input')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>-->
          <td> <?php __('eg. YYYY-MM-DD HH:MM (24 HOUR)')?> </td>
        </tr>
		
        <tr class="tablecell2">
        <td><?php echo __('Release Date', true).':<font color="red">*</font>' ?>
        <td><?php echo __('From :', true)?>
        	<?php echo $form->input('Survey.release_date_begin', array('div'=>false, 
        														'label'=>__('From :', true),
        														'type'=>'text',
        	  													'size'=>'50',
        														'format'=>array('input'),
        	  													'class'=>'input',
        	 													'style'=>'width:48.5%;')) ?>&nbsp;&nbsp;&nbsp;
				<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');">
				<?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
		    
		<br><?php echo 'To :'?>
	   		<?php echo $form->input('Survey.release_date_end', array('div'=>false, 
                                                        	'label'=> __('Due Date :', true),
        													'format'=> array('input'), 
                                                        	'type'=>'text', 
                                                        	'size'=>'50',
                                                        	'class'=>'input', 
                                                        	'style'=>'width:51.5%;')) ?>&nbsp;&nbsp;
		    <a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');">
		    <?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
                                                               	
		</td>              
          <!--<td><?php //echo $html->input('Survey/due_date', array('size'=>'25','class'=>'input')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>-->
          <td><?php __(' eg. YYYY-MM-DD HH:MM (24 HOUR) ')?></td>
        </tr>
	
        <tr class="tablecell2">
          <td colspan="3" align="center">
        <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php echo $this->Form->submit(ucfirst($this->action).__(' Survey', true), array('div' => false))?></td>
          </tr>
		  </td>
          </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
    </form>
	</td>
  </tr>
</table>

<?php //echo $html->script('calendar1')?>
<script type="text/javascript">

// create calendar object(s) just after form tag closed
// specify form element as the only parameter (document.forms['formname'].elements['inputname']);
// note: you can have as many calendar objects as you need for your application

var cal1 = new calendar1(document.forms[0].elements['data[Survey][due_date]']);
cal1.year_scroll = false;
cal1.time_comp = true;

var cal2 = new calendar1(document.forms[0].elements['data[Survey][release_date_begin]']);
cal2.year_scroll = false;
cal2.time_comp = true;

var cal3 = new calendar1(document.forms[0].elements['data[Survey][release_date_end]']);
cal3.year_scroll = false;
cal3.time_comp = true;
</script>