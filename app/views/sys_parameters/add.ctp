<div id='sysParam'>
<?php
echo $this->Form->create('Sysparameter', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('SysParameter.parameter_code', array('autocomplete' => 'off'));
echo $this->Form->input('SysParameter.parameter_type', array('options' => $types));
echo $this->Form->input('SysParameter.parameter_value', array('type' => 'text', 'autocomplete' => 'off'));
echo $this->Form->input('SysParameter.description');
echo $this->Form->submit(__('Save', true));
echo $this->Form->end();
?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('#SysParameterParameterType').change(function(){
        if (jQuery(this).val() == "E"){
            jQuery('#SysParameterParameterValue').prop('type', 'password');
        }
        else {
            jQuery('#SysParameterParameterValue').prop('type', 'text');
        }
    });

});
</script>