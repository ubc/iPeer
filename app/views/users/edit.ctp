<div>
<?php 
echo $this->Form->create('User', array('id' => 'UserForm'));
echo '<input type="hidden" name="required" id="required" value="username" />';
echo $this->Form->input('id');
echo $this->Form->input('username');
echo "<div id='usernameErr' class='red'></div>";
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('email');
echo $this->Form->input(
  'Role.RolesUser.role_id', 
  array(
    'default' => $roleDefault,
    'label' => 'Role',
    'options' => $roleOptions,
  )
);
echo $this->Form->input('title');
echo $this->Form->input('student_no', array('label' => 'Student Number'));
echo $this->Form->input(
  'Courses.id', 
  array(
    'type' => 'select',
    'multiple' => 'checkbox',
    'options' => $coursesOptions,
    'label' => "Put User in Course",
    'selected' => $coursesSelected
  )
);

echo $this->Form->submit('Save');
echo $this->Form->end();

// dynamically check username availability
echo $ajax->observeField(
  'UserUsername', 
  array(
    'update'=>'usernameErr', 
    'url'=>'checkDuplicateName/', 
    'frequency'=>1, 
    'loading'=>"Element.show('loading');", 
    'complete'=>"Element.hide('loading');stripe();"
  )
); 

?>
</div>

