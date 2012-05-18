<?php 
echo $this->Form->create('Department');
echo $this->Form->input('id');
echo $this->Form->input('name');
echo $this->Form->input('faculty_id', array('options' => $faculties));
echo $this->Form->end(__('Save', true));
?>
