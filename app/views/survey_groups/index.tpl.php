<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select" id="select2">
          <option value="set_description" >Survey Group Set</option>
          <option value="num_groups" >Number in Groups</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30">
      </td>
      <td width="180%" align="right">
        <?php if (!empty($access['SURVEY'])) {   ?>
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Survey', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Survey Group Set', '/surveygroups/makegroups'); ?>
        <?php }?>
      </td>
    </tr>
  </table>

  <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_groupset_table', 'url'=>"/surveygroups/listgroupssearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>  </form>

<a name="list"></a>
<div id='survey_groupset_table'>
    <?php
    $params = array('controller'=>'surveygroups', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('survey_groups/ajax_survey_group_list', $params);
    ?>
</div>
</td>
</tr>
</table>