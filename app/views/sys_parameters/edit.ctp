<div id='sysParam'>
<?php
echo $this->Form->create('Sysparameter', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('SysParameter.id', array('type' => 'text', 'readonly' => true));
echo $this->Form->input('SysParameter.parameter_code', array('autocomplete' => 'off'));
echo $this->Form->input('SysParameter.parameter_type', array('options' => $types));

$parameter_value_attr = array('type' => $parameter_value_input_type, 'autocomplete' => 'off');
if ($parameter_value_input_type=='password') {
    $parameter_value_attr['readonly'] = 'readonly';
}
echo $this->Form->input('SysParameter.parameter_value', $parameter_value_attr);
echo $this->Form->input('SysParameter.description');
echo $this->Form->submit(__('Save', true));
echo $this->Form->end();
?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){

    // Change field type to password if it's Encrypted String
    jQuery('#SysParameterParameterType').change(function(){
        if (jQuery(this).val() == "E"){
            jQuery('#SysParameterParameterValue').prop('type', 'password');
        }
        else {
            jQuery('#SysParameterParameterValue').prop('type', 'text');
        }
    });

    // Warn user that saving will overwrite the value
    jQuery('#SysParameterParameterValue, #SysParameterParameterType').focus(function(){
        if (jQuery('#SysParameterParameterValue').is('[readonly]')){
            if (confirm('<?php echo __('Note that if you continue and then save this Sys Parameter, the previous value ' .
                                   'will be overwritten (even if empty). Would you like to proceed?', true); ?>')) {
                jQuery('#SysParameterParameterValue').val('');
                jQuery('#SysParameterParameterValue').removeAttr('readonly');
            }
            else {
                $(this).blur();
                return false;
            }
        }
    });

});
</script>