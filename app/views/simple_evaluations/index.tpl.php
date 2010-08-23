<?php echo $this->renderElement('evaltools/tools_menu', array());?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr><td style="text-align:right;">
    <?php echo $html->image('icons/add.gif',
    array('alt'=>'Add Simple Evaluation', 'align'=>'middle')); ?>
    &nbsp;
    <?php echo $html->linkTo('Add Simple Evaluation', '/simpleevaluations/add'); ?>
  </td></tr>
  <tr><td>
        <?php echo $this->renderElement("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </td></tr>
  </table>
</td></tr>
</table>

