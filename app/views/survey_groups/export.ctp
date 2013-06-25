<div id='exportSurveyGroup'>
<h2><?php echo __('Instructions', true) ?></h2>
<ul>
    <li><?php echo __("Give the export file a name.", true)?></li>
    <li><?php echo __("Select the survey group set you would like to export.", true)?></li>
    <li><?php echo __("Please select at least one of the export fields.", true)?></li>
</ul>
<h2><?php echo __('Export', true) ?></h2>
<?php
echo $this->Form->create('SurveyGroup', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('file_name', array(
    'label' => __('Export Filename', true), 'after' => '.csv'
));
echo $this->Form->input('survey_group_set', array(
    'type' => 'select', 'options' => $survey_group_set, 'id' => 'groupset'
));
?>
<h2><?php echo __('Export Fields', true) ?></h2>
<?php
echo $this->Form->input('fields', array(
    'type' => 'select', 'multiple' => 'checkbox', 'options' => $fields,
    'label' => false, 'selected' => array_keys($fields)
));
echo $this->Form->submit(__('Export', true), array('id' => 'export'));
echo $this->Form->end();
?>
</div>