<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td align="center">
		<form id="searchForm" action="">
		<table width="95%" border="0" cellspacing="0" cellpadding="2">
  		<tr>
			<td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
			<td width="35"> <b>Search:</b> </td>
			<td width="35"><select name="select" >
				  <option value="username" >Username</option>
				  <option value="student_no" >Student No.</option>
				  <option value="first_name" >First Name</option>
				  <option value="last_name" >Last Name</option>
				  <option value="email" >Email</option>
				</select></td>
   			<td width="35"><input type="text" name="livesearch" size="30"></td>
    		<td width="80%" align="right">
			  <?php if (!empty($access['USR_ADMIN_MGT'])) {   ?>
				<?php echo $html->image('icons/add.gif', array('alt'=>'Add Admin', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Admin', '/users/add/A'); ?>&nbsp;|&nbsp;
			  <?php }?>
			  <?php if (!empty($access['USR_INST_MGT'])) {   ?>
				<?php echo $html->image('icons/add.gif', array('alt'=>'Add Instructor', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Instructor', '/users/add/I'); ?>&nbsp;|&nbsp;
			  <?php }?>
			  <?php if (!empty($access['USR_RECORD'])) {   ?>
				<?php echo $html->image('icons/add.gif', array('alt'=>'Add Student', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Student', '/users/add/S'); ?>
			  <?php }?>
			</td>
  		</tr>
		<tr><td colspan="5" align="left">
	      <table>
	      <tr>
	          <td align="left">&nbsp;<b>View Users As:&nbsp;</b></td>
	          <td>
 				<select name="display_user_type" id="display_user_type" onchange='OnChange(this.form.display_user_type);'>
    		<?php
    		if ($rdAuth->role == 'A') {
  				$userTypes = array(''=>'-- All --', 'A'=>'Administrators', 'I'=>'Instructors', 'S'=>'Students');
  			} else {
  			  $userTypes = array('S'=>'Students');
  			}
				foreach($userTypes as $index => $value) {
						echo '<option value="'.$index.'"';
						if ($displayUserType == $index){
							echo 'SELECTED';
						}
						echo '>'.$value.'</option>';
				}
				?>
				</select></td>
			  <td>
				<div id='course_select' style="background: #FFF;">
				&nbsp;<b>For Course:&nbsp;</b>
				<select name="course_id" id="course_id" >
   				<?php
				//echo '<option value="" SELECTED >-- All --</option>';
				echo '<option value="-1" SELECTED >-- Unassigned Students --</option>';
				foreach($courseList as $index => $value) {
				  if ($value['record_status'] == 'A') {
						echo '<option value="'.$value['id'].'"';
						if ($rdAuth->courseId == $index){
							echo 'SELECTED';
						}
						echo '>'.$value['course'].'</option>';
					}
				}
				?>
			  </select>
			</div>
			  </td>
			  </tr>
			  </table>
			</td></tr>
</table>
</form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'user_table', 'url'=>"/users/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<a name="list"></a>
<div id='user_table'>
    <?php
    $params = array('controller'=>'users', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('users/ajax_user_list', $params);
    ?>
</div>
</td></tr>
</table>