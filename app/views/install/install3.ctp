<div class='install'>

<h3><?php __('Step 3: iPeer Database Configuration')?></h3>

<?php 
echo $this->Form->create(
  'InstallValidation',
  array('url' => '/install/install3')
); 
?>

<h4><?php __('Data Setup Option')?></h4>

<?php
echo $this->Form->input(
  'data_setup_option', 
  array(
    'type' => 'radio', 
    'legend' => false,
    'options' => array(
      'A' => 'Installation with Sample Data', 
      'B' => 'Basic Installation'
    ),
    'value' => 'A',
    'separator' => '<br />',
  )
);
?>

<h4><?php __('MySQL Database Configuration Parameters')?></h4>

<?php
echo $form->input('host', array('value'=>'localhost')); 
echo $form->input('login', array('label' => 'Username'));
echo $form->input('password'); 
echo $form->input('database'); 

echo $form->submit('Next >>', array('id'=>'next', 'div' => 'dbform'));

echo $this->Form->end();
?>

</div>
