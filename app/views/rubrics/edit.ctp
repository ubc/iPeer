<?php
$readonly = isset($readonly) ? $readonly : false;
$evaluate = isset($evaluate) ? $evaluate : false;
$url = $this->action == 'copy' ? 'add' : $this->action;
?>

<?php echo $this->Form->create('Rubric',
    array('id' => 'frm', 'url' => array('action' => $url),
))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'))?>
<?php echo $this->Form->input('template', array('type' => 'hidden', 'value' => 'horizontal'))?>

<?php echo $this->Form->input('name', array('id' => 'name', 'size'=>'30',
    'readonly' => $readonly, 'label' => __('Name', true)));?>

<?php echo $this->Form->input('lom_max', array('id' => 'LOM',
    'options' => array_combine(range(2,10), range(2,10)),
    'default' => 5,
    'label' => __('Level of Mastery', true),
    'style'=>'width:50px;',
    'disabled' => $readonly));?>
<div class="help-text"><?php __('aka LOM, Evaluation Range (Max 10)')?></div>

<?php echo $this->Form->input('criteria', array('id' => 'criteria',
    'options' => array_combine(range(1,25), range(1,25)),
    'default' => 3,
    'label' => __('Number of Criteria:', true),
    'style'=>'width:50px;',
    'disabled' => $readonly));?>
<div class="help-text"><?php __('Number of Evaluation Aspects (Max 25)')?></div>

<?php echo $this->Form->input('availability', array('id' => 'availability',
    'type' => 'radio',
    'options' => array('public'=>__('Public', true),'private'=>__('Private', true)),
    'label' => __('Availability', true),
    'separator' => '&nbsp;',
    'disabled' => $readonly));?>
<div class="help-text"><?php __('Public lets you share this rubric evaluation with other instructors.')?></div>

<?php echo $this->Form->input('zero_mark', array('id' => 'zero_mark',
    'class'=>'self_enroll',
    'label' => false,
    'before' => $this->Form->label(__('Zero Mark', true)),
    'size' => 50,
    'disabled' => $readonly));?>
<div class="help-text"><?php __('No Marks Given for Level of Mastery of 1')?></div>

<div style="text-align: center">
        <input type="button" name="Back" value="<?php echo __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php switch($action) {
          case 'Add Rubric':
            echo $this->Form->submit(__('Next', true), array('Name'=>'preview', 'div' => false));
            break;
          case 'View Rubric':
            break;
          default:
            echo $this->Form->submit(__('Preview (Update Format)', true), array('Name'=>'preview', 'div' => false));
            echo $this->Form->submit(__('Save', true), array('id' => 'submit-rubric', 'Name' => 'submit', 'div' => false));
          }?>
</div>


<?php if(!empty($data)):?>
<h1 onclick="$('rpreview').toggle();" class="title" id="rubricPreview">
  <span class="ipeer-icon"><?php __('Rubric Preview')?></span>
</h1>

<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;"'; ?>>
<?php echo $this->element('rubrics/ajax_rubric_'.($this->action == 'view' ? 'view' : 'edit'), array('data' => $data, 'readonly' => $readonly, 'evaluate' => $evaluate)); ?>
</div>
<?php endif;?>

<?php echo $this->Form->end()?>

<script type="text/javascript">
$('LOM').observe('change', function(event){
  $('submit-rubric').disable();
})
$('criteria').observe('change', function(event){
  $('submit-rubric').disable();
})
</script>
