<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $html->script('groups')?>
<?php echo $html->script('showhide')?>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Group']['id'])?'add':'edit') ?>">
      <?php echo empty($params['data']['Group']['id']) ? null : $html->hidden('Group/id'); ?>
      <?php echo empty($params['data']['Group']['id']) ? $html->hidden('Group/creator_id', array('value'=>$this->Auth->user('id'))) : $html->hidden('Group/updater_id', array('value'=>$this->Auth->user('id'))); ?>
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center">
      <?php echo empty($params['data']['Group']['id'])?'Add':'Edit' ?> <?php __('Group')?>
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="265"><?php __('Group Number')?>:<font color="red">*</font></td>
    <td colspan="3">
    <input type="hidden" name="assigned" id="assigned"/>
	<input type="text" name="group_num" id="group_num" class="input" value="<?php echo empty($params['data']['Group']['group_num'])? '' : $params['data']['Group']['group_num'] ?>" size="50">
	<div id="groupErr">
	<?php $params = array('controller'=>'groups', 'data'=>null);
    	echo $this->element('groups/ajax_group_validate', $params);
    ?>
  <?php echo $html->tagErrorMsg('Group/group_num', __('Group number is required.', true))?>
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
		<input type="radio" name="record_status" value="A" checked> - <?php __('Active')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="record_status" value="I" > - <?php __('Inactive')?><br>
	</td>
    </tr>


    <tr class="tablecell2">
  <td colspan="6">

    <?php echo $this->element("groups/group_list_chooser",
                array('all' => $user_data,
                'allName' =>  __("Filtered Students", true), 'selectedName' => __('Students in Group', true),
                'itemName' => 'User', 'listStrings' => array("student_no"," - ","first_name", " ", "last_name"),
                'listSize' => 20));
    ?>
  </td>


  </tr>
  <tr class="tablecell2">
    <td colspan="4" align="center"><?php echo $html->submit(__('Add Group', true)) ?></td>
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
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Import Groups From Text (.txt) or CSV File (.csv)')?></td>
        <td><div align="right"><a href="#" onclick="$('import').style.display='block'; toggle(this);">[+]</a> </div></td>
      </tr>
  </table>
<div id="import" style="display: <?php echo isset($import_again) ? "block" : "none" ?>; background: #FFF;">
  <br>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
	<table width="95%"  border="0" cellspacing="2" cellpadding="8" align="center">
  <tr class="tableheader">
    <td width="50%"><?php __('Instructions')?></td>
    <td width="50%"><?php __('Import')?></td>
  </tr>
  <tr class="tablecell2">
    <td>
      <?php __('NOTE:')?>
      <ul>
        <li><?php __('Please make sure the username column matches the username column in student import file.')?></li>
        <li><?php __('Please make sure to remove the header in CSV file.')?></li>
        <li><?php __('All columns are required.')?></li>
      </ul>

      <br />
      Format:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
<?php __('Username, Group# (e.g. 5 for group 5), and Group Name')?>
      </pre>

      <?php __('Example')?>:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
29978037, 1, <?php __('Team A')?>
29978063, 1, <?php __('Team A')?>
29978043, 2, <?php __('Team B')?>
29978051, 2, <?php __('Team B')?>
      </pre>
	</td>
    <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
    <h3>1) <?php __('Please select a CSV file to import')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="file" name="file" value="Browse" /><br>
    <?php
        $params = array('controller'=>'users', 'coursesList'=>$coursesList, "courseId" => $rdAuth->courseId);
    ?>
    <br /><h3>2) <?php __('Select the course to import into')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo $this->element('courses/course_selection_box', $params); ?>
    <br /><br /><h3>3) <?php __('Click the button bellow to Create the Groups')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo $html->submit(__('Import Group List', true)) ?>
</form>
<br></td>
  </tr>
</table>
	</td>
  </tr>
</table>
</div>
