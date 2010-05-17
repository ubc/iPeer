<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $javascript->link('groups')?>
<?php echo $javascript->link('showhide')?>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Group']['id'])?'add':'edit') ?>">
      <?php echo empty($params['data']['Group']['id']) ? null : $html->hidden('Group/id'); ?>
      <?php echo empty($params['data']['Group']['id']) ? $html->hidden('Group/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Group/updater_id', array('value'=>$rdAuth->id)); ?>
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center">
      <?php echo empty($params['data']['Group']['id'])?'Add':'Edit' ?> Group
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="265">Group Number:<font color="red">*</font></td>
    <td colspan="3">
    <input type="hidden" name="assigned" id="assigned"/>
	<input type="text" name="group_num" id="group_num" class="input" value="<?php echo empty($params['data']['Group']['group_num'])? '' : $params['data']['Group']['group_num'] ?>" size="50">
	<div id="groupErr">
	<?php $params = array('controller'=>'groups', 'data'=>null);
    	echo $this->renderElement('groups/ajax_group_validate', $params);
    ?>
	</div>
	<?php echo $ajax->observeField('group_num', array('update'=>'groupErr', 'url'=>"/groups/checkDuplicateName", 'frequency'=>1,'loading'=>"Element.show('loading');",'complete'=>"Element.hide('loading');stripe();")) ?>
	</td></tr>
  <tr class="tablecell2">
    <td>Group Name:&nbsp;</td>
    <td colspan="3"><?php echo $html->input('Group/group_name', array('size'=>'50','class'=>'input')) ?>  </td>
    </tr>
  <tr class="tablecell2">
    <td>Status:</td>
    <td colspan="3">
		<input type="radio" name="record_status" value="A" checked> - Active&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="record_status" value="I" > - Inactive<br>
	</td>
    </tr>
  <tr class="tablecell2">
    <td align="center">&nbsp;</td>
    <td width="250" align="center">Filtered Students<br>
      <br>
	  <select name="filtered" id="filtered" style="width:90%; height:200px;" multiple>
        <?php foreach($user_data as $row): $user = $row['User'];?>
		<option value="<?php echo $user['id'] ?>"><?php echo $user['last_name'] ?>, <?php echo $user['first_name'] ?></option>
    	<?php endforeach; ?>
      </select>
	  </td>
    <td width="124" align="center">
      <input type="button" style="width:150px;" onClick="move(document.getElementById('filtered'),document.getElementById('group_members'))" value="Add Student(s) >>" />
      <br><br><br>
      <input name="filter" type="checkbox" id="filter" value="filter">
- Show Unassigned Students Only <br><br><br>
      <input type="button" style="width:150px;" onClick="move(document.getElementById('group_members'),document.getElementById('filtered'))" value="<< Remove Student(s)" /></td>
    <td width="251" align="center">Group List<br><br>
      <select name="group_members" multiple id="group_members" style="width:90%; height:200px;">
	  </select></td>
  </tr>
  <tr class="tablecell2">
    <td colspan="4" align="center"><?php echo $html->submit('Add Group') ?></td>
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
  <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Import Groups From Text (.txt) or CSV File (.csv)</td>
        <td><div align="right"><a href="#" onclick="showhide('import'); toggle(this);">[+]</a> </div></td>
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

StudentNumber is required<br>
Group# (e.g. 5 for group 5) is required<br>
Please follow the following formatting:<br>
<br>
[StudentNumber],[Group#],[GroupName]<br>
[StudentNumber],[Group#],[GroupName]<br>
<br>
*[GroupName] is optional. Leave blank for no group name.<br>
<br>
eg:<br>
<br>
29978037,1,team 1<br>
29978063,1,team 1<br>
	</td>
    <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
<input type="file" name="file" value="Browse" /><br>
			<?php
			if (empty($rdAuth->courseId)) {
			  $params = array('controller'=>'users', 'courseList'=>$coursesList, 'defaultOpt'=>1);
			} else {
			  $params = array('controller'=>'users', 'courseList'=>$coursesList);
			}
      echo $this->renderElement('courses/course_selection_box', $params);
      ?>
<br>
<?php echo $html->submit('Import Group List') ?>
</form>
<br></td>
  </tr>
</table>
	</td>
  </tr>
</table>
</div>
