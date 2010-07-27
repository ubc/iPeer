<?php echo $this->renderElement('evaltools/tools_menu', array());?>

<form id="searchForm" action="">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select" id="select2">
          <option value="Survey.name">Survey</option>
          <option value="Course.course">Course</option>
          <option value="Creator.name">Creator</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30"></td>
      <td width="80%" align="right"> 
        <?php if (!empty($access['SURVEY'])): ?>
          <?php echo $html->image('icons/add.gif', array('alt'=>'Add Survey', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Survey', '/surveys/add/'); ?>
        <?php endif; ?>
       | Show Only My Team Making Tool(s)? <input type="checkbox" name="show_my_tool" checked />	
       | Show <select name="show"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="all">all</option>
      </td>
    </tr>
  </table>

<a name="list"></a>
<div id='survey_table'>
    <?php
    $params = array('controller'=>'surveys', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('surveys/ajax_survey_list', $params);
    ?>
</div>
</td>
</tr>
</table>
</form>

<?php echo $ajax->observeForm('searchForm', array('update'=>'survey_table', 'url'=>"/surveys/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>  
