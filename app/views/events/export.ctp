<div id="portEvent">
<h2><?php echo __('Information', true) ?></h2>
<ul>
    <li><?php echo __('Groups will either get listed as all groups (with an *) or a semi-colon separated list of names', true) ?>.</li>
    <li><?php echo __('Evaluation types are shown by their id: 1=simple; 2=rubric; 3=survey; 4=mixed', true) ?></li>
    <li><?php echo __('For Student Result Mode: 0=basic (grades only); 1=detailed (grades and comments)', true) ?></li>
    <li><?php echo __('For the other 0 or 1 fields: 0=no/off; 1=on/yes', true) ?></li>
</ul>
<h2><?php echo __('Export', true) ?></h2>
<?php
echo $this->Form->create('ExportEvents', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('file_name', array(
    'name' => 'file_name', 'value' => isset($file_name) ? $file_name : '',
    'label' => __('Export Filename', true),
    'after' => '.csv'
));
echo $this->Form->submit(__('Export', true));
echo $this->Form->end();
?>
</div>
