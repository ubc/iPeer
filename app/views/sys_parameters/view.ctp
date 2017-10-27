<div id="sysParam">
<?php
echo $this->Form->input('SysParameter.id', array('type' => 'text', 'readonly' => true));
echo $this->Form->input('SysParameter.parameter_code', array('readonly' => true));
echo $this->Form->input('SysParameter.parameter_type', array('options' => $types, 'disabled' => true));
echo $this->Form->input('SysParameter.parameter_value', array('type' => $parameter_value_input_type, 'readonly' => true));
echo $this->Form->input('SysParameter.description', array('readonly' => true));
echo $this->Form->input('SysParameter.created', array('type' => 'text', __('Create Date', true), 'readonly' => true));
echo $this->Form->input('SysParameter.modified', array('type' => 'text', __('Update Date', true), 'readonly' => true));
?>
</div>
