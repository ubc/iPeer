<div id='sysParam'>
<?php
echo $this->Form->create('Sysparameter', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('SysParameter.id', array('type' => 'text', 'readonly' => true));
echo $this->Form->input('SysParameter.parameter_code');
echo $this->Form->input('SysParameter.parameter_value', array('type' => 'text'));
echo $this->Form->input('SysParameter.parameter_type', array('options' => $types));
echo $this->Form->input('SysParameter.description');
echo $this->Form->submit(__('Save', true));
echo $this->Form->end();
?>
</div>
