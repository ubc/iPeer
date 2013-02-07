<p>
A password will be automatically generated and shown after you click "Save".
</p>


<div>
<?php
echo $this->Form->create('User', array('id' => 'UserForm', 'url' => '/'.$this->params['url']['url']));
echo '<input type="hidden" name="required" id="required" value="username" />';
echo $this->Form->input('username');
echo "<div id='usernameErr' class='red'></div>";
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('email');
echo $this->Form->input('send_email_notification', array('type'=>'checkbox'));
echo $this->Form->input(
  'Role.RolesUser.role_id',
  array(
    'default' => $roleDefault,
    'label' => 'Role',
    'options' => $roleOptions,
  )
);
if (User::hasPermission('functions/user/admin', 'create')) {
    echo $this->Form->input('Faculty',
        array('label' => 'Faculty (admins & instructors)'));
}
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
?><div class=buttons><?php
echo $this->Form->submit(
    'Save',
    array('name' => 'data[Form][save]')
);
echo $this->Form->submit(
    'Save & Add Another',
    array('name' => 'data[Form][save]')
);
?></div><?php
echo $this->Form->end();

$url = 'checkDuplicateName/';
if(!is_null($courseId)) {
    $url = $url.$courseId;
}

// dynamically check username availability
echo $ajax->observeField(
  'UserUsername',
  array(
    'update'=>'usernameErr',
    'url'=>$url,
    'frequency'=>1,
    'loading'=>"Element.show('loading');",
    'complete'=>"Element.hide('loading');stripe();"
  )
);

?>
</div>

<?php
if (User::hasPermission('functions/user/admin')) {
    echo "
<script type='text/javascript'>
// If the user is supposed to be a student, disable adding as an
// instructor. Any other role, disable adding as a student.
jQuery('#RoleRolesUserRoleId').change(function() {
    var str = jQuery('#RoleRolesUserRoleId option:selected').text();
    if (str == 'admin' || str == 'instructor') {
        jQuery('#FacultyFaculty').removeAttr('disabled');
    }
    else {
        jQuery('#FacultyFaculty').attr('disabled', 'disabled');
    }
});
// run once on initial page load
jQuery('#RoleRolesUserRoleId').change();
</script>";
}
?>
