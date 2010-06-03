<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <br>
<form id="searchForm" action="">
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td align="right" width="55%">
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Sys Function', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Sys Function', '/sysfunctions/edit'); ?>&nbsp;
    </td>
  </tr>
  <tr>
    <td colspan="2" align="left"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="17" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
        <td width="55">  <b>Search:</b> </td>
        <td width="92"><select name="searchIndex" id="searchIndex">
          <option value="function_code" >Function Code</option>
          <option value="function_name" >Function Name</option>
          <option value="parent_id" >Parent Id</option>
          <option value="permission_type" >Permission Type</option>
        </select></td>
        <td width="180"><input type="text" name="livesearch" id="livesearch2" size="30"> </td>
        </tr>
    </table></td>
    </tr>
</table>
</form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'function_table', 'url'=>"/sysfunctions/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<a name="list"></a>
<div id='function_table'>
    <?php
    $params = array('controller'=>'sysfunctions', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('sys_functions/ajax_sysfunctions_list', $params);

    ?>
</div>
</td></tr></table>