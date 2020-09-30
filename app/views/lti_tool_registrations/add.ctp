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
));
echo $this->Form->input('LtiToolRegistration.client_id', array(
    'type' => 'text',
    'label' => 'Client ID',
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.auth_login_url', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.auth_token_url', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.key_set_url', array(
    'size'  => '60',
    'label' => 'Security JWKs Url',
));
echo $this->Form->input('LtiToolRegistration.tool_private_key', array(
    'rows' => '5',
    'cols' => '60',
    'placeholder' => '-----BEGIN RSA PRIVATE KEY-----
...
-----END RSA PRIVATE KEY-----
',
));
echo $this->Form->input('LtiToolRegistration.tool_public_key', array(
    'rows' => '5',
    'cols' => '60',
    'placeholder' => '-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----
',
));
echo $this->Form->input('LtiToolRegistration.user_identifier_field', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.student_number_field', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.term_field', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.canvas_id_field', array(
    'size'  => '60',
));
echo $this->Form->input('LtiToolRegistration.faculty_name_field', array(
    'size'  => '60',
));
?>

    <?php echo $this->Form->end(__('Create', true)); ?>

</div>

