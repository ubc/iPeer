<?php echo $this->renderElement('evaltools/tools_menu', array());?>

<form id="searchForm" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="white">
<tr><td align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select">
          <option value="name" >Name</option>
          <!--<option value="owner" >Owner</option>-->
          <option value="lom_max" >LOM</option>
          <option value="criteria" >Criteria</option>
		  <option value="total_marks" >Total Mark</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch" size="30">
      </td>
      <td width="80%" align="right">
      <?php if (!empty($access['MIX_EVAL_RECORD'])):?>
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Rubric', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Rubric', '/rubrics/add'); ?>
      <?php endif;?>
      | Show Only My Rubric Evaluation Tool(s)? <input type="checkbox" name="show_my_tool" checked />
      | Show <select name="show"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="all">all</option>
      </td>
    </tr>
    </table>
<a name="list"></a>
<div id='rubric_table'>
    <?php
    $params = array('controller'=>'rubrics', 'data'=>$data);
    echo $this->renderElement('rubrics/ajax_rubric_list', $params);
    ?>
</div>
</td></tr></table>
</form>

<?php echo $ajax->observeForm('searchForm', array('update'=>'rubric_table', 'url'=>"/rubrics/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>
