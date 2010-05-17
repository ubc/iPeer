<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35"> <b>Search:</b> </td>
      <td width="35"><select name="select">
          <option value="course" >Course</option>
          <option value="title" >Title</option>
      </select></td>
      <td width="35"><input type="text" name="livesearch" size="30">
      </td>
      <td width="180%" align="right">
        <?php if (!empty($access['COURSE_RECORD'])) {   ?>
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Course', 'align'=>'middle')); ?>&nbsp;<?php echo $html->linkTo('Add Course', '/courses/add'); ?>
        <?php }?>
      </td>
    </tr>
  </table>

</form>
<?php echo $ajax->observeForm('searchForm', array('update'=>'course_table', 'url'=>"/courses/search", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>

<a name="list"></a>
<div id='course_table'>
    <?php
    $params = array('controller'=>'courses', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('courses/ajax_course_list', $params);
    ?>
</div>
</td>
</tr>
</table>