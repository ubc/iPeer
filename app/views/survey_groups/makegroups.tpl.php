<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">

    <tr><td colspan="4"><b>Survey:</b> <select name="survey_select">
    <?php
      for ($i=0; $i < count($data); $i++) {
        echo '<option value="'.$data[$i]['Survey']['id'].'">'.$data[$i]['Survey']['name'].'</option>';
      }
    ?>
    </select></td></tr>

  </table>

  <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_makegroups_table', 'url'=>"makegroupssearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
<a name="list"></a>
<div id='survey_makegroups_table'>
    <?php
    $params = array('controller'=>'surveygroups', 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('survey_groups/ajax_survey_makegroups', $params);
    ?>
</div>
</td>
</tr>
</table>