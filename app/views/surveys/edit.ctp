<?php $html->script("jquery-ui-timepicker-addon", array("inline"=>false)); ?>
<?php $readonly = isset($readonly) ? $readonly : false;
      $action = ($this->action == 'copy' ? 'add' : $this->action);
?>
<?php echo $this->Form->create('Survey', array(
    'id' => 'frm',
    'url' => array('action' => $action),
))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>

<?php echo $this->Form->input('name', array('size'=>'50', 'class'=>'input',
    'readonly' => $readonly)) ?>
<div id="surveyErr"></div>

<?php if('add' == $this->action):?>
    <?php echo $this->Form->input('template_id', array('empty' => __('(No Template)', true)))?>
<?php elseif('copy' == $this->action):?>
    <?php echo $this->Form->input('template_id', array('type' => 'hidden', 'value' => $template_id))?>
<?php endif;

echo $this->Form->input('course_id', array(
    'label' => __('Assigned Course:', true).'<font color="red">*</font>'));
echo $form->input('Survey.due_date', array(
    'label'=> __('Due Date', true).':<font color="red">*</font>',
    'type'=>'text',
    'size'=>'40',
    'class'=>'input',
    ));

echo $form->input('Survey.release_date_begin', array(
    'label'=>__('Release From:<font color="red">*</font>', true),
    'type'=>'text',
    'size'=>'40',
    'class'=>'input',
    ));

echo $form->input('Survey.release_date_end', array(
    'label'=> __('Release Until:<font color="red">*</font>', true),
    'type'=>'text',
    'size'=>'40',
    'class'=>'input',
    )) ?>

    <div style="text-align: center">
        <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php echo $this->Form->submit(ucfirst($this->action).__(' Survey', true), array('div' => false))?>
    </div>

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
