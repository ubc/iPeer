<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="2" cellpadding="4">
  <tr><td align="right" width="55%">
    <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Sys Parameter', true), 'valign'=>'middle')); ?>
    <?php echo $html->link(__('Add Sys Parameter', true), '/sysparameters/edit'); ?>
  </td></tr>
  <tr><td>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
  </td></tr>
</table>
</td></tr></table>
