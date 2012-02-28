<div class='install'>
<h3><?php __('Step 4: System Parameters Configuration')?> </h3>

<?php
echo $this->Form->create(
  'InstallValidationStep4',
  array('url' => '/install/install4')
);

echo $this->Form->input(
  'absolute_url', 
  array(
    'value' => "$absolute_url",
    'label' => 'Absolute URL ',
  )
);

echo $this->Form->input(
  'domain', 
  array(
    'value' => "$domain_name",
    'label' => 'Domain',
  )
);

echo $this->Form->input(
  'super_admin', 
  array(
    'value' => "root",
    'label' => 'Super admin username',
  )
);

echo $this->Form->input(
  'password', 
  array(
    'label' => 'Super admin password',
  )
);

echo $this->Form->input(
  'confirm_password', 
  array(
    'type' => 'password',
    'label' => 'Super admin confirm password',
  )
);

echo $this->Form->input(
  'admin_email', 
  array(
    'label' => 'Super admin email',
  )
);

echo $this->Form->input(
  'login_text', 
  array(
    'type' => 'textarea',
    'value' => '<a href="http://www.ubc.ca" target="_blank">UBC</a>',
    'label' => 'Custom Login Text',
  )
);

echo $this->Form->input(
  'contact_info', 
  array(
    'type' => 'textarea',
    'value' => 'Please enter your custom contact info. HTML tabs are acceptable.',
    'label' => 'Custom Contact Info',
  )
);

echo $form->submit('Done!', array('id'=>'next', 'div' => 'dbform')); 

echo $this->Form->end();

?>
</div>
