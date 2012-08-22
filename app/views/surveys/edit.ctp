<?php $html->script("jquery-ui-timepicker-addon", array("inline"=>false)); ?>
<?php $readonly = isset($readonly) ? $readonly : false;
      $action = ($this->action == 'copy' ? 'add' : $this->action);
?>
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
        <?php echo $this->Form->input('template_id', array('type' => 'hidden', 'value' => $template_id))?>
        <?php endif;?>

        <tr class="tablecell2">
        <?php echo $this->Form->input('course_id', array('label' => __('Assigned Course:', true).'<font color="red">*</font>'));?>
          <td></td>
        </tr>
        <tr class="tablecell2">
        <td><?php echo __('Due Date', true).':<font color="red">*</font>'?></td>
        <td><?php echo $form->input('Survey.due_date', array('div'=>false,
                                                            'label'=>__('Due:', true),
                                                            'type'=>'text',
                                                            'size'=>'50',
                                                            'format'=>array('input'),
                                                            'class'=>'input',
                                                            'style'=>'width:55%;')) ?>
        </td>
        <td><?php __('eg. yyyy-MM-dd HH:mm:ss (24hr time)')?></td>
        </tr>
        <tr class="tablecell2">
        <td><?php echo __('Release From:<font color="red">*</font>', true) ?></td>
        <td><?php echo $form->input('Survey.release_date_begin', array('div'=>false,
                                                            'label'=>__('From:', true),
                                                            'type'=>'text',
                                                            'size'=>'50',
                                                            'format'=>array('input'),
                                                            'class'=>'input',
                                                            'style'=>'width:55%;')) ?>
        </td>
        </tr>
		<tr class="tablecell2">
        <td><?php echo __('Release Until:<font color="red">*</font>', true) ?></td>
	   	<td><?php echo $form->input('Survey.release_date_end', array('div'=>false, 
                                                        	'label'=> __('Until:', true),
        													'format'=> array('input'), 
                                                        	'type'=>'text', 
                                                        	'size'=>'50',
                                                        	'class'=>'input', 
                                                        	'style'=>'width:55%;')) ?>
		</td>
        </tr>
	
        <tr class="tablecell2">
          <td colspan="3" align="center">
        <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php echo $this->Form->submit(ucfirst($this->action).__(' Survey', true), array('div' => false))?></td>
          </tr>
		  </td>
          </tr>
      </table>
    </form>

<script type="text/javascript">
    // change the datetime text input boxes to show the datetimepicker
    initDateTime();
    function initDateTime() {
        var format = { dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss' }
        jQuery("#SurveyDueDate").datetimepicker(format);
        jQuery("#SurveyReleaseDateBegin").datetimepicker(format);
        jQuery("#SurveyReleaseDateEnd").datetimepicker(format);
    }
</script>