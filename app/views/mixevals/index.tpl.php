<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr><td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select" id="select">
          <option value="name" >Name</option>
          <option value="owner" >Owner</option>
		      <option value="lom_max" >LOM</option>
          <option value="criteria" >Criteria</option>
		  <option value="total_mark" >Total Mark</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch" id="livesearch" size="30">
      </td>
      <td width="80%" align="right">
	       Show Only My Mixeval Evaluation Tool(s)? <input type="checkbox" name="show_my_tool" checked />
      </td>
    </tr>
    <?php if (!empty($access['MIX_EVAL_RECORD'])) {   ?>
      <tr>
        <td width="10" height="32"></td>
        <td colspan="4" align="right"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Mix Evaluation', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Mix Evaluation', '/mixevals/add'); ?></td>
      </tr>
    <?php }?>

  </table>
</form>
<?php echo $ajax->observeForm('searchForm', array('update'=>'mixeval_table', 'url'=>"/mixevals/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>
<a name="list"></a>
<div id='mixeval_table'>
    <?php
    $params = array('controller'=>'mixevals', 'data'=>$data);
    echo $this->renderElement('mixevals/ajax_mixeval_list', $params);
    ?>
</div>
</td></tr></table>