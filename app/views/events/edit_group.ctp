<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><?php echo $html->script('groups')?>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('editGroup/'.$group_id.'/'.$event_id . '/'. $popup) ?>">
     <?php echo empty($params['data']['Group']['id']) ? null : $html->hidden('Group/id'); ?>
     <?php echo empty($params['data']['Group']['id']) ? $html->hidden('Group/creator_id', array('value'=>$this->Auth->user('id'))) : $html->hidden('Group/updater_id', array('value'=>$this->Auth->user('id'))); ?>
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center">
      <?php echo empty($params['data']['Group']['id'])?'Add':'Edit' ?> <?php __('Group')?>
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="265"><?php __('Group Number')?>:&nbsp;</td>
    <td colspan="3"><?php echo $html->input('Group/group_num', array('size'=>'50','class'=>'input')) ?></td>
    </tr>
  <tr class="tablecell2">
    <td><?php __('Group Name')?>:&nbsp;</td>
    <td colspan="3"><?php echo $html->input('Group/group_name', array('size'=>'50','class'=>'input')) ?>  </td>
    </tr>
  <tr class="tablecell2">
    <td><?php __('Status')?>:</td>
    <td colspan="3">
	  <input type="hidden" name="assigned" id="assigned"/>
		<input type="radio" name="record_status" value="A" <?php if( $params['data']['Group']['record_status'] == "A" ) echo "checked";?>> - <?php __('Active')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="record_status" value="I" <?php if( $params['data']['Group']['record_status'] == "I" ) echo "checked";?>> - <?php __('Inactive')?><br>
	</td>
    </tr>
  <tr class="tablecell2">
    <td align="center">&nbsp;</td>
    <td width="250" align="center"><?php __('Filtered Students')?><br>
      <br>
	  <select name="filtered" id="filtered" style="width:90%; height:200px;" multiple>
        <?php foreach($user_data as $row): $user = $row['users'];?>
		<option value="<?php echo $user['id'] ?>"><?php echo $user['last_name'] ?>, <?php echo $user['first_name'] ?></option>
    	<?php endforeach; ?>
      </select>
	  </td>
    <td width="124" align="center">
      <input type="button" style="width:150px;" onClick="move(document.getElementById('filtered'),document.getElementById('group_members'))" value="<?php __('Add Student(s)')?> >>" />
      <br><br><br>
      <input name="filter" type="checkbox" id="filter" value="filter">
- <?php __('Show Unassigned Students Only ')?><br><br><br>
      <input type="button" style="width:150px;" onClick="move(document.getElementById('group_members'),document.getElementById('filtered'))" value="<< <?php __('Remove Student(s)')?>" /></td>
    <td width="251" align="center">Group List<br><br>
      <select name="group_members" multiple id="group_members" style="width:90%; height:200px;">
	  	<?php if (isset($group_data)) foreach($group_data as $row): $user = $row['users'];?>
		<option value="<?php echo $user['id'] ?>"><?php echo $user['last_name'] ?>, <?php echo $user['first_name'] ?></option>
    	<?php endforeach; ?>
	  </select></td>
  </tr>
  <tr class="tablecell2">
    <td colspan="4" align="center"><?php echo $html->submit(__('Update Group', true), array('onClick'=>"processSubmit(document.getElementById('group_members'))")) ?></td>
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
