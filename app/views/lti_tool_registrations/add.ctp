<div class="ltiToolRegistrations form">

    <div class="button-row">
        <ul>
            <li><?php echo $this->Html->link(__('Back to index', true), array('action' => 'index')); ?></li>
        </ul>
    </div>

    <p><span style="color:red;">*</span><?php __('All fields required'); ?></p>

<?php
echo $this->Form->create('Ltitoolregistration');

echo $this->Form->input('LtiToolRegistration.iss', array(
    'type'  => 'text',
    'label' => 'Issuer',
    'size'  => '60',
    'placeholder' => 'Ex.: https://canvas.instructure.com',
));
echo $this->Form->input('LtiToolRegistration.client_id', array(
    'type' => 'text',
    'label' => 'Client ID',
    'size'  => '60',
    'placeholder' => 'Ex.: 10000000000001',
));
echo $this->Form->input('LtiToolRegistration.auth_login_url', array(
    'size'  => '60',
    'placeholder' => 'Ex.: http://canvas.docker/api/lti/authorize_redirect',
));
echo $this->Form->input('LtiToolRegistration.auth_token_url', array(
    'size'  => '60',
    'placeholder' => 'Ex.: http://canvas.docker/login/oauth2/token',
));
echo $this->Form->input('LtiToolRegistration.key_set_url', array(
    'size'  => '60',
    'placeholder' => 'Ex.: http://canvas.docker/api/lti/security/jwks',
));
echo $this->Form->input('LtiToolRegistration.kid', array(
    'size'  => '60',
    'placeholder' => 'Ex.: 2018-06-18T22:33:20Z',
));
echo $this->Form->input('LtiToolRegistration.tool_private_key_file', array(
    'size'  => '60',
    'placeholder' => 'Ex.: app/config/lti13/tool.private.key',
));
?>

    <div class="add-delete-wrapper">

<?php
$i = 0;
$remove_button = '<button type="button" class="delete-button" style="background-position:0;padding:0 1px 3px 16px;">&nbsp;</button>';
echo $this->Form->input('LtiPlatformDeployment.0.deployment', array(
    'label' => 'Deployment ID',
    'id'    => null,
    'size'  => '60',
    'after' => $remove_button,
));
foreach ((array)@$this->data['LtiPlatformDeployment'] as $i => $row) {
    if ($i > 0) {
        echo $this->Form->input('LtiPlatformDeployment.' . $i . '.deployment', array(
            'label' => 'Deployment ID',
            'id'    => null,
            'size'  => '60',
            'after' => $remove_button,
        ));
    }
}
?>

    </div>

    <div class="input text">
        <label>&nbsp;</label>
        <button type="button" class="add-button">Add deployment ID</button>
    </div>

    <?php echo $this->Form->end(__('Create', true)); ?>

</div>

<script type="text/javascript">
jQuery(document).ready(function() {
    var max = 10,
        $addButton = jQuery('.add-button'),
        $wrapper = jQuery('.add-delete-wrapper'),
        i = <?php echo ++$i; ?>,
        html = '<div class="input text required"><label>Deployment ID</label><input type="text" size="60" name="data[LtiPlatformDeployment][][deployment]" value=""><?php echo $remove_button; ?></div>';

    // Click add button
    jQuery($addButton).on('click', function() {
        if (i < max) {
            jQuery($wrapper).append(html);
            i++;
        }
    });
    
    // Click remove button
    jQuery($wrapper).on('click', '.delete-button', function(e) {
        e.preventDefault();
        jQuery(this).parent('div').remove();
        i--;
    });
});
</script>
