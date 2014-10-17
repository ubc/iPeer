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
echo $this->Form->input(
  'Role.RolesUser.role_id',
  array(
    'default' => $roleDefault,
    'label' => 'Role',
    'options' => $roleOptions,
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
		<label>Put User in Course</label>
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
						?>
					<tr>
						<td style="text-align: center;"><?php 
						if (in_array($key, $coursesSelected)) {
							$checked = "checked";
						}
						else {
							$checked = "";
						}
						echo $this->Form->checkbox('Courses.id', array('value' => $key, 'hiddenField' => false, 'checked' => $checked, 'style' => 'width: 12px;', 'id' => 'course_'.$key));
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

<script>
	function selectCourse(id) {
		if (jQuery("#CoursesEnrollment").val().indexOf("|" + id + "|") >= 0) {
			jQuery("#CoursesEnrollment").val(jQuery("#CoursesEnrollment").val().replace("|" + id + "|", ""));
		}
		else {
			jQuery("#CoursesEnrollment").val(jQuery("#CoursesEnrollment").val() + "|" + id + "|");
		}
	}

	/* Create an array with the values of all the checkboxes in a column */
	jQuery.fn.dataTableExt.afnSortData['dom-checkbox'] = function(oSettings, iColumn) {
	    var aData = [];
	    jQuery('td:eq(' + iColumn + ') input', oSettings.oApi._fnGetTrNodes(oSettings)).each(function() {
	        aData.push(this.checked == false ? "1" : "0");
	    });
	    return aData;
	}
	
	jQuery(document).ready(function() {

		jQuery("#courseTable input[type='checkbox'][checked='checked']").each(function(){
			selectCourse(this.value);
		});

		jQuery("#courseTable input[type='checkbox']").click(function(){
			selectCourse(this.value);
		});
		
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