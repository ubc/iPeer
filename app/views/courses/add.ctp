<?php
echo $this->Form->create('Course');
echo $this->Form->input('course', array('after' => ''));?>
<div class="help-text"><?php __('Course subjects and course numbers, e.g. APSC 201 001')?></div>

<?php echo $this->Form->input('title');?>
<div class="help-text"><?php __('Course title, e.g. Technical Communication')?></div>
<?php echo $this->Form->input('Instructor', array('selected' => User::get('id')));?>
<div class="help-text"><?php __('Holding "ctrl" or "command" key to select multiple instructors.')?></div>

<?php echo $this->Form->input(
    'record_status',
    array(
        'type' => 'select',
        'id' => 'status',
        'label' => 'Status',
        'options' => $statusOptions,
    )
);?>

<?php echo $this->Form->input('Department', array(
    'label' => __($this->Vocabulary->translate('Department'), true),
));
?>
<div class="help-text"><?php __('Selecting correct assoication will allow admin to help troubleshooting.')?></div>
<?php echo $this->Form->input('homepage');?>
<div class="help-text"><?php __('e.g. http://mycoursehome.com')?></div>

<?php echo $this->Form->submit('Save');
echo $this->Form->end();
