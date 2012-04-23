<div class='install'>
<h3><?php __('Step 4: System Parameters Configuration')?> </h3>

<?php
echo $this->Form->create(
  'InstallValidationStep4',
  array('url' => '/install/install4')
);

echo "<h4>Site Configuration</h4>";

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

echo "<h4>Super Admin Configuration</h4>";

echo $this->Form->input(
  'super_admin', 
  array(
    'value' => "root",
    'label' => 'Username',
  )
);

echo $this->Form->input(
  'password', 
  array(
    'label' => 'Password',
  )
);

echo $this->Form->input(
  'confirm_password', 
  array(
    'type' => 'password',
    'label' => 'Confirm Password',
  )
);

echo $this->Form->input(
  'admin_email', 
  array(
    'label' => 'Email',
  )
);

echo "<h4>Email Server Configuration</h4>";

echo $this->Form->input(
  'email_host', 
  array(
    'label' => "Host",
  )
);

echo $this->Form->input(
  'email_port', 
  array(
    'label' => 'Port',
  )
);

echo $this->Form->input(
  'email_username', 
  array(
    'label' => 'Username',
  )
);

echo $this->Form->input(
  'email_password', 
  array(
    'label' => 'Password',
  )
);

echo $form->submit('Done!', array('id'=>'next', 'div' => 'dbform')); 

echo $this->Form->end();

?>
</div>
