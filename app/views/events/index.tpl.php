<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
    <td width="35"> <b>Search:</b> </td>
    <td width="35"><select name="select" id="select2">
      <option value="title" >Event Title</option>
    </select></td>
    <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30"></td>
    <td width="80%" align="right">
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Event', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Event', '/events/add/'); ?>&nbsp;
    </td>
  </tr>
</table>
</form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'event_table', 'url'=>"/events/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<a name="list"></a>
<div id='event_table'>
    <?php
    $params = array('controller'=>'events', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('events/ajax_event_list', $params);
    ?>
</div>
</td></tr>
</table>
