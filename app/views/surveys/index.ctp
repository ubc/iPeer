<?php echo $this->element('evaltools/tools_menu', array());?>

<form id="searchForm" action="">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr><td style="text-align:right">
            <?php echo $html->link($html->image('icons/add.gif', array('alt'=>'Add Survey', 'valign'=>'middle')).' Add Survey', '/surveys/add/'.$course_id, array('escape' => false)); ?>
    </td></tr>
    <tr><td colspan=10>
        <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
    </td></tr>
  </table>
</td></tr></table>
