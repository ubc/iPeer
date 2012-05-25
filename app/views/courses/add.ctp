<div>
<?php
echo $this->Form->create('Course');
echo $this->Form->input('course', array('after' => 'eg. APSC 201 001'));
echo $this->Form->input('title', array('after' => 'eg. Technical Communication'));

echo $this->Form->input(
    'record_status',
    array(
        'type' => 'select',  
        'id' => 'status',
        'label' => 'Status',
        'options' => $statusOptions,
    )
);
echo $this->Form->input('Department');
echo $this->Form->input('homepage', array('after' => 'eg. http://mycoursehome.com'));
echo $this->Form->submit('Save');
echo $this->Form->end();

?>
</div>

