<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><?php echo $javascript->link('groups')?>
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
    <td width="265">Group Number:&nbsp;</td>
    <td colspan="3"><?php echo $html->input('Group/group_num', array('size'=>'50','class'=>'input')) ?></td>
    </tr>
  <tr class="tablecell2">
    <td>Group Name:&nbsp;</td>
    <td colspan="3"><?php echo $html->input('Group/group_name', array('size'=>'50','class'=>'input')) ?>  </td>
    </tr>
  <tr class="tablecell2">
    <td>Status:</td>
    <td colspan="3">
	  <input type="hidden" name="assigned" id="assigned"/>
		<input type="radio" name="record_status" value="A" <?php if( $params['data']['Group']['record_status'] == "A" ) echo "checked";?>> - Active&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="record_status" value="I" <?php if( $params['data']['Group']['record_status'] == "I" ) echo "checked";?>> - Inactive<br>
	</td>
    </tr>

  <tr class="tablecell2">
  <td colspan="6">

    <?php echo $this->renderElement("groups/group_list_chooser",
                array('all' => $user_data, 'selected' => $group_data,
                'allName' =>  "Filtered Students", 'selectedName' => 'Students in Group',
                'itemName' => 'users', 'listStrings' => array("student_no"," - ","first_name", " ", "last_name"),
                'listSize' => 20));
    ?>
  </td>


  </tr>
  <tr class="tablecell2">
    <td colspan="4" align="center"><?php echo $html->submit('Update Group', array('onClick'=>"processSubmit(document.getElementById('selected_groups'))")) ?></td>
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
