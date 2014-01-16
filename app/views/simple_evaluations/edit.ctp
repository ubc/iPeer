<?php
    $readonly = isset($readonly) ? $readonly : false;
    if($this->action == 'copy') $this->action = 'add';
?>
<?php echo $this->Form->create('SimpleEvaluation', array(
            'url' => array(
                'controller' => 'simpleevaluations',
                'action' => $this->action
            ),
        ))
?>
<input type="hidden" name="required" id="required" value="SimpleEvaluationName SimpleEvaluationPointPerMember" />
<!-- Evaluation Name -->
<?php echo $this->Form->input('name', array(
            'size'=>'80',
            'readonly' => $readonly
        )
    )
?>

<!-- Description -->
<?php echo $this->Form->input('description', array(
            'cols' => 60,
            'rows' => 10,
            'readonly' => $readonly
        ))
?>

<!-- Base Point Per Member -->
<?php echo $this->Form->input('point_per_member', array(
            'label' => __('Points Per Member', true),
            'size'=>'5',
            'maxlength'=>'5',
            'readonly' => $readonly,
        ))
?>
    
<!-- Template Availability -->
<?php echo $this->Form->input('availability', array(
            'id' => 'availability',
            'type' => 'radio',
            'options' => array('public' => __('Public', true), 'private' => __('Private', true)),
            'label' => __('Availability', true),
            'separator' => '&nbsp;',
            'disabled' => $readonly
        ))
?>
<div class="help-text"><?php __('Public lets you share this simple evaluation with other instructors.')?></div>
<div style="text-align: center">
    <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
    <?php echo $this->Form->submit(__('Save', true),  array('div' => false)); ?>
</div>
<?php echo $this->Form->end();?>