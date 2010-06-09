<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td><?php echo $javascript->link('showhide')?>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td><form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['User']['id'])?'add':'edit') ?>" onSubmit="return validate()">
<?php echo empty($params['data']['User']['id']) ? null : $html->hidden('User/id'); ?>
<?php echo empty($params['data']['User']['role']) ? null: $html->hidden('User/role'); ?>
<input type="hidden" name="required" id="required" value="newuser last_name" />
<?php echo empty($params['data']['User']['id']) ? $html->hidden('User/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('User/updater_id', array('value'=>$rdAuth->id)); ?>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
        <?php echo empty($params['data']['Group']['id'])?'Add':'Edit' ?> Student
    </td>
  </tr>
  <tr class="tablecell2">
    <td id="course_label">Course:</td>
    <td width="440"><?php
              if (empty($rdAuth->courseId)) {
                $params = array('controller'=>'courses', 'courseList'=>$coursesList, 'courseId'=>$rdAuth->courseId, 'defaultOpt'=>1);
              } else {
                $params = array('controller'=>'courses', 'courseList'=>$coursesList, 'courseId'=>$rdAuth->courseId);
              }
              echo $this->renderElement('courses/course_selection_box', $params);
        ?></td>
    <td width="201" id="course_msg" class="error">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td width="161"  id="newuser_label">Student No.: <font color="red">*</font></td>
    <td>
      <input type="text" name="newuser" id="newuser" class="validate required USERNAME_FORMAT newuser_msg Invalid_Username_Format." value="<?php echo empty($params['data']['User']['username'])? '' : $params['data']['User']['username'] ?>" size="50">
      <?php echo $ajax->observeField('newuser', array('update'=>'usernameErr', 'url'=>"/users/checkDuplicateName/S", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?>
      <div id='usernameErr' class="error">
        <?php $params = array('controller'=>'users', 'data'=>null);
              echo $this->renderElement('users/ajax_username_validate', $params);
        ?>
      </div></td>
    <td id="newuser_msg" class="error">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td id="last_name_label">Last Name:</td>
    <td><?php echo $html->input('User/last_name', array('id'=>'last_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
    <td id="last_name_msg" class="error">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td id="first_name_label">First Name:</td>
    <td><?php echo $html->input('User/first_name', array('id'=>'first_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?></td>
    <td id="first_name_msg" class="error">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td id="email_label">Email:</td>
    <td><?php echo $html->input('User/email', array('id'=>'email', 'size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.')) ?></td>
    <td id="email_msg" class="error">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td colspan="3"><div align="center"><span class="error">
	 <input type="button" name="Back" value="Back" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller']; ?>'">
	 <?php echo $html->submit('Save') ?><br>
          <span class="required">Note:</span> The password will be generated automatically on the next page. </span></div></td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>
  <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Import Students From Text (.txt) or CSV File (.csv)</td>
        <td><div align="right"><a href="#import" onclick="showhide('import'); toggle(this);">[+]</a> </div></td>
      </tr>
  </table>
<div id="import" style="display: none; background: #FFF;">
<br>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
	<table width="95%"  border="0" cellspacing="2" cellpadding="8" align="center">
  <tr class="tableheader">
    <td width="50%">Instructions</td>
    <td width="50%">Import</td>
  </tr>
  <tr class="tablecell2">
    <td>
			<?php
			if (isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL) {
				echo $this->renderElement('users/user_import_info_cwl');
			} else {
  			echo $this->renderElement('users/user_import_info');
  		}?>
	  </td>
    <td valign="top"><br>

<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
<input type="hidden" name="required" value="file" />
<input type="file" name="file" value="Browse" /><br>
<?php echo  $html->hidden('User/role'); ?>

			<?php
			if (empty($rdAuth->courseId)) {
			  $params = array('controller'=>'users', 'courseList'=>$courseList, 'defaultOpt'=>1);
			} else {
			  $params = array('controller'=>'users', 'courseList'=>$courseList);
			}
      echo $this->renderElement('courses/course_selection_box', $params);
      ?>
      <br>
<?php echo $html->submit('Import Student List') ?>
</form>
<br></td>
  </tr>
</table>
	</td>
  </tr>
</table>
</div>
