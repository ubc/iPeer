<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr><td align='right'>
    <?php echo $html->link($html->image('icons/add.gif', array('alt'=>'Add Group', 'valign'=>'middle')) . __(' Add Group', true), 
                           '/groups/add/'.$course_id, array('escape' => false)); ?>
  </td></tr>
  <tr><td>
    <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </td></tr>
</table>
	</td>
  </tr>
</table>
