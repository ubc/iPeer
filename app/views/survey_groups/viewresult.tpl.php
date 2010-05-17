<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
  <table width="95%" border="0" cellspacing="0" cellpadding="2">

    <tr><td colspan="4" align="left"><b>Survey:</b> <select name="survey_select">
    <?php
      for ($i=0; $i < count($data)-1; $i++) {
        echo '<option value="'.$data[$i]['Survey']['id'].'">'.$data[$i]['Survey']['name'].'</option>';
      }
    ?>
    </select></td></tr>
    <tr>
      <td width="10" height="32" align="left"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
      <td width="35" align="left"> <b>Search:</b> </td>
      <td width="35" align="left"><select name="select" id="select2">
          <option value="user">User</option>
      </select></td>
      <td width="35" align="left"><input type="text" name="livesearch2" id="livesearch" size="30">
      </td>
      <td width="100%"></td>
    </tr>
  </table>

  <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_result_table', 'url'=>"viewresultsearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
<a name="list"></a>
<div id='survey_result_table'>
    <?php
    $params = array('controller'=>'surveygroups', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('survey_groups/ajax_survey_result_list', $params);
    ?>
</div>
</td>
</tr>
</table>