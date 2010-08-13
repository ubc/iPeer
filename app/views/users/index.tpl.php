<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td align="center">
		<table width="95%" border="0" cellspacing="0" cellpadding="2">
  		<tr>
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
        <tr><td>
            <?php echo $this->renderElement("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
        </td></tr>
        </table>
    </td></tr>
</table>
