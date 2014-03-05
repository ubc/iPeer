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
    'label' => __('Number of Criteria', true),
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

<?php echo $this->Form->input('view_mode', array('id' => 'view_mode',
    'type' => 'radio',
    'options' => array('criteria'=>__('Criteria', true),'student'=>__('Student', true)),
    'label' => __('View Mode', true),
    'separator' => '&nbsp;',
    'disabled' => $readonly));?>
<div class="help-text">
    <?php __('View mode determines the rubric layout that will be visible to the students.'); ?>
    <br><br>
    <?php __('"Criteria" mode separates the rubric into sections for each criteria. Under each section, all users will be listed for that particular criteria. This mode is useful as it provides easy comparisons between students.'); ?>
    <br><br>
    <?php __('"Student" mode separates the rubric into sections for each student. Under each section, all criteria will be listed for that particular student. This mode is useful as students can be evaluated one at a time.'); ?>
    <br><br>
    <a href="#" onClick="javascript:$('showCriteria').toggle(); return false;">( <?php __('Show/Hide Criteria mode example')?> )</a>
    <a href="#" onClick="javascript:$('showStudent').toggle(); return false;">( <?php __('Show/Hide Student mode example')?> )</a>
</div>
<div id="showCriteria" style="border:1px solid black; display:none; width:80%; margin-left:auto; margin-right:auto;">
    <div style="margin: 0.5em; text-align:center;">
    <?php echo __('<strong>Criteria Example</strong><br><br>'); ?>
    <?php echo $html->image('rubric/Sort_by_Criteria.jpg', array('width'=>'100%')); ?>
    </div>
</div>
<br>
<div id="showStudent" style="border:1px solid black; display:none; width:80%; margin-left:auto; margin-right:auto;">
    <div style="margin: 0.5em; text-align:center;">
    <?php echo __('<strong>Student Example</strong><br><br>'); ?>
    <?php echo $html->image('rubric/Sort_by_Student.jpg', array('width'=>'100%')); ?>
    </div>
</div>
<br>

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
  <span class="ipeer-icon"><?php $readonly ? __('Rubric Preview') : __('Rubric Edit')?></span>
</h1>

<div id="rpreview" style="display: <?php echo empty($data) ? 'none' : 'block' ?>; background: #FFF;">
    <?php if (!$readonly): ?>
        <?php echo $this->element('rubrics/ajax_rubric_edit', array('data' => $data, 'readonly' => $readonly, 'evaluate' => $evaluate)); ?>
    <?php else: ?>
        <?php echo $this->element('evaluations/rubric_eval_form', Toolkit::getRubricEvalDemoData($data));?>
    <?php endif; ?>
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
