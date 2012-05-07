<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">

<table width="60%" align="center" border="0" cellspacing="0" cellpadding="2">  
  <tr><td>
    <?php echo $this->element('searchs/search_menu', array());?>
  </td></tr>
</table>

<a name="list"></a>

<div id='search_table'>

<?php
$params = array('controller'=>'schs', 'data'=>$data, 'currentUser'=>$currentUser, array('names'=>$names), 'eventTypes' => $eventTypes);
echo $this->element('searchs/evaluation_search_panel', $params);
//echo $this->element('searchs/evaluation_search_panel', array('currentUser'=>$currentUser));

?>
</div>
	</td>
  </tr>
</table>
