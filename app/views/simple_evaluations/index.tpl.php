<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
    <td width="35"> <b>Search:</b> </td>
    <td width="35"><select name="select">
      <option value="name" >Name</option>
      <option value="description" >Description</option>
      <option value="point_per_member" >Base Point/Member</option>
    </select></td>
    <td width="35"><input type="text" name="livesearch" size="30"></td>
    <td width="80%" align="right"> Show Only My Simple Evaluation Tool(s)? <input type="checkbox" name="show_my_tool" checked />
    </td>
  </tr>
  <?php if (!empty($access['SIMPLE_EVAL'])) {   ?>
  <tr>
    <td width="10" height="32"></td>
    <td colspan="4" align="right"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Simple Evaluation', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Simple Evaluation', '/simpleevaluations/add'); ?></td>
  </tr>
  <?php } ?>
</table>
</form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'simple_table', 'url'=>"/simpleevaluations/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<a name="list"></a>
<div id='simple_table'>
    <?php
    $params = array('controller'=>'simpleevaluations', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('simple_evaluations/ajax_simple_eval_list', $params);
    ?>
</div>
</td></tr>
</table>