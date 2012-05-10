<div>
<?php
echo $this->Form->create('Course');
echo $this->Form->input('course', array('id' => 'course', 'after' => 'eg. APSC 201 001', 'value' => $course));
echo $this->Form->input('title', array('after' => 'eg. Technical Communication', 'value' => $title));
echo $this->Form->input(
    'Instructor.id',
    array(
        'type' => 'select',
        'options' => $instructors,
        'multiple' => 'true',
        'id' => 'instruct',
        'selected' => $selected,
        'label' => 'Instructors',
    )
);
echo $this->Form->input(
    'record_status', 
    array(
        'type' => 'select', 
        'label' => 'Status', 
        'id' => 'status',
        'options' => $statusOptions,
        'default' => $status,
    )
);
echo $this->Form->input('homepage', array('after' => 'eg. http://mycoursehome.com', 'value' => $homepage));
echo $this->Form->submit('Save');
echo $this->Form->end();

?>
</div>