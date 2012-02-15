<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <br>
<form id="searchForm" action="">
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td align="right" width="55%" style="padding:0px">
        <?php echo $html->image('icons/add.gif',
            array('alt'=>__('Add Sys Function', true), 'align'=>'middle')); ?>
            &nbsp;
            <?php echo $html->link(__('Add Sys Function', true), '/sysfunctions/edit'); ?>
            &nbsp;
    </td></tr>
    <tr><td style="padding:0px">
        <?php echo $this->element("list/ajaxList", $paramsForList);?>
    </td> </tr>
    </table>
</td></tr></table>
