<?php echo $this->renderElement('evaltools/tools_menu', array());?>

<form id="searchForm" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr><td align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select" id="select">
          <option value="name" >Name</option>
		  <option value="total_marks" >Total Marks</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch" id="livesearch" size="30">
      </td>
      <td width="80%" align="right">
      <?php if (!empty($access['MIX_EVAL_RECORD'])):?>
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Mix Evaluation', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Mix Evaluation', '/mixevals/add'); ?>
      <?php endif;?>
	      | Show Only My Mixeval Evaluation Tool(s)? <input type="checkbox" name="show_my_tool" checked />
        | Show <select name="show"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="all">all</option>
      </td>
    </tr>
  </table>
<a name="list"></a>
<div id='mixeval_table'>
    <?php
    $params = array('controller'=>'mixevals', 'data'=>$data);
    echo $this->renderElement('mixevals/ajax_mixeval_list', $params);
    ?>
</div>
</td></tr></table>
</form>

<?php echo $ajax->observeForm('searchForm', array('update'=>'mixeval_table', 'url'=>"/mixevals/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>
