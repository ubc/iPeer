<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>__('Magnify Icon', true)));?></td>
    <td width="35"> <b><?php __('Search:')?></b> </td>
    <td width="35"><select name="select" id="select2">
      <option value="title" ><?php __('Title')?></option>
    </select></td>
    <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30"></td>
    <td width="80%" align="right">&nbsp;</td>
  </tr>
</table>
</form>
<?php
echo $ajax->observeForm('searchForm', array('update'=>'eval_table', 'url'=>"/evaluations/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))
?>
<a name="list"></a>
<div id='eval_table'>
    <?php
    $params = array('controller'=>'evaluations', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->element('evaluations/ajax_evaluation_list', $params);
    ?>
</div>
</td></tr>
<tr><td align="center">
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
	  <td>
      <?php
          if (!empty($rdAuth->courseId)) {
            echo $html->link(__('Back to Course Home', true), '/courses/home/'.$rdAuth->courseId);
          }
    ?>
    </td>
  </tr>
</table>
</td></tr>
</table>
