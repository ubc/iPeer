<?php echo $this->element('evaltools/tools_menu', array());?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr><td align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr><td style="text-align:right">
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Rubric', 'align'=>'middle')); ?>
        &nbsp;
        <?php echo $html->link('Add Rubric', '/rubrics/add'); ?>
    </td></tr>
    <tr><td colspan=10>
        <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
    </td></tr>
    </table>
</td></tr></table>
