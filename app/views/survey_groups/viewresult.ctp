<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<?php echo $this->Form->create(false, array('id' => 'searchForm'))?>
  <table width="95%" border="0" cellspacing="0" cellpadding="2">

    <tr><td colspan="4" align="left"><b><?php __('Survey')?>:</b> <?php echo $this->Form->select('survey_select', $survey_list, null, array('empty' => false))?></td></tr>
    <tr>
      <td width="10" height="32" align="left"><?php echo $html->image('magnify.png', array('alt'=>__('Magnify Icon', true)));?></td>
      <td width="35" align="left"> <b><?php __('Search')?>:</b> </td>
      <td width="35" align="left"><?php echo $this->Form->select('select',
                                                                 array('user' => 'User'),
                                                                 null, 
                                                                 array('empty' => false));?></td>
      <td width="35" align="left"><?php echo $this->Form->input('livesearch2',
                                                                array('size' => 30,
                                                                      'label' => false))?>
      </td>
      <td width="100%"></td>
    </tr>
  </table>

  <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_result_table', 'url'=>"viewresultsearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
<a name="list"></a>
<div id='survey_result_table'>
    <?php
    $params = array('controller'=>'surveygroups', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->element('survey_groups/ajax_survey_result_list', $params);
    ?>
</div>
</td>
</tr>
</table>
