<div class='install'>

<h3><?php __('Step 3: iPeer Database Configuration')?></h3>

<?php
echo $this->Form->create(
    'InstallValidationStep3',
    array('url' => '/install/install3')
);
?>

<h4><?php __('Data Setup Option')?></h4>

<?php
$default = array('host' => 'localhost', 'login' => '', 'password' => '', 'database' => '');
$options = array(
    'A' => 'Installation with Sample Data',
    'B' => 'Basic Installation'
);
if (class_exists('DATABASE_CONFIG')) {
    $dbConfig = new DATABASE_CONFIG();
    $options['C'] = 'Upgrade from iPeer 2.x';
    $default = $dbConfig->default;
}
echo $this->Form->input(
    'data_setup_option',
    array(
        'options' => $options,
        'type' => 'radio',
        'legend' => false,
        'value' => 'A',
        'separator' => '<br />',
    )
);
?>

<div id='db-config'>
<!--<h4><?php __('MySQL Database Configuration Parameters')?></h4>-->

<?php
/*echo $form->input('host', array('value'=>$default['host']));
echo $form->input('login', array('label' => 'Username', 'value'=>$default['login']));
echo $form->input('password', array('value' => $default['password']));
echo $form->input('database', array('value' => $default['database']));*/

echo $form->submit('Next >>', array('id'=>'next', 'div' => 'dbform'));

echo $this->Form->end();
?>
</div>

</div>
