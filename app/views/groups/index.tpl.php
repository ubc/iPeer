<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr><td>
    <?php echo $html->image('icons/add.gif', array('alt'=>'Add Group', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Group', '/groups/add/'); ?>
  </td></tr>
  <tr><td colspan = 10>
    <?php echo $this->renderElement("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </td></tr>
</table>
	</td>
  </tr>
</table>
