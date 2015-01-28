<div>
<?php
echo $this->Form->create('User', array('id' => 'UserForm', 'url' => '/'.$this->params['url']['url']));
echo '<input type="hidden" name="required" id="required" value="username" />';
echo $this->Form->input('id');
if (User::hasPermission('functions/viewusername'))
{
    echo $this->Form->input('username');
}
echo "<div id='usernameErr' class='red'></div>";
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('email');
echo '<p class="upgradenote message good-message green hide">The user\'s primary role will be upgraded:</p>';
echo $this->Form->input(
  'Role.RolesUser.role_id',
  array(
    'default' => $roleDefault,
    'label' => 'Primary Role',
    'options' => $roleOptions,
    'after' => 'If the user is an instructor below, their primary role should match.'
  )
);
if (User::hasPermission('functions/user/admin', 'update')) {
    echo $this->Form->input('Faculty',
        array('label' => 'Faculty<br>(instructor/admin only)'));
}
echo $this->Form->input('title');
echo $this->Form->input('student_no', array('label' => 'Student Number'));
?>
	<div class="input text">
		<label>Course Enrollment</label>
		<div style="width: 35em; display: inline-block; border: 1px solid #999; padding: 5px; border-radius: 3px; margin: 0.35em 0.35em;">
			<table id="courseTable">
				<thead>
					<tr>
						<th style="border: none"></th>
						<th style="border: none">Course</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($coursesOptions as $key=>$row) {
                        
                        $courseRole = 0; // default is none
                        if(isset($coursesSelected[$key])) {
                            $courseRole = $coursesSelected[$key];
                        }
                        
						?>
					<tr>
						<td style="text-align: center;"><?php 
						echo $this->Form->input('CourseEnroll.'.$key, array('default' => $courseRole, 'options' => $courseLevelRoles, 'id' => 'course_'.$key , 'class' => 'role-select', 'label' => false));
						/* echo $this->Form->checkbox('Courses.id', array('value' => $key, 'hiddenField' => false, 'checked' => $checked, 'style' => 'width: 12px;', 'id' => 'course_'.$key)); */
						?>
						</td>
						<td style="text-align: left;"><?php echo $this->Form->label('Courses.id', $row, array('style' => 'width: 100%; float: none', 'for' => 'course_'.$key));
						?>
						</td>
					</tr>
					<?php 
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
?><div class=buttons><?php
echo $this->Form->submit('Save');
echo $this->Form->hidden('Courses.enrollment');
?></div><?php
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

<?php
if (User::hasPermission('functions/user/admin')) {
    echo "
<script type='text/javascript'>
// enable or disable faculty menu as appropriate
jQuery('#RoleRolesUserRoleId').change(function() {
    var str = jQuery('#RoleRolesUserRoleId option:selected').text();
    if (str == 'admin' || str == 'instructor') {
        jQuery('#FacultyFaculty').removeAttr('disabled');
        jQuery('#FacultyFaculty').addClass('enabled'); // for edit view testing
    }
    else {
        jQuery('#FacultyFaculty').attr('disabled', 'disabled');
    }
});

jQuery('.role-select').change(function () {
    if(jQuery(this).val()==3 && jQuery('#RoleRolesUserRoleId').val()>3) {
        jQuery('#RoleRolesUserRoleId').val(3).change();
        jQuery('.upgradenote').show();
    }
});

// run once on initial page load
jQuery('#RoleRolesUserRoleId').change();
</script>";
}
?>

<script>
	/* Create an array with the values of all the checkboxes in a column */
	jQuery.fn.dataTableExt.afnSortData['dom-checkbox'] = function(oSettings, iColumn) {
	    var aData = [];
	    jQuery('.role-select', oSettings.oApi._fnGetTrNodes(oSettings)).each(function() {
	        aData.push(jQuery(this).val());
	    });
	    return aData;
	}
	
	jQuery(document).ready(function() {
		jQuery('#courseTable').dataTable({
			"sPaginationType" : "full_numbers",
	        "aoColumnDefs" : [
	            {"bSearchable": false, "sSortDataType": "dom-checkbox", "aTargets": [0] }
	        ],
	        "aaSorting" :[[1, 'asc']]
		});

		jQuery("#courseTable_length select").css({"width": "65px", "margin-top": "0", "margin-bottom": "0", "background": "#ddd", "font-size": "15px"});
		jQuery("#courseTable_length label").css({"float": "none", "padding": "0"});
		jQuery("#courseTable_filter label").css({"float": "none", "padding": "0"});
		jQuery("#courseTable_filter input").css({"width": "175px"});
	});
</script>
