<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
	<td align="center">
		<table width="95%" border="0" cellspacing="0" cellpadding="2">
  		<tr>
            <td width="80%" align="right">
            <?php if($can_add_user):?>
                <?php echo $html->image('icons/add.gif', array('alt'=>'Add User', 'valign'=>'middle')); ?>&nbsp;<?php echo $html->link('Add User', '/users/add'); ?>&nbsp;|&nbsp;
            <?php endif;?>
            <?php if($can_import_user):?>
                <?php echo $html->image('icons/add.gif', array('alt'=>'Import User', 'valign'=>'middle')); ?>&nbsp;<?php echo $html->link('Import User', '/users/import'); ?>&nbsp;|&nbsp;
            <?php endif;?>
            </td>
        </tr>
        <tr><td>
            <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
        </td></tr>
        </table>
    </td></tr>
</table>
