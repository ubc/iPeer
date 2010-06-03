<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td align="right" width="55%">
      <?php echo $html->image('icons/add.gif', array('alt'=>'Add Sys Parameter', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Sys Parameter', '/sysparameters/add'); ?>&nbsp;
    </td>
  </tr>
  <tr>
    <td colspan="2" align="left"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="17" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
        <td width="55">  <b>Search:</b> </td>
        <td width="92"><select name="searchIndex" id="searchIndex">
          <option value="parameter_code" >Parameter Code</option>
          <option value="parameter_value" >Parameter Value</option>
          <option value="parameter_type" >Parameter Type</option>
        </select></td>
        <td width="180"><input type="text" name="livesearch" id="livesearch2" size="30"> </td>
        </tr>
    </table>
</td>
</tr>
</table></form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'parameter_table', 'url'=>"search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<div id='parameter_table'>
    <?php
    $params = array('controller'=>'sysparameters', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    var_dump($this->renderElement('sys_parameters/ajax_sysparameters_list', $params));
    ?>
</div>
</td></tr></table>