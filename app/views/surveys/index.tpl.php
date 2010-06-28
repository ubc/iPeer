<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select" id="select2">
          <option value="Survey.title">Survey</option>
          <option value="Course.course">Course</option>
          <option value="Creator.name">Creator</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30"></td>
      <td width="80%" align="right"> Show Only My Team Making Tool(s)? <input type="checkbox" name="show_my_tool" checked />	</td>
    </tr>
    <?php if (!empty($access['SURVEY'])) {   ?>
    <tr>
      <td width="10" height="32"></td>
      <td colspan="4" align="right"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Survey', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Survey', '/surveys/add/'); ?></td>
    </tr>
    <?php } ?>

  </table>

  <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_table', 'url'=>"/surveys/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>  </form>

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
