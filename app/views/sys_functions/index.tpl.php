<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <br>
<form id="searchForm" action="">
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td align="right" width="55%" style="padding:0px">
        <?php echo $html->image('icons/add.gif',
            array('alt'=>'Add Sys Function', 'align'=>'middle')); ?>
            &nbsp;
            <?php echo $html->linkTo('Add Sys Function', '/sysfunctions/edit'); ?>
            &nbsp;
    </td></tr>
    <tr><td style="padding:0px">
        <?php echo $this->renderElement("list/ajaxList", $paramsForList);?>
    </td> </tr>
    </table>
</td></tr></table>