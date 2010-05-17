<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
    <td width="35"> <b>Search:</b> </td>
    <td width="35"><select name="select" id="select2">
        <option value="group_name" >Group Name</option>
        <option value="group_num" >Group Number</option>
    </select></td>
    <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30">
    </td>
    <td width="80%" align="right">
      <?php if (!empty($access['GROUP_RECORD'])) {   ?>
      <?php echo $html->image('icons/add.gif', array('alt'=>'Add Group', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Group', '/groups/add/'); ?>
      <?php }?>
    </td>
  </tr>

</table>
  </form>
<?php echo $ajax->observeForm('searchForm', array('update'=>'group_table', 'url'=>"/groups/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>
<a name="list"></a>

<div id='group_table'>
    <?php
    $params = array('controller'=>'groups', 'data'=>$data);
    echo $this->renderElement('groups/ajax_group_list', $params);
    ?>
</div>
	</td>
  </tr>
</table>
