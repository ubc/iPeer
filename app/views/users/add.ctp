<p>
Usernames must be at least 6 characters long and contain only letters and numbers. A password will be automatically generated and shown after you click "Save".
</p>


<div>
<?php
echo $this->Form->create('User');
echo '<input type="hidden" name="required" id="required" value="username" />';
echo $this->Form->input('username');
echo "<div id='usernameErr' class='red'></div>";
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('email');
echo $this->Form->input('send_email_notification',
  array('type'=>'checkbox', 'id' => 'email'));
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
