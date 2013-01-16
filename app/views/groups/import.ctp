<div id='groupsimport'>
<h2><?php __('Instructions') ?></h2>
<ul>
    <li><?php __('Please make sure the column matches the username column in student import file.')?></li>
    <li><?php __('Please make sure to remove the header in CSV file.')?></li>
    <li><?php __('All columns are required.')?></li>
</ul>

<h3><?php __('Formatting:')?></h3>
    <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
    <?php __('Username, Group#, Group Name')?>
    </pre>

<h3><?php __('Examples:')?></h3>
    <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
        29978037, 1, <?php __('Team A')?><br>
        29978063, 1, <?php __('Team A')?><br>
        29978043, 2, <?php __('Team B')?><br>
        29978051, 2, <?php __('Team B')?>
    </pre>
    
<h2><?php __('Import')?></h2>

<?php
echo $this->Form->create(null, array('type' => 'file', 'url' => 'import/'.$courseId));
echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
echo $this->Form->input('Course',
    array('multiple'=>false, 'default' =>$courseId));
echo $this->Form->submit(__('Import', true));
echo $this->Form->end();
?>
</div>